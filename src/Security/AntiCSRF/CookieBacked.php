<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Security\AntiCSRF;

use Soatok\Cupcake\Core\AntiCSRFInterface;
use Soatok\Cupcake\Exceptions\CupcakeException;
use Soatok\Cupcake\Ingredients\Input\Hidden;

/**
 * Class CookieBacked
 * @package Soatok\Cupcake\Security\AntiCSRF
 */
class CookieBacked implements AntiCSRFInterface
{
    protected array $cookie;
    protected string $cookieName;
    protected string $formName;

    /**
     * CookieBacked constructor.
     * @param string $cookieName
     * @param string $formName
     */
    public function __construct(
        string $cookieName = 'cupcake-csrf',
        string $formName = 'cupcake-csrf',
        ?array &$cookie = null
    ) {
        $this->cookieName = $cookieName;
        $this->formName = $formName;
        if (!is_null($cookie)) {
            $this->cookie =& $cookie;
        } else {
            $this->cookie =& $_COOKIE;
        }
    }

    /**
     * @param bool $generate Generate a cookie if it's not set?
     * @return string
     *
     * @throws CupcakeException
     */
    protected function getCookieValue(bool $generate): string
    {
        if (isset($this->cookie[$this->cookieName])) {
            if (!is_string($this->cookie[$this->cookieName])) {
                throw new \TypeError('Cookie value is not a string');
            }
            return $this->cookie[$this->cookieName];
        }
        if (!$generate) {
            throw new CupcakeException('No Anti-CSRF token found');
        }
        return $this->storeTokenInCookie(
            $this->generateToken()
        );
    }

    /**
     * @param string $token
     * @return string
     */
    protected function storeTokenInCookie(string $token): string
    {
        setcookie($this->cookieName, $token, [
            // This only needs to live for the session
            'expires' => 0,
            // If this breaks because you don't have TLS, then write your own.
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        return $token;
    }

    /**
     * @return Hidden
     * @throws CupcakeException
     */
    public function getHiddenElement(): Hidden
    {
        return (new Hidden('anti-csrf'))
            ->setValue($this->getCookieValue(true));
    }

    /**
     * @param array<string, scalar> $formData
     * @return bool
     *
     * @throws CupcakeException
     */
    public function validate(array $formData): bool
    {
        if (!isset($formData[$this->formName])) {
            return false;
        }
        if (!is_string($formData[$this->formName])) {
            throw new \TypeError('Input is not a string');
        }
        return $this->checkToken($formData[$this->formName]);
    }

    /**
     * Check that the provided anti-CSRF token is correct
     *
     * @param string $token
     * @return bool
     *
     * @throws CupcakeException
     */
    public function checkToken(string $token): bool
    {
        $expected = $this->getCookieValue(false);
        return hash_equals($expected, $token);
    }

    /**
     * Generates a new CSRF protection token
     *
     * @return string
     */
    public function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * @return string
     */
    public function getFormName(): string
    {
        return $this->formName;
    }
}
