<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\Button;

/**
 * Class ButtonTest
 * @package Soatok\Cupcake\Tests\Ingredients
 * @covers Button
 */
class ButtonTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<button></button>',
            (new Button()) . ''
        );
    }

    public function testLabel()
    {
        $this->assertSame(
            '<button>test label</button>',
            (new Button('', 'test label')) . ''
        );
        $this->assertSame(
            '<button name="foo">test label</button>',
            (new Button('foo', 'test label')) . ''
        );
        $this->assertSame(
            '<button name="foo">test label</button>',
            (new Button('foo'))->setLabel('test label') . ''
        );

        $this->assertSame(
            '<button>test</button>',
            (new Button('', '<script>alert("xss");</script>test')) . '',
            'Possible XSS vulnerability'
        );
    }

    public function testType()
    {
        $this->assertSame(
            '<button type="submit">test label</button>',
            (new Button('', 'test label', 'submit')) . ''
        );
        $this->assertSame(
            '<button type="reset">reset</button>',
            (string) (new Button('', 'reset'))
                ->setType('reset')
        );
    }
}
