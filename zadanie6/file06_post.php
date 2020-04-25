<?php
    session_start();
    if(isSet($_SESSION["failed"]) && isset($_SESSION["error"]))
    {
        printf("<p>Błąd przy tworzeniu pracownika<br/>".$_SESSION["error"]."</p>");
        unset($_SESSION["failed"]);
        unset($_SESSION["error"]);
    }

print<<<KONIEC
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <style>
                .fs { width : 25%; }
            </style>
        </head>
        <body>
            <form action="file06_redirect.php" method="POST">
                <fieldset class="fs">
                    <legend>Wstaw nowego pracownika</legend>
                    <label for="id_prac">Id_prac</label>
                    <input type="text" name="id_prac">
                    <br/>
                    <label for="nazwisko">Nazwisko</label>
                    <input type="text" name="nazwisko">
                    <br/>
                    <input type="submit" value="Wstaw">
                    <input type="reset" value="Wyczyść">
                </fieldset>
            </form>
KONIEC;
    print("<br/>GO TO <a href=file06_get.php>file06_get.php</a>")
?>