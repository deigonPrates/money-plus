<?php
if (($_POST['nome'])) {

    $erros = array();

    if ($_POST['codigo']) {
        $sql_insert = "update parceiros set nome = '{$_POST['nome']}' where codigo = {$_POST['codigo']}";
        mysqli_query($conexao, $sql_insert) or die('Erro ao salvar:' . $sql_insert);
    } else {
        if (!existe_nome($_POST['nome'], $conexao)) {
            $senha = hash('sha512', $_POST['senha']);
            $sql_insert = "insert into parceiros(nome, status) value('{$_POST['nome']}','1')";
            mysqli_query($conexao, $sql_insert) or die('Erro ao salvar:' . $sql_insert);
        } else {
            $erros[] = 'Ja existe esse nome salvo na nossa base, favor use outro';
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


$sql_parceiros = "SELECT * FROM parceiros limit 5";
$obj_parceiros = mysqli_query($conexao, $sql_parceiros);


if ($_GET['id']) {
    $sql_parceiros = "SELECT * FROM parceiros WHERE codigo = '{$_GET['id']}'";
    $obj_parceiros = mysqli_query($conexao, $sql_parceiros);
    $parceiro = $obj_parceiros->fetch_object();
}

/**
 * @param $nome
 * @return bool
 */
function existe_nome($nome, $conexao)
{
    $sql_nome = "select * from parceiros where nome = '$nome'";
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
        <li><a href="<?= URL_SITE ?>diversos/parceiro">Parceiros</a></li>
    </ul>
</div>
<div class="user-index" <?= ($_GET['id']) ? " style='display:none'" : ' ' ?>>
    <button class="btn-verde" onclick="abrirCadastro()">Cadastrar</button>
    <button class="btn-azul" onclick="abrirListagem()">Listar</button>
</div>

<div class="user-cadastro" <?= ($_GET['id']) ? " style='display:block'" : '' ?>>

    <form action="<?= URL_SITE ?>diversos/parceiro" method="post" class="form">
        <?php
        if ($_GET['id']) {
            echo "<input type='hidden' value='{$_GET['id']}' name='codigo'>";
        }
        ?>
        <input type="text" name="nome" id="nome" value="<?= $parceiro->nome ?>" placeholder="Nome de usuário" required>
        <label id="error"></label>
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

        <?php $cont = 1; ?>
        <?php while ($parceiro = $obj_parceiros->fetch_object()) { ?>
            <tr>
                <td><?= $cont ?></td>
                <td><?= $parceiro->nome ?></td>
                <td><?= ($parceiro->status == 1) ? 'Ativo' : 'Inativo' ?></td>
                <td><a href="<?= URL_SITE ?>diversos/parceiro/edt?id=<?= $parceiro->codigo ?>">Editar</a></td>
            </tr>
            <?php $cont++; ?>
        <?php } ?>
    </table>
</div>


<script>
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

