<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Ingredients\Input;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Ingredients\Input\Password;

/**
 * Class PasswordTest
 * @package Soatok\Cupcake\Tests\Ingredients
 * @covers Password
 */
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
