<?php

declare(strict_types=1);

namespace App\Extension\FrenchCompanyValidation\backend\Process;

use App\Process\Entity\Process;
use App\Process\Service\ProcessHandlerInterface;
use SuiteCrmExt\FrenchCompanyValidation\AccountCheck;
use SuiteCrmExt\FrenchCompanyValidation\NafCode;
use SuiteCrmExt\FrenchCompanyValidation\Siret;

/**
 * Checks the SIRET or the NAF code while it is typed in the record view of an
 * account: SuiteCRM 8 calls this process for the asyncValidators of the field
 * (backend/FieldDefinitions/AddFieldChecks) and shows, under
 * the field, the label of the error; the record cannot be saved meanwhile.
 * The same rules as the hook before_save (AccountCheck), which runs for every
 * other way in.
 */
final class FieldCheckHandler implements ProcessHandlerInterface
{
    public const string TYPE = 'french-company-validation-check';

    public function getProcessType(): string
    {
        return self::TYPE;
    }

    public function requiredAuthRole(): string
    {
        return 'ROLE_USER';
    }

    public function getRequiredACLs(Process $process): array
    {
        return ['Accounts' => [['action' => 'edit']]];
    }

    public function configure(Process $process): void
    {
        $process->setId(self::TYPE);
        $process->setAsync(false);
    }

    public function validate(Process $process): void {}

    public function run(Process $process): void
    {
        $options = $process->getOptions() ?? [];
        $value = is_scalar($options['value'] ?? null) ? (string) $options['value'] : '';
        $params = is_array($options['params'] ?? null) ? $options['params'] : [];

        // The rules live in the legacy layer, shared with the hook:
        // extensions/<Slug>/backend/Process → the root of the application.
        $rules = \dirname(__DIR__, 4) . '/public/legacy/custom/include/french-company-validation';
        foreach (['Siret', 'NafCode', 'AccountCheck'] as $class) {
            require_once "{$rules}/{$class}.php";
        }
        $errors = ($params['check'] ?? '') === 'naf'
            ? AccountCheck::errors('', NafCode::clean($value))
            : AccountCheck::errors(Siret::clean($value), '');

        if ($errors === []) {
            $process->setStatus('success');

            return;
        }
        $process->setStatus('error');
        $process->setData(['errors' => ['startLabelKey' => $errors[0]]]);
    }
}
