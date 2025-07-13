$(window).on("load", function () {

	$(".save").attr("disabled", true)
	$("#detail_item").css("display", "none")
	// alert('123')

	url = window.location.search
	$split = url.split("id=")
	$id_edit = $split[1]

	Ddata = {
		id: '',
		tanggalinput: '',
		notes: '',
		id_supp: '',
		supplier: '',
		id_pro: '',
		proses: '',
		ws: ''
	}
	item = []


	getListWS((typeof $split[1] === 'undefined' ? "-1" : $split[1]))
		.then(gen_option => generate_option(gen_option, 'Choose WS'))
		.then(inj_option => injectOptionToHtml(inj_option))
		.then(check_crud => checkUrl($split[1]))
		.then(disable_ws => DoDisableWs($G_kondisi))
		.then(define_id_url => DoDefineId($G_kondisi, $split[1]))	//$G_kondisi didapat dari fungsi DoDisableWs
		.then(getListHeader => getListData(getListHeader))
		.then(def_json_id => define_json_id((typeof $G_array === 'undefined' ? "0" : $G_array)))
		.catch(error => {
			console.log(error);
			alert("Some think Wrong!")
		})

})


function getListWS($id_edit) {
	return new Promise(function (resolve, reject) {
		/*  */
		$.ajax("webservices/getListSecOutWS.php?id_url=" + $id_edit,
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
		var cost = decodeURIComponent(PropHtmlData.records[i].cost);
		var sec = decodeURIComponent(PropHtmlData.records[i].sec_id);
		var ws = decodeURIComponent(PropHtmlData.records[i].ws);
		option += '<option value="' + cost + '_' + sec + '">' + ws + '</option>';
	}
	return option;
}


function injectOptionToHtml(string) {
	return $("#ws").append(string);
}


function checkUrl(check_url_nya) {
	if (check_url_nya) {
		$G_kondisi = 1
		return "1"
	}
	else {
		$G_kondisi = 0
		return "1"
	}
}


function DoDisableWs(kondisi) {
	if (kondisi == '1') {
		return $("#ws").attr("disabled", true)
	} else {
		return $("#ws").attr("disabled", false)
	}
	return $("#ws").append(string)
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


function define_json_id(array) {
	if (array == '0') {
		return "1";
	} else {
		return $data.id = decodeURIComponent(array.records[0].id);
	}
}





/* membuat select option */

function handlekeyup(myValue) {
	if (myValue.id == "tanggalinput") {
		Ddata.tanggalinput = myValue.value
	} else if (myValue.id == "notes") {
		Ddata.notes = myValue.value
	} else if (myValue.id == "inhousesubcon") {
		Ddata.inhousesubcon = myValue.value
	} else if (myValue.id == "deptsubcon") {
		Ddata.deptsubcon = myValue.value
	} else if (myValue.id == "proses") {
		Ddata.proses = myValue.value
	} else if (myValue.id == "ws") {
		Ddata.ws = myValue.value
	}
	// else if (myValue.id == "panel") {
	// 	Ddata.panel = $("#panel").val()
	// }

	var id = myValue.id
	var index_array = id.split('_')
	var idx = index_array[1]
	var vl = myValue.value
	if (index_array[0] == 'reject') {
		item[idx].qty_reject_so_val = vl

		var i = parseFloat($("#qtySecIn_" + idx).val())
		var j = parseFloat($("#reject_" + idx).val() != '' ? $("#reject_" + idx).val() : 0)
		var x = i - j

		$("#qtySecOut_" + idx).val(x)
	}

}


function getList($id_cost) {
	// alert($id_cost); return false;
	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/getListSecOutDet.php",
		data: { code: '1', id: $id_cost },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			// console.log(data);
			d = JSON.parse(data);
			// console.log(d.message);
			if (d.status == 'ok') {
				// Ddata.id = decodeURIComponent(d.records[0].id);
				// $("#id_supp").val(decodeURIComponent(d.records[0].id_supp))
				$("#supplier").val(decodeURIComponent(d.records[0].supplier))
				// $("#id_pro").val(decodeURIComponent(d.records[0].id_pro))
				// $("#proses").val(decodeURIComponent(d.records[0].proses))
			}
			else {
				console.log('Data gak ketarik');
			}
		}
	});
}


