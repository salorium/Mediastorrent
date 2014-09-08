<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 25/04/14
 * Time: 23:31
 */

namespace model\simple;


class Mail extends \core\Model
{
    static function envoi($destinataire, $objet, $content)
    {
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: no-reply@' . $_SERVER["HTTP_HOST"] . '' . "\r\n";
        return mail($destinataire, $objet, $content, $headers);
    }

    static function activationMotDePasse($mail, $login, $mdp, $ticket)
    {
        $message = '
     <html>
      <head>
       <title>Activation du nouveau mot de passe</title>
      </head>
      <body>
       <p>Bonjour ' . $login . ',<br><br>Vous venez de faire une demande de réinitialisation de mot de passe, pour activer ce nouveau mot de passe vous devez cliquer sur le liens en dessous.<br>Si vous n\'avez pas éffectué de réinistialisation de mot de passe, merci de ne pas prendre en compte ce mail.</p>
       <table>
        <tr>
         <td>Votre nouveau mot de passe :</td><td>' . $mdp . '</td>
        </tr>
        </table>
        <a href="' . \core\Router::url("ticket/traite/" . $ticket) . '">Activer le mot de passe</a>
      </body>
     </html>
     ';
        return Mail::envoi($mail, "[Mot de passe] Réinitialisation", $message);
    }

    static function creationCompte($mail, $login, $mdp)
    {
        $message = '
     <html>
      <head>
       <title>Bienvenue sur ' . \config\Conf::$nomdusite . '</title>
      </head>
      <body>
      <h3>Bienvenue sur ' . \config\Conf::$nomdusite . '</h3>
       <table>
        <tr>
         <td>Votre login :</td><td>' . $login . '</td>
        </tr>
        <tr>
         <td>Votre mot de passe :</td><td>' . $mdp . '</td>
        </tr>
        </table>
      </body>
     </html>
     ';
        return Mail::envoi($mail, "Bienvenue sur " . \config\Conf::$nomdusite, $message);
    }

    static function infosSysopErreurAdjRtorrent($mail, $data)
    {
        $message = '
     <html>
      <head>
       <title>Erreur lors de l\'ajout d\'un rtorrent </title>
      </head>
      <body>
       <table>
        ';
        foreach ($data["system"] as $v) {
            $message .= '<tr><td>' . $v[0] . '</td><td>' . implode("<br>", $v[1]) . '</td><td>' . $v[2] . '</td></tr>';
        }
        if (isset($data["rtorrentsadj"]))
            $message .= '</table> rtorrentsadj : ' . $data["rtorrentsadj"] . '</td></tr>';
        $message .= '   </table>
      </body>
     </html>
     ';
        return Mail::envoi($mail, "[ERREUR] AddTorrent", $message);
    }
} 