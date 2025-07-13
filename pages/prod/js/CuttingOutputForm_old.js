$(window).on("load", function () {
	// $("#dtpicker").datepicker({
	// 	dateFormat: "dd mm yyyy"
	// });
	$data = {
		id: '',
		id_jo: '',
		dtpicker: '',
		ws: ''
	}
	$detail = [];
	$f_json = {
		fabric_code: "",
		id_costing: "",
		id_cut_in_detail: "",
		color: "",
		fabric_desc: "",
		panel: "",
		grouping: "",
	};
	$is_view = 0;
	url = window.location.search;
	$split_nya = url.split("=");
	if ($split_nya[2]) {
		if ($split_nya[3]) {
			//$split_nya___ = $split_nya[2].split("&");
			if (typeof $split_nya[2].split("&") === 'undefined') {
				var die = '';
			} else {
				$split_nya___ = $split_nya[2].split("&");
				var tmp = $split_nya[2].split("&");
				$split_nya[2] = $split_nya___[0];
				$is_view = $split_nya___[1];
			}
		}
	}

	//console.log($split_nya[2]);

	$id_cut_out = 0;

	$('.simpan').prop('disabled', true)

	OptionWS((typeof $split_nya[2] === 'undefined' ? "-1" : $split_nya[2]))
		.then(gen_option => generate_option(gen_option, 'Choose WS'))
		.then(inj_option => injectOptionToHtml(inj_option))
		.then(check_crud => checkUrl($split_nya[2]))
		.then(disable_ws => DoDisableWs($G_kondisi))
		.then(X => refresh())
		.then(define_id_url => DoDefineId($G_kondisi, $split_nya[2]))	//$G_kondisi didapat dari fungsi DoDisableWs
		.then(getListHeader => getListData(getListHeader))
		.then(inj_prop_ws => injectPropWs((typeof $G_array === 'undefined' ? "0" : $G_array)))
		.then(def_json_id => define_json_id((typeof $G_array === 'undefined' ? "0" : $G_array)))//$G_id_ws didapat dari fungsi injectPropWs
		.then(gen_det => getDetail((typeof $G_array === 'undefined' ? "0" : decodeURIComponent($G_array.records[0].ws))))
		//.then(gen_det => getDetail2((typeof $G_array === 'undefined' ? "0" : decodeURIComponent($G_array.records[0].ws))))
		.then(ZZ => is_view($is_view))
		.catch(error => {
			console.log(error);
		});
});

function is_view($is_view) {
	console.log($is_view)
	if ($is_view == 'v') {
		return [$(".simpan").css('display', 'none'),
		setTimeout(function () {
			$('.1numbering').prop('disabled', true)
			$('.2numbering').prop('disabled', true)
			$('.embro').prop('disabled', true)
			$('.print').prop('disabled', true)
			$('.heat').prop('disabled', true)
			$('.cutt').prop('disabled', true)
			$('.reject').prop('disabled', true)
			$('.oke').prop('disabled', true)
		}, 7000)

		] //addrow
	} else {
		return 1;
	}
}
function generate_option(PropHtmlData, judul) {
	var option = "";
	option += "<option value=''>--" + judul + "--</option>";
	var i;
	for (i = 0; i < PropHtmlData.records.length; i++) {
		var id_cost = decodeURIComponent(PropHtmlData.records[i].id_cost);
		var color = decodeURIComponent(PropHtmlData.records[i].color);
		var ws = decodeURIComponent(PropHtmlData.records[i].ws);
		option += '<option value="' + id_cost + '_' + color + '">' + ws + '</option>';
	}
	$('.simpan').prop('disabled', false)
	return option;
}

function define_json_id(array) {
	return $data.id = decodeURIComponent(array.records[0].id);
}

function injectPropWs(array) {
	console.log(array);
	$G_id_ws = decodeURIComponent(array.records[0].ws);
	//alert(decodeURIComponent(array.records[0].ws));
	//return $("#ws").val(decodeURIComponent(array.records[0].ws)).trigger("change");
	return 1;
}

function injectOptionToHtml(string) {
	return $("#ws").append(string);
}

function DoDisableWs(kondisi) {
	if (kondisi == '1') {
		return $("#ws").attr("disabled", true);
	} else {
		return $("#ws").attr("disabled", false);
	}
	return $("#ws").append(string);
}

function DoDefineId(kondisi) {
	if (kondisi == '1') {
		getListData($split_nya[2]);
		$id_cut_out = $split_nya[2]
		getDetail2($id_cut_out);
		// GenerateTableDetailAll($id_cost);
		// alert($split_nya[2]);
		return id_url = $split_nya[2];
	} else {
		return id_url = -1;
	}
	return $("#ws").append(string);
}

