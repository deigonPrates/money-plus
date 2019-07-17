<?php

require_once './file/inc/conecta.php';

session_start();
if(!$_SESSION['logado']){
    header('location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="portview" content="width=device-width, initial-scale=1">
    <title>Gerente Remoto</title>
    <meta name="description" content="ERP Mobiliario">
    <meta name="keywords" content="ERP Mobiliario, Fincanceiro, ERP, Remoto">
    <meta name="robots" content="index,follow">
    <meta name="author" content="Deigon Prates, Rafael Cotrim, Ronni Donato">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>
    <link rel="icon" href="img/icon.png">

</head>
<body>
<!-- CABEÇALHO -->
<header class="container">
    <a href="index.html"><h1 class="logo">Gerente Remoto</h1></a>
    <button class="btn-menu bg-gradient"><i class="fa fa-bars fa-lg"></i></button>
    <nav class="menu">
        <a class="btn-close"><i class="fa fa-times"></i></a>
        <ul>
            <li><a href="<?=URL_SITE?>inicio">Inico</a></li>
            <li><a href="#" onclick="showMenu()">Cadastros</a>
                <ul class="sub-menu" id="sub-menu">
                    <li><a href="<?=URL_SITE?>diversos/usuario">Usuários</a></li>
                    <li><a href="<?=URL_SITE?>diversos/parceiro">Parceiros</a></li>
                    <li><a href="<?=URL_SITE?>diversos/despesa">Despesa</a></li>
                </ul>
            </li>

            <li><a href="#">Gasto</a></li>
            <li><a href="#">Rateio</a></li>
        </ul>
    </nav>
</header>

<main class="container">

</main>
<!-- RODAPE -->
<footer class="container bg-gradient footer">
    <div class="social-icons">
        <a href="#"><i class="fa fa-facebook"></i></a>
        <a href="#"><i class="fa fa-twitter"></i></a>
        <a href="#"><i class="fa fa-google"></i></a>
        <a href="#"><i class="fa fa-instagram"></i></a>
        <a href="#"><i class="fa fa-envelope"></i></a>
    </div>
    <p class="copyright">
        Copyright © NodeProp 2016. Todos os direitos reservados.
    </p>
</footer>

<script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
<script type="text/javascript">

    $('.btn-menu').click(function () {
        $('.menu').show();
    });

    $('.btn-close').click(function () {
        $('.menu').hide();
    });

    function showMenu() {
       if($('#sub-menu').css('display') == 'none'){
           $('#sub-menu').css('display', 'block');
       }else{
           $('#sub-menu').css('display', 'none');
       }
    }
</script>
</body>
</html>

