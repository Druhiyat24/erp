$(window).on("load", function () {

	$(".simpan").prop("disabled", true)
	$(".boxxer").hide()

	url = window.location.search
	split = url.split("=")
	// alert(split[2])


	getOptionWS((typeof split[2] === 'undefined' ? "-1" : split[2]))
		.then(generate => generateOption(generate, "Choose WS"))
		.then(attach => attachOption(attach))
		.then(check_crud => checkUrl(split[2]))
		.then(disable_ws => DoDisableWs(G_condition))
		.then(define_id_url => DoDefineId(G_condition, split[2]))
		// .then(getListHeader => getListData(getListHeader))
		.then(inj_prop_ws => injectPropWs((typeof G_array === 'undefined' ? "0" : G_array)))
		.then(def_json_id => define_json_id((typeof G_array === 'undefined' ? "0" : G_array)))
		.catch(error => {
			console.log(error)
		})

	Data = {
		id: "",
		ws: "",
		date: ""
	}
	detail = []
	id_cut_qc = 0

})


function Cancel() {

	// alert('123')
	window.location = "?mod=cutQC"

}


function getOptionWS(_url) {

	return new Promise(function (resolve, reject) {
		$.ajax("webservices/getListCutQCWS.php?id_url=" + _url, {
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


function checkUrl(check_url) {

	if (check_url) {
		G_condition = 1
		id_cut_num = check_url
		return "1"
	}
	else {
		G_condition = 0
		id_cut_num = 0
		return false
	}

}


function DoDisableWs(condition) {

	if (condition == '1') {
		return $("#ws").attr("disabled", true)
	} else {
		return $("#ws").attr("disabled", false)
	}
	return $("#ws").append(string)

}


function DoDefineId(condition) {

	if (condition == '1') {
		getListData(split[2])
		id_cut_qc = split[2]
		// getDataTableDetail(id_cut_num)
		return id_url = split[2]
	} else {
		return id_url = -1
	}
	return $("#ws").append(string)

}


function injectPropWs(array) {

	// console.log(array)
	G_id_ws = decodeURIComponent(array.records[0].ws)
	return 1

}


function define_json_id(array) {

	return Data.id = decodeURIComponent(array.records[0].id)

}


function getDetail(value) {

	$(".boxxer").show()
	getDataTableDetail(value)

}


x_dataTable = 0
function getDataTableDetail(e) {
	// alert(e)

	$(".simpan").prop("disabled", false)

	url = typeof split[2] === 'undefined' ? "-1" : split[2]
	var cost = e
	// val = e.split("_")
	// cost = val[0]
	// clr = decodeURI(val[1])

	if (x_dataTable == '0') {
		x_count = 0
		x_dataTable = x_dataTable + 1
		detail = []

		TableDetail = $("#tbl_cut_qc").DataTable({
			"processing": true,
			"serverSide": true,
			"bSort": false,
			"destroy": true,
			"searching": false,
			"ajax": "webservices/getListCutQCDetail.php?url=" + url + "&cost=" + cost,
			"columns": [
				{
					"data": "num",
					"className": "center"
				},
				{ "data": "description" },
				{ "data": "color" },
				{
					"data": "size",
					"className": "center"
				},
				{ "data": "grouping" },
				{ "data": "panel" },
				{ "data": "lot" },
				{
					"data": null,
					"className": "center",
					"render": function (data) {
						// console.log(data)
						return decodeURIComponent(data.qty_so)
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
					"className": "center",
					"render": function (data) {
						// console.log(data)
						return decodeURIComponent(data.number_cutting)
					}
				},
				{
					"data": null,
					"className": "center",
					"render": function (data) {
						// console.log(data)
						return decodeURIComponent(data.number_bundle)
					}
				},
				{
					"data": null,
					"className": "center",
					"render": function (data) {
						// console.log(data)
						return decodeURIComponent(data.number_sack)
					}
				},
				{
					"data": null,
					"className": "center",
					"render": function (data) {
						// console.log(data)
						return decodeURIComponent(data.qty_input_qc)
					}
				},
				{
					"data": null,
					"className": "center",
					"render": function (data) {
						// console.log(data)
						return decodeURIComponent(data.reject_qc_qty)
					}
				},
				{
					"data": null,
					"className": "center",
					"render": function (data) {
						// console.log(data)
						return decodeURIComponent(data.qc_qty)
					}
				},
				{
					"data": null,
					"className": "center",
					"render": function (data) {
						// console.log(data)
						return decodeURIComponent(data.remarks)
					}
				}
				// {
				// 	"data": null,
				// 	"className": "center",
				// 	"render": function (data) {
				// 		// console.log(data)
				// 		return decodeURIComponent(data.check_approve)
				// 	}
				// }
			],
			"rowCallback": function (nRow, data, index) {
				if (x_count != index) {
					return false
				}
				else {
					x_count = x_count + 1
					detail.push(data)
				}
			},
			scrollX: true,
			scrollY: "360px",
			scrollCollapse: true,
			scrollXInner: "100%",
			paging: false,
			fixedColumns: {
				leftColumns: 4
			}
		})
	}

}


function handleKeyUp(that) {

	var id = that.id
	var index_array = id.split('_')
	var idx = index_array[1]
	var vl = that.value

	if (index_array[0] == "qtyInQC") {
		detail[idx].qty_input_qc_val = vl

		// var i = parseFloat($("#bal_" + idx).val())
		var j = parseFloat($("#qtyInQC_" + idx).val() != '' ? $("#qtyInQC_" + idx).val() : 0)
		var k = parseFloat($("#reject_" + idx).val() != '' ? $("#reject_" + idx).val() : 0)
		var x = j - k

		$("#qtyQC_" + idx).val(x)
		detail[idx].qc_qty_val = x

		// Calculation Cumulative
		var c = parseInt(detail[idx].before_cumulative)
		var r = c + x
		$("#cml_" + idx).val(r)

		// Calculation Balance
		var b = parseInt(detail[idx].before_balance)
		var q = b - r
		$("#bal_" + idx).val(q)
	}

	if (index_array[0] == "reject") {
		detail[idx].reject_qty_val = vl

		// var i = parseFloat($("#bal_" + idx).val())
		var j = parseFloat($("#qtyInQC_" + idx).val() != '' ? $("#qtyInQC_" + idx).val() : 0)
		var k = parseFloat($("#reject_" + idx).val() != '' ? $("#reject_" + idx).val() : 0)
		var x = j - k

		$("#qtyQC_" + idx).val(x)
		detail[idx].qc_qty_val = x

		// Calculation Cumulative
		var c = parseInt(detail[idx].before_cumulative)
		var r = c + x
		$("#cml_" + idx).val(r)

		// Calculation Balance
		var b = parseInt(detail[idx].before_balance)
		var q = b - r
		$("#bal_" + idx).val(q)
	}

	if (index_array[0] == "remark") {
		detail[idx].remarks_val = vl
	}

	if ($("#check_" + idx).is(":checked")) {
		detail[idx].check_val = "Y"
	}
	else {
		detail[idx].check_val = "N"
	}

}


function Save() {
	// console.log(detail.length)
	if (Data.id == "") {
		var format = "1"
		G_format = "1"

		for (var i = 0; i < detail.length; i++) {
			if (detail[i].qc_qty_val > detail[i].balance_val) {
				alert("Qty QC No More Than Balance Row to-" + detail[i].num)
				return false
			}
			else if (detail[i].qc_qty_val < 0) {
				alert("Qty QC Cannot be Minus Row to-" + detail[i].num)
				return false
			}
		}
	}
	else {
		var format = "2"
		G_format = "2"

		for (var i = 0; i < detail.length; i++) {
			if (detail[i].qc_qty_val < 0) {
				alert("Qty QC Cannot be Minus Row to-" + detail[i].num)
				return false
			}
			// else if (detail[i].qc_qty_val > detail[i].balance_val) {
			// 	alert("Qty QC No More Than Balance Row to-" + detail[i].num)
			// 	return false
			// }
		}
	}



	$(".simpan").prop("disabled", true)

	Data.ws = $("#ws").val()
	Data.date = $("#date").val()

	var stringDetail = JSON.stringify(detail)
	var detail64 = btoa(stringDetail)

	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveCuttingQC.php",
		data: { code: '1', format: format, data: Data, detail: detail64 },
		success: function (response) {
			data = response
			// console.log(data)
			d = JSON.parse(data)
			//console.log(d.message);
			if (d.responds == '200') {
				alert('Data is Successfully Saved')
				window.location.href = "?mod=cutQC";
				// location.reload()
			}
			else {
				alert('Data is Not Entered')
			}
		}
	})
}


function getListData($id) {
	return new Promise(function (resolve, reject) {
		$.ajax({
			type: "POST",
			cache: false,
			url: "webservices/Cut_QC_GetListData.php",
			data: { code: '1', id: $id },
			success: function (response) {
				data = response
				//console.log(data);
				d = JSON.parse(data)
				//console.log(d.message);
				if (d.responds == '200') {
					// alert($id)
					G_array = d
					Data.id = decodeURIComponent(d.records[0].id)
					$("#ws").val(decodeURIComponent(d.records[0].cost)).trigger("change")
					$("#cutQC").val(decodeURIComponent(d.records[0].no_cut))
					resolve(d)
				}
				else {
					alert('Error Get Data')
				}
			}
		})
	})
}