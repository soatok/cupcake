<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use Soatok\Cupcake\Ingredients\Meter;
use PHPUnit\Framework\TestCase;

/**
 * Class MeterTest
 * @package Soatok\Cupcake\Tests\Ingredients
 * @covers Meter
 */
class MeterTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<meter value=""></meter>',
            (new Meter()) . ''
        );
    }

    public function testPopulated()
    {
        $element = (new Meter());
        $element->setMax(100);
        $r = (string) random_int(1, 100);
        $element->setValue($r);

        $this->assertSame(
            '<meter max="100" value="' . $r . '"></meter>',
            $element . ''
        );

        $element->setId(bin2hex(random_bytes(8)));
        $this->assertSame(
            '<meter id="' . $element->getId() . '" max="100" value="' . $r . '"></meter>',
            $element . ''
        );
    }
}
