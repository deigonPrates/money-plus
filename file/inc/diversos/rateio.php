<?
$sql_gasto = "SELECT * FROM gastos";
$obj_gastos = mysqli_query($conexao, $sql_gasto) or die(mysqli_error($conexao));

$sql_parceiro = "SELECT * FROM parceiros";
$obj_parceiros = mysqli_query($conexao, $sql_parceiro) or die(mysqli_error($conexao));

$sql_rateios = "select T2.descricao, T3.nome, T1.valor,T1.status from rateios as T1
join gastos as T2 on T1.gastos_codigo = T2.codigo
join parceiros as T3 on T1.parceiros_codigo = T3.codigo";
$obj_rateios = mysqli_query($conexao, $sql_rateios) or die(mysqli_error($conexao));




if (($_POST['gastos_codigo']) and ($_POST['parceiros_codigo'])
    and ($_POST['valor']) and ($_POST['status'])) {

    $erros = array();

    if ($_POST['codigo']) {
        $sql_update = "update rateios set gastos_codigo = '{$_POST['gastos_codigo']}', 
                                         parceiros_codigo = '{$_POST['parceiros_codigo']}', 
                                         valor = '{$_POST['valor']}', 
                                         status = '{$_POST['status']}'
                                         where codigo = {$_POST['codigo']}";
        mysqli_query($conexao, $sql_update) or die('Erro ao editar:' . $sql_update);
    } else {
        $sql_insert = "insert into rateios(usuarios_codigo,gastos_codigo,parceiros_codigo,valor,status) 
        value('{$_SESSION['logado']->codigo}','{$_POST['gastos_codigo']}','{$_POST['parceiros_codigo']}'
        ,'{$_POST['valor']}','{$_POST['status']}')";
        mysqli_query($conexao, $sql_insert) or die('Erro ao salvar:' . $sql_insert);
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

if ($_GET['id']) {
    $sql_rateios = "SELECT * FROM rateios WHERE codigo = '{$_GET['id']}'";
    $obj_rateios = mysqli_query($conexao, $sql_rateios);
    $rateio = $obj_rateios->fetch_object();
}

function formatDate($data){
    $result = explode('-', $data);

    return $result[2].'/'.$result[1].'/'.$result[0];
}
?>
<div class="navega">
    <ul>
        <li><a href="<?= URL_SITE ?>inicio">Inicio</a></li>
        <li><a href="<?= URL_SITE ?>diversos/rateio">Rateio</a></li>
    </ul>
</div>
<div class="user-index" <?= ($_GET['id']) ? " style='display:none'" : ' ' ?>>
    <button class="btn-verde" onclick="abrirCadastro()">Cadastrar</button>
    <button class="btn-azul" onclick="abrirListagem()">Listar</button>
</div>
<div class="user-cadastro" <?= ($_GET['id']) ? " style='display:block'" : '' ?>>

    <form action="<?= URL_SITE ?>diversos/rateio" method="post" class="form">
        <?php
        if ($_GET['id']) {
            echo "<input type='hidden' value='{$_GET['id']}' name='codigo'>";
        }
        ?>
        <select name="gastos_codigo" id="gastos_codigo" required>
            <option value=''>Selecione um gasto</option>
            <? while ($gasto = $obj_gastos->fetch_object()) { ?>
                <option value='<?= $gasto->codigo ?>' <? if(($reteio->gastos_codigo) and ($gasto->codigo == $reteio->gastos_codigo)){ echo 'selected';}?>><?= $gasto->descricao ?></option>
            <? } ?>

        </select>
        <select name="parceiros_codigo" id="parceiros_codigo" required>
            <option value=''>Selecione um parceiro</option>
            <? while ($parceiro = $obj_parceiros->fetch_object()) { ?>
                <option value='<?= $parceiro->codigo ?>' <? if(($reteio->parceiros_codigo) and ($parceiro->codigo == $reteio->parceiros_codigo)){ echo 'selected';}?>><?= $parceiro->nome ?></option>
            <? } ?>

        </select>
        <select name="status" id="status" required>
            <option value='0'>Pendente</option>
            <option value='1'>Pago</option>
        </select>
        <input type="text" name="valor" id="valor" value="<?= $gasto->valor ?>" placeholder="R$ 0,00">
        <label id="error"></label>
        <button type="button" onclick="validForm()"
                id="btn-save">  <?= ($_GET['id']) ? " Salvar" : 'Cadastrar' ?></button>
    </form>
</div>
<div class="user-listagem" <?= ($_GET['id']) ? " style='display:none'" : '' ?>>
    <table id="customers">
        <tr>
            <th>#</th>
            <th>Gasto</th>
            <th>Parceiro</th>
            <th>Valor</th>
            <th>Status</th>
            <th>Ação</th>
        </tr>
        <? $cont = 1; ?>
        <? while ($rateio = $obj_rateios->fetch_object()) { ?>
            <tr>
                <td><?= $cont ?></td>
                <td><?= $rateio->descricao ?></td>
                <td><?= $rateio->nome ?></td>
                <td><?= 'R$ '.number_format($rateio->valor,2,',','.') ?></td>
                <td><?= ($rateio->parceiro== 0) ?'Pendente' : 'Pago'?></td>
                <td><a href="<?= URL_SITE ?>diversos/rateio/edt?id=<?= $gasto->codigo ?>">Editar</a></td>
            </tr>
            <? $cont++; ?>
        <? } ?>
    </table>
</div>


<script>
    function validGasto() {
        var error = '-1';
        elemento = document.getElementById('gastos_codigo');
        if (elemento.value === '') {
            error = (' O campo Gastos é obrigatorio');
            elemento.style.border = 'solid 1px red';
        } else {
            error = '-1';
            elemento.style.border = '';
        }

        return error;
    }

    function validParceiro() {
        var error = '-1';
        elemento = document.getElementById('parceiros_codigo');
        if (elemento.value === '') {
            error = (' O campo Parceiro é obrigatorio');
            elemento.style.border = 'solid 1px red';
        } else {
            error = '-1';
            elemento.style.border = '';
        }

        return error;
    }

    function validStatus() {
        var error = '-1';
        elemento = document.getElementById('status');
        if (elemento.value === '') {
            error = (' O campo Status é obrigatorio');
            elemento.style.border = 'solid 1px red';
        } else {
            error = '-1';
            elemento.style.border = '';
        }

        return error;
    }

    function validValor() {
        var error = '-1';
        elemento = document.getElementById('valor');
        if (elemento.value === '') {
            error = (' O campo Valor é obrigatorio');
            elemento.style.border = 'solid 1px red';
        } else {
            error = '-1';
            elemento.style.border = '';
        }

        return error;
    }

    function validForm() {
        var erros = new Array();

        if (validGasto() != -1) {
            erros.push(validGasto());
        }
        if (validParceiro() != -1) {
            erros.push(validParceiro());
        }
        if (validStatus() != -1) {
            erros.push(validStatus());
        }
        if (validValor() != -1) {
            erros.push(validValor());
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

