<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Core;

use Soatok\Cupcake\Ingredients\File;

/**
 * Class Container
 * @package Soatok\Cupcake\Core
 */
abstract class Container implements IngredientInterface
{
    use StapleTrait;

    /** @var IngredientInterface[] $ingredients */
    protected array $ingredients = [];

    /**
     * @param IngredientInterface $ingredient
     * @return self
     */
    public function append(IngredientInterface $ingredient): self
    {
        $this->ingredients[] = $ingredient;
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
        return [];
    }

    /**
     * @return IngredientInterface[]
     */
    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    /**
     * Override this in inherited classes.
     *
     * @return string
     */
    public function renderAfter(): string
    {
        return '';
    }

    /**
     * Override this in inherited classes.
     *
     * @return string
     */
    public function renderBefore(): string
    {
        return '';
    }

    /**
     * Render
     *
     * @param string $rendered
     * @return string
     */
    public function renderIngredient(string $rendered): string
    {
        return $rendered;
    }

    /**
     * Render this container, and everything it contains.
     *
     * @return string
     */
    public function render(): string
    {
        $objectsVisited = [];
        return $this->renderComponents($objectsVisited);
    }

    /**
     * Render all of the components as a flat string.
     *
     * @param array $objectsVisited
     * @return string
     */
    protected function renderComponents(array &$objectsVisited): string
    {
        $rendered = '';
        $components = $this->renderInternal($objectsVisited);
        foreach ($components as $component) {
            $rendered .= $this->renderIngredient($component);
        }
        return implode('', [$this->renderBefore(), $rendered, $this->renderAfter()]);
    }

    /**
     * Recursively walk down all other Containers and render the whole shebang.
     *
     * @param array $objectsVisited
     * @return array
     */
    protected function renderInternal(array &$objectsVisited): array
    {
        if (in_array(spl_object_hash($this), $objectsVisited, true)) {
            // Prevent cycles.
            return [];
        }
        $pieces = [];
        foreach ($this->ingredients as $ingredient) {
            $hash = spl_object_hash($ingredient);
            if (in_array($hash, $objectsVisited, true)) {
                // Prevent cycles.
                continue;
            }
            if ($ingredient instanceof Container) {
                $piece = $ingredient->renderComponents($objectsVisited);
                $objectsVisited[] = $hash;
            } else {
                $objectsVisited[] = $hash;
                $piece = $ingredient->render();
            }
            $pieces[] = $this->renderIngredient($piece);
        }
        $objectsVisited[] = spl_object_hash($this);
        return $pieces;
    }

    /**
     * @return bool
     */
    public function hasFileInput(): bool
    {
        foreach ($this->ingredients as $ingredient) {
            if ($ingredient instanceof File) {
                return true;
            }
            if ($ingredient instanceof Container) {
                // Recursive check
                if ($ingredient->hasFileInput()) {
                    return true;
                }
            }
        }
        return false;
    }
}
