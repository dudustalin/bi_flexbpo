

<head>
<!DOCTYPE html>
<html lang="pt-BR">

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <?php
    $url = $_GET['title'];
    $title = str_replace("-", " ", $url);

    $url = $_GET['id'];
    $id_rel = str_replace("-", " ", $url);

    ?>
    <title><?php echo $title; ?> </title>

        <!-- -->
        <link rel="stylesheet" href="./css/detail.css">

</head>

<body>




    <?php
    require_once "./home_menu.php";
       include_once("./classes/conexao.php");


    $resultado = mysqli_query($conect, "select * from menu_principal where id_menu='$id_rel'");
    $dados = mysqli_fetch_array($resultado);

    $link_bi = $dados['link'];
    $width = $dados['width'];
    $height = $dados['height'];


 

echo 
'<iframe class="frame"

src="'.$link_bi.'" 
frameborder="0" allowFullScreen="true"></iframe>';





   ?>

</body>

</html>