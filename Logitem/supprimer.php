<?php

session_start();

$connect=new PDO("mysql:host=localhost;dbname=EIMOL","root","");

if(isset($_GET['id']) AND isset($_GET['idu']) AND isset($_GET['log']) AND $_GET['id']==$_SESSION['id']){ 
	$delete=$connect->prepare('DELETE FROM utilisateur WHERE id=?');
	$delete->execute(array($_GET['idu']));
	$verifie=$connect->prepare('SELECT * FROM utilisateur WHERE id=?');
	$verifie->execute(array($_GET['idu']));
	$exist=$verifie->rowcount();
	if($exist==0){
		header("location: http://localhost/Logitem/supression_utilisateur.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&msg=1&log=".$_GET['log']);
	}else{
		header("location: http://localhost/Logitem/supression_utilisateur.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&msg=2&log=".$_GET['log']);
	}
	
}else{
	$_SESSION=array();
	session_destroy();
	header("location: http://localhost/Logitem/connexion.php");
}

?>