# ![Cupcake - Documentation](headers/documentation.png)

The primary goal of Cupcake is to make form generation and
secure data processing easy, without any framework-specific
dependencies.

## Contents

1. [Introduction to Cupcake](01-Intro.md)
1. [Baked-In Features](Baked-In) (Reference Documentation)
1. [Examples](Examples)
1. [Third-Party Integrations](Integrations)

## ![(Neophyte Delighted)](Neophyte/Happy-40px.png) Tenets of Cupcake

1. **Security is the highest priority.**
   * It MUST be harder to use this library insecurely than securely.
   * The defaults MUST be reasonable and secure.
1. **Easy-to-use or bust.**
   * If a neophyte cannot figure it out, the design is wrong.
   * The Principle of Least Astonishment is our guiding principle.
   * If something seems confusing or surprising, it needs to be corrected.
1. **Extensibility.**
   * It must be easy for a developer to extend Cupcake in their own projects.
1. **Minimal dependencies.**
   * Cupcake MUST not depend on a bloated web framework.
   * (But it should be easy to integrate with an existing framework. See also:
     Extensibility.)

## ![(Neophyte Cooking)](Neophyte/Cooking-40px.png) Quick Example

Here are two snippets of code that will instantiate a web form,
process the request (if it exists), and display it again with
the user-provided data if there was an error.

```php
<?php
declare(strict_types=1);
use Soatok\Cupcake\Blends\{
    CheckboxWithLabel,
    DivWithPurifiedHtml
};
use Soatok\Cupcake\Form;
use Soatok\Cupcake\Ingredients\{
    Grouping,
    Input\Text,
    Input\Password
};

// First, let's instantiate our form:
$form = (new Form())
    ->append(
        (new DivWithPurifiedHtml(''))
            ->setId('form-feedback')
    )->append(
        (new Grouping())
            ->setBeforeEach('<div class="form-row">')
            ->setAfterEach('</div>')
            ->append(
                (new Text('username'))
                    ->setId('form1-username')
                    ->setRequired(true)
                    ->setPattern('^[a-z0-9_\-]{2,}$')
            )
            ->createAndPrependLabel('Username')
            ->append(
                (new Password('password'))
                    ->setId('form1-password')
                    ->setRequired(true)
            )->createAndPrependLabel('Password')
    )->append(
        CheckboxWithLabel::create(
            'remember-me',
            '1',
            'form1-remember-me',
            'Remember me on this computer?'
        )
    );
    // This is optional; by default, it includes at the time
    // of form rendering. This can cause issues with the Cookie-backed
    // Anti-CSRF implementation. Custom implementations may be unaffected.
    $form->finalizeCsrfElement();

return $form;
```

The above snippet instantiates and returns a Form object.

```php
<?php
declare(strict_types=1);
use ParagonIE\Ionizer\InvalidDataException;
use Soatok\Cupcake\Blends\DivWithPurifiedHtml;
use Soatok\Cupcake\Form;

/**
 * @var callable $callback (user-defined)
 * @var Form $form (see previous snippet)
 * 
 * If you want, copy the definition of $form from the previous snippet
 * to the current snippet, right below this docblock. Or you can just
 * do this:
 *
 * $form = require "other-snippet.php";
 */

// Next, let's process data:
if (!empty($_POST)) {
    try {
        $postData = $form->getValidFormInput($_POST);
        // Pass the validated post data to another function
        $callback($postData);
        exit;
    } catch (InvalidDataException $ex) {
        /** @var DivWithPurifiedHtml $el */
        $el = $form->getChildById('form-feedback');
        $el->setContents($ex->getMessage());
        
        // Populate the form elements with $_POST data      
        $form->populateUserInput($_POST);
    }
}

// To print the form into a web page, just print it:
echo '<!DOCCTYPE html><html><body>';
echo $form;
echo '</body></html>';
```

When the user fills out the form, if their input passes validation,
the validated (and type-conforming) input will be passed to `$callback`.

If the form fails validation, the form will be displayed again.
