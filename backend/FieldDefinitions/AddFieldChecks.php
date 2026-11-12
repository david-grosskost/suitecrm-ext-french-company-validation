<?php

declare(strict_types=1);

namespace App\Extension\FrenchCompanyValidation\backend\FieldDefinitions;

use App\FieldDefinitions\Entity\FieldDefinition;
use App\FieldDefinitions\LegacyHandler\FieldDefinitionMapperInterface;

/**
 * Adds the checks of the SIRET and the NAF code to the field definitions of
 * the accounts, when SuiteCRM 8 builds them: whatever the order in which the
 * extensions' files are merged, and nothing in the legacy vardefs.
 */
final class AddFieldChecks implements FieldDefinitionMapperInterface
{
    public function getKey(): string
    {
        return 'french-company-validation-checks';
    }

    // Every module: the accounts are recognised in map().
    public function getModule(): string
    {
        return 'default';
    }

    public function map(FieldDefinition $definition): void
    {
        if (strtolower($definition->getId()) !== 'accounts') {
            return;
        }
        $definition->setVardef(FieldChecks::add($definition->getVardef() ?? []));
    }
}
