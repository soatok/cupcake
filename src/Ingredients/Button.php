<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Element;
use Soatok\Cupcake\Core\Utilities;

/**
 * Class Button
 * @package Soatok\Cupcake\Ingredients
 */
class Button extends Element
{
    protected string $label;

    public function __construct(string $label = '')
    {
        $this->label = $label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * Render this button, and everything it contains.
     *
     * @return string
     */
    public function render(): string
    {
        return sprintf(
            '<button%s>%s</button>',
            $this->flattenAttributes(),
            Utilities::purify($this->label)
        );
    }
}
