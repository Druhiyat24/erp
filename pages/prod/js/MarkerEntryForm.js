$(window).on("load", function () {
	// show_loading();
	url = window.location.search;
	$split = url.split("=");
	d_api = [];
	// Id URL
	$id = $split[2];
	$url = $id.split("&");
	$id_url = $url[0];

	// Id Cost
	$ws = $split[3];
	$cost = $ws.split("&");
	$id_cost = $cost[0];

	// Color
	$clr = $split[4];
	$color = decodeURI($clr);

	// alert($id_cost)
	$("#bcol").prop('disabled', true);
	getListHeader($id_cost)
	// getListPanel($id_url)
	$("#color").val($color).prop('disabled', true);
	//$("#item-select").hide();
	getListGenerateJsonMarker($id_url, $color)
		.then(xxx => getListPanel($id_cost, $color))
		.then(gen_option => generate_option(gen_option, 'Choose Panel'))
		.then(inj_option => injectOptionToHtml(inj_option))
		.then(xxx => getListItem($id_cost, $color))
		.then(gen_option => generate_option2(gen_option, 'Choose Item'))
		.then(inj_option => injectOptionToHtml2(inj_option))
		.then(xxx => existingTab())
		.catch(error => {
			console.log(error);
			alert("Some think Wrong!")
		});

	// sync_perhitungan_marker();

	Data = {
		tabPanel: ''
	}
});


function getListGenerateJsonMarker($id_url, $color) {
	return new Promise(function (resolve, reject) {
		/*  */
		$.ajax("webservices/generate_json_marker.php?id_mark_entry=" + $id_url + "&color=" + $color,
			{
				type: "POST",
				processData: false,
				contentType: false,
				success: function (data) {
					$populasi_array_marker = jQuery.parseJSON(data).records[0]
					console.log($populasi_array_marker)
					resolve(jQuery.parseJSON(data));
				},
				error: function (data) {
					alert("Error req");
				}
			});
	});
}


function getListPanel($id_cost, $color) {
	return new Promise(function (resolve, reject) {
		/*  */
		$.ajax("webservices/getListMarkEntryPanel.php?id_cost=" + $id_cost + "&color=" + $color,
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
	option += "<option value='' selected>--" + judul + "--</option>";
	var i;
	for (i = 0; i < PropHtmlData.records.length; i++) {
		var id = decodeURIComponent(PropHtmlData.records[i].id);
		var panel = decodeURIComponent(PropHtmlData.records[i].panel);
		option += '<option value="' + id + '_' + panel + '">' + panel + '</option>';
	}
	return option;
}


function injectOptionToHtml(string) {
	return $("#panel").append(string);
}


function newPanel(val) {
	// $("#bcol").prop('disabled', false);

	$value = val;
	$val = $value.split("_");
	$idTab = $val[0];
	$valTab = $val[1];

	// GenerateTableExistingItemTab()
}


function getListItem($id_cost, $color) {
	return new Promise(function (resolve, reject) {
		/*  */
		$.ajax("webservices/getListMarkEntryItem2.php?id_cost=" + $id_cost + "&color=" + $color,
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
		var id_item = decodeURIComponent(PropHtmlData.records[i].id_item);
		var id_jo = decodeURIComponent(PropHtmlData.records[i].id_jo);
		var item = decodeURIComponent(PropHtmlData.records[i].item);
		option += '<option value="' + id_item + '_' + id_jo + '">' + item + '</option>';
	}
	return option;
}


function injectOptionToHtml2(string) {
	return $("#item").append(string);
}

setTimeout(function () {
	$('#panel').on('change', function () {
		// alert('123')
		// alert($('#panel').val())
		if ($('#panel').val() == '') {
			$('#item-select').hide()
			$('#bcol').prop('disabled', true)
		}
		else {
			$('#item-select').show()
			$('#bcol').prop('disabled', false)
		}
	})
}, 1000)


setTimeout(function () {
	$('#item').on('change', function () {
		itemmm = $(this).val()
		mmm = itemmm.split("_")
		_id_item = mmm[0]
		_id_jo = mmm[1]
	})
}, 1000)


function saveTab() {
	// alert($id_item)

	if ($('#item').val() == null) {
		alert('Please Select The Item First')
	}

	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveMarkerEntryDetail.php",
		data: { code: '1', format: '1', id_cost: $id_cost, clr: $color, id: $id_url, tab: $idTab, item: _id_item, jo: _id_jo },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			d = JSON.parse(data);
			console.log(d);
			if (d.respon == '200') {
				alert('Success Add Tab')
				window.location = '';
				sync_perhitungan_marker($id_cost, $id_url, $color, $id_panel);
				// existingTab()
			}
			else {
				alert('Sabar Ya Ini Ujian')
			}
		}
	});
}


function GenerateTableExistingItemTab($__id_panel) {
	// alert('coba ulang 2')
	table = $(".item_item").DataTable({
		"processing": true,
		"serverSide": true,
		"lengthMenu": [[999999999], ["All"]],
		"searching": false,
		"paging": false,
		"bInfo": false,
		"ajax": {
			"url": "webservices/getListMarkEntryItem.php?id_cost=" + $id_cost + "&color=" + $color + "&panel=" + $__id_panel,
			"type": "POST"
		},
		"columns": [
			//description
			{
				"data": "no",
				"className": "center"
			},
			{ "data": "fabric_code" },
			{ "data": "fabric_desc" },
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.button);
				},
				"className": "center"
			}
		],
		"autoWidth": true,
		"scrollCollapse": true,
		scrollY: "200px",
		scrollX: true,
		scrollCollapse: true,
		"destroy": true,
		//order: [[ 1, "asc" ]],
		"ordering": false,
		dom: 'Bfrtip',
	});
}


