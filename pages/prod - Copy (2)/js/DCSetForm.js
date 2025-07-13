$(window).on("load", function () {
	$(".boxxer").css("display", "none")
	$(".simpan").attr("disabled", true)

	url = window.location.search;
	$split = url.split("id=");
	$id_edit = $split[1];

	Data = {
		id: '',
		notes: '',
		date: '',
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
		})

})


function OptionWS($id_edit) {
	return new Promise(function (resolve, reject) {
		/*  */
		$.ajax("webservices/getListDCSetWS.php?id_url=" + $id_edit,
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
		var ws = decodeURIComponent(PropHtmlData.records[i].ws);
		option += '<option value="' + id_cost + '">' + ws + '</option>';
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
		return Data.id = decodeURIComponent(array.records[0].id);
	}
}


function handleKeyUp(that) {
	var id = that.id
	var index_array = id.split('_')
	var idx = index_array[1]
	var vl = that.value

	if (index_array[0] == 'qtyInSet') {
		Detail[idx].qty_input_set_val = vl

		var i = parseFloat($("#qtyInSet_" + idx).val() != '' ? $("#qtyInSet_" + idx).val() : 0)
		var j = parseFloat($("#reject_" + idx).val() != '' ? $("#reject_" + idx).val() : 0)
		var x = i - j

		$("#qtySet_" + idx).val(x)
	}
	if (index_array[0] == 'reject') {
		Detail[idx].reject_dc_set_val = vl

		var i = parseFloat($("#qtyInSet_" + idx).val() != '' ? $("#qtyInSet_" + idx).val() : 0)
		var j = parseFloat($("#reject_" + idx).val() != '' ? $("#reject_" + idx).val() : 0)
		var x = i - j

		$("#qtySet_" + idx).val(x)
	}
	if (index_array[0] == 'location') {
		Detail[idx].location_val = vl
	}
	if (index_array[0] == 'remarks') {
		Detail[idx].remarks_val = vl
	}
}


x_datatable = 0
function getDetail(myValue) {
	$(".boxxer").show()
	$(".simpan").attr("disabled", false)

	return new Promise(function (resolve, reject) {

		if (x_datatable == '0') {
			x_inx = 0
			x_datatable = x_datatable + 1

			Detail = []
			TableDetail = $('#detail_item').DataTable({
				"processing": true,
				"serverSide": true,
				"bSort": false,
				"method": "POST",
				"destroy": true,
				"ajax": "webservices/getListDCSetDetail.php?id_ws=" + myValue + "&id_url=" + $id_edit,
				"columns": [
					{
						"data": "no",
						"className": "center"
					},
					{ "data": "color" },
					{ "data": "size" },
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
						"render": function (data) {
							// console.log(data);
							return decodeURIComponent(data.number_cutting)
						}
					},
					{
						"data": null,
						"render": function (data) {
							// console.log(data);
							return decodeURIComponent(data.number_bundle)
						}
					},
					{
						"data": null,
						"render": function (data) {
							// console.log(data);
							return decodeURIComponent(data.number_sack)
						}
					},
					{
						"data": null,
						"className": "center",
						"render": function (data) {
							// console.log(data)
							return decodeURIComponent(data.qty_input_set)
						}
					},
					{
						"data": null,
						"render": function (data) {
							// console.log(data);
							return decodeURIComponent(data.reject_dc_set)
						}
					},
					{
						"data": null,
						"render": function (data) {
							// console.log(data);
							return decodeURIComponent(data.qty_dc_set)
						}
					},
					{
						"data": null,
						"render": function (data) {
							// console.log(data);
							return decodeURIComponent(data.location)
						}
					},
					{
						"data": null,
						"render": function (data) {
							// console.log(data);
							return decodeURIComponent(data.remarks)
						}
					}
				],
				"rowCallback": function (nRow, data, index) {
					if (x_inx != index) {
						return false
					}
					else {
						x_inx = x_inx + 1;
						// console.log(data.no)
						// console.log(data)
						Detail.push(data)
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

	})

}

function Cancel() {
	// alert('123')
	window.location = "/pages/prod/?mod=7WP";
}

function Save() {
	$(".simpan").attr("disabled", true)
	// alert('123')
	// return false

	// for (var i = 0; i < Detail.length; i++) {
	// 	var $qty = remove_separator($("#qty_" + i).val());
	// 	// alert($qty); return false
	// 	if (isNaN($qty)) {
	// 		alert("Qty DC Output baris ke-" + Detail[i].no + " Salah Format");
	// 		$("#qty_" + i).val("0");
	// 		return false;
	// 	}
	// 	// alert(item[i].oke)
	// 	if (parseInt(Detail[i].qty_val) > parseInt(Detail[i].bal_val)) {
	// 		alert('Baris ke-' + Detail[i].no + ' Qty Output ' + '(' + Detail[i].qty_val + ')' + ' Lebih Besar Dari Balance ' + '(' + Detail[i].bal_val + ')')
	// 		return false
	// 	}
	// }

	// if (Data.id == "") {
	// 	var format = "1";

	// 	for (var i = 0; i < Detail.length; i++) {
	// 		var $qty = remove_separator($("#qty_" + i).val());
	// 		// alert($qty); return false
	// 		if (isNaN($qty)) {
	// 			alert("Qty DC Output baris ke-" + Detail[i].no + " Salah Format");
	// 			$("#qty_" + i).val("0");
	// 			return false;
	// 		}
	// 		// alert(item[i].oke)
	// 		if (parseInt(Detail[i].qty_val) > parseInt(Detail[i].bal_val)) {
	// 			alert('Baris ke-' + Detail[i].no + ' Qty Output ' + '(' + Detail[i].qty_val + ')' + ' Lebih Besar Dari Balance ' + '(' + Detail[i].bal_val + ')')
	// 			return false
	// 		}
	// 	}

	// } else {
	// 	var format = "2";

	// 	for (var i = 0; i < Detail.length; i++) {
	// 		var $qty = remove_separator($("#qty_" + i).val());
	// 		// alert($qty); return false
	// 		if (isNaN($qty)) {
	// 			alert("Qty DC Output baris ke-" + Detail[i].no + " Salah Format");
	// 			$("#qty_" + i).val("0");
	// 			return false;
	// 		}
	// 		// alert(item[i].oke)
	// 		if (parseInt(Detail[i].qty_val) > parseInt(Detail[i].qty_so_val)) {
	// 			alert('Baris ke-' + Detail[i].no + ' Qty Output ' + '(' + Detail[i].qty_val + ')' + ' Terlalu Besar ')
	// 			return false
	// 		}
	// 	}

	// }

	if (Data.id == "") {
		var format = "1"
	} else {
		var format = "2"
	}
	// alert(format)
	// return false


	Data.ws = $("#ws").val()
	Data.notes = $("#notes").val()
	Data.date = $("#date").val()


	var stringDetail = JSON.stringify(Detail);
	var detail64 = btoa(stringDetail);

	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveDCSet.php",
		data: { code: '1', format: format, data: Data, detail: detail64 },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			// console.log(stringDetail);
			d = JSON.parse(data);
			//console.log(d.message);
			if (d.respond == '200') {
				alert('Data Success on Save')
				window.location.href = "?mod=7WP"
			}
			else {
				alert('Data Failed !!!')
			}
		}
	})
}


function getListData($id) {
	// $("#ws").prop("disabled", true)
	// $("#panel").prop("disabled", true)
	if ($G_kondisi == 0) {
		return "1"
	}
	else {
		Detail = []
		return new Promise(function (resolve, reject) {
			$.ajax({
				type: "POST",
				cache: false,
				url: "webservices/DC_Set_GetListData.php",
				data: { code: '1', id: $id },     // multiple data sent using ajax
				success: function (response) {
					data = response
					// console.log(data)
					d = JSON.parse(data)
					// console.log(d.message)
					if (d.respond == '200') {
						Data.id = decodeURIComponent(d.records[0].id);
						$("#ws").val(decodeURIComponent(d.records[0].ws)).trigger("change")
						$("#notes").val(decodeURIComponent(d.records[0].notes))
						$("#dsnum").val(decodeURIComponent(d.records[0].num))
					}
					else {
						alert('Erorr!')
					}
				}
			})
		})
	}
}