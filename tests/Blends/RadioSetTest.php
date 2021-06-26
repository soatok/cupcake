<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Blends;

use Exception;
use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Blends\RadioSet;

/**
 * Class RadioSetTest
 *
 * @package Soatok\Cupcake\Tests
 * @covers RadioSet
 */
class RadioSetTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame('', (new RadioSet('foo')) . '');
    }

    /**
     * @throws Exception
     */
    public function testWithRadios()
    {
        $rs = new RadioSet('foo');
        $rs->addRadio('1', 'Example', 'foo-1');
        $rs->addRadio('2', 'Another One', 'foo-2');
        $this->assertSame(
            '<input id="foo-1" type="radio" name="foo" value="1" />' .
                '<label for="foo-1">Example</label>' .
            '<input id="foo-2" type="radio" name="foo" value="2" />' .
                '<label for="foo-2">Another One</label>',
            (string) $rs
        );

        $rs->addRadio('3', 'test<script>alert("xss");</script>', 'foo-3', true);
        $this->assertSame(
            '<input id="foo-1" type="radio" name="foo" value="1" />' .
                '<label for="foo-1">Example</label>' .
            '<input id="foo-2" type="radio" name="foo" value="2" />' .
              '<label for="foo-2">Another One</label>' .
            '<input id="foo-3" type="radio" name="foo" value="3" checked="checked" />' .
                '<label for="foo-3">test</label>',
            (string) $rs,
            'Possible XSS Vulnerability?'
        );
    }
}
