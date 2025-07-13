$(window).on("load", function () {

	$detail = []
	url = window.location.search
	$split_nya = url.split("=")
	$so = $split_nya[2]
	$cekSO = $so.split("&")
	$id_so = $cekSO[0]
	$split = $split_nya[3]
	$url = $split.split("&")
	$color = decodeURI($url[0])
	$item = $split_nya[4]

	Data = {
		id_item: '',
		id_so: '',
		buyer: '',
		ws: '',
		color: '',
		buyerno: '',

	}

	get_url(url)
		.then(pop_url => init_url(pop_url))
		.then(X => getListDataMrFormHeader())
		.then(_json => initData(_json))
		.then(_json => initHeader(_json))
		.then(_json => templateTable(_json))
		.then(_color_item => getDetail($color, $item, $id_so))
	format = 1

	// alert($color)
})

function templateTable() {
	return new Promise(function (resolve, reject) {
		$.ajax("webservices/getListDataMrFormHeader.php",
			{ //decodeURIComponent(_json.records[0].color)
				type: "POST",
				data: { code: '1', id_so: $populasi_id_url.id_so },
				success: function (data) {
					resolve(jQuery.parseJSON(data));
				},
				error: function (data) {
					alert("Error req");
				}
			})
	})

}

function initData(_json) {
	return [
		Data.buyer = decodeURIComponent(_json.records[0].buyer),
		Data.buyerno = decodeURIComponent(_json.records[0].buyerno),
		Data.ws = decodeURIComponent(_json.records[0].kpno),
		Data.so_no = decodeURIComponent(_json.records[0].so_no),
		Data.id_so = $populasi_id_url.id_so,
		Data.id_item = $populasi_id_url.id_item,
		Data.color = $populasi_id_url.color,
		//Data.key_det = _json.key_det,
		// console.log(Data)
	]
}

function initHeader() {
	$("#buyer").val(Data.buyer)
	$("#po_buyer").val(Data.buyerno)
	$("#ws").val(Data.ws)
	$("#so").val(Data.so_no)
	$("#colors").val(Data.color)
}

function getListDataMrFormHeader() {
	return new Promise(function (resolve, reject) {
		$.ajax("webservices/getListDataMrFormHeader.php",
			{ //decodeURIComponent(_json.records[0].color)
				type: "POST",
				data: { code: '1', id_so: $populasi_id_url.id_so, color: $populasi_id_url.color },
				success: function (data) {
					resolve(jQuery.parseJSON(data))
				},
				error: function (data) {
					alert("Error req");
				}
			})
	})
}


$x_datatable = 0
function getDetail($_color, $_item, $_so) {

	if ($x_datatable == '0') {
		$_inx = 0
		$x_datatable = $x_datatable + 1
		$detail = []
		TableDetail = $('#tbl_mroll').DataTable({
			"processing": true,
			"serverSide": true,
			"bSort": false,
			"searching": false,
			"method": "POST",
			"destroy": true,
			"bAutoWidth": false,
			"ajax": "webservices/getListMRollDetail.php?color=" + $_color + "&item=" + $_item + "&so=" + $_so,
			"columns": [
				{
					"data": "no",
					"className": "center"
				},
				{ "data": "panel" },
				{ "data": "lot" },
				{
					"data": "roll",
					"className": "center"
				},
				{
					"data": "qty_sticker",
					"className": "right"
				},
				{
					"data": null,
					"render": function (data) {
						//console.log(data);
						return decodeURIComponent(data.fabric_use)
					}
				},
				{
					"data": null,
					"render": function (data) {
						//console.log(data);
						return decodeURIComponent(data.qty_cut)
					}
				},
				{
					"data": null,
					"render": function (data) {
						//////console.log(data);
						return decodeURIComponent(data.cons_ws)
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.cons_m)
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.cons_act)
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.cons_bal)
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.percent)
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.bind)
					}
				},
				{
					"data": null,
					"render": function (data) {
						//console.log(data);
						return decodeURIComponent(data.act_bal)
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.act_tot)
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.short_roll)
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.spread_sheet)
					}
				},
				{ "data": "ratio" },
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.qty_pcs)
					}
				},
				{
					"data": "p_markers",
					"className": "right"
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.efficiency)
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.fabric_tot_act)
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.majun)
					}
				},
				{
					"data": null,
					"render": function (data) {
						//	//console.log(data);
						return decodeURIComponent(data.majun_kg)
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.ampar)
					}
				},
				{
					"data": null,
					"render": function (data) {
						//	//console.log(data);
						return decodeURIComponent(data.total)
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.use_total)
					}
				},
				{
					"data": null,
					"render": function (data) {
						//	//console.log(data);
						return decodeURIComponent(data.l_fabric)
					}
				}
			],
			"rowCallback": function (nRow, data, index) {
				// console.log(index)

				if ($_inx != index) {
					return false;
				}
				else {
					$_inx = $_inx + 1;
					// console.log(data.no);
					// console.log(data);
					$detail.push(data)
				}
			},
			scrollX: true,
			scrollY: "350px",
			scrollCollapse: true,
			scrollXInner: "100%",
			paging: false,
			fixedColumns: {
				leftColumns: 5
				// rightColumns: 1
			}
		})
		// setTimeout(function () {
		// 	console.log($detail);
		// }, 10000);
	}

}

