<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\Fieldset;
use Soatok\Cupcake\Ingredients\PurifiedHtmlBlock;

/**
 * Class FieldsetTest
 * @package Soatok\Cupcake\Tests\Ingredients
 * @covers \Soatok\Cupcake\Ingredients\Fieldset
 */
class FieldsetTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<fieldset><legend></legend></fieldset>',
            (new Fieldset()) . ''
        );
    }

    public function testIdAndClasses()
    {
        $fs = new Fieldset();
        $fs->setId('foo-bar');
        $fs->addClass('baz');
        $fs->addClass('qux');
        $fs->addClass('quux');
        $fs->addClass('quuz');

        $this->assertSame(
            '<fieldset id="foo-bar" class="baz qux quux quuz"><legend></legend></fieldset>',
            $fs . ''
        );
    }

    public function testWithLegend()
    {
        $fs = new Fieldset();
        $fs->setLegend('testing');

        $this->assertSame(
            '<fieldset><legend>testing</legend></fieldset>',
            $fs . ''
        );
    }

    public function testWithXssAttempt()
    {
        $fs = new Fieldset();
        $fs->setLegend('xss<script type="application/javascript">alert("xss");</script>block');

        $this->assertSame(
            '<fieldset><legend>xssblock</legend></fieldset>',
            $fs . '',
            'Possible XSS vulnerability'
        );

        // Let's append a block
        $fs->append(new PurifiedHtmlBlock(
            'xss<script type="application/javascript">alert("xss");</script>block'
        ));
        $this->assertSame(
            '<fieldset><legend>xssblock</legend>xssblock</fieldset>',
            $fs . '',
            'Possible XSS vulnerability'
        );
    }
}
