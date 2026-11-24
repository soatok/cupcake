<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients\Input;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\Input\File;

#[CoversClass(File::class)]
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
