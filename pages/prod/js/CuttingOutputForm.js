$(document).ready(function () {

	url = window.location.search
	split = url.split("=")

	// alert(url)

	$("#tbl_cut_out1").hide()
	$("#tbl_cut_out2").hide()
	$(".simpan").prop("disabled", true)

	getOptionWS((typeof split[2] === 'undefined' ? "-1" : split[2]))
		.then(generate => generateOption(generate, "Choose WS"))
		.then(attach => attachOption(attach))
		.then(check_crud => checkUrl(split[2]))
		.then(disable_ws => DoDisableWs(G_condition))
		.then(X => refresh())
		.then(define_id_url => DoDefineId(G_condition, split[2]))
		// .then(getListHeader => getListData(getListHeader))
		.then(inj_prop_ws => injectPropWs((typeof G_array === 'undefined' ? "0" : G_array)))
		.then(def_json_id => define_json_id((typeof G_array === 'undefined' ? "0" : G_array)))
		.catch(error => {
			console.log(error)
		})

	$detail = []
	$f_json = {
		id_cost: "",
		code: "",
		description: "",
		color: "",
		// panel: "",
		grouping: ""
	}
	$data = {
		id: "",
		ws: "",
		date: "",
		color: ""
	}

	id_cut_out = 0

})


function Cancel() {
	window.location = "?mod=3WP"
}


function getOptionWS(_url) {

	return new Promise(function (resolve, reject) {
		$.ajax("webservices/getListCutOutWS.php?id_url=" + _url, {
			type: "POST",
			processData: false,
			contentType: false,
			success: function (data) {
				resolve(jQuery.parseJSON(data))
			},
			error: function (data) {
				alert("Error Request")
			}
		})
	})

}


function generateOption(generate, title) {

	var option = ""
	option += "<option value='' selected disabled>--" + title + "--</option>"
	var i
	for (i = 0; i < generate.records.length; i++) {
		var id_cost = decodeURIComponent(generate.records[i].id_cost)
		var ws = decodeURIComponent(generate.records[i].ws)
		option += "<option value='" + id_cost + "'>" + ws + "</option>"
	}
	return option

}


function attachOption(attach) {
	return $("#ws").append(attach)
}


function getColor(_cost) {
	var url = typeof split[2] === 'undefined' ? "-1" : split[2]
	$("#color").empty()
	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/getListCutOutColor.php?id_cost=" + _cost + "&id_url=" + url,
		data: { data: '1' },
		success: function (response) {
			data = response
			d = JSON.parse(data)
			//d = response;
			option = ''
			renders = ''
			if (d.message == '1') {
				//	console.log(d.records);
				option += "<option selected disabled>--Choose Color--</option>"
				for (i = 0; i < d.records.length; i++) {
					var color = decodeURIComponent(d.records[i].color)
					var ws_color = decodeURIComponent(d.records[i].ws_color)
					option += "<option value='" + ws_color + "'>" + color + "</option>"
				}
				$("#color").append(option)
			}
		}
	})
}


function checkUrl(check_url) {
	if (check_url) {
		G_condition = 1
		id_cut_out = check_url
		return "1"
	}
	else {
		G_condition = 0
		id_cut_out = 0
		return false
	}
}


function DoDefineId(condition) {
	if (condition == '1') {
		id_cut_out = split[2]
		getListData(split[2])
		setTimeout(function () {
			// console.log($data.color)
			$("#color").val($data.color).trigger("change")
		}, 1000)
		getDetail2(id_cut_out)
		return id_url = split[2]
	} else {
		return id_url = -1
	}
	// return $("#ws").append(string)
}


function DoDisableWs(condition) {
	if (condition == '1') {
		return $("#ws").attr("disabled", true), $("#color").attr("disabled", true)
	} else {
		return $("#ws").attr("disabled", false)
	}
	// return $("#ws").append(string)
}


function injectPropWs(array) {
	// console.log(array)
	G_id_ws = decodeURIComponent(array.records[0].ws)
	// G_color = decodeURIComponent(array.records[0].color)
	return 1
}


function define_json_id(array) {
	return $data.id = decodeURIComponent(array.records[0].id)
}


function getDetail(value) {
	// alert(value)
	$("#tbl_cut_out1").show()
	generateTableDetailItem(value)
}


function getDetail2($id_cut_out) {
	$("#tbl_cut_out2").show()
	generateTableDetailAll2($id_cut_out)
}


