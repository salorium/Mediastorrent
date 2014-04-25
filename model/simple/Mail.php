<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 25/04/14
 * Time: 23:31
 */

namespace model\simple;


class Mail extends \core\Model {
    static function envoi($destinataire,$objet, $content){
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: no-reply@'.$_SERVER["HTTP_HOST"].'' . "\r\n";
        return mail($destinataire, $objet, $content, $headers);
    }

    static function activationMotDePasse($mdp,$login,$mail){
        $message = '
     <html>
      <head>
       <title>Activation du nouveau mot de passe</title>
      </head>
      <body>
       <p>Bonjour '.$login.', vous venez de faire une demande de réinitialisation de mot de passe, pour activer ce nouveau mot de passe vous devez cliquer sur le liens en dessous.<br><span style="color:red;"> Si vous n\'avez pas éffectué de réinistialisation de mot de passe, merci de ne pas prendre en compte ce mail.</span></p>
       <table>
        <tr>
         <td>Mot de passe :</td><td>'.$mdp.'</td>
        </tr>
        </table>
      </body>
     </html>
     ';
        return Mail::envoi($mail,"[Mot de passe] Réinitialisation",$message);
    }

} 