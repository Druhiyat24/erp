$( document ).ready(function() {
localStorage.clear();
sessionStorage.clear();
getLisData();

});
function AddNew(){
	$('#myOverlay').css('display','block');
	window.location = './?mod=31';
	console.log('12');
}
	function getLisData(){
		$('#myOverlay').css('display','block');
		var y = 0;
     $.ajax({
        type:"POST",
        cache:false,
        url:"webservices/getListKontrakKerja.php",
        success: function (response) {
			console.log(response);
			d = JSON.parse(response);
			StoreNomorSurat = d.records['n_id'];
			StoreId = d.records['id'];
			StoreNama = d.records['nama'];
			StoreNik2 = d.records['nik2'];
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
				td +=	'<td>  <a href="#"  onclick="handleEdit('+"'"+decodeURIComponent(StoreId[x])+"'"+')" style="color:black" > ';
				td +=		decodeURIComponent(StoreNomorSurat[x]);
				td +=	'</a> </td>';				
				td +=	'<td>';
				td +=		decodeURIComponent(StoreNik2[x]);
				td +=	'</td>';
				td += 	'<td>';
				td += 		decodeURIComponent(StoreNama[x]);
				td += 	'</td>';
				td += 	'<td>';
				td += 		decodeURIComponent(StoreTglMasuk[x]);
				td += 	'</td>';				
				td += 	'<td>';
				td += 		'<i class="fa fa-pencil" style="color:red;background-color:white;cursor:pointer" onclick="handleEdit('+"'"+decodeURIComponent(StoreId[x])+"'"+')" > </i> |  ';
				td += 		'<i class="fa fa-trash" style="color:red;background-color:white;cursor:pointer" onclick="handleDelete('+"'"+decodeURIComponent(StoreId[x])+"'"+')" > </i> | ';
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
	function handleEdit(Data){
	$('#myOverlay').css('display','block');	
		sessionStorage.setItem("idKontrakKerja", Data);
		window.location = './?mod=31';
	}
	
function handlePrint(id){
	//window.location = './KontrakKerjaForm/pdf/index.php';
	window.open('./KontrakKerjaForm/pdf/index.php?id='+id, '_blank');
}	
	
	function handleDelete(Data){
		$('#myOverlay').css('display','block');
		Id = decodeURIComponent(Data);
		//Id = Data;
		console.log(StoreId);
    $.ajax({		
		type:"POST",
		cache:false,
        url:"webservices/deleteKontrakKerja.php",
		data : { data: {'id' : Id } },     // multiple data sent using ajax
		success: function (response) {
			d = JSON.parse(response);
			if(d.status == 'ok'){
				alert("Data BerHasil Dihapus");
				location.reload();
			return false;
			}
			else{
				$('#myOverlay').css('display','none');
				alert('SomeThing Wrong!');
				location.reload();
				
			}
        }
      });
	}	
	
