$(window).on("load", function () {

	$data = {
		id: '',
		ws: ''
	}
	$detail = [];
	url = window.location.search;
	$split_nya = url.split("=");
	id_url = $split_nya[2]
	// if ($split_nya[2]) {
	// 	if ($split_nya[3]) {
	// 		//$split_nya___ = $split_nya[2].split("&");
	// 		if (typeof $split_nya[2].split("&") === 'undefined') {
	// 			var die = '';
	// 		} else {
	// 			$split_nya___ = $split_nya[2].split("&");
	// 			var tmp = $split_nya[2].split("&");
	// 			$split_nya[2] = $split_nya___[0];
	// 			$is_view = $split_nya___[1];
	// 		}
	// 	}
	// }

	// $('.simpan').prop('disabled', true)

	OptionWS((typeof $split_nya[2] === 'undefined' ? "-1" : $split_nya[2]))
		.then(gen_option => generate_option(gen_option, 'Choose WS'))
		.then(inj_option => injectOptionToHtml(inj_option))
		.then(check_crud => checkUrl($split_nya[2]))
		.then(disable_ws => DoDisableWs($G_kondisi))
		.then(define_id_url => DoDefineId($G_kondisi, $split_nya[2]))	//$G_kondisi didapat dari fungsi DoDisableWs
		.then(getListHeader => getListData(getListHeader))
		.then(inj_prop_ws => injectPropWs($G_kondisi, (typeof $G_array === 'undefined' ? "0" : $G_array)))
		.then(def_json_id => define_json_id((typeof $G_array === 'undefined' ? "0" : $G_array)))//$G_id_ws didapat dari fungsi injectPropWs
		.catch(error => {
			console.log(error);
			alert("Some think Wrong!");
		});

});


function OptionWS(_id_url) {
	return new Promise(function (resolve, reject) {
		/*  */
		$.ajax("webservices/getListMRollWS.php?id_url=" + _id_url,
			{
				type: "POST",
				processData: false,
				contentType: false,
				success: function (data) {
					//console.log(data);
					resolve(jQuery.parseJSON(data));
				},
				error: function (data) {
					alert("Error req");
				}
			});
	});
}

function generate_option(PropHtmlData, judul) {
	var option = "";
	option += "<option value=''>--" + judul + "--</option>";
	var i;
	for (i = 0; i < PropHtmlData.records.length; i++) {
		var id_cost = decodeURIComponent(PropHtmlData.records[i].id_cost);
		var ws = decodeURIComponent(PropHtmlData.records[i].ws);
		// var id_jo = decodeURIComponent(PropHtmlData.records[i].id_jo);
		// var req_no = decodeURIComponent(PropHtmlData.records[i].req_no);
		option += '<option value="' + id_cost + '">' + ws + '</option>';
	}
	$('.simpan').prop('disabled', false)
	return option;
}

function injectOptionToHtml(string) {
	return $("#ws").append(string);
}

function getStyle($id_cost) {

	return new Promise(function (resolve, reject) {
		$.ajax({
			type: "POST",
			cache: false,
			url: "webservices/getListMRollStyle.php",
			data: { code: '1', id_cost: $id_cost },     // multiple data sent using ajax
			success: function (response) {
				data = response;
				//console.log(data);
				d = JSON.parse(data);
				console.log(d.message);
				if (d.respon == '200') {
					// Ddata.id = decodeURIComponent(d.records[0].id);
					$("#style").val(decodeURIComponent(d.records[0].style)).prop("disabled", true)
				}
				else {
					alert('Error')
				}
			}
		});
	});

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

function getDetailWS(value) {
	$x_datatable = 0;
	$_inx = 1;
	getDetail(value);
}

$x_datatable = 0;
function getDetail(idcost) {
	if ($x_datatable == '0') {
		$x_datatable = $x_datatable + 1;
		$detail = [];
		TableDetail = $('#tbl_mroll').DataTable({
			"processing": true,
			"serverSide": true,
			"bSort": false,
			"method": "POST",
			"destroy": true,
			"bAutoWidth": false,
			"ajax": "webservices/getListMRollDetail.php?id_cost=" + idcost + "&id_url=" + id_url,
			"columns": [
				{
					"data": "no",
					"className": "center"
				},
				// { "data": "fabric_code" },
				// {
				// 	"data": "fabric_name",
				// 	"width": 200,
				// },
				// { "data": "fabric_color" },
				{ "data": "lot" },
				{ "data": "roll" },
				{ "data": "qty_sticker" },
				{
					"data": null,
					"render": function (data) {
						//console.log(data);
						return decodeURIComponent(data.fabric_use);
					}
				},
				{
					"data": null,
					"render": function (data) {
						//console.log(data);
						return decodeURIComponent(data.qty_cut);
					}
				},
				{
					"data": null,
					"render": function (data) {
						//////console.log(data);
						return decodeURIComponent(data.cons_ws);
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.cons_m);
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.cons_act);
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.cons_bal);
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.percent);
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.bind);
					}
				},
				{
					"data": null,
					"render": function (data) {
						//console.log(data);
						return decodeURIComponent(data.act_bal);
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.act_tot);
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.short_roll);
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.spread_sheet);
					}
				},
				{ "data": "ratio" },
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.qty_pcs);
					}
				},
				{ "data": "p_markers" },
				{ "data": "efficiency" },
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.fabric_tot_act);
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.majun);
					}
				},
				{
					"data": null,
					"render": function (data) {
						//	//console.log(data);
						return decodeURIComponent(data.majun_kg);
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.ampar);
					}
				},
				{
					"data": null,
					"render": function (data) {
						//	//console.log(data);
						return decodeURIComponent(data.total);
					}
				},
				{
					"data": null,
					"render": function (data) {
						////console.log(data);
						return decodeURIComponent(data.use_total);
					}
				},
				{
					"data": null,
					"render": function (data) {
						//	//console.log(data);
						return decodeURIComponent(data.l_fabric);
					}
				}
			],
			"rowCallback": function (nRow, data, index) {
				//console.log(index)

				if ($_inx > data.no) {

					return false;
				} else {
					$_inx = $_inx + 1;
					console.log($_inx);
					//$_inx = data.no;
					console.log(data.no);
					console.log(data);
					$detail.push(data);

				}

				//if ($_inx == data.no) {
				//}
				//i_trigger = 1+1;
			},
			scrollX: true,
			scrollY: "350px",
			scrollCollapse: true,
			scrollXInner: "100%",
			paging: false,
			fixedColumns: {
				leftColumns: 4
				// rightColumns: 1
			}
		});

		setTimeout(function () {
			console.log($detail);
		}, 10000);



	}


}

