<?php
declare(strict_types=1);
namespace Soatok\Cupcake;

use ParagonIE\Ionizer\{
    InputFilterContainer,
    InvalidDataException
};
use TypeError;

/**
 * Class FilterContainer
 * @package Soatok\Cupcake
 */
class FilterContainer extends InputFilterContainer
{
    public const SEPARATOR = '::';

    public function __construct()
    {
        // NOP.
    }

    public function validateInput(array $untrusted): ValidationResult
    {
        $data = [];
        $errors = [];
        foreach ($this->filterMap as $path => $filters) {
            $pieces = explode(self::SEPARATOR, $path);
            $value = $untrusted;
            $malformed = false;
            foreach ($pieces as $piece) {
                if (!is_array($value)) {
                    $malformed = $value !== null;
                    $value = null;
                    break;
                }
                $value = $value[$piece] ?? null;
            }
            if ($malformed) {
                $errors[$path] = ['Invalid field value.'];
                continue;
            }
            try {
                foreach ($filters as $filter) {
                    $value = $filter->process($value);
                }
            } catch (InvalidDataException | TypeError $ex) {
                // Custom filter exceptions may contain secrets; do not reflect them.
                $errors[$path] = ['Invalid field value.'];
                continue;
            }
            $target = &$data;
            foreach ($pieces as $piece) {
                if (!isset($target[$piece]) || !is_array($target[$piece])) {
                    $target[$piece] = [];
                }
                $target = &$target[$piece];
            }
            $target = $value;
            unset($target);
        }
        // Assigning through $target changes a nested value, never the root array.
        /** @var array $data */
        return new ValidationResult($errors ? [] : $data, $errors);
    }
}
