$(window).on("load", function () {
	show_loading();
	url = window.location.search;
	format = 1;
	$split = url.split("=");
	$id_url = $split[2];
	$id_url__ = $id_url.split("&");
	$id_url = $id_url__[0];
	$add_more_item = 0;
	console.log($id_url);
	$color = decodeURIComponent($split[3]);
	console.log($color);
	Data = {
		key_det: '0',
		no_cutting: '',
		id_item: '',
		id_jo: '',
		id_number: $id_url,
		id_cost: '',
		id_so: '',
		id_panel: '',
		cons: '',
		panjang_marker: '',
		lebar_marker: '',
		bagian: '',
		buyer: '',
		buyerno: '',
		id_mark_entry: '',
		id_group_det: '',
		color: $color,
		id_group: '',
		bppbno_req: '',
		so_no: '',
		ws: '',
		cutting_no: '',
		bppbno_int: '',
		efficiency: '',
		yield: '',
		d_insert: '', //GenerateTableDetail(_id_number,_color)
	}
	getListDataSpreadingReportFormHeader()
		.then(_json => initData(_json))
		.then(_json => initHeader(_json))
		.then(XX => GenerateTableDetail('X', 'X', 'X', 'X'))
		.then(XX => cancel_datatable())
		.then(X => Option("webservices/Opt_Color_Sr.php?id_number=" + $id_url))//OPTION jenis item   )
		.then(data => generate_option(data, '--Choose Color--'))//generate bkb 
		.then(inj_option => injectOptionToHtml(inj_option, 'marker_color')) //generate bkb 
		.then(X => Option("webservices/getListDataSpreadingReportByItem.php?id_number=" + $id_url + "&id_so=" + Data.id_so + "&color=" + Data.color))//OPTION jenis item   )
		.then(data => generate_option(data, '--Choose Item--'))//generate bkb 
		.then(inj_option => injectOptionToHtml(inj_option, 'item')) //generate bkb			

		//	.then(X => Option("webservices/Opt_Bppb_req_sr.php?id_number=" + $id_url + "&id_so=" + Data.id_so + "&color=" + Data.color))
		//	.then(data => validasi_option(data, "../prod/?mod=SpreadingReport", "Bppb"))//generate bkb 
		//	.then(data => generate_option(data, '--Choose Request Number--'))//generate bkb 
		//	.then(inj_option => injectOptionToHtml(inj_option, 'no_req')) //generate bkb
		// .then(xxx => getListPanel())
		// .then(gen_option => generate_option_panel(gen_option, 'Choose Panel'))
		// .then(inj_option => injectOptionToHtml_panel(inj_option))
		.then(inj_option => setColor($color, 'marker_color')) //generate bkb
		.then(_json => check_existing_data($id_url))

	//.then(inj_option 				=> GenerateTableDetail(Data.id_number,'WHITE')) //generate bkb
	/* 	auth_page()
			.then(X			 				=> Option("webservices/Opt_Color_Sr.php?id_number="+$id_url))//OPTION jenis item   )
			.then(data 				    	=> generate_option(data,'--Pilih Color--'))//generate bkb 
			.then(inj_option 				=> injectOptionToHtml(inj_option,'marker_color')) //generate bkb  */
});

function cancel_datatable() {
	return;
}

function titleSize(val) {
	$("#size").prop("title", val)
}

$(window).resize(function () {
	$("#marker_color").select2({ width: 'resolve' });
	$("#marker_panel").select2({ width: 'resolve' });
	$("#item").select2({ width: 'resolve' });
	$("#no_req").select2({ width: 'resolve' });
	if (Data.key_det == '1') {
		//$("#marker_color").val(Data.color).trigger("change");
		$("#marker_color").prop("disabled", true);
	}
});


// function getListPanel() {
// 	return new Promise(function (resolve, reject) {
// 		/*  */
// 		$.ajax("webservices/getListSpreadingReportPanel.php",
// 			{
// 				type: "POST",
// 				processData: false,
// 				contentType: false,
// 				success: function (data) {
// 					resolve(jQuery.parseJSON(data));
// 				},
// 				error: function (data) {
// 					alert("Error req");
// 				}
// 			});
// 	});
// }


// function generate_option_panel(PropHtmlData, judul) {
// 	//console.log(PropHtmlData);

