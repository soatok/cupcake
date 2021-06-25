<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\PurifiedHtmlBlock;

/**
 * Class PurifiedHtmlBlockTest
 * @package Soatok\Cupcake\Tests\Ingredients
 * @covers PurifiedHtmlBlock
 */
class PurifiedHtmlBlockTest extends TestCase
{
    public function testWithXSSAttempt()
    {
        $purified = new PurifiedHtmlBlock(
            'xss<script type="application/javascript">alert("xss");</script>block'
        );
        $this->assertSame('xssblock', $purified . '', 'Possible XSS vulnerability?');
    }
}
