<?php
session_start();
$connect=new PDO("mysql:host=localhost;dbname=EIMOL","root","");
if(!empty($_SESSION)){
$req=$connect->prepare('SELECT * FROM utilisateur WHERE id=?');
$req->execute(array($_SESSION['id']));
$userinfo = $req->fetch();

$msg="";
if(isset($_POST['submit'])){
	$tmp1=trim($_POST['password']);
	if(!empty($tmp1)){
			$password=htmlspecialchars($_POST['password']);


	if($userinfo['password']==$password){
		$_SESSION['habilitation']=$userinfo['habilitation'];
		$_SESSION['login']=$userinfo['login'];
		header("location: http://localhost/Logitem/Index.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']);


	}else{
		$msg="Votre mot de passe n\'est pas exacte!!!";
	}
    }else{
		$msg="Veuillez bien remplir tous les champs!!!";
	}
}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Connexion</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/style1.css">
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
a{
		text-decoration: none;
		color: blue;
	}
	a:hover{
		text-decoration: underline;
		color: blue;
	}
	</style>
	<link rel="shortcut icon" type="images/x-icon" href="css/img/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="css/font-awesome-4.7.0/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="css/Icon_style_2.css">
	<style type="text/css">
	.img{
		background-image: url('employes/avatars/<?=$userinfo['avatar'];?>');
	}
	</style>
</head>
<body>
	<div class="entete"><div id="rev">Version 1.0</div><div class="titre">TOUS EN MUSIQUE</div>
		
		<div class="form" align="center">
			<form method="POST" action="">
			<fieldset>
				<legend align="center"><div class="img"></div></legend>
				<div class="info_users"><font color='black'><b><?=$userinfo['login'];?></b></font></div>
				<div class="info_users"><font color='black'><b><?=$userinfo['email'];?></b></font></div>
				<div class="inputWithIcon inputIconBg">
						<input type="password" name="password" id="login" placeholder="Mot de passe"/><i class="fa fa-lock fa-lg fa-fw" aria-hidden="true" ></i><br>
						<input type="submit" name="submit" value="SE CONNECTER">
				</div>
				<?php
				echo "<a href='mdp_oublie.php?id=".$_SESSION['id']."'>Mot de passe oublié ?</a>";
				?>
			</fieldset>
			</form>
			<?php
			if(!empty($msg)){ 
			echo "<script type='text/javascript'>alert('".$msg."');</script>";
			}
			if(isset($_GET['erreur'])){ 
			echo "<script type='text/javascript'>alert('Votre mot de passe vous a été envoyé dans votre boîte mail');</script>";
			}
			?>
			<br><br><br>
		</div>
	</div>
	<div id="pied_de_page">Développée par ANOUMEDEM NGUEFACK GILLES CEDRIC;élève en classe de Terminale TI au LBY;Tel: 698-15-81-92; Email: nguefackgilles@gmail.com</div>
</body>
</html>
<?php
}else{
	header("location: http://localhost/Genie Informatique/connexion.php");
}
?>