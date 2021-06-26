<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Core;

/**
 * Trait ValueTrait
 * @package Soatok\Cupcake\Core
 */
trait ValueTrait
{
    protected string $value;

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return static
     */
    public function setValue(string $value)
    {
        $this->value = $value;
        return $this;
    }
}
