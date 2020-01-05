<?php

session_start();

$connect=new PDO("mysql:host=localhost;dbname=EIMOL","root","");

if(isset($_GET['id']) AND $_GET['id']>0 AND isset($_GET['pv']) AND $_GET['pv']==$_SESSION['habilitation'] AND !empty($_SESSION)){
$msg="";
if(isset($_POST['submit'])){
	$tmp1=trim($_POST['nom']);
	$tmp2=trim($_POST['prenom']);
	$tmp3=trim($_POST['login']);
	$tmp4=trim($_POST['password']);
	$tmp5=trim($_POST['confirmpassword']);
	$tmp6=trim($_POST['tel']);
	$tmp7=trim($_POST['email']);
	$tmp8=trim($_POST['confirmemail']);
	if(!empty($tmp1) AND !empty($tmp2) AND !empty($tmp3) AND !empty($tmp4) AND !empty($tmp5) AND !empty($tmp6) AND !empty($tmp7) AND !empty($tmp8)){
		if(is_numeric($tmp6)){

			$nom=htmlspecialchars($_POST['nom']);
			$prenom=htmlspecialchars($_POST['prenom']);
			$login=htmlspecialchars($_POST['login']);
			$password=htmlspecialchars($_POST['password']);
			$confirmpassword=htmlspecialchars($_POST['confirmpassword']);
			$tel=htmlspecialchars($_POST['tel']);
			$habilitation=htmlspecialchars($_POST['habilitation']);
			$email=htmlspecialchars($_POST['email']);
			$confirmemail=htmlspecialchars($_POST['confirmemail']);
		if(strlen($tel)==9){
		if($password==$confirmpassword){
		if($email==$confirmemail){
		if(filter_var($email,FILTER_VALIDATE_EMAIL)){

			$sql=$connect->prepare('SELECT login FROM utilisateur WHERE login=?');
			$sql->execute(array($login));
			
			$sql1=$sql->rowcount();
			if($sql1==0){
					$req=$connect->prepare('INSERT INTO utilisateur (nom,prenom,login,password,email,habilitation,tel,avatar) VALUES (?,?,?,?,?,?,?,?)');
					$req->execute(array($nom,$prenom,$login,$password,$email,$habilitation,$tel,'default.png'));
					$verifie=$connect->prepare('SELECT * FROM utilisateur WHERE login=?');
					$verifie->execute(array($login));
					$exist=$verifie->rowcount();
					if($exist==0){
						header("location: http://localhost/Logitem/Index.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&erreur=1");
					}else{
						header("location: http://localhost/Logitem/Index.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&erreur=2");
					}
					
			}else{
				$msg="Votre login n'est pas disponible... Veuillez en choisir un autre!!!";
			}
		}else{
			$msg="Votre adresse e-mail n\'est pas une adresse mail valide!!!";
		}
		}else{
			$msg="L\'adresse mail et la confirmation ne correspondent pas!!!";
		}
		}else{
			$msg="Le mot de passe et la confirmation ne correspondent pas!!!";
		}
		}else{
			$msg="Votre numéro de téléphone doit contenir 9 chiffres!!!";
		}
	}else{
		$msg="Votre numéro de téléphone n\'est pas valide!!!Veuillez éviter tous symboles de séparation";	
	}
	}else{
		$msg="Veuillez bien remplir tous les champs!!!";
	}
	
}

if(isset($_POST['back'])){

	header("location: http://localhost/Logitem/Index.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']);
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Inscription</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/style1.css">
	<link rel="shortcut icon" type="images/x-icon" href="css/img/favicon.ico" />
			<style type="text/css">
		.entete{
	width: 70%;
	height: 90%;
	margin-left: 15%;
	margin-right: 15%;
	background-image: url("css/img/M5.JPG");
	background-size: cover;
	border-radius: 15px;
	font-weight: bold;
	margin-top: 30px;
	 
}
	</style>
</head>
<body>
	<div class="entete"><div id="rev">Version 1.0</div><div class="titre">TOUS EN MUSIQUE</div>
		
		<div class="form" align="center" >
			<form method="POST" action="">
			<fieldset>
				<legend align="center"><div class="ins">INSCRIPTION</div></legend>
				<table>
					<tr>
						<td><label for="nom">Nom:</label></td><td><input type="text" name="nom" id="nom"></input></td>
					</tr>
					<tr>
						<td><label for="prenom">Prenom:</label></td><td><input type="text" name="prenom" id="prenom"></input></td>
					</tr>
					<tr>
						<td><label for="login">Login:</label></td><td><input type="text" name="login" id="login"></input></td>
					</tr>
					<tr>
						<td><label for="password">Mot de passe:</label></td><td><input type="password" name="password" id="password"></input></td>
					</tr>
					<tr>
						<td><label for="confirmpassword">Confirmer votre mot de passe:</label></td><td><input type="password" name="confirmpassword" id="confirmpassword"></input></td>
					</tr>
					<tr>
						<td><label for="mail">Adresse mail:</label></td><td><input type="email" name="email" id="mail"></input></td>
					</tr>
					<tr>
						<td><label for="confirmmail">Confirmer votre adresse mail:</label></td><td><input type="email" name="confirmemail" id="confirmmail"></input></td>
					</tr>
					<tr>
						<td><label for="tel">Numéro de téléphone:</label></td><td><input type="text" name="tel" id="tel"></input></td>
					</tr>
					<tr>
						<td><label for="habilitation">Habilitation:</label></td><td><select id="habilitation" name="habilitation"><option value="Administrateur">Administrateur</option><option value="Utilisateur">Utilisateur</option></select></td>
					</tr>
					<tr>
						<td align="right"><input type="submit" name="submit" value="INSCRIRE"></input></td><td><input type="reset" name="reset" value="EFFACER"></input></td>
					</tr>
					<tr>
						<td align="left"><input type="submit" name="back" value="ACCEUIL" style="width:90px"></input></td><td></td>
					</tr>
				</table>
			</fieldset>
			</form>
			<br><br><br>
			<?php 
			if(!empty($msg)){ 
			echo "<script type='text/javascript'>alert('".$msg."');</script>";
			}
			?>
		</div>
	</div>
	<div id="pied_de_page">Développée par ANOUMEDEM NGUEFACK GILLES CEDRIC; élève en classe de Terminale TI au LBY; Tel: 698-15-81-92; Email: nguefackgilles@gmail.com</div>
</body>
</html>
<?php
}else{
	header("location: http://localhost/Logitem/connexion.php");
}
?>