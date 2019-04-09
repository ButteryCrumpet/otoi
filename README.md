Otoi - Simple Contact Form
==========================

# Description

**Otoi** is a simple contact form app, designed to be easy to set
up and configure with minimal PHP knowledge

### Requirements

PHP >= 5.6 \
Url Rewriting \


# Usage

The simplest way of using Otoi is to just drop the folder into the
location you want it to run at. You can also include bootstrap file
if you want Otoi to run in a different directory. \
For more advanced users, Otoi is build with composer so it can also
be started by including its autoload and either creating an instance
of `Otoi\Otoi()` or using the `Otoi\Otoi::start()` static method.

## Example

```php
<?php
// /contact/index.php

require "/var/www/lib/Otoi/bootstrap.php";

// OR

require "/var/www/lib/Otoi/autoload.php";

Otoi\Otoi::start();

```
_With regards to security it is usually better to keep config files and_ 
_templates outside of the document root or include a "Deny from all" .htaccess_

## Rewrites

Otoi uses URL routing so the following, or similar, .htaccess file is necessary. 

```apacheconfig

<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule . index.php [L]
</IfModule>

```

---


# Configuration

**Otoi** uses naming conventions to make configuration simple and easy. \
Inside your config directory there should be two files: 

- forms.php
- mail.php

## App

The apps key configuration is set in a .env file. \
The file is searched for in directories in the following order:

1. The directory in which Otoi was run
2. A directory named config in the director in which Otoi was run
3. The top level folder of Otoi
4. The src folder of Otoi

```dotenv

# App Configuration
BASE_URL=/            # The base url of the contact forms
CONFIG=config         # Location of the config directory
LOGS=logs             # Locaiton of the log directory (Must be writable)
TEMPLATES=templates   # Location of the template directory
DEBUG=false           # Run in debug mode
HONEYPOT=true         # Uses honeypot method for reducing spam

# Mail Configuration
MAIL_DRIVER=mail             # Driver for mail (mail, sendmail, smtp)
MAIL_LANG=jp                 # Language setting for mail content
SMTP_HOST=smtp.example.com   # SMTP host if using smtp driver
SMTP_USER=user               # Username for SMTP if required
SMTP_PASS=password           # Password for SMTP if required
SMTP_PORT=25                 # Port for SMTP (May need setting if using SSL)

```

## Forms

Inside the forms file is where you will write the configuration for each 
form. The file must return an associative array of `{form-name} => {configuration}`. 
It **SHOULD** include a "default" form that will be run at the root of the application.

```php
<?php

return [
    "default" => [/** ...configuration */],
    "reserve" => [/** ...configuration */]
];

```

URLs for the forms are generated automatically from the form name key name.

For example:
`reservation` name key will generate the following URLs:

- `GET reservation/`
- `POST reservation/confirm`
- `POST reservation/mail` (used internally)

These URLs are then appended to the base url set in env variables.
So if your base url is `/contact` then the full URL of your form will be: 

- `/contact/reservation/`
- `/contact/reservation/confirm`
- `/contact/reservation/mail` 

The form generated from `default` key name config will then be found at `/contact`



### Configuration Schema

The schema for Form configuration is simple. \
The key will be used to identify the form internally as well as for the
slug for the url. (The exception being default, which runs at the root
of the app and so has no url slug)

**The following four fields are required** 

`"validation"` \
A `name => validation` array of input names and the string configuration validation. \
For possible validation options so Validation section [below](#validation).

`"templates"` \
**MUST** contain `"index" => {string}` and `"confirm" => {string}` where \
the string is a path to a template file inside the template folder.

`"final-location"` \
A URL to which users will be redirected upon completion of a form.

`"mail"` \
An array of emails to be sent upon completion of the form. These are names
of mail configurations that will be set in `mail.php`


#### Example Configuration

```php
<?php

return [
    "default" => [
        "validation" => [
            "name" => "required",
            "email" => "email|required",
            "file" => ""
        ],
        "templates" => ["index" => "index", "confirm" => "confirm"],
        "final-location" => "/",
        "mail" => ["admin", "customer"]
    ]
];

```

## Mail

Inside the mail file is where you will write the configuration for each form.
The file must return an associative array of `{mail-name} => {configuration}`.
The mail-name key is used to identify the each mail configuration when configuring forms.

```php
<?php

return [
    "customer-response" => [/** ...configuration */],
    "admin" => [/** ...configuration */]
];

```

### Configuration Schema

A mail configuration **MUST** have at least a `"to"`, `"from"` and `"subject"` key.
`"cc"`, `"bcc"`, `"files"`, `"template"` are optional.

`"to"`, `"from"`, `"cc"` and `"bcc"` can all be either:

- Single string email address e.g `"test@mail.com"`
- Array of string email addresses e.g `["test@mail.com", "mail@test.com"]`
- Email address-name e.g `["test@mail.com", "name1"]`
- Array of email address-name pairs e.g `[["test@mail.com", "name1"], ["mail@test.com", "name2"]]`


#### Example Configuration

```php
<?php

return [
    "admin" => [
        "subject" => "SUBJECT",
        "to" => "test@test.com",
        "from" => ["no-reply@server.com", "Test Server"],
        "template" => "mail/default",
        "files" => "file",
        "cc" => ["cc@test.com", "cc2@test.com"]
    ],
    "customer" => [
        "subject" => "CUSTOMER SUBJECT",
        "to" => ["@email", "@name"],
        "from" => ["no-reply@server.com", "Test Server"],
        "template" => "mail/default"
    ]
];

```

Add these mail configurations by name to each form configuration.


## Validation

The validation configuration takes the form of a string. \
Rules are separated by a pipe `|`, if a rule takes arguments they are delineated with a colon
`:` and each argument separated by a comma `,`.

e.g:
- `email`
- `required|email`
- `blacklist:not,this,or,this|required`

Available rules include
- required
- whitelist
- blacklist
- email
- phone
- ext
- pdf
- regex 


---

&copy; 2019 Simon Leigh