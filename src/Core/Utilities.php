<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Core;

use HTMLPurifier;
use Soatok\Cupcake\Exceptions\InvalidDataKeyException;

/**
 * Class Utilities
 * @package Soatok\Cupcake\Core
 */
class Utilities
{
    private static ?Utilities $instance = null;
    private HTMLPurifier $purifier;

    /**
     * Utilities constructor.
     * @param HTMLPurifier|null $purifier
     */
    private function __construct(
        ?HTMLPurifier $purifier = null
    ) {
        if (!is_null($purifier)) {
            $this->purifier = $purifier;
        } else {
            $config = \HTMLPurifier_Config::createDefault();
            $this->purifier = new HTMLPurifier($config);
        }
    }

    /**
     * @param string $input
     * @return string
     */
    public static function escapeAttribute(string $input): string
    {
        return htmlentities($input, ENT_HTML5 | ENT_QUOTES, 'utf-8');
    }

    /**
     * @param array $classes
     * @return string
     */
    public static function escapeClasses(array $classes): string
    {
        $filtered = [];
        foreach ($classes as $class) {
            if (preg_match('#-?[_a-zA-Z]+[_a-zA-Z0-9-]*#', trim($class))) {
                $filtered []= $class;
            }
        }
        return implode(' ', $filtered);
    }

    /**
     * Singleton accessor with dependency injection support.
     *
     * @param HTMLPurifier|null $purifier
     * @return self
     */
    public static function getInstance(
        ?HTMLPurifier $purifier = null
    ): self {
        if (is_null(self::$instance)) {
            // This is the only time we pass parameters
            self::$instance = new Utilities(
                $purifier
            );
        }
        // We don't pass parameters if this has already been instantiated.
        return self::$instance;
    }

    /**
     * @param string $input
     * @return string
     */
    public static function purify(string $input): string
    {
        return self::getInstance()
            ->purifier
            ->purify($input);
    }

    /**
     * @param string $key
     * @return string
     * @throws InvalidDataKeyException
     */
    public static function validateHtmlDataKey(string $key): string
    {
        if ($key === '') {
            throw new InvalidDataKeyException('data-* attributes cannot be empty after the hyphen');
        }
        if (str_starts_with($key, 'xml')) {
            throw new InvalidDataKeyException('data-* attributes cannot begin with "xml"');
        }
        if (str_contains($key, ':')) {
            throw new InvalidDataKeyException('data-* attributes cannot contain colons (:)');
        }
        if (preg_match('/[A-Z]/', $key)) {
            throw new InvalidDataKeyException('data-* attributes cannot contain capital letters');
        }
        return $key;
    }
}
