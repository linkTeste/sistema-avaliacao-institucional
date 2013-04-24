<?php

$email = "";
if(isset($_SESSION["s_aluno"])){
	$str = $_SESSION["s_aluno"];
	if($str instanceof Aluno){
		$aluno = $str;
	}else{
		$aluno = unserialize($_SESSION["s_aluno"]);
	}

	$usuario_logado = $aluno;
	$id = $usuario_logado->getRa();
	
	//pega dados do aluno e monta o formato do email da instituicao
	$nomeTemp = explode(" ", $usuario_logado->getNome());
	$primeiroNome = $nomeTemp[0];
	
	//remove os acentos pra montar o endereco de email corretamente
	$primeiroNome = strtolower($primeiroNome);
	
	$primeiroNome = ereg_replace("[áàâãª]","a",$primeiroNome);
	$primeiroNome = ereg_replace("[éèê]","e",$primeiroNome);
	$primeiroNome = ereg_replace("[óòôõº]","o",$primeiroNome);
	$primeiroNome = ereg_replace("[úùû]","u",$primeiroNome);
	$primeiroNome = str_replace("ç","c",$primeiroNome);
	
	$email = $primeiroNome.$usuario_logado->getRa()."@faculdadeunicampo.edu.br";
	
}else{
	$id = $usuario_logado->getId();
}

$nome = $usuario_logado->getNome();
if($email == ""){
	$email = $usuario_logado->getEmail();
}




?>
<div id="info_user">
	<div class="info_user_nao_clicavel">
		<a class="exibir_info_usuario_box link_comum_no-underline"
			href="javascript:void(0)"><span><?php echo utf8_encode($nome);?> </span>
		</a> <a class="exibir_info_usuario_box" href="javascript:void(0)"><img
			src="<?php echo pegaImagem($id); ?>"
			alt="<?php echo utf8_encode($nome)?>" width="36" height="36" /> </a>
		<a class="exibir_info_usuario_box" href="javascript:void(0)"><span
			class="arrow"></span> </a>
	</div>
	<div id="info_usuario_box">
		<div class="info_user_nao_clicavel">
			<span class="arrow_up"></span> <br />
			<div class="photo96">
				<img src="<?php echo pegaImagem($id); ?>" alt="Foto" />
			</div>
			<div style="min-width: 200px;margin-left: 110px;">
			<span style="font-weight: bold;"><?php echo utf8_encode($nome);?></span>
			<br />
			<span><?php echo utf8_encode($email);?></span>
			<br /> <br />
			</div>
<!-- 			<a href="#" class="link_comum">Perfil</a> - <a href="#" -->
<!-- 				class="link_comum">Configura&ccedil;&otilde;es</a> -->
			<hr />
		</div>
		<a href="../Controller/loginController.php?action=logout" title="Sair"
			class="botao_left botaoGoogleGrey">Sair</a>
	</div>

</div>
