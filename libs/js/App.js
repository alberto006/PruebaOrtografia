//Scripts Personalizados

$(document).ready(function(){

	//Log
	var form = "log";
    $.post('backend/AppController.php',{
      form:form
    }).done(function(e){
      $("#notificaciones").html(e);
    });

    //Contacto Form
	$("#FormContacto").on("submit",function(e){
		e.preventDefault();
		$("#btnEnviar").html("<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> Enviando...");
		$("#btnEnviar").prop("disabled",true);
		$("#spinner1").css("display","inline-block");
		
		$.ajax({
			url:'backend/AppController.php',
			method:'POST',
			data: new FormData(this),
			contentType:false,
			cache:false,
			processData:false,
			success: function(data){
				$("#notificaciones").html(data);
			}

		});
		
	});

	//Disabled autocomplete
	$("input[type=text]").prop("autocomplete","off");

	//---------------- WhatsHelp.io widget ---------------//
    
        (function () {
            var options = {
                whatsapp: "+50494308801", // WhatsApp number
                call_to_action: "Contactanos", // Call to action
                position: "right", // Position may be 'right' or 'left'
                text: "Cotizacion  de servicios",
            };
            var proto = document.location.protocol, host = "whatshelp.io", url = proto + "//static." + host;
            var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
            s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
            var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
        })();
    
    //--------------- WhatsHelp.io widget ---------------//


});