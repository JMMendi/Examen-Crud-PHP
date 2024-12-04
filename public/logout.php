<?php

session_start();

session_destroy();

session_start();

$_SESSION['mensaje'] = "Cerrada la sesión correctamente.";

header("Location:index.php");