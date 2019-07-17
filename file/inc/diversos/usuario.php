

<form action="<?= URL_SITE?>diversos/usuario" method="post" class="form">
    <input type="text" name="nome" id="nome" placeholder="Nome de usuário" required>
    <input type="password" name="senha" id="senha" onblur="validPass()" placeholder="Senha" required>
    <input type="password" name="confirmacao_senha" id="confirmacao_senha" onblur="validPass()" placeholder="Repita a senha" required>
    <label id="error">As senhas não conferem</label>
    <button type="button" onclick="validForm()" id="btn-save"> Cadastrar</button>
</form>


<script>
    function validPass() {
        var error = '-1';
        var senha = document.getElementById('senha');
        var confirma_senha = document.getElementById('confirmacao_senha');

        if((confirma_senha.value === senha.value) || (confirma_senha.value === '')){
            confirma_senha.style.border = '';
            senha.style.border = '';
            document.getElementById('error').style.display = 'none';
            error = '-1';

            if(confirma_senha.value.length >= 8){
                confirma_senha.style.border = 'solid 1px red';
                senha.style.border = 'solid 1px red';
                document.getElementById('error').style.display = 'block';
                error = 'A senha deve ter no minimo 8 caracteres';
            }
        }else{
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
        if(elemento.value === ''){
            error = ('O campo nome é obrigatorio');
            elemento.style.border = 'solid 1px red';
        }else{
            error='-1';
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

         if(erros.length === 0){
             $('#btn-save').attr('type', 'submit');
         }else{
             document.getElementById('error').innerHTML = (erros.toString());
             document.getElementById('error').style.display = 'block';
         }

    }
    
    
    
</script>

<?

?>
