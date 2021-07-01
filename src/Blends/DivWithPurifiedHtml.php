<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Blends;

use Soatok\Cupcake\Core\Container;
use Soatok\Cupcake\Core\Utilities;
use Soatok\Cupcake\Ingredients\PurifiedHtmlBlock;
use Soatok\Cupcake\Ingredients\RawHtmlBlock;

/**
 * Class DivWithPurifiedHtml
 * @package Soatok\Cupcake\Blends
 */
class DivWithPurifiedHtml extends Container
{
    /**
     * DivWithPurifiedHtml constructor.
     * @param string $contents
     */
    public function __construct(string $contents, string $id = '')
    {
        $this->append(new PurifiedHtmlBlock($contents));
        if ($id) {
            $this->setId($id);
        }
    }

    /**
     * @param string $contents
     * @return self
     */
    public function setContents(string $contents): self
    {
        /** @var RawHtmlBlock $el */
        $el = $this->ingredients[0];
        $el->setContents($contents);
        return $this;
    }
}
