<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Core;

use Soatok\Cupcake\Exceptions\CupcakeException;
use Soatok\Cupcake\FilterContainer;

/**
 * Trait NameTrait
 * @package Soatok\Cupcake\Core
 */
trait NameTrait
{
    protected string $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return static
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the element name as Ionizer understands it.
     *
     * @return string
     * @throws CupcakeException
     */
    public function getIonizerName(): string
    {
        if (!str_contains($this->name, '[') && !str_contains($this->name, ']')) {
            // Fast response
            return $this->name;
        }
        $buffer = $this->name;
        $output = [];
        $index = 0;
        do {
            $left_pos = strpos($buffer, '[');
            if ($left_pos === false) {
                break;
            }
            $left_chunk = substr($buffer, 0, $left_pos);

            if ($index > 0 && $left_pos !== 0) {
                throw new CupcakeException('Could not parse element name');
            } elseif ($index === 0) {
                if ($left_pos === 0) {
                    throw new CupcakeException('Could not parse element name');
                }
                // Only the first element gets included:
                $output []= preg_replace('/[^A-Za-z0-9\-_]/', '', $left_chunk);
            }

            $buffer = substr($buffer, $left_pos + 1);
            $right_pos = strpos($buffer, ']');
            if (!$right_pos) {
                // We're done parsing now
                break;
            }
            $right_chunk = substr($buffer, 0, $right_pos);
            // The second element gets included:
            $output []= preg_replace('/[^A-Za-z0-9\-_]/', '', $right_chunk);
            $buffer = substr($buffer, $right_pos + 1);
            ++$index;
        } while (str_contains($buffer, '['));
        return implode(FilterContainer::SEPARATOR, $output);
    }
}
