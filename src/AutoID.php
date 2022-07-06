<?php
declare(strict_types=1);
namespace Soatok\Cupcake;

use Soatok\Cupcake\Core\Container;
use SodiumException;

class AutoID
{
    private bool $objectHash;
    private string $key;
    private int $length;

    public function __construct(?string $key = null, int $length = 16, bool $objectHash = false)
    {
        if (is_null($key)) {
            $key = sodium_crypto_generichash_keygen();
        }
        $this->key = $key;
        $this->length = $length;
        $this->objectHash = $objectHash;
    }

    /**
     * @throws SodiumException
     */
    public function autoId(string $unique): string
    {
        return 'cupcake-' . sodium_bin2hex(
                sodium_crypto_generichash(
                    $unique,
                    $this->key,
                    $this->length
                )
            );
    }

    public function autoPopulate(Container $container): Container
    {
        $copy = clone $container;
        $obj = $this->objectHash ? spl_object_hash($this) : '';
        if (!$copy->idIsPopulated()) {
            $copy->setId($this->autoId(
                pack('P', 0) . $obj
            ));
        }
        return $this->recursivePopulate($copy, $obj);
    }

    public function setLength(int $length): void
    {
        $this->length = $length;
    }

    public function setObjectHash(bool $useObjectHash): void
    {
        $this->objectHash = $useObjectHash;
    }

    private function recursivePopulate(
        Container $container,
        string $prefix = '',
        int $depth = 1
    ): Container {
        $depthPacked = pack('P', $depth);
        $iterator = 0;
        foreach ($container->getIngredients() as $ingredient) {
            // Calculate the current position in the structure.
            $current = $prefix . pack('P', $iterator);
            if (!$ingredient->idIsPopulated()) {
                $ingredient->setId(
                    $this->autoId($depthPacked . $current)
                );
            }

            ++$iterator;
            if ($ingredient instanceof Container) {
                $this->recursivePopulate(
                    $ingredient,
                    $current,
                    $depth + 1
                );
            }
        }
        return $container;
    }
}
