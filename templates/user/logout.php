<?php
$_SESSION = [];
session_destroy();
header('Location: ?x=login');
?>
