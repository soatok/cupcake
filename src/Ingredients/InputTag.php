<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Element;
use Soatok\Cupcake\Core\ValueTrait;

/**
 * Class InputTag
 * @package Soatok\Cupcake\Ingredients
 */
class InputTag extends Element
{
    use ValueTrait;
    protected string $type = 'text';
    protected ?Datalist $datalist = null;

    /**
     * InputTag constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param Datalist $list
     * @return self
     */
    public function setList(Datalist $list): self
    {
        $this->datalist = $list;
        return $this;
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
        $attributes = ['type' => null, 'name' => null];
        if (!empty($this->value)) {
            $attributes['value'] = null;
        }
        return $attributes;
    }

    /**
     * Direct key-value pair to include in output
     *
     * @return array<string, string>
     */
    public function renderAttributes(): array
    {
        if ($this->datalist) {
            return ['list' => $this->datalist->getId()];
        }
        return parent::renderAttributes();
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
