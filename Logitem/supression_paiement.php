<?php
session_start();

$connect=new PDO("mysql:host=localhost;dbname=EIMOL","root","");

if(isset($_GET['id']) AND isset($_GET['idu']) AND $_GET['id']==$_SESSION['id']){ 
	$delete=$connect->prepare('DELETE FROM paiement WHERE id=?');
	$delete->execute(array($_GET['idu']));
	$verifie=$connect->prepare('SELECT * FROM paiement WHERE id=?');
	$verifie->execute(array($_GET['idu']));
	$exist=$verifie->rowcount();
	if($exist==0){
		header("location: http://localhost/Logitem/index.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&msg=3");
	}else{
		header("location: http://localhost/Logitem/index.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."&msg=3");
	}
	
}else{
	$_SESSION=array();
	session_destroy();
	header("location: http://localhost/Logitem/connexion.php");
}

?>