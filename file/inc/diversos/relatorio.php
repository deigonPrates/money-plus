<?php
$sql_gasto = "SELECT * FROM gastos";
$obj_gastos = mysqli_query($conexao, $sql_gasto) or die(mysqli_error($conexao));

$sql_parceiro = "SELECT * FROM parceiros";
$obj_parceiros = mysqli_query($conexao, $sql_parceiro) or die(mysqli_error($conexao));

$sql_despesa = "SELECT * FROM despesas";
$obj_despesas = mysqli_query($conexao, $sql_despesa) or die(mysqli_error($conexao));


$sql_consulta = "select sum(T1.valor) as total from rateios as T1
                        join parceiros as T2 on T1.parceiros_codigo = T2.codigo
                        join gastos as T3 on T1.gastos_codigo = T3.codigo
                        join despesas as T4 on T3.despesas_codigo = T4.codigo
                        where T1.codigo is not null";

if (isset($_POST['search'])) {
    if (!empty($_POST['parceiro'])) {
        $sql_consulta .= " and T2.codigo = '{$_POST['parceiro']}'";
    }
    if (!empty($_POST['gasto'])) {
        $sql_consulta .= " and T3.codigo = '{$_POST['gasto']}'";
    }
    if (!empty($_POST['despesa'])) {
        $sql_consulta .= " and T4.codigo = '{$_POST['despesa']}'";
    }
    if (!empty($_POST['mes'])) {
        $sql_consulta .= " and T3.mes = '{$_POST['mes']}'";
    }

    $obj_consulta = mysqli_query($conexao, $sql_consulta) or die(mysqli_error($conexao));
    $consulta = $obj_consulta->fetch_object();
}

function formatDate($data)
{
    $result = explode('-', $data);

    return $result[2] . '/' . $result[1] . '/' . $result[0];
}

?>
<div class="navega">
    <ul>
        <li><a href="<?php echo URL_SITE; ?>inicio">Inicio</a></li>
        <li><a href="<?php echo URL_SITE; ?>diversos/relatorio">Relatorio</a></li>
    </ul>
</div>
<div class="user-index" <?php echo isset($_GET['id']) ? " style='display:none'" : ' '; ?>>
    <form action="<?php echo URL_SITE;?>diversos/relatorio" method="post">
        <input type="hidden" name="search" value="true">
        <select name="parceiro" id="parceiro">
            <option value=''>Selecione um parceiro</option>
            <?php while ($parceiro = $obj_parceiros->fetch_object()) { ?>
                <option value='<?php echo $parceiro->codigo; ?>'
                    <?php if (isset($_POST['parceiro']) and ($parceiro->codigo == $_POST['parceiro'])) {
                    echo 'selected';
                } ?>><?php echo $parceiro->nome; ?></option>
            <?php } ?>
        </select>
        <select name="gasto" id="gastos">
            <option value=''>Selecione um gasto</option>
            <?php while ($gasto = $obj_gastos->fetch_object()) { ?>
                <option value='<?php echo $gasto->codigo; ?>' <?php if (isset($_POST['gasto']) and ($_POST['gasto'] == $gasto->codigo)) {
                    echo 'selected';
                } ?>><?php echo $gasto->descricao; ?></option>
            <?php } ?>
        </select>
        <select name="despesa" id="despesa">
            <option value=''>Selecione uma despesa</option>
            <?php while ($despesa = $obj_despesas->fetch_object()) { ?>
                <option value='<?php echo $despesa->codigo; ?>'
                    <?php if (isset($_POST['despesa']) and ($_POST['despesa'] == $despesa->codigo)) {
                    echo 'selected';
                } ?>><?php echo $despesa->nome; ?></option>
            <?php } ?>
        </select>
        <select name="mes" id="mes">
            <option value=''>Selecione um mes</option>
            <option value='01' <?php if (isset($_POST['mes']) and ($_POST['mes'] == '01')) {
                    echo 'selected';} ?>>01 - Janeiro</option>
            <option value='02' <?php if (isset($_POST['mes']) and ($_POST['mes'] == '02')) {
                    echo 'selected';} ?>>02 - Fevereiro</option>
            <option value='03' <?php if (isset($_POST['mes']) and ($_POST['mes'] == '03')) {
                    echo 'selected';} ?>>03 - Março</option>
            <option value='04' <?php if (isset($_POST['mes']) and ($_POST['mes'] == '04')) {
                    echo 'selected';} ?>>04 - Abril</option>
            <option value='05' <?php if (isset($_POST['mes']) and ($_POST['mes'] == '05')) {
                    echo 'selected';} ?>>05 - Maio</option>
            <option value='06' <?php if (isset($_POST['mes']) and ($_POST['mes'] == '06')) {
                    echo 'selected';} ?>>06 - Junho</option>
            <option value='07' <?php if (isset($_POST['mes']) and ($_POST['mes'] == '07')) {
                    echo 'selected';} ?>>07 - Julho</option>
            <option value='08' <?php if (isset($_POST['mes']) and ($_POST['mes'] == '08')) {
                    echo 'selected';} ?>>08 - Agosto</option>
            <option value='09' <?php if (isset($_POST['mes']) and ($_POST['mes'] == '09')) {
                    echo 'selected';} ?>>09 - Setembro</option>
            <option value='10' <?php if (isset($_POST['mes']) and ($_POST['mes'] == '10')) {
                    echo 'selected';} ?>>10 - Outubro</option>
            <option value='11' <?php if (isset($_POST['mes']) and ($_POST['mes'] == '11')) {
                    echo 'selected';} ?>>11 - Novembro</option>
            <option value='12' <?php if (isset($_POST['mes']) and ($_POST['mes'] == '12')) {
                    echo 'selected';} ?>>12 - Dezembro</option>
        </select>
        <?php
        if (isset($consulta->total)) {
            echo '<label id="info">R$ '.number_format($consulta->total,2,',','.').'</label>';
        }
        ?>
        <button class="btn-azul" type="submit">Visualizar</button>
    </form>
</div>


