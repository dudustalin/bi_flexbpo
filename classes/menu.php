<?php

require_once './classes/conexao.php';
require_once './classes/sessao.php';
require_once './classes/flashcards.php';



if ($_SESSION['log']){
    if (isset($_SESSION['grp'])){
    $id_az =$_SESSION['grp'];
    }

} else {
    $id_az = '';
    header('Local: http://localhost/project03/');
    
}

$query = "select * from menu_principal where nivel_acesso >= (select id_grupo_acesso from grupo_acesso where nm_grupo_acesso ='".$id_az."')";
$result = mysqli_query($conect, $query);
//"select cliente from menu_acesso where id_azure ='".$_SESSION['id_azure']."' limit 1 ");


$menu = $pdo->prepare($query);
$menu->execute();
$result = $menu->fetchAll(\PDO::FETCH_ASSOC);

$cat_assoc = viewmenu($result,0);

function viewmenu($categoria, $parent_id){
    $arr = [];

    foreach($categoria as $categ){
        if($categ['id_menu_pai'] == $parent_id){
            $submenu = viewmenu($categoria,$categ['id_menu']);
            if($submenu){
                $categ['filho'] = $submenu;    
            }
            $arr [] = $categ;
        }
    }
return $arr;
}


function menuPrincipal($categoria){
    $html = '<ul class="navbar-nav"';
     foreach($categoria as $value){
        $liClass = isset($value['filho']) ? "dropdown" : '';
        $data_bs = isset($value['filho']) ? 'data-bs-toggle="dropdown"' : '';
        $aClass = isset($value['filho']) ? "dropdown-toggle" : '';
        $html .= '<li class="nav-item '.$liClass.'">
        <a class="nav-link '.$aClass.'" href=""'.$data_bs.'>'.$value['nm_menu'].'</a>'.submenu($value).'</li>';
     }

    $html .="</ul>";

    return $html;

}


function submenu($categ){
    $html = '';

    if(isset($categ['filho'])){
        $html = '<ul class="submenu dropdown-menu">';
        foreach($categ['filho'] as $value){
        $html .= '<li><a class="dropdown-item" href="./detail.php?title='.str_replace(" ", "-", $value['nm_menu']).'&id='.str_replace(" ", "-", $value['id_menu']).'">'.$value['nm_menu'].'</a>'.submenu($value).'</li>';
 
        }
        $html .="</ul>";

    }
    return $html;

}

$html = menuPrincipal($cat_assoc);

?>