function checkUrl(check_url_nya) {
	if (check_url_nya) {
		$G_kondisi = 1;
		$id_cut_out = check_url_nya
		return "1";
		//$("#ws").attr("disabled", true)

		//id_url = $split_nya[2]
	}
	else {
		$G_kondisi = 0;
		$id_cut_out = 0;
		return false;
		//id_url = -1
	}
}

function OptionWS(_id_url) {
	return new Promise(function (resolve, reject) {
		$.ajax("webservices/getListCutOutWS.php?id_url=" + _id_url,
			{
				type: "POST",
				processData: false,
				contentType: false,
				success: function (data) {
					resolve(jQuery.parseJSON(data));
				},
				error: function (data) {
					alert("Error req");
				}
			});
	});
}

function getDetail($id_cost) {
	// alert($id_cost)
	// return false;
	// $("#tbl_cut_in").table('show');

	GenerateTableDetail($id_cost);
}

function getDetail2($id_cut_out) {
	// alert($id_cost)
	// return false;
	// $("#tbl_cut_in").table('show');
	// $id_co = 

	GenerateTableDetailAll2($id_cut_out);
}

exist = 0;

function GenerateTableDetail($id_cost) {
	// alert($id_cost)
	// return false;
	TableDetail = $('#tbl_cut_out1').DataTable({
		"serverSide": true,
		"bSort": false,
		"destroy": true,
		"ajax": "webservices/getListCutOutDetailWS.php?id_cost=" + $id_cost,
		"columns": [
			{
				"data": "no",
				"className": "center"
			},
			{ "data": "fabric_code" },
			{ "data": "fabric_desc" },
			// { "data": "lot" },
			{
				"data": null,
				"className": "center",
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.lot);
				}
			},
			{
				"data": null,
				"className": "center",
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.select);
				}
			},
			{
				"data": null,
				"className": "center",
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.select2);
				}
			},
			{
				"data": null,
				"className": "center",
				"render": function (data) {
					if ($is_view == '0') {
						return decodeURIComponent(data.button);
					} else {
						return '';
					}

				}
			}
		],
		// "rowCallback": function (nRow, data, index) {
		// 	$data.qty_header = data.qty_header;
		// 	// if (exist == '0') {
		// 	$detail.push(data);
		// 	console.log($detail);
		// 	// exist = 1;
		// 	// }
		// },
		scrollX: true,
		scrollY: "250px",
		scrollCollapse: true,
		scrollXInner: "100%",
		paging: false,
		fixedColumns: true,

	})
}

// Proses Save
function Insert(id_cut_in_det) {

	if ($data.id == "") {
		var format = "1";
	} else {
		var format = "2";
	}

	// $detail
	//$('#'+that.id).data('bpbnya');
	$f_json.fabric_code = $('#tmp_html_' + id_cut_in_det).data('fabric_code');
	$f_json.id_costing = $('#tmp_html_' + id_cut_in_det).data('id_costing');
	$f_json.id_cut_in_detail = $('#tmp_html_' + id_cut_in_det).data('id_cut_in_detail');
	$f_json.color = $('#tmp_html_' + id_cut_in_det).data('color');
	$f_json.fabric_desc = $('#tmp_html_' + id_cut_in_det).data('fabric_desc');
	$f_json.panel = $('#panel_' + id_cut_in_det).val();
	$f_json.grouping = $('#group_' + id_cut_in_det).val();
	$f_json.lot = $('#lot_' + id_cut_in_det).val();

	$id_cost = $f_json.id_costing;
	// console.log($id_cost);
	// return false;

	$.ajax({
		type: "POST",
		//cache: false,
		url: "webservices/saveCuttingOutputCategory.php",
		data: { code: '1', format: format, detail: $f_json },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			// console.log(data);
			d = JSON.parse(data);
			//console.log(d.message);
			if (d.respon == '200') {
				$f_json.fabric_code = "";
				$f_json.id_costing = "";
				$f_json.id_cut_in_detail = "";
				$f_json.color = "";
				$f_json.fabric_desc = "";
				$f_json.panel = "";
				$f_json.grouping = "";
				$f_json.lot = "";

				// Swal.fire("Success!", "Data Has Been Add", "success");
				// setTimeout(function () { 
				alert('Data Berhasil Di Tambah')
				// GenerateTableDetailAll();
				if ($G_kondisi == '1') {
					GenerateTableDetailAll3($id_cost);
				} else {
					GenerateTableDetailAll($id_cost);
				}
				$('.addrow').attr('disabled', true);

				//window.location.href = "?mod=3WP";
				// }, 3000)
			}
			else {
				Swal.fire({
					type: 'Error!',
					title: 'Validation',
					text: d.message,
					footer: '-_-'
				});
			}
		}
	});
}


