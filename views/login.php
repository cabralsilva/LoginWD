<?php
include '../util/constantes.php';
date_default_timezone_set ( 'America/Sao_Paulo' );
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>iBoltSys - Login</title>
<link rel="stylesheet"
	href="<?= BaseProjeto ?>/resources/css/style-login.css">

<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>
<body>
	<div class="login">
		<div class="login-screen">
			<div class="app-title">
				<h1>iBolt Sistemas</h1>
				<h3>Acesso restrito</h3>
			</div>
			<span class="fail-login"> </span>

			<div class="login-form">
				<form id="frmLogin" action="<?= BaseProjeto ?>/controllers/indexController.php" method="post">
					<div class="control-group">
						<input type="text" class="login-field form-control" placeholder="usuário" id="usr" name="usr" required="required"> 
						<label class="login-field-icon fui-user" for="email"></label>
					</div>

					<div class="control-group">
						<input type="password" class="login-field form-control" placeholder="password" id="pwd" name="pwd" required="required"> 
						<label class="login-field-icon fui-lock" for="login-pass"></label>
					</div>
					
					<div class="control-group hide">
						<input hidden type="text" class="login-field form-control" id="system" name="system" required="required" value="<?= $_GET["sistema"]?>"> 
						<label class="login-field-icon fui-user" for="email"></label>
					</div>
					
					<button type="submit" class="btn btn-primary btn-large btn-block">Entrar</button>
					<a class="login-link" href="#">Esqueceu sua senha? <?= $_GET["sistema"]?></a>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
