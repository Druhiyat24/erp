$( document ).ready(function() {
    $("#froms").datepicker({
      format: "dd/mm/yyyy",
      autoclose: true  
    });	
    $("#tos").datepicker({
      format: "dd/mm/yyyy",
      autoclose: true  
    });		
});


function searchDate(){
	var froms = $("#froms").val();
	var tos = $("#tos").val();
	if(!froms){
		alert("Format Tanggal Salaha/tanggal belum diisi");
		return false;
	}
	if(!tos){
		alert("Format Tanggal Salaha/tanggal belum diisi");
		return false;
	}
	window.location.href = "?mod=39&rptid=out_dev&froms="+froms+"&tos="+tos;
	
}

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
        data : { idws : $idws },     // multiple data sent using ajax
        success: function (response) {

        }
      });
}

function getMasterLaporanMaterialCount(item,type){
	
	
		$("#myLoading_2").css("display","block");
		console.log(item.id);
		$("#example1b").dataTable().fnDestroy();
		$("#bodyexamle234").empty();
		
	
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getMasterLaporanMaterial.php", // nama webservices nya 
        data : { idws : item, type : type },     
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				console.log(d);
				var td='';
				if(d.status == 'ok'){
					for (var i=0; i<d.records.length;i++) {
						td +="<tr>";
						td +="<td>";
						td +=i+1;
						td +="</td>";

						td +="<td>";
						td +=decodeURIComponent(d.records[i].pono);	
						td +="</td>";


						td +="<td>";
						td +=decodeURIComponent(d.records[i].item_d);	
						td +="</td>";

						td +="<td>";
						td +=decodeURIComponent(d.records[i].count_po);
						td +="</td>";

						td +="<td>";
						td +=decodeURIComponent(d.records[i].count_bpb);
						td +="</td>";
						
						

						
						td +="</tr>";
					}
					$('#bodyexamle234').append(td);
					pasangDataTable('example1b');
				}

        }

      });	
}

function getMasterLaporanMaterial(item,type){
		$("#myLoading").css("display","block");
		console.log(item.id);
		$("#example1a").dataTable().fnDestroy();
		$("#bodyexamle23").empty();
		
	
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getMasterLaporanMaterial.php", // nama webservices nya 
        data : { idws : item, type : type },     
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				console.log(d);
				var td='';
				if(d.status == 'ok'){
					for (var i=0; i<d.records.length;i++) {
						td +="<tr>";
						td +="<td>";
						td +=i+1;
						td +="</td>";

						td +="<td>";
						td +=decodeURIComponent(d.records[i].pono);	
						td +="</td>";

						td +="<td>";
						td +=decodeURIComponent(d.records[i].bpb);	
						td +="</td>";

						td +="<td>";
						td +=decodeURIComponent(d.records[i].item_d);	
						td +="</td>";

						td +="<td>";
						td +=decodeURIComponent(d.records[i].qty);
						td +="</td>";

						td +="<td>";
						td +=decodeURIComponent(d.records[i].qty_bpb);
						td +="</td>";
						
						
						td +="<td>";
						td +=decodeURIComponent(d.records[i].unit);
						td +="</td>";
						
						td +="</tr>";
					}
					$('#bodyexamle23').append(td);
					pasangDataTable('example1a');
				}

        }

      });	
}

async function pasangDataTable(id){
	await $('#'+id).DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });
	$("#myLoading").css("display","none");
	$("#myLoading_2").css("display","none");
}

			


