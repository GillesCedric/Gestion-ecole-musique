<?php
session_start();
$connect=new PDO("mysql:host=localhost;dbname=EIMOL","root","");

$msg="";

if(isset($_POST['submit'])){
	$tmp1=trim($_POST['login']);
	if(!empty($tmp1)){
	$login=htmlspecialchars($_POST['login']);

	$req=$connect->prepare('SELECT * FROM utilisateur WHERE login=?');
	$req->execute(array($login));
			 
	$userexit=$req->rowcount();
	if($userexit==1){
		$userinfo = $req->fetch();
		$_SESSION['id']=$userinfo['id'];
		header("location: http://localhost/Logitem/connexion_2.php");


	}else{
		$msg="Votre login n\'est pas exacte!!!";
	}
    }else{
		$msg="Veuillez bien remplir le champ!!!";
	}
}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Connexion</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/style1.css">
	<link rel="shortcut icon" type="images/x-icon" href="css/img/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="css/font-awesome-4.7.0/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="css/Icon_style.css">
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
			<form method="POST" action="">
			<fieldset>
				<legend align="center"><div class="ins">CONNEXION</div></legend>
					<div class="inputWithIcon inputIconBg">
						<input type="text" name="login" id="login" placeholder="Login"/>
						<i class="fa fa-user fa-lg fa-fw" aria-hidden="true" ></i><br>
						<input type="submit" name="submit" value="ENVOYER">
					</div>
			</fieldset>
			</form>
			<?php
			if(!empty($msg)){ 
			echo "<script type='text/javascript'>alert('".$msg."');</script>";
			}
			?>
			<br><br><br>
		</div>
	</div>
	<div id="pied_de_page">Développée par ANOUMEDEM NGUEFACK GILLES CEDRIC;élève en classe de Terminale TI au LBY;Tel: 698-15-81-92; Email: nguefackgilles@gmail.com</div>
</body>
</html>