function handlekeyup(that) {
	// console.log(that)
	var id = that.id
	var index_array = id.split('_')
	var idx = index_array[1]
	var vl = that.value


	if (index_array[0] == 'qs_') {
		$detail[idx].qty_sticker_val = vl
	}
	// if (index_array[0] == 'fUse') {
	// 	$detail[idx].fabric_use_val = vl;
	// }
	if (index_array[0] == 'qtyCut') {
		$detail[idx].qty_cut_val = vl
	}
	if (index_array[0] == 'consWs') {
		$detail[idx].cons_ws_val = vl
	}
	if (index_array[0] == 'consM') {
		$detail[idx].cons_m_val = vl
	}
	// if (index_array[0] == 'consAct') {
	// 	$detail[idx].cons_act_val = vl;
	// }
	// if (index_array[0] == 'consBal') {
	// 	$detail[idx].cons_bal_val = vl;
	// }
	// if (index_array[0] == 'percent') {
	// 	$detail[idx].percent_val = vl;
	// }
	if (index_array[0] == 'bind') {
		$detail[idx].bind_val = vl
	}
	if (index_array[0] == 'actBal') {
		$detail[idx].act_bal_val = vl
	}
	if (index_array[0] == 'actTot') {
		$detail[idx].act_tot_val = vl
	}
	// if (index_array[0] == 'sht') {
	// 	$detail[idx].short_roll_val = vl;
	// }
	if (index_array[0] == 'spd') {
		$detail[idx].spread_sheet_val = vl
	}
	// if (index_array[0] == 'qtyPcs') {
	// 	$detail[idx].qty_pcs_val = vl;
	// }
	// if (index_array[0] == 'fTotAct') {
	// 	$detail[idx].fabric_tot_act_val = vl;
	// }
	if (index_array[0] == 'majun') {
		$detail[idx].majun_val = vl
	}
	// if (index_array[0] == 'majunKg') {
	// 	$detail[idx].majun_kg_val = vl;
	// }
	if (index_array[0] == 'ampar') {
		$detail[idx].ampar_val = vl
	}
	// if (index_array[0] == 'total') {
	// 	$detail[idx].total_val = vl;
	// }
	// if (index_array[0] == 'useTotal') {
	// 	$detail[idx].use_total_val = vl;
	// }
	if (index_array[0] == 'lFab') {
		$detail[idx].l_fabric_val = vl
	}

}


function qtyCutSum(that) {
	var id = that.id
	var index_array = id.split("_")
	var idx = index_array[1]

	var i = parseFloat($("#fUse_" + idx).val())
	var j = $("#" + that.id).val()
	var x = i / j

	// alert(x.toFixed(3))

	$("#consAct_" + idx).val(x.toFixed(3)) // Menampilkan ke Web
	$detail[idx].cons_act_val = x.toFixed(3) // Mengirim ke DB
	// alert(x)
}


function consWsSum(that) {
	var id = that.id
	var index_array = id.split("_")
	var idx = index_array[1]

	var i = parseFloat($("#consAct_" + idx).val())
	var j = $("#" + that.id).val()
	var x = i - j

	$("#consBal_" + idx).val(x.toFixed(3)) // Menampilkan ke Web
	$detail[idx].cons_bal_val = x.toFixed(3) // Mengirim ke DB
	// alert(x)

	var p = parseFloat($("#" + that.id).val())
	var q = parseFloat($("#consBal_" + idx).val())
	var xx = (q / p) * 100

	$("#percent_" + idx).val(xx.toFixed(3) + " %") // Menampilkan ke Web
	$detail[idx].percent_val = xx.toFixed(3) // Mengirim ke DB
	// alert(x)
}


