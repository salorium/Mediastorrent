<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 05/05/14
 * Time: 03:29
 */
if (!isset($res)) {
    ?>

    <form method="post">
        <table>
            <caption>Identifiant de connexion à mysql</caption>
            <tr>
                <td>Host :</td>
                <td>
                    <input name="hostmysql" type="text"/>
                </td>
            </tr>
            <tr>
                <td>Login :</td>
                <td>
                    <input name="loginmysql" type="text"/>
                </td>
            </tr>
            <tr>
                <td>Mot de passe :</td>
                <td>
                    <input name="passmysql" type="password"/>

                </td>
            </tr>
        </table>

        <button class="secondary" type="submit">Initialiser la base de données</button>
    </form>
<? } else if (isset($res)) { ?>
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
<? } ?>