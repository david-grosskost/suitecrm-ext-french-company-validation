<?php

declare(strict_types=1);

namespace SuiteCrmExt\FrenchCompanyValidation;

/**
 * Before an account is saved, whatever saves it (record view, import, API,
 * workflow): the SIRET and the NAF code cleaned, then refused when wrong.
 * The record view checks the
 * same while typing (backend/Process/FieldCheckHandler): this is the last
 * line, for every other way in.
 */
final class AccountCheck
{
    /** Hook before_save of Accounts (legacy/Extension/…/LogicHooks/french-company-validation.php). */
    public function beforeSave(\SugarBean $bean, string $event, mixed $arguments = null): void
    {
        $siret = Siret::clean(self::text($bean, 'fr_siret'));
        $naf = NafCode::clean(self::text($bean, 'fr_naf_code'));
        self::write($bean, 'fr_siret', $siret);
        self::write($bean, 'fr_naf_code', $naf);

        $error = self::errors($siret, $naf)[0] ?? null;
        if ($error !== null) {
            $message = \translate($error, 'Accounts');

            throw new \RuntimeException(is_string($message) ? $message : $error);
        }
    }

    /**
     * Label keys of what is wrong in cleaned values, the SIRET first.
     *
     * @return list<string>
     */
    public static function errors(string $siret, string $naf): array
    {
        return array_values(array_filter([Siret::error($siret), NafCode::error($naf)]));
    }

    private static function write(\SugarBean $bean, string $field, string $value): void
    {
        $bean->{$field} = $value;
    }

    private static function text(\SugarBean $bean, string $field): string
    {
        $value = $bean->{$field} ?? '';

        return is_scalar($value) ? (string) $value : '';
    }
}
