<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Blends\MultiCheckbox;
use Soatok\Cupcake\Blends\RadioSet;
use Soatok\Cupcake\Form;
use Soatok\Cupcake\Ingredients\Fieldset;
use Soatok\Cupcake\Ingredients\Input\File;
use Soatok\Cupcake\Ingredients\Input\Text;
use Soatok\Cupcake\Ingredients\InputTag;
use Soatok\Cupcake\Tests\Security\AntiCSRF\CookieBackedDummy;

/**
 * Class FormTest
 * @package Soatok\Cupcake\Tests
 * @covers Form
 */
class FormTest extends TestCase
{
    protected string $idPrefix = '';

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        $this->idPrefix = bin2hex(random_bytes(32));
    }

    public function testEmpty()
    {
        $form = (new Form())->disableAntiCSRF();
        $this->assertSame(
            '<form method="GET" action=""></form>',
            $form . ''
        );
    }
    public function testEmptyWithoutDisablingCsrfProtection()
    {
        /** @var array<string, string> $storage */
        $storage = [];
        $cookie = new CookieBackedDummy('cupcake-csrf', 'cupcake-csrf', $storage);
        // Coerce it to write
        $cookie->getHiddenElement();

        $form = new Form();
        $form->setAntiCSRF($cookie);

        /** @var string $token */
        /** @psalm-suppress PossiblyNullArrayAccess */
        $token = $storage['cupcake-csrf'];
        $this->assertSame(
            '<form method="GET" action="">' .
                '<input type="hidden" name="anti-csrf" value="' . $token . '" />' .
            '</form>',
            $form . ''
        );
    }

    public function testFileBehavior()
    {
        $form = (new Form())->disableAntiCSRF();
        $this->assertFalse($form->hasFileInput(), 'No file input is detected by default');
        $this->assertStringNotContainsString(
            'enctype="multipart/form-data"',
            $form->render(),
            'By default, no enctype is specified'
        );
        $form->append(
            (new Fieldset())->append(
                (new File('test'))
            )
        );
        $this->assertTrue($form->hasFileInput());
        $this->assertStringContainsString(
            'enctype="multipart&sol;form-data"',
            $form->render(),
            'Adding a file input should set the enctype to multipart/form-data'
        );
    }

    protected function getTestForm()
    {
        $form = new Form();
        $form->disableAntiCSRF();
        $input = (new InputTag('test'))
            ->setPattern('^[a-z]+$')
            ->setRequired(true);
        $form->append($input);
        return $form;
    }

    public function testValidInput()
    {
        $form = $this->getTestForm();
        $this->assertSame(
            ['test' => 'abcdefg'],
            $form->getValidFormInput(['test' => 'abcdefg'])
        );
    }

    public function testInvalidInput()
    {
        $this->expectExceptionMessage('Pattern match failed (test).');
        $form = $this->getTestForm();
        $form->getValidFormInput(['test' => 'abc1234']);
    }

    /**
     * @throws \Exception
     */
    public function testPopulateInput()
    {
        $this->assertSame(
            '<form method="GET" action="">' .
                '<input type="text" name="foo" value="dhole" />' .
                '<input id="' . $this->idPrefix . '-1" type="checkbox" name="bar&lbrack;&rsqb;" value="1" />' .
                    '<label for="' . $this->idPrefix . '-1">test 1</label>' .
                '<input id="' . $this->idPrefix . '-2" type="checkbox" name="bar&lbrack;&rsqb;" value="2" />' .
                    '<label for="' . $this->idPrefix . '-2">test 2</label>' .
                '<input id="' . $this->idPrefix . '-3" type="checkbox" name="bar&lbrack;&rsqb;" value="3" checked="checked" />' .
                    '<label for="' . $this->idPrefix . '-3">test 3</label>' .
                '<input id="' . $this->idPrefix . '-apple" type="checkbox" name="bar&lbrack;&rsqb;" value="apple" checked="checked" />' .
                    '<label for="' . $this->idPrefix . '-apple">test 4</label>' .
                '<input id="' . $this->idPrefix . '-4" type="radio" name="baz" value="4" />' .
                    '<label for="' . $this->idPrefix . '-4">test 4</label>' .
                '<input id="' . $this->idPrefix . '-5" type="radio" name="baz" value="5" />' .
                    '<label for="' . $this->idPrefix . '-5">test 5</label>' .
                '<input id="' . $this->idPrefix . '-6" type="radio" name="baz" value="6" checked="checked" />' .
                    '<label for="' . $this->idPrefix . '-6">test 6</label>' .
            '</form>',
            $this->exampleForm()->populateUserInput([
                'foo' => 'dhole',
                'bar' => ['3', 'apple'],
                'baz' => '6',
            ]) . ''
        );
    }

    /**
     * @return Form
     * @throws \Exception
     */
    protected function exampleForm(): Form
    {
        $form = new Form();
        $form->disableAntiCSRF();
        $form->append(new Text('foo'));
        $form->append(
            (new MultiCheckbox('bar'))
                ->addCheckbox('test 1', '1', false, $this->idPrefix . '-1')
                ->addCheckbox('test 2', '2', true, $this->idPrefix . '-2')
                ->addCheckbox('test 3', '3', false, $this->idPrefix . '-3')
                ->addCheckbox('test 4', 'apple', false, $this->idPrefix . '-apple')
        );
        $form->append(
            (new RadioSet('baz'))
                ->addRadio('4', 'test 4', $this->idPrefix . '-4', true)
                ->addRadio('5', 'test 5', $this->idPrefix . '-5')
                ->addRadio('6', 'test 6', $this->idPrefix . '-6')
        );
        return $form;
    }
}
