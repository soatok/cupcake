<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\Optgroup;
use Soatok\Cupcake\Ingredients\Option;
use Soatok\Cupcake\Ingredients\SelectTag;

/**
 * Class SelectTagTest
 * @package Soatok\Cupcake\Tests\Ingredients
 * @covers \Soatok\Cupcake\Ingredients\SelectTag
 */
class SelectTagTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<select></select>',
            (new SelectTag()) . ''
        );
        $this->assertSame(
            '<select name="foo"></select>',
            (new SelectTag('foo')) . ''
        );
    }

    public function testWithOnlyOptions()
    {
        $sel = new SelectTag('foo');
        $sel->append(new Option('Bar', '1', false));
        $sel->append(new Option('Baz', '2', true));
        $sel->append(new Option('Qux', '3', false));
        $this->assertSame(
            '<select name="foo">' .
                '<option value="1">Bar</option>' .
                '<option value="2" selected="selected">Baz</option>' .
                '<option value="3">Qux</option>' .
            '</select>',
            $sel . ''
        );
    }

    public function testWithOptgroups()
    {
        $sel = new SelectTag('foo');
        $optgroup = new Optgroup('Tests');
        $optgroup->append(new Option('Test 1', '4'));
        $optgroup->append(new Option('Test 2', '5'));

        $sel->append(new Option('Bar', '1', false));
        $sel->append(new Option('Baz', '2', true));
        $sel->append($optgroup);
        $sel->append(new Option('Qux', '3', false));
        $this->assertSame(
            '<select name="foo">' .
                '<option value="1">Bar</option>' .
                '<option value="2" selected="selected">Baz</option>' .
                '<optgroup label="Tests">' .
                    '<option value="4">Test 1</option>' .
                    '<option value="5">Test 2</option>' .
                '</optgroup>' .
                '<option value="3">Qux</option>' .
            '</select>',
            $sel . ''
        );
    }
}
