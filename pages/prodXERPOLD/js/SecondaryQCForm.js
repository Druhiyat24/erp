$(window).on("load", function () {
	$('#tanggalinput').datepicker
		({
			format: "dd M yyyy",
			autoclose: true
		});
	//show_loading();
	url = window.location.search;
	$id_url = 'X';
	format = 1;
	Data = {
		id_sec_qc_header: '0',
		no_sec_qc: '',
		inhousesubcon: '',
		deptsubcon: '',
		tanggalinput: '',
		notes: '',
	}
	get_url(url)//G_kondisi,format diinisialisasi disini $.ajax("webservices/getListSecInInhouseSubcon.php",
		.then(X => GenerateTableDetail("webservices/getListSecQCDetail.php?id_cost=0&id_url=0&id_proses=0&id_panel=0"))
		.then(X => Option("webservices/getListSecQcWS.php"))//OPTION LINE)
		.then(data => generate_option(data, '--Choose WS--'))//OPTION LINE 
		.then(inj_option => injectOptionToHtml(inj_option, 'ws')) //OPTION LINE 


		.then(X => Option("webservices/getListSecInInhouseSubcon.php"))//OPTION LINE   )
		.then(data => generate_option(data, '--Choose Department--'))//OPTION LINE 
		.then(inj_option => injectOptionToHtml(inj_option, 'inhousesubcon')) //OPTION LINE 


		.then(C => getListDataHeader($populasi_id_url))
		.then(default_data => inisialisasi_data_header(default_data, $populasi_id_url.G_kondisi))
		.then(D => inejectHeaderToHtml(Data, $populasi_id_url.G_kondisi))
		.then(DOM => trigger_detail())

});
$(window).resize(function () {
	$("#ws").select2({ width: 'resolve' });
	$("#line").select2({ width: 'resolve' });

});
$x_datatable = 0;

function Cancel() {
	window.location = "../prod/?mod=secQC"
}

function trigger_detail() {
	if ($populasi_id_url.G_kondisi == '1') {
		setTimeout(function () {
			$("#cari").trigger("click");
		}, 6000)
		return 1;
	}
	return 1;
}
function getDetail() {
	//alert("123");
	//event.preventDefault()
	$x_datatable = 0;

	if ($populasi_id_url.G_kondisi == '1') {
		$id_url = $populasi_id_url.id_sec_qc_header;
	}

	/* if($populasi_id_url.G_kondisi == '1'){
		
		
		
	} */
	if ($("#id_proses").val() == null) {
		alert("Process Must Be Fill");
		return false;
	} if ($("#id_panel").val() == null) {
		alert("Panel Must Be Fill");
		return false;
	}

	pop_id_proses = $("#id_proses").val();
	pop_id_panel = $("#id_panel").val();
	$str_id_proses = pop_id_proses.toString();
	$str_id_panel = pop_id_panel.toString();
	$id_cost = $("#ws").val();

	// GenerateTableDetail("webservices/getListSecQCDetail.php?id_cost=" + $id_cost + "&id_url=" + $id_url + "&id_proses="+$str_id_proses+"&id_panel="+$str_id_panel)

	setTimeout(function () {
		table.ajax.url("webservices/getListSecQCDetail.php?id_cost=" + $id_cost + "&id_url=" + $id_url + "&id_proses=" + $str_id_proses + "&id_panel=" + $str_id_panel).load()
	}, 300);
}


