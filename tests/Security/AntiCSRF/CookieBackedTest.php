<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Security\AntiCSRF;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Exceptions\CupcakeException;
use Soatok\Cupcake\Security\AntiCSRF\CookieBacked;

/**
 * Class CookieBackedTest
 * @package Soatok\Cupcake\Tests\Security\AntiCSRF
 * @covers CookieBacked
 */
class CookieBackedTest extends TestCase
{
    /**
     * @throws CupcakeException
     */
    public function testGenerateCheck()
    {
        $storage = [];
        $cookie = new CookieBackedDummy('cupcake-csrf', 'cupcake-csrf', $storage);
        $token = $cookie->getHiddenElement()->getValue();
        $this->assertSame($token, $storage['cupcake-csrf']);
        $this->assertTrue($cookie->checkToken($token), 'Validation fails');
    }
}
