/* globals Chart:false, feather:false */

(() => {
  'use strict'

  feather.replace({ 'aria-hidden': 'true' })

  
})()

function IR(url, variables, tipo){
	
	if (variables == null){
		
		window.location=url;
		
	}else{
		$.redirect(url, variables, tipo);
	}
	
}

function RECARGAR(){
	location.reload();
}

function NUEVA_PESTANHA(url, variables, tipo) {

	if (variables == null){
		window.open(url, '_blank'); 
		
	}else{
		$.redirect(url, variables, tipo, '_blank');
	}
}