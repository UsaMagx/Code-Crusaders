<?php
session_start();
if(isset($_SESSION['access'])){
    session_unset();

    session_destroy();
}
header("Location:../index.php");
?>