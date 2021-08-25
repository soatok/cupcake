<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Element;
use Soatok\Cupcake\Core\Utilities;
use Soatok\Cupcake\Core\ValueTrait;

class Meter extends Element
{
    use ValueTrait;

    public function __construct(
        protected string $description = '',
        protected int|float|null $min = null,
        protected int|float|null $max = null,
        protected int|float|null $low = null,
        protected int|float|null $high = null,
        protected int|float|null $optimum = null,
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
        if (!is_null($this->low)) {
            $attributes['low'] = null;
        }
        if (!is_null($this->high)) {
            $attributes['high'] = null;
        }
        if (!is_null($this->max)) {
            $attributes['max'] = null;
        }
        if (!is_null($this->min)) {
            $attributes['min'] = null;
        }
        if (!empty($this->name)) {
            $attributes['name'] = null;
        }
        if (!is_null($this->optimum)) {
            $attributes['optimum'] = null;
        }
        $attributes['value'] = null;
        return $attributes;
    }

    public function render(): string
    {
        return sprintf(
            '<meter%s>%s</meter>',
            $this->flattenAttributes(),
            Utilities::escapeAttribute($this->description)
        );
    }

    public function setHigh(float|int $high): void
    {
        $this->high = $high;
    }

    public function setLow(float|int $low): void
    {
        $this->low = $low;
    }

    public function setMax(float|int $max): void
    {
        $this->max = $max;
    }

    public function setMin(float|int $min): void
    {
        $this->min = $min;
    }

    public function setOptimum(float|int $optimum): void
    {
        $this->optimum = $optimum;
    }
}