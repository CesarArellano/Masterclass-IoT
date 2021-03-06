$(document).ready(function(e)
{
  $(findDevices());
  // Busca usuarios por diferentes filtros
  $(".button-collapse").sideNav(); // Habilita barra lateral del adminal del adminse").sideNav(); // Habilita barra lateral del admin
  $("#main").show();
  $("#devices").hide();
  $("#selectMain").click(function()
  {
    $("#main").slideDown(600);
    $("#devices").hide();
  });
  $("#selectDevices").click(function()
  {
    $("#main").hide();
    $("#devices").slideDown(600);
  });
  //Login usuarios
  $("#formRegisterDevice").on('submit', function(e)
  {
    let titulo;
    e.preventDefault();
    $.ajax(
    {
      type: 'POST',
      url: 'registerDevice.php', //URL a donde se redirecciona
      data: new FormData(this), // Inicializa el objeto con la información (del submit)del formulario.
      dataType : 'json',
      processData: false,
      contentType: false,
      cache: false,
      success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
      {
        if(data.alerta == 'error') // título de acuerdo al tipo de alerta
          titulo = 'Ups...';
        else
          titulo = 'Bien hecho!';

        swal(
        {
          type: data.alerta,
          title: titulo,
          html: data.mensaje,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Ok!'
        });
        if(data.alerta == 'success')
        {
          $(findDevices());
          $('#formRegisterDevice')[0].reset();
        }

      },
      error: function(xhr, status) // Si hubo error, despliega mensaje.
      {
        swal( // Se inicializa sweetalert2
        {
          title: "Ups...",
          type: "error",
          html: "Error del servidor, intente de nuevo",
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Ok!'
        });
      }
    });
  });
});
function findDevices()
{
    $.ajax(
    {
        url: 'findDevices.php',
        dataType: 'html',
        success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
        {
          $("#devicesList").html(data);
        },
        error : function(xhr, status) // Si hubo error, despliega mensaje.
        {
          swal( // Se inicializa sweetalert2
          {
            title: "Ups...",
            type: "error",
            html: "Error del servidor, intente de nuevo",
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ok!'
          });
        }
    });
}
function deleteDevice(idDevice)
{
  let titulo,param = "idDevice="+idDevice;
  $.ajax(
	{
		type: 'POST',
		url: 'deleteDevice.php',
		data: param, // Inicializa el objeto con la información de la forma.
		dataType : 'json', // Indicamos formato de respuesta
		success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
		{
			if(data.alerta == "error")
			{
				titulo = "Ups..."
			}
			else
			{
				titulo = "Bien hecho!"
			}
			swal( // Se inicializa sweetalert2
			{

				title: titulo,
				type: data.alerta,
				html: data.mensaje,
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Ok!'
			}).then(function ()
			{
				if(data.alerta == "success")
					$(findDevices());
			});
			$(document).click(function()
			{
				if(data.alerta == "success")
					$(findDevices());
			});
			$(document).keyup(function(e)
			{
				if (e.which == 27)
				{
					if(data.alerta == "success")
						$(findDevices());
				}
			});
		},
		error : function(xhr, status,error) // Si hubo error, despliega mensaje.
		{
			swal( // Se inicializa sweetalert2
			{
				title: "Ups...",
				type: "error",
				html: "Error del servidor, intente de nuevo"+error,
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Ok!'
			});
		}
	});
}

function updateValues(temp1, temp2, volts)
{
  $('#displayTemp1').html(temp1);
  $('#displayTemp2').html(temp2);
  $('#displayVolt').html(volts);
}
function processMsg(topic,message)
{
  if(topic == "values")
  {
    let msg = message.toString();
    let sp = msg.split(",");
    let temp1 = sp[0];
    let temp2 = sp[1];
    let volts = sp[2];
    updateValues(temp1,temp2,volts);
  }
}
function processLed(numLed)
{
  let idLed = "#inputLed"+numLed;
  let topicoLed = "led"+numLed;
  if($(idLed).is(":checked"))
  {
    console.log("Encendido");
    client.publish(topicoLed,'On', (error) =>
    {
      console.log(error || 'Mensaje enviado')
    })
  }
  else
  {
    console.log("Apagado");
    client.publish(topicoLed,'Off', (error) =>
    {
      console.log(error || 'Mensaje enviado')
    })

  }
}
// connection option
const options =
{
  clean: true, // Cuando se tienen activas sesiones persistentes, esta instrucción limpia aquella carga del broker
  connectTimeout: 4000, // Tiempo de respuesta del dispositvo (4 segundos)
  keepalive: 60, // Señal de vida cada 60 segundos
  // Authentication information
  clientId: 'iotmc', // Identificador de clientes
  username: 'webClient',
  password: 'Breaktime2018',
}

// Connect string, and specify the connection method by the protocol
// ws Unencrypted WebSocket connection
// wss Encrypted WebSocket connection
// mqtt Unencrypted TCP connection
// mqtts Encrypted TCP connection
// wxs WeChat applet connection
// alis Alipay applet connection
const connectUrl = 'wss://cesararellano.ml:8094/mqtt'
const client = mqtt.connect(connectUrl, options)

client.on('connect', () =>
{
  console.log('Se conectó con éxito:')

  client.subscribe('values',{qos:0}, (error) =>
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
  processMsg(topic,message);
})
