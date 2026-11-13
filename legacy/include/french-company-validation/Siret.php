<?php

declare(strict_types=1);

namespace SuiteCrmExt\FrenchCompanyValidation;

/**
 * The SIRET number of an establishment: 14 digits, the SIREN of the company
 * (9) then the NIC of the establishment (5), with a Luhn key. Pure functions,
 * tested without SuiteCRM.
 */
final class Siret
{
    /** SIREN of La Poste: its establishments follow another rule (sum of digits). */
    private const string LA_POSTE = '356000000';

    /**
     * The number as typed or imported, without what is only presentation:
     * spaces (also non-breaking), dots and hyphens ("123 456 789 00011").
     */
    public static function clean(string $siret): string
    {
        return preg_replace('/[\s.\-\x{00A0}\x{202F}]+/u', '', $siret) ?? $siret;
    }

    /**
     * Why a cleaned number is refused, as a label key (Accounts language
     * files); null when it is accepted. Empty is accepted: the SIRET is not
     * mandatory.
     */
    public static function error(string $siret): ?string
    {
        if ($siret === '') {
            return null;
        }
        if (preg_match('/^\d{14}$/', $siret) !== 1) {
            return 'LBL_FCV_SIRET_FORMAT';
        }

        return self::hasValidKey($siret) ? null : 'LBL_FCV_SIRET_KEY';
    }

    /** The Luhn key of 14 digits, or the rule of La Poste for its establishments. */
    public static function hasValidKey(string $siret): bool
    {
        if (str_starts_with($siret, self::LA_POSTE)) {
            // A loop, not array_map: refused by the scanner of the Module Loader.
            $sum = 0;
            foreach (str_split($siret) as $digit) {
                $sum += (int) $digit;
            }

            return $sum % 5 === 0;
        }

        $sum = 0;
        foreach (array_reverse(str_split($siret)) as $position => $digit) {
            $value = (int) $digit * ($position % 2 === 1 ? 2 : 1);
            $sum += $value > 9 ? $value - 9 : $value;
        }

        return $sum % 10 === 0;
    }
}
