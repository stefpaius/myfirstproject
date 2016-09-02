<?php
require_once 'init.php';

if(session_destroy()) {
    header("Location: login.php");
}

?>
