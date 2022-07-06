<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Form;
use Soatok\Cupcake\AutoID;
use Soatok\Cupcake\Ingredients\Div;
use Soatok\Cupcake\Ingredients\Input\Text;

/**
 * @covers \Soatok\Cupcake\AutoID
 */
class AutoIDTest extends TestCase
{
    public function testEmpty()
    {
        $form = new Form();
        $form->disableAntiCSRF();
        $autoId = new AutoID(str_repeat("\0", 32));

        // Default behavior:
        $this->assertSame(
            '<form id="cupcake-b5e166965cf1713adfda5de4b6c52228" method="GET" action=""></form>',
            $autoId->autoPopulate($form) . ''
        );

        // This always mixes with spl_object_hash even with weak keys
        $autoId->setObjectHash(true);

        $this->assertNotSame(
            '<form id="cupcake-b5e166965cf1713adfda5de4b6c52228" method="GET" action=""></form>',
            $autoId->autoPopulate($form) . ''
        );

        $expect = $autoId->autoId(pack('P', 0) . spl_object_hash($autoId));
        $this->assertSame(
            '<form id="' . $expect . '" method="GET" action=""></form>',
            $autoId->autoPopulate($form) . ''
        );
    }

    public function testFormWithZeroKey()
    {
        $form = $this->getDummyForm();
        $autoId = new AutoID(str_repeat("\0", 32));

        $this->assertSame(
            '<form id="cupcake-b5e166965cf1713adfda5de4b6c52228" method="GET" action=""><input id="cupcake-2d7603abf000d275112cbe001b7ff4f1" type="text" name="foo" /><div id="cupcake-481ad0fd1b490065721c82859ee4e00b"><input id="cupcake-6f90b0f6691dfb0521222b0a9312366f" type="text" name="bar" /></div></form>',
            $autoId->autoPopulate($form) . ''
        );

        $autoId->setObjectHash(true);
        $this->assertNotSame(
            '<form id="cupcake-b5e166965cf1713adfda5de4b6c52228" method="GET" action=""><input id="cupcake-2d7603abf000d275112cbe001b7ff4f1" type="text" name="foo" /><div id="cupcake-481ad0fd1b490065721c82859ee4e00b"><input id="cupcake-6f90b0f6691dfb0521222b0a9312366f" type="text" name="bar" /></div></form>',
            $autoId->autoPopulate($form) . ''
        );

        $h0 = $autoId->autoId(pack('P', 0) . spl_object_hash($autoId));
        $h1 = $autoId->autoId(pack('P', 1) . spl_object_hash($autoId) . pack('P', 0));
        $h2 = $autoId->autoId(pack('P', 1) . spl_object_hash($autoId) . pack('P', 1));
        $h3 = $autoId->autoId(pack('P', 2) . spl_object_hash($autoId) . pack('P', 1) . pack('P', 0));

        $this->assertSame(
            '<form id="' . $h0
                . '" method="GET" action=""><input id="' . $h1
                . '" type="text" name="foo" /><div id="' . $h2
                . '"><input id="' . $h3 .
                '" type="text" name="bar" /></div></form>',
            $autoId->autoPopulate($form) . ''
        );
    }

    private function getDummyForm(): Form
    {
        $form = new Form();
        $form->disableAntiCSRF();
        $form->append(new Text('foo'));
        $form->append(
            (new Div())->append(new Text('bar'))
        );
        return $form;
    }
}
