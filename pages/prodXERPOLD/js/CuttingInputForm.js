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
	$id_cut_in = 0;

	$('.simpan').prop('disabled', true)

	OptionWS((typeof $split_nya[2] === 'undefined' ? "-1" : $split_nya[2]))
		.then(gen_option => generate_option(gen_option, 'Choose WS'))
		.then(inj_option => injectOptionToHtml(inj_option))
		.then(check_crud => checkUrl($split_nya[2]))
		.then(disable_ws => DoDisableWs($G_kondisi))
		.then(define_id_url => DoDefineId($G_kondisi, $split_nya[2]))	//$G_kondisi didapat dari fungsi DoDisableWs
		.then(getListHeader => getListData(getListHeader))
		.then(inj_prop_ws => injectPropWs($G_kondisi, (typeof $G_array === 'undefined' ? "0" : $G_array)))
		.then(def_json_id => define_json_id((typeof $G_array === 'undefined' ? "0" : $G_array)))//$G_id_ws didapat dari fungsi injectPropWs
		//.then(gen_det  		=> getDetail( (typeof $G_array === 'undefined' ? "0":decodeURIComponent($G_array.records[0].ws)) ))
		.then(ZZ => is_view($is_view))
		.catch(error => {
			console.log(error);
			alert("Some think Wrong!");
		});

});

function is_view($is_view) {
	console.log($is_view)
	if ($is_view == 'v') {
		return [
			// $('.simpan').css('display', 'none'),
			$('.simpan').prop('disabled', true),
			// $('.refresh').css('display', 'none'),
			setTimeout(function () {
				$('.need').prop('disabled', true)
				$('.ret').prop('disabled', true)
				$('.cek').prop('disabled', true)
				$('.remark').prop('disabled', true)
			}, 3000)

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
		var ws = decodeURIComponent(PropHtmlData.records[i].ws);
		var id_jo = decodeURIComponent(PropHtmlData.records[i].id_jo);
		var req_no = decodeURIComponent(PropHtmlData.records[i].req_no);
		option += '<option value="' + id_cost + '_' + id_jo + '_' + req_no + '">' + ws + '</option>';
	}
	$('.simpan').prop('disabled', false)
	return option;
}

function define_json_id(array) {
	if (array == '0') {
		return "1";
	} else {
		return $data.id = decodeURIComponent(array.records[0].id);
	}
}

function injectPropWs(kondisi, array) {
	if (kondisi == '0') {
		return 1;
	} else {
		$G_id_ws = decodeURIComponent(array.records[0].ws);
		//alert(decodeURIComponent(array.records[0].ws));
		return $("#ws").val(decodeURIComponent(array.records[0].ws)).trigger("change");
	}
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
		return id_url = $split_nya[2];
	} else {
		return id_url = -1;
	}

	// OptionWS(id_url)

	return $("#ws").append(string);
}
function checkUrl(check_url_nya) {
	if (check_url_nya) {
		$G_kondisi = 1;
		return "1";
		//$("#ws").attr("disabled", true)

		//id_url = $split_nya[2]
	}
	else {
		$G_kondisi = 0;
		return "1";
		//id_url = -1
	}


}
function OptionWS(_id_url) {
	return new Promise(function (resolve, reject) {
		/*  */
		$.ajax("webservices/getListCutInWS.php?id_url=" + _id_url,
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
	if ($id_cost == "0") {
		return "1";
	} else {
		var id = $id_cost
		var id_cost = id.split('_')
		var cost = id_cost[0]
		var jo = id_cost[1]
		var req = id_cost[2]
		GenerateTableDetail(cost, jo, req);
	}
}

exist = 0;
i_trigger = 0;
function GenerateTableDetail(idcost, idjo, reqno) {


	$detail = [];
	TableDetail = $('#tbl_cut_in').DataTable({
		"processing": true,
		"serverSide": true,
		"bSort": false,
		"method": "POST",
		"destroy": true,
		"ajax": "webservices/getListCutInDetailWS.php?id_cost=" + idcost + "&id_url=" + id_url + "&id_jo=" + idjo + "&reqno=" + reqno,
		"columns": [
			{
				"data": "num",
				"className": "center"
			},
			{ "data": "fabric_code" },
			{ "data": "material_color" },
			{ "data": "fabric_desc" },
			{ "data": "lot" },
			// { "data": "lot_qty" },
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.need);
				}
			},
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.return);
				}
			},
			{ "data": "unit" },
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.button);
				}
			},
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.remark);
				}
			}
		],
		"rowCallback": function (nRow, data, index) {
			$detail.push(data);
			//i_trigger = 1+1;
		},
		scrollX: true,
		scrollY: "500px",
		scrollCollapse: true,
		scrollXInner: "100%",
		paging: false,
		fixedColumns: true,

	});
}

