<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
</head>
<?php
$fh = $formHelper;
$fh->loadValues($data);
?>
<body>
    <style>
        .errors {
            background-color:lightcoral;
            border-radius:5px;
            color: white;
            font-size: 11px;
        }
        .error {
            border: 1px solid lightcoral
        }
        .clean {
            border: 1px solid lightseagreen;
        }
        form {
            width: 60%;
        }
        body {
            display: flex;
            justify-content: center;
        }
    </style>
    <form action="confirm" method="POST" enctype="multipart/form-data">
        <?= $fh->renderErrors("test") ?>
        <input class="<?= $fh->hasError("test") ? "error" : "clean" ?>" name="test" type="text" value="<?= $fh->valueFor("test") ?>"><br>
        <?= $fh->renderErrors("phone") ?>
        <input class="<?= $fh->hasError("phone") ? "error" : "clean" ?>" type="text" name="phone" value="<?= $fh->valueFor("phone") ?>"><br>
        <?= $fh->renderErrors("test2") ?>
        <input class="<?= $fh->hasError("test2") ? "error" : "clean" ?>" type="text" name="test2" value="<?= $fh->valueFor("test2") ?>"><br>
        <?= $fh->renderErrors("file") ?>
        <input type="file" name="file" accept="application/pdf"><br><br>
        <input type="submit">
    </form>
</body>
</html>