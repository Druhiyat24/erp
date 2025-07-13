$(document).ready(function () {
	GenerateTable();
});



function GenerateTable() {
	table = $("#sewing").DataTable({
		'processing': true,
		'serverSide': true,
		"lengthMenu": [[999999999], ["All"]],
		"ajax": {
			"url": "webservices/getListSewingOutput.php",
			"type": "POST"
		},
		"columns": [
			//description
			{
				"data": "no",
				"className": "center"
			},
			{ "data": "buyer" },
			{ "data": "styleno" },
			{ "data": "ws" },
			// { "data": "qty" },
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


// $(".addNew").on("click", function addNew() {
// 	// alert("ok");
// 	window.location = "/prod/?mod=2LA";
// })

function addNew_() {
	// alert("ok");
	window.location = "./?mod=9LA";
}


function edit(id_sew_out) {
	window.location = "/prod/?mod=9LA&id=" + id_sew_out;
}