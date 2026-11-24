<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients\Input;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\Input\Password;

#[CoversClass(Password::class)]
class PasswordTest extends TestCase
{
    public function testEmpty()
    {
        $this->assertSame(
            '<input type="password" name="foo" />',
            (new Password('foo')) . ''
        );
    }
}