// 	var option = "";
// 	option += "<option value='' selected>--" + judul + "--</option>";
// 	var i;
// 	for (i = 0; i < PropHtmlData.records.length; i++) {
// 		var id = decodeURIComponent(PropHtmlData.records[i].id);
// 		var panel = decodeURIComponent(PropHtmlData.records[i].panel);
// 		option += '<option value="' + id + '">' + panel + '</option>';
// 	}
// 	return option;
// }


// function injectOptionToHtml_panel(string) {
// 	return $("#panel").append(string);
// }


function setColor(__color) {
	return [$("#marker_color").val(__color).trigger("change"),
		//alert(__color)
	];
}

function modal_rasio() {
	//$("#marker_color").select2({ width: 'resolve' });
	setTimeout(function () {
		$("#marker_color").select2({ width: 'resolve' });
		$("#marker_panel").select2({ width: 'resolve' });
		if (Data.key_det == '1') {
			//$("#marker_color").val(Data.color).trigger("change");
			$("#marker_color").prop("disabled", true);
		}
	}, 1000)


}


function check_existing_data() {
	if (Data.key_det == '1') {
		//$("#marker_color").val(Data.color).trigger("change");
		$("#marker_color").prop("disabled", true);
		format = 2;
		setTimeout(function () {
			exist_bppbno_req();
		}, 2000);
		setTimeout(function () {
			exist_item();
		}, 4000);
		setTimeout(function () {
			exist_marker_panel();
		}, 6000);
		//exist_marker_panel()
		//returns()
		//	GenerateTableDetail(Data.id_number, Data.color, Data.bppbno_req);
		//table.ajax.url( "webservices/getListSpreadingReportDetail.php?id_number=" + Data.id_number, + "&color=" + Data.color + "&key_det=" + Data.key_det + "&bppbno=" + Data.bppbno_req + "&id_item=" + Data.id_item ).load()
		//return 1;

		$("#add_more_item").css("display", "block");

	} else {
		hide_loading();
		return 1;
	}



}
function returns() {
	return 1;
}
function exist_bppbno_req() {
	$("#no_req").val(Data.bppbno_req).trigger("change")
	$("#no_req").prop("disabled", true)
	console.log(Data.bppbno_req)

}
function exist_marker_panel() {
	console.log(Data.id_panel);
	$("#marker_panel").val(Data.id_panel).trigger("change")
	//	$("#no_req").prop("disabled",true)
	//	console.log(Data.bppbno_req)

}
function exist_item() {

	return [
		$("#item").val(Data.id_jo + "_" + Data.id_item).trigger("change"),
		$("#item").prop("disabled", true),
		console.log(Data.id_jo + "_" + Data.id_item)
	];
}
function exist_panel() {
	return [
		$("#marker_panel").val(Data.id_panel).trigger("change")
	];
}

