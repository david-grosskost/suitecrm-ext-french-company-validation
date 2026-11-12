<?php

// Contrôles des entreprises françaises (extension french-company-validation):
// the record view checks the SIRET and the NAF code while they are typed, by
// asking SuiteCRM 8 (process french-company-validation-check,
// backend/Process/FieldCheckHandler). Added to the fields of french-company-
// fields, merged before this file (alphabetical order): without them, nothing
// is added. Its variables are prefixed and unset: Quick Repair merges every
// extension's file into one.

/** @var array<string, array<string, array<string, array<mixed>>>> $dictionary Set by SuiteCRM */
$svFields = $dictionary['Account']['fields'] ?? [];

foreach (['fr_siret' => 'siret', 'fr_naf_code' => 'naf'] as $svField => $svCheck) {
    if (!isset($svFields[$svField])) {
        continue;
    }
    $svDefinition = $svFields[$svField];
    $svValidators = is_array($svDefinition['asyncValidators'] ?? null) ? $svDefinition['asyncValidators'] : [];
    $svValidators['french-company-validation'] = ['key' => 'french-company-validation-check', 'params' => ['check' => $svCheck]];
    $svDefinition['asyncValidators'] = $svValidators;
    $dictionary['Account']['fields'][$svField] = $svDefinition;
}
unset($svFields, $svField, $svCheck, $svDefinition, $svValidators);
