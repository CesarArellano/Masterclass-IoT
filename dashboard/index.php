<?php
  session_start();
  if(!isset($_SESSION['idUser']))
  {
      header('location: ../index.php');
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Dashboard</title>
	<meta charset="utf-8">
  <link rel="shortcut icon" href="../images/icono.png">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.3.2/sweetalert2.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
  <link type="text/css" rel="stylesheet" href="../css/adminStyles.css"  media="screen,projection"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script><!--Biblioteca de funciones que simplifica el usor de JS-->
  <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.3.2/sweetalert2.js" charset="utf-8"></script>
  <script type="text/javascript" src="../js/adminScript.js">

  </script>
</head>
<body>
<main>
	<nav class="teal darken-2">
		<div class="nav-wrapper">
			<a class="brand-logo center" id="naveTitle">Principal</a>
			<img src="../images/icono.png" class="brand-logo right" height="50px" id="nav-logo">
			<a href="#" id="activar" data-activates="panel" class="button-collapse"><i class="material-icons">menu</i></a>
		</div>
	</nav>
  <div class="sections" style="margin:20px;">
    <div id="main">
      <div class="row">
        <div class="col s12">
          <ul class="collapsible hoverable" data-collapsible="expandable">
            <li>
              <div class="collapsible-header"><i class="material-icons hoverable circle">filter_drama</i>Temperatura</div>
              <div class="collapsible-body white"><span class="flow-text">Actual:</span><span id="displayTemp1" class="flow-text"> --</span><br><span>Promedio: 17°C</span></div>
            </li>
            <li>
              <div class="collapsible-header"><i class="material-icons hoverable circle">computer</i>Computadora</div>
              <div class="collapsible-body white"><span class="flow-text">Actual:</span><span id="displayTemp2" class="flow-text"> --</span><br><span>Temp. Pico: 70°C</span></div>
            </li>
            <li>
              <div class="collapsible-header"><i class="material-icons hoverable circle">power</i>Voltaje</div>
              <div class="collapsible-body white"><span class="flow-text">Actual:</span><span id="displayVolt" class="flow-text"> --</span><br><span>Tensión Pico: 12V</span></div>
            </li>
          </ul>
        </div>
        <div class="col s12 m12 l4">
          <div class="card-panel hoverable light-blue darken-2 center-align">
            <span class="flow-text white-text">LED 1</span>
            <br>
            <div class="switch btn-large white">
              <label class="blue-text">
                Off
                <input id="inputLed1" type="checkbox" onchange="processLed('1')">
                <span class="lever"></span>
                On
              </label>
            </div>
          </div>
        </div>
        <div class="col s12 m12 l4">
          <div class="card-panel hoverable light-blue darken-2 center-align">
            <span class="flow-text white-text">LED 2</span>
            <br>
            <div class="switch btn-large white">
              <label class="blue-text">
                Off
                <input id="inputLed2" type="checkbox" onchange="processLed('2')">
                <span class="lever"></span>
                On
              </label>
            </div>
          </div>
        </div>
        <div class="col s12 m12 l4">
          <div class="card-panel hoverable light-blue darken-2 center-align">
            <span class="flow-text white-text">LED 3</span>
            <br>
            <div class="switch btn-large white">
              <label class="blue-text">
                Off
                <input id="inputLed3" type="checkbox" onchange="processLed('3')">
                <span class="lever"></span>
                On
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="devices">
      <div class="row">
        <div id="registerDevice" class="col l12 s12">
          <div class="card hoverable">
            <form method="POST" id="formRegisterDevice">
              <div class="card-content"> <!--Contenido de la tarjeta-->
                <h3 class="center-align">Agregar dispositivo</h3>
                <div class="row">
                  <div class="input-field col l12 s12">
                    <i class="material-icons prefix">devices_other</i>
                    <input type="text" class="validate" name="alias" id="idAlias" pattern="[A-Za-z0-9- ]{2,50}" maxlength="50" required>
                    <label for="alias" data-error="Error, alias no válido" data-success="Alias válido">Ingrese el alias</label>
                  </div>
                  <div class="input-field col l12 s12">
                    <i class="material-icons prefix">confirmation_number</i>
                    <input type="text" class="validate" name="numSerie" id="idNumSerie" pattern="[0-9]{4,12}" minlength="4" maxlength="12" required>
                    <label for="numSerie" data-error="Error, no. serie mínimo de 4 dígitos" data-success="Num. serie válido">Ingrese el no. de serie</label>
                  </div>
                </div>
              </div>
              <div class="card-action center-align">
                <button class="btn-flat grey-text waves-effect" type="reset" style="margin: 10px"> Limpiar Campos</button>
                <button type="submit" class="btn waves-effect waves-light green darken-3 " style="margin: 10px">Registrar
                  <i class="material-icons right">phonelink</i>
                </button>
              </div>
            </form>
          </div>
        </div>
        <div id="devicesList"class="col l12 s12"></div>
      </div>
    </div>
  </div>
	<ul class="side-nav fixed grey darken-4 white-text" id="panel">
		<li>
			<div class="user-view">
				<div class="background">
          <img src="../images/background.png" class="responsive-img">
				</div>
				<a><img src="../images/avatar.png" style="width:40%;object-fit:cover; border-radius: 10%;"></a>
				<a><span class="white-text name"><font size=3>Usuario: <?php echo $_SESSION['firstName'] ?></font></span></a>
        <br>
			</div>
		</li>
		<li><a class="white-text " id="selectMain" href="#"><i class="material-icons white-text">home</i>Principal</a></li>
    <li><a class="white-text" id="selectDevices" href="#"><i class="material-icons white-text">devices</i>Dispositivos</a></li>
		<li><a class="white-text" href="../logOut.php"><i class="material-icons white-text">exit_to_app</i>Salir del sistema</a></li>
		<li><div class="divider"></div></li>
		<center>
			<p><i class="material-icons">settings</i></p><p>ADMINISTRADOR</p>
		</center>
 	</ul>
</main>
</body>
</html>
