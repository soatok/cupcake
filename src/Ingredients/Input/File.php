<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients\Input;

use Soatok\Cupcake\Core\IngredientInterface;
use Soatok\Cupcake\Ingredients\InputTag;

/**
 * Class File
 * @package Soatok\Cupcake\Ingredients
 */
class File extends InputTag
{
    protected string $type = 'file';

    #[\Override]
    public function populateUserInput(array $untrusted): IngredientInterface
    {
        $this->value = '';
        return $this;
    }
}
