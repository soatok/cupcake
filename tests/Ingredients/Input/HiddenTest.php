<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients\Input;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\Input\Hidden;

#[CoversClass(Hidden::class)]
class HiddenTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<input type="hidden" name="foo" />',
            (new Hidden('foo')) . ''
        );
    }

    public function testWithValue()
    {
        $this->assertSame(
            '<input type="hidden" name="foo" value="bar" />',
            (new Hidden('foo'))->setValue('bar') . ''
        );
    }
}
