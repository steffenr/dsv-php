#!/usr/bin/env bash
#
# Computes the next version from Conventional Commits and rewrites CHANGELOG.md.
#
# Usage: prepare-release.sh <auto|patch|minor|major|X.Y.Z>
#
# Writes the release notes to $NOTES_FILE (default: release-notes.md) and prints
# the new version (without "v") as the last line of stdout.
#
# - First release (no v*.*.* tag yet): the hand-written "Unreleased" section is
#   used as is, auto bump yields 0.1.0.
# - Later releases: entries are generated from the commits since the last tag
#   (feat -> Added, fix -> Fixed, perf/refactor -> Changed). Hand-written
#   "Unreleased" entries are kept and come first. docs/test/chore/ci/build/style
#   commits are not listed.
# - Breaking changes ("type!:" or "BREAKING CHANGE:") bump major, or minor while
#   the major version is 0.

set -euo pipefail

bump="${1:-auto}"
notes_file="${NOTES_FILE:-release-notes.md}"
changelog="${CHANGELOG:-CHANGELOG.md}"
date_today="$(date -u +%Y-%m-%d)"

last_tag="$(git describe --tags --abbrev=0 --match 'v[0-9]*.[0-9]*.[0-9]*' 2>/dev/null || true)"

# --- hand-written Unreleased section ----------------------------------------
manual="$(awk '
  /^## \[Unreleased\]/ { in_section = 1; next }
  in_section && /^## \[/ { exit }
  in_section { print }
' "$changelog" | sed -e '/./,$!d' | sed -e :a -e '/^\n*$/{$d;N;ba' -e '}')"

# --- commits since the last tag ----------------------------------------------
added=""
changed=""
fixed=""
breaking=0
has_feat=0
has_commits=0

if [ -n "$last_tag" ]; then
  while IFS= read -r -d $'\x1e' record; do
    record="${record#$'\n'}"
    [ -z "$record" ] && continue
    subject="${record%%$'\x1f'*}"
    body="${record#*$'\x1f'}"

    case "$subject" in
      "chore(release)"*) continue ;;
    esac
    has_commits=1

    type="$(printf '%s' "$subject" | sed -nE 's/^([a-z]+)(\([^)]*\))?(!)?:.*/\1/p')"
    bang="$(printf '%s' "$subject" | sed -nE 's/^[a-z]+(\([^)]*\))?(!):.*/!/p')"
    scope="$(printf '%s' "$subject" | sed -nE 's/^[a-z]+\(([^)]*)\)!?:.*/\1/p')"
    text="$(printf '%s' "$subject" | sed -E 's/^[a-z]+(\([^)]*\))?!?: *//')"
    text="$(printf '%s' "${text:0:1}" | tr '[:lower:]' '[:upper:]')${text:1}"
    [ -n "$scope" ] && text="**${scope}:** ${text}"

    if [ -n "$bang" ] || printf '%s' "$body" | grep -qE '^BREAKING[ -]CHANGE:'; then
      breaking=1
      text="${text} **(breaking)**"
    fi

    case "$type" in
      feat) has_feat=1; added+="- ${text}"$'\n' ;;
      fix) fixed+="- ${text}"$'\n' ;;
      perf | refactor) changed+="- ${text}"$'\n' ;;
      *) if [ -n "$bang" ]; then changed+="- ${text}"$'\n'; fi ;;
    esac
  done < <(git log --no-merges --format='%s%x1f%b%x1e' "${last_tag}..HEAD")

  if [ "$has_commits" -eq 0 ] && [ -z "$manual" ]; then
    echo "No commits since ${last_tag}; nothing to release." >&2
    exit 1
  fi
fi

# --- next version --------------------------------------------------------------
if [[ "$bump" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
  version="$bump"
else
  base="${last_tag#v}"
  base="${base:-0.0.0}"
  IFS=. read -r major minor patch <<<"$base"

  if [ "$bump" = "auto" ]; then
    if [ -z "$last_tag" ]; then
      bump="first"
    elif [ "$breaking" -eq 1 ]; then
      bump=$([ "$major" -eq 0 ] && echo minor || echo major)
    elif [ "$has_feat" -eq 1 ]; then
      bump=minor
    else
      bump=patch
    fi
  fi

  case "$bump" in
    first) major=0; minor=1; patch=0 ;;
    major) major=$((major + 1)); minor=0; patch=0 ;;
    minor) minor=$((minor + 1)); patch=0 ;;
    patch) patch=$((patch + 1)) ;;
    *) echo "Unknown bump '${bump}' (auto|patch|minor|major|X.Y.Z)" >&2; exit 1 ;;
  esac
  version="${major}.${minor}.${patch}"
fi

if git rev-parse -q --verify "refs/tags/v${version}" >/dev/null; then
  echo "Tag v${version} already exists." >&2
  exit 1
fi

# --- release notes -------------------------------------------------------------
{
  if [ -n "$manual" ]; then
    printf '%s\n\n' "$manual"
  fi
  if [ -n "$added" ]; then printf '### Added\n\n%s\n' "$added"; fi
  if [ -n "$changed" ]; then printf '### Changed\n\n%s\n' "$changed"; fi
  if [ -n "$fixed" ]; then printf '### Fixed\n\n%s\n' "$fixed"; fi
} | sed -e :a -e '/^\n*$/{$d;N;ba' -e '}' >"$notes_file"

if [ ! -s "$notes_file" ]; then
  echo "- Maintenance release." >"$notes_file"
fi

# --- rewrite CHANGELOG.md -------------------------------------------------------
tmp="$(mktemp)"
awk -v version="$version" -v date="$date_today" -v notes="$notes_file" '
  /^## \[Unreleased\]/ {
    print
    print ""
    print "## [" version "] - " date
    print ""
    while ((getline line < notes) > 0) print line
    print ""
    skipping = 1
    next
  }
  skipping && /^## \[/ { skipping = 0 }
  !skipping { print }
' "$changelog" >"$tmp"
mv "$tmp" "$changelog"

echo "$version"
