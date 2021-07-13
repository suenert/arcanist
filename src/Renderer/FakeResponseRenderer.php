<?php declare(strict_types=1);

namespace Arcanist\Renderer;

use Arcanist\WizardStep;
use Arcanist\AbstractWizard;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Arcanist\Contracts\ResponseRenderer;
use Illuminate\Contracts\Support\Responsable;

class FakeResponseRenderer implements ResponseRenderer
{
    private array $renderedSteps = [];
    private ?string $redirect = null;
    private ?string $error = null;
    private bool $hasError = false;

    public function renderStep(
        WizardStep $step,
        AbstractWizard $wizard,
        array $data = []
    ): Response | Responsable {
        $this->renderedSteps[get_class($step)] = $data;

        return new Response();
    }

    public function redirect(WizardStep $step, AbstractWizard $wizard): RedirectResponse | Response | JsonResponse
    {
        $this->redirect = get_class($step);

        return new RedirectResponse('::url::');
    }

    public function redirectWithError(
        WizardStep $step,
        AbstractWizard $wizard,
        ?string $error = null
    ): RedirectResponse  | Response | JsonResponse {
        $this->redirect = get_class($step);
        $this->hasError = true;
        $this->error = $error;

        return new RedirectResponse('::url::');
    }

    public function stepWasRendered(string $stepClass, ?array $data = null): bool
    {
        if (!isset($this->renderedSteps[$stepClass])) {
            return false;
        }

        if ($data !== null) {
            return array_diff($data, $this->renderedSteps[$stepClass]) === [];
        }

        return true;
    }

    public function didRedirectTo(string $stepClass): bool
    {
        return $this->redirect === $stepClass && !$this->hasError;
    }

    public function didRedirectWithError(string $stepClass, ?string $message = null): bool
    {
        return $this->redirect === $stepClass
            && $this->hasError
            && $this->error === $message;
    }
}
