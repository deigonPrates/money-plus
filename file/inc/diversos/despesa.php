<?php
if (isset($_POST['nome']) and isset($_POST['vencimento'])) {

    $erros = array();

    if (isset($_POST['codigo'])) {
        $sql_update = "update despesas set nome = '{$_POST['nome']}' , vencimento = '{$_POST['vencimento']}' where codigo = {$_POST['codigo']}";
        mysqli_query($conexao, $sql_update) or die('Erro ao editar:' . $sql_update);

    } else {
        if (!existe_nome($_POST['nome'], $conexao)) {
            $sql_insert = "insert into despesas(nome,vencimento) value('{$_POST['nome']}','{$_POST['vencimento']}')";
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


$sql_despesa = "SELECT * FROM despesas limit 5";
$obj_despesas = mysqli_query($conexao, $sql_despesa) or die(mysqli_error($conexao));


if (isset($_GET['id'])) {
    $sql_despesa = "SELECT * FROM despesas WHERE codigo = '{$_GET['id']}'";
    $obj_despesas = mysqli_query($conexao, $sql_despesa) or die(mysqli_error($conexao));
    $despesa = $obj_despesas->fetch_object();
}

/**
 * @param $nome
 * @return bool
 */
function existe_nome($nome, $conexao)
{
    $sql_nome = "select * from despesas where nome = '$nome'";
    $obj_banco = mysqli_query($conexao, $sql_nome) or die(mysqli_error($conexao));
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
        <li><a href="<?php echo URL_SITE; ?>inicio">Inicio</a></li>
        <li><a href="<?php echo URL_SITE; ?>diversos/despesa">Despesas</a></li>
    </ul>
</div>
<div class="user-index" <?php echo isset($_GET['id']) ? " style='display:none'" : ' '; ?>>
    <button class="btn-verde" onclick="abrirCadastro()">Cadastrar</button>
    <button class="btn-azul" onclick="abrirListagem()">Listar</button>
</div>

<div class="user-cadastro" <?php echo isset($_GET['id']) ? " style='display:block'" : ''; ?>>

    <form action="<?php echo URL_SITE; ?>diversos/despesa" method="post" class="form">
        <?php
        if (isset($_GET['id'])) {
            echo "<input type='hidden' value='{$_GET['id']}' name='codigo'>";
        }
        ?>
        <input type="text" name="nome" id="nome" value="<?php echo @$despesa->nome; ?>" placeholder="Nome" required>
        <input type="number" name="vencimento" value="<?php echo @$despesa->vencimento; ?>" id="vencimento" placeholder="01">
        <label id="error"></label>
        <button type="button" onclick="validForm()"
                id="btn-save">  <?php echo isset($_GET['id']) ? " Salvar" : 'Cadastrar'; ?></button>
    </form>
</div>
<div class="user-listagem" <?php echo ($_GET['id']) ? " style='display:none'" : ''; ?>>
    <table id="customers">
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Vencimento</th>
            <th>Ação</th>
        </tr>

        <?php $cont = 1; ?>
        <?php while ($despesa = $obj_despesas->fetch_object()) { ?>
            <tr>
                <td><?php echo $cont; ?></td>
                <td><?php echo $despesa->nome; ?></td>
                <td><?php echo $despesa->vencimento; ?></td>
                <td><a href="<?php echo URL_SITE; ?>diversos/despesa/edt?id=<?php echo $despesa->codigo; ?>">Editar</a></td>
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

