# File

> Fully qualified class name:  
> `Soatok\Cupcake\Ingredients\Input\File`

The `File` class extends [`Soatok\Cupcake\Ingredients\InputTag`](../InputTag.md).

> ## ![(Neophyte Confused)](../../../Neophyte/Confused-40px.png) Important 
>
> The [`Form`](../../Form.md) class has special logic to check if it contains a
`File`. If so, it will change the `enctype` on the `Form` to 
`"multipart/form-data"`.
>
> This is necessary to ensure file uploads succeed automatically, but if
you aren't expecting this change, this intended behavior may be mildly
astonishing. Please keep this in mind.

## Object Properties

### $type

> Type: `string`

This is set to `file`.
