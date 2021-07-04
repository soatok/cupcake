<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\DoesNotPopulateTrait;
use Soatok\Cupcake\Core\Element;
use Soatok\Cupcake\Core\Utilities;

/**
 * Class PurifiedHtmlBlock
 * @package Soatok\Cupcake\Ingredients
 */
class PurifiedHtmlBlock extends Element
{
    use DoesNotPopulateTrait;
    protected string $contents;

    public function __construct(string $contents)
    {
        $this->contents = $contents;
    }

    /**
     * @param string $contents
     * @return self
     */
    public function setContents(string $contents): self
    {
        $this->contents = $contents;
        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return Utilities::purify($this->contents);
    }
}
