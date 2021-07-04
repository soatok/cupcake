<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Core;

/**
 * Trait DoesNotPopulateTrait
 * @package Soatok\Cupcake\Core
 */
trait DoesNotPopulateTrait
{
    /**
     * @param array $untrusted
     * @return IngredientInterface
     */
    public function populateUserInput(array $untrusted): IngredientInterface
    {
        if (!($this instanceof IngredientInterface)) {
            throw new \TypeError('This trait should only be used on ingredients');
        }
        return $this;
    }
}
