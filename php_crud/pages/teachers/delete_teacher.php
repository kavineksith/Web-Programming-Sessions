<?php
    session_start();

    if (!isset($_SESSION["user"]) || $_SESSION["user"] !== "loggedIn") {
        header('Location: ../login.php');
        exit();
    }

    require_once '../../config/config.php';

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);

        $checkSql = "SELECT * FROM teachers WHERE id = $id";
        $checkResult = mysqli_query($conn, $checkSql);

        if (mysqli_num_rows($checkResult) > 0) {
            $deleteSql = "DELETE FROM teachers WHERE id = $id";
            if (mysqli_query($conn, $deleteSql)) {
                $_SESSION['message'] = "Teacher deleted successfully.";
            } else {
                $_SESSION['message'] = "Error deleting teacher: " . mysqli_error($conn);
            }
        } else {
            $_SESSION['message'] = "Teacher not found.";
        }
    } else {
        $_SESSION['message'] = "Invalid request.";
    }

    mysqli_close($conn);
    header("Location: teachers-view.php");
    exit();

?>