<html>
    <body>
        <h1 style="color: green;">Bienvenido a tu panel de administracion</h1>
        <h2>Aqui podras Crear, Leer, Modificar y Eliminar tus contactos. Solo selecciona la accion que quieras hacer:</h2>
    </body>
</html>
<?php
session_start();
include "database.php";
$user_id=$_SESSION['user_id'];
$conec=$conexion->prepare("SELECT * FROM contactos WHERE usuario_id = ?");
$conec->execute([$user_id]);
$listar=$conec->fetchALL(PDO::FETCH_ASSOC);
if(empty($listar)) {
    echo "<h2>No tienes contactos almacenados</h2>";
    ?><br><form method='POST' action=''>
              <input type='submit' value='Crear nuevo contacto🖌️​' name='crear'>
              <input style='color: red; float: right;' type='submit' value='Cerrar sesíon​' name='cerrar'>
         </form>
    <?php
}else{
    echo "<h2>Tu lista de contactos:</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Nombre</th><th>telefono</th><th>Correo</th><th>Fecha de creacion</th><th>Modificar</th><th>Eliminar</th></tr>";
    foreach($listar as $u) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($u['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($u['telefono']) . "</td>";
        echo "<td>" . htmlspecialchars($u['correo']) . "</td>";
        echo "<td>" . htmlspecialchars($u['fecha_creacion']) . "</td>";
        echo "<td> <form method='POST' action=''><input type='hidden' name='id' value='".$u['id']."'><input type='submit' value='     ✏️​     ​' name='modificar'></form> </td>";
        echo "<td> <form method='POST' action=''><input type='hidden' name='id' value='".$u['id']."'><input type='submit' value='    🗑️    ​​' name='eliminar'></form> </td>";
        echo "</tr>";
    }
    echo "</table>";
    ?><br><form method='POST' action=''>
              <input type='submit' value='Crear nuevo contacto 🖌️​' name='crear'>
              <input style='color: red; float: right;' type='submit' value='Cerrar sesíon​' name='cerrar'>
          </form>
    <br><hr>
    <?php

}
//Bloque de codigo para agregar un nuevo contacto
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['crear'])) {
        echo "<h2>Has elegido la opcion de crear un nuevo contacto.</h2>";
        ?>
          <html>
            <body>
                <form method="POST" action="">
                   <h3>Porfavor llena los siguientes espacios para crear el nuevo contacto:</h3>
                   <input type="hidden" name="accion" value="guardar">
                   Nombre: <input type="text" name="nombre" required><br><br>
                   Telefono: <input type="text" name="telefono" required><br><br>
                   Correo: <input type="email" name="correo" required><br><br>
                   <input type="submit" value="enviar">
                </form>
             </body>
          </html>
       <?php
    }
    if (isset($_POST['accion']) && $_POST['accion'] === 'guardar') {
        $name = htmlspecialchars($_POST['nombre'] ?? '');
        $correo = filter_var($_POST['correo'] ?? '', FILTER_SANITIZE_EMAIL);
        $tel = preg_replace("/[^0-9]/", "", $_POST['telefono'] ?? '');
        $errores=[];
        if (empty(trim($name))) {
           $errores[]="<h3 style='color: red;'>El nombre es obligatorio.</h3>";
        }
        if (empty(trim($tel))) {
           $errores[]="<h3 style='color: red;'>El numero de telefono aun no esta especificado.</h3>";
        }
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
           $errores[]="<h3 style='color: red;'>El email no es válido.</h3>";
        }
        if(empty($errores)) {
            $ingre=$conexion->prepare("INSERT INTO contactos (usuario_id, nombre, telefono, correo) VALUES (?, ?, ?, ?)");
            $exito=$ingre->execute([$user_id, $name, $tel, $correo]);
            if ($exito) {
                echo "<h3 style='color: green;'>¡Contacto creado con exito! :)</h3>";
                header("Refresh:1; url=funciones.php");
            } else {
                echo "<h3>Tu contacto no se pudo crear debido a un problema, intentalo de nuevo</h3>";
            }
            $ingre=null;
            $conexion=null;
        } else {
        // Mostrar errores
            foreach ($errores as $error) {
                echo "<div class='error'>$error</div>";
            }
        }
    }
    //Bloque de codigo para eliminar un contacto
    if (isset($_POST['eliminar'])) {
        $id = $_POST['id'];
        echo "<h2>¿Estas seguro/a de querer eliminar este contacto?</h3>";
        ?><br><form method='POST' action=''>
            <input type='hidden' name='id' value='<?php echo $id; ?>'>
            <input type='submit' value='Cancelar​' name='cancelar'>
            <input type='submit' value='Eliminar​' name='quitar'>
        </form>
        <?php
    }
    if (isset($_POST['quitar'])) {
       $id = $_POST['id'];
       $sacar=$conexion->query("DELETE FROM contactos WHERE id=".$id);
       echo "<h2>Contacto eliminado con exito.</h2>";
       header("Refresh:1; url=funciones.php");
    }elseif(isset($_POST['cancelar'])) {
        echo "";
    }
    //Bloque de codigo para modificar un contacto
    if (isset($_POST['modificar'])) {
       $id = $_POST['id'];
       ?>
       <h2>Por favor selecciona que campo quieres modificar:</h3>
       <br><form method='POST' action=''>
               <input type='hidden' name='id' value='<?php echo $id; ?>'>
               <input type='submit' value='Nombre​' name='name'>
               <input type='submit' value='Telefono' name='celular'>
               <input type='submit' value='Correo​' name='email'>
            </form>
       <?php
    }
    //Modificar nombre
    if (isset($_POST['name'])) {
       $id = $_POST['id'];
       ?>
       <h2>Especifica el nuevo nombre para tu contacto:</h3>
       <br><form method='POST' action=''>
               <input type='hidden' name='id' value='<?php echo $id; ?>'>
               <input type="hidden" name="action" value="enviado">
               Nuevo nombre: <input type='text' name='nombre'>
               <input type="submit" value="enviar">
            </form>
       <?php
    }
    if (isset($_POST['action']) && $_POST['action'] === 'enviado') {
       $nombre=htmlspecialchars($_POST['nombre'] ?? '');
       $id = $_POST['id'];
       $errores=[];
        if (empty(trim($nombre))) {
           $errores[]="<h3 style='color: red;'>No has especificado el nombre aun.</h3>";
        }
        if(empty($errores)) {
            $update=$conexion->prepare("UPDATE contactos SET nombre= ? WHERE id= ? ");
            $exito=$update->execute([$nombre, $id]);
            if ($exito) {
                echo "<h3 style='color: green;'>¡Cambio creado con exito! :)</h3>";
                header("Refresh:1; url=funciones.php");
            } else {
                echo "<h3>Tu cambio no se pudo crear debido a un problema, intentalo de nuevo</h3>";
            }
        }else{
            foreach ($errores as $error) {
                echo "<div class='error'>$error</div>";
            }
            header("Refresh:2; url=funciones.php");
        }
    }
    //Modificar telefono
    if (isset($_POST['celular'])) {
       $id = $_POST['id'];
       ?>
       <h2>Especifica el nuevo telefono para tu contacto:</h3>
       <br><form method='POST' action=''>
               <input type='hidden' name='id' value='<?php echo $id; ?>'>
               <input type="hidden" name="action" value="listo">
               Nuevo telefono: <input type='text' name='tel'>
               <input type="submit" value="enviar">
            </form>
       <?php
    }
    if (isset($_POST['action']) && $_POST['action'] === 'listo') {
       $tel=preg_replace("/[^0-9]/", "", $_POST['tel'] ?? '');
       $id=$_POST['id'];
       $errores=[];
        if (empty(trim($tel))) {
           $errores[]="<h3 style='color: red;'>No has especificado el telefono aun.</h3>";
        }
        if(empty($errores)) {
            $update=$conexion->prepare("UPDATE contactos SET telefono= ? WHERE id= ? ");
            $exito=$update->execute([$tel, $id]);
            if ($exito) {
                echo "<h3 style='color: green;'>¡Cambio creado con exito! :)</h3>";
                header("Refresh:1; url=funciones.php");
            } else {
                echo "<h3>Tu cambio no se pudo crear debido a un problema, intentalo de nuevo</h3>";
            }
        }else{
            foreach ($errores as $error) {
                echo "<div class='error'>$error</div>";
            }
            header("Refresh:2; url=funciones.php");
        }
    }
    //Modificar correo
    if (isset($_POST['email'])) {
       $id = $_POST['id'];
       ?>
       <h2>Especifica el nuevo correo para tu contacto:</h3>
       <br><form method='POST' action=''>
               <input type='hidden' name='id' value='<?php echo $id; ?>'>
               <input type="hidden" name="action" value="ok">
               Nuevo correo: <input type='email' name='correo' required>
               <input type="submit" value="enviar">
            </form>
       <?php
    }
    if (isset($_POST['action']) && $_POST['action'] === 'ok') {
       $correo=filter_var($_POST['correo'] ?? '', FILTER_SANITIZE_EMAIL);
       $id=$_POST['id'];
       $errores=[];
        if (empty(trim($correo))) {
           $errores[]="<h3 style='color: red;'>No has especificado el correo aun.</h3>";
        }
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
           $errores[]="<h3 style='color: red;'>El email no es válido.</h3>";
        }
        if(empty($errores)) {
            $update=$conexion->prepare("UPDATE contactos SET correo= ? WHERE id= ? ");
            $exito=$update->execute([$correo, $id]);
            if ($exito) {
                echo "<h3 style='color: green;'>¡Cambio creado con exito! :)</h3>";
                header("Refresh:1; url=funciones.php");
            } else {
                echo "<h3>Tu cambio no se pudo crear debido a un problema, intentalo de nuevo</h3>";
            }
        }else{
            foreach ($errores as $error) {
               echo "<div class='error'>$error</div>";
            }
            header("Refresh:2; url=funciones.php");
        }
    }
    if (isset($_POST['cerrar'])) {
        session_destroy();
        header('Location: ../index.php');
    }
}
?>
