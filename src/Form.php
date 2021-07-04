<?php
declare(strict_types=1);
namespace Soatok\Cupcake;

use Soatok\Cupcake\Core\AntiCSRFInterface;
use Soatok\Cupcake\Core\Container;
use Soatok\Cupcake\Core\Utilities;
use Soatok\Cupcake\Exceptions\CupcakeException;

/**
 * Class Form
 * @package Soatok\Cupcake
 */
class Form extends Container
{
    protected bool $antiCSRFdisabled = false;
    protected string $method = 'GET';
    protected string $action = '';
    protected string $enctype = '';
    protected ?AntiCSRFInterface $antiCSRF = null;

    public function getAction(): string
    {
        return $this->action;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function disableAntiCSRF(): self
    {
        $this->antiCSRFdisabled = true;
        return $this;
    }

    public function enableAntiCSRF(): self
    {
        $this->antiCSRFdisabled = false;
        return $this;
    }

    /**
     * @param bool $ignoreDefault
     * @return AntiCSRFInterface
     * @throws CupcakeException
     */
    public function getAntiCSRF(bool $ignoreDefault = false): AntiCSRFInterface
    {
        if (is_null($this->antiCSRF)) {
            if ($ignoreDefault) {
                throw new CupcakeException('No Anti-CSRF class configured.');
            }
            return Utilities::defaultAntiCSRF();
        }
        return $this->antiCSRF;
    }

    /**
     * @param AntiCSRFInterface $antiCSRF
     * @return self
     */
    public function setAntiCSRF(AntiCSRFInterface $antiCSRF): self
    {
        $this->antiCSRF = $antiCSRF;
        return $this;
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

    /**
     * @return string
     * @throws CupcakeException
     */
    public function render(): string
    {
        if (!$this->antiCSRFdisabled) {
            if (!$this->elementExistsWithName($this->getAntiCSRF()->getFormName())) {
                // We need to inject this.
                $this->append($this->getAntiCSRF()->getHiddenElement());
            }
        }
        return parent::render();
    }
}
