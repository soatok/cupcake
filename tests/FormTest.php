<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Form;
use Soatok\Cupcake\Ingredients\Fieldset;
use Soatok\Cupcake\Ingredients\File;

/**
 * Class FormTest
 * @package Soatok\Cupcake\Tests
 * @covers \Soatok\Cupcake\Form
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
            'enctype="multipart/form-data"',
            $form->render(),
            'Adding a file input should set the enctype to multipart/form-data'
        );
    }
}
