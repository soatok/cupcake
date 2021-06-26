<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients\Input;

use Soatok\Cupcake\Core\ValueTrait;
use Soatok\Cupcake\Ingredients\InputTag;

/**
 * Class Radio
 * @package Soatok\Cupcake\Ingredients\Input
 */
class Radio extends InputTag
{
    use ValueTrait;

    protected bool $checked = false;
    protected string $type = 'radio';

    /**
     * @param bool $checked
     * @return $this
     */
    public function setChecked(bool $checked): self
    {
        $this->checked = $checked;
        return $this;
    }

    /**
     * @param string $name
     * @param bool $value
     * @return string
     */
    public function boolPropertyValue(string $name, bool $value): string
    {
        if ($name === 'checked') {
            return $value ? 'checked' : '';
        }
        return parent::boolPropertyValue($name, $value);
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
        $attributes = parent::customAttributes();
        if ($this->checked) {
            $attributes['checked'] = null;
        }
        return $attributes;
    }
}
