<?php
  require '../config.php';
  $idUser = intval($_SESSION['idUser']);
  $sentencia = $conexionMySQL->stmt_init(); // Este método indica que la variable podra ocupar las siguientes consultas preparadas, retorna un objeto
  $sentencia->prepare("SELECT * FROM devices WHERE idUser = ?");
  $sentencia->bind_param('i',$idUser);
  $sentencia->execute();
  $resultado = $sentencia->get_result();
  $sentencia->close();
  $contenido = "<div class='card hoverable'>
    <div class='card-content'>
      <h3 class='center-align'>Listado de dispositivos</h3>";
  if($resultado->num_rows > 0)
  {
    $contenido.="<table class='responsive-table centered highlight'>
      <thead>
       <th>Alias</th>
       <th>Fecha</th>
       <th>Num.Serie</th>
       <th>Eliminar</th>
      </thead>
      <tbody>";
    while($campos = $resultado->fetch_assoc())
    {
      $contenido.="<tr>
        <td>".$campos['alias']."</td>
        <td>".$campos['fechaRegistro']."</td>
        <td>".$campos['numSerie']."</td>
        <td><a class='btn-floating btn-large waves-effect waves-light red' onclick='deleteDevice(".$campos['idDevice'].")'><i class='material-icons'>phonelink_erase</i></a></td>
      </tr>";

    }
    $contenido.= "</tbody></table>";
  }
  else
  {
    $contenido.="<h5 class='center-align'>No hay dispositivos registrados</h5>";
  }
  $contenido .= "</div></div>";

  echo $contenido;
  $conexionMySQL->close();
?>
