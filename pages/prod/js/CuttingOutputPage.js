$(document).ready(function () {
	GenerateTable();
});



function GenerateTable() {
	table = $('#CutOut').DataTable({
		'processing': true,
		'serverSide': true,
		"lengthMenu": [[10, 25, 50, 999999999], [10, 25, 50, "All"]],
		'serverMethod': 'post',
		"ajax": "webservices/getListCuttingOutput.php",
		"columns": [
			{
				"data": "no",
				"className": "center"
			},
			{ "data": "cutting_out" },
			{ "data": "Supplier" },
			{ "data": "kpno" },
			{ "data": "styleno" },
			{ "data": "color" },
			{ "data": "username" },
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
		"ordering": false,
		//"order": [[1, 'asc']],
		scrollY: "500px",
		scrollX: true,
		scrollCollapse: true,
		"destroy": true,
		//order: [[ 6, "desc" ]],
		// fixedColumns: {
		// 	leftColumns: 1
		// },
		dom: 'Bfrtip',
		header: true,
		dom:
			"<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-5'i><'col-sm-7'p>>",
	});
}

function edit(id_cut_out) {
	window.location = "/prod/?mod=3LA&id=" + id_cut_out;
}

function preview(id_cut_out) {
	window.location = "/prod/?mod=3LA&id=" + id_cut_out + "&v=1";
}