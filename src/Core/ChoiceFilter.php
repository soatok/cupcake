<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Core;

use ParagonIE\Ionizer\InputFilter;
use ParagonIE\Ionizer\InvalidDataException;

/**
 * Class ChoiceFilter
 * @package Soatok\Cupcake\Core
 */
final class ChoiceFilter extends InputFilter
{
    /**
     * @param list<string> $choices
     */
    public function __construct(
        private readonly array $choices,
        private readonly ?InputFilter $inner = null,
        private readonly bool $multiple = false
    ) {}

    /**
     * @throws InvalidDataException
     */
    #[\Override]
    public function process($data = null): mixed
    {
        if ($this->multiple) {
            $values = $data ?? [];
            if (!is_array($values)) {
                throw new InvalidDataException('Expected a list of choices.');
            }
        } else {
            if ($data === null) {
                return null;
            }
            $values = [$data];
        }
        foreach ($values as $value) {
            if (!is_string($value) || !in_array($value, $this->choices, true)) {
                throw new InvalidDataException('Invalid choice.');
            }
        }
        return $this->inner ? $this->inner->process($data) : ($this->multiple ? $values : $data);
    }
}
