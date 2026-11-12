<?php

declare(strict_types=1);

namespace SuiteCrmExt\FrenchCompanyValidation;

/**
 * The NAF code (APE) of the main activity: 2 digits, a dot, 2 digits and a
 * letter (62.01Z). Pure functions, tested without SuiteCRM.
 */
final class NafCode
{
    /**
     * The code as typed, in its usual form: no spaces, capital letter, the
     * dot added when missing ("6201z" → "62.01Z").
     */
    public static function clean(string $code): string
    {
        $code = strtoupper(preg_replace('/\s+/u', '', $code) ?? $code);

        return preg_match('/^(\d{2})(\d{2}[A-Z])$/', $code, $parts) === 1 ? "{$parts[1]}.{$parts[2]}" : $code;
    }

    /** Why a cleaned code is refused (label key), null when accepted; empty is accepted. */
    public static function error(string $code): ?string
    {
        if ($code === '') {
            return null;
        }

        return preg_match('/^\d{2}\.\d{2}[A-Z]$/', $code) === 1 ? null : 'LBL_FCV_NAF_FORMAT';
    }
}
