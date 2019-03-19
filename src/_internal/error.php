<html lang="en">
<head>
    <title>Error</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <style>
        html {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            background-color: #333333;
            max-height: 100vh;
        }

        header {
            background-color: mediumseagreen;
            color:white;
            text-align: center;
            padding:5px;
        }

        header > h2 {
            margin:5px;
        }

        .title {
            color: rebeccapurple
        }

        #main {
            display: flex;
            justify-content: space-evenly;
            align-items: flex-start;
            margin: 30px 0 30px 0;
            height: 80%;
        }

        #main > div {
            padding:5px;
        }

        .file {
            background-color: white;
        }

        .trace {
            background-color: white;
            overflow: scroll;
            height: 100%;
        }

        .file > h5 {
            margin: 0 0 10px 0;
            padding: 5px;
            text-align: center;
            color:white;
            background-color: mediumseagreen
        }

        .f-line {
            margin: 0;
        }

        .f-line.err {
            text-decoration: underline wavy red;
        }

        .trace > table {
            padding:5px;
            font-size: 11px;
        }

        .trace th {
            background-color: rgba(183,21,25,0.82);
            color:white;
        }

        .trace td {
            padding: 2px;
            border-bottom: solid 1px rgba(183,21,25,0.82);
        }

    </style>
</head>
<body>
    <header>
        <h2><span class="title"><?= get_class($exception) ?></span> :
        <span class="message"><?= $exception->getMessage() ?></span></h2>
    </header>
    <div id='main'>
        <div class="trace">
            <table >
                <tr>
                    <th>File</th>
                    <th>Function</th>
                </tr>
                <?php foreach ($exception->getTrace() as $step) :
                    $file = basename(isset($step["file"]) ? $step["file"] : $exception->getFile());
                    $class = isset($step["class"]) ? $step["class"] . "->" : "";
                    $function = $step["function"];
                    $line = isset($step["line"]) ? $step["line"] : $exception->getLine();
                    ?>
                    <tr>
                        <td>
                            <?= "$file:$line" ?>
                        </td>
                        <td>
                            <?= "$class$function()" ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="file">
            <h5>
                <?= $exception->getFile() ?>:<?= $exception->getLine() ?>
            </h5>
            <code>
                <?php
                $eline = $exception->getLine();
                $lines = explode('<br />', highlight_file($exception->getFile(), true));
                foreach ($lines as $key => $line) :
                    if ($key < $eline - 15 || $key > $eline + 15) continue;
                    $err = $key + 1 === $eline;
                    ?>
                    <p class="f-line <?= $err ? "err" : "" ?>">
                        <span><?= $err ? "&#10145;" : $key + 1 ?></span>
                        <?= $line ?>
                    </p>
                <? endforeach; ?>
            </code>
        </div>
    </div>
</body>
</html>