function search_key($id_panel, $_array_marker) {
	for (var i = 0; i < $_array_marker.length; i++) {
		for (var j = 0; j < $_array_marker[i]['panel'].length; j++) {
			var obj = $_array_marker[i]['panel'];
			if (obj[j]['id_panel'] == $id_panel) {
				return i;
			} else {
				console.log('Search Key');
			}
		}
	}
}


$keyPanel = '';


function get_id_panel(_id_panel) {
	$id_panel = _id_panel;
	// alert($id_panel)

	$keyPanel = search_key($id_panel, $populasi_array_marker)
	$keyPanelItem = $populasi_array_marker[$keyPanel].panel

	existingTabBody($id_panel)
		.then(ppp => GenerateTableExistingItemTab($id_panel))
		.then(yyy => getLoopTblDynamic($keyPanelItem))
		.then(xx => getLoopGetDetail($id_panel, $keyPanelItem))
}


function getLoopTblDynamic($_keyPanelItem) {
	// alert('coba ulang 1')

	$('#tbl_dynamic_induk').html("");
	for (var i = 0; i < $_keyPanelItem.length; i++) {
		//alert(i);
		// console.log(i);
		$('#tbl_dynamic_induk').append('<div id="tbl_dynamic_' + i + '"></div >');
	}
	setTimeout(function () {
		return 1
	}, 3000)

}


var Count = 0;


function existingTab() {
	var tabtab = [];
	$.when(
		$.ajax({
			type: "POST",
			cache: false,
			url: "webservices/Mark_Entry_GetListData.php",
			data: { code: '1', id_cost: $id_cost, clr: $color, id: $id_url },     // multiple data sent using ajax
			success: function (response) {
				data = response;
				console.log(data);
				d = JSON.parse(data);
				console.log(d.message);
				if (d.respon == '200') {
					tabtab.push(d.records);
				} else {
					alert('Erorr Cuxxx!')
				}
			}
		})).then(function () {
			// console.log(tabtab[0].panel);
			// alert(tabtab[0].panel)
			// return false

			for (var i = 0; i < tabtab[0].length; i++) {

				if (tabtab[0][i].panel === '') {
					console.log('No Panel Existing');
					// alert("No Panel Existing")
				}
				else {
					console.log('Panel Ready');

					Count++;

					var tabHeader =
						'<li role="presentation" class="new-tab-' + Count + '">'
						+ '		<a href="#new-tab-' + decodeURIComponent(tabtab[0][i].id) + '" onclick="get_id_panel(\'' + decodeURIComponent(tabtab[0][i].id) + '\')" aria-controls="home" role="tab" data-toggle="tab" class="klik-tab-' + Count + '">'
						+ decodeURIComponent(tabtab[0][i].panel)
						// + '<span href="javascript:void(0)" style="" onclick="removeTab(\'new-tab-' + Count + '\')"> x</span>'
						+ '		</a>'
						+ '</li>';

					var tabBody =
						'<div class="tab-pane fade in" id="new-tab-' + decodeURIComponent(tabtab[0][i].id) + '">'
						+ '</div>';

					$('.mytabs').append(tabHeader);
					$('.mytabsContent').append(tabBody);
					// $('.nav-tabs a[href="#new-tab-' + Count + '"]').tab('show');
					$('.nav-tabs ').tab('show');
				}
			}
		});
}


