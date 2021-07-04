<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Mixtures;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Form;
use Soatok\Cupcake\Ingredients\Datalist;
use Soatok\Cupcake\Ingredients\InputTag;

/**
 * Class InputWithDatalistTest
 * @package Soatok\Cupcake\Tests\Mixtures
 *
 * @covers InputTag, Datalist
 */
class InputWithDatalistTest extends TestCase
{
    public function testInputWithDatalist()
    {
        $form = new Form();
        $form->disableAntiCSRF();
        $datalist = new Datalist('test-data');
        $input = (new InputTag('test'))->setList($datalist);
        $form->append($input)->append($datalist);
        $this->assertSame(
            '<form method="GET" action="">' .
                '<input list="test-data" type="text" name="test" />' .
                '<datalist id="test-data"></datalist>' .
            '</form>',
            (string) $form
        );
    }
}
