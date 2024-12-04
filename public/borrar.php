<?php

use App\Db\Post;

session_start();

// Solo el admin puede borrar posts.
if (!isset($_SESSION['login'])) {
    header("Location:index.php");
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

// Si no existe el id o es menor o igual que 0, volvemos a index
if (!$id || $id <= 0) {
    header("Location:index.php");
    exit;
}

require __DIR__."/../vendor/autoload.php";

//Si llegamos aquí, podemos borrar el post usando delete.

Post::delete($id);

$_SESSION['mensaje'] = "El Post ha sido eliminado correctamente.";
header("Location:index.php");