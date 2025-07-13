$( document ).ready(function() {
		$("#period_from").datepicker( {
format: "dd M yyyy",
        autoclose: true
	});   
	
/* 	$("#period_from").datepicker( {
		format: "mm/yyyy",
		viewMode: "months",
		minViewMode: "months",
		autoclose: true
	}); */
	
	$("#period_to").datepicker( {
format: "dd M yyyy",
        autoclose: true
	});		
});


function getListData(){
	from = $("#period_from").val();
	to = $("#period_to").val();
	if(!from){
		alert("Periode From Harus Diisi");
		return false;
	}
	if(!to){
		alert("Periode To Harus Diisi");
	}	
	$(".list").css("display","");

	GenerateTable(from,to);

	$("#label_from").text(from);
	$("#label_to").text(to);
}

function getListData(){
	from = $("#period_from").val();
	to = $("#period_to").val();
	if(!from){
		alert("Periode From Harus Diisi");
		return false;
	}
	if(!to){
		alert("Periode To Harus Diisi");
	}	
	$(".list").css("display","");

	GenerateTable(from,to);

	$("#label_from").text(from);
	$("#label_to").text(to);
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
	event.preventDefault();

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
	event.preventDefault();
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

			

function GenerateTable(from_,to_){
	table = $('#laporan_jurnal').DataTable( {
      'processing': true,
      'serverSide': true,
	  "lengthMenu": [[10, 25, 50, 999999999], [10, 25, 50, "All"]],
		'serverMethod': 'post',
		
        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
        "ajax":  "webservices/getListDataHistoryPage.php?from="+from_+"&to="+to_,
        "columns": [
		//kpno
        {
			"data":           "Trans_Date",/* 
             "render":        function (data) {
							return decodeURIComponent(data.period);
                            } */
         } ,
		//deldate
        {
			"data":           "Trans_Desc",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,	

		//eta_fabric
        {
			"data":           "Trans_Host",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,
		 
        ],
		"autoWidth": true,
		"scrollCollapse": true,

        "order": [[1, 'asc']],
        scrollY:        "500px",
        scrollX:        true,
        scrollCollapse: true,
		
        "destroy": true,
        fixedColumns:   {
            leftColumns: 2
        },
      dom: 'Bfrtip',
		header: true,
		    dom:
			"<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
			"<'row'<'col-sm-12'tr>>"+
    "<'row'<'col-sm-5'i><'col-sm-7'p>>",	
    } );
}
