<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use Soatok\Cupcake\Ingredients\InputTag;
use Soatok\Cupcake\Ingredients\Label;
use PHPUnit\Framework\TestCase;

/**
 * Class LabelTest
 * @package Soatok\Cupcake\Tests\Ingredients
 * @covers Label
 */
class LabelTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<label></label>',
            (new Label()) . ''
        );
    }

    public function testWithElement()
    {
        $element = new InputTag('test');
        $element->setId(bin2hex(random_bytes(8)));
        $label = (new Label())->setFor($element);
        $this->assertSame(
            '<label for="' . $element->getId() . '"></label>',
            (string) $label
        );
    }
}
