<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\Div;

/**
 * Class DivTest
 * @package Soatok\Cupcake\Tests\Ingredients
 * @covers Div
 */
class DivTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<div></div>',
            (new Div()) . ''
        );
    }

    public function testWithId()
    {
        $this->assertSame(
            '<div id="foo"></div>',
            (new Div())->setId('foo') . ''
        );
    }
}
