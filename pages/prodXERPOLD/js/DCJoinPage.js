$(document).ready(function () {
	GenerateTable();
})



function GenerateTable() {
	table = $('#itemDC').DataTable({
		'processing': true,
		'serverSide': true,
		"lengthMenu": [[999999999], ["All"]],
		"ajax": {
			"url": "webservices/getListDCJoin.php",
			"type": "POST"
		},
		"columns": [
			//description
			{
				"data": "no",
				"className": "center"
			},
			{ "data": "dc_join" },
			{ "data": "buyer" },
			{ "data": "styleno" },
			{ "data": "ws" },
			{ "data": "created_by" },
			{
				"data": null,
				"render": function (data) {
					// console.log(data);
					return decodeURIComponent(data.button)
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
	})
}


function addNew_() {
	// alert("ok");
	window.location = "./?mod=6LA"
}


function edit(id_dc_join) {
	window.location = "/prod/?mod=6LA&id=" + id_dc_join
}