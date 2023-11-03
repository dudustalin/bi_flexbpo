<?php

require_once './classes/sessao.php';
require_once './classes/conexao.php';

// CLASSE CRIADA PARA GERAR MENSAGENS NUMA TELA DE LOGIN
class FlashCard {

    public $dataConf;
    public $dataMode;

    // MÉTODOS SET
    function set_dataconf($dataConf){
        $this->dataConf = $dataConf;

    }

    function set_datamode(int $dataMode){
        $this->dataMode = $dataMode;
        $this->determineMode();
        $this->prep_data();

    }

    // MÉTODOS GET
    function get_dataconf(){
        if (isset($this->dataConf)){
            return $this->dataConf;
        } 
        return null;
    }

    function get_datamode(){
        if (isset($this->dataMode)){
            return $this->dataMode;
        } 
        return 0;
    }

    // MÉTODOS ESTÁTICOS
    function determineMode(){
        $arr = [];
        switch ($this->dataMode){
            case 0:
                $arr = [
                    'email'=>'É preciso preencher um nome de usuário.',
                    'password'=>'É preciso preencher uma senha.',
                    'error'=>'Nome de usuário ou senha incorretos. Tente novamente.'
                ];
                break;

            case 1:
                $arr = [
                    'oldpwd'=>'É preciso digitar a senha antiga para prosseguir.',
                    'newpwd1'=>'É preciso digitar uma nova senha para prosseguir.',
                    'newpwd2'=>'É preciso repetir a nova senha para prosseguir.',
                    'pwdcoincidence'=>'A nova senha deve ser diferente da antiga.',
                    'pwdCheck'=>'As duas senhas precisam coincidir.',

                ];
                break;

        }
        $this->set_dataconf($arr);
    }

    function populateFlash() {
        $this->prep_data();
        if (isset($this->dataConf)){
            if ($_SESSION['count'] > 0){
            foreach ($this->get_dataconf() as $key=>$item){
                        switch ($key){
                            case 'email':
                                if (($_SESSION[$key] == '' | !isset($_SESSION[$key]))){
                                    $_SERVER['flash'][$key]=$item;
                                }
                                break;
                            case 'password':
                                if (($_SESSION[$key] == '')){
                                    $_SERVER['flash'][$key]=$item;
                                }
                                break;

                            case 'error':
                                if ((!$_SESSION['log'] | !isset($_SESSION['log']))){
                                    $_SERVER['flash'][$key]=$item;
                                }
                                break;
                            case 'oldpwd':
                                if (($_SESSION[$key] == '' | !isset($_SESSION[$key]))){
                                    $_SERVER['flash'][$key]=$item;
                                }
                                break;
                            case 'newpwd1':
                                if (($_SESSION[$key] == '' | !isset($_SESSION[$key]))){
                                    $_SERVER['flash'][$key]=$item;
                                }
                                break;
                            case 'newpwd2':
                                if (($_SESSION[$key] == '' | !isset($_SESSION[$key]))){
                                    $_SERVER['flash'][$key]=$item;
                                }
                                break;
                            case 'pwdcoincidence':
                                if ($_SESSION['oldpwd'] == $_SESSION['newpwd1']){
                                    $_SERVER['flash'][$key]=$item;
                                }
                                break;
                            case 'pwdCheck':
                                if ($_SESSION['newpwd1'] != $_SESSION['newpwd2']){
                                    $_SERVER['flash'][$key]=$item;
                                }
                                break;
                            
                        }
                    }
                
            }
        } 
    }


    function prep_data(){
        if (isset($this->dataMode)){
            $arr = [0=>['email','password','error'],
            1=>['oldpwd','newpwd1','newpwd2','pwdcoincidence', 'pwdcheck']];

            foreach ($arr[$this->get_datamode()] as $flash){
                if (!isset($_SERVER['flash'][$flash])){
                    $_SERVER['flash'][$flash] = '';
                }
            }
        } 
    }

    function generate_flash(string $message): string {
        return 
        '<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
            <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </symbol>
        </svg>
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
            <div class="row">
                <div class="col-9">'.$message.'</div>
                
                <div class="col-3">
                <button type="button" class="btn-close"  data-bs-dismiss="alert" aria-label="Close" style="position: absolute; left: 83%; top: 33%;"></button>
                </div>
                
                </div>
        </div>';
        
    }



    }

?>