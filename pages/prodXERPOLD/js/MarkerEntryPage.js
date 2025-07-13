$(window).on("load", function () {
	GenerateTable();

	Data = {
		id: '',
		ws: ''
	}

	getListWS()
		.then(gen_option => generate_option(gen_option, 'Choose WS'))
		.then(inj_option => injectOptionToHtml(inj_option))
		.catch(error => {
			console.log(error);
			alert("Some think Wrong!")
		});
});


function getListWS() {
	return new Promise(function (resolve, reject) {
		/*  */
		$.ajax("webservices/getListMarkEntryWS.php",
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
	option += "<option value='' selected disabled>--" + judul + "--</option>";
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


function typeWS() {
	$("#ws").select2({ width: 'resolve' });
}


function GenerateTable() {
	table = $("#item").DataTable({
		"processing": true,
		"serverSide": true,
		// "lengthMenu": [[999999999], ["All"]],
		"ajax": {
			"url": "webservices/getListMarkerEntry.php",
			"type": "POST"
		},
		"columns": [
			//description
			{
				"data": "no",
				"className": "center"
			},
			{ "data": "buyer" },
			{ "data": "ws" },
			{ "data": "styleno" },
			{ "data": "created_by" },
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
		//order: [[ 1, "asc" ]],
		"ordering": true,
		/*         fixedColumns:   {
					leftColumns: 7
				}, */
		dom: 'Bfrtip',

	});
}


function saveWS() {

	if (Data.id == "") {
		var format = "1";
	} else {
		var format = "2";
	}

	Data.ws = $("#ws").val()

	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/saveMarkerEntry.php",
		data: { code: '1', format: format, data: Data },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			d = JSON.parse(data);
			console.log(d);
			if (d.respon == '200') {
				alert('Data Berhasil Di Save')
				// window.location.href = "?mod=PW";
				GenerateTable()
				$('#myModal2').modal('hide');

				$('#ws').empty()
				getListWS()
					.then(gen_option => generate_option(gen_option, 'Choose WS'))
					.then(inj_option => injectOptionToHtml(inj_option))
					.catch(error => {
						console.log(error);
						alert("Some think Wrong!")
					});
			}
			else {
				alert('Data Not In')
			}
		}
	});
}


// function addNew_() {
// 	window.location = "./?mod=AL";
// }

function preview(id_me) {
	window.location = "/prod/?mod=WL&id=" + id_me;
}