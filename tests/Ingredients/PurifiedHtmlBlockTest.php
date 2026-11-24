<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\PurifiedHtmlBlock;

#[CoversClass(PurifiedHtmlBlock::class)]
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
