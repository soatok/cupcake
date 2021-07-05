# Form

> Fully qualified class name:  
> `Soatok\Cupcake\Form`

The `Form` class extends `Soatok\Cupcake\Core\Container`.

## ![(Neophyte Happy)](../Neophyte/Happy-40px.png) Public Methods

### customAttributes

> Returns an `array` where every key is a `string` and every value is either
> a `string` or `null`.

See [Container](Core/Container.md#customAttributes).

### getAction

> Returns a `string`.

### getMethod

> Returns a `string`.



## ![(Neophyte Confused)](../Neophyte/Confused-40px.png) Protected Methods

These methods are not publicly callable.

## Object Properties

### $action

> Type: `string`

Default value: `""`

Acceptable values: Any valid request URI for a form.

### $antiCSRFdisabled

> Type: `bool`

Default value: `false`

**Security:** If you set this to `true`, you are opting out of CSRF protection
for this form.

### $antiCSRF

> Type: `AntiCSRFInterface | null`

Default value: `null`

The Anti-CSRF implementation specific to this form. If not specified
(i.e. `null`), this form will use the default implementation specified
in the `Utilities` class.

### $enctype

> Type: `string`

Default value: `""`

If you include a [`File`](Ingredients/Input/File.md) element anywhere in the form, it will automatically
be converted to `"multipart/form-data"` when the form is rendered.

### $method

> Type: `string`

Default value: `"GET"`

Acceptable values: Any HTTP request method. (GET, POST, etc.)
