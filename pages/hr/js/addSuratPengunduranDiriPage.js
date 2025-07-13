$( document ).ready(function() {
localStorage.clear();
sessionStorage.clear();
getLisData();

});



function AddNew(){
	$('#myOverlay').css('display','block');	
	window.location = './?mod=32';
	console.log('12');
	
}



	function getLisData(){
		$('#myOverlay').css('display','block');	
		StoreId       = [];
		StoreNama     = [];
		StoreNik      = [];
		StoreTglKeluar= [];
		var y = 0;
     $.ajax({
        type:"POST",
        cache:false,
        url:"webservices/getListPengunduranDiri.php",
        success: function (response) {
			console.log(response);
			d = JSON.parse(response);
			StoreId = d.records['id'];
			StoreNama = d.records['nama'];
			StoreNik = d.records['nik'];
			StoreTglKeluar = d.records['tglkeluar'];
			td  = '';
			console.log(StoreId.length);
			var Loop = 1;
			if(Loop == 1){
			for (var x = 0; x < StoreId.length; x++) {
				Loop++;
			console.log(x);
			renders = '';
			console.log(StoreNik[x]);
				td += '<tr>';
				td +=	'<td>';
				td +=		decodeURIComponent(StoreNik[x]);
				td +=	'</td>';
				td += 	'<td>';
				td += 		decodeURIComponent(StoreNama[x]);
				td += 	'</td>';
				td += 	'<td>';
				td += 		decodeURIComponent(StoreTglKeluar[x]);
				td += 	'</td>';				
				td += 	'<td>';
				td += 		'<i class="fa fa-pencil" style="color:red;background-color:white;cursor:pointer" onclick="handleEdit('+"'"+decodeURIComponent(StoreId[x])+"'"+')" > </i> |  ';
				td += 		'<i class="fa fa-trash" style="color:red;background-color:white;cursor:pointer" onclick="handleDelete('+"'"+decodeURIComponent(StoreId[x])+"'"+')" > </i> |';
				td += 		'<i class="fa fa-print" style="color:red;background-color:white;cursor:pointer" onclick="handlePrint('+"'"+decodeURIComponent(StoreId[x])+"'"+')" > </i>';				
				td += 	'</td>';
				td += '</tr>';
				renders += td;
		        //$('#bodyexamle1').append(td);
				//console.log(sessionStorage.getItem("idPengunduranDiri"))
		     
			} 
			console.log(renders);
			$('#bodyexamle1').append(renders);
			}
			$("#example1").DataTable();
			$('#myOverlay').css('display','none');	
        }
      });
      return false;
	}

	
function handlePrint(id){
	//window.location = './KontrakKerjaForm/pdf/index.php';
	window.open('./SuratPengunduranDiriForm/pdf/index.php?id='+id, '_blank');
}	
	
	
	function handleEdit(Data){
		$('#myOverlay').css('display','block');	
		sessionStorage.setItem("idPengunduranDiri", Data);
		console.log(sessionStorage.getItem("idPengunduranDiri"));
		window.location="./?mod=32";
	}
	
	function handleDelete(Data){
		$('#myOverlay').css('display','block');	
		//Id = decodeURIComponent(StoreId[Data]);
		Id = Data;
    $.ajax({		
		type:"POST",
		cache:false,
        url:"webservices/deletePengunduranDiri.php",
		data : { data: {'id' : Id } },     // multiple data sent using ajax
		success: function (response) {
			d = JSON.parse(response);
			if(d.status == 'ok'){
				alert("Data BerHasil Dihapus");
				 location.reload();
			}
        }
      });
	}	
	
