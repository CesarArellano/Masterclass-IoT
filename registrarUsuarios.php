<?php
  require 'config.php'; /*Si no tiene este archivo, no ejecuta nada*/
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar al archivo JS, codificado en utf-8

  $nombre = $_POST['nombre'];
  $nombre = $conexionMySQL->real_escape_string($nombre); // Añade el caracter de escape \ si encuentra una cadena que inteta hacer inyección SQL, si no la deja tal cual
  $apellidos = $_POST['apellidos'];
  $apellidos = $conexionMySQL->real_escape_string($apellidos);
  $telefono = $_POST['telefono'];
  $telefono = $conexionMySQL->real_escape_string($telefono);
  $fechaNacimiento = $_POST['fechaNacimiento'];
  $fechaNacimiento = $conexionMySQL->real_escape_string($fechaNacimiento);
  $email = $_POST['email'];
  $email = $conexionMySQL->real_escape_string($email);
  $password = $_POST['password'];
  $password = $conexionMySQL->real_escape_string($password);
  $enc_password = password_hash($password,PASSWORD_DEFAULT); //Encriptar la contraseña (ocupa sha512 y lo ocupa 10 veces)

  $sentencia = $conexionMySQL->stmt_init(); // Este método indica que la variable podra ocupar las siguientes consultas preparadas, retorna un objeto
  $sentencia->prepare("SELECT email FROM users WHERE email = ?");
  $sentencia->bind_param('s',$email);
  $sentencia->execute();
  $resultado = $sentencia->get_result();
  $sentencia->close();
  if($resultado->num_rows == 1)
    echo json_encode(array('mensaje' => "Error, usted ya se encuentra registrado", 'pagina' => "registro",'alerta' => "error"));
  else
  {
    $sentencia = $conexionMySQL->stmt_init();
    if(!$sentencia->prepare("INSERT INTO users(firstName,lastName,fechaNacimiento,telefono,email,password) VALUES(?,?,?,?,?,?)"))
    {
      echo json_encode(array('mensaje' => "Error, al procesar la información", 'pagina' => "registro",'alerta' => "error"));
    }
    $sentencia->bind_param('ssssss',$nombre,$apellidos,$fechaNacimiento,$telefono,$email,$enc_password); //Función que rellena los variables con la información del usuario. la password ya se pasa encriptada
    if(!$sentencia->execute()) //Función que ejecuta los comandos anteriores en la base de datos
    {
      echo json_encode(array('mensaje' => "Error, al procesar la información", 'pagina' => "registro",'alerta' => "error"));
    }
    else
    {
      echo json_encode(array('mensaje' => "Te registraste con éxito", 'pagina' => "index",'alerta' => "success"));
    }
    $sentencia->close();
  }
  $conexionMySQL->close(); //Se cierra la conexión con la BD
?>
