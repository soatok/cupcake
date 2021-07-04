<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients\Input;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\Input\Radio;

/**
 * Class RadioTest
 * @package Soatok\Cupcake\Tests\Ingredients\Input
 * @covers Radio
 */
class RadioTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<input type="radio" name="foo" />',
            (new Radio('foo')) . ''
        );
    }
}
