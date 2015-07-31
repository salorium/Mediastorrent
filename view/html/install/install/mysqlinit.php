<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 05/05/14
 * Time: 03:29
 */
if (!isset($res)) {
    ?>

    <form data-abide class="custom" method="post">
        <table>
            <caption>Création du compte Sysop</caption>
            <tr>
                <td><label>Login :
                        <small>obligatoire</small>
                    </label></td>
                <td>
                    <input name="login" type="text" required pattern="alpha_numeric"/>
                </td>
            </tr>
            <tr>
                <td><label>Mot de passe :
                        <small>obligatoire</small>
                    </label></td>
                <td>
                    <input name="pass" type="password" required pattern="password"/>
                    <small class="error">Le mot de passe est obligatoire ! (Au moins 8 caractères avec une lettre
                        majuscule, un chiffre / un caractère spécial.)
                    </small>
                </td>
            </tr>
            <tr>
                <td><label>Adresse mail :
                        <small>obligatoire</small>
                    </label></td>
                <td>
                    <input name="mail" type="email" required pattern="email"/>
                    <small class="error">Une adresse mail est requise.</small>
                </td>
            </tr>
        </table>
        <button class="secondary" type="submit">Ajouter ce compte Sysop</button>
    </form>
<?
} else {
    if ($res) {
        ?>
            <div data-alert class="alert-box success radius connexion">
                Enregistrement du compte sysop fait !
                <a href="#" class="close">&times;</a>
            </div>

    <?
    } else {
        ?>
            <div data-alert class="alert-box alert radius connexion">
                Erreur lors du enregistrement du compte sysop !
                <a href="#" class="close">&times;</a>
            </div>

    <?
    }

    \core\LoaderJavascript::add("base", "controller.redirection", \core\Router::url(""));
} ?>