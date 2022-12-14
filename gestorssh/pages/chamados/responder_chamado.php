<?php
require_once('../../pages/system/seguranca.php');
require_once('../../pages/system/config.php');
require_once('../../pages/system/funcoes.php');
require_once('../../pages/system/classe.ssh.php');
require_once("../../pages/system/funcoes.system.php");

protegePagina("user");

if(isset($_POST['chamado'])){

#Posts
$chamado=$_POST['chamado'];
$msg=$_POST['msg'];
$diretorio=$_POST['diretorio'];

$buscachamado = "SELECT * FROM chamados where id='".$chamado."' and usuario_id='".$_SESSION['usuarioID']."'";
$buscachamado = $conn->prepare($buscachamado);
$buscachamado->execute();

if($buscachamado->rowCount()==0){
	        echo '<script type="text/javascript">';
			echo 	'alert("Chamado não encontrado!");';
			echo	'window.location="'.$diretorio.'";';
			echo '</script>';
			exit;
}
$chama=$buscachamado->fetch();
if($chama['status']=='encerrado'){
	echo myalertuser('success', 'Ja resolvido !', $diretorio);
	exit;
}
$verificausuario = "SELECT * FROM usuario where id_usuario= '".$chama['usuario_id']."'";
$verificausuario = $conn->prepare($verificausuario);
$verificausuario->execute();
if($buscachamado->rowCount()==0){
	echo myalertuser('warning', 'Usuario nao encontrado !', $diretorio);
	exit;
}
//Sucesso
$updatechamado = "UPDATE chamados set status='resposta', mensagem='".$msg."', data='".date('Y-m-d H:i:s')."' where id= '".$chama['id']."'";
$updatechamado = $conn->prepare($updatechamado);
$updatechamado->execute();

//Insere notificacao ao ADM
$msg="O Usuário <small><b>".$usuario['nome']."</b></small> Acabou de Responder o Chamado <b>N°".$chama['id']."</b> de Suporte!";
$notins = "INSERT INTO notificacoes (usuario_id,data,tipo,linkfatura,mensagem,admin) values ('0','".date('Y-m-d H:i:s')."','chamados','Admin','".$msg."','sim')";
$notins = $conn->prepare($notins);
$notins->execute();

echo myalertuser('success', 'Respondido com sucesso !', $diretorio);
}