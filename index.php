<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email=filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $password=$_POST['clave'];
    include "include/database.php";
    $buscar=$conexion->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $buscar->execute([$email]);
    $user=$buscar->fetch(PDO::FETCH_ASSOC);
    if(empty($user)) {
       echo "";
    }else{
       $_SESSION['user_id'] = $user['id'];
    }
    
    if($user && (password_verify($password, $user['clave']) || $password === $user['clave'])) {
        echo "<h2 style='color: green;'>¡Inicio de sesión exitoso! Bienvenido. " . htmlspecialchars($user['nombre'])."</h2>";
        $conec=$conexion->prepare("SELECT * FROM contactos WHERE usuario_id = ?");
        $conec->execute([$_SESSION['user_id']]);
        $listar=$conec->fetchALL(PDO::FETCH_ASSOC);
        if(empty($listar)) {
            echo "<h2>No tienes contactos almacenados</h2>";?>
            <a href="include/funciones.php"><button>Ir a panel de administración</button></a><?php
        }else{
            echo "<h2>Tu lista de contactos:</h2>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>Nombre</th><th>telefono</th><th>Correo</th><th>Fecha de creacion</th></tr>";
            foreach($listar as $u) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($u['nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($u['telefono']) . "</td>";
                echo "<td>" . htmlspecialchars($u['correo']) . "</td>";
                echo "<td>" . htmlspecialchars($u['fecha_creacion']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";?>
            <br><a href="include/funciones.php"><button>Ir a panel de administración</button></a><?php
        }
    }elseif(!$user) {
        echo "<h2 style='color: red;'>Usuario inexistente</h2>";?>
        <a href="agregar.php"><button>Registrate aquí</button></a><?php
    }else{
        echo "<h2 style='color: red;'>Correo o contraseña incorrectos, inicio de sesíon rechazado</h2>";
    }
}
?>

<!DOCTYPE html>
<html>
    <body>
        <form method="POST" action="">
           <h2>Inicio de sesíon</h2>
           Correo: <input type="email" name="correo" required><br><br>
           Clave: <input type="password" name="clave" required><br><br>
           <input type="submit" value="Enviar">
        </form>
        <br>¿ Aun no tienes cuenta ?, create una  <a href="agregar.php">Aquí</a>
    </body>
</html>