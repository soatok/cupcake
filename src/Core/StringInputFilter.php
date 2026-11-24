<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Core;

use ParagonIE\Ionizer\Filter\StringFilter;
use ParagonIE\Ionizer\InvalidDataException;

/**
 * Class StringInputFilter
 * @package Soatok\Cupcake\Core
 */
final class StringInputFilter extends StringFilter
{
    /**
     * @throws InvalidDataException
     */
    #[\Override]
    public function process($data = null): string
    {
        return parent::process($data);
    }

    /**
     * @throws InvalidDataException
     */
    #[\Override]
    public function applyCallbacks($data = null, int $offset = 0): string
    {
        $value = $data === null ? '' : (string) $data;
        if ($offset === 0 && $this->pattern !== '' && !preg_match($this->pattern, $value)) {
            throw new InvalidDataException(sprintf('Pattern match failed (%s).', $this->index));
        }
        if ($offset >= count($this->callbacks)) {
            return $value;
        }
        $callback = $this->callbacks[$offset];
        return $this->applyCallbacks($callback($value), $offset + 1);
    }
}