function handlekeyup(that) {
	// console.log(that)
	var id = that.id;
	var index_array = id.split('_');
	var idx = index_array[1];
	var vl = that.value;

	if (index_array[0] == 'fUse') {
		$detail[idx].fabric_use_val = vl;
	}
	if (index_array[0] == 'qtyCut') {
		$detail[idx].qty_cut_val = vl;
	}
	if (index_array[0] == 'consWs') {
		$detail[idx].cons_ws_val = vl;
	}
	if (index_array[0] == 'consM') {
		$detail[idx].cons_m_val = vl;
	}
	if (index_array[0] == 'consAct') {
		$detail[idx].cons_act_val = vl;
	}
	if (index_array[0] == 'consBal') {
		$detail[idx].cons_bal_val = vl;
	}
	if (index_array[0] == 'percent') {
		$detail[idx].percent_val = vl;
	}
	if (index_array[0] == 'bind') {
		$detail[idx].bind_val = vl;
	}
	if (index_array[0] == 'actBal') {
		$detail[idx].act_bal_val = vl;
	}
	if (index_array[0] == 'actTot') {
		$detail[idx].act_tot_val = vl;
	}
	if (index_array[0] == 'sht') {
		$detail[idx].short_roll_val = vl;
	}
	if (index_array[0] == 'spd') {
		$detail[idx].spread_sheet_val = vl;
	}
	if (index_array[0] == 'qtyPcs') {
		$detail[idx].qty_pcs_val = vl;
	}
	if (index_array[0] == 'fTotAct') {
		$detail[idx].fabric_tot_act_val = vl;
	}
	if (index_array[0] == 'majun') {
		$detail[idx].majun_val = vl;
	}
	if (index_array[0] == 'majunKg') {
		$detail[idx].majun_kg_val = vl;
	}
	if (index_array[0] == 'ampar') {
		$detail[idx].ampar_val = vl;
	}
	if (index_array[0] == 'total') {
		$detail[idx].total_val = vl;
	}
	if (index_array[0] == 'useTotal') {
		$detail[idx].use_total_val = vl;
	}
	if (index_array[0] == 'lFab') {
		$detail[idx].l_fabric_val = vl;
	}

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
	console.log($detail.length)

	if ($data.id == "") {
		var format = "1";
	} else {
		var format = "2";
	}

	$data.ws = $("#ws").val();


	var stringDetail = JSON.stringify($detail);
	var detail64 = btoa(stringDetail);
	// alert(detail64)

	// return false;
	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveMRoll.php",
		data: { code: '1', format: format, data: $data, detail: detail64 },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			// console.log(stringDetail);
			d = JSON.parse(data);
			//console.log(d.message);
			if (d.respon == '200') {
				alert('Data is Successfully Saved')
				window.location.href = "?mod=mRoll";
			}
			else {
				alert('Data is Not Entered')
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
				url: "webservices/M_Roll_GetListData.php",
				data: { code: '1', id: $id },     // multiple data sent using ajax
				success: function (response) {
					data = response;
					//console.log(data);
					d = JSON.parse(data);
					console.log(d.message);
					if (d.respon == '200') {
						$("#ws").val(decodeURIComponent(d.records[0].ws)).trigger("change");
						$G_array = d;
						resolve(d);
					}
					else {
						alert('Data Error')
					}
				}
			});
		});
	}
}

function Cancel() {
	window.location = "/pages/prod/?mod=mRoll";
}