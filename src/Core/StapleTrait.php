<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Core;

use Soatok\Cupcake\Exceptions\InvalidDataKeyException;

/**
 * Trait StapleTrait
 * @package Soatok\Cupcake\Core
 *
 * @method string render()
 * @method array<string, ?string> customAttributes()
 */
trait StapleTrait
{
    protected string $id = '';
    protected array $classes = [];
    protected array $data = [];

    /**
     * @param string $class
     * @return self
     */
    public function addClass(string $class): self
    {
        $this->classes []= $class;
        return $this;
    }

    /**
     * @param string $class
     * @return bool
     */
    public function removeClass(string $class): bool
    {
        $key = array_search($class, $this->classes, true);
        if (is_bool($key)) {
            return false;
        }
        unset($this->classes[$key]);
        return true;
    }

    /**
     * Override this to handle element-specific behaviors
     *
     * @param string $name
     * @param bool $value
     * @return string
     */
    public function boolPropertyValue(string $name, bool $value): string
    {
        return $value ? '1' : '0';
    }

    /**
     * Flatten the attributes and return them as a string.
     *
     * @return string
     */
    public function flattenAttributes(): string
    {
        $pieces = $this->renderAttributes();

        if (!empty($this->id)) {
            $pieces['id'] = Utilities::escapeAttribute($this->id);
        }

        if (!empty($this->classes)) {
            $pieces['class'] = Utilities::escapeClasses($this->classes);
        }

        foreach ($this->data as $key => $value) {
            $pieces['data-' . $key] = $value;
        }
        foreach ($this->customAttributes() as $key => $property) {
            if (is_null($property)) {
                $property = $key;
            }
            if (property_exists($this, $property)) {
                if (is_bool($this->{$property})) {
                    $pieces[$key] = $this->boolPropertyValue($property, $this->{$property});
                } else {
                    $pieces[$key] = $this->{$property};
                }
            }
        }

        if (empty($pieces)) {
            return '';
        }
        $return = '';
        foreach ($pieces as $attr => $value) {
            $return .= "{$attr}=\"" . Utilities::escapeAttribute((string) $value) . "\" ";
        }
        return ' ' . trim($return);
    }

    /**
     * @return string[]
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $key
     * @return string|null
     * @throws InvalidDataKeyException
     */
    public function getData(string $key): ?string
    {
        $key = Utilities::validateHtmlDataKey($key);
        if (!array_key_exists($key, $this->data)) {
            return null;
        }
        return $this->data[$key];
    }

    /**
     * @param string $key
     * @param string $value
     * @return self
     * @throws InvalidDataKeyException
     */
    public function setData(string $key, string $value): self
    {
        $this->data[Utilities::validateHtmlDataKey($key)] = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }
}
