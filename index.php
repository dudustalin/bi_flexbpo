<?php

// INÍCIO - 
// INFORMAÇÕES NECESSÁRIAS PARA O PROCESSO DE LOGIN

// DEPENDÊNCIAS DE BIBLIOTECAS
require_once './classes/conexao.php';
require_once './classes/flashcards.php';
require_once './classes/sessao.php';


 /* SELETOR PARA ESCOLHA DA PÁGINA A SER EXIBIDA
 SÃO POSSÍVEIS QUATRO OPÇÕES: 
-1) LOGIN INTERNO; .
0) LOGIN EXTERNO;
1)
2)
 */
if (! isset($_SESSION['client'])){
   $_SESSION['client'] = -1;
}

$flashcards = new FlashCard;

 switch($_SESSION['client']){
   case 2: // 2 é o número padrão para a opção ao lado
      $flashcards->set_datamode(1);
      $switch_login =  "./login/chpassword.php";
      break;
   default:
      $flashcards->set_datamode(0);
      $switch_login =  "./login/login.php";
      break;

}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Business Intelligence</title>

   <!-- CSS-->

   <link rel="stylesheet" type="text/css" href="./css/styleparticles.css">
   <link rel="stylesheet" type="text/css" href="./css/landPage.css">

</head>

<body>



   <div class="logoatma">
      <img class="logoatma" src="./img/flex_05-01.png">
   </div>

   <div><img class="logobi" src="./img/bi_motto.png"></div>

<div id="particles-js" style="position: absolute;">

   <div class="frame">
      <div class="cardtheme card-body">

         <?php

      foreach($_SESSION as $key=>$item){
         echo $key."=>".$item.";".chr(0x0A);
      }
         //echo implode($_SESSION);
      

      include $switch_login;

      // ÁRVORE DE DESTINO
      // APÓS A REQUISIÇÃO DE LOGIN, A ÁRVORE DE DESTINO ENCAMINHARÁ O USUÁRIO PARA O
      // DIRETÓRIO PERTINENTE DEPENDENDO DAS RESPOSTAS OOBTIDAS VIA AD
      if (isset($_POST["submitbutton"])){
      
      switch ($flashcards->get_datamode()){
         case 1: // O padrão da opção ao lado é 1
            change_password($dn, $_SESSION['oldpwd'], $_SESSION['newpwd1'], $_SESSION['newpwd2']);
         default:
            solve_session_login($dn);
      }
      }    
      
      ?>

         </div>
      </div>

       <div class="flashcards">
   <?php 
      
      $flashcards->populateFlash();

      if (isset($_SERVER['flash']) & $_SESSION['count'] =1) {
         foreach($_SERVER['flash'] as $key=>$item){
            if ($_SERVER['flash'][$key] <> ''){

                  echo $flashcards->generate_flash($item);
            
            }
         }
         //unset($_SERVER['flash']);
      }

   ?>
</div> 

   </div>
</div>
<!-- scripts
ESTE SCRIPT É RESPONSÁVEL PELO EFEITO DE PARTÍCULAS DO FRONT
-->
         <script src="./js/particles.js"></script>
      <script src="./js/app.js"></script>
   </body>
</html>
