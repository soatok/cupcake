<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Core;

use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Core\NameTrait;
use Soatok\Cupcake\Exceptions\CupcakeException;

/**
 * Class NameTraitTest
 * @package Soatok\Cupcake\Tests\Core
 *
 * @covers NameTrait
 */
class NameTraitTest extends TestCase
{
    protected ClassWithNameTrait $testClass;

    public function setUp(): void
    {
        $this->testClass = new ClassWithNameTrait();
    }

    /**
     * @throws CupcakeException
     */
    public function testIonizerName()
    {
        $this->testClass->setName('abc[defg]');
        $this->assertSame(
            'abc::defg',
            $this->testClass->getIonizerName()
        );
        $this->testClass->setName('abc[defg][x]');
        $this->assertSame(
            'abc::defg::x',
            $this->testClass->getIonizerName()
        );
        $this->testClass->setName('abc[[defg][x]');
        $this->assertSame(
            'abc::defg::x',
            $this->testClass->getIonizerName()
        );
        $this->testClass->setName('abc[[defg][x][]');
        $this->assertSame(
            'abc::defg::x',
            $this->testClass->getIonizerName()
        );
    }
}