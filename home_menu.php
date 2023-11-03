<?php 
require_once './classes/menu.php';
require_once './classes/sessao.php';
require_once './classes/flashcards.php';
require_once './classes/conexao.php';

if (!$_SESSION['log']){
	header('Location: http://localhost/project03');
}

?>

<!DOCTYPE HTML>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="htmlcss bootstrap, multi level menu, submenu, treeview nav menu examples" />
    <meta name="description" content="Bootstrap 5 navbar multilevel treeview examples for any type of project, Bootstrap 5" />

    <title>HOME</title>

    <!-- -->
    <link rel="stylesheet" href="./css/menu.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>


</head>

<body class="bd">

    <!-- ============= COMPONENT ============== -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div>
            <a href="./sair.php"><button class="btn-sair" type="button">Sair</button></a>
        </div>
        <div>
            <a class="nm_logado">
                <?php             
                echo "Seja bem vindo(a), ".ucfirst(strtolower(($_SESSION['usrnm'])))."!";
                ?>
            </a>
        </div>

        <div class="bg-logoatma" style="opacity:0">
        -- <a href="./home.php"> <img class="logoatma" src="img/flex_05-01.png"></a>
        </div>

        <div class="collapse navbar-collapse" id="main_nav">

            <?= $html ?>
        </div>

    </nav>


</body>

</html>
