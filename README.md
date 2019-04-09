Otoi - Simple Contact Form
==========================

# Description

**Otoi**とはPHP詳しくない方でも使いやすい、シンプルな、お問い合わせフォームのシステムです

**Otoi** is a simple contact form app, designed to be easy to set
up and configure with minimal PHP knowledge

English documentation can be found below.

### Requirements 必要条件

PHP >= 5.6 \

# 使用法

Otoiを使用する最も簡単な方法はOtoiのフォルダーを実行したい場所におくことです。
Otoiの別のディレクトリで実行したい場合、自分の作ったindex.phpのファイルにbootstrap.phpをインコルードもできます。 \
より高度なユーザだと、OtoiがComposerで実装されたものなのでautoload.phpをインクルードすると自由に
`Otoi\Otoi`のインスタンスを作成で始め、または`Otoi\Otoi::start()`の静的のメソッドを使用ください。

## 例

```php
<?php
// /contact/index.php

require "/var/www/lib/Otoi/bootstrap.php";

// OR

require "/var/www/lib/Otoi/autoload.php";

Otoi\Otoi::start();

```
_セキュリティーに関しては、通常に設定ファイルとテンプレートをドキュメントルートの外側におくか、.htaccessの「Deny
from all」を含めることがおすすめです_

## 書き換え

OtoiはURL書き換えを使用しているために、下記のような.htaccessファイルが必要です。 

```apacheconfig

<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule . index.php [L]
</IfModule>

```

---


# 設定

**Otoi** attempts to keep configuration simple and easy.

## 基本

重要な設定は.envファイルに設定されています。 \
その.envファイルは順序でディレクトリ内で検索されます。

1. Otoiが実行されていたディレクトリ
2. Otoiが実行されていたdirectorのconfigという名前のディレクトリ
3. Otoiの最上位フォルダ
4. Otoiのsrcフォルダ

```dotenv

# App Configuration
BASE_URL=/            # フォームのURL
CONFIG=config         # configディレクトリ
LOGS=logs             # ログディレクトリ（書き込み可能である必要）
TEMPLATES=templates   # テンプレートディレクトリ
DEBUG=false           # デバッグモードで実行
HONEYPOT=true         # スパムを減らすためにハニーポットを使う

# Mail Configuration
MAIL_DRIVER=mail             # メール用ドライバ（mail、sendmail、smtp）
MAIL_LANG=jp                 # メールコンテンツの言語設定
SMTP_HOST=smtp.example.com   # SMTPホスト (smtpドライバを使用している場合)
SMTP_USER=user               # 必要に応じてSMTPのユーザー名
SMTP_PASS=password           # 必要に応じてSMTPのパスワード
SMTP_PORT=25                 # SMTP用ポート（SSLを使用している場合は設定が必要な場合があります）

```

configディレクトリの中には2つのファイルがあります。

- forms.php
- mail.php


## フォーム

forms.phpファイルの中には、それぞれの設定を書き込みます。ファイルは「`{form-name} => {configuration}`」の
連想配列を返さなければなりません。\
高度なユーザではない場合、アプリケーションのルートで実行される「default」というフォームを含めるべきです。

```php
<?php

return [
    "default" => [/** ...configuration */],
    "reserve" => [/** ...configuration */]
];

```

フォームのURLはフォーム名キーから自動的に生成されます。

例えば:
`reservation`キー名は以下のURLを生成します:

- `GET reservation/`
- `POST reservation/confirm`
- `POST reservation/mail`

これらのURLは、env変数に設定されているBASE_URLに追加されます。
そうしてBASE_URLが`/contact`だとフォームの完全なURLがこの通り: 

- `/contact/reservation/`
- `/contact/reservation/confirm`
- `/contact/reservation/mail` 

`default`キー名configから生成されたフォームは` / contact`にあります。



### 設定構造

フォーム設定のスキーマは簡単です。 \
キーは内部的にフォームを識別するためと、URLのスラグのために使用されます。
（例外はデフォルトで、ルートで実行されますので、URLスラッグはありません）

**以下のフィールドはすべて必須です** 

`"validation"`

入力名と検証文字列の設定の `name => validation`配列。 \
可能な検証オプションについては、 [下記](#validation)の検証セクションを参照してください。

`"templates"`

`"index "=> {string}`と `" confirm "=> {string}`を含める**必要**があります。
文字列は、テンプレートフォルダ内のテンプレートファイルへのパスです。

`"final-location"`

フォームの完成時にユーザーが転送されるURL。

`"mail"`

フォームの完成時に送信されるEメールの配列。これらはmail.phpに設定されるメール設定の名前です


#### 例

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

## メール

mail.phpというファイル内には、各フォームの設定を書き込みます。
ファイルは `{mail-name} => {configuration}`の連想配列を返さなければなりません。

```php
<?php

return [
    "customer-response" => [/** ...configuration */],
    "admin" => [/** ...configuration */]
];

```

### 設定構造

メール設定は少なくとも `" to "`、 `" from "`、 `" subject "`キーが**必須**です。
`"cc"`, `"bcc"`, `"files"`, `"template"`は任意です。

`" to "`、 `" from "`、 `" cc "`、`" bcc "`はすべて以下のいずれかです。

- メールアドレスの文字列          e.g `"test@mail.com"`
- メールアドレスの文字列の配列     e.g `["test@mail.com", "mail@test.com"]`
- メールアドレス・名のペア        e.g `["test@mail.com", "name1"]`
- メールアドレスと名前のペアの配列 e.g `[["test@mail.com", "name1"], ["mail@test.com", "name2"]]`

`"files"`は、メールの添付ファイルとして送信されるファイルアップロード入力に関連する入力名の文字列または配列です。

`"tempate"`はテンプレートディレクトリを基準とした、電子メールの本文を作成するために使われるテンプレートのパスです。

#### 例

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

このメール設定を各フォーム設定に名前で追加します。


## 検証

検証設定は文字列の形式です。 \
ルールはパイプ `|`で区切ります。ルールが引数を取る場合、引数はコロン `：`で区切られ、各引数はカンマ `、`で区切られます。

e.g:
- `email`
- `required|email`
- `blacklist:not,this,or,this|required`

利用可能なルール：
- required　
- whitelist　　
- blacklist
- email
- phone
- ext
- pdf
- regex 


---


# <a id="english"></a> English

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

URLs for the forms are generated automatically from the form name key.

For example:
`reservation` name key will generate the following URLs:

- `GET reservation/`
- `POST reservation/confirm`
- `POST reservation/mail`

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

`"validation"`

A `name => validation` array of input names and the validation string configuration. \
For possible validation options so Validation section [below](#validation).

`"templates"`

**MUST** contain `"index" => {string}` and `"confirm" => {string}` where \
the string is a path to a template file inside the template folder.

`"final-location"`

A URL to which users will be redirected upon completion of a form.

`"mail"`

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

`"files"` is an string or array of input names that relate to a file upload input to be sent as
an attachment on the email.

`"tempate"` is the path, relative to the template directory, of the template to be used to
construct the body of the email.

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

&copy;2019 Simon Leigh