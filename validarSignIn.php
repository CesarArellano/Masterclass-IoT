<?php
  require 'config.php'; /*Si no tiene este archivo, no ejecuta nada*/
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar al archivo JS, codificado en utf-8

  $email = $_POST['email'];
  $email = $conexionMySQL->real_escape_string($email);
  $password = $_POST['password'];
  $password = $conexionMySQL->real_escape_string($password);
  $sentencia = $conexionMySQL->stmt_init(); // Este método indica que la variable podra ocupar las siguientes consultas preparadas, retorna un objeto
  $sentencia->prepare("SELECT idUser,firstName,password FROM users WHERE email = ?");
  $sentencia->bind_param('s',$email);
  $sentencia->execute();
  $resultado = $sentencia->get_result();
  $sentencia->close();
  if($resultado->num_rows == 1)
  {
    $campos = $resultado->fetch_assoc();
    if (password_verify($password, $campos['password']))
    {
      $_SESSION['idUser'] = $campos['idUser'];
      $_SESSION['firstName'] = $campos['firstName'];
      echo json_encode(array('mensaje' => "Bienvenido al sistema", 'pagina' => "dashboard",'alerta' => "success"));
    }
    else
    {
      echo json_encode(array('mensaje' => "Error, email y/o contraseña incorrecto(s)", 'pagina' => "index",'alerta' => "error"));
    }
  }
  else
    echo json_encode(array('mensaje' => "Error, email y/o contraseña incorrecto(s)", 'pagina' => "index",'alerta' => "error"));

  $conexionMySQL->close();
?>
