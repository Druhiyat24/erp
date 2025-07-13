$(window).on("load", function () {
	show_loading();
	url = window.location.search;
	format = 1;
	$split = url.split("=");
	$id_url = $split[2];
	Data = {
		key_det : '0',
		id_number: $id_url,
		id_cost :'',
		cons :'',
		panjang_marker :'',
		lebar_marker :'',
		bagian :'',
		buyer :'',
		buyerno:'',
		id_mark_entry :'',
		id_group_det:'',
		color :'',
		cutting_no:'',
		d_insert :'', //GenerateTableDetail(_id_number,_color)
	}
	getListDataSpreadingReportFormHeader()
		.then(_json			=>initData(_json))
		.then(_json			=>initHeader(_json))
		.then(X			 				=> Option("webservices/Opt_Color_Sr.php?id_number="+$id_url))//OPTION jenis item   )
		.then(data 				    	=> generate_option(data,'--Pilih Color--'))//generate bkb 
		.then(inj_option 				=> injectOptionToHtml(inj_option,'marker_color')) //generate bkb
		.then(_json						=>check_existing_data($id_url))
		//.then(inj_option 				=> GenerateTableDetail(Data.id_number,'WHITE')) //generate bkb
/* 	auth_page()
		.then(X			 				=> Option("webservices/Opt_Color_Sr.php?id_number="+$id_url))//OPTION jenis item   )
		.then(data 				    	=> generate_option(data,'--Pilih Color--'))//generate bkb 
		.then(inj_option 				=> injectOptionToHtml(inj_option,'marker_color')) //generate bkb  */		
});



$( window ).resize(function() {
  $("#marker_color").select2({ width: 'resolve' });  
 	if(Data.key_det == '1'){
		//$("#marker_color").val(Data.color).trigger("change");
		$("#marker_color").prop("disabled",true);
	} 

}); 

function modal_rasio(){
//$("#marker_color").select2({ width: 'resolve' });
setTimeout(function(){
	  $("#marker_color").select2({ width: 'resolve' });
	if(Data.key_det == '1'){
	//$("#marker_color").val(Data.color).trigger("change");
		$("#marker_color").prop("disabled",true);
	}	  
},1000)

	
}


