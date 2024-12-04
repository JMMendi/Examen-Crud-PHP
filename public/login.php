<?php

use App\Utils\Validaciones;

    session_start();

    $intento = (isset($_COOKIE['intento'])) ? $_COOKIE['intento'] : 1;

    if(!isset($_COOKIE['intento'])) {
        setcookie('intento', $intento);
    }

    if ($intento > 3) {
        setcookie('intento', $intento, time()+30);
    }
 
    // Hay un pequeño fallo de que, cuando se recarga la primera vez la página tras acabarse el tiempo de espera
    // muestra que $_COOKIE['intento'] no existe. Si se recarga nuevamente no aparece el error


    // Si ya estamos logeados, no tiene sentido hacer otro login
    if (isset($_SESSION['login'])) {
        header("Location:index.php");
        exit;
    }

    require __DIR__."/../vendor/autoload.php";

    // Recogemos los datos para ver si el login es correcto.
    if (isset($_POST['email'])) {
        // Saneamos cadenas 
        $email = Validaciones::sanearCadenas($_POST['email']);
        $pass = Validaciones::sanearCadenas($_POST['pass']);

        $errores = false;
        // Comprobamos si son válidos.

        if (!Validaciones::isEmailValido($email)) {
            $errores = true;
        }
        if (!Validaciones::isLongitudValida('pass', $pass, 5, 150)) {
            $errores = true;
        }
        

        // Y ahora vemos si el login es válido.

        if (!Validaciones::isLoginValido($email, $pass)) {
            $errores = true;
            $intento++;
            setcookie('intento', $intento);

        }
        

        if ($errores) {
            header("Location:login.php");
            exit;
        }

        // Si llegamos hasta aquí, el login ha sido correcto. Volvemos a index.php

        $_SESSION['mensaje'] = "Login realizado con éxito.";
        header("Location:index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen</title>
    <!-- CDN sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CDN tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="flex items-center justify-center min-h-screen bg-orange-200">
    <div class="bg-white p-8 rounded-xl shadow-xl w-96">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">Login</h2>
        <form method='POST' action="/usuario15/login.php">
            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="block text-gray-600 mb-1">
                    <i class="fas fa-envelope mr-2"></i>Email
                </label>
                <div class="relative">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <i class="fas fa-user absolute top-3 right-3 text-gray-400"></i>
                </div>
                <?= Validaciones::pintarErrores("err_email") ?>
            </div>
            <!-- Password Field -->
            <div class="mb-4">
                <label for="password" class="block text-gray-600 mb-1">
                    <i class="fas fa-lock mr-2"></i>Password
                </label>
                <div class="relative">
                    <input
                        type="password"
                        id="password"
                        name="pass"
                        placeholder="Enter your password"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <i class="fas fa-key absolute top-3 right-3 text-gray-400"></i>
                </div>
                <?= Validaciones::pintarErrores("err_pass") ?>
                <?= Validaciones::pintarErrores("err_login") ?>

            </div>
            <!-- Buttons -->
            <div class="flex items-center justify-between">
                <a
                    href="index.php"
                    class="inline-block px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 focus:outline-none">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
                <button
                    type="reset"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none">
                    <i class="fas fa-redo mr-2"></i>Reset
                </button>
                <?php if ($_COOKIE['intento'] <= 3) : ?>
                <button
                    type="submit"
                    class=" px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
                <?php endif; ?>
            </div>
        </form>

    </div>
</body>

</html>