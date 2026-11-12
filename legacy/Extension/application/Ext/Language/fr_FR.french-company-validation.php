<?php

// Contrôles des entreprises françaises (extension french-company-validation):
// messages shown under the field, or when a save is refused. Application
// strings: the record view looks a validation message up there, not in the
// module's.

/** @var array<string, string> $app_strings Set by SuiteCRM */
$app_strings['LBL_FCV_SIRET_FORMAT'] = 'Le SIRET doit compter 14 chiffres.';
$app_strings['LBL_FCV_SIRET_KEY'] = 'Ce SIRET n\'existe pas : sa clé de contrôle est fausse (faute de frappe ?).';
$app_strings['LBL_FCV_NAF_FORMAT'] = 'Le code NAF s\'écrit 2 chiffres, un point, 2 chiffres et une lettre (ex. 62.01Z).';
