Otoi - Simple Contact Form
==========================

**Otoi**とはPHP詳しくない方でも使いやすい、シンプルな、お問い合わせフォームのシステムです

**Otoi** is a simple contact form app, designed to be easy to set
up and configure with minimal PHP knowledge

English documentation can be found below.

### Requirements 必要条件

PHP >= 5.6

## 使用法

Otoiを使用する最も簡単な方法はOtoiのフォルダーを実行したい場所におくことです。
Otoiの別のディレクトリで実行したい場合、自分の作ったindex.phpのファイルにbootstrap.phpをインコルードもできます。 \
より高度なユーザだと、OtoiがComposerで実装されたものなのでautoload.phpをインクルードすると自由に
`Otoi\Otoi`のインスタンスを作成で始め、または`Otoi\Otoi::start()`の静的のメソッドを使用ください。

### 例

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

### 書き換え

OtoiはURL書き換えを使用しているために、下記のような.htaccessファイルが必要です。 

```apacheconfig

<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule . index.php [L]
</IfModule>

```

---


## 設定

**Otoi** attempts to keep configuration simple and easy.

### 基本

重要な設定は.envファイルに設定されています。 \
その.envファイルは順序でディレクトリ内で検索されます。

1. Otoiが実行されていたディレクトリ
2. Otoiが実行されていたディレクトリのconfigというディレクトリ
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


### フォーム

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



#### 設定構造

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


##### 例

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

### メール

mail.phpというファイル内には、各フォームの設定を書き込みます。
ファイルは `{mail-name} => {configuration}`の連想配列を返さなければなりません。

```php
<?php

return [
    "customer-response" => [/** ...configuration */],
    "admin" => [/** ...configuration */]
];

```

#### 設定構造

メール設定は少なくとも `" to "`、 `" from "`、 `" subject "`キーが**必須**です。
`"cc"`, `"bcc"`, `"files"`, `"template"`は任意です。

`" to "`、 `" from "`、 `" cc "`、`" bcc "`はすべて以下のいずれかです。

- メールアドレスの文字列          e.g `"test@mail.com"`
- メールアドレスの文字列の配列     e.g `["test@mail.com", "mail@test.com"]`
- メールアドレス・名のペア        e.g `["test@mail.com", "name1"]`
- メールアドレスと名前のペアの配列 e.g `[["test@mail.com", "name1"], ["mail@test.com", "name2"]]`

`"files"`は、メールの添付ファイルとして送信されるファイルアップロード入力に関連する入力名の文字列または配列です。

`"tempate"`はテンプレートディレクトリを基準とした、電子メールの本文を作成するために使われるテンプレートのパスです。

##### 例

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


### 検証

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


### テンプレート

テンプレートは単にphpファイルです。そのように他のファイルを要求など、通常PHPで実行できます。ただし、
ファイルはテンプレートクラスで実行されるため、あなたがOtoiを他のPHPファイル変数に埋め込んでいるなら、
親ファイルは範囲外になります。\
CSRFトークンとHoneytrapを簡単に含めるために、これらをレンダリングするためのヘルパーがあります。`$csrf()`と
`$honeypot()`。 CSRFトークンは、クロスサイトリクエストフォージェリ攻撃に対抗するために常に必要です。
ハニーポットは.envファイルで無効にできます。ハニーポットは、埋められた場合にフォームが拒否される原因となる隠し入力
を含めることによって、ボットからのスパムの量を減らすためのシンプルで効果的な手法です。\
ボットが自動的にそれを記入している間、人間はこの入力を見て記入しないでしょう。

入力値はインデックステンプレートと確認テンプレートにある`$data`配列を通して利用可能です。 これらは項目の`name`
でインデックスされています（検証設定で登録されている必要あります）。入力に対して値が送信されていない場合は、
空の文字列になります (`""`)。 \
エラーはインデックステンプレートのみにある`$errors`配列で利用できます。\
`$errors`配列はname =\> errors[]の配列です。入力が有効な場合、これは空の配列になります。 エラーの値は失敗した
ルールの名前になります。\
e.g `required|email`の検証の項目に`test＆test.com`を入力すると、エラー配列が`['email']`になります。\
もっと有用なエラーメッセージが表示されます。\
`$ action`変数はフォームの次の段階のために生成されたURLを取得するために使うことができます。
#### Example Template

