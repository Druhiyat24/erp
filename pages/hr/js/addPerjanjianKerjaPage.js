$( document ).ready(function() {
localStorage.clear();
sessionStorage.clear();
getLisData();

});
function AddNew(){
	$('#myOverlay').css('display','block');	
	window.location = './?mod=34';
	console.log('12');
}
	function getLisData(){
		$('#myOverlay').css('display','block');	
		var y = 0;
     $.ajax({
        type:"POST",
        cache:false,
        url:"webservices/getListPerjanjianKerja.php",
        success: function (response) {
			console.log(response);
			d = JSON.parse(response);
			StoreId = d.records['id'];
			StoreNama = d.records['nama'];
			StoreNik2 = d.records['nik2'];
			console.log(StoreNik2);
			StoreNomorSurat = d.records['nomorsurat'];
			StoreTglMasuk = d.records['tglmasuk'];
			td  = '';
			renders = '';
			console.log(StoreId.length);
			var Loop = 1;
			if(Loop == 1){
			for (var x = 0; x < StoreId.length; x++) {
				Loop++;
			console.log(x);
			//console.log(StoreNik[x]);
				td += '<tr>';
				td +=	'<td>';
				td +=		decodeURIComponent(StoreNomorSurat[x]);
				td +=	'</td>';				
				td +=	'<td>';
				td +=		decodeURIComponent(StoreNik2[x]);
				td +=	'</td>';
				td += 	'<td>';
				td += 		decodeURIComponent(StoreNama[x]);
				td += 	'</td>';				
				td += 	'<td>';
				td += 		'<i class="fa fa-pencil" style="color:red;background-color:white;cursor:pointer" onclick="handleEdit('+"'"+decodeURIComponent(StoreId[x])+"'"+')" > </i> |  ';
				td += 		'<i class="fa fa-trash" style="color:red;background-color:white;cursor:pointer" onclick="handleDelete('+"'"+decodeURIComponent(StoreId[x])+"'"+')" ></i>  | ';
				td += 		'<i class="fa fa-print" style="color:red;background-color:white;cursor:pointer" onclick="handlePrint('+"'"+decodeURIComponent(StoreId[x])+"'"+')" > </i>';						
				td += 	'</td>';
				td += '</tr>';
				console.log("DATA KE :"+x);
				console.log(" || "+td);
				
				
		        //$('#bodyexamle1').append(td);
				//console.log(sessionStorage.getItem("idPengunduranDiri"))		     
			} 
			console.log(renders);
			renders = td;
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
	window.open('./PerjanjianKerjaForm/pdf/index.php?id='+id, '_blank');
}	
		
	
	
	function handleEdit(Data){
		$('#myOverlay').css('display','block');	
		sessionStorage.setItem("idPerjanjianKerja", Data);
		window.location = './?mod=34';
	}
	
	function handleDelete(Data){
		$('#myOverlay').css('display','block');	
		Id = decodeURIComponent(Data);
		//Id = Data;
		console.log(StoreId);
    $.ajax({		
		type:"POST",
		cache:false,
        url:"webservices/deletePerjanjianKerja.php",
		data : { data: {'id' : Id } },     // multiple data sent using ajax
		success: function (response) {
			d = JSON.parse(response);
			if(d.status == 'ok'){
				$('#myOverlay').css('display','none');	
				alert("Data BerHasil Dihapus");
				location.reload();
			}
        }
      });
	}	
	
