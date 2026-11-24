<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Core;

use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Core\NameTrait;
use Soatok\Cupcake\Exceptions\CupcakeException;

#[CoversTrait(NameTrait::class)]
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
