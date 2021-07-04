<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients\Input;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\Input\Text;

/**
 * Class TextTest
 * @package Soatok\Cupcake\Tests\Ingredients
 * @covers Text
 */
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
