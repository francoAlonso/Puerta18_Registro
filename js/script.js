$(document).ready(function() {

  var statusInfo = document.getElementById("mensaje");
  var nombreIngresado = document.getElementById("input");
  var statusIcon = document.getElementById("statusIcon");

  $(statusIcon).hide();
 
  $( nombreIngresado ).autocomplete({

    minLength: 4,//caracteres minimos para buscar
    delay: 500,//cuanto tarda en empezar a buscar dps de tipear
    source: "http://localhost/ejercicio/prueba/php/index.php/buscarUsuarios?term=" + nombreIngresado.value,
    position: {
        my: "left+0 top+8",
    },
    search: function(){
      selectStatusIcon("loading");
    },
    focus: function( event, ui ) { //cuando paso por las respuestas
      $( nombreIngresado ).val( ui.item.Nombre_Completo );
      return false;
    },
    response: function(event, ui) {
      // ui.content is the array that's about to be sent to the response callback.
      if (!ui.content.length) {
        informStatus("No existe", "D91967");
      }
      $(statusIcon).hide();
    },
    select: function( event, ui ) { //cuando selecciono la opcion
      var nombre = ui.item.Nombre_Completo;
      selectStatusIcon("loading");
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
      estado = "<a class='entrar'>Entrar</a>";
    }else{
      estado = "<a class='salir'>Salir</a>";
    }
    return $( "<li>" )
      .append("<div class='theDiv'>" + item.Nombre_Completo + estado + "</div>" )
      .appendTo( ul );
  };

  var timeOut; //variable global para selectStatusIcon() e informStatus()

  function selectStatusIcon(estado){
    clearTimeout(timeOut);
    switch(estado) {
      case "gambini":
        statusIcon.src = ("img/" + estado + ".png"); 
        $(statusIcon).show();
        break;

      case "loading":
        statusIcon.src = ("img/" + estado + ".gif"); 
        $(statusIcon).show();
        break;

      default: 
        statusIcon.src = ("img/" + estado + ".gif"); 
        $(statusIcon).show();
        timeOut = setInterval(function(){
          $(statusIcon).hide();
        }, 500);
        break;
    }
  }//selectStatusIcon

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