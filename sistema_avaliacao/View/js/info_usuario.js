$(function(){
	var mostrar = true;
	$('#info_usuario_box').css('display', 'none');
	$('.exibir_info_usuario_box', '#info_user').click(function() {
		//alert("oi");
		if(mostrar == true){
			mostrar = false;
			$('#info_usuario_box').fadeToggle('fast').siblings('#info_usuario_box:visible').fadeToggle('fast');
		}
		
		
//		//$('#info_usuario_box').slideToggle('slow').siblings('#info_usuario_box:visible').slideToggle('fast');
//		$('#info_usuario_box').fadeToggle('fast').siblings('#info_usuario_box:visible').fadeToggle('fast');
				
	});
	
		
	//esconde a div qdo clicar fora dela
	$(document).click(function () { 
		mostrar = true;
		$('.info_user_nao_clicavel').click(function () { return false; });
		//$('#info_user').click(function () { return false; });
		$('#info_usuario_box').hide();
	});
	
});
