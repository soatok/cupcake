<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests;

use ParagonIE\Ionizer\Filter\StringFilter;
use ParagonIE\Ionizer\InvalidDataException;
use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Form;
use Soatok\Cupcake\Ingredients\Fieldset;
use Soatok\Cupcake\Ingredients\Input\File;
use Soatok\Cupcake\Ingredients\InputTag;

/**
 * Class FormTest
 * @package Soatok\Cupcake\Tests
 * @covers Form
 */
class FormTest extends TestCase
{
    public function testFileBehavior()
    {
        $form = new Form();
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
}
