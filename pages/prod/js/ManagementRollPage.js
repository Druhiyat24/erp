$(document).ready(function () {
	GenerateTable();
});



function GenerateTable() {
	table = $('#MRoll').DataTable({
		'processing': true,
		'serverSide': true,
		// "lengthMenu": [[999999999], ["All"]],
		"ajax": {
			"url": "webservices/getListMRoll.php",
			"type": "POST"
		},
		"columns": [
			//description
			{
				"data": "no",
				"className": "center"
			},
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
		"ordering": false,
		/*         fixedColumns:   {
					leftColumns: 7
				}, */
		dom: 'Bfrtip',
	});
}


function addNew_() {
	// alert("ok");
	window.location = "./?mod=mRollForm";
}


function edit(id_cut_in) {
	window.location = "/prod/?mod=mRollForm&id=" + id_cut_in;
}