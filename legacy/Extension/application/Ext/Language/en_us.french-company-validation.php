<?php

// Contrôles des entreprises françaises (extension french-company-validation):
// messages shown under the field, or when a save is refused. Application
// strings: the record view looks a validation message up there, not in the
// module's.

/** @var array<string, string> $app_strings Set by SuiteCRM */
$app_strings['LBL_FCV_SIRET_FORMAT'] = 'The SIRET number must have 14 digits.';
$app_strings['LBL_FCV_SIRET_KEY'] = 'This SIRET number does not exist: its check digit is wrong (typo?).';
$app_strings['LBL_FCV_NAF_FORMAT'] = 'A NAF code is 2 digits, a dot, 2 digits and a letter (e.g. 62.01Z).';
