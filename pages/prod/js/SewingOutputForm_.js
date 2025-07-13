$(window).on("load", function () {
	url = window.location.search;
	$split = url.split("id=");
	$id_edit = $split[1];

	Data = {
		id: '',
		notes: '',
		id_line: '',
		line: '',
		ws: ''
	}
	Detail = []

	OptionWS((typeof $split[1] === 'undefined' ? "-1" : $split[1]))
		.then(gen_option => generate_option(gen_option, 'Choose WS'))
		.then(inj_option => injectOptionToHtml(inj_option))
		.then(check_crud => checkUrl($split[1]))
		.then(B => initFormat($G_kondisi))//inisialisasi format (1=NEW, 2 =UPDATE
		.then(disable_ws => DoDisableWs($G_kondisi))
		.then(define_id_url => DoDefineId($G_kondisi, $split[1]))	//$G_kondisi didapat dari fungsi DoDisableWs
		.then(getListHeader => getListData(getListHeader))
		// .then(inj_prop_ws => injectPropWs($G_kondisi, (typeof $G_array === 'undefined' ? "0" : $G_array)))
		.then(def_json_id => define_json_id((typeof $G_array === 'undefined' ? "0" : $G_array)))
		.catch(error => {
			console.log(error);
			alert("Some think Wrong!")
		});

});