```php

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
</head>
    <div>
        <ul>
        <?php foreach($errors as $name => $errs) ?>
            <?php foreach ($errs as $err) ?>
            <li><?= $err ?></li>
            <?php endforeach ?>
        <?php endforeach ?>
        </ul>
    </div>
    <form enctype="multipart/form-data" action="<?= $action ?>" method="post">
        <?php $csrf() ?>
        <label>Name: <input type="text" name="name" value="<?= $data["name"] ?>"></label><br>
        <label>Email: <input type="text" name="email" id="<?= $data["email"] ?>"></label><br>
        <label>File: <input type="file" name="file"></label><br>
        <input type="submit" value="Submit">
        <?php $honeypot() ?>
    </form>
</html>
```

---


# English

## Usage

The simplest way of using Otoi is to just drop the folder into the
location you want it to run at. You can also include bootstrap file
if you want Otoi to run in a different directory. \
For more advanced users, Otoi is build with composer so it can also
be started by including its autoload and either creating an instance
of `Otoi\Otoi()` or using the `Otoi\Otoi::start()` static method.

### Example

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

### Rewrites

Otoi uses URL routing so the following, or similar, .htaccess file is necessary. 

```apacheconfig

<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule . index.php [L]
</IfModule>

```

---


## Configuration

**Otoi** uses naming conventions to make configuration simple and easy. \
Inside your config directory there should be two files: 

- forms.php
- mail.php

### App

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

### Forms

Inside the forms file is where you will write the configuration for each 
form. The file **MUST** return an associative array of `{form-name} => {configuration}`. 
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



#### Configuration Schema

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


##### Example Configuration

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

### Mail

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

#### Configuration Schema

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

##### Example Configuration

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


### Validation

The validation configuration takes the form of a string. \
Rules are separated by a pipe `|`, if a rule takes arguments they are delineated with a colon
`:` and each argument separated by a comma `,`.

e.g:
- `email`
- `required|email`
- `blacklist:not,this,or,that|required`

Available rules include
- required
- whitelist
- blacklist
- email
- phone
- ext
- pdf
- regex 

### Templates

Templates are simply php files. As such you are free to require other files and do anything
else you can usually in PHP. However, as the file is executed in the templating classes
context, if you are for some reason embedding Otoi in another PHP file variables from the
parent file will be out of scope. \
To make including a CSRF token and a Honeytrap easier there are helpers to render these.
`$csrf()` and `$honeypot()`. CSRF token is always necessary to combat cross site request
forgery attacks. Honeypot can be disabled via the .env file. The honeypot is
a simple and effective technique to reduce the amount of spam from bots by including a hidden
input which if filled will cause the form to be rejected. Humans will not see and not fill
in this input while bots will automatically fill it in.

Input values are available through the `$data` array which is present in both the index
template and the confirm template. These are indexed by the name keys in the validation
configuration. If no value has been submitted for an input it will be an empty string (`""`). \
Errors are available through the `$errors` array, present ONLY in the index template. \
The errors array is an array of name =\> errors[]. Each input will have an an array of error,
empty if valid. The value of the errors will be the name of the failed rule. \
e.g input of `test&test.com` for a validation of `required|email` will have an errors array of
`['email']` \
More useful error messages are left to you to display. It is recommended to make an easy to use
function that can be imported into the template to handle all the possible validation errors. \   
The `$action` variable can be used to get the generated url for the next stage of the form.

#### Example Template

```php

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
</head>
    <div>
        <ul>
        <?php foreach($errors as $name => $errs) ?>
            <?php foreach ($errs as $err) ?>
            <li><?= $err ?></li>
            <?php endforeach ?>
        <?php endforeach ?>
        </ul>
    </div>
    <form enctype="multipart/form-data" action="<?= $action ?>" method="post">
        <?php $csrf() ?>
        <label>Name: <input type="text" name="name" value="<?= $data["name"] ?>"></label><br>
        <label>Email: <input type="text" name="email" id="<?= $data["email"] ?>"></label><br>
        <label>File: <input type="file" name="file"></label><br>
        <input type="submit" value="Submit">
        <?php $honeypot() ?>
    </form>
</html>

```

---

&copy;2019 Simon Leigh