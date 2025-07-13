$(document).ready(function () {
	url = window.location.search;
	$split = url.split("id=");
	$id_edit = $split[1];

	// if ($split[1]) {
	// 	getListData($split[1]);
	// }

	// getListInHouseSubcon();
	// getListProses();
	// getListWS((typeof $split[1] === 'undefined' ? "-1" : $split[1]));

	// $("#panel").attr('placeholder', 'hello');
	// $("#panel").placeholder("Hai")
	// document.getElementById("panel").placeholder = "Type name here..";
	// $('#tanggalinput').datepicker
	// 	({
	// 		format: "dd M yyyy",
	// 		autoclose: true
	// 	});

	Ddata = {
		id: '',
		tanggalinput: '',
		notes: '',
		inhousesubcon: '',
		deptsubcon: '',
		proses: '',
		ws: '',
		panel: '',
	}
	item = [];

	getListWS((typeof $split[1] === 'undefined' ? "-1" : $split[1]))
		.then(X						=> check_cr($populasi_id_url.mod,$populasi_id_url.cr))
		.then(X						=> auth($populasi_id_url.mod,$populasi_id_url.cr))
		.then(akses					=> is_route(akses))	
		.then(akses					=> check_validation_again())	
		.then(gen_option => generate_option(gen_option))
		.then(inj_option => injectOptionToHtml(inj_option))
		.then(XX => getListLine())
		.then(gen_option => generate_option2(gen_option, 'Choose Line'))
		.then(inj_option => injectOptionToHtml2(inj_option))
		.then(check_crud => checkUrl($split[1]))
		.then(B => initFormat($G_kondisi))//inisialisasi format (1=NEW, 2 =UPDATE
		.then(disable_ws => DoDisableWs($G_kondisi))
		.then(define_id_url => DoDefineId($G_kondisi, $split[1]))	//$G_kondisi didapat dari fungsi DoDisableWs
		.then(getListHeader => getListData(getListHeader))
		.then(A => initDeptSubcon($G_kondisi))
		// .then(inj_prop_ws => injectPropWs($G_kondisi, (typeof $G_array === 'undefined' ? "0" : $G_array)))
		.then(def_json_id => define_json_id((typeof $G_array === 'undefined' ? "0" : $G_array)))
		.catch(error => {
			console.log(error);
			alert("Some think Wrong!")
		});

	// alert($split[1])
});


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
	} else if (myValue.id == "panel") {
		Ddata.panel = $("#panel").val()
	}

	var id = myValue.id;
	var index_array = id.split('_');
	var idx = index_array[1];
	var vl = myValue.value;
	if (index_array[0] == 'qty') {
		item[idx].qty_val = vl;
	}

}