function bindSum(that) {
	var id = that.id
	var index_array = id.split("_")
	var idx = index_array[1]

	var i = parseFloat($("#qs_" + idx).val())
	var j = parseFloat($("#" + that.id).val())
	var k = parseFloat($("#actBal_" + idx).val() != '' ? $("#actBal_" + idx).val() : 0)
	var x = i - j - k

	$("#fUse_" + idx).val(x.toFixed(3)) // Menampilkan ke Web
	$detail[idx].fabric_use_val = x.toFixed(3) // Mengirim ke DB


	// Rumus Change Before After
	var i2 = parseFloat($("#fUse_" + idx).val())
	var j2 = parseFloat($("#qtyCut_" + idx).val())
	var x2 = i2 / j2

	$("#consAct_" + idx).val(x2.toFixed(3)) // Menampilkan ke Web
	$detail[idx].cons_act_val = x2.toFixed(3) // Mengirim ke DB


	var i3 = parseFloat($("#consAct_" + idx).val())
	var j3 = parseFloat($("#consWs_" + idx).val())
	var x3 = i3 - j3

	$("#consBal_" + idx).val(x3.toFixed(3)) // Menampilkan ke Web
	$detail[idx].cons_bal_val = x3.toFixed(3) // Mengirim ke DB


	var p = parseFloat($("#consAct_" + idx).val())
	var q = parseFloat($("#consBal_" + idx).val())
	var xx = (q / p) * 100

	$("#percent_" + idx).val(xx.toFixed(3) + " %") // Menampilkan ke Web
	$detail[idx].percent_val = xx.toFixed(3) // Mengirim ke DB


	var p2 = parseFloat($("#" + that.id).val())
	var q2 = parseFloat($("#actBal_" + idx).val() != '' ? $("#actBal_" + idx).val() : 0)
	var r2 = parseFloat($("#useTotal_" + idx).val() != '' ? $("#useTotal_" + idx).val() : 0)
	var xx2 = p2 + q2 + r2

	$("#actTot_" + idx).val(xx2.toFixed(3)) // Menampilkan ke Web
	$detail[idx].act_tot_val = xx2.toFixed(3) // Mengirim ke DB


	var pp = parseFloat($("#actTot_" + idx).val() != '' ? $("#actTot_" + idx).val() : 0)
	var qq = parseFloat($("#qs_" + idx).val())
	var shtx = pp - qq

	$("#sht_" + idx).val(shtx.toFixed(3)) // Menampilkan ke Web
	$detail[idx].short_roll_val = shtx.toFixed(3) // Mengirim ke DB

}


function actBalSum(that) {
	var id = that.id
	var index_array = id.split("_")
	var idx = index_array[1]

	var i = parseFloat($("#qs_" + idx).val())
	var j = parseFloat($("#bind_" + idx).val() != '' ? $("#bind_" + idx).val() : 0)
	var k = parseFloat($("#" + that.id).val())
	var x = i - j - k

	$("#fUse_" + idx).val(x) // Menampilkan ke Web
	$detail[idx].fabric_use_val = x // Mengirim ke DB

	// Rumus Change Before After
	var i2 = parseFloat($("#fUse_" + idx).val())
	var j2 = parseFloat($("#qtyCut_" + idx).val())
	var x2 = i2 / j2

	$("#consAct_" + idx).val(x2.toFixed(3)) // Menampilkan ke Web
	$detail[idx].cons_act_val = x2.toFixed(3) // Mengirim ke DB


	var i3 = parseFloat($("#consAct_" + idx).val())
	var j3 = parseFloat($("#consWs_" + idx).val())
	var x3 = i3 - j3

	$("#consBal_" + idx).val(x3.toFixed(3)) // Menampilkan ke Web
	$detail[idx].cons_bal_val = x3.toFixed(3) // Mengirim ke DB

	var p = parseFloat($("#consAct_" + idx).val())
	var q = parseFloat($("#consBal_" + idx).val())
	var xx = (q / p) * 100

	$("#percent_" + idx).val(xx.toFixed(3) + " %") // Menampilkan ke Web
	$detail[idx].percent_val = xx.toFixed(3) // Mengirim ke DB


	var p2 = parseFloat($("#bind_" + idx).val() != '' ? $("#bind_" + idx).val() : 0)
	var q2 = parseFloat($("#" + that.id).val())
	var r2 = parseFloat($("#useTotal_" + idx).val() != '' ? $("#useTotal_" + idx).val() : 0)
	var xx2 = p2 + q2 + r2

	$("#actTot_" + idx).val(xx2.toFixed(3)) // Menampilkan ke Web
	$detail[idx].act_tot_val = xx2.toFixed(3) // Mengirim ke DB


	var pp = parseFloat($("#actTot_" + idx).val() != '' ? $("#actTot_" + idx).val() : 0)
	var qq = parseFloat($("#qs_" + idx).val())
	var shtx = pp - qq

	$("#sht_" + idx).val(shtx.toFixed(3)) // Menampilkan ke Web
	$detail[idx].short_roll_val = shtx.toFixed(3) // Mengirim ke DB


}