function getWS_sr() {
	return new Promise(function (resolve, reject) {
		$.ajax("webservices/getWS_sr.php",
			{
				type: "POST",
				data: { code: '1', id: $id_url },
				success: function (data) {
					d = jQuery.parseJSON(data)
					Data.ws = decodeURIComponent(d.records[0].ws);
					Data.id_costing = decodeURIComponent(d.records[0].id_cost);
					resolve(jQuery.parseJSON(data));
				},
				error: function (data) {
					alert("Error req");
				}
			});

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
function saveNomor() {
	Data.id_mark_entry = $id_url;
	Data.nomor_internal = $("#nomor_spreading_internal").val();
	if (Data.ws == '') {
		alert("Data Belum Siap");
		return false
	}
	if (!Data.nomor_internal) {
		alert("Nomor Spreading Salah Format");
		return false
	}
	if (Data.nomor_internal == '') {
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


function edit_nomor_spreading(that) {
	console.log(that);
	format = $(that).data('format');
	var no_spreading = $(that).data('spr_number');
	Data.id_number = $(that).data('id_number');
	if (Data.ws == '') {
		alert("Data Belum Siap!");
		return false;
	} else {
		$("#modals_ws").val(Data.ws);
	}

	if (format == '2') {
		$("#nomor_spreading_internal").val(no_spreading);
		//Data.id_number = id_number;
	}
}

function disable_marker_color() {
	$("#marker_color").prop('disabled', true);
}
function enable_marker_color() {
	$("#marker_color").prop('disabled', false);
}
function get_rasio(that) {
	$x_datatable = 0;
	$("#content_rasio").empty();
	disable_marker_color();
	//GenerateTableDetail(Data.id_number,that.value);
	//Data.color = that.value;
	var string = JSON.stringify(Data);
	get_header_rasio()
		.then(data_header => inject_kerangka_header(data_header))
	//.then(XX								=>GenerateTableDetail(Data.id_number,that.value))
	//.then(X									=>generate_data_rasio())
}
function get_panel_rasio(that) {
	$x_datatable = 0;
	$("#content_rasio").empty();
	//disable_marker_color();
	//GenerateTableDetail(Data.id_number,that.value);
	//Data.color = that.value;
	//var string = JSON.stringify(Data);
	get_init_id_panel(that.value)
		.then(X => get_header_rasio())
		.then(data_header => inject_kerangka_header(data_header))
	//.then(XX								=>GenerateTableDetail(Data.id_number,that.value))
	//.then(X									=>generate_data_rasio())
}

function get_init_id_panel(val_) {
	return new Promise(function (resolve, reject) {
		Data.id_panel = val_
		resolve("OK");
	});
}
function get_header_rasio() {
	// console.log(Data)

	return new Promise(function (resolve, reject) {
		string = JSON.stringify(Data);
		$.ajax("webservices/Get_Size_Header.php",
			{
				type: "POST",
				data: { code: '1', data: Data },
				success: function (data) {
					data_ = jQuery.parseJSON(data);
					resolve(data_);
				},
				error: function (data) {
					alert("Error req");
				}
			});
	});

}


function generate_size_dynamic_qty(populasi_qty_so, populasi_headers) {

	var __table = '';

	for (i = 0; i < populasi_qty_so.length; i++) {
		for (j = 0; j < populasi_headers.length; j++) {
			var $size = populasi_headers[j].size;
			console.log(populasi_qty_so[i].$size);
			__table += "<th>" + populasi_qty_so[i][$size] + "</th>";
		}

	}
	return __table;

}

function get_string_rasio() {



}

function get_id_rasio(that) {

	$("#rasio").val($("#" + that.id).data("rasio"));
	$("#bagian").val($("#" + that.id).data("nama_panel"));
	$("#qty_gelar").val($("#" + that.id).data("spread_group")); //lebar_marker panjang_marker
	$("#lebar_marker").val($("#" + that.id).data("width"));
	_var_length = $("#" + that.id).data("unit_yds") + " YDS, " + $("#" + that.id).data("unit_inch") + " INCH"
	$("#panjang_marker").val(_var_length);

	console.log($("#" + that.id).data("no_cutting"));
	var pecah = that.id.split("_");
	$(".checkBox").prop('checked', false)
	setTimeout(function () {
		$('#' + that.id).prop('checked', true);
		no_c = $("#" + that.id).data('no_cutting');
		console.log(no_c)
		$("#no_cutting").val(no_c);
		Data.id_group_det = pecah[1];
	}, 100);


	// alert(pecah[1])


}

function get_string_size(populasi_rasio, populasi_headers) {
	_string_rasio = "";
	for (j = 0; j < populasi_headers.length; j++) {

		var $size = populasi_headers[j].size;
		if (populasi_headers[j].size.length - 1) {
			_ratioSPT = ''
		}
		else {
			_ratioSPT = '|'
		}
		_string_rasio += populasi_rasio[i][$size] + " " + _ratioSPT + " ";

	}
	return _string_rasio;


}
function generate_size_dynamic_rasio(populasi_rasio, populasi_headers) {
	;
	var __table = '';
	for (i = 0; i < populasi_rasio.length; i++) {
		str_checklis_nya = "";
		_str_size = "";
		_str_rasio = get_string_size(populasi_rasio, populasi_headers);
		console.log(Data.id_group_det + "  " + populasi_rasio[i]['id_group_det']);
		if ((Data.id_group_det) === (populasi_rasio[i]['id_group_det'])) {
			str_checklis_nya = 'checked';


		}



		if (Data.key_det == '1') {
			str_disabled_nya = '';
			id_checklist = "GR_" + Data.id_group_det;
			setTimeout(function () {
				_str_s = $("#" + id_checklist).data("rasio");
				$("#rasio").val(_str_s);
				$("#bagian").val($("#" + id_checklist).data("nama_panel"));
				$("#qty_gelar").val($("#" + id_checklist).data("spread_group")); //lebar_marker panjang_marker
				$("#lebar_marker").val($("#" + id_checklist).data("width"));
				_var_length = $("#" + id_checklist).data("unit_yds") + " YDS, " + $("#" + id_checklist).data("unit_inch") + " INCH"
				$("#panjang_marker").val(_var_length);


			}, 4000)
		} else {
			str_disabled_nya = '';
			str_checklis_nya = '';
		}

		var __j = i + 1;

		__table += "<tr>"
		__table += "<th><input " + str_disabled_nya + "  " + str_checklis_nya + " data-rasio='" + _str_rasio + "' class='checkBox' id='GR_" + populasi_rasio[i]['id_group_det'] + "' type='checkbox' data-no_cutting='" + populasi_rasio[i]['id_group_det'] + "' data-id_group_det='" + populasi_rasio[i]['id_group_det'] + "' onclick='get_id_rasio(this)' data-nama_panel='" + populasi_rasio[i]['nama_panel'] + "' data-unit_yds='" + populasi_rasio[i]['unit_yds'] + "' data-unit_inch ='" + populasi_rasio[i]['unit_inch'] + "' data-spread_group='" + populasi_rasio[i]['spread_group'] + "' data-width='" + populasi_rasio[i]['width'] + "'></th>";
		__table += "<th>Rasio ke " + populasi_rasio[i]['id_group_det'] + ":</th>";
		for (j = 0; j < populasi_headers.length; j++) {
			var $size = populasi_headers[j].size;
			//console.log(populasi_rasio[i]);	
			__table += "<th>" + populasi_rasio[i][$size] + "</th>";
		}
		__table += "</tr>"
	}
	return __table;
}


function inject_kerangka_header(_json) {
	$("#content_rasio").empty();
	//return false;
	var _table = "<table id='tbl_rasio' class='table-bordered display responsive' style='width:100%'>";
	_table += "<thead>";
	_table += "<tr>";
	_table += "<th>*</th>";
	_table += "<th>Size</th>";


	var populasi_headers = _json.records[0].headers;
	var populasi_qty_so = _json.records[0].qty_so;
	var populasi_rasio = _json.records[0].rasio;
	_tmp_size = "";
	// alert(populasi_headers.length)
	for (i = 0; i < populasi_headers.length; i++) {
		if (populasi_headers[i].size.length - 1) {
			_separator = ''
		}
		else {
			_separator = '|'
		}
		_table += "<th>" + populasi_headers[i].size + "</th>";
		_tmp_size += populasi_headers[i].size + " " + _separator + " ";
	}
	setTimeout(function () {
		$("#size").val(_tmp_size);
		//alert(_tmp_size);
	}, 5000);



	_table += "</tr>"
	_table += "<tr>"
	_table += "<th>*</th>";
	_table += "<th>Qty SO </th>"

	_table += generate_size_dynamic_qty(populasi_qty_so, populasi_headers);

	_table += "</tr>"

	_table += generate_size_dynamic_rasio(populasi_rasio, populasi_headers);
	_table += "<thead>"
	_table += "</table>"
	setTimeout(function () {
		console.log(_table);
		$("#content_rasio").append(_table);
		//enable_marker_color()
		return _table;
	}, 2000)



}
function getDataReq(that) {
	// alert($color)
	if (that.value == '') {
		return false;
	} else {
		reset_x_datatable()
			.then(X => show_loading())
			//.then(X => getPopulasiItem(that.value))
			.then(X => Option("webservices/getListDataSpreadingReportByItem.php?bppbno_req=" + that.value + "&color=" + $color))//OPTION jenis item   )
			.then(data => generate_option(data, '--Choose Item--'))//generate bkb 
			.then(inj_option => injectOptionToHtml(inj_option, 'item')) //generate bkb	
			.then(inj_option => hide_loading()) //generate bkb		
		//.then(X => table.ajax.url( "webservices/getListSpreadingReportDetail.php?id_number=" + $id_url + "&color=" + Data.color + "&key_det=" + Data.key_det + "&bppbno=" + that.value ).load())
		//show_loading(X=> GenerateTableDetail($id_url, Data.color, that.value));
	}
}

function init_item_jo(val) {
	return [
		pecah_id = val.split("_"),
		Data.id_jo = pecah_id[0],
		Data.id_item = pecah_id[1]
	];
}

function getDataReqItem(that) {

	Data.bppbno_req = $("#no_req").val();
	reset_x_datatable()
		.then(X => show_loading())
		.then(X => init_item_jo(that.value))
		.then(X => Option("webservices/Opt_Panel_Sr.php?id_item=" + Data.id_item + "&id_jo=" + Data.id_jo + "&color=" + Data.color))
		.then(data => generate_option(data, '--Choose Panel--'))//generate bkb 
		.then(inj_option => injectOptionToHtml(inj_option, 'marker_panel')) //generate bkb
		.then(X => table.ajax.url("webservices/getListSpreadingReportDetail.php?id_number=" + $id_url + "&color=" + Data.color + "&key_det=" + Data.key_det + "&bppbno=" + Data.bppbno_req + "&id_item=" + that.value + "&add_more_item=" + $add_more_item).load())
		.then(X => hide_loading())
}
function reset_x_datatable() {
	return new Promise(function (resolve, reject) {
		$x_datatable = 0;
		resolve($x_datatable);
	});

}


function getListDataSpreadingReportFormHeader() {
	return new Promise(function (resolve, reject) {
		string = JSON.stringify(Data);
		$.ajax("webservices/getListDataSpreadingReportFormHeader.php",
			{ //decodeURIComponent(_json.records[0].color)
				type: "POST",
				data: { code: '1', id_number: $id_url },
				success: function (data) {
					//VALIDASI
					if (Data.color != decodeURIComponent(jQuery.parseJSON(data).records[0].color)) {
						alert("Ada Kesalahan Data #452(Color Tidak Sesuai)");
						_color = Data.color;
						_id_so = decodeURIComponent(jQuery.parseJSON(data).records[0].id_so);
						_id_mark_entry = decodeURIComponent(jQuery.parseJSON(data).records[0].id_mark_entry);
						window.location.href = window.location = "../prod/?mod=SpreadingReport";
					} else {
						resolve(jQuery.parseJSON(data));
					}
				},
				error: function (data) {
					alert("Error req");
				}
			});

	});
}

function initHeader() {
	$("#buyer").val(Data.buyer);
	$("#po_buyer").val(Data.buyerno);
	$("#dtpicker").val(Data.d_insert);
	$("#panjang_marker").val(Data.panjang_marker);
	$("#lebar_marker").val(Data.lebar_marker);
	$("#efficiency").val(Data.efficiency);
	$("#yield").val(Data.yield);
	$("#cons").val(Data.cons);
	$("#bagian").val(Data.bagian);
	$("#no_cutting").val(Data.id_group_det);
	$("#ws").val(Data.ws);
	$("#so").val(Data.so_no);
	$("#colors").val(Data.color);
}
function initData(_json) {
	return [
		Data.color = decodeURIComponent(_json.records[0].color),
		Data.id_number = decodeURIComponent(_json.records[0].id_number),
		Data.id_group_det = decodeURIComponent(_json.records[0].id_group_det),
		Data.id_group = decodeURIComponent(_json.records[0].id_group),
		Data.id_cost = decodeURIComponent(_json.records[0].id_cost),
		Data.id_mark_entry = decodeURIComponent(_json.records[0].id_mark_entry),
		Data.buyer = decodeURIComponent(_json.records[0].buyer),
		Data.buyerno = decodeURIComponent(_json.records[0].buyerno),
		Data.d_insert = decodeURIComponent(_json.records[0].d_insert),
		Data.panjang_marker = decodeURIComponent(_json.records[0].panjang_marker),
		Data.lebar_marker = decodeURIComponent(_json.records[0].lebar_marker),
		Data.efficiency = decodeURIComponent(_json.records[0].efficiency),
		Data.yield = decodeURIComponent(_json.records[0].yield),
		Data.bagian = decodeURIComponent(_json.records[0].bagian),
		Data.cons = decodeURIComponent(_json.records[0].cons),
		Data.ws = decodeURIComponent(_json.records[0].kpno),
		Data.so_no = decodeURIComponent(_json.records[0].so_no),
		Data.id_so = decodeURIComponent(_json.records[0].id_so),
		Data.bppbno_req = decodeURIComponent(_json.records[0].bppbno_req),
		Data.key_det = _json.key_det,
		Data.id_panel = decodeURIComponent(_json.records[0].id_panel),
		Data.id_item = decodeURIComponent(_json.records[0].id_item),
		Data.id_jo = decodeURIComponent(_json.records[0].id_jo),
		Data.bppbno_int = decodeURIComponent(_json.records[0].bppbno_int),
		console.log(Data)
	];
}


$x_datatable = 0;
function GenerateTableDetail(_id_number, _color, _bppbno, _id_item) {
	//var key_det = Data.key_det;
	//var id_number = Data.id_number
	setTimeout(function () {
		$(".dt-buttons").css("display", "none");
	}, 2000)

	if ($x_datatable == '0') {


		if (typeof table === 'undefined') {

		} else {
			//console.log(table.FixedColumns('false').draw());
			//$('#inv_scrap_detail').destroy();
			//table.columns.adjust().draw();
			table.destroy();

			if (typeof dt === 'undefined') {
				table.destroy();
			}
		}


		$x_datatable = $x_datatable + 1;
		$.fn.dataTable.ext.errMode = 'none';
		table = $("#inv_scrap_detail").on('error.dt', function (e, settings, techNote, message) {
			console.log('An error has been reported by DataTables: ', message);
		})

			.DataTable({
				"processing": true,
				"serverSide": true,
				"lengthMenu": [[999999999], ["All"]],
				"ajax": {
					"url": "webservices/getListSpreadingReportDetail.php?id_number=" + _id_number + "&color=" + _color + "&key_det=" + Data.key_det + "&bppbno=" + _bppbno,
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
					// { "data": "goods_code" },
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.goods_code);
						},
						//"className": "center"
					},
					//bpb
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.bppbno_int);
						},
					},
					//color
					// { "data": "color" },
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.color);
						},
						//"className": "center"
					},
					//lot
					// { "data": "lot" },
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.lot);
						},
						//"className": "center"
					},
					//roll_no
					{ "data": "roll_no", "className": "center" },
					//loc_qty
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.loc_qty);
						},
						"className": "right"
					},
					//is_lembar_gelar
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.is___lembar_gelar);
						},
						// "className": "center"
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
				"drawCallback": function () {
					hide_loading();
				},
				"stripeClasses": [],
				"autoWidth": true,
				"scrollCollapse": true,
				scrollY: "500px",
				scrollX: true,
				scrollCollapse: true,
				fixedColumns: {
					leftColumns: 3
				},
				colReorder: true,
				"destroy": true,
				//order: [[ 1, "asc" ]],
				"ordering": false,
				/*         fixedColumns:   {
							leftColumns: 7
						}, */
				dom: 'Bfrti',
			});


	}



	//table.settings()[0].jqXHR.abort();
}


