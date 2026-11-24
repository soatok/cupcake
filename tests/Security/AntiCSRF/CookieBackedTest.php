<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Security\AntiCSRF;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Random\RandomException;
use Soatok\Cupcake\Exceptions\CupcakeException;
use Soatok\Cupcake\Security\AntiCSRF\CookieBacked;

#[CoversClass(CookieBacked::class)]
class CookieBackedTest extends TestCase
{
    /**
     * @throws CupcakeException
     * @throws RandomException
     */
    public function testGenerateCheck()
    {
        $storage = [];
        $cookie = new CookieBackedDummy('cupcake-csrf', 'cupcake-csrf', $storage);
        $token = $cookie->getHiddenElement()->getValue();
        $this->assertSame($token, $storage['cupcake-csrf']);
        $this->assertTrue($cookie->checkToken($token), 'Validation fails');
        $this->assertSame('cupcake-csrf', $cookie->getHiddenElement()->getName());
        $this->assertTrue($cookie->validate(['cupcake-csrf' => $token]));
        $this->assertFalse($cookie->validate(['cupcake-csrf' => []]));
        $this->assertFalse($cookie->validate([]));
        $empty = [];
        $missingCookie = new CookieBackedDummy('cookie', 'token', $empty);
        $this->assertFalse($missingCookie->validate(['token' => 'untrusted']));
    }
}
