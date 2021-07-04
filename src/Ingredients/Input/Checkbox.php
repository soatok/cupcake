<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients\Input;

use Soatok\Cupcake\Core\IngredientInterface;
use Soatok\Cupcake\Core\ValueTrait;
use Soatok\Cupcake\Exceptions\CupcakeException;
use Soatok\Cupcake\FilterContainer;
use Soatok\Cupcake\Ingredients\InputTag;

/**
 * Class Checkbox
 * @package Soatok\Cupcake\Ingredients\Input
 */
class Checkbox extends InputTag
{
    use ValueTrait;

    protected bool $checked = false;
    protected string $type = 'checkbox';

    /**
     * Checkbox constructor.
     * @param string $name
     * @param string $id
     * @param string $value
     */
    public function __construct(string $name, string $id = '', string $value = '')
    {
        parent::__construct($name);
        $this->id = $id;
        $this->value = $value;
    }

    /**
     * @param bool $checked
     * @return self
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
     * @param array $untrusted
     * @return IngredientInterface
     * @throws CupcakeException
     */
    public function populateUserInput(array $untrusted): IngredientInterface
    {
        /** @var string[]|string[][] $pointer */
        $pointer = &$untrusted;
        $pieces = explode(FilterContainer::SEPARATOR, $this->getIonizerName());
        foreach ($pieces as $piece) {
            if (!array_key_exists($piece, $pointer)) {
                $this->checked = false;
                return $this;
            }
            /** @var string[]|string[][] $pointer */
            $pointer = &$pointer[$piece];
        }
        /** @var string|string[] $pointer */
        if (is_array($pointer)) {
            $this->checked = false;
            return $this;
        }
        $this->checked = $pointer === $this->value;
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
        $attributes = parent::customAttributes();
        if ($this->checked) {
            $attributes['checked'] = null;
        }
        return $attributes;
    }
}
