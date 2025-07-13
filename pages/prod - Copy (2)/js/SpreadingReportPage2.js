$(window).on("load", function () {
	url = window.location.search;
	$split = url.split("=");
	$id_url_tmp = $split[2];
	$pecah_id_url = $id_url_tmp.split("&");
	$id_url = $pecah_id_url[0];
	console.log($id_url);
	$id_so = $split[3];
	$id_so = $id_so.split("&");
	$id_so = $id_so[0];
	$color = $split[4];
	console.log($color);
	Data = {
		id_number: '',
		id_costing :'',
		nomor_internal :'',
		id_mark_entry :'',
		id_so	:'',
		ws : '',
		color : $color
	}
	auth_page()
		.then(X 		 =>getWS_sr())
		.then(check_crud => checkUrl($id_url))
		.then(X			 => GenerateTable()   ) 
});


function getWS_sr(){
	return new Promise(function(resolve, reject) {
	$.ajax("webservices/getWS_sr.php",
		{
			type: "POST",
			data: {code:'1',id:$id_url},
			success: function (data) {
				d =jQuery.parseJSON(data)
				Data.ws = decodeURIComponent(d.records[0].ws);
				Data.id_costing =decodeURIComponent(d.records[0].id_cost);
				resolve(jQuery.parseJSON(data));
			},
			error: function (data) {
				alert("Error req");
			}
		});
		
	});
}

function GenerateTable() {
	// alert($id_url); return false
	table = $("#item").DataTable({
		"processing": true,
		"serverSide": true,
		"lengthMenu": [[999999999], ["All"]],
		"ajax": {
			"url": "webservices/getListSpreadingReport_number.php?id=" + $id_url+"&id_so="+$id_so+"&color="+$color,
			"type": "POST"
		},
		"columns": [
			//description
			{ "data": "ws" },
			{ "data": "so_no" },
			{ "data": "buyer" },
			{ "data": "style" },
			{ "data": "color" },
			{ "data": "spreding_number" },
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.button);
				},
				"className": "center"
			}

		],
		"autoWidth": true,
		"scrollCollapse": true,
		scrollY: "500px",
		scrollX: true,
		scrollCollapse: true,
		"destroy": true,
		//order: [[ 1, "asc" ]],
		"ordering": true,
		/*         fixedColumns:   {
					leftColumns: 7
				}, */
		dom: 'Bfrtip',

	});
}
function preview(id_me, color, $url) {
	// alert('123')
	// if (Data.id == "") {
	// 	var format = "1";
	// } else {
	// 	var format = "2";
	// }

	// Data.ws = $("#ws").val()

	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveMarkerEntryDetail.php",
		data: { code: '1', format: '1', id_cost: id_me, clr: color, id: $url,color:$color },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			d = JSON.parse(data);
			console.log(d);
			if (d.respon == '200') {
				// window.location = "/prod/?mod=AL&id=" + id_me;
			}
			else {
				alert('Sabar Ya Ini Ujian')
			}
		}
	});

}
function saveNomor(){
	Data.id_mark_entry = $id_url;
	Data.id_so			=$id_so;
	Data.nomor_internal = $("#nomor_spreading_internal").val();
	if(Data.ws ==''){
		alert("Data Belum Siap");
		return false
	}
	if(!Data.nomor_internal){
		alert("Nomor Spreading Salah Format");
		return false		
	}
	if(Data.nomor_internal == ''){
		alert("Nomor Spreading Belum diisi");
		return false			
	}
	
	
/* 	Data.nomor = $("#nomor_spreading_internal").val();
	Data.id_costing = $id_mark_entry; */
	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveSpreadingNumber.php",
		data: { code: '1', format: format, data: Data },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			d = JSON.parse(data);
			console.log(d);
			if (d.respon == '200') {
				 GenerateTable();
				  $('#myModal2').modal('hide');
			}
			else {
				alert('Sabar Ya Ini Ujian')
			}
		}
	});	
	
}

function add_nomor_spreading(_format,no_spreading,id_number){
	format = _format;
	if(Data.ws ==''){
		alert("Data Belum Siap!");
		return false;
	}else{
		$("#modals_ws").val(Data.ws);
	}	
	
	if(_format =='2'){
		$("#nomor_spreading_internal").val(no_spreading);
		Data.id_number = id_number;
	}
}

function edit_nomor_spreading(that){
	console.log(that);
	format = $(that).data('format');
	var no_spreading = $(that).data('spr_number');
	Data.id_number = $(that).data('id_number');
	if(Data.ws ==''){
		alert("Data Belum Siap!");
		return false;
	}else{
		$("#modals_ws").val(Data.ws);
	}	
	
	if(format =='2'){
		$("#nomor_spreading_internal").val(no_spreading);
		//Data.id_number = id_number;
	}
}




/* function back() {
	window.location = "../prod/?mod=PW";
} */