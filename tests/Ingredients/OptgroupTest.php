<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\Optgroup;

/**
 * Class OptgroupTest
 * @package Soatok\Cupcake\Tests\Ingredients
 * @covers Optgroup
 */
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
