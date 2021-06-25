<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Core;

/**
 * Trait NameTrait
 * @package Soatok\Cupcake\Core
 */
trait NameTrait
{
    protected string $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