function spdSum(that) {
	var id = that.id
	var index_array = id.split("_")
	var idx = index_array[1]

	var i = $("#" + that.id).val()
	var j = parseFloat($("#ratio_" + idx).val())
	var x = j * i

	$("#qtyPcs_" + idx).val(x) // Menampilkan ke Web
	$detail[idx].qty_pcs_val = x // Mengirim ke DB

}


function amparSum(that) {
	var id = that.id
	var index_array = id.split("_")
	var idx = index_array[1]

	var i = $("#" + that.id).val()

	$("#total_" + idx).val(i) // Menampilkan ke Web
	$detail[idx].total_val = i // Mengirim ke DB


	var y = parseFloat($("#spd_" + idx).val())
	var z = parseFloat($("#total_" + idx).val())
	var totx = y * z

	$("#useTotal_" + idx).val(totx.toFixed(3)) // Menampilkan ke Web
	$detail[idx].use_total_val = totx.toFixed(3) // Mengirim ke DB


	var yy = parseFloat($("#useTotal_" + idx).val())
	var zz = parseFloat($("#efi_" + idx).val())
	var xx = yy * zz / 100

	$("#fTotAct_" + idx).val(xx.toFixed(3)) // Menampilkan ke Web
	$detail[idx].fabric_tot_act_val = xx.toFixed(3) // Mengirim ke DB


	var p = parseFloat($("#useTotal_" + idx).val())
	var q = parseFloat($("#fTotAct_" + idx).val())
	var o = p - q

	$("#majunKg_" + idx).val(o.toFixed(3)) // Menampilkan ke Web
	$detail[idx].majun_kg_val = o.toFixed(3) // Mengirim ke DB


	var p2 = parseFloat($("#bind_" + idx).val() != '' ? $("#bind_" + idx).val() : 0)
	var q2 = parseFloat($("#actTot_" + idx).val() != '' ? $("#actTot_" + idx).val() : 0)
	var r2 = parseFloat($("#useTotal_" + idx).val() != '' ? $("#useTotal_" + idx).val() : 0)
	var xx2 = p2 + q2 + r2

	$("#actTot_" + idx).val(xx2.toFixed(3)) // Menampilkan ke Web
	$detail[idx].act_tot_val = xx2.toFixed(3) // Mengirim ke DB


	var pp = parseFloat($("#actTot_" + idx).val() != '' ? $("#actTot_" + idx).val() : 0)
	var qq = parseFloat($("#qs_" + idx).val())
	var shtx = pp - qq

	$("#sht_" + idx).val(shtx.toFixed(3)) // Menampilkan ke Web
	$detail[idx].short_roll_val = shtx.toFixed(3) // Mengirim ke DB

}


function mouseOver(that) {
	// console.log(that); return false;
	var id = that.id;
	var index_array = id.split('_');
	var idx = index_array[1];
	var vl = that.value;

	$('#fUse_' + idx).prop('title', vl);
	$('#qtyCut_' + idx).prop('title', vl);
	$('#consWs_' + idx).prop('title', vl);
	$('#consM_' + idx).prop('title', vl);
	$('#consAct_' + idx).prop('title', vl);
	$('#consBal_' + idx).prop('title', vl);
	$('#percent_' + idx).prop('title', vl);
	$('#bind_' + idx).prop('title', vl);
	$('#actBal_' + idx).prop('title', vl);
	$('#actTot_' + idx).prop('title', vl);
	$('#sht_' + idx).prop('title', vl);
	$('#spd_' + idx).prop('title', vl);
	$('#qtyPcs_' + idx).prop('title', vl);
	$('#fTotAct_' + idx).prop('title', vl);
	$('#majun_' + idx).prop('title', vl);
	$('#majunKg_' + idx).prop('title', vl);
	$('#ampar_' + idx).prop('title', vl);
	$('#total_' + idx).prop('title', vl);
	$('#useTotal_' + idx).prop('title', vl);
	$('#lFab_' + idx).prop('title', vl);

}


function Save() {
	console.log($detail)
	// alert($color)

	// if ($data.id == "") {
	// 	var format = "1";
	// } else {
	// 	var format = "2";
	// }

	// $data.ws = $("#ws").val();


	var stringDetail = JSON.stringify($detail);
	var detail64 = btoa(stringDetail);
	// alert(detail64)

	// return false;
	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveMRoll.php",
		data: { code: '1', format: '1', detail: detail64, color: $color, item: $item, so: $id_so },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			// console.log(stringDetail);
			d = JSON.parse(data);
			//console.log(d.message);
			if (d.respon == '200') {
				alert('Data is Successfully Saved')
				// location.reload();
				getDetail($color, $item, $id_so)
			}
			else {
				alert('Data is Not Entered')
			}
		}
	})
}