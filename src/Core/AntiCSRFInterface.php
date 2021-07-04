<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Core;

use Soatok\Cupcake\Ingredients\Input\Hidden;

/**
 * Interface AntiCSRFInterface
 * @package Soatok\Cupcake\Core
 */
interface AntiCSRFInterface
{
    /**
     * Check that the provided anti-CSRF token is correct
     *
     * @param string $token
     * @return bool
     */
    public function checkToken(string $token): bool;

    /**
     * Generates a new CSRF protection token
     *
     * @return string
     */
    public function generateToken(): string;

    /**
     * @return string
     */
    public function getFormName(): string;

    /**
     * Get a hidden input type to inject to the form.
     *
     * @return Hidden
     */
    public function getHiddenElement(): Hidden;

    /**
     * Asserts that the form contains a valid anti-CSRF token.
     *
     * @param array<string, scalar> $formData
     * @return bool
     */
    public function validate(array $formData): bool;
}
