<?php

declare(strict_types=1);

namespace SuiteCrmExt\FrenchCompanyValidation\Tests;

use App\Extension\FrenchCompanyValidation\backend\FieldDefinitions\FieldChecks;
use PHPUnit\Framework\TestCase;

final class FieldChecksTest extends TestCase
{
    public function testTheChecksAreAddedToTheFieldsOfFrenchCompanyFields(): void
    {
        $vardefs = ['name' => ['name' => 'name'], 'fr_siret' => ['name' => 'fr_siret'], 'fr_naf_code' => ['name' => 'fr_naf_code']];

        self::assertSame(
            [
                'name' => ['name' => 'name'],
                'fr_siret' => ['name' => 'fr_siret', 'asyncValidators' => ['french-company-validation' => ['key' => 'french-company-validation-check', 'params' => ['check' => 'siret']]]],
                'fr_naf_code' => ['name' => 'fr_naf_code', 'asyncValidators' => ['french-company-validation' => ['key' => 'french-company-validation-check', 'params' => ['check' => 'naf']]]],
            ],
            FieldChecks::add($vardefs),
        );
    }

    public function testNothingIsAddedWithoutFrenchCompanyFields(): void
    {
        $vardefs = ['name' => ['name' => 'name']];

        self::assertSame($vardefs, FieldChecks::add($vardefs), 'pas de champ à moitié défini');
    }

    public function testTheChecksOfOtherExtensionsStay(): void
    {
        $other = ['other' => ['key' => 'other-check']];
        $checked = FieldChecks::add(['fr_siret' => ['name' => 'fr_siret', 'asyncValidators' => $other]]);

        self::assertIsArray($checked['fr_siret']);
        self::assertIsArray($checked['fr_siret']['asyncValidators']);
        self::assertSame(['other', 'french-company-validation'], array_keys($checked['fr_siret']['asyncValidators']));
    }
}
