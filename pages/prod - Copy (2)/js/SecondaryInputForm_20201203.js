$(document).ready(function () {

	// $(".save").prop("disabled", true)
	$("#detail_item").css("display", "none")

	url = window.location.search
	$split = url.split("id=")
	$id_edit = $split[1]

	Ddata = {
		id: '',
		tanggalinput: '',
		notes: '',
		inhousesubcon: '',
		deptsubcon: '',
		process: '',
		ws: '',
		panel: '',
	}
	item = []

	getListWS((typeof $split[1] === 'undefined' ? "-1" : $split[1]))
		.then(gen_option => generate_option(gen_option, 'Choose WS'))
		.then(inj_option => injectOptionToHtml(inj_option))
		.then(XX => getListInHouseSubcon())
		.then(gen_option => generate_option2(gen_option, 'Choose Inhouse/Subkon'))
		.then(inj_option => injectOptionToHtml2(inj_option))
		.then(check_crud => checkUrl($split[1]))
		// .then(B => initFormat($G_kondisi))
		.then(disable_ws => DoDisableWs($G_kondisi))
		.then(define_id_url => DoDefineId($G_kondisi, $split[1]))
		.then(A => initDeptSubcon($G_kondisi))
		.then(def_json_id => define_json_id((typeof $G_array === 'undefined' ? "0" : $G_array)))
		.then(getListHeader => getListData(getListHeader))
		.catch(error => {
			console.log(error);
			alert("Some think Wrong!")
		})

})


function Cancel() {
	window.location = "?mod=4WP"
}


/* membuat select option */
function getListWS($id_edit) {

	return new Promise(function (resolve, reject) {
		$.ajax("webservices/getListSecInWS.php?id_url=" + $id_edit,
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
			})
	})

}


function generate_option(PropHtmlData, judul) {

	var option = ""
	option += "<option value='' selected disabled>--" + judul + "--</option>"
	var i
	for (i = 0; i < PropHtmlData.records.length; i++) {
		var id_cost = decodeURIComponent(PropHtmlData.records[i].id)
		var ws = decodeURIComponent(PropHtmlData.records[i].ws)
		option += '<option value="' + id_cost + '">' + ws + '</option>'
	}
	return option

}


function injectOptionToHtml(string) {

	return $("#ws").append(string);

}


function getListInHouseSubcon() {

	return new Promise(function (resolve, reject) {
		$.ajax("webservices/getListSecInInhouseSubcon.php",
			{
				type: "POST",
				processData: false,
				contentType: false,
				success: function (data) {
					resolve(jQuery.parseJSON(data));
				},
				error: function (data) {
					alert("Error req")
				}
			})
	})

}


function generate_option2(PropHtmlData, judul) {

	var option = ""
	option += "<option value='' selected disabled>--" + judul + "--</option>";
	var i
	for (i = 0; i < PropHtmlData.records.length; i++) {
		var id = decodeURIComponent(PropHtmlData.records[i].id)
		var nama = decodeURIComponent(PropHtmlData.records[i].nama)
		option += '<option value="' + id + '">' + nama + '</option>'
	}
	return option

}


function injectOptionToHtml2(string) {

	return $("#inhousesubcon").append(string)

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
	}
	else {
		return $("#ws").attr("disabled", false)
	}
	return $("#ws").append(string)

}


function DoDefineId(kondisi) {

	if (kondisi == '1') {
		return id_url = $split[1]
	}
	else {
		return id_url = -1
	}

	return $("#ws").append(string)

}


function initDeptSubcon(kondisi) {

	if (kondisi == '1') {
		setTimeout(function () {
			console.log(Ddata.deptsubcon)
			$("#deptsubcon").val(Ddata.deptsubcon).trigger("change")
		}, 1000)
	}
	else {
		return 1
	}

}


function define_json_id(array) {

	if (array == '0') {
		return "1"
	}
	else {
		return $data.id = decodeURIComponent(array.records[0].id)
	}

}


function getListDeptSubcon() {

	$("#deptsubcon").empty()
	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/getListSecInDeptSubcon.php",
		data: { code: '1', id_inhouse_subcon: Ddata.inhousesubcon },
		success: function (response) {
			data = response
			d = JSON.parse(data)
			//d = response;
			option = ''
			renders = ''
			if (d.message == '1') {
				//	console.log(d.records);
				for (i = 0; i < d.records.length; i++) {
					option += "<option value=" + decodeURIComponent(d.records[i].id) + ">" + decodeURIComponent(d.records[i].dept) + "</option>"
				}
				$("#deptsubcon").append(option)
			}
		}
	})

}


function getListProcess(id_cost) {

	$("#process").empty()
	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/getListSecInProcess.php",
		data: { id_ws: id_cost },
		success: function (response) {
			data = response;
			d = JSON.parse(data);
			//d = response;
			option = '';
			renders = '';
			if (d.message == '1') {
				// console.log(d.records);
				for (i = 0; i < d.records.length; i++) {
					option += "<option value=" + decodeURIComponent(d.records[i].id) + ">" + decodeURIComponent(d.records[i].process) + "</option>"
				}
				$("#process").append(option)
			}
		}
	})

}


