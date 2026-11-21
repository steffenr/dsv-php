<?php

declare(strict_types=1);

namespace SteffenR\Dsv\Exception;

use RuntimeException;

/** Thrown when a file cannot be read. */
final class DsvFileException extends RuntimeException implements DsvException {}
