$(document).ready(function () {
	url = window.location.search;
	get_url(url)
	.then(X						=> auth($populasi_id_url.mod,"page"))
	.then(akses					=> is_route(akses))
	.then(X 					=> GenerateTable())
	
	
	//GenerateTable();
});





function GenerateTable() {
	table = $("#List").DataTable({
		'processing': true,
		'serverSide': true,
		"lengthMenu": [[999999999], ["All"]],
		"ajax": {
			"url": "webservices/getListQcFinalOutput.php?mod="+$populasi_id_url.mod,
			"type": "POST"
		},
		"columns": [
			//description
			{
				"data": null,
				"render": function (data) {
					console.log(data);
					return decodeURIComponent(data.no_sew_qc_final_out);
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
					return decodeURIComponent(data.time);
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


// $(".addNew").on("click", function addNew() {
// 	// alert("ok");
// 	window.location = "/prod/?mod=2LA";
// })

function addNew_() {
	// alert("ok");
	window.location = "./?mod=QC_F_O_Form&cr=is_add";
}
