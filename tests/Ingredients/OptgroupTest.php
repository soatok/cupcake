<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\Optgroup;

#[CoversClass(Optgroup::class)]
class OptgroupTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<optgroup label=""></optgroup>',
            (new Optgroup()) . ''
        );
    }
}
