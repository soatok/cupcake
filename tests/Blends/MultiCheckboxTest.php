<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests\Blends;

use ParagonIE\Ionizer\Filter\IntArrayFilter;
use PHPUnit\Framework\Attributes\CoversClass;
use Soatok\Cupcake\Blends\MultiCheckbox;
use PHPUnit\Framework\TestCase;

#[CoversClass(MultiCheckbox::class)]
class MultiCheckboxTest extends TestCase
{
    public function testEmpty()
    {
        $mc = new MultiCheckbox('test');
        $this->assertSame('', (string) $mc);
    }

    /**
     * @throws \Exception
     */
    public function testWithIntArrays()
    {
        $mc = (new MultiCheckbox('test'))
            ->addCheckbox('Apple', '1', false)
            ->addCheckbox('Bear', '2', false)
            ->setFilter(
                (new IntArrayFilter())
            );
        $out = $mc->getValidFormInput(['test' => ['1', 2]]);
        $this->assertSame(['test' => [1, 2]], $out);
    }
}
