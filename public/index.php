<?php

use App\Db\Post;

session_start();

require __DIR__ . "/../vendor/autoload.php";

// Recogemos todos los posts para enseñarlos
$posts = Post::read();

// Este if es para cuando queremos cambiar el Status de un post siempre y cuando uno sea Admin
if (isset($_POST['id'])) {
    $post = Post::getPostById($_POST['id']);
    $status = $post[0]->status;

    $cambio = ($status === 'PUBLICADO') ? "BORRADOR" : "PUBLICADO";
    Post::cambiarStatus($cambio, $_POST['id']);

    $posts= POST::read();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <!-- CDN sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CDN tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <!-- Navbar -->
    <nav class="bg-blue-500 text-white px-4 py-2 flex items-center justify-between">
        <!-- Left Section -->
        <div class="flex items-center space-x-4">
            <img src="img/iesalandalus.png" alt="Profile" class="rounded-full w-10 h-10">
            <a href="index.php" class="text-lg font-semibold hover:underline">
                <i class="fas fa-home mr-2"></i>Home
            </a>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-4">
            <?php if (isset($_SESSION['login'])) : ?>

                <!-- Email Display -->
                <input
                    type="text"
                    value="<?= $_SESSION['login'][0] ?>"
                    readonly
                    class="px-4 py-2 rounded-lg bg-white text-gray-800 border border-gray-300 focus:outline-none">
            <?php endif; ?>
            <!-- Login Button -->
            <?php if (!isset($_SESSION['login'])) : ?>
                <a href="login.php" class="px-4 py-2 bg-green-500 rounded-lg hover:bg-green-600 focus:outline-none text-white">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </a>
            <?php endif; ?>
            <?php if (isset($_SESSION['login'])) : ?>
                <!-- Logout Button -->
                <a href="logout.php" class="px-4 py-2 bg-red-500 rounded-lg hover:bg-red-600 focus:outline-none text-white">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            <?php endif; ?>

            <a href="nuevo.php" class="px-4 py-2 bg-pink-500 rounded-lg hover:bg-pink-600 focus:outline-none text-white">
                <i class="fas fa-add mr-2"></i>NUEVO
            </a>

        </div>
    </nav>
    <main class="px-8 mx-auto">
        <div class="mt-4 w-full h-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">

            <!-- Aquí empieza el foreach de la tabla solo para ***Admin*** -->
            <?php foreach ($posts as $item) :
                // Asignamos un color a cada categoría para hacerlo más legible
                $color = match($item->nombre) {
                    'Arte' => 'bg-yellow-500',
                    'Politica' => 'bg-teal-500',
                    'Ciencia' => 'bg-orange-500',
                    'Medio Ambiente' => 'bg-green-500',
                    'Ficción' => 'bg-purple-500',
                    'Historia' => 'bg-red-500',
                    default => 'bg-red-500'
                };
                // Personalizamos tanto el fondo del article como el color del botón de cambio de status
                $colorStatus = ($item->status === 'PUBLICADO') ? "bg-green-500" : "bg-red-500";
                $colorBackground = ($item->status === 'PUBLICADO') ? "bg-green-100" : "bg-red-100";
                // Si estamos logueados (es decir, somos administradores) nos mostrará TODOS los posts
                if (isset($_SESSION['login'])) : 
            ?>
                <article class="w-full h-80 p-2 border-2 rounded-xl shadow-xl border-blue-600 <?= $colorBackground ?> relative">
                    <div class="flex flex-col justify-center w-full h-full">
                        <div>
                            <h1 class="w-3/4 px-2 text-2xl text-teal-800 font-bold py-2 rounded-xl bg-gray-200
                            opacity-50"><?= $item->titulo ?></h1>
                        </div>
                        <div class="mt-4">
                            <p class="italic">
                                <?= $item->contenido ?> </p>
                        </div>
                        <div class="mt-4 w-1/4 mx-auto">
                            <p class="font-bold text-white text-center px-2 py-1 rounded-xl <?= $color ?>">
                                <?= $item->nombre ?> </p>
                        </div>
                        <?php if(isset($_SESSION['login'])) : ?>
                        <form class="mt-4 " action="index.php" method="POST">
                            <input type='hidden' name='id' value="<?= $item->id ?>" />
                            <button type='submit' class="font-bold text-white text-center px-2 py-1 rounded-xl <?= $colorStatus ?> border-2 border-black">
                                <i class="fas fa-redo mr-2"></i><?= $item->status ?> </button>
                        </form>
                        <div class="absolute bottom-2 right-2">
                            <form action='borrar.php' method="POST">
                                <input type='hidden' name='id' value="<?= $item->id ?>" />
                                <a href="update.php?id=<?= $item->id ?>">
                                    <i class="fas fa-edit text-blue-600 text-lg"></i>
                                </a>
                                <button type='submit' onclick="return confirm('¿Borrar definitivamente el Post?');">
                                    <i class="fas fa-trash text-lg text-red-600"></i>
                                </button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endif; ?>
            <?php endforeach; ?>
            <!-- Aquí termina el foreach para la tabla solo para ***Admin*** -->


            <!-- Aquí empieza el foreach de la tabla solo para ***Usuarios no logueados*** -->
            <?php foreach ($posts as $item) :
                // Asignamos un color a cada categoría para hacerlo más legible
                $color = match($item->nombre) {
                    'Arte' => 'bg-yellow-500',
                    'Política' => 'bg-brown-500',
                    'Ciencia' => 'bg-orange-500',
                    'Medio Ambiente' => 'bg-green-500',
                    'Ficción' => 'bg-purple-500',
                    'Historia' => 'bg-red-500',
                    default => 'bg-red-500'
                };
                // Como no vamos a poder ver los BORRADORES, no hace falta hacer operador ternario para asignar colores
                $colorStatus = "bg-green-500";
                $colorBackground = "bg-green-100";
                // Si NO estamos logeados y el post en cuestión está PUBLICADO, lo mostramos. Si no, no lo mostramos
                if (!isset($_SESSION['login']) && $item->status === 'PUBLICADO') :
            ?>
                <article class="w-full h-80 p-2 border-2 rounded-xl shadow-xl border-blue-600 <?= $colorBackground ?> relative">
                    <div class="flex flex-col justify-center w-full h-full">
                        <div>
                            <h1 class="w-3/4 px-2 text-2xl text-teal-800 font-bold py-2 rounded-xl bg-gray-200
                            opacity-50"><?= $item->titulo ?></h1>
                        </div>
                        <div class="mt-4">
                            <p class="italic">
                                <?= $item->contenido ?> </p>
                        </div>
                        <div class="mt-4 w-1/4 mx-auto">
                            <p class="font-bold text-white text-center px-2 py-1 rounded-xl <?= $color ?>">
                                <?= $item->nombre ?> </p>
                        </div>
                        <!-- Dejamos esto (aunque no podría acceder un usuario normal) porque si lo quito, el grid se desconfigura y se ve mal 
                        Pero si no fuese por la estética, se quitaría porque es código inusable 
                        -->
                        <?php if(isset($_SESSION['login'])) : ?>
                        <form class="mt-4 " action="index.php" method="POST">
                            <input type='hidden' name='id' value="<?= $item->id ?>" />
                            <button type='submit' class="font-bold text-white text-center px-2 py-1 rounded-xl <?= $colorStatus ?> border-2 border-black">
                                <i class="fas fa-redo mr-2"></i><?= $item->status ?> </button>
                        </form>
                        <div class="absolute bottom-2 right-2">
                            <form action='borrar.php' method="POST">
                                <input type='hidden' name='id' value="<?= $item->id ?>" />
                                <a href="update.php?id=<?= $item->id ?>">
                                    <i class="fas fa-edit text-blue-600 text-lg"></i>
                                </a>
                                <button type='submit' onclick="return confirm('¿Borrar definitivamente el Post?');">
                                    <i class="fas fa-trash text-lg text-red-600"></i>
                                </button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endif; ?>
            <?php endforeach; ?>
            <!-- Aquí termina el foreach para la tabla solo para ***Usuarios no logueados*** -->
        </div>
    </main>
</body>
<?php if (isset($_SESSION['mensaje'])) : ?>
    <script>
        Swal.fire({
            icon: "success",
            title: "<?= $_SESSION['mensaje'] ?>",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
<?php
    unset($_SESSION['mensaje']);
endif;
?>

</html>