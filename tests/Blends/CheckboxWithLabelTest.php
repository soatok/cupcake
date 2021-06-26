<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Blends;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Blends\CheckboxWithLabel;
use Soatok\Cupcake\Ingredients\Input\Checkbox;
use Soatok\Cupcake\Ingredients\Label;

/**
 * Class CheckboxWithLabelTest
 * @package Soatok\Cupcake\Tests\Blends
 * @covers CheckboxWithLabel
 */
class CheckboxWithLabelTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '',
            (string) (new CheckboxWithLabel())
        );
    }

    public function testCreate()
    {
        $this->assertSame(
            '<input id="agreement" type="checkbox" name="agree" value="1" checked="checked" />' .
                '<label for="agreement">Do you agree?</label>',
            (string) (CheckboxWithLabel::create('agree', '1', 'agreement', 'Do you agree?', true))
        );
    }

    /**
     *
     */
    public function testConstructor()
    {
        $checkbox = new Checkbox('test', 'id', '1');
        $label = new Label('Test', $checkbox);
        $cwl = new CheckboxWithLabel($checkbox, $label);
        $this->assertSame(
            '<input id="id" type="checkbox" name="test" value="1" /><label for="id">Test</label>',
            (string) $cwl
        );
    }
}
