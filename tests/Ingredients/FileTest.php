<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\File;

/**
 * Class FileTest
 * @package Soatok\Cupcake\Tests\Ingredients
 * @covers \Soatok\Cupcake\Ingredients\File
 */
class FileTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<input type="file" name="foo" />',
            (new File('foo')) . ''
        );
    }
}