function check_existing_data(){
	if(Data.key_det == '1'){
		$("#marker_color").val(Data.color).trigger("change");
		$("#marker_color").prop("disabled",true);
	}else{
		hide_loading();
		return 1;
	}
	
	
	
}

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
			"url": "webservices/getListSpreadingReport_number.php?id=" + $id_url,
			"type": "POST"
		},
		"columns": [
			//description
			{ "data": "ws" },
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
		data: { code: '1', format: '1', id_cost: id_me, clr: color, id: $url },     // multiple data sent using ajax
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

function disable_marker_color(){
	$("#marker_color").prop('disabled',true);
}
function enable_marker_color(){
	$("#marker_color").prop('disabled',false);
}
function get_rasio(that){
	$x_datatable = 0;
	$("#content_rasio").empty();
	disable_marker_color();
	GenerateTableDetail(Data.id_number,that.value);
	Data.color = that.value;
	var string = JSON.stringify(Data);
		get_header_rasio()
		.then(data_header 		 				=>inject_kerangka_header(data_header))
		.then(XX								=>GenerateTableDetail(Data.id_number,that.value))
		//.then(X									=>generate_data_rasio())
}


function get_header_rasio(){
	
	return new Promise(function(resolve, reject) {
		string =JSON.stringify(Data);
	$.ajax("webservices/Get_Size_Header.php",
		{
			type: "POST",
			data: {code:'1',data:Data},
			success: function (data) {
				data_ = jQuery.parseJSON(data);
				//_data_ = jQuery.parseJSON(data[0]);
				
				resolve(data_);
			},
			error: function (data) {
				alert("Error req");
			}
		});
	});	
	
}


function generate_size_dynamic_qty(populasi_qty_so,populasi_headers){
	
	var __table = '';
	
	for (i = 0; i < populasi_qty_so.length; i++) {
		for (j= 0; j < populasi_headers.length; j++) {
			var $size = populasi_headers[j].size;
				console.log(populasi_qty_so[i].$size);		
				__table += "<th>"+populasi_qty_so[i][$size]+"</th>";
		}			
		
	}		
	return __table;
	
}
function get_id_rasio(that){
	console.log($("#"+that.id).data("no_cutting"));
	var pecah = that.id.split("_");
	$(".checkBox").prop('checked',false)
	setTimeout(function(){
		$('#'+that.id).prop('checked',true);
		no_c = $("#"+that.id).data('no_cutting');
		console.log(no_c)
		$("#no_cutting").val(no_c);
		Data.id_group_det = pecah[1];		
	},100);
	

	
	
	
}
function generate_size_dynamic_rasio(populasi_rasio,populasi_headers){
	
	var __table = '';
	for (i = 0; i < populasi_rasio.length; i++) {
		if(Data.key_det == '1'){
			str_disabled_nya = 'disabled';
			str_checklis_nya = 'checked';
		}else{
			str_disabled_nya = '';
			str_checklis_nya = '';
		}
		
		var __j = i+1;
		__table +="<tr>"
			__table += "<th><input "+str_disabled_nya+"  "+str_checklis_nya+" class='checkBox'id='GR_"+populasi_rasio[i]['id']+"' type='checkbox' data-no_cutting='"+populasi_rasio[i]['id_group_det']+"' data-id_group_det='"+populasi_rasio[i]['id_group_det']+"' onclick=get_id_rasio(this)></th>";
			__table += "<th>Rasio ke "+__j+":</th>";
			for (j = 0; j < populasi_headers.length; j++) {
				var $size = populasi_headers[j].size;
				console.log(populasi_rasio[i]);	
				__table += "<th>"+populasi_rasio[i][$size]+"</th>";
			}
		__table +="</tr>"		
	}
	return __table;
}


function inject_kerangka_header(_json){
	//return false;
	var _table = "<table id='tbl_rasio' class='table-bordered display responsive' style='width:100%'>";
		_table +="<thead>";
        _table +="<tr>";
		_table +="<th>*</th>";
		_table +="<th>Size</th>";
		
		
	var populasi_headers = _json.records[0].headers;	
	var populasi_qty_so = _json.records[0].qty_so;	
	var populasi_rasio = _json.records[0].rasio;
	for (i = 0; i < populasi_headers.length; i++) {
			_table += "<th>"+populasi_headers[i].size+"</th>";
	}	
		_table +="</tr>"
		_table +="<tr>"
		_table +="<th>*</th>";
		_table +="<th>Qty SO </th>"
		
		_table += generate_size_dynamic_qty(populasi_qty_so,populasi_headers);
		
		_table +="</tr>"
		
		_table += generate_size_dynamic_rasio(populasi_rasio,populasi_headers);
        _table +="<thead>"
	 _table += "</table>"
	 setTimeout(function(){
		 console.log(_table);
		$("#content_rasio").append(_table);
		enable_marker_color()
		return _table;
	 },2000)
	
	
	
}
function getListDataSpreadingReportFormHeader(){
		return new Promise(function(resolve, reject) {
		string =JSON.stringify(Data);
	$.ajax("webservices/getListDataSpreadingReportFormHeader.php",
		{
			type: "POST",
			data: {code:'1',id_number:$id_url},
			success: function (data) {
				resolve(jQuery.parseJSON(data));
			},
			error: function (data) {
				alert("Error req");
			}
		});
		
	});	
	
	
	
}

function initHeader(){
$("#buyer").val(Data.buyer);
$("#po_buyer").val(Data.buyerno);
$("#dtpicker").val(Data.d_insert);
$("#panjang_marker").val(Data.panjang_marker);
$("#lebar_marker").val(Data.lebar_marker);
$("#cons").val(Data.cons);
$("#bagian").val(Data.bagian);
$("#no_cutting").val(Data.id_group_det);
}
function initData(_json){
	return[
	Data.color 			= decodeURIComponent(_json.records[0].color),
	Data.id_number 		= decodeURIComponent(_json.records[0].id_number),
	Data.id_group_det 	= decodeURIComponent(_json.records[0].id_group_det),
	Data.id_cost 		= decodeURIComponent(_json.records[0].id_cost),
	Data.id_mark_entry 	= decodeURIComponent(_json.records[0].id_mark_entry),
	Data.buyer 			= decodeURIComponent(_json.records[0].buyer),
	Data.buyerno		= decodeURIComponent(_json.records[0].buyerno),
	Data.d_insert		= decodeURIComponent(_json.records[0].d_insert),
	Data.panjang_marker = decodeURIComponent(_json.records[0].panjang_marker),
	Data.lebar_marker	= decodeURIComponent(_json.records[0].lebar_marker),
	Data.bagian			= decodeURIComponent(_json.records[0].bagian),	
	Data.cons			= decodeURIComponent(_json.records[0].cons),
	Data.key_det		= _json.key_det
	];
}


$x_datatable = 0;
function GenerateTableDetail(_id_number,_color){
	//var key_det = Data.key_det;
	//var id_number = Data.id_number
	setTimeout(function(){
		$(".dt-buttons").css("display","none");
	},2000)	
	
		if($x_datatable =='0' ){
			$x_datatable =$x_datatable +1;

	table = $("#inv_scrap_detail").DataTable({
		"processing": true,
		"serverSide": true,
		"lengthMenu": [[999999999], ["All"]],
		"ajax": {
			"url": "webservices/getListSpreadingReportDetail.php?id_number="+_id_number+"&color="+_color,
			"type": "POST"
		},
		"columns": [
			//checklist
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.is___checklist);
				},
				"className": "center"
			},
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.itemdesc);
				},
				//"className": "center"
			},			
				//fabric code3
			{ "data": "goods_code" },
			//color
			{ "data": "color" },
			//lot
			{ "data": "lot" },
			//roll_no
			{ "data": "roll_no" },
			//loc_qty
			{ "data": "loc_qty" },
			//is_lembar_gelar
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.is___lembar_gelar);
				},
				//"className": "center"
			},
			//is_sisa_gelar 9
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.is___sisa_gelar);
				},
				//"className": "center"
			},		
			//is_sambung_duluan_bisa 10
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.is___sambung_duluan_bisa);
				},
				//"className": "center"
			},		
			//is_sisa_tidak_bisa 11
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.is___sisa_tidak_bisa);
				},
				//"className": "center"
			},		
			//is_qty_reject_yds 12
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.is___qty_reject_yds);
				},
				//"className": "center"
			},	
			//is_total_yds 13
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.is___total_yds);
				},
				//"className": "center"
			},	
			//is_total_yds 14			
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.is___short_roll);
				},
				//"className": "center"
			},			
			//is_percent 15
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.is___percent);
				},
				//"className": "center"
			},		
			//is_remark
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.is___remark);
				},
				//"className": "center"
			}		

		

		],
				   "drawCallback": function(){
				hide_loading();
			},		
		"autoWidth": true,
		"scrollCollapse": true,
		scrollY: "500px",
		scrollX: true,
		scrollCollapse: true,
        fixedColumns:   {
            leftColumns: 3
        },		
		"destroy": true,
		//order: [[ 1, "asc" ]],
		"ordering": true,
		/*         fixedColumns:   {
					leftColumns: 7
				}, */
		dom: 'Bfrtip',
  
	});

		
		}
	
	

	//table.settings()[0].jqXHR.abort();
}




