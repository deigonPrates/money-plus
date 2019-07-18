<?php
$sql_despesa = "SELECT * FROM despesas";
$obj_despesas = mysqli_query($conexao, $sql_despesa) or die(mysqli_error($conexao));

$sql_gastos = "SELECT * FROM gastos ";
$obj_gastos = mysqli_query($conexao, $sql_gastos);

if (($_POST['despesas_codigo']) and ($_POST['data'])
    and ($_POST['descricao']) and ($_POST['valor']) and ($_POST['mes'])) {

    $erros = array();

    if ($_POST['codigo']) {
        $sql_update = "update gastos set despesas_codigo = '{$_POST['despesas_codigo']}', 
                                         data = '{$_POST['data']}', 
                                         descricao = '{$_POST['descricao']}', 
                                         valor = '{$_POST['valor']}', 
                                         mes = '{$_POST['mes']}'
                                         where codigo = {$_POST['codigo']}";
        mysqli_query($conexao, $sql_update) or die('Erro ao editar:' . $sql_update);
    } else {
        $sql_insert = "insert into gastos(usuarios_codigo,despesas_codigo,data,descricao,valor,mes) 
        value('{$_SESSION['logado']->codigo}','{$_POST['despesas_codigo']}','{$_POST['data']}'
        ,'{$_POST['descricao']}','{$_POST['valor']}','{$_POST['mes']}')";
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
    $sql_gastos = "SELECT * FROM gastos WHERE codigo = '{$_GET['id']}'";
    $obj_gastos = mysqli_query($conexao, $sql_gastos);
    $gasto = $obj_gastos->fetch_object();
}

function formatDate($data){
    $result = explode('-', $data);

    return $result[2].'/'.$result[1].'/'.$result[0];
}
?>
<div class="navega">
    <ul>
        <li><a href="<?= URL_SITE ?>inicio">Inicio</a></li>
        <li><a href="<?= URL_SITE ?>diversos/gasto">Gasto</a></li>
    </ul>
</div>
<div class="user-index" <?= ($_GET['id']) ? " style='display:none'" : ' ' ?>>
    <button class="btn-verde" onclick="abrirCadastro()">Cadastrar</button>
    <button class="btn-azul" onclick="abrirListagem()">Listar</button>
</div>
<div class="user-cadastro" <?= ($_GET['id']) ? " style='display:block'" : '' ?>>

    <form action="<?= URL_SITE ?>diversos/gasto" method="post" class="form">
        <?php
        if ($_GET['id']) {
            echo "<input type='hidden' value='{$_GET['id']}' name='codigo'>";
        }
        ?>
        <select name="despesas_codigo" id="despesas_codigo" required>
            <option value=''>Selecione uma despesa</option>
            <?php while ($despesa = $obj_despesas->fetch_object()) { ?>
                <option value='<?= $despesa->codigo ?>' <? if(($gasto->despesas_codigo) and ($despesa->codigo)){ echo 'selected';}?>><?= $despesa->nome ?></option>
            <?php } ?>

        </select>
        <input type="date" name="data" id="data" value="<? if($gasto->data){ echo $gasto->data;}else{ echo DATE('Y-m-d');}?>" required>
        <input type="text" name="descricao" id="descricao" value="<?= $gasto->descricao ?>" placeholder="Descrição"
               required>
        <input type="text" name="valor" id="valor" value="<?= $gasto->valor ?>" placeholder="R$ 0,00">
        <input type="number" name="mes" id="mes" value="<? if($gasto->mes){ echo $gasto->mes;}else{ echo DATE('m');}?>">
        <label id="error"></label>
        <button type="button" onclick="validForm()"
                id="btn-save">  <?= ($_GET['id']) ? " Salvar" : 'Cadastrar' ?></button>
    </form>
</div>
<div class="user-listagem" <?= ($_GET['id']) ? " style='display:none'" : '' ?>>
    <table id="customers">
        <tr>
            <th>#</th>
            <th>Data</th>
            <th>Descricao</th>
            <th>Valor</th>
            <th>Ação</th>
        </tr>
        <?php $cont = 1; ?>
        <?php while ($gasto = $obj_gastos->fetch_object()) { ?>
            <tr>
                <td><?= $cont ?></td>
                <td><?= formatDate($gasto->data) ?></td>
                <td><?= $gasto->descricao ?></td>
                <td><?= 'R$ '.number_format($gasto->valor,2,',','.') ?></td>
                <td><a href="<?= URL_SITE ?>diversos/gasto/edt?id=<?= $gasto->codigo ?>">Editar</a></td>
            </tr>
            <? $cont++; ?>
        <? } ?>
    </table>
</div>


<script>
    function validDespesa() {
        var error = '-1';
        elemento = document.getElementById('despesas_codigo');
        if (elemento.value === '') {
            error = (' O campo Despesa é obrigatorio');
            elemento.style.border = 'solid 1px red';
        } else {
            error = '-1';
            elemento.style.border = '';
        }

        return error;
    }

    function validData() {
        var error = '-1';
        elemento = document.getElementById('data');
        if (elemento.value === '') {
            error = (' O campo Data é obrigatorio');
            elemento.style.border = 'solid 1px red';
        } else {
            error = '-1';
            elemento.style.border = '';
        }

        return error;
    }

    function validDescricao() {
        var error = '-1';
        elemento = document.getElementById('descricao');
        if (elemento.value === '') {
            error = (' O campo Descrição é obrigatorio');
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

    function validMes() {
        var error = '-1';
        elemento = document.getElementById('mes');
        if (elemento.value === '') {
            error = (' O campo Mes é obrigatorio');
            elemento.style.border = 'solid 1px red';
        } else {
            error = '-1';
            elemento.style.border = '';
        }

        return error;
    }

    function validForm() {
        var erros = new Array();

        if (validDespesa() != -1) {
            erros.push(validDespesa());
        }
        if (validData() != -1) {
            erros.push(validData());
        }
        if (validDescricao() != -1) {
            erros.push(validDescricao());
        }
        if (validValor() != -1) {
            erros.push(validValor());
        }
        if (validMes() != -1) {
            erros.push(validMes());
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