function generateTableDetailItem(value) {
	// alert(split[2])
	var url = typeof split[2] === 'undefined' ? "-1" : split[2]

	TableDetail = $("#tbl_cut_out1").DataTable({
		"processing": true,
		"serverSide": true,
		"bSort": false,
		"destroy": true,
		"searching": false,
		"ajax": "webservices/getListCutOutDetailWS.php?data=" + value + "&id=" + url,
		"columns": [
			{
				"data": "no",
				"className": "center"
			},
			{ "data": "code" },
			{ "data": "description" },
			{
				"data": null,
				"className": "center",
				"render": function (data) {
					// console.log(data)
					return decodeURIComponent(data.group)
				}
			},
			{
				"data": null,
				"className": "center",
				"render": function (data) {
					// console.log(data)
					return decodeURIComponent(data.button)
				}
			}
		],
		scrollX: true,
		scrollY: "250px",
		scrollCollapse: true,
		scrollXInner: "100%",
		paging: false,
		// fixedColumns: true,
	})

}


function getDataDetail(cost, color, item) {

	// alert($("group_" + item).val())
	// if ($("group_" + item).val(0) == "0") {
	// 	alert("Please Choose Group Shell")
	// }
	// else {
	// 	alert(item)
	// }
	// return false

	// if ($data.id == "") {
	// 	var format = "1"
	// } else {
	// 	var format = "2"
	// }

	// $detail

	$f_json.id_cost = $('#tmp_html_' + item).data('id_cost')
	$f_json.id_item = $('#tmp_html_' + item).data('id_item')
	$f_json.code = decodeURIComponent($('#tmp_html_' + item).data('code'))
	$f_json.description = decodeURIComponent($('#tmp_html_' + item).data('description'))
	$f_json.color = decodeURIComponent($('#tmp_html_' + item).data('color'))
	// $f_json.panel = $('#panel_' + id).val()
	$f_json.grouping = $('#group_' + item).val()
	// $f_json.lot = $('#lot_' + id).val()

	$id_cost = $f_json.id_cost
	// console.log($id_cost)
	// return false

	$.ajax({
		type: "POST",
		//cache: false,
		url: "webservices/saveCuttingOutputCategory.php",
		data: { code: '1', format: '1', detail: $f_json },
		success: function (response) {
			data = response
			// console.log(data)
			d = JSON.parse(data)
			//console.log(d.message)
			if (d.respon == '200') {
				$f_json.id_cost = ""
				$f_json.code = ""
				$f_json.description = ""
				$f_json.color = ""
				// $f_json.panel = ""
				$f_json.grouping = ""
				// $f_json.lot = ""

				alert('Success Add Items')
				$(".addrow").prop("disabled", true)
				$("#tbl_cut_out2").show()
				if (G_condition == '1') {
					generateTableDetailAll3(cost, color, item)
				} else {
					generateTableDetailAll(cost, color, item)
				}
			}
			else {
				alert('Not Success !')
			}
		}
	})

}