function GenerateTableDetailAll($id_cost) {
	TableDetail = $('#tbl_cut_out2').DataTable({
		"processing": true,
		"serverSide": true,
		"bSort": false,
		"ajax": "webservices/getListCutOutAllDetailWS.php?id_url=" + id_url + "&id_cost=" + $id_cost,
		"columns": [
			{
				"data": "num",
				"className": "center"
			},
			{ "data": "goods_code" },
			{ "data": "itemdesc" },
			{ "data": "grouping" },
			{ "data": "panel" },
			{ "data": "lot" },
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.inputEmbro);
				}
			},
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.inputPrint);
				}
			},
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.inputHeat);
				}
			},
			{ "data": "size" },
			{ "data": "dt" },
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.inputCutt);
				}
			},
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.inputReject);
				}
			},
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.fieldOkeCutt);
				}
			}
			// { "data": "oke" }
		],
		"rowCallback": function (nRow, data, index) {
			// $data.qty_header = data.qty_header;
			// if (exist == '0') {
			$detail.push(data);
			console.log($detail);
			// exist = 1;
			// }
		},
		"autoWidth": true,
		"destroy": true,
		fixedColumns: {
			leftColumns: 4
			// rightColumns: 1
		},
		scrollX: true,
		scrollY: "450px",
		scrollCollapse: true,
		scrollXInner: "120%",
		paging: false,

	})
}

function handleKeyUp(that) {
	var id = that.id;

	var index_array = id.split('_');
	var idx = index_array[1];

	var vl = that.value;
	// console.log(that.value)
	// return false;
	if (index_array[0] == '1numbering') {
		create_separator2(vl, id).then(function ($responnya) {
			$detail[idx].numbering1 = vl;
			$("#" + id).val($responnya.angka);
		});
	}
	else if (index_array[0] == '2numbering') {
		create_separator2(vl, id).then(function ($responnya) {
			$detail[idx].numbering2 = vl;
			$("#" + id).val($responnya.angka);
		});

	}
	else if (index_array[0] == 'embro') {
		create_separator2(vl, id).then(function ($responnya) {
			$detail[idx].embro = vl;
			$("#" + id).val($responnya.angka);
		});

	}
	else if (index_array[0] == 'print') {
		create_separator2(vl, id).then(function ($responnya) {
			$detail[idx].print = vl;
			$("#" + id).val($responnya.angka);
		});

	}
	else if (index_array[0] == 'heat') {
		create_separator2(vl, id).then(function ($responnya) {
			$detail[idx].heat = vl;
			$("#" + id).val($responnya.angka);
		});

	}
	else if (index_array[0] == 'cutt') {
		create_separator2(vl, id).then(function ($responnya) {
			$detail[idx].cutt = vl;
			$("#" + id).val($responnya.angka);
		});

	}
	else if (index_array[0] == 'reject') {
		create_separator2(vl, id).then(function ($responnya) {
			$detail[idx].reject = vl;
			$("#" + id).val($responnya.angka);
		});
	}
	console.log($detail)
}

function cuttNumber(that) {
	var id = that.id;

	var index_array = id.split('_');
	var idx = index_array[1];

	$("#" + that.id).val();

	var i = remove_separator($("#" + that.id).val());
	var j = remove_separator($("#reject_" + idx).val());
	var p = i - j;

	$("#oke_" + idx).val(p); // Menampilkan ke web
	$detail[idx].okeCutt = p; // Mengirim ke db
	// alert(p);
}

function rejectNumber(that) {
	var id = that.id;

	var index_array = id.split('_');
	var idx = index_array[1];

	$("#" + that.id).val();

	var i = remove_separator($("#cutt_" + idx).val());
	var j = remove_separator($("#" + that.id).val());
	var p = i - j;

	$("#oke_" + idx).val(p); // Menampilkan ke web
	$detail[idx].okeCutt = p; // Mengirim ke db
	// alert(p)
}

function mouseOver(that) {
	// console.log(that); return false;
	var id = that.id;
	var index_array = id.split('_');
	var idx = index_array[1];
	var vl = that.value;

	$('#oke_' + idx).prop('title', vl);
}


