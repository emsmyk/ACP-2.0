<?php
$_SESSION = [];
session_destroy();
redirect('?x=login');
?>
