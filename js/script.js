$(document).ready(function() {

  var statusInfo = document.getElementById("mensaje");
  var nombreIngresado = document.getElementById("input");
  var statusIcon = document.getElementById("statusIcon");

  //$(statusIcon).hide();
 
  $( nombreIngresado ).autocomplete({
    minLength: 4,//caracteres minimos para buscar
    delay: 500,//cuanto tarda en empezar a buscar dps de tipear
    search: function(){
      selectStatusIcon("loading");
    },
    source: "http://localhost/ejercicio/prueba/php/index.php/buscarUsuarios?term=" + 
      nombreIngresado.value,
    focus: function( event, ui ) { //cuando paso por las respuestas
      $( nombreIngresado ).val( ui.item.Nombre_Completo );
      return false;
    },
    response: function(event, ui) {
      // ui.content is the array that's about to be sent to the response callback.
      if (ui.content.length === 0) {
        informStatus("no existe", "D91967");
      }
        $(statusIcon).hide();
    },
    select: function( event, ui ) { //cuando selecciono la opcion
      var nombre = ui.item.Nombre_Completo;
      $.ajax({
        type: "GET",  
        url: "http://localhost/ejercicio/prueba/php/index.php/login/" + nombre,
        success: function(){
          informStatus(nombre, "D6F1FF");  
          selectStatusIcon("checkIn");
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {  
          informStatus("Conexion de internet: " + textStatus + ", " + errorThrown, "D91967");
          selectStatusIcon("cross"); 
        }       
      });
    }
  }).autocomplete( "instance" )._renderItem = function( ul, item ) {
    var estado = "";
    if(item.Conectado == 0){
      estado = "<a class='entrar'>entrar</a>";
    }else{
      estado = "<a class='salir'>salir</a>";
    }
    return $( "<li>" )
      .append("<div class='theDiv'>" + item.Nombre_Completo + estado + "</div>" )
      .appendTo( ul );
  };


  function selectStatusIcon(estado){
    switch(estado) {
      case "checkIn":
        statusIcon.src = ("img/" + estado + ".png"); 
        $(statusIcon).show(500);
        $(statusIcon).hide(500);
        break;
      case "cross":
        statusIcon.src = ("img/" + estado + ".png"); 
        $(statusIcon).show();
        break;
      case "loading":
        statusIcon.src = ("img/" + estado + ".gif"); 
        $(statusIcon).show();
        break;
    }
  }//selectStatusIcon

  var timeOut;
  function informStatus(estado, color){
    clearTimeout(timeOut);
    statusInfo.innerHTML = estado;
    statusInfo.style.color = "#" + color;
    timeOut = setTimeout(function() {
      statusInfo.innerHTML = "Empezara a buscar cuando ingreses 4 caracteres";
      statusInfo.style.color = "#F4F7F7";
    }, 4000);
  }

});