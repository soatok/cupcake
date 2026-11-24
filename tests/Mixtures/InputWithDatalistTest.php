<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Mixtures;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Form;
use Soatok\Cupcake\Ingredients\Datalist;
use Soatok\Cupcake\Ingredients\InputTag;

#[CoversClass(InputTag::class)]
#[CoversClass(Datalist::class)]
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
