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