function OptionWS($id_edit) {
	return new Promise(function (resolve, reject) {
		/*  */
		$.ajax("webservices/getListSewOutWS.php?id_url=" + $id_edit,
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


function generate_option(PropHtmlData, judul) {
	//console.log(PropHtmlData);

	var option = "";
	option += "<option value=''>--" + judul + "--</option>";
	var i;
	for (i = 0; i < PropHtmlData.records.length; i++) {
		var id_cost = decodeURIComponent(PropHtmlData.records[i].id);
		var sew_in = decodeURIComponent(PropHtmlData.records[i].sew_in);
		var ws = decodeURIComponent(PropHtmlData.records[i].ws);
		option += '<option value="' + id_cost + '_' + sew_in + '">' + ws + '</option>';
	}
	return option;
}


function injectOptionToHtml(string) {
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
		return id_url = $split[1];
	} else {
		return id_url = -1;
	}

	// OptionWS(id_url)

	return $("#ws").append(string);
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


function define_json_id(array) {
	if (array == '0') {
		return "1";
	} else {
		return $data.id = decodeURIComponent(array.records[0].id);
	}
}



function getList($id_cost) {
	// alert($id_cost); return false;
	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/getListSewOutDet.php",
		data: { code: '1', id: $id_cost },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			console.log(data);
			d = JSON.parse(data);
			console.log(d.message);
			if (d.status == 'ok') {
				// Ddata.id = decodeURIComponent(d.records[0].id);
				$("#id_line").val(decodeURIComponent(d.records[0].id_line))
				$("#line").val(decodeURIComponent(d.records[0].nm_line))
			}
			else {
				console.log('Data gak ketarik');
			}
		}
	});
}


function handlekeyup(that) {
	var id = that.id
	var index_array = id.split('_')
	var idx = index_array[1]
	var vl = that.value

	if (index_array[0] == 'qty') {
		create_separator2(vl, id).then(function ($responnya) {
			Detail[idx].qty_val = vl
			$("#" + id).val($responnya.angka);
		});
	}
}


function getDetail(myValue) {
	// alert('123')

	return new Promise(function (resolve, reject) {

		Detail = [];
		TableDetail = $('#detail_item').DataTable({
			"processing": true,
			"serverSide": true,
			"bSort": false,
			"method": "POST",
			"destroy": true,
			"ajax": "webservices/getListSewOutDetail.php?id_ws=" + myValue + "&id_url=" + $id_edit,
			"columns": [
				{
					"data": "no",
					"className": "center"
				},
				{ "data": "buyer" },
				{ "data": "dest" },
				{ "data": "color" },
				{
					"data": "size",
					"className": "center"
				},
				{ "data": "unit" },
				{
					"data": "qty_input",
					"className": "right"
				},
				{
					"data": "bal",
					"className": "right"
				},
				{
					"data": null,
					"render": function (data) {
						console.log(data);
						return decodeURIComponent(data.qty_output);
					}
				}
			],
			"rowCallback": function (nRow, data, index) {
				Detail.push(data);
				//i_trigger = 1+1;
			},
			scrollX: true,
			scrollY: "300px",
			scrollCollapse: true,
			scrollXInner: "100%",
			paging: false,
			fixedColumns: true,
		});

	});

}

function Cancel() {
	// alert('123')
	window.location = "/pages/prod/?mod=9WP";
}

function save() {

	if (Data.id == "") {
		var format = "1";

		for (var i = 0; i < Detail.length; i++) {
			var $qty = remove_separator($("#qty_" + i).val());
			// alert($qty); return false
			if (isNaN($qty)) {
				alert("Qty Sewing Output baris ke-" + Detail[i].no + " Salah Format");
				$("#qty_" + i).val("0");
				return false;
			}
			// alert(item[i].oke)
			if (parseInt(Detail[i].qty_val) > parseInt(Detail[i].bal_val)) {
				alert('Baris ke-' + Detail[i].no + ' Qty Output ' + '(' + Detail[i].qty_val + ')' + ' Lebih Besar Dari Balance ' + '(' + Detail[i].bal_val + ')')
				return false
			}
		}
	} else {
		var format = "2";

		for (var i = 0; i < Detail.length; i++) {
			var $qty = remove_separator($("#qty_" + i).val());
			// alert($qty); return false
			if (isNaN($qty)) {
				alert("Qty Sewing Output baris ke-" + Detail[i].no + " Salah Format");
				$("#qty_" + i).val("0");
				return false;
			}
			// alert(item[i].oke)
			if (parseInt(Detail[i].qty_val) > parseInt(Detail[i].qty_inpuut_val)) {
				alert('Baris ke-' + Detail[i].no + ' Qty Output ' + '(' + Detail[i].qty_val + ')' + ' Terlalu Besar ')
				return false
			}
		}
	}

	Data.notes = $("#notes").val()
	Data.line = $("#id_line").val()
	Data.ws = $("#ws").val()


	var stringDetail = JSON.stringify(Detail);
	var detail64 = btoa(stringDetail);

	// alert('123'); return false;

	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveSewingOutput.php",
		data: { code: '1', format: format, data: Data, detail: detail64 },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			// console.log(stringDetail);
			d = JSON.parse(data);
			//console.log(d.message);
			if (d.respon == '200') {
				alert('Data Berhasil Di Save')
				window.location.href = "?mod=9WP";
			}
			else {
				alert('Data Gak Masuk')
			}
		}
	});
}


function getListData($id) {
	// $("#ws").prop("disabled", true)
	// $("#panel").prop("disabled", true)
	if ($G_kondisi == 0) {
		return "1";
	}
	else {
		Detail = [];
		return new Promise(function (resolve, reject) {
			$.ajax({
				type: "POST",
				cache: false,
				url: "webservices/Sew_Out_GetListData.php",
				data: { code: '1', id: $id },     // multiple data sent using ajax
				success: function (response) {
					data = response;
					console.log(data);
					d = JSON.parse(data);
					console.log(d.message);
					if (d.respon == '200') {
						Data.id = decodeURIComponent(d.records[0].id);
						$("#ws").val(decodeURIComponent(d.records[0].ws)).trigger("change")
						$("#id_line").val(decodeURIComponent(d.records[0].id_line))
						$("#line").val(decodeURIComponent(d.records[0].line))
						$("#notes").val(decodeURIComponent(d.records[0].notes))

						// getDetail()
						// resolve("2")

						/* 						if($G_kondisi == '0'){
													getDetail(decodeURIComponent(d.records[0].ws));
												} */
					}
					else {
						alert('Erorr!')
					}
				}
			});
		});
	}
}