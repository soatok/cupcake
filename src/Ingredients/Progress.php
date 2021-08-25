<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Element;
use Soatok\Cupcake\Core\Utilities;
use Soatok\Cupcake\Core\ValueTrait;

class Progress extends Element
{
    use ValueTrait;

    public function __construct(
        protected string $description = '',
        protected int|float|null $max = null,
    ) {}

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
        $attributes = [];
        if (!empty($this->max)) {
            $attributes['max'] = null;
        }
        if (!empty($this->name)) {
            $attributes['name'] = null;
        }
        if (!empty($this->value)) {
            $attributes['value'] = null;
        }
        return $attributes;
    }

    public function render(): string
    {
        return sprintf(
            '<progress%s>%s</progress>',
            $this->flattenAttributes(),
            Utilities::escapeAttribute($this->description)
        );
    }

    public function setMax(float|int $max): void
    {
        $this->max = $max;
    }
}
