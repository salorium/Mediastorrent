<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 25/10/13
 * Time: 12:14
 * To change this template use File | Settings | File Templates.
 */

if ( isset($modifiermdp)){
    if ( $modifiermdp){
        ?>
        <div data-alert class="alert-box success radius connexion">
            Modification du mot de passe faite, vous pouvez vous connecter.
            <a href="#" class="close">&times;</a>
        </div>
    <?php
    }else{
        ?>
        <div data-alert class="alert-box warning radius connexion">
            Erreur lors de la modification du mot de passse.
            <a href="#" class="close">&times;</a>
        </div>
    <?php
    }
}


if ( isset($succereinitialmdp)){
    if ($succereinitialmdp){
    ?>
    <div data-alert class="alert-box success radius connexion">
        Modification du mot de passe faite, consulter vos e-mail pour plus d'information.
        <a href="#" class="close">&times;</a>
    </div>
<?php
    }else{
        ?>
        <div data-alert class="alert-box warning radius connexion">
            Erreur lors de la réinitialisation du mot de passe.
            <a href="#" class="close">&times;</a>
        </div>
    <?php
    }
}

if (isset ($erreur)){
    ?>
    <div data-alert class="alert-box warning radius connexion">
        Nom d'utilisateur/Mot de passse invalide
        <a href="#" class="close">&times;</a>
    </div>
<?php
}
?>
<div class="connexion">
    <form data-abide class="custom" action="<?=\core\Router::url("utilisateur/connexion")?>" method="POST">
        <fieldset>
            <legend>Connexion</legend>
            <div class="row">
                <div class="columns">
                    <label>Login <small>obligatoire</small>
                    <input name="login" type="text" required pattern="alpha_numeric" value="<?= (isset($login)== true ? $login:"") ?>"/>
                    </label>
                    <small class="error">Le login est obligatoire !</small>
                </div>
            </div>
            <div class="row">
                <div class="columns">
                    <label>Mot de passe <small>obligatoire</small>
                    <input name="motdepasse" type="password" required pattern="password"/>
                    </label>
                    <small class="error">Le mot de passe est obligatoire ! (Au moins 8 caractères avec une lettre majuscule, un chiffre / un caractère spécial.) </small>
                </div>
            </div>
            <div class="row">
                <div class="columns">
                    <ul class="button-group round">
                        <li>    <button class="button small secondary" value="Connexion" type="submit">Connexion</button></li>
                        <li>    <a class="button small" href="<?=\core\Router::url("utilisateur/mdpoublier")?>">Mot de passe oublié ?</a></li>


                    </ul>
                </div>
            </div>
        </fieldset>
    </form>
</div>