function existingTabBody($id_panel) {
	// alert('coba ulang')
	return new Promise(function (resolve, reject) {

		$('.tab-pane').empty()

		var tabBodyItem =
			'<div class="col-md-12" style="margin-bottom: 45px;">'
			+ '		<table class="item_item" class="table-bordered table table-striped" style="width: 100%">'
			+ '			<thead>'
			+ '				<tr>'
			+ '					<th style="text-align: center">No</th>'
			+ '					<th style="text-align: center">Fabric Code</th>'
			+ '					<th style="text-align: center">Fabric Name</th>'
			+ '					<th style="text-align: center">Action</th>'
			+ '				</tr>'
			+ '			</thead>'
			+ '		</table>'
			+ '	</div>'
			+ '	<div id="tbl_dynamic_induk">'
			+ '	</div>';

		setTimeout(function () {
			$('#new-tab-' + $id_panel).append(tabBodyItem)
			$('#new-tab-' + $id_panel).css('display', '')
			resolve(1)
		}, 2000)

	})
}


function editItem(_id_item, _id_jo) {
	// alert(_id_jo)
	// return false

	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveMarkerEntryDetailNewTab.php",
		data: { code: '1', format: '2', id_cost: $id_cost, clr: $color, id: $id_url, id_item: _id_item, id_jo: _id_jo, panel: $id_panel },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			d = JSON.parse(data);
			console.log(d);
			if (d.respon == '200') {
				alert('Success Add Item')
				window.location = '';
			}
			else {
				alert('Sabar Ya Ini Ujian')
			}
		}
	});

}


function Cancel() {
	// alert('123')
	window.location = "?mod=PW";
}


function getListHeader($id) {

	return new Promise(function (resolve, reject) {
		$.ajax({
			type: "POST",
			cache: false,
			url: "webservices/getListMarkEntryHeader.php",
			data: { code: '1', id: $id },     // multiple data sent using ajax
			success: function (response) {
				data = response;
				console.log(data);
				d = JSON.parse(data);
				console.log(d.message);
				if (d.respon == '200') {
					// Ddata.id = decodeURIComponent(d.records[0].id);
					$("#ws").val(decodeURIComponent(d.records[0].ws)).prop("disabled", true)
				}
				else {
					alert('Error')
				}
			}
		});
	});

}


function getLoopGetDetail($_id_panel__, $_keyPanelItem__) {
	// alert('coba ulang 3')
	console.log($_keyPanelItem__)

	for (let ax = 0; ax < $_keyPanelItem__.length; ax++) {
		// alert('ini looping' + ax)
		// console.log($_keyPanelItem__[ax]['id_item'])
		setTimeout(function () {
			getDetail($id_cost, $color, $id_url, $_id_panel__, $_keyPanelItem__[ax]['id_item'], ax)
		}, 5000)
	}

}


function getDetail($id_cost, $color, $id_url, $_id_panel__, $_keyPanelItem__, axxx) {
	// console.log($keyPanelItem); return false
	// console.log(_item_); return false
	array_rasio = [];
	return new Promise(function (resolve, reject) {
		$.ajax({
			type: "POST",
			cache: false,
			url: "webservices/getListMarkEntryDetail.php",
			data: { code: '1', id: $id_cost, clr: $color, url: $id_url, panel: $_id_panel__, itemColl: $_keyPanelItem__, seq: axxx },     // multiple data sent using ajax
			success: function (response) {
				data = response;
				d = JSON.parse(data);
				d_api[axxx] = JSON.parse(data);
				console.log(d_api);
				// return false
				if (d.respon == '200') {
					console.log(d_api);
					$("#tbl_dynamic_" + axxx).append(decodeURIComponent(d.records));
					hide_loading();
				}
				else {
					alert("Coba Lagi")
				}
			}
		});
	});

}


/* function getDetailNewTab($id_cost, $color, $id_url) {
	// alert($id_url); return false
	array_rasio = [];
	return new Promise(function (resolve, reject) {
		$.ajax({
			type: "POST",
			cache: false,
			url: "webservices/getListMarkEntryDetailNewTab.php",
			data: { code: '1', id: $id_cost, clr: $color, url: $id_url },     // multiple data sent using ajax
			success: function (response) {
				data = response;
				d = JSON.parse(data);
				d_api = JSON.parse(data);
				console.log(d.message);
				// return false
				if (d.respon == '200') {
					console.log(d_api);
					$("#tbl_dynamic2").append(decodeURIComponent(d.records));
					hide_loading();
				}
				else {
					// alert("Coba Lagi")
					$("#tbl_dynamic2").append(decodeURIComponent(d.records))
				}
			}
		});
	});

} */


