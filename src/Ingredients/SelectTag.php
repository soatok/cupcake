<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Container;
use Soatok\Cupcake\Core\NameTrait;

/**
 * Class SelectTag
 * @package Soatok\Cupcake\Ingredients
 */
class SelectTag extends Container
{
    use NameTrait;

    /** @var array<array-key, Option|Optgroup> $options */
    protected array $options = [];

    /**
     * SelectTag constructor.
     * @param string $name
     */
    public function __construct(string $name = '')
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
        $elements = [];
        if (!empty($this->name)) {
            $elements['name'] = null;
        }
        return $elements;
    }

    /**
     * Render this button, and everything it contains.
     *
     * @return string
     */
    public function renderBefore(): string
    {
        return sprintf(
            '<select%s>',
            $this->flattenAttributes()
        );
    }

    /**
     * @return string
     */
    public function renderAfter(): string
    {
        return '</select>';
    }
}
