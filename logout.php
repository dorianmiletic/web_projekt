<?php
session_start();
session_unset();    // briše sve varijable sesije
session_destroy();  // uništava sesiju
header("Location: index.php"); // preusmjeri na početnu ili login
exit;
?>
