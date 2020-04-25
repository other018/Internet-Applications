<?php
    session_start();
    
    if(isSet($_SESSION["passed"]))
    {
        printf("<p>Dodano pracownika</p>");
        unset($_SESSION["passed"]);
    }
    
    $link = mysqli_connect("localhost", "scott", "tiger", "instytut");
    if(!$link) {
        printf("Connect failed: %s<br/>", mysqli_connect_error());
        exit();
    }

    $sql = "SELECT * FROM pracownicy";
    $result = $link -> query($sql);
    
    printf("<h1>Pracownicy</h1>");
    printf("<table border=\"1\">");
    printf("<tr><th>ID_PRAC</th><th>NAZWISKO</th></tr>");
    
    foreach ($result as $v) {
        printf("<tr><td>%d</td><td>%s</td></tr>", $v["ID_PRAC"], $v["NAZWISKO"]);
    }
        
    printf("</table>");
    printf("<i>query returned %d rows</i>", mysqli_num_rows($result));

    $result -> free();
    $link -> close();

    print("<br/>GO TO <a href=file06_post.php>file06_post.php</a>")
?>