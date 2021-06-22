<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\InputTag;

/**
 * Class InputTagTest
 * @package Soatok\Cupcake\Tests\Ingredients
 * @covers \Soatok\Cupcake\Ingredients\InputTag
 */
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
