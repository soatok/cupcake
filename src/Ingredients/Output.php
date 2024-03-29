<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Container;
use Soatok\Cupcake\Core\DoesNotPopulateTrait;
use Soatok\Cupcake\Core\Element;
use Soatok\Cupcake\Core\Utilities;

class Output extends Element
{
    use DoesNotPopulateTrait;
    protected string $contents;
    protected Element|Container|null $for = null;

    /**
     * Label constructor.
     * @param string $contents
     * @param Element|Container|null $for
     */
    public function __construct(string $contents = '', Element|Container|null $for = null)
    {
        $this->contents = $contents;
        if ($for) {
            $this->for = $for;
        }
    }

    /**
     * Direct key-value pair to include in output
     *
     * @return array<string, string>
     */
    public function renderAttributes(): array
    {
        if ($this->for) {
            return ['for' => $this->for->getId()];
        }
        return parent::renderAttributes();
    }

    /**
     * @param Element|Container|null $ingredient
     * @return self
     */
    public function setFor(Element|Container|null $ingredient): self
    {
        $this->for = $ingredient;
        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return sprintf(
            '<output%s>%s</output>',
            $this->flattenAttributes(),
            Utilities::purify($this->contents)
        );
    }
}
