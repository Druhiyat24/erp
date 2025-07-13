$( document ).ready(function() {
});

function send_draft_po($id_po){
	$("#myOverlay").css("display","block");
		$.ajax("webservices/save_draft_po.php",
		{
			type: "POST",
			data :{id : $id_po},
			success: function (data) {
				data =jQuery.parseJSON(data);
				if(data.respon){
					console.log(data.respon == '200');
					swal({ title: data.message,  imageUrl: '../../images/success.jpg'});  //success
				}else{
					swal({ title: data.message, imageUrl: '../../images/error.jpg' }); //success
				}
				setTimeout(function(){
					$("#myOverlay").css("display","none");
					window.location.href="./?mod=4_draft";
				},3000)
			},
			error: function (data) {
				alert("Error req");
			}
		});
}