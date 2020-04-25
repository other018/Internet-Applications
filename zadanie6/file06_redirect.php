<?php
    session_start();

    $link = mysqli_connect("localhost", "scott", "tiger", "instytut");

    if(!$link) {
        $_SESSION["failed"] = True;
        $_SESSION["error"] = mysqli_connect_error();
        header("Location: file06_post.php");
        exit();
    }

    if(isset($_POST['id_prac']) && is_numeric($_POST['id_prac']) && is_string($_POST['nazwisko'])) {
        $sql = "INSERT INTO pracownicy(id_prac, nazwisko) VALUES (?,?)";
        $stmt = $link -> prepare($sql);
        $stmt -> bind_param ("is", $_POST['id_prac'], $_POST['nazwisko']);
        $result = $stmt -> execute();
        if($result) {
            $_SESSION["passed"] = True;
            $stmt -> close();
            $link -> close();
            header("Location: file06_get.php");
            exit();
        }

        if(!$result) {
            $_SESSION["failed"] = True;
            $_SESSION["error"] = mysqli_error($link);
            $stmt -> close();
            $link -> close();
            header("Location: file06_post.php");
            exit();
        }
    }

    else {
        $_SESSION["failed"] = True;
        $_SESSION["error"] = mysqli_error($link);
        $link -> close();
        header("Location: file06_post.php");
    }
?>