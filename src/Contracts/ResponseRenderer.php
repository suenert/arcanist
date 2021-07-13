<?php declare(strict_types=1);

namespace Arcanist\Contracts;

use Arcanist\WizardStep;
use Arcanist\AbstractWizard;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Support\Responsable;

interface ResponseRenderer
{
    public function renderStep(
        WizardStep $step,
        AbstractWizard $wizard,
        array $data = []
    ): Response | Responsable | Renderable | JsonResponse;

    public function redirect(
        WizardStep $step,
        AbstractWizard $wizard
    ): RedirectResponse | Response | JsonResponse;

    public function redirectWithError(
        WizardStep $step,
        AbstractWizard $wizard,
        ?string $error = null
    ): RedirectResponse | Response | JsonResponse;
}
