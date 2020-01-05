<?php
session_start();

$connect=new PDO("mysql:host=localhost;dbname=EIMOL","root","");

if(isset($_GET['id']) AND $_GET['id']==$_SESSION['id']){ 
	$data=$connect->query('SELECT eleve.nom,eleve.prenom,eleve.id,eleve.tel,tuteur.teltuteur FROM eleve,tuteur WHERE eleve.id=tuteur.id');
	$datas=array();
	$filename='Liste des contacts';
	while ($d = $data->fetch(PDO::FETCH_OBJ)) {
		$datas[]=array(
		'Id' => $d->id,
		'Nom' => $d->nom,
		'Prenom' => $d->prenom,
		'Tel eleve' => $d->tel,
		'Tel tuteur' => $d->teltuteur,
		);
	}
	require 'class.csv.php';
	CSV::Export($datas,$filename);
}else{
	$_SESSION=array();
	session_destroy();
	header("location: http://localhost/Logitem/connexion.php");
}
?>