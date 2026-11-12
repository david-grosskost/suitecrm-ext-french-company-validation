<?php

declare(strict_types=1);

namespace App\Extension\FrenchCompanyValidation\backend\FieldDefinitions;

/**
 * The checks the record view runs while the SIRET or the NAF code is typed:
 * an asyncValidator of SuiteCRM 8 on each field, which calls the process
 * french-company-validation-check (backend/Process/FieldCheckHandler).
 * Pure function, tested without SuiteCRM; AddFieldChecks applies it.
 */
final class FieldChecks
{
    /** @var array<string, string> Field => what FieldCheckHandler checks */
    public const array FIELDS = ['fr_siret' => 'siret', 'fr_naf_code' => 'naf'];

    /**
     * The field definitions of the accounts with the checks: only on fields
     * that are there (french-company-fields active), other checks kept.
     *
     * @param array<array-key, mixed> $vardefs
     *
     * @return array<array-key, mixed>
     */
    public static function add(array $vardefs): array
    {
        foreach (self::FIELDS as $field => $check) {
            $definition = $vardefs[$field] ?? null;
            if (!is_array($definition)) {
                continue;
            }
            $validators = is_array($definition['asyncValidators'] ?? null) ? $definition['asyncValidators'] : [];
            $validators['french-company-validation'] = [
                'key' => 'french-company-validation-check',
                'params' => ['check' => $check],
            ];
            $definition['asyncValidators'] = $validators;
            $vardefs[$field] = $definition;
        }

        return $vardefs;
    }
}
