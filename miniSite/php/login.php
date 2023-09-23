<h3>Login</h3>

<?php

if ( isset( $_POST[ 'rec_btn' ] ) ) {

    if($_POST[ 'login' ] == $_POST[ 'pw' ]){
        // ok logué !
        $_SESSION['login'] = true;
        header('Location: index.php?numPage=2');
    } else {
        // pas logué.
        $_SESSION['login'] = false;
        header('Location: index.php?numPage=4');
    }
}
?>

<form method="post" action="" class='inscription' enctype = 'multipart/form-data'>
    <p>
        <label>Login</label>
        <input type="text" name="login" value="" />
    </p>
    <p>
        <label>Mot de passe</label>
        <input type="password" name="pw" value="" />
    </p>
    <p>
        <label></label>
        <input type='submit' name='rec_btn' value='Envoyer' />
    </p>
</form>

