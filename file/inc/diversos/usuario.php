<?
if (($_POST['nome']) and ($_POST['senha']) and ($_POST['confirmacao_senha'])) {

    $erros = array();

    if ($_POST['codigo']) {

        if ($_POST['senha'] === $_POST['confirmacao_senha']) {
            $senha = hash('sha512', $_POST['senha']);
            $sql_insert = "update usuarios set nome = '{$_POST['nome']}' ,senha = '$senha' where codigo = {$_POST['codigo']}";
            mysqli_query($conexao, $sql_insert) or die('Erro ao salvar:' . $sql_insert);
        } else {
            $erros[] = 'As senhas nao conferem';
        }
    } else {
        if ($_POST['senha'] === $_POST['confirmacao_senha']) {
            if (!existe_nome($_POST['nome'], $conexao)) {
                $senha = hash('sha512', $_POST['senha']);
                $sql_insert = "insert into usuarios(nome,senha, status) value('{$_POST['nome']}','{$senha}','1')";
                mysqli_query($conexao, $sql_insert) or die('Erro ao salvar:' . $sql_insert);
            } else {
                $erros[] = 'Ja existe esse nome salvo na nossa base, favor use outro';
            }

        } else {
            $erros[] = 'As senhas nao conferem';
        }
    }


    if (count($erros) > 0) {
        $error = implode(',', $erros);
        echo "<script>
                        alert('{$error}');
              </script>";
    } else {
        echo "<script>
                        alert('Operação realizada com sucesso');
              </script>";
    }
}


$sql_usuarios = "SELECT * FROM USUARIOS limit 5";
$obj_usuarios = mysqli_query($conexao, $sql_usuarios);


if ($_GET['id']) {
    $sql_usuarios = "SELECT * FROM USUARIOS WHERE codigo = '{$_GET['id']}'";
    $obj_usuarios = mysqli_query($conexao, $sql_usuarios);
    $usuario = $obj_usuarios->fetch_object();
}

/**
 * @param $nome
 * @return bool
 */
function existe_nome($nome, $conexao)
{
    $sql_nome = "select * from usuarios where nome = '$nome'";
    $obj_banco = mysqli_query($conexao, $sql_nome);
    $array_dados = $obj_banco->fetch_array();

    if (count($array_dados) > 0) {
        return true;
    } else {
        return false;
    }
}

?>
<div class="navega">
    <ul>
        <li><a href="<?= URL_SITE ?>inicio">Inicio</a></li>
        <li><a href="<?= URL_SITE ?>diversos/usuario">Usuário</a></li>
    </ul>
</div>
<div class="user-index" <?= ($_GET['id']) ? " style='display:none'" : ' ' ?>>
    <button class="btn-verde" onclick="abrirCadastro()">Cadastrar</button>
    <button class="btn-azul" onclick="abrirListagem()">Listar</button>
</div>

<div class="user-cadastro" <?= ($_GET['id']) ? " style='display:block'" : '' ?>>

    <form action="<?= URL_SITE ?>diversos/usuario" method="post" class="form">
        <?php
        if ($_GET['id']) {
            echo "<input type='hidden' value='{$_GET['id']}' name='codigo'>";
        }
        ?>
        <input type="text" name="nome" id="nome" value="<?= $usuario->nome ?>" placeholder="Nome de usuário" required>
        <input type="password" name="senha" id="senha" onblur="validPass()" placeholder="Senha" required>
        <input type="password" name="confirmacao_senha" id="confirmacao_senha" onblur="validPass()"
               placeholder="Repita a senha" required>
        <label id="error">As senhas não conferem</label>
        <button type="button" onclick="validForm()"
                id="btn-save">  <?= ($_GET['id']) ? " Salvar" : 'Cadastrar' ?></button>
    </form>
</div>
<div class="user-listagem" <?= ($_GET['id']) ? " style='display:none'" : '' ?>>
    <table id="customers">
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Status</th>
            <th>Ação</th>
        </tr>

        <? $cont = 1; ?>
        <? while ($usuario = $obj_usuarios->fetch_object()) { ?>
            <tr>
                <td><?= $cont ?></td>
                <td><?= $usuario->nome ?></td>
                <td><?= ($usuario->status == 1) ? 'Ativo' : 'Inativo' ?></td>
                <td><a href="<?= URL_SITE ?>diversos/usuario/edt?id=<?= $usuario->codigo ?>">Editar</a></td>
            </tr>
            <? $cont++; ?>
        <? } ?>
    </table>
</div>


<script>
    function validPass() {
        var error = '-1';
        var senha = document.getElementById('senha');
        var confirma_senha = document.getElementById('confirmacao_senha');

        if ((confirma_senha.value === senha.value) || (confirma_senha.value === '')) {
            confirma_senha.style.border = '';
            senha.style.border = '';
            document.getElementById('error').style.display = 'none';
            error = '-1';

            if (confirma_senha.value.length >= 8) {
                confirma_senha.style.border = 'solid 1px red';
                senha.style.border = 'solid 1px red';
                document.getElementById('error').style.display = 'block';
                error = 'A senha deve ter no minimo 8 caracteres';
            }
        } else {
            confirma_senha.style.border = 'solid 1px red';
            senha.style.border = 'solid 1px red';
            document.getElementById('error').style.display = 'block';
            error = 'As senhas nao conferem';
        }

        return error;
    }

    function validName() {
        var error = '-1';
        elemento = document.getElementById('nome');
        if (elemento.value === '') {
            error = ('O campo nome é obrigatorio');
            elemento.style.border = 'solid 1px red';
        } else {
            error = '-1';
            elemento.style.border = '';
        }

        return error;
    }

    function validForm() {
        var erros = new Array();

        if (validName() != -1) {
            erros.push(validName());
        }

        if (validPass() != -1) {
            erros.push(validPass());
        }

        if (erros.length === 0) {
            $('#btn-save').attr('type', 'submit');
        } else {
            document.getElementById('error').innerHTML = (erros.toString());
            document.getElementById('error').style.display = 'block';
        }

    }

    function fechaTodasDivs() {
        $('.user-index').css('display', 'none');
        $('.user-cadastro').css('display', 'none');
        $('.user-listagem').css('display', 'none');
    }

    function abrirCadastro() {
        fechaTodasDivs();
        $('.user-cadastro').css('display', 'block');
    }

    function abrirListagem() {
        fechaTodasDivs();
        $('.user-listagem').css('display', 'block');
    }

</script>

