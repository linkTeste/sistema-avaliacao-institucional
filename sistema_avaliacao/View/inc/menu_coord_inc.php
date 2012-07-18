<?php 
$menu_caps = true;

if($menu_caps){
	?>
	<div id="menu">
	<ul>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("indexCoordenador.php");?>" title="P&aacute;gina Inicial" class="home_link"><?php echo strtoupper(strtr("Página Inicial" ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));?></a></li>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("avaliacoesCoordenador.php");?>" title="Avalia&ccedil;&otilde;es"><?php echo strtoupper(strtr("Avaliações" ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));?></a></li>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("alunospendentes.php");?>" title="Alunos Pendentes"><?php echo strtoupper(strtr("Alunos Pendentes" ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));?></a></li>
	<li><a href="#" title="Relat&oacute;rios"><?php echo strtoupper(strtr("Relatórios" ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));?></a></li>
	</ul>
	<?php include_once 'inc/info_usuario_inc.php';?>
				</div>
	<?php 
}else{
	?>
	<div id="menu">
	<ul>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("indexCoordenador.php");?>" title="P&aacute;gina Inicial" class="home_link">P&aacute;gina Inicial</a></li>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("avaliacoesCoordenador.php");?>" title="Avalia&ccedil;&otilde;es">Avalia&ccedil;&otilde;es</a></li>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("alunospendentes.php");?>" title="Alunos Pendentes">Alunos Pendentes</a></li>
	<li><a href="#" title="Relat&oacute;rios">Relat&oacute;rios</a></li>
	</ul>
	<?php include_once 'inc/info_usuario_inc.php';?>
				</div>
	<?php 
}
?>