function GenerateTableDetail($_api) {
	setTimeout(function () {
		$(".dt-buttons").css("display", "none");
	}, 2000)

	//if ($x_datatable == '0') {
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
			"url": $_api,
			"type": "POST"
		},
		"columns": [
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.nama_item);
				},
				//"className": "center"
			},
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.color);
				},
			},
			//loc_qty
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.size);
				},
				"className": "right"
			},
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.shell);
				},
				"className": "right"
			},
			//is_lembar_gelar
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.nama_panel);
				},
			},

			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.lot);
				},
				//"className": "center"
			},
			//is_sambung_duluan_bisa 10

			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.balance_sec_qc);
				},
				//"className": "center"
			},
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.tot_qty_sec_out);
				},
				//"className": "center"
			},


			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.cutting_number);
				},
			},
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.bundle_number);
				},
			},

			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.sack_number);
				},
			},
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.is___qty_rpr);
				},
			},
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.is___qty_sec_qc);
				},
			},


		],
		"drawCallback": function () {
			hide_loading();
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


	//}



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
	Data.id_cost = $("#ws").val();
	Data.tanggalinput = $("#tanggalinput").val(); //inhousesubcon deptsubcon id_proses id_panel
	Data.notes = $("#notes").val();
	Data.inhousesubcon = $("#inhousesubcon").val();
	Data.deptsubcon = $("#deptsubcon").val();
	detail = [];


	var getTable = $('#table_detail tbody tr');
	getTable.each(function (i) {

		_js = {};
		$tds = $(this).find('td').find('input');
		$tdselect = $(this).find('td').find('select');
		for (var i = 0; i < $tds.length; i++) {
			console.log($tds[i].value);
			_js.is_qty = $tds[i].value;
			_js.is_rpr = $tds[i].value;
			//_js.is_id_defect			= $tdselect[i].value; 
		}
		if ($tds.length > 0) {
			$pecah_id = $("#" + $tds[0].id).attr('id'); //is___qty_sew_qc_out__ is_next_qty				: $tds.data('next_qty'),
			console.log($pecah_id);
			$serpihan_id = $pecah_id.split("__");

			/*
			
			id_panel",
			id_item",
			id_proses"
			
			*/

			_js.is_qty = $("#is___qty_sec_qc__" + $serpihan_id[2]).val();
			_js.is_rpr = $("#is___qty_rpr__" + $serpihan_id[2]).val();
			_js.is_id_so_det = $("#" + $tds[0].id).attr('data-id_so_det');
			_js.is_id_det = $("#" + $tds[0].id).attr('data-id_det');
			_js.is_balance = $("#" + $tds[0].id).attr('data-balance');
			_js.is_next_qty = $("#" + $tds[0].id).attr('data-next_qty');
			_js.is_id_panel = $("#" + $tds[0].id).attr('data-id_panel');
			_js.is_id_item = $("#" + $tds[0].id).attr('data-id_item');
			_js.is_id_proses = $("#" + $tds[0].id).attr('data-id_proses');
		}
		//console.log($tdselect[0].attr('id'));



		detail.push(_js);
		setTimeout(function () {
			console.log(detail);
		}, 3000)
	});
	/* 	console.log(Data);
		return false; */
	setTimeout(function () {
		str_det = JSON.stringify(detail);
		str_header = JSON.stringify(Data);
		$.ajax({
			type: "POST",
			cache: false,
			url: "webservices/saveSecondaryQC.php",
			data: { code: '1', format: '1', header: str_header, detail: str_det },     // multiple data sent using ajax
			success: function (response) {
				data = response;
				d = JSON.parse(data);
				if (d.respon == '200') {
					alert('Success Saved');
					setTimeout(function () {
						window.location = "../prod/?mod=secQC";
						//window.location = "../prod/?mod=QC_F_O_Form&id_sew_qc_final_out_header="+d.id_sew_qc_final_out_header;

					}, 1000)
				}
				if (d.respon == '201') {
					alert('Wrong Format On Row ' + d.baris);
					hide_loading();
					return false;
				}
				if (d.respon == '202') {
					hide_loading();
					alert('Qty Input Over On Row ' + d.baris);
					return false;
				}
				if (d.respon == '203') {
					hide_loading();
					alert('Qty Input Must be Greater Than 0 On Row ' + d.baris);
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
					alert("Qty Sec In Must be Smaller Than Qty Sec Out!");
					return false;
				}
				if (d.respon == '207') {
					hide_loading();
					alert('Wrong Format Qty Rpr On Row ' + d.baris);
					return false;
				}
				if (d.respon == '208') {
					hide_loading();
					alert('Qty Rpr Must be Greater Than 0 On Row' + d.baris);
					return false;
				}

			}
		});
	}, 3000)
}

