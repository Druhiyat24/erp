$(window).on("load", function () {
    $('#date_output').datepicker
    ({  format: "dd M yyyy",
        autoclose: true
    });	
    $("#time").timepicker({
      showInputs: false,
      showMeridian: false,
	  autoclose: true
    });	
	//show_loading();
	url = window.location.search;
	$id_url = 'X';
	format = 1;		
	Data = {
		id_mut_qc_fin_header: '0',
		no_mut_qc_fin :'',
		id_cost: '',
		id_so: '',
		id_line: '',
		time :'',
		date_output :'',
		notes :'',
	}
	get_url(url)//G_kondisi,format diinisialisasi disini
		.then(X						=> check_cr($populasi_id_url.mod,$populasi_id_url.cr))
		.then(X						=> auth($populasi_id_url.mod,$populasi_id_url.cr))
		.then(akses					=> is_route(akses))	
		.then(akses					=> check_validation_again())	
		.then(X						=> GenerateTableDetail('X','X'))
		.then(X 					=> Option("webservices/Opt_Line_Si.php"))//OPTION LINE   )
		.then(data 					=> generate_option(data, '--Choose Line--'))//OPTION LINE 
		.then(inj_option 			=> injectOptionToHtml(inj_option, 'line')) //OPTION LINE 
		.then(X 					=> Option("webservices/Opt_Ws_Mut_Qc_fin.php"))//OPTION WS   )
		.then(data 					=> generate_option(data, '--Choose Ws--'))//OPTION WS 
		.then(inj_option 			=> injectOptionToHtml(inj_option, 'ws')) //OPTION WS 	
		.then(C						=> getListDataHeader($populasi_id_url))
		.then(default_data			=> inisialisasi_data_header(default_data,$populasi_id_url.G_kondisi))
		.then(D						=> inejectHeaderToHtml(Data,$populasi_id_url.G_kondisi))
		.then(is_views				=> is_view())		
		
});
$(window).resize(function () {
	$("#ws").select2({ width: 'resolve' });
	$("#line").select2({ width: 'resolve' });

});
$x_datatable = 0;	
function getDetail(that){
	if($populasi_id_url.G_kondisi == '1'){
		$id_url = $populasi_id_url.id_mut_qc_fin_header;
	}
 	setTimeout(function(){
		table.ajax.url( "webservices/getListMutQcFinDetail.php?id_cost=" + that.value + "&id_url=" + $id_url).load()		
	},300); 
	setTimeout(function(){
		$(".sel_defect").select2();
		
		
	},4000)
}


function GenerateTableDetail($_id_cost,$_id_url) {
	setTimeout(function () {
		$(".dt-buttons").css("display", "none");
	}, 2000)

	if ($x_datatable == '0') {


		if (typeof table === 'undefined') {

		} else {
			table.destroy();

			if (typeof dt === 'undefined') {
				table.destroy();
			}
		}
		$x_datatable = $x_datatable + 1;
		$.fn.dataTable.ext.errMode = 'none';
			table = $("#table_detail").DataTable({
				"processing": true,
				"serverSide": true,
				"lengthMenu": [[999999999], ["All"]],
				"ajax": {
					"url": "webservices/getListMutQcFinDetail.php?id_cost=" + $_id_cost + "&id_url=" + $_id_url,
					"type": "POST"
				},					
				"columns": [
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.so_no);
						},
						//"className": "center"
					},
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.buyerno);
						},
					},
					//loc_qty
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.dest);
						},
						"className": "right"
					},
					//is_lembar_gelar
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.color);
						},
					},
				
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.size);
						},
						//"className": "center"
					},
					//is_sambung_duluan_bisa 10
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.qty_so);
						},
						//"className": "center"
					},
					//is_sisa_tidak_bisa 11
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.unit);
						},
					},
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_qotiti);
						},
					},	
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_mut_qc_fin_out);
						},
					},		
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_plus_qty_mut);
						},
					},			
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.qty_transfer);
						},
					},						
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.is___qty_mut);
						},
					},					
				],
				"drawCallback": function () {
					hide_loading();
					is_view();
				},
				"stripeClasses": [],
				"autoWidth": true,
				"scrollCollapse": true,
				scrollY: "500px",
				scrollX: true,
				scrollCollapse: true,


				colReorder: true,
				"destroy": true,
				//order: [[ 1, "asc" ]],
				"ordering": false,
				/*         fixedColumns:   {
							leftColumns: 7
						}, */
				dom: 'Bfrtip',

			});


	}



	//table.settings()[0].jqXHR.abort();
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



