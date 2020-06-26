<?php
  require '../config.php'; /*Si no tiene este archivo, no ejecuta nada*/
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar al archivo JS, codificado en utf-8

  $alias = $_POST['alias'];
  $alias = $conexionMySQL->real_escape_string($alias); // Añade el caracter de escape \ si encuentra una cadena que inteta hacer inyección SQL, si no la deja tal cual
  $numSerie = $_POST['numSerie'];
  $numSerie = $conexionMySQL->real_escape_string($numSerie);
  $idUser = intval($_SESSION['idUser']);

  $sentencia = $conexionMySQL->stmt_init(); // Este método indica que la variable podra ocupar las siguientes consultas preparadas, retorna un objeto
  $sentencia->prepare("SELECT numSerie FROM devices WHERE numSerie = ? AND idUser = ?");
  $sentencia->bind_param('si',$numSerie,$idUser);
  $sentencia->execute();
  $resultado = $sentencia->get_result();
  $sentencia->close();
  if($resultado->num_rows == 1)
    echo json_encode(array('mensaje' => "Error, usted ya ha registrado este dispositivo",'alerta' => "error"));
  else
  {
    $sentencia = $conexionMySQL->stmt_init();
    if(!$sentencia->prepare("INSERT INTO devices(idUser,fechaRegistro,alias,numSerie) VALUES(?,NOW(),?,?)"))
    {
      echo json_encode(array('mensaje' => "Error, al procesar la información", 'alerta' => "error"));
    }
    $sentencia->bind_param('iss',$idUser,$alias,$numSerie); //Función que rellena los variables con la información del usuario. la password ya se pasa encriptada
    if(!$sentencia->execute()) //Función que ejecuta los comandos anteriores en la base de datos
    {
      echo json_encode(array('mensaje' => "Error, al procesar la información", 'alerta' => "error"));
    }
    else
    {
      echo json_encode(array('mensaje' => "Se registró el dispositivo con éxito", 'alerta' => "success"));
    }
    $sentencia->close();
  }
  $conexionMySQL->close(); //Se cierra la conexión con la BD
?>
