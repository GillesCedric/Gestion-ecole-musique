<?php
session_start();
//connexion à la bd
$connect=new PDO("mysql:host=localhost;dbname=EIMOL","root","");
//vérification de si l'utilisateur est bien connecté
if(isset($_GET['id']) AND $_GET['id']>0 AND isset($_GET['pv']) AND !empty($_SESSION)){
$getid=intval($_GET['id']);
$getpriv=$_GET['pv'];
//récupération des données dans la bd
$requser=$connect->prepare('SELECT * FROM utilisateur WHERE id=?');
$requser->execute(array($getid));
$userinfo=$requser->fetch();
//système de pagination
$elevesparpage=1;
$elevestotalsreq=$connect->query('SELECT id FROM eleve');
$elevestotals=$elevestotalsreq->rowCount();
$pagestotales=ceil($elevestotals/$elevesparpage);

if(isset($_GET['page']) AND !empty($_GET['page']) ){
	$_GET['page']=intval($_GET['page']);
	$pagecourante=$_GET['page'];
}else{
	$pagecourante=1;
}

$depart= ($pagecourante-1)*$elevesparpage;

$req=$connect->query('SELECT * FROM eleve ORDER BY Nom ASC LIMIT '.$depart.','.$elevesparpage);
$count = $req->rowCount(); 

$date=new Datetime("".date('Y-m-d')." -7 day");
$sql="SELECT eleve.nom, eleve.prenom, eleve.id, paiement.ideleve, paiement.dateprochaine FROM eleve, paiement WHERE paiement.dateprochaine=? AND eleve.id=paiement.ideleve";
$reqpaiement=$connect->prepare($sql);
$reqpaiement->execute(array($date->format('y-m-d')));
$countp=$reqpaiement->rowCount();
if($countp>0){
	$notif=$connect->prepare("SELECT * FROM notification WHERE mois=?");
	$notif->execute(array(date("m")));
	$notifcount=$notif->rowCount();
	if($notifcount==0){
		while ($result = $reqpaiement->fetch(PDO::FETCH_OBJ)) {
			if(date("m")<10){
				$insertnotif=$connect->prepare("INSERT INTO notification(ideleve,nom,prenom,mois) VALUES (?,?,?,?)");
				$insertnotif->execute(array($result->ideleve,$result->nom,$result->prenom,"0".date("m")));
			}else{
				$insertnotif=$connect->prepare("INSERT INTO notification(ideleve,nom,prenom,mois) VALUES (?,?,?,?)");
				$insertnotif->execute(array($result->ideleve,$result->nom,$result->prenom,date("m")));
			}
		
		}
	}
}
$tableau_date=array('01' => 'Janvier', '02' => 'Février','03' => 'Mars','04' => 'Avril','05' => 'Mai','06' => 'Juin','07' => 'Juillet','08' => 'Août','09' => 'Septembre','10' => 'Octobre','11' => 'Novembre','12' => 'Décembre');
$notif=$connect->query("SELECT * FROM notification");
$notifcount=$notif->rowCount();

$admin="Administrateur";
$uti="Utilisateur";

if(isset($_POST['deconnexion'])){
	$_SESSION=array();
	session_destroy();
	header("location: http://localhost/Logitem/connexion.php");
}

if(isset($_POST['inscrire'])){

	header("location: http://localhost/Logitem/inscription.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']);
}
if(isset($_POST['ajouter'])){

	header("location: http://localhost/Logitem/index2.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']);
}

if(isset($_POST['acceuil'])){

	header("location: http://localhost/Logitem/Index.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']);
}

if(isset($_POST['modifier'])){

	header("location: http://localhost/Logitem/modifier_profil.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']);
}
if(isset($_POST['supprimer'])){

	header("location: http://localhost/Logitem/supression_utilisateur.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Liste des eleves</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/style5.css">
	<link rel="shortcut icon" type="images/x-icon" href="css/img/favicon.ico" />
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/search.js"></script>
	<style type="text/css">
	input[name="ajouter"]{
	margin-top: 5px;
	margin-left: 15px;
	border-radius: 5px;

}
.ajouter{
	margin-top: 5px;
	margin-left: 15px;
	border-radius: 5px;
}
	table{
		border-collapse: collapse;
		margin-bottom: 60px;
	}
	.img{
		background-image: url('employes/avatars/<?=$userinfo['avatar'];?>');
		background-repeat: no-repeat;
		width: 130px;
		border-radius: 50%;
		margin-top: 10px;
		margin-left: 30px;
		margin-bottom: 10px;

	}
	.img:after{
		content: "";
		padding-bottom: 100%;
		display: block;
	}
	#connex{
	position: absolute;
	background-color: blue;
	border-radius: 15px;
	margin-top: 350px;
	background: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#e5e5e5));
	background: -webkit-linear-gradient(top, #ffffff, #e5e5e5);
	background: -moz-linear-gradient(top, #ffffff, #e5e5e5);
	background: -ms-linear-gradient(top, #ffffff, #e5e5e5);
	background: -o-linear-gradient(top, #ffffff, #e5e5e5);
	width: 192px;
	margin-left: 01px;
	height: 385px;
}
#notif{
	position: absolute;
	background-color: blue;
	border-radius: 15px;
	margin-top: 150px;
	background: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#e5e5e5));
	background: -webkit-linear-gradient(top, #ffffff, #e5e5e5);
	background: -moz-linear-gradient(top, #ffffff, #e5e5e5);
	background: -ms-linear-gradient(top, #ffffff, #e5e5e5);
	background: -o-linear-gradient(top, #ffffff, #e5e5e5);
	width: 192px;
	margin-left: 1142px;
	height: 375px;
}
a:visited{
	color: blue;
	}
	input[type="search"]{
	width: 70%;
	height: 40px;
	margin-left: 40px;
	margin-top: 100px;
	margin-right: 0px;
	font-size: 20px;
	margin-bottom: 30px;
	border-radius: 5px;
}
body{
	background-image: url("css/img/M1.JPG");
	background-size: cover;
}
#recherche,#resultat,ul{
	margin-left: 80px;
	padding: 0;
	border: 0; 
	width: 700px;
	margin-top: 0;
	margin-bottom: 0;
	line-height: 1.5em;
	font-size: 20px;
}
#resultat li{
	list-style-type: none;
	margin-left: 40px;
	padding: 0;
}
#resultat ul li a{
	color: #000;
	text-decoration: none;
	width: 700px;
	height: 30px;
	display: block;
	text-align: center;
	border: 1px solid #000;
	margin-top: 0;
	margin-bottom: 0; 
	border-top: 0px;
}
#recherche{
	border: 1px solid #000;
	margin-left: 120px; 
	font-size: 25px;
}
a{
	text-decoration: none; 
}
input[value='Télécharger']{
	margin-left: 740px;
	}
.resultnotif{
	margin-left: 10px;
}
#menunotif{
	background-color: black;
	width: 100%;
	height: 25px;
	position: absolute;
	color: white;
}
</style>
</head>
<body>
<form method="POST" action="" accept-charset="utf-8">
<div id="notif"><br><span class="out" style="margin-left: 28px;"><font size="05px">Notifications</font></span>
<?php
echo "<div class='resultnotif'>";
if($notifcount>0){
	while ($result = $notif->fetch(PDO::FETCH_OBJ)) {
	echo "<br><hr>L'élève ".$result->nom." ".$result->prenom." doit payer sa pension pour le mois de ".$tableau_date[$result->mois]."<br>";
}
}else{
	echo "<br>Aucune notification";
}
echo "</div>";
?>
</div>
<!-- début du menu à droite -->
	<div id="connex"><div class="img"></div><span class="out">Login: <?=$userinfo['login'];?></span><br><br><span class="out">Type: <?= $_SESSION['habilitation']; ?></span><input type="submit" name="acceuil" value="Acceuil" class="in" disabled></input><input type="submit" name="deconnexion" value="Se déconnecter" class="in"></input><input type="submit" name="modifier" value="Modifier mon profil"><input type="submit" name="ajouter" value="Ajouter un élève" class="in" class="ajouter"></input><?php if($getpriv==$admin AND $getpriv==$_SESSION['habilitation']){ ?><input type="submit" name="inscrire" value="Ajouter un employé" class="in"></input><input type="submit" name="supprimer" value="Supprimer un employé" class="in"></input></span><?php }elseif($getpriv==$uti AND $getpriv==$_SESSION['habilitation']){ ?>
	<input type="submit" name="inscrire" value="Ajouter un employé" class="in" class="ajouter" disabled></input><input type="submit" name="supprimer" value="Supprimer un employé" class="in" class="ajouter" disabled></input></span><?php } ?>
	</div>
	<!-- fin du menu à droite -->
	<!-- définition du menu à principal -->
	<div class="entete"><div id="rev">Version 1.0</div><div class="titre">TOUS EN MUSIQUE</div>		<div id="st">Fiche de consultation des élèves</div>
		<div class="logo"><img src="css/img/P1.PNG" width="150px" height="90px;" style="border-radius: 10px;"></div>
		<div id="dt">
			<div id="d"><?= date('j').' '.$tableau_date[date('m')]." ".date('Y'); ?></div>
			<div id="tt">Liste des élèves</div>
			<div id="result">
			<?php if($count>0){ ?>
			<input type="search" name="recherche" placeholder="Rechercher... " class="text" id="recherche" style="margin-bottom: 20px;"><br>
			<div class="resultat" id="resultat">
			
			</div>	
			<?php }else{ ?>
			<input type="search" name="recherche" placeholder="Rechercher... " class="text" id="recherche" style="margin-bottom: 20px;" disabled><br>
			<?php } ?>
			<table border="1" width="750px" align="center">
			<!-- affichage des données de la bd -->
			<?php 
			if($count > 0){
				//affichage du tableau
				echo "<tr>
						<td align='center'>Avatar</td>
						<td align='center'>Nom</td>
						<td align='center'>Prénom</td>
						<td align='center'>Ville</td>
						<td align='center'>Tél</td>
						<td align='center'>Action</td>
					</tr>";
					//affichage des données du tableau
				while ($result = $req->fetch(PDO::FETCH_OBJ)) {
					if($_SESSION['habilitation']==$admin ){
							echo "<tr>
									<td align='center'>
										<img src='eleves/avatars/".$result->avatar."' style='border-radius: 50%;width:50px;height:50px;'/><br>
										<a href='modifier_profil.php?id=".$_SESSION['id']."&idu=".$result->id."&pv=".$_SESSION['habilitation']."&mode=avatar'><input type='button' value='Modifier'></a>
									</td>
									<td align='center'>
										<a href='consult.php?id=".$_SESSION['id']."&idu=".$result->id."'>".$result->nom."</a>
									</td>
									<td align='center'>
										".$result->prenom."
									</td>
									<td align='center'>
										".$result->ville."
									</td>
									<td align='center'>
										".$result->tel."
									</td>
									<td>
										<a href='supprimer_eleve.php?id=".$_SESSION['id']."&idu=".$result->id."&log=".$result->nom."'><input type='button' value='Supprimer'></a><br>
										<a href='consult.php?id=".$_SESSION['id']."&idu=".$result->id."&mode=payer'><input type='button' value='Effectuer un paiement'></a><br>
										<a href='consulter_paiement.php?id=".$_SESSION['id']."&idu=".$result->id."&pv=".$_SESSION['habilitation']."'><input type='button' value='Consulter les paiements'></a>
									</td>
								</tr>";
					}else{ 
							echo "<tr>
									<td align='center'>
										<img src='eleves/avatars/".$result->avatar."' style='border-radius: 50%;width:50px;height:50px;'/><br>
										<a href='modifier_profil.php?id=".$_SESSION['id']."&idu=".$result->id."&pv=".$_SESSION['habilitation']."&mode=avatar'><input type='button' value='Modifier'></a>
									</td>
									<td align='center'>
										<a href='consult.php?id=".$_SESSION['id']."&idu=".$result->id."'>".$result->nom."</a>
									</td>
									<td align='center'>
										".$result->prenom."
									</td>
									<td align='center'>
										".$result->ville."
									</td>
									<td align='center'>
										".$result->tel."
									</td>
									<td>
										<a href='consult.php?id=".$_SESSION['id']."&idu=".$result->id."&mode=payer'><input type='button' value='Effectuer un paiement'></a><br>
										<a href='consulter_paiement.php?id=".$_SESSION['id']."&idu=".$result->id."&pv=".$_SESSION['habilitation']."'><input type='button' value='Consulter les paiements'></a>
									</td>
								</tr>";
					}		 		
				}
			}else{
				echo "<div style='text-align:center'>Aucun élèves inscrits</div>";
			} 
			?>
			</table>
			<?php
			//boutton télécharger
			if($count>0){
				echo "<a href='telecharger.php?id=".$_SESSION['id']."&pv=".$_SESSION['habilitation']."'><input type='button' value='Télécharger'></a><br>";
			}
			//affichage des pages de la pagination
				for($i=1;$i<=$pagestotales;$i++){
					if ($pagecourante==$i) {
						echo $i." ";
					}else{
						echo "<a href='index.php?id=".$_SESSION['id']."&page=".$i."&pv=".$_SESSION['habilitation']."'>".$i." </a> ";
					}
				}
			?>
			</div>
			<!-- affichage des messages d'erreur -->
			<?php
				if(isset($_GET['msg']) AND isset($_GET['log'])){
					if($_GET['msg']==1){
						echo "<script type='text/javascript'>alert('L\'élève ".$_GET['log']." a bien été supprimer');</script>";
					}else{
						echo "<script type='text/javascript'>alert('L\'élève ".$_GET['log']." n'a pas pu être supprimer pour des raisons inconnues');</script>";
					}
				}
				if(isset($_GET['er'])){
					if($_GET['er']==1){
						echo "<script type='text/javascript'>alert('Paiement éffectué');</script>";
					}else{
						echo "<script type='text/javascript'>alert('Le paiement n\'a pas pu être éffectué pour des raisons inconnues');</script>";
					}
				}
				if(isset($_GET['msg'])){
					if($_GET['msg']==2){
							echo "<script type='text/javascript'>alert('Inscription éffectué');</script>";
					}elseif ($_GET['msg']==3) {
						echo "<script type='text/javascript'>alert('Supréssion éffectué');</script>";
					}
				}
				if(isset($_GET['erreur'])){
					if($_GET['erreur']==2){
							echo "<script type='text/javascript'>alert('Inscription éffectué');</script>";
					}elseif ($_GET['erreur']==1) {
						echo "<script type='text/javascript'>alert('Inscription échouée');</script>";
					}
				}
			?>	
		</div><br><br><br>
	</div>
	</div>
	<div id="pied_de_page">Développée par ANOUMEDEM NGUEFACK GILLES CEDRIC; élève en classe de Terminale TI au LBY; Tel: 698-15-81-92; Email: nguefackgilles@gmail.com</div>
</body>
</html>
<!-- redirection si l'utilisateur n'est pas connecté -->
<?php
}else{
	header("location: http://localhost/Logitem/connexion.php");
}
?>