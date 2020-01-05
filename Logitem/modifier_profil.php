<?php

session_start();

$connect=new PDO("mysql:host=localhost;dbname=eimol","root","");

if(isset($_GET['id']) AND $_GET['id']>0 AND isset($_GET['pv']) AND $_GET['pv']==$_SESSION['habilitation'] AND !empty($_SESSION)){
if(!isset($_GET['mode'])){
$msg="";
$requser=$connect->prepare('SELECT * FROM utilisateur WHERE id=?');
$requser->execute(array($_SESSION['id']));
$user=$requser->fetch();
if(isset($_POST['submit'])){
	if(empty(trim($_POST['newlogin'])) AND empty(trim($_POST['newpassword'])) AND empty(trim($_POST['newconfirmpassword'])) AND empty(trim($_POST['newtel']))){
		$msg="Veuillez remplir tous les champs!!!";
		$_SESSION['erreur']=$msg;
		header("location: http://localhost/Logitem/modifier_profil.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&erreur=".$msg);
		die();
	}
	if(empty(trim($_POST['newlogin'])) OR empty(trim($_POST['newpassword'])) OR empty(trim($_POST['newconfirmpassword'])) OR empty(trim($_POST['newtel']))){
			$msg="Veuillez remplir tous les champs!!!";
			$_SESSION['erreur']=$msg;
			header("location: http://localhost/Logitem/modifier_profil.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&erreur=".$msg);
			die();
	}
	if(isset($_POST['newpassword']) AND !empty($_POST['newpassword']) AND isset($_POST['newconfirmpassword']) AND !empty($_POST['newconfirmpassword'])){
		$newpassword=md5($_POST['newpassword']);
		$newconfirmpassword=md5($_POST['newconfirmpassword']);
		if($newpassword==$newconfirmpassword){
			if(strlen($_POST['newpassword'])>=8){
			$insertpassword=$connect->prepare('UPDATE utilisateur SET password=? WHERE id=?');
			$insertpassword->execute(array($newpassword,$_SESSION['id']));
			header("location: http://localhost/Logitem/Index.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&mp=1");
			}else{
				$msg="Votre mot de passe doit contenir aumoins 8 lettres!!!";
				$_SESSION['erreur']=$msg;
			header("location: http://localhost/Logitem/modifier_profil.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&erreur=".$msg);
			die();
			}
		}else{
			$msg="Le mot de passe et la confirmation ne correspondent pas!!!";
			$_SESSION['erreur']=$msg;
			header("location: http://localhost/Logitem/modifier_profil.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&erreur=".$msg);
			die();
		}
		
	} 
	if(isset($_POST['newemail']) AND !empty($_POST['newemail']) AND isset($_POST['newconfirmemail']) AND !empty($_POST['newconfirmemail'])){
		$newemail=htmlspecialchars($_POST['newemail']);
		$newconfirmemail=htmlspecialchars($_POST['newconfirmemail']);
		if($newemail==$newconfirmemail){
			if(filter_var($newemail,FILTER_VALIDATE_EMAIL)){
			$insertemail=$connect->prepare('UPDATE utilisateur SET email=? WHERE id=?');
			$insertemail->execute(array($newemail,$_SESSION['id']));
			header("location: http://localhost/Logitem/Index.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&mp=1");
			}else{
				$msg="Votre adresse e-mail n\'est pas une adresse mail valide!!!";
				$_SESSION['erreur']=$msg;
			header("location: http://localhost/Logitem/modifier_profil.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&erreur=".$msg);
			die();
			}
		}else{
			$msg="L\'adresse mail et la confirmation ne correspondent pas!!!";
			$_SESSION['erreur']=$msg;
			header("location: http://localhost/Logitem/modifier_profil.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&erreur=".$msg);
			die();
		}
		
	} 
	if(isset($_POST['newtel']) AND !empty($_POST['newtel']) AND $_POST['newtel']!=$user['tel']){
		if(is_numeric($_POST['newtel'])){
		$newtel=htmlspecialchars($_POST['newtel']);
			if(strlen($newtel)==9){
			$inserttel=$connect->prepare('UPDATE utilisateur SET tel=? WHERE id=?');
			$inserttel->execute(array($newtel,$_SESSION['id']));
			header("location: http://localhost/Logitem/Index.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&mp=1");
			}else{
					$msg="Votre numéro de téléphone doit contenir 9 chiffres!!!";
					$_SESSION['erreur']=$msg;
				header("location: http://localhost/Logitem/modifier_profil.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&erreur=".$msg);
				die();
			}	
		}else{
			$msg="Le numéro de téléphone n\'est pas valide!!!Veuillez éviter tous symboles de séparation";
			$_SESSION['erreur']=$msg;
			header("location: http://localhost/Logitem/modifier_profil.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&erreur=".$msg);
			die();
		}
	}
	if(isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name'])){
		$taillemax=2097152;
		$extensionsvalides=array('jpg','jpeg','gif','png',);
		if($_FILES['avatar']['size']<=$taillemax){
			$extensionsupload=strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
			if(in_array($extensionsupload, $extensionsvalides)){
				$chemin="employes/avatars/".$_SESSION['id'].".".$extensionsupload;
				$deplacement=move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
				if($deplacement){
					$updateavatar=$connect->prepare('UPDATE utilisateur SET avatar=:avatar WHERE id=:id');
					$updateavatar->execute(array(
						'avatar'=>$_SESSION['id'].".".$extensionsupload,
						'id'=>$_SESSION['id']));
					header("location: http://localhost/Logitem/Index.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&mp=1");
				}else{
					$msg="Une erreure inconnue s\'est produite durant l\'importation de l\'avatar";
					$_SESSION['erreur']=$msg;
					header("location: http://localhost/Logitem/modifier_profil.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&erreur=".$msg);
					die();
				}
			}else{
				$msg="Votre avatar doit être au format jpg, jpeg, gif ou png";
				$_SESSION['erreur']=$msg;
				header("location: http://localhost/Logitem/modifier_profil.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&erreur=".$msg);
				die();
			}
		}else{
			$msg="L\'avatar ne doit pas dépasser 2MO";
			$_SESSION['erreur']=$msg;
			header("location: http://localhost/Logitem/modifier_profil.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&erreur=".$msg);
			die();
		}
	}
	if(isset($_POST['newlogin']) AND !empty($_POST['newlogin']) AND $_POST['newlogin']!=$user['login']){
		$newlogin=htmlspecialchars($_POST['newlogin']);
		if(strlen($newlogin)>=4){
		$sql=$connect->prepare('SELECT login FROM utilisateur WHERE login=?');
		$sql->execute(array($newlogin));
		$sql1=$sql->rowcount();
			if($sql1==0){
			$insertlogin=$connect->prepare('UPDATE utilisateur SET login=? WHERE id=?');
			$insertlogin->execute(array($newlogin,$_SESSION['id']));
			header("location: http://localhost/Logitem/Index.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&mp=1");
			}else{
				$msg="Votre login n\'est pas disponible... Veuillez en choisir un autre!!!";
				$_SESSION['erreur']=$msg;
				header("location: http://localhost/Logitem/modifier_profil.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&erreur=".$msg);
				die();
			}
		}else{
				$msg="Votre login doit contenir aumoins 4 lettres!!!";
				$_SESSION['erreur']=$msg;
			header("location: http://localhost/Logitem/modifier_profil.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&erreur=".$msg);
			die();
		}	
	}
}

if(isset($_POST['back'])){
	$_SESSION['erreur']="";
	header("location: http://localhost/Logitem/Index.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']);
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Modification</title>
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
		
		<div class="form" align="center">
			<form method="POST" action="" enctype="multipart/form-data">
			<fieldset>
				<legend align="center"><div class="ins">MODIFIER MON PROFIL</div></legend>
				<table>
					<tr>
						<td><label for="login">Login:</label></td><td><input type="text" name="newlogin" id="login"></input></td>
					</tr>
					<tr>
						<td><label for="login">Mot de passe:</label></td><td><input type="password" name="newpassword" id="login"></input></td>
					</tr>
					<tr>
						<td><label for="login">Confirmer votre mot de passe:</label></td><td><input type="password" name="newconfirmpassword" id="login"></input></td>
					</tr>
					<tr>
						<td><label for="login">Adresse mail:</label></td><td><input type="email" name="newemail" id="login"></input></td>
					</tr>
					<tr>
						<td><label for="login">Confirmer votre adresse mail:</label></td><td><input type="email" name="newconfirmemail" id="login"></input></td>
					</tr>
					<tr>
						<td><label for="login">Numéro de téléphone:</label></td><td><input type="text" name="newtel" id="login"></input></td>
					</tr>
					<tr>
						<td><label for="avatar">Avatar:</label></td><td><input type="file" name="avatar" id="avatar"></input></td>
					</tr>
					<tr>
						<td align="right"><input type="submit" name="submit" value="MODIFIER"></input></td><td><input type="reset" name="reset" value="EFFACER"></input></td>
					</tr>
					<tr>
						<td align="left"><input type="submit" name="back" value="ACCEUIL" style="width:90px"></input></td><td></td>
					</tr>
				</table>
			</fieldset>
			</form>
			<br>
			<div class="nb"><u>NB:</u></div><div class="nb" id="tx">Veuillez remplir tous les champs ou alors<br> saisir les aciennes informations dans les<br> champs que vous ne shouaitez pas modifier</div>
			<br>
			<?php 
			if(isset($_GET['erreur']) AND $_GET['erreur']==$_SESSION['erreur'] AND !empty($_GET['erreur'])){
			echo "<script type='text/javascript'>alert('".$_GET['erreur']."');</script>";
			}
			?>
		</div>
	</div>
	<div id="pied_de_page">Dévelopée par ANOUMEDEM NGUEFACK GILLES CEDRIC; élève en classe de Terminale TI au LBY; Tel: 698-15-81-92; Email: nguefackgilles@gmail.com</div>
</body>
</html>
<?php
}else{
	if(isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name'])){
		$taillemax=2097152;
		$extensionsvalides=array('jpg','jpeg','gif','png',);
		if($_FILES['avatar']['size']<=$taillemax){
			$extensionsupload=strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
			if(in_array($extensionsupload, $extensionsvalides)){
				$chemin="eleves/avatars/".$_GET['idu'].".".$extensionsupload;
				$deplacement=move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
				if($deplacement){
					$updateavatar=$connect->prepare('UPDATE eleve SET avatar=:avatar WHERE id=:id');
					$updateavatar->execute(array(
						'avatar'=>$_GET['idu'].".".$extensionsupload,
						'id'=>$_GET['idu']));
					header("location: http://localhost/Logitem/Index.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&mp=1");
				}else{
					$msg="Une erreure inconnue s\'est produite durant l\'importation de l\'avatar";
					$_SESSION['erreur']=$msg;
					header("location: http://localhost/Logitem/modifier_profil.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&erreur=".$msg."&mode=avatar");
				}
			}else{
				$msg="Votre avatar doit être au format jpg, jpeg, gif ou png";
				$_SESSION['erreur']=$msg;
				header("location: http://localhost/Logitem/modifier_profil.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&erreur=".$msg."&mode=avatar");
			}
		}else{
			$msg="L\'avatar ne doit pas dépasser 2MO";
			$_SESSION['erreur']=$msg;
			header("location: http://localhost/Logitem/modifier_profil.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&erreur=".$msg."&mode=avatar");
		}
	}

	if(isset($_POST['back'])){
	$_SESSION['erreur']="";
	header("location: http://localhost/Logitem/Index.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']);
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Modification</title>
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
		
		<div class="form" align="center">
			<form method="POST" action="" enctype="multipart/form-data">
			<fieldset>
				<legend align="center"><div class="ins">MODIFIER LA PHOTO DE PROFIL</div></legend>
				<table>
					<tr>
						<td><label for="avatar">Avatar:</label></td><td><input type="file" name="avatar" id="avatar"></input></td>
					</tr>
					<tr>
						<td align="right"><input type="submit" name="submit" value="MODIFIER"></input></td><td><input type="reset" name="reset" value="EFFACER"></input></td>
					</tr>
					<tr>
						<td align="left"><input type="submit" name="back" value="ACCEUIL" style="width:90px"></input></td><td></td>
					</tr>
				</table>
			</fieldset>
			</form>
			<br>
			<?php 
			if(isset($_GET['erreur']) AND $_GET['erreur']==$_SESSION['erreur'] AND !empty($_GET['erreur'])){
			echo "<script type='text/javascript'>alert('".$_GET['erreur']."');</script>";
			}
			?>
		</div>
	</div>
	<div id="pied_de_page">Dévelopée par ANOUMEDEM NGUEFACK GILLES CEDRIC; élève en classe de Terminale TI au LBY; Tel: 698-15-81-92; Email: nguefackgilles@gmail.com</div>
</body>
</html>
<?php
}
}
?>