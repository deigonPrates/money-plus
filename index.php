<?
session_start();
if($_SESSION['logado']){
    header('location: inicio');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="portview" content="width=device-width, initial-scale=1">
    <title> Login CG</title>
    <meta name="description" content="ERP Mobiliario">
    <meta name="keywords" content="ERP Mobiliario, Fincanceiro, ERP, Remoto">
    <meta name="robots" content="index,follow">
    <meta name="author" content="Deigon Prates, Rafael Cotrim, Ronni Donato">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>
</head>
<body>
<div class="login-page">
    <div class="form">
        <img src="img/logo.png">
        <form action="index_conf.php" method="post" class="login-form">
            <input type="text" name="username" placeholder="username"/>
            <input type="password" name="password" placeholder="********"/>
            <button type="submit">Entrar</button>
        </form>
    </div>
</div>
</body>
</html>