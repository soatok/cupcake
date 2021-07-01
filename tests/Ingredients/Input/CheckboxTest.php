<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients\Input;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\Input\Checkbox;

/**
 * Class CheckboxTest
 * @package Soatok\Cupcake\Tests\Ingredients\Input
 * @covers Checkbox
 */
class CheckboxTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<input type="checkbox" name="foo" />',
            (new Checkbox('foo')) . ''
        );
    }

    public function testWithValue()
    {
        $this->assertSame(
            '<input type="checkbox" name="foo" value="bar" />',
            (new Checkbox('foo'))->setValue('bar') . ''
        );
    }
}
