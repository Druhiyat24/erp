$(window).on("load", function () {
	url = window.location.search;
	$split = url.split("=");
	$id_url = $split[2];

	Data = {
		id: ''
	}

	GenerateTable();

});


function GenerateTable() {
	// alert($id_url); return false
	table = $("#item").DataTable({
		"processing": true,
		"serverSide": true,
		"lengthMenu": [[999999999], ["All"]],
		// "searching": false,
		"ajax": {
			"url": "webservices/getListMarkerEntryColor.php?id=" + $id_url,
			"type": "POST"
		},
		"columns": [
			//description
			{
				"data": "no",
				"className": "center"
			},
			{ "data": "ws" },
			{ "data": "color" },
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
		scrollY: "500px",
		scrollX: true,
		scrollCollapse: true,
		"destroy": true,
		"ordering": false,
		dom: 'Brt',

	});
}


// function preview(id_me, color, $url) {
// 	// alert('123')
// 	// if (Data.id == "") {
// 	// 	var format = "1";
// 	// } else {
// 	// 	var format = "2";
// 	// }

// 	// Data.ws = $("#ws").val()

// 	$.ajax({
// 		type: "POST",
// 		cache: false,
// 		url: "webservices/saveMarkerEntryDetail.php",
// 		data: { code: '1', format: '1', id_cost: id_me, clr: color, id: $url },     // multiple data sent using ajax
// 		success: function (response) {
// 			data = response;
// 			d = JSON.parse(data);
// 			console.log(d);
// 			if (d.respon == '200') {
// 				// window.location = "/prod/?mod=AL&id=" + id_me;
// 			}
// 			else {
// 				alert('Sabar Ya Ini Ujian')
// 			}
// 		}
// 	});

// }


function back() {
	window.location = "../prod/?mod=PW";
}