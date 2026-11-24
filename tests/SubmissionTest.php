<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Tests;

use ParagonIE\Ionizer\InvalidDataException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Soatok\Cupcake\Blends\{
    MultiCheckbox,
    RadioSet
};
use Random\RandomException;
use Soatok\Cupcake\Exceptions\CupcakeException;
use Soatok\Cupcake\Core\FieldFilter;
use Soatok\Cupcake\FilterContainer;
use Soatok\Cupcake\Form;
use Soatok\Cupcake\ValidationResult;
use Soatok\Cupcake\Ingredients\{
    Grouping,
    Optgroup,
    Option,
    SelectTag
};
use Soatok\Cupcake\Ingredients\Input\{
    Text,
    Password,
    File
};
use Soatok\Cupcake\Tests\Security\AntiCSRF\CookieBackedDummy;

#[CoversClass(Form::class)]
#[CoversClass(ValidationResult::class)]
#[CoversClass(FilterContainer::class)]
#[CoversClass(FieldFilter::class)]
class SubmissionTest extends TestCase
{
    /**
     * @throws CupcakeException
     * @throws RandomException
     */
    private function form(): Form
    {
        $storage = [];
        $csrf = new CookieBackedDummy('cookie', 'token', $storage);
        $csrf->getHiddenElement();
        return (new Form())->setMethod('POST')->setAntiCSRF($csrf)
            ->append((new Grouping())
                ->append(
                    (new Text('profile[name]'))
                    ->setRequired(true)
                    ->setPattern('^[a-z]+$')
                )
                ->append((new Password('password'))->setRequired(true)));
    }

    /**
     * @throws CupcakeException
     */
    public function testCrossFieldRulesAndFormErrors(): void
    {
        $called = false;
        $form = (new Form())->disableAntiCSRF()
            ->append((new Password('password'))->setRequired(true))
            ->append((new Password('confirm'))->setRequired(true))
            ->addValidator(static function (array $data) use (&$called): array {
                $called = true;
                return $data['password'] === $data['confirm']
                    ? []
                    : ['confirm' => ['Passwords must match.'], '' => ['Please review your submission.']];
            });
        self::assertFalse($form->submit([])->isValid());
        self::assertFalse($called);
        $result = $form->submit(['password' => 'secret', 'confirm' => 'other']);
        self::assertTrue($called);
        self::assertSame([], $result->getData());
        self::assertSame(['confirm' => ['Passwords must match.']], $result->getFieldErrors());
        self::assertSame(['Please review your submission.'], $result->getFormErrors());
        self::assertTrue($form->submit(['password' => 'secret', 'confirm' => 'secret'])->isValid());
    }

    /**
     * @throws CupcakeException
     */
    public function testChoiceControlsValidateAndRedisplay(): void
    {
        $select = (new SelectTag(
            'color',
            new Option('Red', 'red'),
            new Optgroup(
                'More',
                new Option('Blue', 'blue')
            )
        ))->setRequired(true);
        $radios = (new RadioSet('radio'))->addRadio('yes', 'Yes');
        $checks = (new MultiCheckbox('checks'))->addCheckbox('One', 'one');
        $form = (new Form())->disableAntiCSRF()
            ->append((new Grouping())->append($select)->append($radios)->append($checks));
        $valid = ['color' => 'blue', 'radio' => 'yes', 'checks' => ['one']];
        self::assertSame($valid, $form->submit($valid)->getData());
        foreach (['color', 'radio', 'checks'] as $name) {
            $bad = $valid;
            $bad[$name] = $name === 'checks' ? ['forged'] : 'forged';
            self::assertArrayHasKey($name, $form->submit($bad)->getFieldErrors());
        }
        $form->populateUserInput($valid);
        self::assertStringContainsString('value="blue" selected="selected"', $form->render());
        $form->populateUserInput(['color' => [], 'radio' => [], 'checks' => 'malformed']);
        self::assertStringNotContainsString('selected="selected"', $form->render());
    }

    /**
     * @throws CupcakeException
     */
    public function testMultipleSelectAndUploadExclusion(): void
    {
        $select = (new SelectTag(
            'colors[]',
            new Option('Zero', '0'),
            new Option('Blue', 'blue')
        ))->setMultiple(true)->setRequired(true);
        $form = (new Form())->disableAntiCSRF()->append($select)->append(new File('upload'));
        self::assertFalse($form->submit(['colors' => []])->isValid());
        self::assertFalse($form->submit(['colors' => 'blue'])->isValid());
        self::assertFalse($form->submit(['colors' => [['blue']]])->isValid());
        self::assertSame(['colors' => ['0', 'blue']], $form->submit([
            'colors' => ['0', 'blue'], 'upload' => 'forged-file'
        ])->getData());
        $form->populateUserInput(['colors' => ['0', 'blue']]);
        self::assertSame(2, substr_count($form->render(), 'selected="selected"'));
        self::assertStringContainsString('multiple="multiple"', $form->render());
    }

