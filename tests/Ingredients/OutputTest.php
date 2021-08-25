<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use Soatok\Cupcake\Ingredients\InputTag;
use Soatok\Cupcake\Ingredients\Output;
use PHPUnit\Framework\TestCase;

/**
 * Class OutputTest
 * @package Soatok\Cupcake\Tests\Ingredients
 * @covers Output
 */
class OutputTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<output></output>',
            (new Output()) . ''
        );
    }

    public function testWithElement()
    {
        $element = new InputTag('test');
        $element->setId(bin2hex(random_bytes(8)));
        $Output = (new Output())->setFor($element);
        $this->assertSame(
            '<output for="' . $element->getId() . '"></output>',
            (string) $Output
        );
    }
}
