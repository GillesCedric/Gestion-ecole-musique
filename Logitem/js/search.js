$(document).ready(function(){
	$("#recherche").keyup(function(){

		var recherche = $(this).val();

		var data = 'motclef=' + recherche;
		if (recherche.length>0) {
			$.ajax({

				type : "GET",
				url : "result.php",
				data : data,
				success: function(server_response){

					$("#resultat").html(server_response).show();
				}

			});

		}

	});

});