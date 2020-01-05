<?php

session_start();

$connect=new PDO("mysql:host=localhost;dbname=EIMOL","root","");
if(isset($_GET['id']) AND $_GET['id']>0 AND isset($_GET['pv']) AND !empty($_SESSION)){
$getid=intval($_GET['id']);
$getpriv=$_GET['pv'];
$requser=$connect->prepare('SELECT * FROM utilisateur WHERE id=?');
$requser->execute(array($getid));
$userinfo=$requser->fetch();

$paiementsparpage=15;
$paiementstotalsreq=$connect->prepare('SELECT id FROM paiement WHERE ideleve=?');
$paiementstotalsreq->execute(array($_GET['idu']));
$paiementstotals=$paiementstotalsreq->rowCount();
$pagestotales=ceil($paiementstotals/$paiementsparpage);

$tableau_date=array('01' => 'Janvier', '02' => 'Février','03' => 'Mars','04' => 'Avril','05' => 'Mai','06' => 'Juin','07' => 'Juillet','08' => 'Août','09' => 'Septembre','10' => 'Octobre','11' => 'Novembre','12' => 'Décembre');

if(isset($_GET['page']) AND !empty($_GET['page']) ){
	$_GET['page']=intval($_GET['page']);
	$pagecourante=$_GET['page'];
}else{
	$pagecourante=1;
}

$depart= ($pagecourante-1)*$paiementsparpage;

$req=$connect->prepare('SELECT * FROM paiement WHERE ideleve=? ORDER BY id DESC LIMIT '.$depart.','.$paiementsparpage);
$req->execute(array($_GET['idu']));
$count = $req->rowCount();

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
	<title>Liste des paiements</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/style5.css">
	<link rel="shortcut icon" type="images/x-icon" href="css/img/favicon.ico" />
	<style type="text/css">
	input[name="ajouter"]{
	margin-top: 5px;
	margin-left: 15px;
	border-radius: 5px;

}

input[name="imprimer"]{
	margin-top: 5px;
	margin-left: 715px;
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
a:visited{
	color: blue;
}
body{
	background-image: url("css/img/M1.JPG");
	background-size: cover;
}
@media print{
			#pied_de_page,input[type="submit"],input[type="button"],#rev,#connex{
				display: none;
			}
			table td:last-child{
				display: none;
			}
			table{
				width:400px;
				margin-left: -150px;
			}
			.titre{
				margin-left: -100px;
				text-align: left;
				font-size: 40px;
			}

		}
	</style>
</head>
<body>
<form method="POST" action="" accept-charset="utf-8">
	<div id="connex"><div class="img"></div><span class="out">Login: <?=$userinfo['login'];?></span><br><br><span class="out">Type: <?= $_SESSION['habilitation']; ?></span><input type="submit" name="acceuil" value="Acceuil" class="in"></input><input type="submit" name="deconnexion" value="Se déconnecter" class="in"></input><input type="submit" name="modifier" value="Modifier mon profil"><input type="submit" name="ajouter" value="Ajouter un élève" class="in"></input><?php if($getpriv==$admin AND $getpriv==$_SESSION['habilitation']){ ?><input type="submit" name="inscrire" value="Ajouter un employé" class="in"></input><input type="submit" name="supprimer" value="Supprimer un employé" class="in"></input></span><?php }elseif($getpriv==$uti AND $getpriv==$_SESSION['habilitation']){ ?>
	<input type="submit" name="inscrire" value="Ajouter un employé" id="in" disabled></input><input type="submit" name="supprimer" value="Supprimer un employé" id="in" disabled></input></span><?php } ?>
	</div>
	<div class="entete"><div id="rev">Version 1.0</div><div class="titre">TOUS EN MUSIQUE</div>
		<div id="st">Fiche de consultation des paiements</div>
		<div class="logo"><img src="css/img//P1.PNG" width="150px" height="90px;" style="border-radius: 10px;"></div>
		<div id="dt">
			<div id="d"><?= date('j').' '.$tableau_date[date('m')]." ".date('Y'); ?></div>
			<div id="tt">Liste des paiements</div>
			<div id="result">
			<table border="1" width="750px" align="center">
			<?php 
			if($count > 0){
				echo "<tr>
						<td align='center'>Montant</td>
						<td align='center'>Date</td>
						<td align='center'>Action</td>
					</tr>";
				while ($result = $req->fetch(PDO::FETCH_OBJ)) {
					if($_SESSION['habilitation']==$admin ){
							echo "<tr>
									<td align='center'>
										".$result->somme."
									</td>
									<td align='center'>
										".$result->date."
									</td>
									<td align='center'>
										<a href='supression_paiement.php?id=".$_SESSION['id']."&idu=".$result->id."'><input type='button' value='Supprimer'></a><br>
									</td>
								</tr>";
					}else{ 
							echo "<tr>
									<td>
										".$result->somme."
									</td>
									<td>
										".$result->date."
									</td>
									<td>
									</td>
								</tr>";
					}		 		
				}
			}else{
				echo "<div style='text-align:center'>Aucun Paiements éffectués</div>";
			} 
			?>
			</table>
			<?php 
			if($count>0){ ?>
			<td><input type="submit" name="imprimer" value="IMPRIMER" onclick="window.print();"></input></td></td><br>
			<?php }
				for($i=1;$i<=$pagestotales;$i++){
					if ($pagecourante==$i) {
						echo $i." ";
					}else{
						echo "<a href='consulter_paiement.php?id=".$_SESSION['id']."&idu=".$_GET['idu']."&pv=".$_SESSION['habilitation']."&page=".$i."'>".$i."</a> ";
					}
				}
			?>
			</div>
			<?php
				if(isset($_GET['msg']) AND isset($_GET['log'])){
					if($_GET['msg']==1){
						echo "<script type='text/javascript'>alert('Le paiement ".$_GET['log']." a bien été supprimer');</script>";
					}else{
						echo "<script type='text/javascript'>alert('Le paiement ".$_GET['log']." n'a pas pu être supprimer pour des raisons inconnues');</script>";
					}
				}
			?>	
		</div><br><br><br>
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