function Save() {
	show_loading();
	format = $populasi_id_url.format;
	//console.log($("#marker_panel").val());
	if ($("#item").val() == "--Choose Item--") {
		alert("Items Must Be Selected");
		$bussy = 1;
		//hide_loading();
		return false;
	}
	if ($("#marker_panel").val() == "") {
		alert("Panel Must Be Selected");
		$("#klik_saya").trigger("click");
		$bussy = 1;
		hide_loading();
		return false;
	}
		Data.id_cost        = $("#ws").val();
		Data.id_line		= $("#line").val();
		Data.time			= $("#time").val();
		Data.date_output	= $("#date_output").val();
		Data.notes			= $("#notes").val();
		detail = [];
	
var getTable = $('#table_detail tbody tr');
	getTable.each(function (i) {
		
		_js = {};
		$tds = $(this).find('td').find('input');
		console.log($tds);
		$tdselect = $(this).find('td').find('select');
		for(var i=0;i<$tds.length;i++){
			console.log($tds[i].value);
			//_js.is_id_defect			= $tdselect[i].value;
		}
		if($tds.length > 0){
			$pecah_id = $("#"+$tds[0].id).attr('id'); //is___qty_sew_qc_out__ is_next_qty				: $tds.data('next_qty'),
			console.log($pecah_id);
		 	$serpihan_id = $pecah_id.split("__"); 
/* 			if($("#"+$tds[0].id).is(':checked')){
			   $checkeds = '1';
		   }else{
			   $checkeds = '0';
		   } */
			//_js.is_checklist			= $checkeds,
			_js.is_qty					= $("#"+$tds[0].id).val();
			_js.is_id_so_det			= $("#"+$tds[0].id).attr('data-id_so_det');
			_js.is_id_det				= $("#"+$tds[0].id).attr('data-id_det');
			_js.is_balance				= $("#"+$tds[0].id).attr('data-balance');
			_js.is_next_qty				= $("#"+$tds[0].id).attr('data-next_qty');
			_js.is_qty_current			= $("#"+$tds[0].id).attr('data-qty_current');
			detail.push(_js);
		}
		
	});
	//setTimeout(function(){ console.log(detail); },3000);
	//return false; 
	setTimeout(function () {
		str_det = JSON.stringify(detail);
		str_header = JSON.stringify(Data);
		$.ajax({
			type: "POST",
			cache: false,
			url: "webservices/Save_MutQcFinForm.php",
			data: { code: '1', format: format, header: str_header, detail: str_det },     // multiple data sent using ajax
			success: function (response) {
				data = response;
				d = JSON.parse(data);
				if (d.respon == '200') {
					alert('Success Saved');
					setTimeout(function(){
						window.location = "../prod/?mod=MUT_QC_FIN_PAGE";
						
					},1000)
				}
				if (d.respon == '201') {
					alert('Wrong Format On Row '+d.baris);
					hide_loading();
					return false;
				}	
				if (d.respon == '202') {
					hide_loading();
					alert('Qty Input Over On Row '+d.baris);
					return false;
				}
				if (d.respon == '203') {
					hide_loading();
					alert('Qty Input Must be Greater Than 0 On Row '+d.baris);
					return false;
				}	
				if (d.respon == '204') {
					hide_loading();
					alert(d.message);
					return false;
				}	
				if (d.respon == '205') {
					hide_loading();
					alert(d.message);
					return false;
				}	
/* 				if (d.respon == '206') {
					hide_loading();
					alert('Qty Input Must Better Then RPR On Row '+d.baris);
					return false;
				}	 */	
				if (d.respon == '206') {
					hide_loading();
					alert("Qty Sewing Qc Final Out Must be Smaller Than Qty Packing!");
					return false;
				}	
				if (d.respon == '207') {
					hide_loading();
					alert('Wrong Format Qty Rpr On Row '+d.baris);
					return false;
				}		
				if (d.respon == '208') {
					hide_loading();
					alert('Qty Rpr Must be Greater Than 0 On Row '+d.baris);
					return false;
				}	
				if (d.respon == '209') {
					hide_loading();
					alert('Qty Death Stock Must be Greater Than 0 On Row '+d.baris);
					return false;
				}	
				if (d.respon == '210') {
					hide_loading();
					alert('Wrong Format Qty Ds On Row '+d.baris);
					return false;
				}			
				if (d.respon == '110') {
					hide_loading();
					alert('Please Choose Minimal 1 Data ');
					return false;
				}				
				
			}
		});
	}, 3000)
}

