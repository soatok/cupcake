<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Core;

use ParagonIE\Ionizer\Contract\FilterInterface;
use ParagonIE\Ionizer\InputFilter;
use ParagonIE\Ionizer\InvalidDataException;

/**
 * Class FieldFilter
 * @package Soatok\Cupcake\Core
 */
final class FieldFilter extends InputFilter
{
    public function __construct(private readonly InputFilter $inner, private readonly bool $required) {}

    /**
     * @throws InvalidDataException
     */
    #[\Override]
    public function process($data = null): mixed
    {
        if ($this->required && ($data === null || $data === '' || $data === [])) {
            throw new InvalidDataException('This field is required.');
        }
        return $this->inner->process($data);
    }

    #[\Override]
    public function setIndex(string $index): FilterInterface
    {
        $this->inner->setIndex($index);
        return parent::setIndex($index);
    }
}
