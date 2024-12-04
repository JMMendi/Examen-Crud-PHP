<?php

namespace App\Utils;

use App\Db\Categoria;
use App\Db\User;

require __DIR__."/../../vendor/autoload.php";

class Validaciones {
    // Nos aseguramos de que no hay espacios en blanco al principio y al final y de que no nos van a inyectar código html/jss
    public static function sanearCadenas(string $cadena) : string {
        return htmlspecialchars(trim($cadena));
    }

    // Comprobamos que el campo pasado tiene la longitud adecuada
    public static function isLongitudValida(string $nomCampo, string $valor, int $min, int $max) : bool {
        if (strlen($valor) < $min || strlen($valor) > $max) {
            $_SESSION["err_$nomCampo"] = "*** Error, el campo $nomCampo debe estar entre $min y $max caracteres. ***";
            return false;
        }
        return true;
    }

    // Comprobamos si es PUBLICADO o BORRADOR
    public static function isStatusValido(string $status) : bool {
        if (!in_array($status, Datos::devolverStatus())) {
            $_SESSION["err_status"] = "*** Error, el status es inválido. ***";
            return false;
        }
        return true;
    }

    // Comprobamos si la id pasada por POST está en el array de Ids que tiene Categorias
    public static function isCategoriaIdValida(int $id) : bool {
        if (!in_array($id, Categoria::devolverIdsCategorias())) {
            $_SESSION["err_categoria_id"] = "*** ERROR, la categoría es inválida. ***";
            return false;
        }
        return true;
    }

    // Validamos si el formato del email es correcto para el login
    public static function isEmailValido(string $email) : bool {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['err_email'] = "*** ERROR, el email no es válido. ***";
            return false;
        }
        return true;
    }

    // Comprobamos si el Login es valido. Si no, lanzamos un error (esto servirá para las cookies ya que usaremos el mismo código de error para informar
    // al usuario)
    public static function isLoginValido (string $email, string $pass) : bool {
        $datos = User::isLoginValido($email, $pass);
        if (!is_array($datos)) {
            if ($_COOKIE['intento'] != "3") {
                $_SESSION['err_login'] = "Login inválido - Tienes {$_COOKIE['intento']} / 3 intentos"; 
            } else if ($_COOKIE['intento'] == "3") {
                $_SESSION['err_login'] = "*** ERROR, Espere 30 segundos antes de volver a intentar logearse. ***";
            }
            return false;
        }
        $_SESSION['login'] = $datos;
        return true;
    }

    // Esta función pinta los errores en el formulario
    public static function pintarErrores($error) : void {
        if (isset($_SESSION[$error])) {
            echo "<p class='text-red-500 text-sm text-xl italic'>{$_SESSION[$error]}</p>";
            unset($_SESSION[$error]);
        }
    }
}