function getListDataHeader($_pop_id_url){
	if($_pop_id_url.G_kondisi == '1'){
		my_id = $_pop_id_url.id_mut_qc_fin_header
	
	return new Promise(function(resolve, reject) {
	$.ajax("webservices/MutQcFinForm_GetListDataHeader.php",
		{
			type: "POST",
			data: {id:my_id,code:1},
			success: function (data) {
				resolve(jQuery.parseJSON(data));
			},
			error: function (data) {
				alert("Error req");
			}
		});
		
	});	
	}else{
		return 1;
	}
}

function inisialisasi_data_header(json,kondisi){
	if(kondisi =='1'){
	return [
		Data.id_mut_qc_fin_header		= decodeURIComponent(json.records[0].id_mut_qc_fin_header),	
		Data.id_cost        	= decodeURIComponent(json.records[0].id_cost),    
		Data.id_line        	= decodeURIComponent(json.records[0].id_line),    
		Data.time           	= decodeURIComponent(json.records[0].time),   
		Data.date_output    	= decodeURIComponent(json.records[0].date_output),
		Data.notes          	= decodeURIComponent(json.records[0].notes),
		Data.no_mut_qc_fin       = decodeURIComponent(json.records[0].no_mut_qc_fin)                     
	];                                           
	}
	else{
		return 1;
	}
	return 1;
}

function inejectHeaderToHtml(json,kondisi){
	if(kondisi == '1'){
		setTimeout(function(){
			return [
			console.log(json),
			$("#date_output").val(json.date_output),
			$("#time").val(json.time),
			$("#no_mut_qc_fin").val(json.no_mut_qc_fin),
			$("#notes").val(json.notes),
			$("#line").val(json.id_line).trigger("change"),
			$("#ws").val(json.id_cost).trigger("change"),
			$("#ws").attr("disabled", true),
			$("#time").attr("disabled", true)
			];			
		},2000)

	}else{
		return 1;
	}
	
}	

function all_check(that){
	
	
var getTable = $('#table_detail tbody tr');
	getTable.each(function () {
		var $tds = $(this).find('input');
		//console.log($tds[0].id);
		if($("#"+$tds[0].id).attr('data-balance') > 0){
			if ($(that).is(':checked')) {
				$("#"+$tds[0].id).prop('checked', true);
			} else {
				$("#"+$tds[0].id).prop('checked', false);
			}
		}
		
	});	
	
	
	
	
/* 	if ($(that).is(':checked')) {
		$(".is___checklist").prop('checked', true);
	} else {
		$(".is___checklist").prop('checked', false);
	} */
}