<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\InputTag;

#[CoversClass(InputTag::class)]
class InputTagTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<input type="text" name="foo" />',
            (new InputTag('foo')) . ''
        );
    }
}
