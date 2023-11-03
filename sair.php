<?php
require_once './classes/sessao.php';
require_once './classes/conexao.php';

session_unset();
session_destroy();
unset($_SERVER['flash']);
if (isset($_POST["password"]) ){
    $_POST["password"] = '';
} 

header("Location: http://localhost/project03/");

?>