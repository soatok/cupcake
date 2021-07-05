## ![Introduction to Cupcake](headers/01-intro-header.png)

Cupcake is designed to generate forms and validate user input from said forms.

## ![(Neophyte Sitting)](Neophyte/Sitting-40px.png) Basic Cupcake Concepts

At its core, all Cupcake components are called **Ingredients** (and must implement
the `IngredientInterface` interface).

### Ingredients

There are two primary types of Ingredients: **Elements** and **Containers**.

* **Elements** represent HTML tags that CANNOT contain other elements.
* **Containers** are HTML tags that MAY contain other Containers or Elements.

For example, a `<fieldset>` tag would be represented by the `Fieldset` class.

### Blends

Some Elements and/or Containers are, on their own, a poor user experience.
To remedy this, Cupcake also provides **Blends** which are Containers that 
implement common combinations of ingredients (e.g., checkbox with an 
associated label).

### Utilities

In addition to all the above, there exists a **Utilities** singleton class
that contains the default implementations (and their respective
configurations) for several classes used by Cupcake, including:

* HTMLPurifier
* The default CSRF-prevention strategy when none is provided.

This is mostly an implementation detail internal to Cupcake.

## ![(Neophyte Cooking)](Neophyte/Cooking-40px.png) Intro to Forms

**Forms** are the top-most **Container**, and (in all likelihood) the class
that you will use in your code the most.

Creating one is very easy:

```php
<?php
use Soatok\Cupcake\Form;

$form = new Form();
```

From there, you can `append()` ingredients until you have all the necessary
fields you want to capture.

**Further Reading:** [`Form` definition](Baked-In/Form.md)
