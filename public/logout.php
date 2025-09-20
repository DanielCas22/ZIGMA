<?php
session_start();
session_destroy();
header('Location: /ZIGMA/index.php');
exit;
