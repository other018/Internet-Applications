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
        <?php echo "<h1>Nasz system</h1>"; ?>
        
        <?php
            if(isSet($_POST["wyloguj"]))
            {
                unset($_SESSION["zalogowanyImie"]);
                $_SESSION["zalogowany"] = 0;
            }
        ?>

        <form action="logowanie.php" method="POST">
            <fieldset class="fs">
                <legend>Log in</legend>
                <label for="login">Login</label>
                <input type ="text" name = "login">
                <br>
                <label for="haslo">Haslo</label>
                <input type ="password" name = "haslo">
                <br>
                <input type="submit" name="zaloguj" value="Zaloguj"><br>
            </fieldset>
        </form>
        
        <br>
        GO TO <a href = "user.php">user.php</a>
        <br>

        <?php echo "<h1>Drugi formularz</h1>"; ?>
        <form action="cookie.php" method="GET">
            <fieldset class="fs">
                <legend>Ciasteczko?</legend>
                <label for="czas">Liczba</label>
                <input type ="number" name = "czas">
                <br>
                <input type="submit" name="utworzCookie" value="Utwórz COOKIE">
            </fieldset>
        </form>

        <h1> Cookie section </h1>
        <fieldset class="fs">
            <?php
                if (isSet($_COOKIE["HelloCookie"]))
                    echo ($_COOKIE["HelloCookie"]);
                else
                    echo ("Nie ma ciasteczek :(")
            ?>
        </fieldset>
    </body>
</html>