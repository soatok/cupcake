<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Container;
use Soatok\Cupcake\Core\IngredientInterface;

/**
 * Class Div
 * @package Soatok\Cupcake\Ingredients
 */
class Div extends Container
{
    public function __construct(string $id = '', IngredientInterface ...$ingredients)
    {
        $this->id = $id;
        foreach ($ingredients as $ingredient) {
            $this->append($ingredient);
        }
    }

    /**
     * @return string
     */
    public function renderBefore(): string
    {
        return sprintf(
            '<div%s>',
            $this->flattenAttributes()
        );
    }

    /**
     * @return string
     */
    public function renderAfter(): string
    {
        return '</div>';
    }
}
