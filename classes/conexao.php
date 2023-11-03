<?php

include './dotenv.php'; 

(new DotEnv('./.env'))->load();

$pdo = new PDO('mysql:host=localhost;dbname=portal_bi_flex','root','');
$conect = new mysqli('localhost','root','','portal_bi_flex');

$ldapServer = getenv('MAIN_SERVER');
$ldapBkpServer = getenv('BACKUP_SERVER');
$ldapUser = getenv('USER');
$ldapPwd = getenv('PASSWORD');
$ldapTree = getenv('TREE');
$ldapSubTree = getenv('SUBTREE');

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }
    
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

class LdapHandler{
    //Propriedades
    public $ldapServer;
    public $ldapBkpServer;
    public $ldapUser;
    public $ldapPwd;
    public $ldapTree;
    public $ldapSubTree;
    public $ldapState;

    //Métodos
    //setters
    // Funções que atribuem valores às propriedades do ldapHandler
    function set_server(string $ldapServer) : void {
        $this->ldapServer = $ldapServer;
    }

    function set_bkpserver(string $ldapBkpServer) : void {
        $this->ldapServer = $ldapBkpServer;
    }

    function set_user (string $ldapUser) : void {
        $this->ldapUser = $ldapUser;
    }

    function set_password (string $ldapPwd) : void {
        $this->ldapPwd = $ldapPwd;
    }

    function set_tree (string $ldapTree) : void {
        $this->ldapTree = $ldapTree;
    }
    
    function set_subtree (string $ldapSubTree) : void {
        $this->ldapSubTree = $ldapSubTree;
    }

    function set_state() : void {
        $this->ldapState = $this->ldapresp();
    }

    //getters
    // Funções para solicitar os atributos do ldapHandler
    function get_server () {
        return $this->ldapServer;
    }

    function get_bkpserver () {
        return $this->ldapBkpServer;
    }

    function get_user () {
        return $this->ldapUser;
    }

    function get_password () {
        return $this->ldapPwd;
    }

    function get_tree () {
        return $this->ldapTree;
    }

    function get_subtree () {
        return $this->ldapSubTree;
    }

    function get_state(){
        return $this->ldapState;
    }

    //Estados
    // Funções que determinam os estados do ldapHandler
    // As funções abaixo retornam dados do LDAP server sem a necessidade de apresentar argumentos
    // para as mesmas;
    
    function ldapConn(){
        $ldapConn = ldap_connect($this->ldapServer);
        ldap_set_option( $ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3 );
        ldap_set_option( $ldapConn, LDAP_OPT_REFERRALS, 0 );
        if (!$ldapConn){
            $ldapConn = ldap_connect($this->ldapBkpServer);
        }
        return $ldapConn;
    }
    
    function ldapresp(){
        // Define a conexão com o servidor
        // Caso o servidor principal não esteja disponível, a rotina altera o servidor para o Backup
        $ldapConn = $this->ldapConn();
        if ($ldapConn){
            // Fornece as informações de login do usuário LDAP
            $ldapBind = ldap_bind($ldapConn, $this->ldapUser, $this->ldapPwd);
            // Retona o estado da conexão
            ldap_unbind($ldapConn);
            return $ldapBind;
        }
        // Valor sentinela, caso a conexão não seja estabelecida, retorna o insucesso
        //ldap_unbind($ldapConn);
        return false;
    
    }
    
    //Ações
    // Realiza as consultas no LDAP e retorna os resultados relevantes
    // Na seção abaixo devem constar apenas os métodos que requerem algum argumento externo
    function prop_user(string $prop_user, string $prop_password){
        // PESQUISA OS DADOS DE LOGIN DO USUÁRIO NO AD
        $filter = '(&(objectclass=user)(sAMAccountname=flexcontact\\'.$prop_user.'))';
        if (!isset($this->ldapState) | $prop_password == ''){
            return false;
        }
        $ldapConn = $this->ldapConn();
        try{
            $ldapBind = ldap_bind($ldapConn, 'flexcontact\\'.$prop_user, $prop_password);
            ldap_unbind($ldapConn);
        } catch (Exception $e){
            $ldapBind = false;
        }
        //ldap_unbind($ldapConn);
        return $ldapBind;
    }

    function prop_group(string $prop_user, $grpName){
        // CHECA SE O USUÁRIO PARTICIPA DE UM GRUPO NO AD
        //
        $filter = '(&(objectclass=user)(sAMAccountname='.$prop_user.'))';//(group=cn=PORTAL_BIFLEX*))';
        if (!isset($this->ldapState)){
            return null;
        }
        $ldapConn = $this->ldapConn();
        $ldapBind = ldap_bind($ldapConn, $this->ldapUser, $this->ldapPwd);
        $result = ldap_get_entries($ldapConn, ldap_search($ldapConn, $this->ldapTree,  $filter));

        if (! isset($result)){
            return null;
        } 
        try{
            $usrName = $result[0]['dn'];
            $filter = '(&(objectclass=group)(member='.$usrName.')(cn='.$grpName.'))';
        } catch (Exception $e){
            return null;
        }

        $result = ldap_get_entries($ldapConn, ldap_search($ldapConn, $this->ldapTree,  $filter));
        ldap_unbind($ldapConn);
        if (! isset($result[0]['cn'][0])){
            return null;
        } 
        $x = $result[0]['cn'][0];
        return $x;
    }

