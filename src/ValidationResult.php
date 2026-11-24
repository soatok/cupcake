<?php
declare(strict_types=1);
namespace Soatok\Cupcake;

/**
 * Class ValidationResult
 * @package Soatok\Cupcake
 */
final readonly class ValidationResult
{
    /** @param array<string, list<string>> $fieldErrors
     *  @param list<string> $formErrors
     */
    public function __construct(
        private array $data = [],
        private array $fieldErrors = [],
        private array $formErrors = []
    ) {}

    public function isValid(): bool
    {
        return !$this->fieldErrors && !$this->formErrors;
    }

    public function getData(): array
    {
        return $this->isValid() ? $this->data : [];
    }

    /**
     * @return array<string, list<string>>
     */
    public function getFieldErrors(): array
    {
        return $this->fieldErrors;
    }

    /**
     * @return list<string>
     */
    public function getFormErrors(): array
    {
        return $this->formErrors;
    }
}
