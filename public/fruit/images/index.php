<?php
ob_start(); // Ativar o buffering de saída


$statusOnline = '0';

// Recupera o valor da meta da URL
if (isset($_GET['meta'])) {
    $meta = $_GET['meta'];
} else {
    // Se a meta não estiver presente na URL, defina um valor padrão ou trate de outra forma
}


session_start(); // Iniciar a sessão (caso ainda não tenha sido iniciada)

// Verificar se o usuário está autenticado (exemplo)
if (isset($_SESSION["id_usuario"])) {
    $idUsuario = $_SESSION["id_usuario"];

    
    // Conectar ao banco de dados (substitua pelos seus dados)
    $host = "158.69.117.175";
    $usuario = "fruitcoincom_root";
    $senhaDB = "Ru@nhenrique123";
    $banco = "fruitcoincom_users";

    $conexao = new mysqli($host, $usuario, $senhaDB, $banco);
    if ($conexao->connect_error) {
        die("Erro na conexão: " . $conexao->connect_error);
    }

    // Consultar o banco de dados para obter o valor de status_online
    $sql = "SELECT status_online FROM users WHERE id = $idUsuario";
    $resultado = $conexao->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $statusOnline = $row["status_online"];
    } else {
      $statusOnline = '0';
    }

    // Consultar o banco de dados para obter o valor de status_online
    $sql = "SELECT tentativas FROM users WHERE id = $idUsuario";
    $resultado = $conexao->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $tentativas = $row["tentativas"];
    } 
   

    // Consultar o banco de dados para obter o valor de status_online
    $sql = "SELECT name FROM users WHERE id = $idUsuario";
    $resultado = $conexao->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $name_user = $row["name"];
    }
    // Consultar o banco de dados para obter o valor de tokens
    $sql = "SELECT tokens FROM users WHERE id = $idUsuario";
    $resultado = $conexao->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $myBalance = $row["tokens"];
    }
    
   

     
  }
  else {
    // Redirecionar para a página de login caso o ID do usuário não seja encontrado
  //  header("Location: /./highticket-app/fruit-cash/Fruit-Money-Login.php");
    exit(); // Certificar-se de que o script pare de ser executado após o redirecionamento
}
  

    $conexao->close();

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="description" content="Fruit Money - Earn To Play">
<meta name="author" content="dron">
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, initial-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">

<link rel="stylesheet" href="images/index.css">

<title>
        Fruit Money 🍍 |
        Apostar    </title>

</head>

<body>

<script>


</script>

<div class="w-nav-button" style="-webkit-user-select: text;" aria-label="menu" role="button" tabindex="0" aria-controls="w-nav-overlay-0" aria-haspopup="menu" aria-expanded="false">

</div>
<style>
	.nav-menu{
		background-color: black;
		text-align: center;
		position: center;
		display: inline-block;
		color: white;
		font-size: 13pt;
		width: 100%;
		font-family: 'Poppins';
		padding: 20px;
	}
	.nav-menu a{
		padding: 20px;
		color: white;
		text-decoration: none;
	}



/* Para telas com largura igual ou menor que 320px */
@media (max-width: 320px) {
    .centered-div {
        margin-top: 10%;
        margin-left: 0%;
        position: absolute;
        z-index: 99999999;

    }
    #extra .layer{
	position: absolute;
	left: 0;
	top: 0;
	width: 320px;
	height: 884px;
	z-index: 10;
    }   
    
}
   

/* Para telas com largura igual ou menor que 375px */
@media (max-width: 375px) {
    .centered-div {
        position: absolute;
        z-index: 99999999;
        top: 50%; /* Define o topo no meio da tela */
        left: 50%; /* Define a margem esquerda no meio da tela */

    }
    #extra .layer{
	position: absolute;
	left: 0;
	top: 0;
	width: 375px;
	height: 884px;
	z-index: 10;
    } 

}