function handleKeyUp(that) {
	// alert($("#panjang_marker").val())

	var lengthYdsInch = $("#panjang_marker").val()
	var lengthYI = lengthYdsInch.split(" ")

	var length = lengthYI[0] + '.' + lengthYI[2]
	var p = parseFloat(length)

	var index_name = that.split("&")
	var idx = index_name[1]
	// alert(index_name[0] + '@' + idx)
	// var vl = that.value

	if (index_name[0] == 'is___lembar_gelar') {
		var i = parseFloat($("#is___lembar_gelar__" + idx).val() != '' ? $("#is___lembar_gelar__" + idx).val() : 0)
		var j = parseFloat($("#is___sisa_gelar__" + idx).val() != '' ? $("#is___sisa_gelar__" + idx).val() : 0)
		var k = parseFloat($("#is___sambung_duluan_bisa__" + idx).val() != '' ? $("#is___sambung_duluan_bisa__" + idx).val() : 0)
		var l = parseFloat($("#is___sisa_tidak_bisa__" + idx).val() != '' ? $("#is___sisa_tidak_bisa__" + idx).val() : 0)
		var m = parseFloat($("#is___qty_reject_yds__" + idx).val() != '' ? $("#is___qty_reject_yds__" + idx).val() : 0)

		var x = (i * p) + j + k + l + m
		$("#is___total_yds__" + idx).val(x)
	}

	else if (index_name[0] == 'is___sisa_gelar') {
		var i = parseFloat($("#is___lembar_gelar__" + idx).val() != '' ? $("#is___lembar_gelar__" + idx).val() : 0)
		var j = parseFloat($("#is___sisa_gelar__" + idx).val() != '' ? $("#is___sisa_gelar__" + idx).val() : 0)
		var k = parseFloat($("#is___sambung_duluan_bisa__" + idx).val() != '' ? $("#is___sambung_duluan_bisa__" + idx).val() : 0)
		var l = parseFloat($("#is___sisa_tidak_bisa__" + idx).val() != '' ? $("#is___sisa_tidak_bisa__" + idx).val() : 0)
		var m = parseFloat($("#is___qty_reject_yds__" + idx).val() != '' ? $("#is___qty_reject_yds__" + idx).val() : 0)

		var x = (i * p) + j + k + l + m
		$("#is___total_yds__" + idx).val(x)
	}

	else if (index_name[0] == 'is___sambung_duluan_bisa') {
		var i = parseFloat($("#is___lembar_gelar__" + idx).val() != '' ? $("#is___lembar_gelar__" + idx).val() : 0)
		var j = parseFloat($("#is___sisa_gelar__" + idx).val() != '' ? $("#is___sisa_gelar__" + idx).val() : 0)
		var k = parseFloat($("#is___sambung_duluan_bisa__" + idx).val() != '' ? $("#is___sambung_duluan_bisa__" + idx).val() : 0)
		var l = parseFloat($("#is___sisa_tidak_bisa__" + idx).val() != '' ? $("#is___sisa_tidak_bisa__" + idx).val() : 0)
		var m = parseFloat($("#is___qty_reject_yds__" + idx).val() != '' ? $("#is___qty_reject_yds__" + idx).val() : 0)

		var x = (i * p) + j + k + l + m
		$("#is___total_yds__" + idx).val(x)
	}

	else if (index_name[0] == 'is___sisa_tidak_bisa') {
		var i = parseFloat($("#is___lembar_gelar__" + idx).val() != '' ? $("#is___lembar_gelar__" + idx).val() : 0)
		var j = parseFloat($("#is___sisa_gelar__" + idx).val() != '' ? $("#is___sisa_gelar__" + idx).val() : 0)
		var k = parseFloat($("#is___sambung_duluan_bisa__" + idx).val() != '' ? $("#is___sambung_duluan_bisa__" + idx).val() : 0)
		var l = parseFloat($("#is___sisa_tidak_bisa__" + idx).val() != '' ? $("#is___sisa_tidak_bisa__" + idx).val() : 0)
		var m = parseFloat($("#is___qty_reject_yds__" + idx).val() != '' ? $("#is___qty_reject_yds__" + idx).val() : 0)

		var x = (i * p) + j + k + l + m
		$("#is___total_yds__" + idx).val(x)
	}

	else if (index_name[0] == 'is___qty_reject_yds') {
		var i = parseFloat($("#is___lembar_gelar__" + idx).val() != '' ? $("#is___lembar_gelar__" + idx).val() : 0)
		var j = parseFloat($("#is___sisa_gelar__" + idx).val() != '' ? $("#is___sisa_gelar__" + idx).val() : 0)
		var k = parseFloat($("#is___sambung_duluan_bisa__" + idx).val() != '' ? $("#is___sambung_duluan_bisa__" + idx).val() : 0)
		var l = parseFloat($("#is___sisa_tidak_bisa__" + idx).val() != '' ? $("#is___sisa_tidak_bisa__" + idx).val() : 0)
		var m = parseFloat($("#is___qty_reject_yds__" + idx).val() != '' ? $("#is___qty_reject_yds__" + idx).val() : 0)

		var x = (i * p) + j + k + l + m
		$("#is___total_yds__" + idx).val(x)
	}
}


