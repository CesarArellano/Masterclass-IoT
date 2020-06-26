<?php
  require '../config.php';
  header('Content-type: application/json; charset=utf-8'); // Se especifica el tipo de contenido a regresar al archivo JS, codificado en utf-8
  $idDevice = intval($_POST['idDevice']);
  $sentencia = $conexionMySQL->stmt_init(); // Este método indica que la variable podra ocupar las siguientes consultas preparadas, retorna un objeto
  $sentencia->prepare("DELETE FROM devices WHERE idDevice = ?");
  $sentencia->bind_param('i',$idDevice);
  if($sentencia->execute())
  {
    echo json_encode(array('mensaje' => "Se eliminó el dispositivo con éxito", 'alerta' => "success"));
  }
  else
  {
    echo json_encode(array('mensaje' => "Error, no se pudo eliminar el dispositivo", 'alerta' => "error"));
  }
  $sentencia->close();
  $conexionMySQL->close();
?>
