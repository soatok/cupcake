<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Container;
use Soatok\Cupcake\Core\Utilities;

/**
 * Class Fieldset
 * @package Soatok\Cupcake\Ingredients
 */
class Fieldset extends Container
{
    protected string $legend = '';

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
