$(window).on("load", function () {
	setTimeout(function () {
		GenerateTable();
	}
		, 2000)

	url = window.location.search;
	$split = url.split("=");
	$id_url = $split[2];
	Data = {
		id: '',
		ws: ''
	}


});




function GenerateTable() {
	table = $("#item").DataTable({
		"processing": true,
		"serverSide": true,
		"lengthMenu": [[999999999], ["All"]],
		"ajax": {
			"url": "webservices/getListSpreadingReportBySo.php?id=" + $split[2],
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
			{ "data": "so_no" },
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
		"ordering": false,
		/*         fixedColumns:   {
					leftColumns: 7
				}, */
		dom: 'Brt',
	});
}


function back() {
	window.location = "../prod/?mod=SpreadingReport";
}