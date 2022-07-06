<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Core;

use ParagonIE\Ionizer\{InputFilter, InputFilterContainer, InvalidDataException};
use Soatok\Cupcake\Exceptions\{
    ChildNotFoundException,
    CupcakeException
};
use Soatok\Cupcake\FilterContainer;
use Soatok\Cupcake\Ingredients\{
    Input\File,
    Label
};

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

    public function __clone()
    {
        foreach ($this->ingredients as $index => $original) {
            $this->ingredients[$index] = clone $original;
        }
    }

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
     * Append multiple ingredients in one shot.
     *
     * @param IngredientInterface[] $ingredients
     * @return self
     * @throws CupcakeException
     */
    public function appendArray(array $ingredients) : self
    {
        foreach($ingredients as $ingredient) {
            if ($ingredient instanceof IngredientInterface) {
                $this->ingredients[] = $ingredient;
            } else {
                throw new CupcakeException('Invalid ingredient');
            }
        }
        return $this;
    }

    /**
     * @param IngredientInterface $ingredient
     * @return self
     */
    public function prepend(IngredientInterface $ingredient): self
    {
        array_unshift($this->ingredients, $ingredient);
        return $this;
    }

    /**
     * Prepend multiple ingredients in one shot.
     *
     * @param IngredientInterface[] $ingredients
     * @return self
     * @throws CupcakeException
     */
    public function prependArray(array $ingredients) : self
    {
        foreach($ingredients as $ingredient) {
            if ($ingredient instanceof IngredientInterface) {
                array_unshift($this->ingredients, $ingredient);
            } else {
                throw new CupcakeException('Invalid ingredient');
            }
        }
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
        /** @var string[] $objectsVisited */
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
     * @param string[] &$objectsVisited
     * @param-out string[] &$objectsVisited
     * @return string
     */
    protected function renderComponents(array &$objectsVisited): string
    {
        $rendered = '';
        $components = $this->renderInternal($objectsVisited);
        /** @var string $component */
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
     * @param string[] &$objectsVisited
     * @return array
     * @param-out string[] &$objectsVisited
     */
    protected function renderInternal(array &$objectsVisited): array
    {
        if (in_array(spl_object_hash($this), $objectsVisited, true)) {
            // Prevent cycles.
            return [];
        }
        /** @var string[] $objectsVisited */
        $objectsVisited[] = spl_object_hash($this);
        /** @var string[] $pieces */
        $pieces = [];
        foreach ($this->ingredients as $ingredient) {
            $hash = spl_object_hash($ingredient);
            if (in_array($hash, $objectsVisited, true)) {
                // Prevent cycles.
                continue;
            }
            // The order of when we append $hash to $objectsVisited matters:
            if ($ingredient instanceof Container) {
                $piece = $ingredient->renderComponents($objectsVisited);
                $objectsVisited[] = $hash;
            } else {
                $objectsVisited[] = $hash;
                $piece = $ingredient->render();
            }
            $pieces[] = $this->renderIngredient($piece);
        }
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
     * @param string[] &$objectsVisited
     * @param array<string, InputFilter> $inputFilters
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
                $ingredient->getInputFiltersInternal($objectsVisited, $inputFilters);
            } else {
                $filter = $ingredient->getFilter();
                if ($filter instanceof InputFilter) {
                    $inputFilters[$ingredient->getIonizerName()] = $filter;
                }
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
        /** @var string[] $visited */
        $visited = [];
        /** @var array<string, InputFilter> $filters */
        $filters = [];
        $this->getInputFiltersInternal($visited, $filters);

        $container = new FilterContainer();
        /**
         * @var string $name
         * @var InputFilter $filter
         */
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

    /**
     * @param string $contents
     * @return static
     *
     * @throws CupcakeException
     */
    public function createAndAppendLabel(string $contents): self
    {
        $index = $this->getLastElementIndex();
        /** @var Element|Container $ingredient */
        $ingredient = $this->ingredients[$index];
        $label = new Label($contents, $ingredient);
        $this->append($label);
        return $this;
    }

    /**
     * @param string $contents
     * @return static
     *
     * @throws CupcakeException
     */
    public function createAndPrependLabel(string $contents): self
    {
        // Get the correct index for the most recent element.
        $index = $this->getLastElementIndex();
        /** @var Element|Container|null $ingredient */
        $ingredient = $this->ingredients[$index];
        $label = new Label($contents, $ingredient);

        // Merge in the label _before_ the ingredient.
        $before = array_slice($this->ingredients, 0, $index);
        $after = array_slice($this->ingredients, $index);
        /** @var array<array-key, IngredientInterface> $merged */
        $merged = array_merge(
            $before,
            [$label, $ingredient],
            $after
        );
        $this->ingredients = $merged;
        return $this;
    }

    /**
     * Return an element by its ID. Optionally search all child containers.
     *
     * @param string $id
     * @param bool $recursive
     * @return IngredientInterface
     * @throws ChildNotFoundException
     */
    public function getChildById(string $id, bool $recursive = false): IngredientInterface
    {
        // Search the top level first.
        foreach ($this->ingredients as $ingredient) {
            if ($ingredient->getId() === $id) {
                return $ingredient;
            }
        }

        // Now let's do a recursive search, if selected:
        if ($recursive) {
            foreach ($this->ingredients as $ingredient) {
                if (!($ingredient instanceof Container)) {
                    continue;
                }
                /** @var Container $ingredient */
                return $ingredient->getChildById($id, true);
            }
        }
        throw new ChildNotFoundException(
            'No child element of this container found with id = ' . $id
        );
    }

    /**
     * @return int
     * @throws CupcakeException
     */
    protected function getLastElementIndex(): int
    {
        /** @var int[] $keys */
        $keys = array_keys($this->ingredients);
        // Get the correct index for the most recent element.
        do {
            if (empty($keys)) {
                throw new CupcakeException('No elements found in this container');
            }
            /** @var int $index */
            $index = array_pop($keys);
        } while (!($this->ingredients[$index] instanceof Element));
        return $index;
    }

    /**
     * @param string $id
     * @return int
     * @throws ChildNotFoundException
     */
    protected function getChildIndexById(string $id): int
    {
        foreach ($this->ingredients as $index => $ingredient) {
            if ($ingredient->getId() === $id) {
                return $index;
            }
        }
        throw new ChildNotFoundException(
            'No child element of this container found with id = ' . $id
        );
    }

    /**
     * @param array $untrusted
     * @return IngredientInterface
     */
    public function populateUserInput(array $untrusted): IngredientInterface
    {
        foreach ($this->ingredients as $ingredient) {
            $ingredient->populateUserInput($untrusted);
        }
        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function elementExistsWithName(string $name): bool
    {
        // Check all top-level elements
        foreach ($this->ingredients as $ingredient) {
            if ($ingredient instanceof Container) {
                // We need to skip all containers for now.
                continue;
            }
            if ($ingredient instanceof Element) {
                if ($ingredient->getName() === $name) {
                    return true;
                }
            }
        }
        return false;
    }
}
