<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Element;
use Soatok\Cupcake\Core\IngredientInterface;
use Soatok\Cupcake\Core\NameTrait;
use Soatok\Cupcake\Core\Utilities;
use Soatok\Cupcake\Exceptions\CupcakeException;
use Soatok\Cupcake\FilterContainer;

/**
 * Class Textarea
 * @package Soatok\Cupcake\Core
 */
class Textarea extends Element
{
    use NameTrait;

    protected string $contents;

    public function __construct(string $name = '', string $contents = '')
    {
        $this->name = $name;
        $this->contents = $contents;
    }

    public function customAttributes(): array
    {
        $attributes = [];
        if (!empty($this->name)) {
            $attributes['name'] = null;
        }
        return $attributes;
    }

    public function render(): string
    {
        return sprintf(
            '<textarea%s>%s</textarea>',
            $this->flattenAttributes(),
            Utilities::escapeAttribute($this->contents)
        );
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
                return $this;
            }
            /** @var string[]|string[][] $pointer */
            $pointer = &$pointer[$piece];
        }
        /** @var string|string[] $pointer */
        if (is_array($pointer)) {
            return $this;
        }
        $this->contents = $pointer;
        return $this;
    }
}
