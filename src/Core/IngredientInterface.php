<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Core;

use ParagonIE\Ionizer\InputFilter;

/**
 * Interface IngredientInterface
 * @package Soatok\Cupcake\Core
 */
interface IngredientInterface
{
    /**
     * Returns a map where:
     *
     * - key   -> key to include in flattenAttributes()
     * - value -> property of the object (or NULL if identical to key)
     *
     * @return array<string, ?string>
     */
    public function customAttributes(): array;

    /**
     * @return string
     */
    public function getId(): string;

    /**
     * Return the HTML to display to the end user.
     *
     * @return string
     */
    public function render(): string;

    /**
     * @param string $id
     * @return self
     */
    public function setId(string $id): self;

    /**
     * Direct key-value pair to include in output
     *
     * @return array<string, string>
     */
    public function renderAttributes(): array;

    /**
     * @param array $untrusted
     * @return IngredientInterface
     */
    public function populateUserInput(array $untrusted): IngredientInterface;
}
