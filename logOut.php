 <?php
  session_start();
  $_SESSION = array();
  session_destroy(); // Destruye las variables de sesión.
  header("location: index.php"); // Redirige a index.php
?>