// Proses Save
function Save() {
	console.log($("#1numbering_" + i).val());
	for (var i = 0; i < $detail.length; i++) {
		var $t_1numbering = remove_separator($("#1numbering_" + i).val());
		var $t_2numbering = remove_separator($("#2numbering_" + i).val());
		var $t_embro = remove_separator($("#embro_" + i).val());
		var $t_print = remove_separator($("#print_" + i).val());
		var $t_heat = remove_separator($("#heat_" + i).val());
		var $t_cutt = remove_separator($("#cutt_" + i).val());
		var $t_reject = remove_separator($("#reject_" + i).val());

		if (isNaN($t_1numbering)) {
			alert("Numbering From baris ke-" + $detail[i].num + " Salah Format");
			$("#1numbering_" + i).val("0");
			return false;
		}
		if (isNaN($t_2numbering)) {
			alert("Numbering To baris ke-" + $detail[i].num + " Salah Format");
			$("#2numbering_" + i).val("0")
			return false;
		}
		if (isNaN($t_embro)) {
			alert("Embro To baris ke-" + i + " Salah Format");
			$("#embro_" + i).val("0")
			return false;
		}
		if (isNaN($t_print)) {
			alert("Print To baris ke-" + i + " Salah Format");
			$("#print_" + i).val("0")
			return false;
		}
		if (isNaN($t_heat)) {
			alert("Heat To baris ke-" + i + " Salah Format");
			$("#heat_" + i).val("0")
			return false;
		}
		if (isNaN($t_cutt)) {
			alert("Cutting To baris ke-" + $detail[i].num + " Salah Format");
			$("#cutt_" + i).val("0")
			return false;
		}
		if (isNaN($t_reject)) {
			alert("Reject baris ke-" + $detail[i].num + " Salah Format");
			$("#reject_" + i).val("0")
			return false;
		}
		$detail[i].numbering1 = $t_1numbering;
		$detail[i].numbering2 = $t_2numbering;
		$detail[i].embro = $t_embro;
		$detail[i].print = $t_print;
		$detail[i].heat = $t_heat;
		$detail[i].cutt = $t_cutt;
		$detail[i].reject = $t_reject;
	}
	//console.log($detail);

	/* return false; */


	if ($data.id == "") {
		var format = "1";
		G_format = "1";
	} else {
		var format = "2";
		G_format = "2";
	}
	$data.dtpicker = $("#dtpicker").val();
	$data.ws = $("#ws").val();

	//validation $data
	if ($data.ws == '') {
		Swal.fire({
			type: 'Error!',
			title: 'Validation',
			text: 'WS must be filled',
			footer: '-_-'
		});
		return false;
	}

	var stringDetail = JSON.stringify($detail);
	var detail64 = btoa(stringDetail);

	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveCuttingOutput.php",
		data: { code: '1', format: format, data: $data, detail: detail64, id_header: $id_cut_out },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			// console.log(detail); return false;
			d = JSON.parse(data);
			//console.log(d.message);
			if (d.respon == '200') {
				// Swal.fire("Success!", "Data Has Been Save", "success");
				// setTimeout(function () {
				alert('Data Berhasil Di Simpan')
				$('.addrow').attr('disabled', false);
				// if (G_format == "1") {
				window.location.href = "?mod=3LA&id=" + d.last_id;
				// } else {
				// 	location.reload();
				// }
				// window.location.href = "?mod=3WP";
				// }, 3000)
			}
			else {
				alert("Error");
			}
		}
	});
}

function Cancel() {
	window.location = "/pages/prod/?mod=3WP";
}

function getListData($id) {
	return new Promise(function (resolve, reject) {
		$.ajax({
			type: "POST",
			cache: false,
			url: "webservices/Cut_Out_GetListData.php",
			data: { code: '1', id: $id },     // multiple data sent using ajax
			success: function (response) {
				data = response;
				//console.log(data);
				d = JSON.parse(data);
				//console.log(d.message);
				if (d.respon == '200') {
					// alert($id)

					$data.id = decodeURIComponent(d.records[0].id);
					$G_array = d;
					$("#ws").val(decodeURIComponent(d.records[0].ws)).trigger("change");
					resolve(d);
				}
				else {
					alert('Data Berhasil Diubah')
				}
			}
		});
	});
}

