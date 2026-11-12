<?php

// Contrôles des entreprises françaises (extension french-company-validation):
// cleans the SIRET and the NAF code of an account before it is saved, and
// refuses them when wrong.

$hook_version = 1;

/** @var array<string, list<array{int, string, string, string, string}>> $hook_array Set by SuiteCRM */
$hook_array['before_save'][] = [
    10,
    'french-company-validation : SIRET et code NAF contrôlés',
    'custom/include/french-company-validation/AccountCheck.php',
    'SuiteCrmExt\FrenchCompanyValidation\AccountCheck',
    'beforeSave',
];
