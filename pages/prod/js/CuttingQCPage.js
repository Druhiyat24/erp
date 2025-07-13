$(document).ready(function () {
	GenerateTable();
});



function GenerateTable() {
	table = $('#CutQC').DataTable({
		'processing': true,
		'serverSide': true,
		"lengthMenu": [[10, 25, 50, 999999999], [10, 25, 50, "All"]],
		'serverMethod': 'post',
		"ajax": "webservices/getListCuttingQC.php",
		"columns": [
			{
				"data": "no",
				"className": "center"
			},
			{ "data": "no_cut_qc" },
			{ "data": "buyer" },
			{ "data": "ws" },
			{ "data": "styleno" },
			// { "data": "color" },
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
	window.location = "/prod/?mod=cutQCColor&id=" + id_cut_out;
}