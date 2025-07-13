$(window).on("load", function () {
	GenerateTable();
});

function GenerateTable() {
	table = $("#item").DataTable({
		'processing': true,
		'serverSide': true,
		"lengthMenu": [[999999999], ["All"]],
		"ajax": {
			"url": "webservices/getListSecondaryOutput.php",
			"type": "POST"
		},
		"columns": [
			//description
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.no_sec_out);
				},
				"className": "center"
			},
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.buyer);
				},
				"className": "center"
			},
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.styleno);
				},
				"className": "center"
			},
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.kpno);
				},
				"className": "center"
			},
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.so_no);
				},
				"className": "center"
			},
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.buyerno);
				},
				"className": "center"
			},
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.date_output);
				},
				"className": "center"
			},
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.created_by);
				},
				"className": "center"
			},
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.is___edit);
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
		dom: 'Bfrtip',

	});
}

function addNew_() {
	window.location = "./?mod=5LA";
}
