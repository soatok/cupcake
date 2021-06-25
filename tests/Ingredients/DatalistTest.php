<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\Datalist;
use Soatok\Cupcake\Ingredients\Option;

/**
 * Class DatalistTest
 * @package Soatok\Cupcake\Tests\Ingredients
 * @covers Datalist
 */
class DatalistTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<datalist></datalist>',
            (new Datalist()) . ''
        );
    }

    public function testWithOptions()
    {
        $datalist = new Datalist();
        $datalist->append(new Option('Bar', '1', false));
        $datalist->append(new Option('Baz', '2', true));
        $datalist->append(new Option('Qux', '" onload="alert(\'xss\');', false));
        $this->assertSame(
            '<datalist>' .
                '<option value="1" />' .
                '<option value="2" selected="selected" />' .
                '<option value="&quot; onload&equals;&quot;alert&lpar;&apos;xss&apos;&rpar;&semi;" />' .
            '</datalist>',
            (string) $datalist,
            'Potential XSS vulnerability'
        );
    }
}