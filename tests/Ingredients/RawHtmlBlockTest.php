<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use Soatok\Cupcake\Ingredients\RawHtmlBlock;

/**
 * Class RawHtmlBlockTest
 * @package Soatok\Cupcake\Tests\Ingredients
 */
class RawHtmlBlockTest
{
    /**
     * RawHtmlBlock doesn't protect against XSS.
     */
    public function testWithXSSAttempt()
    {
        $input = 'xss<script type="application/javascript">alert("xss");</script>block';
        $raw = new RawHtmlBlock($input);
        $this->assertSame($input, (string) $raw);
    }
}