function getListDataHeader($_pop_id_url) {
	if ($_pop_id_url.G_kondisi == '1') {
		my_id = $_pop_id_url.id_sec_qc_header

		return new Promise(function (resolve, reject) {
			$.ajax("webservices/Sec_Out_GetListData.php",
				{
					type: "POST",
					data: { id: my_id, code: 1 },
					success: function (data) {
						resolve(jQuery.parseJSON(data));
					},
					error: function (data) {
						alert("Error req");
					}
				});

		});
	} else {
		return 1;
	}
}

function inisialisasi_data_header(json, kondisi) {
	if (kondisi == '1') {
		return [
			Data.id_sec_qc_header = decodeURIComponent(json.records[0].id_sec_qc_header),
			Data.id_cost = decodeURIComponent(json.records[0].id_cost),     //inhousesubcon
			Data.tanggalinput = decodeURIComponent(json.records[0].date_output),
			Data.notes = decodeURIComponent(json.records[0].notes),
			Data.no_sec_qc = decodeURIComponent(json.records[0].no_sec_qc),
			Data.inhousesubcon = decodeURIComponent(json.records[0].inhousesubcon),
			Data.deptsubcon = decodeURIComponent(json.records[0].deptsubcon),
			Data.id_panel = decodeURIComponent(json.records[0].id_panel),
			Data.id_proses = decodeURIComponent(json.records[0].id_proses)
		];
	}
	else {
		return 1;
	}
	return 1;
}

function inejectHeaderToHtml(json, kondisi) {
	if (kondisi == '1') {
		setTimeout(function () {
			return [
				console.log(json),
				$("#tanggalinput").val(json.tanggalinput),
				$("#no_sec_qc").val(json.no_sec_qc),
				$("#notes").val(json.notes),
				$("#inhousesubcon").val(json.inhousesubcon),
				$("#inhousesubcon").trigger("change"),
				setTimeout(function () {
					$("#deptsubcon").val(json.deptsubcon);
					$("#deptsubcon").trigger("change");
					$("#id_panel").val(Data.id_panel.split(","));
					$("#id_proses").val(Data.id_proses.split(","));
					$("#id_panel").trigger("change");
					$("#id_proses").trigger("change");
				}, 2000),
				$("#ws").val(json.id_cost).trigger("change"),
				$("#ws").attr("disabled", true),
				$("#time").attr("disabled", true)
			];
		}, 2000)

	} else {
		return 1;
	}

}

function get_panel_proses(my_value) {
	$("#id_proses").empty();
	$("#id_panel").empty();
	setTimeout(function () {
		Option("webservices/getListSecInProcess.php?id_cost=" + my_value) //getListSecInProcess
			.then(data => generate_option(data, '--Choose Process--'))//OPTION LINE 
			.then(inj_option => injectOptionToHtml(inj_option, 'id_proses')) //OPTION LINE	
		Option("webservices/getListSecInPanel.php?id_cost=" + my_value) //getListSecInProcess
			.then(data => generate_option(data, '--Choose Panel--'))//OPTION LINE 
			.then(inj_option => injectOptionToHtml(inj_option, 'id_panel')) //OPTION LINE	
	}, 200)


}


function getListDeptSubcon(my_value) {
	$("#deptsubcon").empty();
	Option("webservices/getListSecInDeptSubcon.php?id_inhouse_subcon=" + my_value)
		.then(data => generate_option(data, '--Choose Dept/Subcon--'))//OPTION LINE 
		.then(inj_option => injectOptionToHtml(inj_option, 'deptsubcon')) //OPTION LINE
}
