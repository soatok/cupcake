<?php
namespace Soatok\Cupcake\Ingredients\Input;

use Soatok\Cupcake\Core\IngredientInterface;
use Soatok\Cupcake\Ingredients\InputTag;

/**
 * Class Password
 * @package Soatok\Cupcake\Ingredients\Input
 */
class Password extends InputTag
{
    protected string $type = 'password';

    /**
     * Never redisplay submitted secrets or file paths in a password input.
     */
    #[\Override]
    public function populateUserInput(array $untrusted): IngredientInterface
    {
        $this->value = '';
        return $this;
    }
}
