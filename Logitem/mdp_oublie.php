<?php
session_start();

$connect=new PDO("mysql:host=localhost;dbname=EIMOL","root","");

if(isset($_GET['id']) AND $_GET['id']>0 AND !empty($_SESSION)){ 
		$requete=$connect->prepare('SELECT * FROM utilisateur WHERE id=?');
		$requete->execute(array($_SESSION['id']));
		$user=$requete->fetch();
		
  		$header="MIME-version: 1.0\r\n";
        $header.='From:"Tous En Musique.com"<support@OLIALIMA.com>'."\n";
        $header.='content-Type:text/html;charset="utf-8"'."\n";
        $header.='content-Transfer-Encoding: 8bit';
        $message='
        <html>
          <head>
            <title>Tous En Musique-Récupération de mot de passe
            </title>
            <meta charset="utf-8">
          </head>
          <body>
            <font color="#303030";>
              <div align="center">
                <table width="600px">
                  <tr>
                    <td background="http://localhost/Logitem/css/img/P1.png">
                  </tr>
                  <tr>
                    <td>
                      <br>
                      <div align="center">
                        Bonjour <b>'.$user['login'].'</b>,
                      </div><br>
                      Voici votre mot de passe <b>'.$user['password'].'</b>
						<br><br><br><br><br>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <hr color="blue">
                    </td>
                  </tr>
                  <tr>
                    <td align="center">
                      <font size="2">
                        Ceci est un email automatique, merci de ne pas y répondre!
                      </font>
                    </td>
                  </tr>
                </table>
              </div>
            </font>
          </body>
        </html>';
                mail($user['email'],'Récupération de mot de passe',$message,$header);

                header("Location:http://localhost/Logitem/connexion_2.php?erreur=1");

}else{
	$_SESSION=array();
	session_destroy();
	header("location: http://localhost/Logitem/connexion.php");
}

?>