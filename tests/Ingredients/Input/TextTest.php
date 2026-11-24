<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients\Input;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\Input\Text;

#[CoversClass(Text::class)]
class TextTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<input type="text" name="foo" />',
            (new Text('foo')) . ''
        );
    }
}