$x_datatable = 0
function generateTableDetailAll(cost, color, item) {

	$(".simpan").prop("disabled", false)
	if ($x_datatable == '0') {

		$_inx = 0
		$x_datatable = $x_datatable + 1
		$detail = []

		TableDetail = $("#tbl_cut_out2").DataTable({
			"processing": true,
			"serverSide": true,
			"bSort": false,
			"searching": false,
			"ajax": "webservices/getListCutOutAllDetailWS.php?cost=" + cost + "&color=" + color + "&item=" + item,
			"columns": [
				{
					"data": "num",
					"className": "center"
				},
				// { "data": "goods_code" },
				{ "data": "itemdesc" },
				{ "data": "grouping" },
				{ "data": "panel" },
				{ "data": "lot" },
				{
					"data": "size",
					"className": "center"
				},
				{
					"data": null,
					"render": function (data) {
						// console.log(data)
						return decodeURIComponent(data.qtySO)
					}
				},
				{
					"data": null,
					"render": function (data) {
						// console.log(data)
						return decodeURIComponent(data.cumulative)
					}
				},
				{
					"data": null,
					"render": function (data) {
						// console.log(data)
						return decodeURIComponent(data.balance)
					}
				},
				{
					"data": null,
					"render": function (data) {
						// console.log(data)
						return decodeURIComponent(data.inputCutt)
					}
				},
				{
					"data": null,
					"render": function (data) {
						// console.log(data)
						return decodeURIComponent(data.inputReject)
					}
				},
				{
					"data": null,
					"render": function (data) {
						// console.log(data)
						return decodeURIComponent(data.fieldOkeCutt)
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
					$detail.push(data)
				}

			},
			"autoWidth": true,
			"destroy": true,
			// fixedColumns: {
			// 	leftColumns: 3
			// },
			scrollX: true,
			scrollY: "450px",
			scrollCollapse: true,
			scrollXInner: "120%",
			paging: false,
		})

	}

}


function generateTableDetailAll2($id_cut_out) {

	$(".simpan").prop("disabled", false)
	$detail = [];
	TableDetail = $("#tbl_cut_out2").DataTable({
		"processing": true,
		"serverSide": true,
		"bSort": false,
		"searching": false,
		"ajax": "webservices/getListCutOutAllDetailWSEdit.php?id_cut_out=" + $id_cut_out,
		"columns": [
			{
				"data": "num",
				"className": "center"
			},
			// { "data": "goods_code" },
			{ "data": "itemdesc" },
			{ "data": "grouping" },
			{ "data": "panel" },
			{ "data": "lot" },
			{
				"data": "size",
				"className": "center"
			},
			{
				"data": null,
				"render": function (data) {
					// console.log(data)
					return decodeURIComponent(data.qtySO)
				}
			},
			{
				"data": null,
				"render": function (data) {
					// console.log(data)
					return decodeURIComponent(data.cumulative)
				}
			},
			{
				"data": null,
				"render": function (data) {
					// console.log(data)
					return decodeURIComponent(data.balance)
				}
			},
			{
				"data": null,
				"render": function (data) {
					// console.log(data)
					return decodeURIComponent(data.inputCutt)
				}
			},
			{
				"data": null,
				"render": function (data) {
					// console.log(data)
					return decodeURIComponent(data.inputReject)
				}
			},
			{
				"data": null,
				"render": function (data) {
					// console.log(data)
					return decodeURIComponent(data.fieldOkeCutt)
				}
			}
		],
		"rowCallback": function (nRow, data, index) {
			$detail.push(data)
		},
		"autoWidth": true,
		"destroy": true,
		// fixedColumns: {
		// 	leftColumns: 3
		// },
		scrollX: true,
		scrollY: "450px",
		scrollCollapse: true,
		scrollXInner: "120%",
		paging: false,
	})

}


function generateTableDetailAll3(cost, color, item) {

	$(".simpan").prop("disabled", false)
	$detail = []
	TableDetail = $("#tbl_cut_out2").DataTable({
		"processing": true,
		"serverSide": true,
		"bSort": false,
		"searching": false,
		"ajax": "webservices/getListCutOutAllDetailWSEditAdv.php?id_cut_out=" + id_cut_out + "&id_cost=" + cost + "&color=" + color + "&id_item=" + item,
		"columns": [
			{
				"data": "num",
				"className": "center"
			},
			// { "data": "goods_code" },
			{ "data": "itemdesc" },
			{ "data": "grouping" },
			{ "data": "panel" },
			{ "data": "lot" },
			{
				"data": "size",
				"className": "center"
			},
			{
				"data": null,
				"render": function (data) {
					// console.log(data)
					return decodeURIComponent(data.qtySO)
				}
			},
			{
				"data": null,
				"render": function (data) {
					// console.log(data)
					return decodeURIComponent(data.cumulative)
				}
			},
			{
				"data": null,
				"render": function (data) {
					// console.log(data)
					return decodeURIComponent(data.balance)
				}
			},
			{
				"data": null,
				"render": function (data) {
					// console.log(data)
					return decodeURIComponent(data.inputCutt)
				}
			},
			{
				"data": null,
				"render": function (data) {
					// console.log(data)
					return decodeURIComponent(data.inputReject)
				}
			},
			{
				"data": null,
				"render": function (data) {
					// console.log(data)
					return decodeURIComponent(data.fieldOkeCutt)
				}
			}
		],
		"rowCallback": function (nRow, data, index) {
			$detail.push(data)
		},
		"autoWidth": true,
		"destroy": true,
		// fixedColumns: {
		// 	leftColumns: 3
		// },
		scrollX: true,
		scrollY: "450px",
		scrollCollapse: true,
		scrollXInner: "120%",
		paging: false,
	})

}


function handleKeyUp(that) {
	var id = that.id

	var index_array = id.split('_')
	var idx = index_array[1]

	var vl = that.value

	if (index_array[0] == 'cutt') {
		$detail[idx].cutt = vl
	}
	if (index_array[0] == 'reject') {
		$detail[idx].reject = vl
	}

}


function cuttNumber(that) {
	var id = that.id

	var index_array = id.split('_')
	var idx = index_array[1]

	$("#" + that.id).val()

	var i = parseInt($("#" + that.id).val() != '' ? $("#" + that.id).val() : 0)
	var j = parseInt($("#reject_" + idx).val() != '' ? $("#reject_" + idx).val() : 0)
	var p = i - j

	$("#oke_" + idx).val(p) // Menampilkan ke web
	$detail[idx].okeCutt = p // Mengirim ke db

	// Calculation Cumulative
	var c = parseInt($detail[idx].before_cumulative)
	var r = c + p
	$("#cml_" + idx).val(r)

	// Calculation Balance
	var b = parseInt($detail[idx].qtySO_val)
	var q = b - r
	$("#bal_" + idx).val(q)
}


function rejectNumber(that) {
	var id = that.id

	var index_array = id.split('_')
	var idx = index_array[1]

	$("#" + that.id).val()

	var i = parseInt($("#cutt_" + idx).val() != '' ? $("#cutt_" + idx).val() : 0)
	var j = parseInt($("#" + that.id).val() != '' ? $("#" + that.id).val() : 0)
	var p = i - j

	$("#oke_" + idx).val(p) // Menampilkan ke web
	$detail[idx].okeCutt = p // Mengirim ke db

	// Calculation Cumulative
	var c = parseInt($detail[idx].before_cumulative)
	var r = c + p
	$("#cml_" + idx).val(r)

	// Calculation Balance
	var b = parseInt($detail[idx].qtySO_val)
	var q = b - r
	$("#bal_" + idx).val(q)
}


function mouseOver(that) {
	// console.log(that) return false
	var id = that.id
	var index_array = id.split('_')
	var idx = index_array[1]
	var vl = that.value

	$('#oke_' + idx).prop('title', vl)
}


// Proses Save
function Save() {
	// for (var i = 0; i < $detail.length; i++) {
	// 	if ($detail[i].reject > $detail[i].cutt) {
	// 		alert("Qty Reject No More Than Qty Cutting Row to-" + $detail[i].num)
	// 		return false
	// 	}
	// }
	// alert("Success")
	// return false

	if ($data.id == "") {
		var format = "1"
		G_format = "1"

		for (var i = 0; i < $detail.length; i++) {
			// alert(detail[i].balance_val + " " + detail[i].num)
			if ($detail[i].okeCutt < 0) {
				alert("Qty Good Cannot be Minus Row to-" + $detail[i].num)
				return false
			}
		}
	}
	else {
		var format = "2"
		G_format = "2"

		for (var i = 0; i < $detail.length; i++) {
			// alert(detail[i].balance_val + " " + detail[i].num)
			if ($detail[i].okeCutt < 0) {
				alert("Qty Good Cannot be Minus Row to-" + $detail[i].num)
				return false
			}
		}
	}
	$(".simpan").prop("disabled", true)

	$data.ws = $("#ws").val()
	$data.color = $("#color").val()
	$data.date = $("#date").val()

	//validation $data
	if ($data.ws == '') {
		alert("Choose WS")
		return false
	}
	if ($data.color == '') {
		alert("Choose Color")
		return false
	}

	var stringDetail = JSON.stringify($detail)
	var detail64 = btoa(stringDetail)

	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveCuttingOutput.php",
		data: { code: '1', format: format, data: $data, detail: detail64, id_header: id_cut_out },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			// console.log(detail); return false;
			d = JSON.parse(data)
			//console.log(d.message);
			if (d.respon == '200') {
				// Swal.fire("Success!", "Data Has Been Save", "success");
				// setTimeout(function () {
				alert("Data Success on Save")
				$(".addrow").prop("disabled", false)
				if (G_format == "1") {
					window.location.href = "?mod=3LA&id=" + d.last_id
				}
				else {
					location.reload()
				}
			}
			else {
				alert("Error")
			}
		}
	})
}


