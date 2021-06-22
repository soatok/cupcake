<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Element;

/**
 * Class InputTag
 * @package Soatok\Cupcake\Ingredients
 */
class InputTag extends Element
{
    protected string $type = 'text';

    /**
     * InputTag constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Returns a map where:
     *
     * - key   -> key to include in flattenAttributes()
     * - value -> property of the object (or NULL if identical to key)
     *
     * @return array<string, ?string>
     */
    public function customAttributes(): array
    {
        return ['type' => null, 'name' => null];
    }

    /**
     * Render this button, and everything it contains.
     *
     * @return string
     */
    public function render(): string
    {
        return sprintf(
            '<input%s />',
            $this->flattenAttributes()
        );
    }
}