    function prop_name(string $prop_user){
        // CHECA SE O USUÁRIO TEM O SEU PRIMEIRO NOME CADASTRADO NO AD
        $filter = '(&(objectclass=user)(sAMAccountname='.$prop_user.'))';//(group=cn=PORTAL_BIFLEX*))';
        if (!isset($this->ldapState)){
            return null;
        }
        $ldapConn = $this->ldapConn();
        $ldapBind = ldap_bind($ldapConn, $this->ldapUser, $this->ldapPwd);
        $result = ldap_get_entries($ldapConn, ldap_search($ldapConn, $this->ldapTree,  $filter));

        if (! $result){
            return null;
        } 
        //ldap_unbind($ldapConn);
        if (! isset($result[0]['givenname'][0])){
            return null;
        }
        return $result[0]['givenname'][0];
    }

//INCLUSÕES REALIZADAS EM 12/09/2023
// AUTOR: EDUARDO DOS SANTOS CRUZ
// NECESSIDADE:     INCLUSÃO DE POLÍTICA DE LOGIN PARA USUÁRIOS EXTERNOS VIA AD,
//                  BEM COMO ALTERAÇÃO DE SENHA PARA CLIENTES EXTERNOS. A PRIMEIRA FUNÇÃO CHECARÁ
//                  SE O CLIENTE É INTERNO OU EXTERNO PARA ESCOLHA DA LANDING PAGE. A SEGUNDA FUNÇÃO 
//                  AVALIARÁ SE O CLIENTE NECESSITA ALTERAR A SENHA OU NÃO. 
    // AVALIAR A NECESSIDADE DE ALTERAÇÕES ASSIM QUE IMPLEMENTADO
    function prop_client(string $prop_user, $grpName){
        // CHECA SE O USUÁRIO PARTICIPA DE UM GRUPO de usuários externos NO AD

        $filter = '(&(objectclass=user)(sAMAccountname='.$prop_user.'))';//(group=cn=PORTAL_BIFLEX*))';
        if (!isset($this->ldapState)){
            return null;
        }
        $ldapConn = $this->ldapConn();
        $ldapBind = ldap_bind($ldapConn, $this->ldapUser, $this->ldapPwd);
        $result = ldap_get_entries($ldapConn, ldap_search($ldapConn, $this->ldapSubTree,  $filter));

        if (! isset($result)){
            return null;
        } 
        try{
            $usrName = $result[0]['dn'];
            $filter = '(&(objectclass=organizationalunity)(member='.$usrName.')(cn='.$grpName.'))';
            $result = ldap_get_entries($ldapConn, ldap_search($ldapConn, $this->ldapSubTree,  $filter));
        } catch (Exception $e){
            return null;
        }

        ldap_unbind($ldapConn);
        if (! isset($result[0]['cn'][0])){
            return null;
        } 
        $x = $result[0]['cn'][0];
        return $x;
    }

// AVALIAR ALTERAÇÕES ASSIM QUE IMPLEMENTADO
function prop_client_exp_pwd(string $prop_user){
    // CHECA SE A SENHA DO USUÁRIO ESTÁ PARA EXPIRAR E O DIRECIONA PARA A PÁGINA DE TROCA DE
    // SENHAS

    $filter = '(&(objectclass=user)(sAMAccountname='.$prop_user.'))';
    $result = null;
    $ldapConn = $this->ldapConn();
    $ldapBind = ldap_bind($ldapConn, $this->ldapUser, $this->ldapPwd);
    try {
        $result = ldap_get_entries($ldapConn, ldap_search($ldapConn, $this->ldapSubTree,  $filter));
    } catch (Exception $e){
        return $result;
    }
    
    $res = -1;
    if (isset($result[0]['pwdlastset'][0])){
        $res = (int)(($result[0]['pwdlastset'][0])/10_000_000) - 11_644_477_200;
        $timestamp = time() - $res ;
        if ($timestamp < 34_560_000){
            $res = 0;
        } elseif ($timestamp < 38_880_00){
            $res = 1;
        } else{
            $res = 2;
        }

    }

    return $res;
    } 

    function set_new_password(
        $prop_user,
        $prop_new_password
    ){
        $con = $this->ldapConn();
        $dn = $this->ldapTree;
        $filter = '(&(objectclass=user)(sAMAccountname='.$prop_user.'))';
        $ldapBind = ldap_bind($con, $this->ldapUser, $this->ldapPwd);
        $user_search = ldap_search($con,$dn, $filter);
        try{
            $result = ldap_get_entries($con, $user_search)[0]['cn'][0];
        } catch (Exception $e){
            return false;
        }
        
        
        $newPassw = '';
        $newPassword = "\"" . $prop_new_password . "\"";
        $len = strlen($newPassword);
        for ($i = 0; $i < $len; $i++)
                $newPassw .= "{$newPassword[$i]}\000";
        $newPassword = $newPassw;
        $userdata["unicodepwd"] = $newPassword;
        $result = ldap_mod_replace($con, $result, $userdata);
          

        return $result;
    }

    
} 
?>
