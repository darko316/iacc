incremento =0;
var ary = []; // Global

function identidad(){	
	var cuenta 		= form1.cuenta.value;
	var clave 		= hex_md5(form1.clave.value);	
	var url			= "validarUsuario.php?cuenta="+cuenta+"&clave="+clave;		
	setTimeout(function() {redireccionar(url);},800);	
	
	}



function redireccionar(url){window.location = url;}

function logOut(){window.location = "/remuneracion";}




	
