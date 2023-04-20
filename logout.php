<?php

    session_start();
    session_unset();
    session_destroy();

    $conn = null;

    header("Location: index.php");

?>