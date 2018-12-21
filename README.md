Otoi - Simple Contact Form
==========================

# Description

**Otoi** is a simple contact form app that uses Twig for its \
templating. Otoi is designed to be easy to set up and configure \
with minimal PHP knowledge (no PHP knowledge needed as soon as \
the admin feature lands).

# Usage

When booting Otoi in your index.php first include the bootstrap \
file from Otoi's install location, then create a new instance of \
Otoi passing in three things.

1. The URL of its root page. e.g /contact
2. The location of the config folder.
3. The location of the template folder.

If 2 or 3 are not passed in it will look for these folders in its \
installation directory.

Then use Otoi::run() to run the application.

## Example
```php
// /contact/index.php

require "/var/www/lib/otoi/boostrap.php"

$otoi = new Otoi\Otoi("/contact", "/contact/config", "/contact/templates");
$otoi->run();

```
_With regards to security it is usually better to keep config files and_ \
_templates outside of the document root or include a "Deny from all" .htaccess_

## Rewrites

Otoi uses URL routing so the following, or similar, .htaccess file is necessary. \

```apacheconfig

<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule . index.php [L]
</IfModule>

```

# Configuration

**Otoi** uses naming conventions to make configuration simple and easy. \
Inside your config directory there should be two folders: \

- forms
- mail

## Forms

Inside the forms directory is where you will place config files for each \
form. Each file will corresponds to a form and URLs for the forms are \
generated automatically from the file name. \

For example:
`reservation.php` will generate the following URLs:

- `reservation/`
- `reservation/confirm`
- `reservation/mail` (used internally)
- `reservation/thanks`

These URLs are then appended to the base url passed in when booting Otoi. \
So if your base url is `/contact` then the full URL of your form will be: \

- `/contact/reservation/`
- `/contact/reservation/confirm`
- `/contact/reservation/mail` 
- `/contact/reservation/thanks`

If you want to have a Form at your base URL (which is most likely the case), \
create a file named `default.php`. The form generated from `default.php` will \
then be found at `/contact`

### Schema

The schema for Form configuration is simple. The file itself must return an \
array with the fields `"fields"`, `"templates"` and optionally `"final-location"`. \

`"fields"` is a array of arrays. Each field array **MUST** contain a minimum of \
`"name" => string`.

```php

$fields = [
    "name" => "email",
    "type" => "text" // optional: defaults to text,
    "validation" => "required|email", // optional: defaults to no validation (null)
    "placeholder" => "joe@mail.com", // optional: defaults to empty string
    "defaultValue" => null, // optional: defaults to null
    "label" => "Your Email" // optional: defaults to empty string
]

```

`"templates"` **MUST** contain `"index" => string` and `"confirm" => string`. \
It can optionally contain `"final" => string`.
`"final-location"` is optional but only in the case that `"templates" => "final"` \
is not set.
Either `"final-location"` **OR** "templates" => "final"` **MUST** be set. \

#### Example Configuration

```php

return [
    "fields" => [
        [
            "name" => "name",
            "validation" => "required"
        ], [
            "name" => "email",
            "validation" => "email",
            "type" => "email",
            "label" => "Your Email"
        ], [
            "name" => "question"
        ]
    ],
    "templates" => [
        "index" => "question/index.twig.html",
        "confirm" => "question/confirm.twig.html",
        "final" => "question/thanks.twig.html"
    ]
]

```

## Mail


