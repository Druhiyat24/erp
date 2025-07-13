$( document ).ready(function() {
	
	
});
function defaultData(){
	console.log(sessionStorage);
	if((sessionStorage.idKontrakKerja)){
		idForm = '2';
		id = sessionStorage.idKontrakKerja;
		getDefaultData();
		//alert('123');
	}
}
function getDefaultData(){
	$('#myOverlay').css('display','block');	
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getMasterLaporanMaterial.php",
        data : {  },     // multiple data sent using ajax
        success: function (response) {

        }
      });
}

			


