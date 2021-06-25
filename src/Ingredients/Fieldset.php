<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Container;
use Soatok\Cupcake\Core\IngredientInterface;
use Soatok\Cupcake\Core\Utilities;

/**
 * Class Fieldset
 * @package Soatok\Cupcake\Ingredients
 */
class Fieldset extends Container
{
    protected string $legend = '';

    public function __construct(string $legend = '', IngredientInterface ...$ingredients)
    {
        $this->legend = $legend;
        foreach ($ingredients as $ingredient) {
            $this->append($ingredient);
        }
    }

    /**
     * @param string $legend
     * @return void
     */
    public function setLegend(string $legend): void
    {
        $this->legend = $legend;
    }

    /**
     * @return string
     */
    public function renderBefore(): string
    {
        return sprintf(
            '<fieldset%s><legend>%s</legend>',
            $this->flattenAttributes(),
            Utilities::purify($this->legend)
        );
    }

    /**
     * @return string
     */
    public function renderAfter(): string
    {
        return '</fieldset>';
    }
}
