<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Diagnostic;

/**
 * Stable machine-readable identifier of a finding. Switch on this, never on the
 * message text.
 */
enum DiagnosticCode: string
{
    case MissingFormatElement = 'missing-format-element';
    case MissingDateiendeElement = 'missing-dateiende-element';
    case FormatNotFirstElement = 'format-not-first-element';
    case ElementOrderViolation = 'element-order-violation';
    case UnknownEncodingReplacementCharacter = 'unknown-encoding-replacement-character';
    case UnexpectedBom = 'unexpected-bom';
    case UnterminatedFieldList = 'unterminated-field-list';
    case UnexpectedFieldCount = 'unexpected-field-count';
    case UnknownElement = 'unknown-element';
    case MissingRequiredField = 'missing-required-field';
    case InvalidValue = 'invalid-value';
    case InvalidEnumValue = 'invalid-enum-value';
    case CardinalityViolation = 'cardinality-violation';
    case MutuallyExclusiveElements = 'mutually-exclusive-elements';
    case ConditionalFieldRequired = 'conditional-field-required';
    case UnsupportedFormatVersion = 'unsupported-format-version';
    case WrongListType = 'wrong-list-type';
    case DanglingReference = 'dangling-reference';
    case AmbiguousReference = 'ambiguous-reference';
    case IncompleteRelay = 'incomplete-relay';
    case EmptyInput = 'empty-input';
}
