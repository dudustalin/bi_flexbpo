<?php
require_once './classes/conexao.php';
require_once './classes/flashcards.php';
require_once './classes/sessao.php';


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
    
<div class="card-header"><h3>Login</h3></div>
<div class="card-body">
    <form action="" method="POST">

        <div class="form-group">
          

          <label for="email">Nome de rede</label>
          <input type="text" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Entre o login de rede"
          >
        </div>
        <div class="form-group">


          <label for="password">Senha</label>
          <input type="password" class="form-control" name="password" id="password" placeholder="Senha"
          >
        </div>
        <div class="form-check">
          <br>
        <button type="submit" name="submitbutton" class="btn btn-primary" style="position: relative; left:30%;">Login</button>
</div>
      </form>

    </div>

</body>
</html>