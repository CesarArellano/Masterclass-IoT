<?php
  session_start();
  if(isset($_SESSION['idUser']))
    header('location: dashboard/index.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>
    <link rel="shortcut icon" href="images/icono.png">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.3.2/sweetalert2.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script><!--Biblioteca de funciones que simplifica el usor de JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.3.2/sweetalert2.js" charset="utf-8"></script>
    <script type="text/javascript" src="js/script.js"></script>
  </head>
  <body>
    <center>
      <img src="images/icono.png" width="150px" style="position:relative; top:20px;">
    </center>
    <div class="container">
      <h1 class="white-text center-align">Gestor IoT</h1>
      <br>
      <div class="row">
        <div class="card hoverable col s12">
          <h3 class="center-align">Inicio de sesión</h3>
          <form method="POST" id="formSignIn">
            <div class="card-content"> <!--Contenido de la tarjeta-->
              <div class="row">
                <div class="input-field col l12 s12">
                  <i class="material-icons prefix">email</i>
                  <input type="email" class="validate" name="email" pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,60}" maxlength="60" required>
                  <label for="email" data-error="Error, correo no válido" data-success="Correo válido">Ingrese su email</label>
                </div>
                <div class="input-field col l12 s12">
                  <i class="material-icons prefix">lock</i>
                  <input type="password" class="validate" name="password" minlength="8" maxlength="32" required>
                  <label for="password" data-error="Error, contraseña no válida" data-success="Contraseña válida">Ingrese su contraseña</label>
                </div>
              </div>
            </div>
            <div class="card-action center-align">
              <button class="btn-flat grey-text waves-effect" type="reset" style="margin: 10px"> Limpiar Campos</button>
              <button type="submit" class="btn waves-effect waves-light teal darken-1 " style="margin: 10px">Ingresar
                <i class="material-icons right">send</i>
              </button>
              <button class="btn waves-effect waves-light blue darken-1" style="margin: 10px" onclick="location.href='registro.php'">Registrarse
                <i class="material-icons right">assignment</i>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
  <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
  <script type="text/javascript">
    // connection option
    const options =
    {
      clean: true, // Cuando se tienen activas sesiones persistentes, esta instrucción limpia aquella carga del broker
      connectTimeout: 4000, // Tiempo de respuesta del dispositvo (4 segundos)
      keepalive: 60, // Señal de vida cada 60 segundos
      // Authentication information
      clientId: 'emqx_test', // Identificador de clientes
      //username: 'emqx_test',
      //password: 'emqx_test',
    }

    // Connect string, and specify the connection method by the protocol
    // ws Unencrypted WebSocket connection
    // wss Encrypted WebSocket connection
    // mqtt Unencrypted TCP connection
    // mqtts Encrypted TCP connection
    // wxs WeChat applet connection
    // alis Alipay applet connection
    const connectUrl = 'ws://cesararellano.ml:8093/mqtt'
    const client = mqtt.connect(connectUrl, options)

    client.on('connect', () =>
    {
      console.log('Se conectó con éxito:')

      client.subscribe('commands',{qos:0}, (error) =>
      {
        if(!error)
          console.log('Suscripción éxitosa')
        else
          console.log('Suscripción fallida')
      })
        //publish(topic,payload,options/callback)
      client.publish('fabrica','La temperatura de los ventiladores es : 30°C', (error) =>
      {
        console.log(error || 'Mensaje enviado')
      })
    })

    client.on('reconnect', (error) => {
        console.log('Reconectando:', error)
    })

    client.on('error', (error) => {
        console.log('Falló la conexión:', error)
    })

    client.on('message', (topic, message) => {
      console.log('Mensaje recibido bajo el tópico:', topic, 'mensaje ->', message.toString())
    })
  </script>
</html>
