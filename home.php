<?php 

require_once './classes/menu.php'; 
require_once './classes/conexao.php';
require_once './classes/sessao.php';
require_once './classes/flashcards.php';


// RETORNA O USUÁRIO PARA A PÁGINA DE LOGIN CASO A SESSÃO TENHA SIDO ENCERRADA
// OU O USUÁRIO TENHA CONSEGUIDO ENTRAR NESSA PÁGINA SEM PREENCHER TODAS AS CREDENCIAIS VÁLIDAS
if (! $_SESSION['log']){
	header('Location: http://localhost/project03');
}

$result = mysqli_query($conect, "select cliente from menu_acesso where id_azure in (select id_azure from menu_acesso where nm_grupo_acesso = '".$id_az."') limit 1 ");
$result = $pdo->prepare($query);
$result->execute();
//$client = $result->fetchAll[0];

?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <!-- Site made with Mobirise Website Builder v5.8.14, https://mobirise.com -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="generator" content="Mobirise v5.8.14, mobirise.com">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <link rel="shortcut icon" href="./img/icon.png" type="image/x-icon">
    <meta name="description" content="">


    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-reboot.min.css">



    <!-- CSSs próprias do Site -->
    <link rel="stylesheet" href="./css/menu.css">
    <link rel="stylesheet" href="./lps/hm_client.css">

</head>


<body class="bd">

    <!--============= COMPONENT ============== -->
     <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div>
            <a href="./sair.php"><button class="btn-sair" type="button">Sair</button></a>
        </div>
        <div>
            <a class="nm_logado">
                <?php

                echo "Seja bem vindo(a), ". ucfirst(strtolower(($_SESSION['usrnm'])))."!";
                ?>
            </a>
        </div>

        <div class="bg-logoatma">
            <img class="logoatma" src="img/flex_05-01.png">
        </div>
    
        <div class="collapse navbar-collapse" id="main_nav">

            <?= $html ?>
        </div>

    </nav>

    <?php

switch($_SESSION['client']){
    case -1:
        $link ='./lps/homeFlex.php';
        break;
    default:
        $link ='./lps/homeCustomer.php';
        break;
    }

include $link;
?>

<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/smoothscroll/smooth-scroll.js"></script>
        <script src="assets/ytplayer/index.js"></script>
        <script src="assets/theme/js/script.js"></script>

</body>

</html>
