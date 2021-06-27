<?php
declare(strict_types=1);
namespace Soatok\Cupcake;

use ParagonIE\Ionizer\InputFilterContainer;

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
}
