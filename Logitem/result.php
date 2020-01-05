<?php
$connect=new PDO("mysql:host=localhost;dbname=eimol","root","");
session_start();
if(isset($_GET['motclef'])){
	$motclef = $_GET['motclef'];
	$q = array('motclef'=>$motclef.'%');
	$sql = 'SELECT * FROM eleve WHERE nom LIKE :motclef OR tel LIKE :motclef';
	$req = $connect->prepare($sql);
	$req->execute($q);
	$count = $req->rowCount();

	if($count > 0){
		echo "<table border='1' width='750px' align='center'>
		<tr>
						<td align='center'>Avatar</td>
						<td align='center'>Nom</td>
						<td align='center'>Prénom</td>
						<td align='center'>Ville</td>
						<td align='center'>Tél</td>
						<td align='center'>Action</td>
					</tr>";
		while ($result = $req->fetch(PDO::FETCH_OBJ)) {
			 if($_SESSION['habilitation']=='Administrateur' ){
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
									<td>
										<img src='eleves/avatars/".$result->avatar."' style='border-radius: 50%;width:50px;height:50px;'/><br>
										<a href='modifier_profil.php?id=".$_SESSION['id']."&idu=".$result->id."&pv=".$_SESSION['habilitation']."&mode=avatar'><input type='button' value='Modifier'></a>
									</td>
									<td>
										<a href='consult.php?id=".$_SESSION['id']."&idu=".$result->id."'>".$result->nom."</a>
									</td>
									<td>
										".$result->prenom."
									</td>
									<td>
										".$result->ville."
									</td>
									<td>
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
		echo "Aucun résultats pour: ".$motclef;
		echo "<br><br>";
	}
}

?>