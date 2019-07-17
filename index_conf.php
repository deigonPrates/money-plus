<?
require_once './file/inc/conecta.php';

session_start();


if($_POST['username'] and $_POST['password']){

    $password = hash('sha512', $_POST['password']);
    $sql = "select * from usuarios where nome = '{$_POST['username']}' and senha = '{$password}'";
    $con_usuario = mysqli_query($conexao,$sql);
    $obj_usuario = $con_usuario->fetch_object();

    if(!empty($obj_usuario)){
        $_SESSION['logado'] = $obj_usuario;
        header("location: inicio.php");
    }else{
        echo "<script>
            alert('Dados invalidos, tente novamente');
            window.location.href = 'index.php';
         </script>";
    }

}else{
    echo "<script>
            alert('Todos os campos sao obrigatorios');
            window.location.href = 'index.php';
         </script>";
}






?>