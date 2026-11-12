<?php

declare(strict_types=1);

namespace SuiteCrmExt\FrenchCompanyValidation\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SuiteCrmExt\FrenchCompanyValidation\Siret;

final class SiretTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string}>
     */
    public static function typings(): iterable
    {
        yield 'déjà propre' => ['73282932000074', '73282932000074'];
        yield 'espaces' => ['732 829 320 00074', '73282932000074'];
        yield 'espaces insécables' => ["732\u{00A0}829\u{00A0}320\u{202F}00074", '73282932000074'];
        yield 'points et tirets' => ['732.829.320-00074', '73282932000074'];
        yield 'espaces autour' => ['  73282932000074 ', '73282932000074'];
        yield 'vide' => ['', ''];
    }

    #[DataProvider('typings')]
    public function testThePresentationIsRemoved(string $typed, string $clean): void
    {
        self::assertSame($clean, Siret::clean($typed));
    }

    /**
     * @return iterable<string, array{string, string|null}>
     */
    public static function numbers(): iterable
    {
        yield 'SIRET valide' => ['73282932000074', null];
        yield 'autre SIRET valide' => ['55208131766522', null];
        yield 'vide : pas obligatoire' => ['', null];
        yield 'un chiffre de moins' => ['7328293200007', 'LBL_FCV_SIRET_FORMAT'];
        yield 'un chiffre de plus' => ['732829320000740', 'LBL_FCV_SIRET_FORMAT'];
        yield 'une lettre' => ['7328293200007A', 'LBL_FCV_SIRET_FORMAT'];
        yield 'faute de frappe' => ['73282932000075', 'LBL_FCV_SIRET_KEY'];
        yield 'deux chiffres inversés' => ['73282932000047', 'LBL_FCV_SIRET_KEY'];
    }

    #[DataProvider('numbers')]
    public function testTheNumberIsChecked(string $siret, ?string $error): void
    {
        self::assertSame($error, Siret::error($siret));
    }

    public function testLaPosteFollowsItsOwnRule(): void
    {
        // Establishments of La Poste (SIREN 356000000): sum of the digits a multiple of 5.
        self::assertTrue(Siret::hasValidKey('35600000049837'), 'somme 50, clé de Luhn fausse');
        self::assertFalse(Siret::hasValidKey('35600000049838'));
    }

    public function testTheDemoNumbersAreValid(): void
    {
        // Fr::siret of suitecrm-core: 9, 12 digits, then the key that passes Luhn.
        self::assertNull(Siret::error('90000000000001'));
    }
}
