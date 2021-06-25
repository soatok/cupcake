<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Exceptions\InvalidDataKeyException;
use Soatok\Cupcake\Ingredients\Textarea;

/**
 * Class TextareaTest
 * @package Soatok\Cupcake\Tests\Ingredients
 * @covers Textarea
 */
class TextareaTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<textarea></textarea>',
            (new Textarea()) . ''
        );
    }

    public function testWithContents()
    {
        $this->assertSame(
            '<textarea name="bar">&lt;foo&gt;&lt;&sol;foo&gt;</textarea>',
            (new Textarea('bar', '<foo></foo>')) . ''
        );
    }

    /**
     * @throws InvalidDataKeyException
     */
    public function testWithAttributes()
    {
        $textarea = new Textarea('bar', '<foo></foo>');
        $textarea->setId('baz');
        $textarea->setData('qux', 'quux');

        $this->assertSame(
            '<textarea id="baz" data-qux="quux" name="bar">' .
                '&lt;foo&gt;&lt;&sol;foo&gt;' .
            '</textarea>',
            $textarea . ''
        );
    }
}
