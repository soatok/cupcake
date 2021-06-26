<?php
declare(strict_types=1);
namespace Soatok\Cupcake;

use Exception;
use Soatok\Cupcake\Core\Container;
use Soatok\Cupcake\Core\NameTrait;
use Soatok\Cupcake\Core\ValueTrait;
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
     * Adds a new Radio and a new Label, and relates the two.
     *
     * @param string $value
     * @param string $label
     * @param string|null $id
     * @param bool $selected
     * @return self
     * @throws Exception
     */
    public function addRadio(string $value, string $label = '', ?string $id = null, bool $selected = false): self
    {
        if (!$id) {
            $id = 'radio-' . bin2hex(random_bytes(8));
        }
        $radio = (new Radio($this->name))
            ->setValue($value)
            ->setId($id);
        $label = new Label($label, $radio);
        $this->append($radio)->append($label);
        return $this;
    }
}
