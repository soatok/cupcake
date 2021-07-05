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
     * @return self
     */
    public function disableAntiCSRF(): self
    {
        $this->antiCSRFdisabled = true;
        return $this;
    }

    /**
     * @return self
     */
    public function enableAntiCSRF(): self
    {
        $this->antiCSRFdisabled = false;
        return $this;
    }

    /**
     * Append the Anti-CSRF element if it doesn't already exist.
     *
     * @return self
     * @throws CupcakeException
     */
    public function finalizeCsrfElement(): self
    {
        if (!$this->antiCSRFdisabled) {
            if (!$this->elementExistsWithName($this->getAntiCSRF()->getFormName())) {
                // We need to inject this.
                $this->append($this->getAntiCSRF()->getHiddenElement());
            }
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Get the current Anti-CSRF mitigation.
     *
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
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
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
     * @throws CupcakeException
     */
    public function render(): string
    {
        if (!$this->antiCSRFdisabled) {
            $this->finalizeCsrfElement();
        }
        return parent::render();
    }

    /**
     * Set the action attribute for this form.
     *
     * @param string $action
     * @return self
     */
    public function setAction(string $action): self
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Overwrite the anti-CSRF mitigation with another implementation at runtime.
     *
     * @param AntiCSRFInterface $antiCSRF
     * @return self
     */
    public function setAntiCSRF(AntiCSRFInterface $antiCSRF): self
    {
        $this->antiCSRF = $antiCSRF;
        return $this;
    }

    /**
     * Set the HTTP request method for this form.
     *
     * @param string $method
     * @return self
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }
}
