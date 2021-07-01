<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Blends;

use Exception;
use ParagonIE\Ionizer\Filter\AllowList;
use Soatok\Cupcake\Core\Container;
use Soatok\Cupcake\Core\IngredientInterface;
use Soatok\Cupcake\Core\NameTrait;
use Soatok\Cupcake\Core\ValueTrait;
use Soatok\Cupcake\Exceptions\CupcakeException;
use Soatok\Cupcake\FilterContainer;
use Soatok\Cupcake\Ingredients\Input\Radio;
use Soatok\Cupcake\Ingredients\Label;

/**
 * Class RadioSet
 * @package Soatok\Cupcake\Ingredients
 */
class RadioSet extends Container
{
    use NameTrait, ValueTrait;

    /**
     * RadioSet constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * A set of radio buttons should manifest an AllowList.
     *
     * @param array $objectsVisited
     * @param array $inputFilters
     */
    protected function getInputFiltersInternal(
        array &$objectsVisited,
        array &$inputFilters
    ): void {
        if (in_array(spl_object_hash($this), $objectsVisited, true)) {
            // Prevent cycles.
            return;
        }
        $allowList = [];
        /** @var Radio $ingredient */
        foreach ($this->ingredients as $ingredient) {
            $allowList []= $ingredient->getValue();
        }
        $inputFilters[$this->name] = new AllowList(...$allowList);
        $objectsVisited []= spl_object_hash($this);
    }

    /**
     * Adds a new Radio and a new Label, and relates the two.
     *
     * @param string $value
     * @param string $label
     * @param string|null $id
     * @param bool $selected
     * @return self
     * @throws Exception
     */
    public function addRadio(
        string $value,
        string $label = '',
        ?string $id = null,
        bool $selected = false
    ): self {
        if (!$id) {
            $id = 'radio-' . bin2hex(random_bytes(8));
        }
        $radio = (new Radio($this->name))
            ->setChecked($selected)
            ->setValue($value)
            ->setId($id);
        $label = new Label($label, $radio);
        $this->append($radio)->append($label);
        return $this;
    }

    /**
     * @param array $untrusted
     * @return IngredientInterface
     * @throws CupcakeException
     */
    public function populateUserInput(array $untrusted): IngredientInterface
    {
        $this->clearAllChecks();
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
        /** @var Radio|Label $ingredient */
        foreach ($this->ingredients as $ingredient) {
            if ($ingredient instanceof Label) {
                continue;
            }
            if ($ingredient->getValue() === $pointer) {
                $ingredient->setChecked(true);
            }
        }
        return $this;
    }

    /**
     * @return self
     */
    protected function clearAllChecks(): self
    {
        /** @var Radio|Label $ingredient */
        foreach ($this->ingredients as $ingredient) {
            if ($ingredient instanceof Label) {
                continue;
            }
            $ingredient->setChecked(false);
        }
        return $this;
    }
}
