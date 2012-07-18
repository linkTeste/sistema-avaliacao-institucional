<?php 

if(isset($_SESSION["s_aluno"])){
	$str = $_SESSION["s_aluno"];
	if($str instanceof Aluno){
		$aluno = $str;		
	}else{
		$aluno = unserialize($_SESSION["s_aluno"]);		
	}
	
	$usuario_logado = $aluno;
	$id = $usuario_logado->getRa();	
}else{
	$id = $usuario_logado->getId();
}

$nome = $usuario_logado->getNome();
$email = $usuario_logado->getEmail();

?><div id="info_user">
	<div class="info_user_nao_clicavel">
		<a class="exibir_info_usuario_box link_comum_no-underline"
			href="javascript:void(0)"><span><?php echo utf8_encode($nome);?>
		</span>
		</a> <a class="exibir_info_usuario_box" href="javascript:void(0)"><img
			src="<?php echo pegaImagem($id); ?>"
			alt="<?php echo utf8_encode($nome)?>" width="36"
			height="36" />
		</a> <a class="exibir_info_usuario_box" href="javascript:void(0)"><span
			class="arrow"></span>
		</a>
	</div>
	<div id="info_usuario_box">
		<div class="info_user_nao_clicavel">
			<span class="arrow_up"></span> <br />
			<div class="photo96">
				<img src="<?php echo pegaImagem($id); ?>"
					alt="Foto" />
			</div>
			<span><?php echo utf8_encode($nome);?>
			</span><br /> <span><?php echo utf8_encode($email);?>
			</span><br /> <br />
		</div>
		<a href="#" class="link_comum">Perfil</a> - <a href="#"
			class="link_comum">Configura&ccedil;&otilde;es</a>
		<hr />
		<a href="../Controller/loginController.php?action=logout" title="Sair"
			class="botao_left botaoGoogleGrey">Sair</a>
	</div>

</div>
