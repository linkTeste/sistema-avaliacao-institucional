<?php
$menu_caps = true;

$page = $_SESSION["s_active_page"];

if($page == "index.php"){
	$index_active = "class='home_link active'";	
	$avaliacoes_active = "";
	$relatorios_active = "";
}
if($page == "avaliacoes.php"){
	$index_active = "class='home_link'";
	$avaliacoes_active = "class='active'";
	$relatorios_active = "";
}
if($page == "avaliacao.php"){
	$index_active = "class='home_link'";
	$avaliacoes_active = "class='active'";
	$relatorios_active = "";
}
if($page == "relatoriosAluno.php"){
	$index_active = "class='home_link'";
	$avaliacoes_active = "";
	$relatorios_active = "class='active'";
}



if($menu_caps){
	?>
<div id="menu">
	<ul>
		<li><a
			href="../Controller/pageController.php?pg=<?php echo codifica("index.php");?>"
			title="P&aacute;gina Inicial" <?php echo $index_active;?>><?php echo strtoupper(strtr("Página Inicial" ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));?>
		</a></li>
		<li><a
			href="../Controller/pageController.php?pg=<?php echo codifica("avaliacoes.php");?>"
			title="Avalia&ccedil;&otilde;es" <?php echo $avaliacoes_active;?>><?php echo strtoupper(strtr("Avaliações" ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));?>
		</a></li>
		<li><a
			href="../Controller/pageController.php?pg=<?php echo codifica("help.php");?>" target="_blank1"
			title="Ajuda"><?php echo strtoupper(strtr("Ajuda" ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));?>
		</a></li>
		<!-- <li><a href="../Controller/pageController.php?pg=<?php //echo codifica("relatoriosAluno.php");?>" title="Relat&oacute;rios" <?php //echo $relatorios_active;?>><?php //echo strtoupper(strtr("Relatórios" ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));?>
		</a></li> -->
	</ul>
	
	
	
	
	<?php include_once 'inc/info_usuario_inc.php';?>
				</div>

				<?php
}else{
	?>
<div id="menu">
	<ul>
		<li><a
			href="../Controller/pageController.php?pg=<?php echo codifica("index.php");?>"
			title="P&aacute;gina Inicial" <?php echo $index_active;?>>P&aacute;gina Inicial</a>
		</li>
		<li><a
			href="../Controller/pageController.php?pg=<?php echo codifica("avaliacoes.php");?>"
			title="Avalia&ccedil;&otilde;es" <?php echo $avaliacoes_active;?>>Avalia&ccedil;&otilde;es</a></li>
<!-- 		<li><a href="#" title="Relat&oacute;rios">Relat&oacute;rios</a></li> -->
	</ul>
	
	
	
	
	<?php include_once 'inc/info_usuario_inc.php';?>
				</div>

	<?php 
}
?>