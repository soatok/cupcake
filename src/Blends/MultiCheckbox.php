<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Blends;

use Exception;
use ParagonIE\Ionizer\Filter\{
    ArrayFilter,
    BoolArrayFilter,
    FloatArrayFilter,
    IntArrayFilter,
    StringArrayFilter
};
use ParagonIE\Ionizer\InputFilter;
use Soatok\Cupcake\Core\Container;
use Soatok\Cupcake\Core\NameTrait;

/**
 * Class MultiCheckbox
 * @package Soatok\Cupcake\Blends
 */
class MultiCheckbox extends Container
{
    use NameTrait;
    protected ?InputFilter $filter = null;

    /**
     * MultiCheckbox constructor.
     *
     * @param string $name
     * @param InputFilter|null $filter
     */
    public function __construct(string $name, ?InputFilter $filter = null)
    {
        $this->name = (string) preg_replace('#[\[\]]#', '',  $name);
        $this->filter = $filter;
    }

    /**
     * @param string $label
     * @param string $value
     * @param bool $checked
     * @return self
     * @throws Exception
     */
    public function addCheckbox(
        string $label,
        string $value = '',
        bool $checked = false
    ): self {
        $this->append(CheckboxWithLabel::create(
            $this->name . '[]',
            $value,
            'check-' . bin2hex(random_bytes(8)),
            $label,
            $checked
        ));
        return $this;
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
        if ($this->filter) {
            $inputFilters[$this->name] = $this->filter;
            $objectsVisited []= spl_object_hash($this);
            return;
        }

        // Let's figure out which types are allowed by this checkbox
        $typesSeen = [];
        foreach ($this->ingredients as $ingredient) {
            if (!($ingredient instanceof CheckboxWithLabel)) {
                continue;
            }
            $checkbox = $ingredient->getCheckbox();
            if (is_null($checkbox)) {
                continue;
            }
            $type = $checkbox->getFilterType();
            if (is_null($type)) {
                continue;
            }
            if (!in_array($type, $typesSeen, true)) {
                $typesSeen []= $type;
            }
        }
        $inputFilters[$this->name] = self::determineInternalTypes($typesSeen);
        $objectsVisited []= spl_object_hash($this);
    }

    /**
     * @param InputFilter $filter
     * @return self
     */
    public function setFilter(InputFilter $filter): self
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * Get the input filter for this specialized container
     *
     * @param string[] $typesSeen
     * @return InputFilter
     */
    public static function determineInternalTypes(array $typesSeen): InputFilter
    {
        if (empty($typesSeen) || in_array('mixed', $typesSeen, true)) {
            // No types: Untyped
            return new ArrayFilter();
        }
        if (count($typesSeen) > 1) {
            if (in_array('int', $typesSeen) && in_array('float', $typesSeen)) {
                // int|float -> float
                return new FloatArrayFilter();
            }
            // More than 1 type: Untyped
            return new ArrayFilter();
        }
        // If only one type: Try to restrict the type appropriately:
        $type = array_shift($typesSeen);
        return match ($type) {
            'bool' => new BoolArrayFilter(),
            'float' => new FloatArrayFilter(),
            'int' => new IntArrayFilter(),
            'string' => new StringArrayFilter(),
            default => new ArrayFilter(),
        };
    }
}