function check_uncheck(that) {
	$__x = $(".DTFC_Cloned ")
	__data_id = $(that).data('id')
	var __getTable = $('#inv_scrap_detail tbody tr');
	__getTable.each(function () {
		_compare = $(this).find('td:nth-child(1)').find('input').data('id');
		_attr = $(this).find('td:nth-child(1)').find('input');
		if (__data_id == _compare) {
			if ($(that).is(':checked')) {
				_attr.prop('checked', true);
			} else {
				_attr.prop('checked', false);
			}
		}
	});
}

function back() {
	window.location = "../prod/?mod=PW";
}


$bussy = 0;
function Save() {
	show_loading();
	var lengthYdsInch = $("#panjang_marker").val()
	var lengthYI = lengthYdsInch.split(" ")

	var length = lengthYI[0] + '.' + lengthYI[2]
	var p = parseFloat(length)
	// alert(p)
	// return false

	console.log($("#marker_panel").val());
	if ($("#item").val() == "--Choose Item--") {
		alert("Items Must Be Selected");
		$bussy = 1;
		hide_loading();
		return false;
	}
	if ($("#marker_panel").val() == "") {
		alert("Panel Must Be Selected");
		$("#klik_saya").trigger("click");
		$bussy = 1;
		hide_loading();
		return false;
	}

	Data.id_panel = $("#marker_panel").val();
	Data.cons = $("#cons").val();
	// Data.panjang_marker = $("#panjang_marker").val(p);
	Data.panjang_marker = p;
	Data.lebar_marker = $("#lebar_marker").val();
	Data.efficiency = $("#efficiency").val();
	Data.yield = $("#yield").val();
	Data.bagian = $("#bagian").val();
	var pecah_id = $("#item").val().split('_');
	Data.id_jo = pecah_id[0];
	Data.id_item = pecah_id[1];
	Data.no_cutting = $("#no_cutting").val()
	populasi_id_roll = [];
	detail = [];


	var getTable = $('#inv_scrap_detail tbody tr');
	getTable.each(function () {
		_js = {};
		props_ = $(this).find('td:nth-child(1)').find('input').data('id');
		_js = {};
		pecah_id = props_.split("__");
		_bppbno = $(this).find('td:nth-child(1)').find('input').data('bppbno');
		_id_roll_det = pecah_id[2];
		_id_det = $(this).find('td:nth-child(1)').find('input').data('id_srd');
		if ($(this).find('td:nth-child(1)').find('input').is(':checked')) {
			console.log("Check");
			_is_checklist = '1';

		} else {
			_is_checklist = '0';
			console.log("No_Check");
		}
		_is_lembar_gelar = $("#is___lembar_gelar__" + pecah_id[2]).val();
		_is_sisa_gelar = $("#is___sisa_gelar__" + pecah_id[2]).val();
		_is_sambung_duluan_bisa = $("#is___sambung_duluan_bisa__" + pecah_id[2]).val();
		_is_sisa_tidak_bisa = $("#is___sisa_tidak_bisa__" + pecah_id[2]).val();
		_is_qty_reject_yds = $("#is___qty_reject_yds__" + pecah_id[2]).val();
		_is_total_yds = $("#is___total_yds__" + pecah_id[2]).val();
		_is_percent = $("#is___percent__" + pecah_id[2]).val();
		_is_remark = $("#is___remark__" + pecah_id[2]).val();
		_is_short_roll = $("#is___short_roll__" + pecah_id[2]).val();

		_js = {
			bppbno: _bppbno,
			id_det: _id_det,
			id_roll_det: _id_roll_det,
			is_checklist: _is_checklist,
			is_lembar_gelar: _is_lembar_gelar,
			is_sisa_gelar: _is_sisa_gelar,
			is_sambung_duluan_bisa: _is_sambung_duluan_bisa,
			is_sisa_tidak_bisa: _is_sisa_tidak_bisa,
			is_qty_reject_yds: _is_qty_reject_yds,
			is_total_yds: _is_total_yds,
			is_percent: _is_percent,
			is_remark: _is_remark,
			is_short_roll: _is_short_roll
		}
		/* 		
				console.log(_js); */
		detail.push(_js);



	});

	console.log(detail);
	setTimeout(function () {
		str_det = JSON.stringify(detail);
		str_header = JSON.stringify(Data);
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
					alert('Data Success on Save');
					location.reload();
					//	window.location.href = "?mod=SpreadingReportForm&id_number="+$id_url;
					// }, 3000)
				}
			}
		});
	}, 3000)



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


