# Cupcake - Documentation

The primary goal of Cupcake is to make form generation and
secure data processing easy, without any framework-specific
dependencies.

Here's two snippets of code that will instantiate a web form,
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

return $form;
```

The above snippet instantiates and returns a Form object.
If 

```php
<?php
declare(strict_types=1);
use ParagonIE\Ionizer\InvalidDataException;
use Soatok\Cupcake\Blends\DivWithPurifiedHtml;
use Soatok\Cupcake\Form;

/** 
 * @var callable $callback (user-defined)
 * @var Form $form (see previous snippet)
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