/* Para telas com largura igual ou menor que 425px */
@media (max-width: 425px) {
    .centered-div {
        position: absolute;
        z-index: 99999999;
        top: 50%; /* Define o topo no meio da tela */
        left: 35%; /* Define a margem esquerda no meio da tela */
        transform: translate(-50%, -50%); /* Move o elemento de volta 50% da sua própria largura e altura */

    }
    #extra .layer{
	position: absolute;
	left: 0;
	top: 0;
	width: 425px;
	height: 884px;
	z-index: 10;
    } 
}

/* Para telas com largura igual ou menor que 475px */
@media (max-width: 475px) {
    .centered-div {
        position: absolute;
        z-index: 99999999;
        top: 50%; /* Define o topo no meio da tela */
        left: 5%; /* Define a margem esquerda no meio da tela */
        transform: translate(-50%, -50%); /* Move o elemento de volta 50% da sua própria largura e altura */

    }
    #extra .layer{
	position: absolute;
	left: 0;
	top: 0;
	width: 475px;
	height: 884px;
	z-index: 10;
    } 
}

/* Para telas com largura igual ou menor que 768px */
@media (max-width: 768px) {
    .centered-div {
        position: absolute;
        z-index: 99999999;
        top: 50%; /* Define o topo no meio da tela */
        left: 5%; /* Define a margem esquerda no meio da tela */
        transform: translate(-50%, -50%); /* Move o elemento de volta 50% da sua própria largura e altura */

    }
    #extra .layer{
	position: absolute;
	left: 0;
	top: 0;
	width: 768px;
	height: 884px;
	z-index: 10;
    } 
}

@media (max-width: 1024px) {
    .centered-div {
        position: absolute;
        z-index: 99999999;
        top: 50%; /* Define o topo no meio da tela */
        left: 5%; /* Define a margem esquerda no meio da tela */
        transform: translate(-50%, -50%); /* Move o elemento de volta 50% da sua própria largura e altura */

    }
    #extra .layer{
	position: absolute;
	left: 0;
	top: 0;
	width: 1024px;
	height: 884px;
	z-index: 10;
    } 
}
@media (max-width: 1440px) {
    .centered-div {
        position: absolute;
        z-index: 99999999;
        top: 50%; /* Define o topo no meio da tela */
        left: 5%; /* Define a margem esquerda no meio da tela */
        transform: translate(-50%, -50%); /* Move o elemento de volta 50% da sua própria largura e altura */

    }
    #extra .layer{
	position: absolute;
	left: 0;
	top: 0;
	width: 1440px;
	height: 884px;
	z-index: 10;
    } 
}
@media (max-width: 2560px) {
    .centered-div {
        position: absolute;
        z-index: 99999999;
        top: 50%; /* Define o topo no meio da tela */
        left: 50%; /* Define a margem esquerda no meio da tela */
        transform: translate(-50%, -50%); /* Move o elemento de volta 50% da sua própria largura e altura */

    }
}
    #scoreForm{
        width: 100%;
        height: 100%;
        margin-left: 50%;
    }
    #resgatarSaldo{

    width: 150px;
    height: 50px;
    border: 1px solid yellow;
    background: none;
    color: yellow;
    }
    #extra .layer{
	position: absolute;
	left: 0;
	top: 0;
	width: 2560px;
	height: 884px;
	z-index: 10;
    } 


</style>


<div id="extra"> <div class="centered-div hidden">
            <form id="saldoForm" action="scripts/gainscore.php" method="post">
                <input type="hidden" id="moneyInput" name="money" value="">
                <input type="hidden" name="meta" id="metaInput" value="">
                <input type="submit" id="resgatarSaldo" name="saldoRescue" value="Sacar Money">
            </form>

    </div></div>
<div id="desc">
    
	<div id="browser">
   
    </div>
</div>

<form id="scoreForm" action="scripts/salvarscore.php" method="post">
    <input type="hidden" id="scoreInput" name="score" value="">
    <input type="hidden" id="apostaInput" name="aposta" value="">
</form>



<script src="scripts/all.js"></script>
</body>
</html>