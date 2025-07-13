$(document).ready(function () {
	GenerateTable();
})


function GenerateTable() {
	table = $('#itemDC').DataTable({
		'processing': true,
		'serverSide': true,
		"lengthMenu": [[999999999], ["All"]],
		"ajax": {
			"url": "webservices/getListDCSet.php",
			"type": "POST"
		},
		"columns": [
			//description
			{
				"data": "no",
				"className": "center"
			},
			{ "data": "dc_set" },
			{ "data": "buyer" },
			{ "data": "styleno" },
			{ "data": "ws" },
			{ "data": "created_by" },
			{
				"data": null,
				"render": function (data) {
					// console.log(data)
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
		"ordering": false,
		// fixedColumns: {
		// 	leftColumns: 7
		// },
		dom: 'Bfrtip',

	})
}


function addNew_() {
	// alert("ok");
	window.location = "./?mod=7LA"
}


function edit(id_dc_out) {
	window.location = "/prod/?mod=7LA&id=" + id_dc_out
}