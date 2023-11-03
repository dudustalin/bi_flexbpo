<?php
require_once './classes/conexao.php';
require_once './classes/flashcards.php';
//require_once './classes/sessao.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="./css/landPage.css">

    <title>login</title>
</head>
<body>
    

<div class="card-header"><h3>Troca de Senha</h3></div>
<div class="card-body">
<form action="" method="POST">
    <div class="form-group">
          <label for="oldpwd">Senha Antiga</label>
          <input type="password" class="form-control" name="oldpwd" id="oldpwd" placeholder="Senha">
        </div>

        <div class="form-group">
          <label for="newpwd1">Senha Nova</label>
          <input type="password" class="form-control" name="newpwd1" id="newpwd1" placeholder="Senha">
        </div>

        <div class="form-group">
          <label for="newpwd2">Repita a Nova Senha</label>
          <input type="password" class="form-control" name="newpwd2" id="newpwd2" placeholder="Senha">
        </div>
        <div class="form-check">
          <br>
          <button type="submit" name="submitbutton" class="btn btn-primary" style="position: relative; left:25%;">Trocar Senha</button>
      </div>
      </form>

    </div>


    <script>

    </script>





</body>
</html>