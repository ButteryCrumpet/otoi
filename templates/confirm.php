<html>
<head>
    <title>Otoi Example</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <style>
        .wrapper {
            width: 100%;
        }

        form {
            width: 70%;
            margin: 0 auto;
        }

        input, textarea {
            width: 255px;
            background-color: #f9f9f9;
            color: #5b5b5b;
            padding: 12px 20px 12px;
            border: 1px solid #ccc;
        }

        dl {
            display: flex;
            border-top: 1px solid #dbdbdb;
            justify-content: space-between;
            align-items: center;
            padding: 22px;
            margin: 0;
        }

        dt {
            display: flex;
            width: 28.68%;
            justify-content: space-between;
        }

        dt::after {
            width: 60px;
            height: 25px;
            content: "必須";
            border: 1px solid #298080;
            color: #298080;
            font-size: 11px;
            letter-spacing: 0.08em;
            text-align: center;
            line-height: 25px;
            display: inline-block;
        }

        dt > span {
            line-height: 1;
            letter-spacing: 0.08em;
            display: inline-block;
        }

        dd {
            display: flex;
            width: 62.79%;
        }

        header {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btn-wrapper {
            margin-top: 45px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        button {
            width: 480px;
            display: inline-block;
            color: #fff;
            background-color: #232323;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 0.08em;
            line-height: 1;
            text-align: center;
            text-decoration: none;
            padding: 40px;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            border: none;
            cursor: pointer;
        }

        button[type=button] {
            background-color: #808080;
        }
    </style>
</head>
<body>
<header>
    <h1>Otoi</h1>
</header>
<div class="wrapper">
    <form action="<?= $action ?>" method="post">
        <?php $csrf() ?>
        <div class="formTitleArea">
            <h2 class="title">お問い合せ</h2><!-- title -->
        </div><!-- formTitleArea -->
        <div>
            <dl>
                <dt>
                    <span>お名前</span>
                </dt>
                <dd>
                    <div>
                        <?= $data["name"] ?>
                    </div><!-- itemWrap -->
                </dd>
            </dl>
            <dl>
                <dt>
                    <span>メールアドレス</span>
                </dt>
                <dd>
                    <div>
                        <?= $data["email"] ?>
                    </div><!-- itemWrap -->
                </dd>
            </dl>
            <dl>
                <dt>
                    <span>電話番号</span>
                </dt>
                <dd>
                    <div>
                        <?= $data["phone"] ?>
                    </div><!-- itemWrap -->
                </dd>
            </dl>
            <dl>
                <dt>
                    <span>お問い合せ内容</span>
                </dt>
                <dd>
                    <div>
                        <?= $data["inquiry"] ?>
                    </div><!-- itemWrap -->
                </dd>
            </dl>
            <div class="btn-wrapper">
                <button type="button" onclick="history.back()">
                    <span>入力内容を修正する</span>
                </button>
                <button type="submit" href="javascript: void(0);">
                    <span>入力内容を送信する</span>
                </button>
            </div>
        </div><!-- form_input -->
    </form>
</div>
</body>
</html>
