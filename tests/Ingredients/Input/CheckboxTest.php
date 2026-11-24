<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients\Input;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\Input\Checkbox;

#[CoversClass(Checkbox::class)]
class CheckboxTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<input type="checkbox" name="foo" />',
            (new Checkbox('foo')) . ''
        );
    }

    public function testWithValue()
    {
        $this->assertSame(
            '<input type="checkbox" name="foo" value="bar" />',
            (new Checkbox('foo'))->setValue('bar') . ''
        );
    }

    public function testMalformedNestedInput(): void
    {
        $checkbox = (new Checkbox('profile[choice]'))->setValue('yes')->setChecked(true);
        $checkbox->populateUserInput(['profile' => 'yes']);
        $this->assertStringNotContainsString('checked=', $checkbox->render());

        $option = (new \Soatok\Cupcake\Ingredients\Option('Yes', 'yes', true))->setName('profile[choice]');
        $option->populateUserInput(['profile' => 'yes']);
        $this->assertStringNotContainsString('selected=', $option->render());

        $textarea = new \Soatok\Cupcake\Ingredients\Textarea('profile[choice]');
        $textarea->populateUserInput(['profile' => 'yes']);
        $this->assertStringNotContainsString('>yes</textarea>', $textarea->render());
    }
}
