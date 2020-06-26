$(document).ready(function()
{
  $('select').material_select();
  var picker = $('.datepicker').pickadate( // Inicializa el calendario datepicker de materialize
  {
    monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
      weekdaysLetter: [ 'D', 'L', 'M', 'M', 'J', 'V', 'S' ],
    selectMonths: true, // Permite seleccionar meses con un menú desplegable.
    selectYears: 100, // Permite seleccionar años con un menú desplegable.
    today: false,
    clear: 'Limpiar',
    close: 'Ok',
    labelMonthNext: 'Siguiente mes',
    labelMonthPrev: 'Mes anterior',
    labelMonthSelect: 'Selecciona un mes',
    labelYearSelect: 'Selecciona un año',
    min: new Date(1900,0,1), // Fecha mínima de nacimiento (formato del constructor: "año,mes[Rango de meses: 0-11 ],día").
    max: new Date(2005,11,31), // Fecha máxima de nacimiento.
    format: 'yyyy-mm-dd',
    firstDay: true, // Empieza en día Lunes y no en Domingo.
    editable: true
  });
  $('.datepicker').click(function()
  {
    picker.pickadate('open');
  });
  $('.datepicker').on('mousedown',function(e)
  {
    e.preventDefault(); // Arreglo falla datepicker materialize
  });
  //registro usuarios
  $("#formSignUp").on('submit', function(e)
  {
    let titulo;
    e.preventDefault();
    $.ajax(
    {
      type: 'POST',
      url: 'registrarUsuarios.php', //URL a donde se redirecciona
      data: new FormData(this), // Inicializa el objeto con la información (del submit)del formulario.
      dataType : 'json',
      processData: false,
      contentType: false,
      cache: false,
      success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
      {
        if(data.alerta == "error") // título de acuerdo al tipo de alerta
          titulo = "Ups...";
        else
          titulo = "Bien hecho!";

        swal(
        {
          type: data.alerta,
          title: titulo,
          html: data.mensaje,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Ok!'
        }).then(function ()
        {
          if(data.pagina == 'index')
            location.href = 'index.php';
        });
        $(document).click(function()
        {
          if(data.pagina == 'index')
            location.href = 'index.php';
        });
        $(document).keyup(function(e)
        {
          if (e.which == 27)
          {
            if(data.pagina == 'index')
              location.href = 'index.php';
          }
        });
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
  //Login usuarios
  $("#formSignIn").on('submit', function(e)
  {
    let titulo;
    e.preventDefault();
    $.ajax(
    {
      type: 'POST',
      url: 'validarSignIn.php', //URL a donde se redirecciona
      data: new FormData(this), // Inicializa el objeto con la información (del submit)del formulario.
      dataType : 'json',
      processData: false,
      contentType: false,
      cache: false,
      success: function(data) // Después de enviar los datos se muestra la respuesta del servidor.
      {
        if(data.alerta == "error") // título de acuerdo al tipo de alerta
          titulo = "Ups...";
        else
          titulo = "Bien hecho!";

        swal(
        {
          type: data.alerta,
          title: titulo,
          html: data.mensaje,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Ok!'
        }).then(function ()
        {
          if(data.pagina == 'dashboard')
            location.href = 'dashboard/index.php';
        });
        $(document).click(function()
        {
          if(data.pagina == 'dashboard')
            location.href = 'dashboard/index.php';
        });
        $(document).keyup(function(e)
        {
          if (e.which == 27)
          {
            if(data.pagina == 'dashboard')
              location.href = 'dashboard/index.php';
          }
        });
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
