<?php 
$menu_caps = true;

if($menu_caps){
	?>
	<div id="menu">
	<ul>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("indexProfessor.php");?>" title="P&aacute;gina Inicial" class="home_link"><?php echo strtoupper(strtr("Página Inicial" ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));?></a></li>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("avaliacoesProfessor.php");?>" title="Avalia&ccedil;&otilde;es"><?php echo strtoupper(strtr("Avaliações" ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));?></a></li>
	</ul>
	<?php include_once 'inc/info_usuario_inc.php';?>
				</div>
	<?php 
}else{
	?>
	<div id="menu">
	<ul>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("indexProfessor.php");?>" title="P&aacute;gina Inicial" class="home_link">P&aacute;gina Inicial</a></li>
	<li><a href="../Controller/pageController.php?pg=<?php echo codifica("avaliacoesProfessor.php");?>" title="Avalia&ccedil;&otilde;es">Avalia&ccedil;&otilde;es</a></li>
	</ul>
	<?php include_once 'inc/info_usuario_inc.php';?>
				</div>
	<?php 
}
?>