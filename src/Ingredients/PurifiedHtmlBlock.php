<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Element;
use Soatok\Cupcake\Core\Utilities;

/**
 * Class PurifiedHtmlBlock
 * @package Soatok\Cupcake\Ingredients
 */
class PurifiedHtmlBlock extends Element
{
    protected string $contents;

    public function __construct(string $contents)
    {
        $this->contents = $contents;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return Utilities::purify($this->contents);
    }
}
