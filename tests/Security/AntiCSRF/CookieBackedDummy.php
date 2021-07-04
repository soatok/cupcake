<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Security\AntiCSRF;
use Soatok\Cupcake\Security\AntiCSRF\CookieBacked;

/**
 * Class CookieBackedDummy
 * @package Soatok\Cupcake\Tests\Security\AntiCSRF
 */
class CookieBackedDummy extends CookieBacked
{
    /**
     * Don't actually call setcookie() in PHPUnit
     *
     * @param string $token
     * @return string
     */
    protected function storeTokenInCookie(string $token): string
    {
        $this->cookie[$this->cookieName] = $token;
        return $token;
    }
}
