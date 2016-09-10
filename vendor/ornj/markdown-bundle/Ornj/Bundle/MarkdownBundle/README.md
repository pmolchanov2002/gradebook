Ornj MarkdownBundle
-------------------

This bundle create a `markdown` form field allowing for the realtime previewing of markdown markup. It is intended
to be used in a user interface built using [Twitter Bootstrap](http://getboostrap.com). This bundle pairs nicely with
[SonataAdminBundle](https://github.com/sonata-project/SonataAdminBundle) or [MopaBootstrapBundle](https://github.com/phiamo/MopaBootstrapBundle).

You should use [KnpMarkdownBundle](https://github.com/KnpLabs/KnpMarkdownBundle) to render the markdown on your front end.

Markdown fields are rendered with controls and a preview using:

*  [Markdown JS](https://github.com/evilstreak/markdown-js)
*  [To Markdown](https://github.com/domchristie/to-markdown)
*  [Bootstrap Markdown](https://github.com/toopay/bootstrap-markdown)
*  [Bootbox.js](http://bootboxjs.com/)


## Installation

Install this bundle in your Symfony2 project by adding it to your `composer.json`.

```json
{
    "require": {
        "ornj/markdown-bundle": "dev-master"
    }
}
```

After running updating composer, register the bundle in `app/AppKernel.php`.

```php
$bundles = array(
   // ...
   new Ornj\Bundle\MarkdownBundle\OrnjMarkdownBundle(),
);
```


## Usage

### Enable Twig Template

This bundle provides a Twig template for rendering the markdown field. To use it there are two options.

Enable globally by adding the template to `config.yml`

```yml
twig:
    form:
        resources:
            - 'OrnjMarkdownBundle:Form:fields.html.twig'
```

__or__

Add the template in your form's twig template.

```twig
{% form_theme form 'OrnjMarkdownBundle:Form:fields.html.twig' %}
```

### Add Styles and Javascript

Extend or modify your base admin template and add the following styles. Either SCSS or CSS can be used.

#### SCSS

```twig
{% stylesheets filter="cssrewrite, compass"
    '@OrnjMarkdownBundle/Resources/public/scss/*'
%}
<link rel="stylesheet" href="{{ asset_url }}" type="text/css" media="all" />
{% endstylesheets %}
```

#### CSS

```twig
{% stylesheets filter="cssrewrite, compass"
    '@OrnjMarkdownBundle/Resources/public/css/*'
%}
<link rel="stylesheet" href="{{ asset_url }}" type="text/css" media="all" />
{% endstylesheets %}
```

#### Javascript
Extend or modify your base admin template and add the following Javascript.

```twig
{% javascripts
    '@OrnjMarkdownBundle/Resources/public/js/markdown.js'
    '@OrnjMarkdownBundle/Resources/public/js/to-markdown.js'
    '@OrnjMarkdownBundle/Resources/public/js/bootbox.js'
    '@OrnjMarkdownBundle/Resources/public/js/bootstrap-markdown.js'
%}
<script type="text/javascript" src="{{ asset_url }}"></script>
{% endjavascripts %}
```

### The Form Field

The markdown field is an extension of `textarea`. It can be used to render an input for any field expecting a string.

```php
$builder->add('body', 'markdown');
```

### Rendering in front end

You will need a parser in order to render the markdown as HTML. [KnpMarkdownBundle](https://github.com/KnpLabs/KnpMarkdownBundle) adds
a handy Twig extension for rendering the result.


## To Do

1.  Allow inclusion of `textarea` options.
2.  Investigate removing physical copies of dependencies in bundle.
3.  Allow customization of ui options through config.
