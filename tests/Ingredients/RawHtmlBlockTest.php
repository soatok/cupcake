<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\RawHtmlBlock;

/**
 * Class RawHtmlBlockTest
 * @package Soatok\Cupcake\Tests\Ingredients
 */
#[CoversClass(RawHtmlBlock::class)]
class RawHtmlBlockTest extends TestCase
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
