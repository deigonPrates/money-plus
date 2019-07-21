<?php
$sql_gasto = "SELECT * FROM gastos";
$obj_gastos = mysqli_query($conexao, $sql_gasto) or die(mysqli_error($conexao));

$sql_parceiro = "SELECT * FROM parceiros";
$obj_parceiros = mysqli_query($conexao, $sql_parceiro) or die(mysqli_error($conexao));

$sql_rateios = "select T2.descricao, T3.nome, T1.valor,T1.status from rateios as T1
join gastos as T2 on T1.gastos_codigo = T2.codigo
join parceiros as T3 on T1.parceiros_codigo = T3.codigo";
$obj_rateios = mysqli_query($conexao, $sql_rateios) or die(mysqli_error($conexao));




if (isset($_POST['gastos_codigo']) and isset($_POST['parceiros_codigo'])
    and isset($_POST['valor']) and isset($_POST['status'])) {

    $erros = array();

    if (isset($_POST['codigo'])) {
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

if (isset($_GET['id'])) {
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
        <li><a href="<?php echo URL_SITE; ?>inicio">Inicio</a></li>
        <li><a href="<?php echo URL_SITE; ?>diversos/rateio">Rateio</a></li>
    </ul>
</div>
<div class="user-index" <?php echo isset($_GET['id']) ? " style='display:none'" : ' '; ?>>
    <button class="btn-verde" onclick="abrirCadastro()">Cadastrar</button>
    <button class="btn-azul" onclick="abrirListagem()">Listar</button>
</div>
<div class="user-cadastro" <?php echo isset($_GET['id']) ? " style='display:block'" : ''; ?>>

    <form action="<?php echo URL_SITE; ?>diversos/rateio" method="post" class="form">
        <?php
        if (isset($_GET['id'])) {
            echo "<input type='hidden' value='{$_GET['id']}' name='codigo'>";
        }
        ?>
        <select name="gastos_codigo" id="gastos_codigo" required>
            <option value=''>Selecione um gasto</option>
            <?php while ($gasto = $obj_gastos->fetch_object()) { ?>
                <option value='<?php echo $gasto->codigo; ?>' <?php if(isset($rateio->gastos_codigo) and ($rateio->gastos_codigo == $gasto->codigo)){ echo 'selected';}?>><?php echo $gasto->descricao; ?></option>
            <?php } ?>

        </select>
        <select name="parceiros_codigo" id="parceiros_codigo" required>
            <option value=''>Selecione um parceiro</option>
            <?php while ($parceiro = $obj_parceiros->fetch_object()) { ?>
                <option value='<?php echo $parceiro->codigo; ?>' <?php if(isset($rateio->parceiros_codigo) and ($parceiro->codigo == $rateio->parceiros_codigo)){ echo 'selected';}?>><?php echo $parceiro->nome; ?></option>
            <?php } ?>

        </select>
        <select name="status" id="status" required>
            <option value='0' <?php if(isset($rateio->status) and ($rateio->status == 0)) echo 'selected' ?>>Pendente</option>
            <option value='1' <?php if(isset($rateio->status) and ($rateio->status == 1)) echo 'selected' ?>>Pago</option>
        </select>
        <input type="text" name="valor" id="valor" value="<?php echo @$gasto->valor; ?>" placeholder="R$ 0,00">
        <label id="error"></label>
        <button type="button" onclick="validForm()"
                id="btn-save">  <?php echo isset($_GET['id']) ? " Salvar" : 'Cadastrar'; ?></button>
    </form>
</div>
<div class="user-listagem" <?php echo isset($_GET['id']) ? " style='display:none'" : ''; ?>>
    <table id="customers">
        <tr>
            <th>#</th>
            <th>Gasto</th>
            <th>Parceiro</th>
            <th>Valor</th>
            <th>Status</th>
            <th>Ação</th>
        </tr>
        <?php $cont = 1; ?>
        <?php while ($rateio = $obj_rateios->fetch_object()) { ?>
            <tr>
                <td><?php echo $cont; ?></td>
                <td><?php echo $rateio->descricao; ?></td>
                <td><?php echo $rateio->nome; ?></td>
                <td><?php echo 'R$ '.number_format($rateio->valor,2,',','.'); ?></td>
                <td><?php echo ($rateio->status == 0) ?'Pendente' : 'Pago';?></td>
                <td><a href="<?php echo URL_SITE; ?>diversos/rateio/edt?id=<?php echo $gasto->codigo; ?>">Editar</a></td>
            </tr>
            <?php $cont++; ?>
        <?php } ?>
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