function getListWS($id_edit) {
	return new Promise(function (resolve, reject) {
		/*  */
		$.ajax("webservices/getListQCInWS.php?id_url=" + $id_edit,
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


function generate_option(PropHtmlData) {
	//console.log(PropHtmlData);

	var option = "";
	// option += "<option value=''>--" + judul + "--</option>";
	var i;
	for (i = 0; i < PropHtmlData.records.length; i++) {
		var id_cost = decodeURIComponent(PropHtmlData.records[i].id);
		var sew_out = decodeURIComponent(PropHtmlData.records[i].sew_out);
		var ws = decodeURIComponent(PropHtmlData.records[i].ws);
		option += '<option value="' + id_cost + '_' + sew_out + '">' + ws + '</option>';
	}
	return option;
}


function injectOptionToHtml(string) {
	return $("#ws").append(string);
}


function getListLine() {
	return new Promise(function (resolve, reject) {
		/*  */
		$.ajax("webservices/getListQCInLine.php",
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


function generate_option2(PropHtmlData, judul) {
	//console.log(PropHtmlData);

	var option = "";
	option += "<option value='' selected disabled>--" + judul + "--</option>";
	var i;
	for (i = 0; i < PropHtmlData.records.length; i++) {
		var id = decodeURIComponent(PropHtmlData.records[i].id);
		var nama = decodeURIComponent(PropHtmlData.records[i].line);
		option += '<option value="' + id + '">' + nama + '</option>';
	}
	return option;
}


function injectOptionToHtml2(string) {
	return $("#line").append(string);
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


function getDetail() {
	// console.log(Ddata.panel4q)
	item = [];
	TableDetail = $('#detail_item').DataTable({
		"processing": true,
		"serverSide": true,
		"bSort": false,
		"method": "POST",
		"destroy": true,
		"ajax": "webservices/getListSecInDetail.php?id_ws=" + Ddata.ws + "&id_panel=" + Ddata.panel + "&id_url=" + $id_edit,
		"columns": [
			{
				"data": "no",
				"className": "center"
			},
			{ "data": "buyer" },
			{ "data": "styleno" },
			// { "data": "ws" },
			{ "data": "panel" },
			{ "data": "color" },
			{ "data": "size" },
			{ "data": "oke" },
			// {
			// 	"data": null,
			// 	"render": function (data) {
			// 		console.log(data);
			// 		return decodeURIComponent(data.oke);
			// 	}
			// },
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.qty);
				}
			}
		],
		"rowCallback": function (nRow, data, index) {
			item.push(data);
			//i_trigger = 1+1;
		},
		scrollX: true,
		scrollY: "300px",
		scrollCollapse: true,
		scrollXInner: "100%",
		paging: false,
		fixedColumns: true,

	});

}

function Cancel() {
	// alert('123')
	window.location = "?mod=10WP";
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
		var format = "1";
	} else {
		var format = "2";
	}

	Ddata.tanggalinput = $("#tanggalinput").val()
	Ddata.notes = $("#notes").val()
	Ddata.inhousesubcon = $("#inhousesubcon").val()
	Ddata.deptsubcon = $("#deptsubcon").val()
	Ddata.proses = $("#proses").val()
	Ddata.ws = $("#ws").val()


	var stringDetail = JSON.stringify(item);
	var detail64 = btoa(stringDetail);


	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveSecondaryInput.php",
		data: { code: '1', format: format, data: Ddata, detail: detail64 },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			// console.log(stringDetail);
			d = JSON.parse(data);
			//console.log(d.message);
			if (d.respon == '200') {
				alert('Data Berhasil Di Save')
				window.location.href = "?mod=4WP";
			}
			else {
				alert('Data Gak Masuk')
			}
		}
	});

}


function getListData($id) {
	// $("#ws").prop("disabled", true)
	if ($G_kondisi == 0) {
		return "1";
	}
	else {
		$("#panel").prop("disabled", true)
		// $("#cari").prop("disabled", true)

		// $("#panel").css("display", "none")
		$("#cari").css("display", "none")
		item = [];
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
						/* 						setTimeout(function () {
													$("#deptsubcon").val(decodeURIComponent(d.records[0].sup)).trigger("change")
												}, 5000) */

						Ddata.id = decodeURIComponent(d.records[0].id)
						$("#ws").val(decodeURIComponent(d.records[0].ws)).trigger("change")
						$("#inhousesubcon").val(decodeURIComponent(d.records[0].insub)).trigger("change")
						$("#proses").val(decodeURIComponent(d.records[0].proses)).trigger("change")
						$("#notes").val(decodeURIComponent(d.records[0].notes))
						Ddata.deptsubcon = decodeURIComponent(d.records[0].sup)

						getDetail()
						resolve("2");
						// if ($G_kondisi == '0') {
						// 	// getDetail()
						// 	getListInHouseSubcon(decodeURIComponent(d.records[0].insub))
						// 	getListDeptSubcon(decodeURIComponent(d.records[0].sup))
						// 	getListProses(decodeURIComponent(d.records[0].proses))
						// }
					}
					else {
						alert('Erorr!')
					}
				}
			});
		});
	}
}