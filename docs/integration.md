# Integration recipes

The library is framework-agnostic: static entry points, no container, no global state, no
I/O besides `Dsv::parseFile()`. Everything below is plain PHP around the same few calls.

- [Choosing the right call](#choosing-the-right-call)
- [Laravel](#laravel)
- [Symfony](#symfony)
- [PSR-7 / PSR-15 / Slim / Mezzio](#psr-7--psr-15--slim--mezzio)
- [Queue worker / background import](#queue-worker--background-import)
- [Import into a database](#import-into-a-database)
- [Export: JSON, CSV, download](#export-json-csv-download)
- [Create a file for download](#create-a-file-for-download)
- [Validation in CI and pre-commit](#validation-in-ci-and-pre-commit)
- [Dependency injection and testing](#dependency-injection-and-testing)
- [Security and robustness checklist](#security-and-robustness-checklist)

## Choosing the right call

| I want to … | Use |
| --- | --- |
| accept a file of unknown kind from a user | `Dsv::parseAny($bytes)` |
| read a file whose type I know | `Dsv::parseList($bytes, ListType::…)` |
| read from disk | `Dsv::parseFile($path)` |
| work with sections → events → starts | `Dsv::projectResult()` / `Dsv::projectDefinition()` |
| forward / patch a file without changing a byte | `Dsv::parse()` + `Dsv::write()` |
| produce a new file | `Dsv::writeList()` |
| save an edited *real* file | `Dsv::writeListPreservingDefects()` |

A helper that turns diagnostics into something your application understands is worth having
once; the recipes below reuse this one:

```php
use SteffenR\Dsv\Diagnostic\Diagnostic;

/** @param list<Diagnostic> $diagnostics */
function diagnosticsToArray(array $diagnostics): array
{
    return array_map(fn (Diagnostic $d) => [
        'code'     => $d->code->value,       // stable, machine readable
        'severity' => $d->severity->value,   // fatal | error | warning | info
        'line'     => $d->line,
        'data'     => $d->data,              // element, field, value, …
        'message'  => $d->message,           // English, for logs only
    ], $diagnostics);
}
```

Translate for users by `code` (and `data`), never by `message`.

## Laravel

### Upload endpoint

```php
// routes/api.php
Route::post('/meets/import', ImportMeetController::class);

// app/Http/Controllers/ImportMeetController.php
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SteffenR\Dsv\Dsv;
use SteffenR\Dsv\Schema\ListType;

final class ImportMeetController
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate(['file' => ['required', 'file', 'max:5120']]);   // KB; DSV files are small

        $result = Dsv::parseAny((string) $request->file('file')?->get());

        if ($result->document->listType !== ListType::Wettkampfergebnisliste->value) {
            return response()->json(['message' => 'Please upload a result list (Wettkampfergebnisliste).'], 422);
        }

        if (!$result->ok) {
            return response()->json([
                'message' => 'The file has errors.',
                'errors'  => diagnosticsToArray($result->errors()),
            ], 422);
        }

        $graph = Dsv::projectResult($result->document)->graph;

        // … persist (see "Import into a database")

        return response()->json([
            'title'    => $graph->veranstaltung->bezeichnung,
            'events'   => count($graph->wettkampfByKey),
            'swimmers' => count($graph->personById),
            'warnings' => diagnosticsToArray(array_filter(
                $result->diagnostics,
                fn ($d) => $d->severity->value === 'warning',
            )),
        ]);
    }
}
```

### Validation rule

```php
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use SteffenR\Dsv\Dsv;

final class ValidDsvFile implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value instanceof UploadedFile) {
            $fail('The :attribute must be a file.');
            return;
        }

        $result = Dsv::parseAny((string) $value->get());

        foreach ($result->errors() as $d) {
            // Only the first few; line numbers help the uploader fix the export.
            $fail("Line {$d->line}: {$d->code->value}");
        }
    }
}

// $request->validate(['file' => ['required', 'file', new ValidDsvFile]]);
```

### Artisan command

```php
use Illuminate\Console\Command;
use SteffenR\Dsv\Dsv;

final class DsvCheck extends Command
{
    protected $signature = 'dsv:check {path}';

    public function handle(): int
    {
        $result = Dsv::parseFile($this->argument('path'));

        foreach ($result->diagnostics as $d) {
            $this->line((string) $d);
        }

        return $result->ok ? self::SUCCESS : self::FAILURE;
    }
}
```

Nothing needs registering in a service provider. If you prefer injection, see
[Dependency injection and testing](#dependency-injection-and-testing).

## Symfony

```php
use SteffenR\Dsv\Dsv;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DsvController extends AbstractController
{
    #[Route('/dsv/validate', methods: ['POST'])]
    public function validate(Request $request): JsonResponse
    {
        $file = $request->files->get('file');
        if (!$file instanceof UploadedFile || !$file->isValid()) {
            return $this->json(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }

        $result = Dsv::parseAny((string) file_get_contents($file->getPathname()));

        return $this->json(
            [
                'listType' => $result->document->listType,
                'version'  => $result->document->version->value,
                'ok'       => $result->ok,
                'findings' => diagnosticsToArray($result->diagnostics),
            ],
            $result->ok ? Response::HTTP_OK : Response::HTTP_UNPROCESSABLE_ENTITY,
        );
    }
}
```

### As a form constraint

```php
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[Assert\Callback]
public function validateDsv(ExecutionContextInterface $context): void
{
    foreach (Dsv::parseAny($this->contents)->errors() as $d) {
        $context->buildViolation('Line {{ line }}: {{ code }}')
            ->setParameter('{{ line }}', (string) $d->line)
            ->setParameter('{{ code }}', $d->code->value)
            ->atPath('file')
            ->addViolation();
    }
}
```

### Service autowiring

The library is static. If you want to inject something, wrap it (see
[Dependency injection and testing](#dependency-injection-and-testing)) and register the
wrapper as a normal autowired service.

## PSR-7 / PSR-15 / Slim / Mezzio

```php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SteffenR\Dsv\Dsv;

final class ImportHandler implements RequestHandlerInterface
{
    public function __construct(private readonly \Psr\Http\Message\ResponseFactoryInterface $responses) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $upload = $request->getUploadedFiles()['file'] ?? null;
        if ($upload === null || $upload->getError() !== UPLOAD_ERR_OK) {
            return $this->json(400, ['error' => 'upload failed']);
        }

        $result = Dsv::parseAny((string) $upload->getStream());

        return $this->json($result->ok ? 200 : 422, [
            'ok'       => $result->ok,
            'findings' => diagnosticsToArray($result->diagnostics),
        ]);
    }

    /** @param array<string, mixed> $body */
    private function json(int $status, array $body): ResponseInterface
    {
        $response = $this->responses->createResponse($status)->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($body, JSON_THROW_ON_ERROR));

        return $response;
    }
}
```

## Queue worker / background import

Parse in the request to give instant feedback, store the *original bytes*, and process the
heavy part in a job. Because `Dsv::parse` + `Dsv::write` is lossless, the stored copy
is the exact file the user uploaded:

```php
// in the request
$bytes = (string) $upload->getContent();
$result = Dsv::parseAny($bytes);
abort_unless($result->ok, 422, 'invalid file');
$path = Storage::put('dsv/' . Str::uuid() . '.dsv', $bytes);
ImportMeet::dispatch($path);

// in the job
public function handle(): void
{
    $result = Dsv::parseAny(Storage::get($this->path));
    $graph  = Dsv::projectResult($result->document)->graph;
    // … import
}
```

The parse result contains plain readonly objects, so it can be cached; the graph nodes are
mutable data holders — serialize the *input bytes*, not the graph.

## Import into a database

A minimal, framework-free import with PDO. Swimmers are identified by the `dsvId` where
present (the DSV id), otherwise by name and year of birth:

```php
use SteffenR\Dsv\Dsv;
use SteffenR\Dsv\Values\Zeit;

$result = Dsv::parseAny($bytes);
$graph  = Dsv::projectResult($result->document)->graph;

$pdo->beginTransaction();
$insert = $pdo->prepare(
    'INSERT INTO results (meet, event_no, kind, distance, stroke, dsv_id, name, birth_year, club, place, hundredths)
     VALUES (:meet, :event_no, :kind, :distance, :stroke, :dsv_id, :name, :birth_year, :club, :place, :hundredths)',
);

foreach ($graph->wettkampfByKey as $event) {
    foreach ($event->starts as $start) {
        foreach ($start->platzierungen as $placement) {
            $insert->execute([
                'meet'       => $graph->veranstaltung->bezeichnung,
                'event_no'   => $event->nummer,
                'kind'       => $event->art,
                'distance'   => $event->einzelstrecke,
                'stroke'     => $event->technik,
                'dsv_id'     => $start->dsvId !== '' ? $start->dsvId : null,
                'name'       => $start->name,
                'birth_year' => $start->jahrgang !== '' ? (int) $start->jahrgang : null,
                'club'       => $start->verein,
                'place'      => $placement->platz,          // 0 = not ranked
                'hundredths' => $start->endzeit,            // null: no/invalid time
            ]);
        }
    }
}
$pdo->commit();
```

Things worth knowing when importing:

- An event is `(nummer, art)`; heat, final, tie-break swim-off share a number.
- A start can have **several placements** — one per ranking group (`wertungsId`).
- `platz === 0` with a `grundDerNichtwertung` means *not ranked* (disqualified, did not
  start, …); `endzeit` may then be `00:00:00,00` (= `0`).
- `endzeit === null` means the field was empty or malformed, `0` means the specified "no time".
- Relay swimmers are in `$event->staffeln[]->personen`, not in `starts`.
- `Person` (`$graph->personById`) groups all starts of one swimmer in the meet.

## Export: JSON, CSV, download

### JSON

All records, schema-free of the object graph (good for generic tooling):

```php
$list = Dsv::parseAny($bytes)->document;

echo json_encode(array_map(
    fn ($r) => ['element' => $r->element, 'values' => $r->values],
    $list->records,
), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
```

or `vendor/bin/dsv json file.dsv7`.

### CSV

See [`examples/05-export-csv.php`](../examples/05-export-csv.php). Note `fputcsv()`'s
`escape` argument: pass `''` on PHP 8.4+ to opt out of the deprecated legacy behaviour.

### Download of an original file

```php
return response($bytes, 200, [
    'Content-Type'        => 'text/plain; charset=utf-8',
    'Content-Disposition' => 'attachment; filename="results.dsv7"',
]);
```

## Create a file for download

```php
use SteffenR\Dsv\Dsv;
use SteffenR\Dsv\Exception\DsvWriteException;
use SteffenR\Dsv\Format\FormatVersion;
use SteffenR\Dsv\Schema\ListType;
use SteffenR\Dsv\Write\WriteOptions;

try {
    $text = Dsv::writeList($records, ListType::Vereinsmeldeliste, new WriteOptions(version: FormatVersion::V8));
} catch (DsvWriteException $e) {
    // Your data would produce an invalid file. Fix the data, don't hide the error:
    report($e);                       // exception message has the first finding
    $problems = diagnosticsToArray($e->diagnostics);
    // …
}

return response($text, 200, [
    'Content-Type'        => 'text/plain; charset=utf-8',
    'Content-Disposition' => 'attachment; filename="2027-03-15-Musterstadt-SVMuster-Me.dsv8"',
]);
```

- Output is UTF-8 **without BOM**, as the standard requires.
- Default line ending is CRLF (what EasyWk and friends write). Pass `new WriteOptions(eol: "\n")`
  for LF.
- The file name convention is `YYYY-MM-DD-Ort-Verein-Art.dsv7|dsv8`; the library does not
  generate names.

## Validation in CI and pre-commit

GitHub Actions:

```yaml
- uses: shivammathur/setup-php@v2
  with: { php-version: '8.4' }
- run: composer require --dev steffenr/dsv-php
- run: vendor/bin/dsv validate data/*.dsv7 data/*.dsv8 --quiet
```

`--strict` makes warnings fail as well, `--quiet` prints one summary line per file. Exit code
`1` means at least one file is not valid.

Pre-commit hook (`.git/hooks/pre-commit`):

```bash
#!/bin/sh
files=$(git diff --cached --name-only --diff-filter=ACM | grep -E '\.dsv[78]$')
[ -z "$files" ] || vendor/bin/dsv validate $files
```

## Dependency injection and testing

The entry points are static. If your application wants an injectable seam (for tests or
swapping behaviour), wrap them:

```php
interface DsvReader
{
    public function read(string $bytes): ParseResult;
}

final class LibraryDsvReader implements DsvReader
{
    public function read(string $bytes): ParseResult
    {
        return Dsv::parseAny($bytes);
    }
}
```

For tests, don't mock the library: feed it small strings. A valid minimal file is easy to
create (see `tests/ValidatorTest.php` for a header you can copy), and the parser is fast
(about 0.2 s for 1 MB).

## Security and robustness checklist

- **Limit the upload size** in your framework (a 5 MB limit is generous for DSV files). The
  library reads the whole input into memory; measured on a 0.94 MB result list, parsing
  plus typed records plus object graph peak at about 55 MB (the PHP memory limit of your
  worker must allow for roughly 50× the file size).
- **Never trust the file name or extension**; use `Dsv::parseAny()` (reads `FORMAT`).
- **Escape on output.** Names and other fields are user data; HTML-escape when rendering.
- **Treat the content as personal data.** DSV files contain names and years of birth, often of
  children (GDPR). Don't put real files into logs, issues or public fixtures; log
  diagnostic `code`s and `line`s instead.
- **Comment lines** `(* … *)` are kept and written back as is by the schema-free level; the
  typed writer drops them.
- The parser is linear-time on pathological lines (tested with 150 000 comment markers).
- **Don't concatenate parsed values into file paths or shell commands.**
- Wrap parse calls in a try/catch for `\SteffenR\Dsv\Exception\DsvException` only where
  you use the throwing functions (`parseFile`, `parseOrThrow`, the writers).