function GenerateTableDetailAll2($id_cut_out) {
	$detail = [];
	TableDetail = $('#tbl_cut_out2').DataTable({
		"processing": true,
		"serverSide": true,
		"bSort": false,
		"ajax": "webservices/getListCutOutAllDetailWSEdit.php?id_cut_out=" + $id_cut_out,
		"columns": [
			{
				"data": "num",
				"className": "center"
			},
			{ "data": "goods_code" },
			{ "data": "itemdesc" },
			{ "data": "grouping" },
			{ "data": "panel" },
			{ "data": "lot" },
			{
				"data": null,
				"render": function (data) {
					//console.log(data);
					return decodeURIComponent(data.inputNumbering1);
				}
			},
			{
				"data": null,
				"render": function (data) {
					//	console.log(data);
					return decodeURIComponent(data.inputNumbering2);
				}
			},
			{
				"data": null,
				"render": function (data) {
					//console.log(data);
					return decodeURIComponent(data.inputEmbro);
				}
			},
			{
				"data": null,
				"render": function (data) {
					//console.log(data);
					return decodeURIComponent(data.inputPrint);
				}
			},
			{
				"data": null,
				"render": function (data) {
					//console.log(data);
					return decodeURIComponent(data.inputHeat);
				}
			},
			{ "data": "size" },
			{ "data": "dt" },
			{
				"data": null,
				"render": function (data) {
					//console.log(data);
					return decodeURIComponent(data.inputCutt);
				}
			},
			{
				"data": null,
				"render": function (data) {
					//console.log(data);
					return decodeURIComponent(data.inputReject);
				}
			},
			{
				"data": null,
				"render": function (data) {
					//console.log(data);
					return decodeURIComponent(data.fieldOkeCutt);
				}
			}
			// { "data": "oke" }
		],
		"rowCallback": function (nRow, data, index) {
			// $data.qty_header = data.qty_header;
			// if (exist == '0') {
			$detail.push(data);
			//	console.log($detail);
			// exist = 1;
			// }
		},
		"autoWidth": true,
		"destroy": true,
		fixedColumns: {
			leftColumns: 4
			// rightColumns: 1
		},
		scrollX: true,
		scrollY: "450px",
		scrollCollapse: true,
		scrollXInner: "120%",
		paging: false,

	})
}


function GenerateTableDetailAll3($id_cost) {
	$detail = [];
	TableDetail = $('#tbl_cut_out2').DataTable({
		"processing": true,
		"serverSide": true,
		"bSort": false,
		"ajax": "webservices/getListCutOutAllDetailWSEditAdv.php?id_cut_out=" + $id_cut_out + "&id_cost=" + $id_cost,
		"columns": [
			{
				"data": "num",
				"className": "center"
			},
			{ "data": "goods_code" },
			{ "data": "itemdesc" },
			{ "data": "grouping" },
			{ "data": "panel" },
			{ "data": "lot" },
			{
				"data": null,
				"render": function (data) {
					//console.log(data);
					return decodeURIComponent(data.inputNumbering1);
				}
			},
			{
				"data": null,
				"render": function (data) {
					//console.log(data);
					return decodeURIComponent(data.inputNumbering2);
				}
			},
			{
				"data": null,
				"render": function (data) {
					//console.log(data);
					return decodeURIComponent(data.inputEmbro);
				}
			},
			{
				"data": null,
				"render": function (data) {
					//console.log(data);
					return decodeURIComponent(data.inputPrint);
				}
			},
			{
				"data": null,
				"render": function (data) {
					//console.log(data);
					return decodeURIComponent(data.inputHeat);
				}
			},
			{ "data": "size" },
			{ "data": "dt" },
			{
				"data": null,
				"render": function (data) {
					//console.log(data);
					return decodeURIComponent(data.inputCutt);
				}
			},
			{
				"data": null,
				"render": function (data) {
					//console.log(data);
					return decodeURIComponent(data.inputReject);
				}
			},
			{
				"data": null,
				"render": function (data) {
					//	console.log(data);
					return decodeURIComponent(data.fieldOkeCutt);
				}
			}
			// { "data": "oke" }
		],
		"rowCallback": function (nRow, data, index) {
			// $data.qty_header = data.qty_header;
			// if (exist == '0') {
			$detail.push(data);
			//console.log($detail);
			// exist = 1;
			// }
		},
		"autoWidth": true,
		"destroy": true,
		fixedColumns: {
			leftColumns: 4
			// rightColumns: 1
		},
		scrollX: true,
		scrollY: "450px",
		scrollCollapse: true,
		scrollXInner: "120%",
		paging: false,

	})
}


function refresh() {
	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/refresh_cut_out_category.php",
		data: { code: '1' },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			//console.log(data);
			d = JSON.parse(data);
			//console.log(d.message);
			if (d.respon == '200') {
				// alert($id)
				console.log("data refress!");
			}
			else {
				alert('Data Berhasil Diubah')
			}
		}
	});
}

