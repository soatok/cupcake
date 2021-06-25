<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Element;
use Soatok\Cupcake\Core\NameTrait;
use Soatok\Cupcake\Core\Utilities;

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
        $elements = [];
        if (!empty($this->name)) {
            $elements['name'] = null;
        }
        return $elements;
    }

    public function render(): string
    {
        return sprintf(
            '<textarea%s>%s</textarea>',
            $this->flattenAttributes(),
            Utilities::escapeAttribute($this->contents)
        );
    }
}
