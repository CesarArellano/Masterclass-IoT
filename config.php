<?php
  session_start();
  $conexionMySQL = new mysqli('localhost', 'admin_cursoiot', "Breaktime2018", "admin_cursoiot"); //Variable que te permite el acceso a la BD
  if($conexionMySQL->connect_error)
  {
    echo "Error, no se pudo conectar a la BD";
    exit();
  }
  /* cambiar el conjunto de caracteres a utf8 */
  if(!$conexionMySQL->set_charset("utf8"))
  {
    printf("Error cargando el conjunto de caracteres utf8: %s\n", $conexionMySQL->error);
    exit();
  }
?>
