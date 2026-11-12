<?php

declare(strict_types=1);

namespace SuiteCrmExt\FrenchCompanyValidation\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The definitions the extension gives SuiteCRM, read as SuiteCRM reads them:
 * each file included with the array it fills.
 */
final class DefinitionsTest extends TestCase
{
    private const string LEGACY = __DIR__ . '/../legacy/Extension/modules/Accounts/Ext';
    private const array ERRORS = ['LBL_FCV_SIRET_FORMAT', 'LBL_FCV_SIRET_KEY', 'LBL_FCV_NAF_FORMAT'];

    /**
     * The array a definition file fills.
     *
     * @return array<mixed>
     */
    private static function load(string $file, string $variable): array
    {
        $mod_strings = $hook_array = [];
        require self::LEGACY . '/' . $file;
        $values = compact('mod_strings', 'hook_array');

        return is_array($values[$variable] ?? null) ? $values[$variable] : [];
    }

    public function testTheHookRunsBeforeTheSave(): void
    {
        $hooks = self::load('LogicHooks/french-company-validation.php', 'hook_array');

        self::assertIsArray($hooks['before_save'] ?? null);
        self::assertSame(
            [[10, 'french-company-validation : SIRET et code NAF contrôlés', 'custom/include/french-company-validation/AccountCheck.php', 'SuiteCrmExt\FrenchCompanyValidation\AccountCheck', 'beforeSave']],
            $hooks['before_save'],
        );
        self::assertFileExists(__DIR__ . '/../legacy/include/french-company-validation/AccountCheck.php');
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function languages(): iterable
    {
        yield 'français' => ['fr_FR'];
        yield 'anglais' => ['en_us'];
    }

    #[DataProvider('languages')]
    public function testEveryMessageIsTranslated(string $language): void
    {
        $labels = self::load('Language/' . $language . '.french-company-validation.php', 'mod_strings');

        foreach (self::ERRORS as $key) {
            self::assertNotEmpty($labels[$key] ?? null, "$key manquant en $language");
        }
    }
}
