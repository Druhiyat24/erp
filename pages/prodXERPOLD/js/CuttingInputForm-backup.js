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
	url = window.location.search;
	$split_nya = url.split("=");
	OptionWS()
		.then(gen_option => generate_option(gen_option, 'Choose WS'))
		.then(inj_option => injectOptionToHtml(inj_option))
		.then(check_crud => checkUrl($split_nya[2]))
		.then(disable_ws => DoDisableWs($G_kondisi))
		.then(define_id_url => DoDefineId($G_kondisi, $split_nya[2]))	//$G_kondisi didapat dari fungsi DoDisableWs
		.then(getListHeader => getListData(getListHeader))
		.then(inj_prop_ws => injectPropWs($G_kondisi, (typeof $G_array === 'undefined' ? "0" : $G_array)))
		.then(def_json_id => define_json_id((typeof $G_array === 'undefined' ? "0" : $G_array)))//$G_id_ws didapat dari fungsi injectPropWs
		//.then(gen_det  		=> getDetail( (typeof $G_array === 'undefined' ? "0":decodeURIComponent($G_array.records[0].ws)) ))
		.catch(error => {
			console.log(error);
			alert("Some think Wrong!");
		});
});
function generate_option(PropHtmlData, judul) {
	var option = "";
	option += "<option value=''>--" + judul + "--</option>";
	var i;
	for (i = 0; i < PropHtmlData.records.length; i++) {
		var id_cost = decodeURIComponent(PropHtmlData.records[i].id_cost);
		var ws = decodeURIComponent(PropHtmlData.records[i].ws);
		var id_jo = decodeURIComponent(PropHtmlData.records[i].id_jo);
		option += '<option value="' + id_cost + '_' + id_jo + '">' + ws + '</option>';
	}
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
function OptionWS() {
	return new Promise(function (resolve, reject) {
		/*  */
		$.ajax("webservices/getListCutInWS.php",
			{
				type: "POST",
				processData: false,
				contentType: false,
				success: function (data) {
					resolve(jQuery.parseJSON(data));
					// console.log(data);
					/* 				var ObjectData = jQuery.parseJSON(data);
									console.log(ObjectData);
									var i;
									for (i = 0; i < ObjectData.records.length; i++) {
										var id_cost = decodeURIComponent(ObjectData.records[i].id_cost);
										var ws = decodeURIComponent(ObjectData.records[i].ws);
										var id_jo = decodeURIComponent(ObjectData.records[i].id_jo);
										option += '<option value="' + id_cost + '_' + id_jo + '">' + ws + '</option>';
									}
								
									$("#ws").append(option); */
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
		GenerateTableDetail(cost, jo);
	}
}

exist = 0;
i_trigger = 0;
function GenerateTableDetail(idcost, idjo) {


	$detail = [];
	TableDetail = $('#tbl_cut_in').DataTable({
		"serverSide": true,
		"bSort": false,
		"method": "POST",
		"destroy": true,
		"ajax": "webservices/getListCutInDetailWS.php?id_cost=" + idcost + "&id_url=" + id_url + "&id_jo=" + idjo,
		"columns": [
			// { "data": "kpno" },
			{ "data": "fabric_code" },
			{ "data": "material_color" },
			{ "data": "fabric_desc" },
			{ "data": "qty_yard_need","className": "right" },
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.button);
				}
			},
			{ "data": "balance1","className": "right" },
			{ "data": "unit1" },
			{ "data": "qty_yard_need","className": "right" },
			{
				"data": null,
				"render": function (data) {
					return decodeURIComponent(data.button2);
				}
			},
			{ "data": "balance2","className": "right" },
			{ "data": "unit2" }
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
	var id = that.id;
	var index_array = id.split('_');
	var idx = index_array[1];
	var vl = that.value;
	// console.log(that.value)
	// return false;
	if (index_array[0] == 'qtyyard') {
		create_separator(vl,id).then(function($responnya) {
			$detail[idx].qty_yard = vl;
			$("#"+id).val($responnya.angka);
			console.log($responnya.angka);
	});
	}
	else if (index_array[0] == 'qtyroll') {
		create_separator(vl,id).then(function($responnya) {
			$detail[idx].qty_roll = vl;
			$("#"+id).val($responnya.angka);
	});
	}
	
}
function Save() {
//$detail[idx].qty_yard
	for(var i=0;i<$detail.length;i++){
		var $t_qty_yard = remove_separator($("#qtyyard_"+i).val());
		console.log($t_qty_yard);
		if(isNaN($t_qty_yard)){
			alert("qty_yard baris ke-"+i+"Salah Format");
			$("#qtyyard_"+i).val("0.00");
			return false;
		}
		var $t_qty_roll = remove_separator($("#qtyroll_"+i).val());
		if(isNaN($t_qty_roll)){
			alert("qty_roll baris ke-"+i+"Salah Format");
			$("#qtyroll_"+i).val("0.00")
			return false;
		}				
		$detail[i].qty_yard = $t_qty_yard;
		$detail[i].qty_roll = $t_qty_roll;
	}




	if ($data.id == "") {
		var format = "1";
	} else {
		var format = "2";
	}

	$data.dtpicker = $("#dtpicker").val();
	$data.ws = $("#ws").val();

	//validation $data
	if ($data.ws == '') {
		Swal.fire({
			type: 'Error!',
			title: 'Validation',
			text: 'WS must be filled',
			footer: '-_-'
		});
		return false;
	}

	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveCuttingInput.php",
		data: { code: '1', format: format, data: $data, detail: $detail },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			// console.log(data);
			d = JSON.parse(data);
			//console.log(d.message);
			if (d.respon == '200') {
				// Swal.fire("Success!", "Data Has Been Save", "success");
				// setTimeout(function () {
				alert('Data Berhasil Di Save')
				window.location.href = "?mod=2WP";
				// }, 3000)
			}
			else {
				Swal.fire({
					type: 'Error!',
					title: 'Validation',
					text: d.message,
					footer: '-_-'
				});
			}
		}
	});
}

function getListData($id) {
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