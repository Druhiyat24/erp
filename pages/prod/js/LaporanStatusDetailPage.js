$( document ).ready(function() {
    localStorage.clear();
    sessionStorage.clear();
        $("#from").datepicker( {
      format: "dd M yyyy",
      autoclose: true
    });   
    $("#to").datepicker( {
      format: "dd M yyyy",
  
      autoclose: true
    });   
	
	 Option("webservices/Opt_Buyer.php")
		.then(data 					=> generate_option(data, '--Choose Buyer--'))//OPTION LINE  src
		.then(inj_option 			=> injectOptionToHtml(inj_option, 'buyer')) //OPTION LINE 
		.then(A 					=> Option("webservices/Opt_Status.php"))//OPTION LINE  src
		//.then(data 					=> generate_option(data, '--Choose Status--'))//OPTION LINE  src
		//.then(inj_option 			=> injectOptionToHtml(inj_option, 'status')) //OPTION LINE 
});
$(window).resize(function () {
	$("#buyer").select2({ width: 'resolve' });
	//$("#status").select2({ width: 'resolve' });
	$("#src").select2({ width: 'resolve' });
});

data = {
    iddatefrom : '',
    iddateto : '',
    tipejurnal : '',
    stts : ''
}
$reload = 0;
function Back(){
    overlayon();
    location.reload(true);
}
function getListData(){
	event.preventDefault();
    from 		= $("#from").val();
    to 			= $("#to").val();
	id_buyer 	= $("#buyer").val();
	//status 		= $("#status").val();
	buyer  		= $( "#buyer option:selected" ).text();

	if (buyer == "----Choose Buyer----") 
	{
		buyer = "All Buyer";
	} else {
		buyer = $( "#buyer option:selected" ).text();
	}

    if(!from){
        alert("Periode Must Be Filled");
        return false;
    }
    if(!to){
        alert("Periode To Must Be Filled");
    }   
    // if(!id_buyer){
    //     alert("Buyer Must Be Filled");
    //     return false;
    // }
    //if(!status){
    //    alert("Status Must Be Fill");
    //    return false;
    //}	
    GenerateTable(from,to,id_buyer,buyer);
    
    
/*     $("#label_from").text(from);
    $("#label_to").text(to); */
}

function overlayon(){

    $("#myOverlay").css("display","block");
    
}
function overlayoff(){
    $("#myOverlay").css("display","none");
}



/* For Export Buttons available inside jquery-datatable "server side processing" - Start
- due to "server side processing" jquery datatble doesn't support all data to be exported
- below function makes the datatable to export all records when "server side processing" is on */

function newexportaction(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;
    dt.one('preXhr', function (e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;
        dt.one('preDraw', function (e, settings) {
            // Call the original action function
            if (button[0].className.indexOf('buttons-copy') >= 0) {
                $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-print') >= 0) {
                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
            }
            dt.one('preXhr', function (e, s, data) {
                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                // Set the property to what it was before exporting.
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });
            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
            setTimeout(dt.ajax.reload, 0);
            // Prevent rendering of the full data to the DOM
            return false;
        });
    });
    // Requery the server with the new one-time export settings
    dt.ajax.reload();
};
//For Export Buttons available inside jquery-datatable "server side processing" - End
table = "";
CoaId = '';
function getidcoa(idcoa){
    CoaId = idcoa;
}
function GenerateTable(from,to,id_buyer,buyer){
	setTimeout(function(){
		//alert("123");
		//$(".dt-buttons").css("display","block !important");
		$('.dt-buttons').css('cssText', 'display: block !important');
		
		
	},3000)
	

table = $("#laporan").on('error.dt', function (e, settings, techNote, message) {
			console.log('An error has been reported by DataTables: ', message);
		})

			.DataTable({
				"processing": true,
				"serverSide": true,
				"lengthMenu": [[999999999], ["All"]],
				"ajax": {
				"url": "webservices/getDataLaporanDetail.php?from="+from+"&to="+to+"&id_buyer="+id_buyer,//+"&status="+status,
					"type": "POST"
				},
				        "columns": [
				    //Nomor WS
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.kpno);
						},
						//"className": "center"
					},
				    //Date Output
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.date_output);
						},
						"className": "center"
					},
				    //Nomor Proses
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.no_proses);
						},
						"className": "center"
					},
					//Nama Proses
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.Proses_Name);
						},
						"className": "right"
					},
					//PO buyer
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.buyerno);
						},
						"className": "right"
					},
					//Destination
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.dest);
						},
						"className": "right"
						//"className": "center"
					},
					//Color
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.color);
						},
						"className": "right"
						//"className": "center"
					},
					//size
					{
	
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.size);
						},
						"className": "right"
					},
					//Qty SO
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.qty_so);
						},
						"className": "right"
					},		
					//Unit SO				
					{"data": null,
						"render": function (data) {
							return decodeURIComponent(data.unit);
						},
						"className": "right"
					},
					//Qty Tiap Proses
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.Qty_Proses);
						},
						"className": "right"
					},
					//Balance Tiap Proses
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.Balance);
						},
						"className": "right"
					}

				],
				"drawCallback": function () {
					hide_loading();
				},
				"stripeClasses": [],
				"autoWidth": true,
				"scrollCollapse": true,
				scrollY: "500px",
				scrollX: true,
				scrollCollapse: true,

				fixedColumns: {
					leftColumns: 3
				},
				colReorder: true,
				"destroy": true,
				//order: [[ 1, "asc" ]],
				"ordering": false,
				/*         fixedColumns:   {
							leftColumns: 7
						}, */
      dom: 'Bfrtip',
        buttons: [
{

              extend: 'excel', 
              text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export to Excel',
              className: 'btn-primary',
              //title: 'Any title for file',
                        message: "Periode "+from+" Sampai "+to+" \n Buyer : "+buyer+"\n", // Status : "+status+"\n",
                      exportOptions:{
                          search :'applied',
                          order:'applied'
                      },

                       "action": newexportaction,
                      }       
        
            
        ], 
        header: true,

			});

}
