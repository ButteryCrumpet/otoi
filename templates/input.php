<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
</head>
<?php
$emailErr = $validation->hasError("test");

function render_errors($name, $validation) {
    ?>
    <ul class="errors" >
        <?php foreach ($validation->errorsOf($name) as $error) : ?>
            <?php if ($error !== ":ignore") : ?>
                <li><?= $error ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    <?php
}
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
    <form action="confirm.php" method="POST" enctype="multipart/form-data">
        <input class="<?= $emailErr ? "error" : "clean" ?>" name="test" type="text" value="<?= isset($data["test"]) ? $data["test"] : "" ?>"><br>
        <?php render_errors("test", $validation); ?>
        <input class="<?= $emailErr ? "error" : "clean" ?>" type="text" name="phone" value="<?= isset($data["phone"]) ? $data["phone"] : "" ?>"><br>
        <?php render_errors("phone", $validation); ?>
        <input class="<?= $validation->hasError("test2") ? "error" : "clean" ?>" type="text" name="test2" value="<?= isset($data["test2"]) ? $data["test2"] : "" ?>">
        <?php render_errors("test2", $validation); ?>
        <input type="file" name="file" accept="application/pdf"><br><br>
        <?php render_errors("file", $validation); ?>
        <input type="submit">
    </form>
</body>
</html>