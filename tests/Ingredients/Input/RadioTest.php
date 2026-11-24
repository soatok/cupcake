<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients\Input;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\Input\Radio;

#[CoversClass(Radio::class)]
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