function handleKeyUp(that) {
	// console.log(that)

	var id = that.id;
	var index_array = id.split('_');
	var idx = index_array[1];
	var vl = that.value;
	if (index_array[0] == 'cek') {
		if ($('#cek_' + idx).is(':checked')) {
			// alert('123');
			// $("#need_" + idx).val($detail[idx].lot_qty);
			$detail[idx].cek_val = 1;
			// $('#need' + idx).val('20');
			// $detail[idx].need_val = $detail[idx].lot_qty;
		}
		else {
			$("#need_" + idx).val('0.00');
			$detail[idx].cek_val = 0;
			// $detail[idx].need_val = '0';
		}
	}

	if (index_array[0] == 'remark') {
		$detail[idx].remark_val = vl;
	}

	if (index_array[0] == 'need') {
		if (isNaN(that.value)) {
			alert("Value Salah!");
			$("#" + that.id).val('0');
			return false;
		}


		tot = $("#totin").val();
		// tot = 0;
		total = parseFloat(tot) - parseFloat($detail[idx].need_val) + parseFloat(vl);
		console.log(tot)
		console.log($detail[idx].need_val)
		console.log(vl)
		$("#totin").val(parseFloat(total));

		console.log(that.value)
		//return false;		


		create_separator(vl, id).then(function ($responnya) {
			$detail[idx].need_val = vl;
			// alert()
			$("#" + id).val($responnya.angka);

			// var x = $detail[idx].need_val * $detail[idx].need_val;
			// $("#totin").val(x);

			$('#cek_' + idx).prop('checked', true);
			if ($('#cek_' + idx).is(':checked')) {
				$detail[idx].cek_val = 1;
			}
			else {
				$detail[idx].cek_val = 0;
			}

			if ($("#need_" + idx).val() == '0' || $("#need_" + idx).val() == '0.00') {
				$('#cek_' + idx).prop('checked', false);
				$detail[idx].cek_val = 0;
			}
		});
	}

	if (index_array[0] == 'ret') {
		if (isNaN(that.value)) {
			alert("Value Salah!");
			$("#" + that.id).val('0');
			return false;
		}

		create_separator(vl, id).then(function ($responnya) {
			$detail[idx].return_val = vl;
			$("#" + id).val($responnya.angka);
		});
	}

}

function mouseOver(that) {
	// console.log(that); return false;
	var id = that.id;
	var index_array = id.split('_');
	var idx = index_array[1];
	var vl = that.value;

	$('#remark_' + idx).attr('title', vl);
}

//function needNumber(that) {
//	if(isNaN(that.value)){
//		//alert("Value Salah!");
//		$("#"+that.id).val('0');
//		return false;
//	}	
//	// console.log(that)
//	var id = that.id;
//	var index_array = id.split('_');
//	var idx = index_array[1];
//	var vl = that.value;
//
//	tot = $("#totin").val();
//
//	// $detail[idx].need_val = vl;
//	// $vl = $detail[idx].need_val;
//
//	total = parseFloat(tot) - parseFloat($detail[idx].need_val) + parseFloat(vl);
//	console.log(tot)
//	console.log($detail[idx].need_val)
//	console.log(vl)
//	$("#totin").val(parseFloat(total));
//}


function Save() {
	// console.log($detail.length)
	for (var i = 0; i < $detail.length; i++) {
		// alert($detail[i].isTotal)
		if ($detail[i].isTotal == '1') {
			// alert($detail[i].need_val)
			if (isNaN($detail[i].need_val)) {
				// alert($detail[i].need_val)
				alert("Qty Received From baris ke-" + $detail[i].num + " Salah Format");
				$("#need_" + i).val("0.00");
				return false;
			}

			// if (parseFloat($detail[i].need_val) > parseFloat($detail[i].lot_qty)) {
			// 	alert('Qty Received tidak boleh melebihi Lot Qty ' + i + ' ' + $detail[i].need_val + ' ' + $detail[i].lot_qty);
			// 	return false;
			// }
			// $detail[i].need_val = $t_need;
		}
	}

	if ($data.id == "") {
		var format = "1";
	} else {
		var format = "2";
	}

	$data.dtpicker = $("#dtpicker").val();
	$data.ws = $("#ws").val();

	$totin = $("#totin").val();
	$totout = $("#totout").val();


	console.log($totin);
	console.log($totout);

	if (isNaN($totin)) {
		alert("Format Total Salah");
		return false;
	}
	if (isNaN($totout)) {
		alert("Format Total Salah");
		return false;
	}

	//validation $data
	if ($data.ws == '') {
		alert('WS harus dipilih');
		return false;
	}

	if (parseFloat($totin) > parseFloat($totout)) {
		alert('Total terlalu besar dari BPPB');
		return false;
	}


	var stringDetail = JSON.stringify($detail);
	var detail64 = btoa(stringDetail);
	// alert(detail64)

	// return false;
	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveCuttingInput.php",
		data: { code: '1', format: format, data: $data, detail: detail64 },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			// console.log(stringDetail);
			d = JSON.parse(data);
			//console.log(d.message);
			if (d.respon == '200') {
				alert('Data Berhasil Di Save')
				window.location.href = "?mod=2WP";
			}
			else {
				alert('Data Gak Masuk')
			}
		}
	});
}

function getListData($id) {
	// alert($id)
	if ($G_kondisi == 0) {
		return "1";
	}
	else {

		$detail = [];
		return new Promise(function (resolve, reject) {
			$.ajax({
				type: "POST",
				cache: false,
				url: "webservices/Cut_In_GetListData.php",
				data: { code: '1', id: $id },     // multiple data sent using ajax
				success: function (response) {
					data = response;
					console.log(data);
					d = JSON.parse(data);
					console.log(d.message);
					if (d.respon == '200') {
						$G_array = d;
						resolve(d);
						//alert(decodeURIComponent(d.records[0].ws));
						//$data.id = decodeURIComponent(d.records[0].id);
						//$("#ws").val(decodeURIComponent(d.records[0].ws)).trigger("change");
						// alert($data.id)
						// $("#dtpicker").val(decodeURIComponent(d.records[0].dt));
					}
					else {
						alert('Data Berhasil Diubah')
					}
				}
			});
		});
	}
}

function Cancel() {
	window.location = "/pages/prod/?mod=2WP";
}


function Update($id_cost) {
	//alert($id_cost);
	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/updateBomCuttingInput.php?id_cost=" + $id_cost,
		data: { code: '1' },
		success: function (response) {
			data = response;
			console.log(data);
			d = JSON.parse(data);
			location.reload();
		}
	});
}