$x_datatable = 0
function getDetail(id_cost) {

	$("#detail_item").show()
	$(".save").attr("disabled", false)
	// alert(id_cost); return false;
	if ($x_datatable == '0') {
		$_inx = 0
		$x_datatable = $x_datatable + 1

		item = []
		TableDetail = $('#detail_item').DataTable({
			"processing": true,
			"serverSide": true,
			"bSort": false,
			"method": "POST",
			"destroy": true,
			"ajax": "webservices/getListSecOutDetail.php?id_ws=" + id_cost + "&id_url=" + $id_edit,
			"columns": [
				{
					"data": "no",
					"className": "center"
				},
				{ "data": "fabric_desc" },
				{ "data": "color" },
				{ "data": "size" },
				{ "data": "name_grouping" },
				{ "data": "nama_panel" },
				{ "data": "lot" },
				{
					"data": null,
					"render": function (data) {
						// console.log(data);
						return decodeURIComponent(data.qty_sec_in);
					}
				},
				{
					"data": null,
					"render": function (data) {
						// console.log(data);
						return decodeURIComponent(data.number_cutting);
					}
				},
				{
					"data": null,
					"render": function (data) {
						// console.log(data);
						return decodeURIComponent(data.number_bundle);
					}
				},
				{
					"data": null,
					"render": function (data) {
						// console.log(data);
						return decodeURIComponent(data.number_sack);
					}
				},
				{
					"data": null,
					"render": function (data) {
						// console.log(data);
						return decodeURIComponent(data.qty_reject_so);
					}
				},
				{
					"data": null,
					"render": function (data) {
						// console.log(data);
						return decodeURIComponent(data.qty_sec_out);
					}
				}
			],
			"rowCallback": function (nRow, data, index) {
				if ($_inx != index) {
					// $_inx = $_inx - 3;
					return false
				}
				else {
					$_inx = $_inx + 1;
					// console.log(data.no)
					// console.log(data)
					item.push(data)
				}
			},
			scrollX: true,
			scrollY: "636px",
			scrollCollapse: true,
			scrollXInner: "100%",
			paging: false,
			// fixedColumns: true,
		})
	}

}

function Cancel() {
	// alert('123')
	window.location = "?mod=5WP";
}

function save() {

	for (var i = 0; i < item.length; i++) {
		// alert(item[i].oke)
		if (parseInt(item[i].qty_val) > parseInt(item[i].qty_input)) {
			alert('Baris ke-' + item[i].no + ' Qty Output ' + '(' + item[i].qty_val + ')' + ' Lebih Besar Dari Qty Input ' + '(' + item[i].qty_input + ')')
			return false
		}
	}

	if (Ddata.id == "") {
		var format = "1";
	} else {
		var format = "2";
	}

	Ddata.tanggalinput = $("#tanggalinput").val()
	Ddata.notes = $("#notes").val()
	// Ddata.id_supp = $("#id_supp").val()
	// Ddata.supplier = $("#supplier").val()
	// Ddata.id_pro = $("#id_pro").val()
	// Ddata.proses = $("#proses").val()
	Ddata.ws = $("#ws").val()


	var stringDetail = JSON.stringify(item)
	var detail64 = btoa(stringDetail)

	// console.log(item)
	// return false;

	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveSecondaryOutput.php",
		data: { code: '1', format: format, data: Ddata, detail: detail64 },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			// console.log(stringDetail);
			d = JSON.parse(data);
			//console.log(d.message);
			if (d.respon == '200') {
				alert('Data Success on Save')
				window.location.href = "?mod=5WP"
			}
			else {
				alert('Data Failed !')
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
		item = [];
		return new Promise(function (resolve, reject) {
			$.ajax({
				type: "POST",
				cache: false,
				url: "webservices/Sec_Out_GetListData.php",
				data: { code: '1', id: $id },
				success: function (response) {
					data = response;
					// console.log(data);
					d = JSON.parse(data);
					// console.log(d.message);
					if (d.respon == '200') {
						Ddata.id = decodeURIComponent(d.records[0].id)
						$("#ws").val(decodeURIComponent(d.records[0].cost)).trigger("change")
						$("#supplier").val(decodeURIComponent(d.records[0].supplier))
						// $("#id_supp").val(decodeURIComponent(d.records[0].id_supp))
						// $("#id_pro").val(decodeURIComponent(d.records[0].id_pro))
						// $("#proses").val(decodeURIComponent(d.records[0].proses))
						$("#notes").val(decodeURIComponent(d.records[0].notes))
					}
					else {
						alert('Erorr!')
					}
				}
			});
		});
	}
}