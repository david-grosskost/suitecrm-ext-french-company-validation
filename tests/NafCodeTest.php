<?php

declare(strict_types=1);

namespace SuiteCrmExt\FrenchCompanyValidation\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SuiteCrmExt\FrenchCompanyValidation\NafCode;

final class NafCodeTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string, string|null}>
     */
    public static function codes(): iterable
    {
        yield 'déjà propre' => ['62.01Z', '62.01Z', null];
        yield 'sans point' => ['6201Z', '62.01Z', null];
        yield 'minuscule' => ['62.01z', '62.01Z', null];
        yield 'espaces' => [' 62 01 z ', '62.01Z', null];
        yield 'vide : pas obligatoire' => ['', '', null];
        yield 'sans lettre' => ['62.01', '62.01', 'LBL_FCV_NAF_FORMAT'];
        yield 'trop de chiffres' => ['620.1Z', '620.1Z', 'LBL_FCV_NAF_FORMAT'];
        yield 'deux lettres' => ['62.0AZ', '62.0AZ', 'LBL_FCV_NAF_FORMAT'];
    }

    #[DataProvider('codes')]
    public function testTheCodeIsCleanedThenChecked(string $typed, string $clean, ?string $error): void
    {
        self::assertSame($clean, NafCode::clean($typed));
        self::assertSame($error, NafCode::error(NafCode::clean($typed)));
    }
}
