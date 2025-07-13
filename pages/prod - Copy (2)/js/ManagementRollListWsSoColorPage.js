$(document).ready(function () {
	url = window.location.search;
	$split_nya = url.split("=");
	$id_so = $split_nya[2];
	setTimeout(function(){
	GenerateTable();
	},3000);
});



function GenerateTable() {
	table = $('#MRoll').DataTable({
		'processing': true,
		'serverSide': true,
		"lengthMenu": [[999999999], ["All"]],
		"ajax": {
			"url": "webservices/getListMRollWsSoColor.php?id_so="+$id_so,
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
			{ "data": "so_no" },
			{ "data": "color" },
			{ "data": "itemdesc" },
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


function addNew_() {
	// alert("ok");
	window.location = "./?mod=mRollForm";
}


function edit(id_cut_in) {
	window.location = "/prod/?mod=mRollForm&id=" + id_cut_in;
}