function getModalSize($idcost, $color, $group) {
	$gGroup = $group;
	Detail = [];
}

$busy = 0

function handleKeyUp(that) {
	$busy = 1

	var id = that.id;
	var index_array = id.split('_');
	var idx = index_array[1];

	var vl = that.value;
	key_id = index_array[0] + "_" + index_array[1]
	//console.log(d_api[index_array[2]].array_rasio[0][key_id].ratio)

	if (index_array[0] == 'ratio') {
		// alert(d_api[index_array[2]])
		d_api[index_array[2]].array_rasio[0][key_id].ratio = vl;
		setTimeout(function () {
			//console.log(d_api.array_rasio[0]);
		}, 2000)
	}
	if (index_array[0] == 'spread') {
		d_api[index_array[2]].array_spread[0][key_id].spread = vl;
		setTimeout(function () {
			//console.log(d_api.array_spread[0]);
		}, 2000)
	}
	if (index_array[0] == 'yds' || index_array[0] == 'inch') {
		_id = that.id;
		var pecah_id = that.id.split("_");
		if (pecah_id[0] == 'inch' || pecah_id[0] == 'yds') {
			_id = "yds_" + pecah_id[1];
			_tmp = pecah_id[0];
		}
		console.log(_tmp);
		console.log(d_api[index_array[2]].array_sum_ratio[0][_id][_tmp]);
		d_api[index_array[2]].array_sum_ratio[0][_id][_tmp] = vl;
		setTimeout(function () {
		}, 2000)
	}
	if (index_array[0] == 'gsm' || index_array[0] == 'width' || index_array[0] == 'allow' || index_array[0] == 'bcg') {
		console.log(d_api);
		tmp = index_array[0]
		console.log(d_api[index_array[1]].array_length[0]['lengths']);

		setTimeout(function () {
			if (index_array[0] == 'gsm') {
				d_api[index_array[1]].array_length[0]['lengths'].gsm = vl;
			}
			if (index_array[0] == 'width') {
				d_api[index_array[1]].array_length[0]['lengths'].width = vl;
			}
			if (index_array[0] == 'allow') {
				d_api[index_array[1]].array_length[0]['lengths'].allow = vl;
			}
			if (index_array[0] == 'bcg') {
				d_api[index_array[1]].array_length[0]['lengths'].bcg = vl;
			}
		}, 0)
	}
}
function saveDetail() {

	// if (Data.id == "") {
	// 	var format = "1";
	// } else {
	// 	var format = "2";
	// }

	Data.idgroup = $gGroup
	Data.panel = $("#panel").val()
	// Data.yds = $("#yds").val()
	// Data.inch = $("#inch").val()

	// if (Data.spread == '' || Data.spread == '0') {
	// 	alert('Spread Not Null')
	// 	return false
	// }
	// if (Data.yds == '' || Data.yds == '0') {
	// 	alert('Length YDS Not Null')
	// 	return false
	// }
	// if (Data.inch == '' || Data.inch == '0') {
	// 	alert('Length Inch Not Null')
	// 	return false
	// }

	// alert(Data.panel);
	// return false

	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveMarkerEntryDetail.php",
		data: { code: '1', format: '2', data: Data, detail: Detail[0] },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			d = JSON.parse(data);
			console.log(d);
			if (d.respon == '200') {
				$("#tbl_dynamic").append(decodeURIComponent(d.records))
			}
			else {
				console.log("Coba Lagi")
			}
		}
	});
}


function addTable($_panel_id_, $_item_id_, $_jo_id_) {
	// alert('123')
	// if (Data.id == "") {
	// 	var format = "1";
	// } else {
	// 	var format = "2";
	// }

	// Data.ws = $("#ws").val()

	// alert($id_url); return false
	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveMarkerEntryDetailAdd.php",
		data: { code: '1', format: '1', id_cost: $id_cost, clr: $color, id: $id_url, panel: $_panel_id_, item: $_item_id_, jo: $_jo_id_ },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			d = JSON.parse(data);
			console.log(d);
			if (d.respon == '200') {
				// window.location = "/prod/?mod=AL&id=" + id_me;
				// alert('Ratio Successfully Added');
				// location.reload();
				getRenderTabDetail()
				// getDetail($id_cost, $color, $id_url);
			}
			else {
				alert('This is not working')
			}
		}
	});

}


