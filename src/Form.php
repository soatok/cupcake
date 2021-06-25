<?php
declare(strict_types=1);
namespace Soatok\Cupcake;

use Soatok\Cupcake\Core\Container;

/**
 * Class Form
 * @package Soatok\Cupcake
 */
class Form extends Container
{
    protected string $method = 'GET';
    protected string $action = '';
    protected string $enctype = '';

    public function getAction(): string
    {
        return $this->action;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;
        return $this;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Returns a map where:
     *
     * - key   -> key to include in flattenAttributes()
     * - value -> property of the object (or NULL if identical to key)
     *
     * @return array<string, ?string>
     */
    public function customAttributes(): array
    {
        $mapping = [
            'method' => null,
            'action' => null
        ];
        if ($this->hasFileInput()) {
            // Add enctype="multipart/form-data"
            $mapping['enctype'] = null;
            if (empty($this->enctype)) {
                $this->enctype = 'multipart/form-data';
            }
        } elseif (!empty($this->enctype)) {
            // Add whatever the user provided
            $mapping['enctype'] = null;
        }
        return $mapping;
    }

    /**
     * @return string
     */
    public function renderBefore(): string
    {
        return sprintf(
            '<form%s>',
            $this->flattenAttributes(),
        );
    }

    /**
     * @return string
     */
    public function renderAfter(): string
    {
        return '</form>';
    }
}
