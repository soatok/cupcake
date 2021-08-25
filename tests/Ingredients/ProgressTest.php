<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use Soatok\Cupcake\Ingredients\Progress;
use PHPUnit\Framework\TestCase;

/**
 * Class ProgressTest
 * @package Soatok\Cupcake\Tests\Ingredients
 * @covers Progress
 */
class ProgressTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<progress></progress>',
            (new Progress()) . ''
        );
    }
    public function testPopulated()
    {
        $element = (new Progress());
        $element->setMax(100);
        $r = (string) random_int(1, 100);
        $element->setValue($r);

        $this->assertSame(
            '<progress max="100" value="' . $r . '"></progress>',
            $element . ''
        );

        $element->setId(bin2hex(random_bytes(8)));
        $this->assertSame(
            '<progress id="' . $element->getId() . '" max="100" value="' . $r . '"></progress>',
            $element . ''
        );
    }
}
