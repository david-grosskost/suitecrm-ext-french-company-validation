<?php

declare(strict_types=1);

namespace SuiteCrmExt\FrenchCompanyValidation;

// SuiteCRM loads only this file for the hook: the rules it applies come along.
require_once __DIR__ . '/Siret.php';
require_once __DIR__ . '/NafCode.php';

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
        // A loop, not array_filter: the scanner of the Module Loader refuses
        // callbacks (array_filter, array_map…), for every package.
        $errors = [];
        foreach ([Siret::error($siret), NafCode::error($naf)] as $error) {
            if ($error !== null) {
                $errors[] = $error;
            }
        }

        return $errors;
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