function getModalCons($by1, $by2) {
	$si_cost = $by1;
	$si_color = $by2;

	saveCons($si_cost, $si_color)
}


function saveCons($si_cost, $si_color) {

	Data.gsm = $("#gsm").val()
	Data.width = $("#width").val()
	Data.bcg = $("#bcg").val()

	costing = $si_cost
	warna = $si_color

	if (Data.gsm == '' || Data.gsm == '0') {
		alert('GSM Not Null')
		return false
	}
	if (Data.width == '' || Data.width == '0') {
		alert('Width Not Null')
		return false
	}
	if (Data.bcg == '' || Data.bcg == '0' || Data.bcg == '0.000') {
		alert('B. Cons/Kg Not Null')
		return false
	}

	// alert(warna);
	// return false

	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveMarkerEntryDetailAdd.php",
		data: { code: '1', format: '2', data: Data, id_cost: costing, clr: warna },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			d = JSON.parse(data);
			//console.log(d);
			if (d.respon == '200') {
				alert('Success bro')
				$("#tbl_dynamic").append(decodeURIComponent(d.records))
			}
			else {
				alert("Coba Lagi")
				$("#tbl_dynamic").append(decodeURIComponent(d.records))
			}
		}
	});
}


function remove($group) {
	$_Group = $group.split("&")
}


function drop() {
	show_loading()
	// return false
	// id = { id: $_Group }
	Data.url = $id_url
	Data.cost = $id_cost
	Data.color = $color
	Data.group = $_Group[0]
	Data.panel = $_Group[1]
	Data.item = $_Group[2]
	var format = "3";
	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveMarkerEntryDetailAdd.php",
		data: { code: '1', format: format, data: Data },
		success: function (response) {
			data = response;
			// console.log(data);
			d = JSON.parse(data);
			console.log(d.message);
			if (d.respon == '200') {
				// alert('Ratio Successfully Removed')
				// $('#myModal4').fadeOut(1000)
				// $('#modal-backdrop').removeClass('in')
				hide_loading()
				sync_perhitungan_marker($id_cost, $id_url, $color, $id_panel)
				getRenderTabDetail()
			}
			else {
				alert('Failed Cuy')
			}
		}
	})
}


function save_all() {
	show_loading();
	console.log(d_api);
	// for (var i = 0; i < d_api.length; i++) {
	// 	d_api[i]['records'] = ''
	// }


	// console.log(panel)

	// alert('123')
	// return false
	setTimeout(function () {

		$.ajax({
			type: "POST",
			cache: false,
			url: "webservices/saveMarkerEntryDetail.php",
			data: { code: '1', format: '2', data: Data, id_cost: $id_cost, clr: $color, d_api: d_api, id: $id_url },
			success: function (response) {
				data = response;
				d = JSON.parse(data);
				console.log(d.message);
				if (d.respon == '200') {
					// location.reload();
					sync_perhitungan_marker($id_cost, $id_url, $color, $id_panel);
					getRenderTabDetail()
				}
				else {
					alert('Failed Cuy')
				}
			}
		});
	}, 100)
}


function getRenderTabDetail() {
	hide_loading()
	// console.log($id_panel)

	// getLoopTblDynamic($keyPanelItem)
	// 	.then(xx => getLoopGetDetail($id_panel, $keyPanelItem))
	// getLoopGetDetail($id_panel, $keyPanelItem)

	$.when(getLoopTblDynamic($keyPanelItem))
		.then(xx => getLoopGetDetail($id_panel, $keyPanelItem));
}


function sync_perhitungan_marker($id_cost, $id_url, $color, $id_panel) {

	var _arr = {
		id_cost: $id_cost,
		id_mark_entry: $id_url,
		color: $color,
		panel: $id_panel
	}

	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/sync_perhitungan_marker.php",
		data: { code: '1', format: '2', data: _arr },
		success: function (response) {
			data = response;
			// console.log(data);
			d = JSON.parse(data);
			console.log(d.message);
			if (d.respon == '200') {
				// alert('Data Success');
				// setTimeout(function () {
				// 	location.reload();
				// }, 3000)
				console.log('Sync Success')
			}
			else {
				// alert('Failed Cuy')
				console.log('Sync Failled')
			}
		}
	});
}