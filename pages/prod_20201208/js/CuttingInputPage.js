$(document).ready(function () {
	GenerateTable();
});



function GenerateTable() {
	table = $('#CutIn').DataTable({
		'processing': true,
		'serverSide': true,
		"lengthMenu": [[999999999], ["All"]],
		//"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
		//"ajax":  "webservices/getListCuttingInput.php",
		"ajax": {
			"url": "webservices/getListCuttingInput.php",
			"type": "POST"
		},
		"columns": [
			//description
			{ "data": "no" },
			{ "data": "kpno" },
			{ "data": "styleno" },
			// { "data": "so_no" },
			{ "data": "username" },
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.button);
				}
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

function edit(id_cut_in) {
	window.location = "/prod/?mod=2LA&id=" + id_cut_in;
}