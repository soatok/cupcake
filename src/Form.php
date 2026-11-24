<?php
declare(strict_types=1);
namespace Soatok\Cupcake;

use ParagonIE\Ionizer\InvalidDataException;
use Soatok\Cupcake\Core\{
    AntiCSRFInterface,
    Container,
    Utilities
};
use Soatok\Cupcake\Exceptions\CupcakeException;
use Soatok\Cupcake\Ingredients\Input\Hidden;

/**
 * Class Form
 * @package Soatok\Cupcake
 */
class Form extends Container
{
    protected bool $antiCSRFdisabled = false;
    protected string $method = 'GET';
    protected string $action = '';
    protected string $enctype = '';
    protected ?AntiCSRFInterface $antiCSRF = null;

    /**
     * @var list<callable(array): array<string, list<string>>>
     */
    protected array $validators = [];

    /**
     * @param callable(array): array<string, list<string>> $validator $validator
     * @return self
     */
    public function addValidator(callable $validator): self
    {
        $this->validators[] = $validator;
        return $this;
    }

    /**
     * @throws CupcakeException
     */
    public function submit(array $untrusted): ValidationResult
    {
        if (!$this->antiCSRFdisabled && !$this->getAntiCSRF()->validate($untrusted)) {
            return new ValidationResult([], [], ['Invalid or missing CSRF token.']);
        }
        $result = $this->getInputFilters()->validateInput($untrusted);
        if (!$result->isValid()) {
            return $result;
        }
        $data = $result->getData();
        $fieldErrors = [];
        $formErrors = [];
        foreach ($this->validators as $validator) {
            foreach ($validator($data) as $path => $messages) {
                if ($path === '') {
                    $formErrors = array_merge($formErrors, $messages);
                } elseif ($messages) {
                    $fieldErrors[$path] = array_merge($fieldErrors[$path] ?? [], $messages);
                }
            }
        }
        return new ValidationResult($data, $fieldErrors, $formErrors);
    }

    /**
     * @throws CupcakeException
     * @throws InvalidDataException
     */
    #[\Override]
    public function getValidFormInput(array $untrusted): array
    {
        if (!$this->antiCSRFdisabled && !$this->getAntiCSRF()->validate($untrusted)) {
            throw new InvalidDataException('Invalid or missing CSRF token.');
        }
        return parent::getValidFormInput($untrusted);
    }

    /**
     * @throws CupcakeException
     */
    #[\Override]
    protected function getInputFiltersInternal(array &$objectsVisited, array &$inputFilters): void
    {
        parent::getInputFiltersInternal($objectsVisited, $inputFilters);
        if (!$this->antiCSRFdisabled) {
            unset($inputFilters[$this->getAntiCSRF()->getFormName()]);
        }
    }

    /**
     * Returns a map where:
     *
     * - key   -> key to include in flattenAttributes()
     * - value -> property of the object (or NULL if identical to key)
     *
     * @return array<string, ?string>
     */
    #[\Override]
    public function customAttributes(): array
    {
        $mapping = [
            'method' => null,
            'action' => null
        ];
        if ($this->hasFileInput()) {
            // Add enctype="multipart/form-data"
            $mapping['enctype'] = null;
            if (empty($this->enctype)) {
                $this->enctype = 'multipart/form-data';
            }
        } elseif (!empty($this->enctype)) {
            // Add whatever the user provided
            $mapping['enctype'] = null;
        }
        return $mapping;
    }

    /**
     * @return self
     */
    public function disableAntiCSRF(): self
    {
        $this->antiCSRFdisabled = true;
        return $this;
    }

    /**
     * @return self
     */
    public function enableAntiCSRF(): self
    {
        $this->antiCSRFdisabled = false;
        return $this;
    }

    /**
     * Append the Anti-CSRF element if it doesn't already exist.
     *
     * @return self
     * @throws CupcakeException
     */
    public function finalizeCsrfElement(): self
    {
        if (!$this->antiCSRFdisabled) {
            $hidden = $this->getAntiCSRF()->getHiddenElement();
            foreach ($this->ingredients as $ingredient) {
                if ($ingredient instanceof Hidden
                    && $ingredient->getName() === $hidden->getName()) {
                    $ingredient->setValue($hidden->getValue());
                    return $this;
                }
            }
            if ($this->elementExistsWithName($hidden->getName())) {
                throw new CupcakeException('CSRF field name conflicts with another element.');
            }
            $this->append($hidden);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Get the current Anti-CSRF mitigation.
     *
     * @param bool $ignoreDefault
     * @return AntiCSRFInterface
     * @throws CupcakeException
     */
    public function getAntiCSRF(bool $ignoreDefault = false): AntiCSRFInterface
    {
        if (is_null($this->antiCSRF)) {
            if ($ignoreDefault) {
                throw new CupcakeException('No Anti-CSRF class configured.');
            }
            return Utilities::defaultAntiCSRF();
        }
        return $this->antiCSRF;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    #[\Override]
    public function renderAfter(): string
    {
        return '</form>';
    }

    /**
     * @return string
     */
    #[\Override]
    public function renderBefore(): string
    {
        return sprintf(
            '<form%s>',
            $this->flattenAttributes(),
        );
    }

    /**
     * @return string
     * @throws CupcakeException
     */
    #[\Override]
    public function render(): string
    {
        if (!$this->antiCSRFdisabled) {
            $this->finalizeCsrfElement();
        }
        return parent::render();
    }

    /**
     * Set the action attribute for this form.
     *
     * @param string $action
     * @return self
     */
    public function setAction(string $action): self
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Overwrite the anti-CSRF mitigation with another implementation at runtime.
     *
     * @param AntiCSRFInterface $antiCSRF
     * @return self
     */
    public function setAntiCSRF(AntiCSRFInterface $antiCSRF): self
    {
        $this->antiCSRF = $antiCSRF;
        return $this;
    }

    /**
     * Set the HTTP request method for this form.
     *
     * @param string $method
     * @return self
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }
}
