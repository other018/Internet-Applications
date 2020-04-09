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
        <fieldset class="fs">
            <?php
                if (isSet($_SESSION["zalogowany"]) && $_SESSION["zalogowany"] == 1)
                {
                    echo ("<h3>Zalogowano " . $_SESSION["zalogowanyImie"] . "</h3>");
                }
                else
                    header("Location: index.php");
            ?>
        </fieldset>

        <br>
        
        <form action="user.php" method="POST" enctype="multipart/form-data">
            <fieldset class="fs">
                <legend>Obrazek</legend>
                <label for="myFile">Wybierz zdjęcie:</label>
                <input type="file" name="myFile" id="myFile">
                <br>
                <input type="submit" name="zaladuj" value="Załaduj obraz">
                <br>

                <?php
                    if (isSet($_POST["zaladuj"])){
                        $currentDir = getcwd();
                        $uploadDir = "/zdjeciaUzytkownikow/";
                        if (!file_exists($currentDir . $uploadDir)) {
                            mkdir($currentDir . $uploadDir);//, 0777, true);
                        }
                        $fileName = $_FILES['myFile']['name'];
                        $fileSize = $_FILES['myFile']['size'];
                        $fileTmpName = $_FILES['myFile']['tmp_name'];
                        $fileType = $_FILES['myFile']['type'];
                        if ($fileName != "" and 
                            ($fileType == 'image/png' or $fileType == 'image/jpeg' or $fileType == 'image/PNG' or $fileType == 'image/JPEG'))
                        {
                            $uploadPath = $currentDir . $uploadDir . $fileName;
                            if (move_uploaded_file($fileTmpName, $uploadPath))
                                echo "<br>Zdjęcie zostało załadowane na serwer FTP <br>";
                            else echo "<br>Wystąpił błąd<br>";
                            echo ('<img class=image src="zdjeciaUzytkownikow/' . $fileName . '" alt="Błąd w ładowaniu obrazka" />');
                        }
                        
                    }
                ?>
            </fieldset>
        </form>
        
        <br>
        
        <form action="index.php" method="POST">
            <fieldset class="fs">
                <legend>Log out</legend>
                <input type="submit" name="wyloguj" value="Wyloguj">
            </fieldset>
        </form>

        <br>
        
        GO TO <a href="index.php">index.php</a>

    </body>
</html>