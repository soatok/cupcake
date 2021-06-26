<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Core;

use ParagonIE\Ionizer\InputFilterContainer;
use ParagonIE\Ionizer\InvalidDataException;
use Soatok\Cupcake\Exceptions\CupcakeException;
use Soatok\Cupcake\FilterContainer;
use Soatok\Cupcake\Ingredients\Input\File;

/**
 * Class Container
 * @package Soatok\Cupcake\Core
 */
abstract class Container implements IngredientInterface
{
    use StapleTrait;

    /** @var IngredientInterface[] $ingredients */
    protected array $ingredients = [];
    protected string $afterEach = '';
    protected string $beforeEach = '';

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
     * Direct key-value pair to include in output
     *
     * @return array<string, string>
     */
    public function renderAttributes(): array
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
     * @return string
     */
    public function renderAfterEach(): string
    {
        return $this->afterEach;
    }

    /**
     * @return string
     */
    public function renderBeforeEach(): string
    {
        return $this->beforeEach;
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
            $rendered .= $this->renderBeforeEach();
            $rendered .= $this->renderIngredient($component);
            $rendered .= $this->renderAfterEach();
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

    /**
     * @param array $objectsVisited
     * @param array $inputFilters
     * @throws CupcakeException
     */
    protected function getInputFiltersInternal(
        array &$objectsVisited,
        array &$inputFilters
    ): void {
        if (in_array(spl_object_hash($this), $objectsVisited, true)) {
            // Prevent cycles.
            return;
        }
        /** @var Element|Container $ingredient */
        foreach ($this->ingredients as $ingredient) {
            $hash = spl_object_hash($ingredient);
            if (in_array($hash, $objectsVisited, true)) {
                // Prevent cycles.
                continue;
            }
            if ($ingredient instanceof Container) {
                $objectsVisited[] = $hash;
                $this->getInputFiltersInternal($objectsVisited, $inputFilters);
            } else {
                $inputFilters[$ingredient->getIonizerName()] = $ingredient->getFilter();
            }
        }
    }

    /**
     * @param string $after
     * @return static
     */
    public function setAfterEach(string $after): self
    {
        $this->afterEach = $after;
        return $this;
    }

    /**
     * @param string $before
     * @return static
     */
    public function setBeforeEach(string $before): self
    {
        $this->beforeEach = $before;
        return $this;
    }

    /**
     * @return InputFilterContainer
     * @throws CupcakeException
     */
    public function getInputFilters(): InputFilterContainer
    {
        $visited = [];
        $filters = [];
        $this->getInputFiltersInternal($visited, $filters);

        $container = new FilterContainer();
        foreach ($filters as $name => $filter) {
            $container->addFilter($name, $filter);
        }
        return $container;
    }

    /**
     * @param array $untrusted
     * @return array
     *
     * @throws CupcakeException
     * @throws InvalidDataException
     */
    public function getValidFormInput(array $untrusted): array
    {
        $if = $this->getInputFilters();
        return $if($untrusted);
    }
}
