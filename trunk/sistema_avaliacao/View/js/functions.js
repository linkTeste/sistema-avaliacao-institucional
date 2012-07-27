function getPageSize() {

	var xScroll, yScroll;

	if (window.innerHeight && window.scrollMaxY) {
		xScroll = document.body.scrollWidth;
		yScroll = window.innerHeight + window.scrollMaxY;
	} else if (document.body.scrollHeight > document.body.offsetHeight) { // all
		// but
		// Explorer
		// Mac
		xScroll = document.body.scrollWidth;
		yScroll = document.body.scrollHeight;
	} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla
		// and Safari
		xScroll = document.body.offsetWidth;
		yScroll = document.body.offsetHeight;
	}

	var windowWidth, windowHeight;
	if (self.innerHeight) { // all except Explorer
		windowWidth = self.innerWidth;
		windowHeight = self.innerHeight;
	} else if (document.documentElement
			&& document.documentElement.clientHeight) { // Explorer 6 Strict
		// Mode
		windowWidth = document.documentElement.clientWidth;
		windowHeight = document.documentElement.clientHeight;
	} else if (document.body) { // other Explorers
		windowWidth = document.body.clientWidth;
		windowHeight = document.body.clientHeight;
	}

	// for small pages with total height less then height of the viewport
	if (yScroll < windowHeight) {
		pageHeight = windowHeight;
	} else {
		pageHeight = yScroll;
	}

	// for small pages with total width less then width of the viewport
	if (xScroll < windowWidth) {
		pageWidth = windowWidth;
	} else {
		pageWidth = xScroll;
	}

	// correção pro site
	pageHeight += 100;

	arrayPageSize = new Array(pageWidth, pageHeight, windowWidth, windowHeight);
	return arrayPageSize;
}

function getElementSize(element) {
	var el = document.getElementById(element);
	width = el.offsetWidth;
	height = el.offsetHeight;
	arrayElementSize = new Array(width, height);
	return arrayElementSize;
}
function ativaBlackout() {
	pageSize = getPageSize();
	// alert("ola");
	// alert(pageSize[1]);

	// usar ass linhas abaixo somente qdo o tamanho da pagina for em
	// pixels(não-relativo)
	// document.getElementById("overlay").style.height = pageSize[1] + "px";
	// document.getElementById("overlay").style.width = pageSize[0] + "px";

	document.getElementById("overlay").style.height = 100 + "%";
	document.getElementById("overlay").style.width = 100 + "%";

//	document.getElementById("overlay").style.display = 'block';
	$("#overlay").fadeIn(100);
}

function centralizaElemento(elemento) {
	el = document.getElementById(elemento);

	pageSize = getPageSize();
	// descobre o tamanho do elemento
	boxSize = getElementSize(elemento);

	bodyW = pageSize[0];// largura da pagina
	bodyH = pageSize[1];// altura da pagina
	boxW = boxSize[0];// largura box
	boxH = boxSize[1];// altura box

	posX = (bodyH - boxH) / 2;
	posY = (bodyW - boxW) / 2;

	el.style.top = 200 + "px";
	el.style.left = posY + "px";
}
function verificaSize() {
	var ctrl;
	var tecla;
	document.onkeyup = function(e) {
		if (e.which == 17) {// Pressionou CTRL
			ctrl = true;
		}
		if (e.which == 107) {// Pressionou -
			tecla = 107;
		}
		if (e.which == 109) {// Pressionou =
			tecla = 109;
		}

		if (ctrl && tecla == 107) {
			// alert("CTRL +");
			centralizaElemento("box");
			event.keyCode = 0;
			event.returnValue = false;
		}
		if (ctrl && tecla == 109) {
			// alert("CTRL -");
			centralizaElemento("box");
			event.keyCode = 0;
			event.returnValue = false;
		}

	};

}
function ativaPopup() {
	
	centralizaElemento("box");

	document.getElementById("box").style.display = 'none';
	
	//document.getElementById("box").style.display = 'block';
	//$("#box").slideDown("slow");
	$("#box").show("puff", {}, 500);
	
	// esconde barra de rolagem
	document.body.style.overflow = "hidden";
}

function removeBlackout() {
	//document.getElementById("overlay").style.display = 'none';
	//$("#overlay").hide();
	//$("#overlay").slideUp();
	
	//ativa o scroll somente apos finalizar a animacao
	$('#overlay').fadeOut(100, function() {
	    // habilita barra de rolagem novamente
		document.body.style.overflow = "scroll";
	  });	
}

function removePopup() {
	//document.getElementById("box").style.display = 'none';
	//$("#box").hide("slow");
	//$("#box").slideUp("slow");
	
	//efeitos JqueryUI
	//$("#box").hide("slide", { direction: "up" }, 500);
	//$("#box").hide("explode", { pieces: 16 }, 1200);
	
	if(document.getElementById("box").style.display != 'none'){
		$("#box").hide("puff", {}, 500);
	}
	
	
	removeBlackout();

}

function ativaContagem() {
	var time = 10;
	setInterval(function() {
		if (time != 0) {
			time--;
		}

		if (time == 0) {
			removePopup();
			// $("#popup").hide("slow");
		}
	}, 1000);
}

//desabilita menu de opcoes ao clicar no botao direito
function desabilitaMenu(e)
{
if (window.Event)
{
if (e.which == 2 || e.which == 3)
return false;
}
else
{
event.cancelBubble = true;
event.returnValue = false;
return false;
}
}

//desabilita botao direito
function desabilitaBotaoDireito(e)
{
if (window.Event)
{
if (e.which == 2 || e.which == 3)
return false;
}
else
if (event.button == 2 || event.button == 3)
{
event.cancelBubble = true;
event.returnValue = false;
return false;
}
}


function disableButtonRight(){
	//desabilita botao direito do mouse
	if ( window.Event )
	document.captureEvents(Event.MOUSEUP);
	if ( document.layers )
	document.captureEvents(Event.MOUSEDOWN);

	document.oncontextmenu = desabilitaMenu;
	document.onmousedown = desabilitaBotaoDireito;
	document.onmouseup = desabilitaBotaoDireito;
}