function getListData($id) {
	return new Promise(function (resolve, reject) {
		$.ajax({
			type: "POST",
			cache: false,
			url: "webservices/Cut_Out_GetListData.php",
			data: { code: '1', id: $id },     // multiple data sent using ajax
			success: function (response) {
				data = response
				//console.log(data);
				d = JSON.parse(data)
				//console.log(d.message);
				if (d.respon == '200') {
					// alert($id)
					$data.id = decodeURIComponent(d.records[0].id)
					G_array = d
					$("#ws").val(decodeURIComponent(d.records[0].ws)).trigger("change")
					// setTimeout(function () {
					// 	// alert("Hello")
					// 	console.log("Color")
					// 	$("#color").val(decodeURIComponent(d.records[0].color)).trigger("change")
					// }, 5000)
					$data.color = decodeURIComponent(d.records[0].color)
					resolve(d)
				}
				else {
					alert('Error Get Data')
				}
			}
		})
	})
}


function refresh() {
	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveCuttingOutputCategory.php",
		data: { code: '1', format: '2' },
		success: function (response) {
			data = response;
			//console.log(data);
			d = JSON.parse(data);
			//console.log(d.message);
			if (d.respon == '200') {
				// alert($id)
				// console.log("data refress!")
			}
			else {
				alert('Error refress!')
			}
		}
	})
}