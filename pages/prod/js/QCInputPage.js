$(document).ready(function () {
	url = window.location.search;
	get_url(url)
	.then(X						=> auth($populasi_id_url.mod,"page"))
	.then(akses					=> is_route(akses))
	.then(X 					=> GenerateTable())
	
	
	//GenerateTable();
});




function GenerateTable() {
	table = $("#item").DataTable({
		"processing": true,
		"serverSide": true,
		"lengthMenu": [[999999999], ["All"]],
		"ajax": {
			"url": "webservices/getListQCInput.php?mod="+$populasi_id_url.mod,
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
			{ "data": "created_by" },
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.is___edit)+" "+decodeURIComponent(data.is___view);
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

function addNew_() {
	window.location = "./?mod=10LA&cr=is_add";
}

function edit(id_qc_in) {
	window.location = "/prod/?mod=10LA&id=" + id_qc_in + "&cr="+is_add;
}