<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Element;

/**
 * Class RawHtmlBlock
 * @package Soatok\Cupcake\Ingredients
 *
 * RawHtmlBlock doesn't protect against XSS.
 * It does, however, include a Psalm annotation as a taint sink.
 *
 * You can verify that you're not passing user input to this class
 * if you use Psalm's taint analysis feature.
 *
 * @link https://psalm.dev/docs/security_analysis
 */
class RawHtmlBlock extends Element
{
    protected string $contents;

    /**
     * RawHtmlBlock constructor.
     * @param string $contents
     *
     * @psalm-taint-sink html $contents
     */
    public function __construct(string $contents)
    {
        $this->contents = $contents;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return $this->contents;
    }
}
