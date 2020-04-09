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
            echo "<h1>Logowanie w trakcie</h1>";
            if((isSet($_POST["zaloguj"])) && (!empty($_POST["login"])) && (!empty($_POST["haslo"]))){
                $tested_login = test_input($_POST["login"]);
                $tested_password = test_input($_POST["haslo"]);
//                echo "Przeslany login: " . $tested_login . "<br>";
//                echo "Przeslane haslo: " . $tested_password . "<br>";
                if ($tested_login == $osoba1->login && $tested_password == $osoba1->haslo)
                {
                    $_SESSION["zalogowanyImie"] = $osoba1->imieNazwisko;
                }
                else if ($tested_login == $osoba2->login && $tested_password == $osoba2->haslo)
                {
                    $_SESSION["zalogowanyImie"] = $osoba2->imieNazwisko;
                }
                if(isSet($_SESSION["zalogowanyImie"]))
                {
                    $_SESSION["zalogowany"] = 1;
                    header("Location: user.php");
                }
                else
                {
                    header("Location: index.php");
                }
            }
            else
            {
                header("Location: index.php");
            }
        ?>
    </body>
</html>