    public function testAriaAttributesAreEscaped(): void
    {
        $input = (new Text('name'))->setAria('invalid', 'true')
            ->setAria('describedby', 'error"><script>');
        self::assertStringContainsString('aria-invalid="true"', $input->render());
        self::assertStringNotContainsString('<script>', $input->render());
        $this->expectException(\InvalidArgumentException::class);
        $input->setAria('label" onclick', 'bad');
    }

    /**
     * @throws CupcakeException
     */
    public function testUnexpectedValidatorErrorsPropagate(): void
    {
        $form = (new Form())->disableAntiCSRF()->addValidator(static function (): array {
            throw new \LogicException('Application failure');
        });
        $this->expectException(\LogicException::class);
        $form->submit([]);
    }

    /**
     * @throws CupcakeException
     */
    public function testRoundTrip(): void
    {
        $form = $this->form();
        $token = $form->getAntiCSRF()->getHiddenElement()->getValue();
        $form->render();
        $result = $form->submit([
            'profile' => ['name' => 'alice'], 'password' => 'secret',
            'token' => $token, 'admin' => '1'
        ]);
        self::assertTrue($result->isValid());
        self::assertSame(['profile' => ['name' => 'alice'], 'password' => 'secret'], $result->getData());
        self::assertSame([], $result->getFieldErrors());
        self::assertSame([], $result->getFormErrors());
    }

    /**
     * @throws CupcakeException
     */
    public function testCsrfRejectedBeforeFilters(): void
    {
        $form = $this->form();
        foreach ([[], ['token' => 'wrong'], ['token' => []]] as $input) {
            $result = $form->submit($input);
            self::assertFalse($result->isValid());
            self::assertSame([], $result->getData());
            self::assertSame([], $result->getFieldErrors());
            self::assertNotEmpty($result->getFormErrors());
        }
    }

    /**
     * @throws CupcakeException
     * @throws InvalidDataException
     */
    public function testLegacyValidationAlsoRequiresCsrf(): void
    {
        $this->expectException(InvalidDataException::class);
        $this->form()->getValidFormInput([]);
    }

    /**
     * @throws CupcakeException
     */
    public function testAllFieldErrorsAndNoPartialData(): void
    {
        $form = $this->form();
        $token = $form->getAntiCSRF()->getHiddenElement()->getValue();
        foreach ([[], ['profile' => 'malformed'], ['profile' => ['name' => []]]] as $input) {
            $result = $form->submit($input + ['token' => $token]);
            self::assertFalse($result->isValid());
            self::assertSame([], $result->getData());
            self::assertArrayHasKey('profile::name', $result->getFieldErrors());
            self::assertArrayHasKey('password', $result->getFieldErrors());
        }
        $result = $form->submit(['token' => $token, 'profile' => ['name' => 'alice']]);
        self::assertSame([], $result->getData());
        self::assertSame(['password'], array_keys($result->getFieldErrors()));
    }

    /**
     * @throws CupcakeException
     */
    public function testSafeRedisplayAndStableCsrfField(): void
    {
        $form = $this->form()->append(new File('upload'));
        $form->render();
        $token = $form->getAntiCSRF()->getHiddenElement()->getValue();
        $form->populateUserInput([
            'profile' => ['name' => '<script>'], 'password' => 'secret',
            'upload' => 'private-path', 'token' => 'attacker'
        ]);
        $html = $form->render();
        self::assertStringNotContainsString('secret', $html);
        self::assertStringNotContainsString('private-path', $html);
        self::assertStringNotContainsString('<script>', $html);
        self::assertStringNotContainsString('attacker', $html);
        self::assertSame(1, substr_count($html, 'name="token"'));
        self::assertStringContainsString($token, $html);
        $form->populateUserInput(['profile' => 'malformed']);
        self::assertStringNotContainsString('<script>', $form->render());
    }

    /**
     * @throws CupcakeException
     */
    public function testRequiredFieldsRejectEmptyButAcceptZero(): void
    {
        $form = (new Form())->disableAntiCSRF()->append((new Text('value'))->setRequired(true));
        foreach ([[], ['value' => ''], ['value' => []]] as $input) {
            self::assertFalse($form->submit($input)->isValid());
        }
        self::assertSame(['value' => '0'], $form->submit(['value' => '0'])->getData());
    }

    /**
     * @throws CupcakeException
     */
    public function testNestedContainersAndCycle(): void
    {
        $group = (new Grouping())->append((new Text('name'))->setRequired(true));
        $group->append($group);
        $form = (new Form())->disableAntiCSRF()->append($group);
        self::assertFalse($form->submit([])->isValid());
        self::assertSame(['name' => 'alice'], $form->submit(['name' => 'alice'])->getData());
    }
}
