<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 12/03/14
 * Time: 13:28
 */


if (isset ($erreur)){
    ?>
    <div data-alert class="alert-box warning radius connexion">
        Mail invalide
        <a href="#" class="close">&times;</a>
    </div>
<?php
}
?>

<div class="connexion">
    <form data-abide class="custom" action="<?=\core\Router::url("utilisateur/mdpoublier")?>" method="POST">
        <fieldset>
            <legend>Récupération du mot de passe</legend>
            <div class="row">
                <div class="columns">
                    <label>Mail <small>obligatoire</small>
                        <input name="mail" type="text" required pattern="email"/>
                    </label>
                    <small class="error">L' e-mail est obligatoire !</small>
                </div>
            </div>
            <div class="row">
                <div class="columns">
                    <ul class="button-group round">
                        <li>    <button class="button small secondary" value="Reinitialisation" type="submit">Réinitialisation</button></li>
                    </ul>
                </div>
            </div>
        </fieldset>
    </form>
</div>