function add_more_item() {
	show_loading();
	$add_more_item = 1;
	$("#cancel_more_item").css("display", "block");
	$("#add_more_item").css("display", "none");
	setTimeout(function () {
		table.ajax.url("webservices/getListSpreadingReportDetail.php?id_number=" + $id_url + "&color=" + Data.color + "&key_det=" + Data.key_det + "&bppbno=" + Data.bppbno_req + "&id_item=" + Data.id_jo + "_" + Data.id_item + "&add_more_item=" + $add_more_item).load()
	}, 2000)

}
function cancel_more_item() {
	show_loading();
	$add_more_item = 0;
	$("#cancel_more_item").css("display", "none");
	$("#add_more_item").css("display", "block");
	setTimeout(function () {
		table.ajax.url("webservices/getListSpreadingReportDetail.php?id_number=" + $id_url + "&color=" + Data.color + "&key_det=" + Data.key_det + "&bppbno=" + Data.bppbno_req + "&id_item=" + Data.id_jo + "_" + Data.id_item + "&add_more_item=" + $add_more_item).load()
	}, 2000)


}

//is___checklist

function all_check(that) {

	if ($(that).is(':checked')) {
		$(".is___checklist").prop('checked', true);

	} else {
		$(".is___checklist").prop('checked', false);
	}
}