function back() {
	window.location = "../prod/?mod=PW";
}



function Save(){
	
	if(Data.key_det == '1'){
		format = '2';
		
	}
	//HEADER 
	
	
/* 	Data = {
		id_number: $id_url,
		id_cost :'',
		cons :'',
		panjang_marker :'',
		lebar_marker :'',
		bagian :'',
		buyer :'',
		buyerno:'',
		id_mark_entry :'',
		id_group_det:'',
		color :'',
		cutting_no:'',
		d_insert :'', //GenerateTableDetail(_id_number,_color)
	} */
	
	
	Data.cons = $("#cons").val();
	Data.panjang_marker = $("#panjang_marker").val();
	Data.lebar_marker = $("#lebar_marker").val();
	Data.bagian = $("#lebar_marker").val();
	
//	console.log( $(".is_lembar_gelar").val() );
	populasi_id_roll = [];
	detail = [];
		//is_sisa_gelar__90032
	
	$('.is___checklist').each(function() {
		_js = {}; 
		pecah_id 					= this.id.split("__");
		console.log(pecah_id);
		_bppbno						= $("#"+this.id).data("bppbno");
		_id_roll_det 				 = pecah_id[2];
		_is_lembar_gelar             = $("#is___lembar_gelar__"+pecah_id[2]).val();
		_is_sisa_gelar               = $("#is___sisa_gelar__"+pecah_id[2]).val();
		_is_sambung_duluan_bisa      = $("#is___sambung_duluan_bisa__"+pecah_id[2]).val();
		_is_sisa_tidak_bisa          = $("#is___sisa_tidak_bisa__"+pecah_id[2]).val();
		_is_qty_reject_yds           = $("#is___qty_reject_yds__"+pecah_id[2]).val();
		_is_total_yds                = $("#is___total_yds__"+pecah_id[2]).val();
		_is_percent                  = $("#is___percent__"+pecah_id[2]).val();
		_is_remark                   = $("#is___remark__"+pecah_id[2]).val();
		_is_short_roll				 = $("#is___short_roll__"+pecah_id[2]).val();
		
		_js = {
			bppbno					: _bppbno,
			id_roll_det 			: _id_roll_det,
			is_lembar_gelar         : _is_lembar_gelar,
			is_sisa_gelar           : _is_sisa_gelar,        
			is_sambung_duluan_bisa  : _is_sambung_duluan_bisa, 
			is_sisa_tidak_bisa      : _is_sisa_tidak_bisa,     
			is_qty_reject_yds       : _is_qty_reject_yds,     
			is_total_yds            : _is_total_yds,           
			is_percent              : _is_percent,             
			is_remark               : _is_remark,             
			is_short_roll			: _is_short_roll			
		}
/* 		
		console.log(_js); */
		detail.push(_js);
})
setTimeout(function(){
	str_det 	= JSON.stringify(detail);
	str_header 	= JSON.stringify(Data);
	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveSpreadingReportDetail.php",
		data: { code: '1', format: format, header: str_header, detail: str_det },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			// console.log(data);
			d = JSON.parse(data);
			//console.log(d.message);
			if (d.respon == '200') {
				// Swal.fire("Success!", "Data Has Been Save", "success");
				// setTimeout(function () {
				alert('Data Berhasil Di Save');
				location.reload();
			//	window.location.href = "?mod=SpreadingReportForm&id_number="+$id_url;
				// }, 3000)
			}
		}
	});
},3000)
	
	
	
}


function newexportaction(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;
    dt.one('preXhr', function (e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;
        dt.one('preDraw', function (e, settings) {
            // Call the original action function
            if (button[0].className.indexOf('buttons-copy') >= 0) {
                $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-print') >= 0) {
                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
            }
            dt.one('preXhr', function (e, s, data) {
                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                // Set the property to what it was before exporting.
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });
            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
            setTimeout(dt.ajax.reload, 0);
            // Prevent rendering of the full data to the DOM
            return false;
        });
    });
    // Requery the server with the new one-time export settings
    dt.ajax.reload();
};
