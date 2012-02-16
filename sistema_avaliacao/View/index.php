<?php
///obs: os requires devem vir antes da sessao
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/Aluno.php';
require_once '../system/application/models/dao/Turma.php';
require_once '../system/application/models/dao/Questao.php';
require_once '../system/application/models/dao/Questionario.php';
require_once '../system/application/models/dao/Professor.php';
require_once '../system/application/models/dao/ProcessoAvaliacao.php';

require '../Utils/functions.php';

if (!isset($_SESSION)) {
	session_start();
}

//pegar dados ficticios de aluno

$ra = "0003.01.10"; //Ilson Gomes - Psicologia
// $ra = "0011.03.10"; //Dirnei de F�tima - Servi�o Social
// $ra = "0245.03.11"; //Camila Larissa - Servi�o Social
// $ra = "0012.02.10"; //ELIANE DOS SANTOS - Enfermagem
// $ra = "0025.04.10"; //THIAGO GABRIEL MARCELINO - Tecnologia em Gest�o de Cooperativas
// $ra = "0028.05.10"; //GREYCE DA COSTA VICENTE - TECNOLOGIA EM GEST�O COMERCIAL
// $ra = "0031.01.10"; //AMANDA INTROVINI DE CASTRO - Psicologia
// $ra = "0100.01.10"; //ELIANE GUADAGNIN RAIS - Psicologia

$aluno = new Aluno();
$aluno->get($ra);


echo "Aluno: ".$aluno->getNome();
echo "<br />";
echo "RA: ".$aluno->getRa();

//pega dados do processo de avaliacao
$processo = new ProcessoAvaliacao();
$processo->get(1);

//periodo letivo atual pra limitar a listagem de turmas
$periodo_atual = "2/2011";



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Avaliação Institucional - Página Inicial</title>
<link href="css/blueprint/ie.css" rel="stylesheet" type="text/css" />
<link href="css/blueprint/screen.css" rel="stylesheet" type="text/css" />
<link href="css/scrollbar.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Merienda+One|Amaranth' rel='stylesheet' type='text/css' />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.raty.js"></script>
</head>

<body>
<div id="wrapper" class="container">
	<div id="header"></div>
    <div id="content">
    	<div id="apresentacao">
        	<p>Caro aluno,</p>
            <p>Solicitamos sua participação para auxiliar na avaliação de desempenho dos docentes por entender-se que ela é indispensável para a melhoria contínua das atividades desenvolvidas em sala de aula.</p>
            <p>Para tanto, é necessário que sua opinião não se baseie em impressões precipitadas ou ditadas pela emoção. Procure avaliar o professor nos quesitos propostos, baseando sua resposta no que é mais constante no comportamento do professor.</p>
        </div>
        <div id="escala_conceitos">
        	<h3>Escala de Conceitos</h3>
            <div id="item_escala">
                <div id="texto_escala">Quando a questão <span>não for atendida</span></div>
                <div class="star_escala">
                	<ul>
                    	<li class="star-marked1" title="Questão não atendida"></li>
                        <li class="star-unmarked2" title="Questão atendida em até 25% das vezes"></li>
                        <li class="star-unmarked3" title="Questão atendida em até 50% das vezes"></li>
                        <li class="star-unmarked4" title="Questão atendida em até 75% das vezes"></li>
                        <li class="star-unmarked5" title="Questão atendida em até 100% das vezes"></li>
                    </ul>
                </div>
            </div>
            <div id="item_escala">
            	<div id="texto_escala">Quando a questão <span>for atendida em até 25% das vezes</span></div>
                <div class="star_escala">
                	<ul>
                    	<li class="star-marked1" title="Questão não atendida"></li>
                        <li class="star-marked2" title="Questão atendida em até 25% das vezes"></li>
                        <li class="star-unmarked3" title="Questão atendida em até 50% das vezes"></li>
                        <li class="star-unmarked4" title="Questão atendida em até 75% das vezes"></li>
                        <li class="star-unmarked5" title="Questão atendida em até 100% das vezes"></li>
                    </ul>
                </div>
            </div>
            <div id="item_escala">
            	<div id="texto_escala">Quando a questão <span>for atendida em até 50% das vezes</span></div>
                <div class="star_escala">
                	<ul>
                    	<li class="star-marked1" title="Questão não atendida"></li>
                        <li class="star-marked2" title="Questão atendida em até 25% das vezes"></li>
                        <li class="star-marked3" title="Questão atendida em até 50% das vezes"></li>
                        <li class="star-unmarked4" title="Questão atendida em até 75% das vezes"></li>
                        <li class="star-unmarked5" title="Questão atendida em até 100% das vezes"></li>
                    </ul>
                </div>
            </div>
            <div id="item_escala">
            	<div id="texto_escala">Quando a questão <span>for atendida em até 75% das vezes</span></div>
                <div class="star_escala">
                	<ul>
                    	<li class="star-marked1" title="Questão não atendida"></li>
                        <li class="star-marked2" title="Questão atendida em até 25% das vezes"></li>
                        <li class="star-marked3" title="Questão atendida em até 50% das vezes"></li>
                        <li class="star-marked4" title="Questão atendida em até 75% das vezes"></li>
                        <li class="star-unmarked5" title="Questão atendida em até 100% das vezes"></li>
                    </ul>
                </div>
            </div>
            <div id="item_escala">
            	<div id="texto_escala">Quando a questão <span>for atendida em até 100% das vezes</span></div>
                <div class="star_escala">
                	<ul>
                    	<li class="star-marked1" title="Questão não atendida"></li>
                        <li class="star-marked2" title="Questão atendida em até 25% das vezes"></li>
                        <li class="star-marked3" title="Questão atendida em até 50% das vezes"></li>
                        <li class="star-marked4" title="Questão atendida em até 75% das vezes"></li>
                        <li class="star-marked5" title="Questão atendida em até 100% das vezes"></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <br />
        
        <!--<a href="avaliacoes.php" class="btn-comecar-avaliacao" title="Começar Avaliação"></a>-->
        <a href="avaliacoes.php" class="botao botaoGoogleBlue">Começar Avaliação</a>
        <div class="clear"></div>
        <br />
    </div>
    <div id="footer">
        <hr />
    	<p>&copy;2011 - Faculdade Unicampo - Todos os direitos reservados</p>
    </div>
</div>
</body>
<?php 

$_SESSION["aluno"] = serialize($aluno);
$_SESSION["processo"] = serialize($processo);
$_SESSION["periodo"] = "2/2011";

?>
</html>