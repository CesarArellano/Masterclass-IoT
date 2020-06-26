<?php
  require '../config.php'; // Se incluye el archivo de conexón a la BD
  $_SESSION = array(); // Crear un arreglo con todas las variables session activas.
  session_destroy(); // Destruye las variables de sesión.
  header("location: index.php"); // Redirige a index.php
  $conexionMySQL->close();
?>
