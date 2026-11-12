<?php

// Contrôles des entreprises françaises (extension french-company-validation):
// messages shown under the field, or when a save is refused.

/** @var array<string, string> $mod_strings Set by SuiteCRM */
$mod_strings['LBL_FCV_SIRET_FORMAT'] = 'The SIRET number must have 14 digits.';
$mod_strings['LBL_FCV_SIRET_KEY'] = 'This SIRET number does not exist: its check digit is wrong (typo?).';
$mod_strings['LBL_FCV_NAF_FORMAT'] = 'A NAF code is 2 digits, a dot, 2 digits and a letter (e.g. 62.01Z).';
