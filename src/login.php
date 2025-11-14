<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use services\UsersService;
use services\SessionService;

session_start(); // Aunque SessionService también hace esto, no está de más al inicio del script

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $userService = new UsersService();
    $user = $userService->authenticate($username, $password);

    if ($user) {
        // Iniciar sesión correctamente
        $session = SessionService::getInstance();
        // Obtener roles del usuario y convertir todo a minúsculas
        $roles = array_map('strtolower', $user->getRoles());
        $session->login([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'role' => $roles, // roles normalizados
        ]);

        header('Location: index.php'); // Redirige a inicio
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
//Agregamos cabecera
require_once "header.php"; 
?>

<form action="login.php" method="POST" class="p-4 bg-light rounded shadow-sm">
    <div class="nombreUs">
        <label for="username" class="form-label">Usuario</label>
        <input type="text" name="username" id="username" class="form-control" required>
    </div>

    <div class="passwordUs">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
</form>
<?php require_once "footer.php"; ?>
