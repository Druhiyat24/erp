$(window).on("load", function () {

	$(".save").attr("disabled", true)
	$("#detail_item").css("display", "none")

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

	getListHeader($id_edit)
	getDetail($id_edit)
	// .catch(error => {
	// 	console.log(error);
	// 	alert("Some think Wrong !")
	// })

	// getListWS((typeof $split[1] === 'undefined' ? "-1" : $split[1]))
	// 	.then(gen_option => generate_option(gen_option, 'Choose WS'))
	// 	.then(inj_option => injectOptionToHtml(inj_option))
	// 	.then(check_crud => checkUrl($split[1]))
	// 	.then(B => initFormat($G_kondisi))//inisialisasi format (1=NEW, 2 =UPDATE
	// 	.then(disable_ws => DoDisableWs($G_kondisi))
	// 	.then(define_id_url => DoDefineId($G_kondisi, $split[1]))	//$G_kondisi didapat dari fungsi DoDisableWs
	// 	.then(getListHeader => getListData(getListHeader))
	// 	// .then(inj_prop_ws => injectPropWs($G_kondisi, (typeof $G_array === 'undefined' ? "0" : $G_array)))
	// 	.then(def_json_id => define_json_id((typeof $G_array === 'undefined' ? "0" : $G_array)))
	// 	.catch(error => {
	// 		console.log(error);
	// 		alert("Some think Wrong!")
	// 	})

})


function Cancel() {
	window.location = "?mod=secQC"
}


function getListHeader($id_edit) {
	// alert($id_cost); return false;
	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/getListSecQCDet.php",
		data: { code: '1', id: $id_edit },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			console.log(data);
			d = JSON.parse(data);
			console.log(d.message);
			if (d.status == 'ok') {
				// Ddata.id = decodeURIComponent(d.records[0].id);
				$("#ws").val(decodeURIComponent(d.records[0].ws))
				$("#supplier").val(decodeURIComponent(d.records[0].supplier))
				$("#notes").val(decodeURIComponent(d.records[0].notes))
			}
			else {
				console.log('Data gak ketarik');
			}
		}
	})
}



$x_datatable = 0
function getDetail(url) {
	// alert(url)
	// return false
	$("#detail_item").show()
	$(".save").attr("disabled", false)
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
			"ajax": "webservices/getListSecQCDetail.php?id_url=" + url,
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
						return decodeURIComponent(data.qty_sec_out);
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
						return decodeURIComponent(data.qty_reject_qc);
					}
				},
				{
					"data": null,
					"render": function (data) {
						// console.log(data);
						return decodeURIComponent(data.qty_sec_qc);
					}
				},
				{
					"data": null,
					"className": "center",
					"render": function (data) {
						// console.log(data)
						return decodeURIComponent(data.check_approve)
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
			fixedColumns: true,
		})
	}

}


function handlekeyup(myValue) {

	var id = myValue.id
	var index_array = id.split('_')
	var idx = index_array[1]
	var vl = myValue.value
	if (index_array[0] == 'reject') {
		item[idx].qty_reject_qc_val = vl

		var i = parseFloat($("#qtySecOut_" + idx).val())
		var j = parseFloat($("#reject_" + idx).val() != '' ? $("#reject_" + idx).val() : 0)
		var x = i - j

		$("#qtySecQC_" + idx).val(x)
	}

	if ($("#check_" + idx).is(":checked")) {
		item[idx].check_val = "Y"
	}
	else {
		item[idx].check_val = "N"
	}

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

	// Ddata.tanggalinput = $("#tanggalinput").val()
	// Ddata.notes = $("#notes").val()
	// Ddata.id_supp = $("#id_supp").val()
	// Ddata.supplier = $("#supplier").val()
	// Ddata.id_pro = $("#id_pro").val()
	// Ddata.proses = $("#proses").val()
	// Ddata.ws = $("#ws").val()


	var stringDetail = JSON.stringify(item);
	var detail64 = btoa(stringDetail);

	// console.log(item)
	// return false;

	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveSecondaryQC.php",
		data: { code: '1', format: format, detail: detail64, id: $id_edit },
		success: function (response) {
			data = response
			// console.log(data)
			d = JSON.parse(data)
			//console.log(d.message);
			if (d.responds == '200') {
				alert('Data is Successfully Saved')
				// window.location.href = "?mod=mRoll";
				location.reload()
			}
			else {
				alert('Data is Not Entered')
			}
		}
	})

}