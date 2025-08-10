<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name=htmlspecialchars($_POST['nombre']);
    $email=filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $password=$_POST['clave'];
    include "include/database.php";
    $errores=[];
    $consul=$conexion->query("SELECT * FROM usuarios");
    $b=$consul->fetchALL(PDO::FETCH_ASSOC);
    foreach($b as $s) {
        if($s['correo']===$email) {
            $errores[]="<h3 style='color: red;'>El correo ingresado ya existe, agrega otro por favor.</h3>";
            break;
        }
    }
    if (empty(trim($name))) {
        $errores[]="<h3 style='color: red;'>El nombre es obligatorio.</h3>";
    }
    if (empty(trim($password))) {
        $errores[]="<h3 style='color: red;'>La contraseña no la especificaste.</h3>";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[]="<h3 style='color: red;'>El email no es válido.</h3>";
    }
    if(empty($errores)) {
       $passwordh=password_hash($password, PASSWORD_DEFAULT);
       $consul=$conexion->prepare("INSERT INTO usuarios (nombre, correo, clave) VALUES (?, ?, ?)");
       $exito=$consul->execute([$name, $email, $passwordh]);
       if ($exito) {
          echo "<h3 style='color: green;'>¡Registro exitoso! :)</h3>";?>
          <a href="index.php"><button>Ir a inicio de sesíon</button></a><?php
       } else {
          echo "Error: " . $consul->$error;
       }
       $consul=null;
       $conexion=null;
    } else {
        // Mostrar errores
        foreach ($errores as $error) {
            echo "<div class='error'>$error</div>";
        }
    }

}
?>
<!DOCTYPE html>
<html>
    <body>
        <form method="POST" action="">
            <h2>Registrate aqui:</h2>
            Nombre: <input type="text" name="nombre" required><br><br>
            Correo: <input type="email" name="correo" required><br><br>
            Contraseña: <input type="password" name="clave" required><br><br>
            <input type="submit" value="enviar">
        </form>
    </body>
</html>