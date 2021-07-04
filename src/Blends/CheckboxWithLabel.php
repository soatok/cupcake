<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Blends;

use Soatok\Cupcake\Core\Container;
use Soatok\Cupcake\Exceptions\InvalidMixtureException;
use Soatok\Cupcake\Ingredients\Input\Checkbox;
use Soatok\Cupcake\Ingredients\Label;

/**
 * Class CheckboxWithLabel
 * @package Soatok\Cupcake\Blends
 */
class CheckboxWithLabel extends Container
{
    /**
     * CheckboxWithLabel constructor.
     * @param Checkbox|null $checkbox
     * @param Label|null $label
     * @throws InvalidMixtureException
     */
    public function __construct(?Checkbox $checkbox = null, ?Label $label = null)
    {
        if ($checkbox) {
            $this->append($checkbox);
            if ($label) {
                $this->append($label);
            }
        } elseif ($label) {
            throw new InvalidMixtureException(
                'Labels cannot be provided if the checkbox is not'
            );
        }
    }

    /**
     * @return Checkbox|null
     */
    public function getCheckbox(): ?Checkbox
    {
        if ($this->ingredients[0] instanceof Checkbox) {
            return $this->ingredients[0];
        }
        return null;
    }

    /**
     * @return Label|null
     */
    public function getLabel(): ?Label
    {
        if ($this->ingredients[1] instanceof Label) {
            return $this->ingredients[1];
        }
        return null;
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $id
     * @param string $label
     * @param bool $checked
     * @return self
     */
    public static function create(
        string $name,
        string $value,
        string $id,
        string $label = '',
        bool $checked = false
    ): self {
        $self = new self();
        $checkbox = (new Checkbox($name))
            ->setChecked($checked)
            ->setValue($value)
            ->setId($id);
        $self->append($checkbox)->append(new Label($label, $checkbox));
        return $self;
    }

    /**
     * @param bool $value
     * @return self
     */
    public function setChecked(bool $value): self
    {
        $cb = $this->getCheckbox();
        if (!$cb) {
            return $this;
        }
        $cb->setChecked($value);
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        $cb = $this->getCheckbox();
        if (!$cb) {
            return '';
        }
        return $cb->getValue();
    }
}
