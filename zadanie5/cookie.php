<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>PHP</title>
        <meta charset='UTF-8' />
        <?php require("funkcje.php") ?>
        <link rel="stylesheet" type="text/css" href="style.css" title="Arkusz stylów CSS">
    </head>
    <body>
        <?php
            if (isSet($_GET["utworzCookie"]) && isSet($_GET["czas"]))
            {
                echo ("<h1>Pieczemy ciasteczka</h1>
                        Specjalnie dla Ciebie :)");
                setcookie("HelloCookie", "Mam ciasteczko &ltYUM&gt", time() + $_GET["czas"] * 10,"/"); //time() + (86400*30)
                                                                            //czas w sekundach * 10
            }
        ?>

        <br>

        <a href="index.php">wstecz</a>
    </body>
</html>
