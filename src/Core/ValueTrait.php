<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Core;

use Soatok\Cupcake\FilterContainer;

/**
 * Trait ValueTrait
 * @package Soatok\Cupcake\Core
 */
trait ValueTrait
{
    protected string $value = '';

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return static
     */
    public function setValue(string $value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param array $untrusted
     * @return IngredientInterface
     */
    public function populateUserInput(array $untrusted): IngredientInterface
    {
        if (!($this instanceof IngredientInterface)) {
            throw new \TypeError('This trait should only be used on ingredients');
        }
        $pointer = &$untrusted;
        $pieces = explode(FilterContainer::SEPARATOR, $this->getIonizerName());
        foreach ($pieces as $piece) {
            if (!array_key_exists($piece, $pointer)) {
                return $this->setValue('');
            }
            $pointer = &$pointer[$piece];
        }
        $this->setValue($pointer);
        return $this;
    }
}
