<?php

require_once './classes/conexao.php';
require_once './classes/flashcards.php';

// INICIA A SESSÃO
session_start();

// DEFINE AS INFORMAÇÕES PARA QUE O HANDLER POSSA FAZER AS CONSULTAS NO AD
// TAIS INFORMAÇÕES ENCONTRAM-SE NO ARQUIVO ./conexão.php
$dn = new LdapHandler;
$dn->set_password($ldapPwd);
$dn->set_server($ldapServer);
$dn->set_bkpserver($ldapBkpServer);
$dn->set_tree($ldapTree);
$dn->set_subtree($ldapSubTree);
$dn->set_user($ldapUser);
$dn->set_state();

// POPULA A SESSÃO COM UM ID DE SESSÃO GERADO PELO SERVIDOR
$_SESSION['state'] = session_id();

// INICIA O CONTADOR DE TENTATIVAS DA SESSÃO
if (!isset($_SESSION['count'])){
$_SESSION['count'] = 0;
}
  // UTILIZA A GLOBAL "POST" PARA VERIFICAR SE O BOTÃO DE "ENVIAR FOI PRESSIONADO"
  if (isset( $_POST['submitbutton'])){
       $_SESSION['count'] = 1;
   }

// CASO A SESSÃO TERMINE NA PÁGINA DE LOGIN NOVAMENTE POR ALGUM MOTIVO, FORÇA
// O USUÁRIO A REFAZER O LOGIN
if(!isset($_SESSION['log'])){
   
    $_SESSION['log'] = false;
 } 

// UTILIZA A GLOBAL "POST" PARA RECUPERAR O E-MAIL POSTADO
if (isset( $_POST['email'])){
    $_SESSION['email'] = $_POST['email'];
  }
  
  // UTILIZA A GLOBAL "POST" PARA RECUPERAR A SENHA POSTADA
  if (isset( $_POST['password'])){
    $_SESSION['password'] = $_POST['password'];
  }
  
  //recupera os dados de senha
  if (isset( $_POST['oldpwd'])){
   $_SESSION['oldpwd'] = $_POST['oldpwd'];
 }

 if (isset( $_POST['newpwd1'])){
   $_SESSION['newpwd1'] = $_POST['newpwd1'];
 }

 if (isset( $_POST['newpwd2'])){
   $_SESSION['newpwd2'] = $_POST['newpwd2'];
 }

  
         // AVALIA SE E-MAIL E SENHA ESTÃO DEFINIDOS
         // CASO ESTEJAM, FAZ A BUSCA NO AD E ARMAZENA OS DADOS NECESSÁRIOS NA SESSÃO
         if(isset($_POST['password']) & isset($_POST['email'])){     

            $a = null;
            $b = null;
            $c = false;
            $d = false;
            $_SESSION['log'] = false;

            // ATRIBUI VALORES ÀS VARIÁVEIS DE SESSÃO NECESSÁRIAS PARA LOGIN VIA AD
            // ATRIBUI GRUPO 
            try{
               $d = $dn->prop_client_exp_pwd($_SESSION['email']);
            } finally {
               $_SESSION['client'] = $d;
            }
            try{
               $a = $dn->prop_group($_SESSION['email'] , 'PORTAL_BIFLEX*');
            } finally {
               $_SESSION['grp'] = $a;
            }
            // ATRIBUI NOME DE USUÁRIO
            try{
               $b = $dn->prop_name($_SESSION['email'] );
            } finally {
               $_SESSION['usrnm'] = $b;
            }
            // ATRIBUI DADOS DE LOGIN NO AD
            try{
               $c = $dn->prop_user($_SESSION['email'] ,  $_POST['password']);
            } finally {
               $_SESSION['log'] = $c;
            }


         }


function solve_session_login($dn){
   // CHECA A RESPOSTA QUANTO AO PRIMEIRO NOME DO USUÁRIO
   switch (isset($_SESSION['usrnm'])){
      case true:
         // CHECA A RESPOSTA QUANTO AOS DADOS DE LOGIN
         $chk = $dn->prop_user($_SESSION['email'] , $_POST['password']);
         switch ($chk){
            case true:
               // CHECA A RESPOSTA QUANTO AO PERTENCIMENTO DO USUÁRIO EM ALGUM GRUPO "PORTAL_BIFLEX_..."
               switch (isset($_SESSION['grp'])){
                  case true:
                     switch ($_SESSION['client']){
                        case -1:
                           // SENDO TODAS AS CONDIÇÕES VERDADEIRAS, REDIRECIONA PARA A PÁGINA HOME
                           header('Location: http://localhost/project03/home.php');
                           exit;
                        case 0:
                           //QUANDO ESTÁ TUDO OK
                           header('Location: http://localhost/project03/home.php');
                           exit;
                        default:
                           // QUANDO ESTÁ PERTO DE VENCER OU VENCIDA - CINCO DIAS OU MAIS
                           header('Location: http://localhost/project03/');
                           exit;
                        
                     }
                  case false:
                     // CASO O USUÁRIO NÃO PERTENÇA A UM GRUPO COMO O CITADO ACIMA, ENCAMINHA PARA A
                     // PÁGINA DE SOLICITAÇÃO DE INCLUSÃO EM UM DOS GRUPOS DO AD
                     header( 'Location: http://localhost/project03/acesso.php');
                     exit;
               }
               break;
            // EM TODAS AS OUTRAS CONDIÇÕES O USUÁRIO FICARÁ 'PRESO' NA PÁGINA DE LOGIN ATÉ QUE ACERTE
            // OS DADOS DE LOGIN, SENDO AVISADO DOS ERROS PELAS FLASHMESSAGES
            case false:
               ;
         }
         ;

      case false:
         ;
   }
}
   function change_password( $dn,
                            string  $prop_old_password, 
                            string  $prop_new_password,
                            string  $prop_confirm){
        /*if (! isset($_SESSION['email'])){
            return false;
        }*/
        $result = false;
        $ldapConn = $dn->ldapConn();
        $ldapBind = ldap_bind($ldapConn, $dn->ldapUser, $dn->ldapPwd);
        if (isset($prop_old_password) & isset($prop_new_password) & isset($prop_confirm)){
            switch ($prop_old_password == $prop_new_password){
                case false:
                    switch ($prop_new_password == $prop_confirm){
                        case true:
                           $_SESSION['chpwd'] =  $dn->set_new_password(/*$_SESSION['email']*/ 'sasa.ddd', $prop_new_password);
                           header('Location: http://localhost/project03/home.php');
                           exit;                       
                        default:
                           break;
                    }
                    
                default:
               break;
            }

        }
    }


?>