function getListPanel(id_cost) {

	$("#panel").empty()
	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/getListSecInPanel.php",
		data: { code: '1', id_ws: id_cost },
		success: function (response) {
			data = response
			d = JSON.parse(data)
			//d = response;
			option = ''
			renders = ''
			if (d.message == '1') {
				// console.log(d.records);
				for (i = 0; i < d.records.length; i++) {
					option += "<option value=" + decodeURIComponent(d.records[i].id) + ">" + decodeURIComponent(d.records[i].panel) + "</option>"
				}
				$("#panel").append(option)
			}
		}
	})

}


$x_datatable = 0
function getDetail() {

	$("#detail_item").show()
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
			"ajax": "webservices/getListSecInDetail.php?id_ws=" + Ddata.ws + "&id_url=" + $id_edit + "&id_process=" + Ddata.process + "&id_panel=" + Ddata.panel,
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
						console.log(data);
						return decodeURIComponent(data.qty_qc);
					}
				},
				{
					"data": null,
					"render": function (data) {
						console.log(data);
						return decodeURIComponent(data.number_cutting);
					}
				},
				{
					"data": null,
					"render": function (data) {
						console.log(data);
						return decodeURIComponent(data.number_bundle);
					}
				},
				{
					"data": null,
					"render": function (data) {
						console.log(data);
						return decodeURIComponent(data.number_sack);
					}
				},
				{
					"data": null,
					"render": function (data) {
						console.log(data);
						return decodeURIComponent(data.qty_reject_si);
					}
				},
				{
					"data": null,
					"render": function (data) {
						console.log(data);
						return decodeURIComponent(data.qty_sec_in);
					}
				}
			],
			"rowCallback": function (nRow, data, index) {
				if ($_inx != index) {
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
			scrollY: "300px",
			scrollCollapse: true,
			scrollXInner: "100%",
			paging: false,
			// fixedColumns: true,
		})
	}

}


function handlekeyup(myValue) {

	if (myValue.id == "tanggalinput") {
		Ddata.tanggalinput = myValue.value
	} else if (myValue.id == "notes") {
		Ddata.notes = myValue.value
	} else if (myValue.id == "inhousesubcon") {
		Ddata.inhousesubcon = myValue.value
	} else if (myValue.id == "deptsubcon") {
		Ddata.deptsubcon = myValue.value
	} else if (myValue.id == "ws") {
		Ddata.ws = myValue.value
	} else if (myValue.id == "process") {
		Ddata.process = $("#process").val()
	} else if (myValue.id == "panel") {
		Ddata.panel = $("#panel").val()
	}

	var id = myValue.id
	var index_array = id.split('_')
	var idx = index_array[1]
	var vl = myValue.value
	if (index_array[0] == 'reject') {
		item[idx].qty_reject_si_val = vl

		var i = parseFloat($("#qtyCN_" + idx).val())
		var j = parseFloat($("#reject_" + idx).val() != '' ? $("#reject_" + idx).val() : 0)
		var x = i - j

		$("#qtySecIn_" + idx).val(x)
	}

}



function save() {

	// alert('123')
	for (var i = 0; i < item.length; i++) {
		// alert(item[i].oke)
		if (parseInt(item[i].qty_val) > parseInt(item[i].oke)) {
			alert('Baris ke-' + item[i].no + ' Qty Input ' + '(' + item[i].qty_val + ')' + ' Lebih Besar Dari Qty Cutting ' + '(' + item[i].oke + ')')
			return false
		}
	}

	if (Ddata.id == "") {
		var format = "1"
	} else {
		var format = "2"
	}

	Ddata.tanggalinput = $("#tanggalinput").val()
	Ddata.notes = $("#notes").val()
	Ddata.inhousesubcon = $("#inhousesubcon").val()
	Ddata.deptsubcon = $("#deptsubcon").val()
	Ddata.process = $("#process").val()
	Ddata.ws = $("#ws").val()


	var stringDetail = JSON.stringify(item)
	var detail64 = btoa(stringDetail)


	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveSecondaryInput.php",
		data: { code: '1', format: format, data: Ddata, detail: detail64 },
		success: function (response) {
			data = response;
			// console.log(stringDetail);
			d = JSON.parse(data);
			//console.log(d.message);
			if (d.respon == '200') {
				alert('Data Success on Save')
				window.location.href = "?mod=4WP";
			}
			else {
				alert('Data Failed !')
			}
		}
	})

}


function getListData($id) {

	if ($G_kondisi == 0) {
		return "1";
	}
	else {
		$("#detail_item").show()
		$("#panel").prop("disabled", true)
		$("#cari").css("display", "none")
		item = []
		return new Promise(function (resolve, reject) {
			$.ajax({
				type: "POST",
				cache: false,
				url: "webservices/Sec_In_GetListData.php",
				data: { code: '1', id: $id },     // multiple data sent using ajax
				success: function (response) {
					data = response;
					console.log(data);
					d = JSON.parse(data);
					console.log(d.message);
					if (d.respon == '200') {
						//alert(d.records[0].sup)
						console.log(d.records)
						Ddata.id = decodeURIComponent(d.records[0].id)
						$("#ws").val(decodeURIComponent(d.records[0].ws)).trigger("change")
						$("#inhousesubcon").val(decodeURIComponent(d.records[0].insub)).trigger("change")
						// $("#process").val(decodeURIComponent(d.records[0].process)).trigger("change")
						$("#notes").val(decodeURIComponent(d.records[0].notes))
						Ddata.deptsubcon = decodeURIComponent(d.records[0].sup)

						getDetail()
						resolve("2");
					}
					else {
						alert('Erorr!')
					}
				}
			})
		})
	}

}