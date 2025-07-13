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
		.then(data 					=> generate_option(data, '--Choose Status--'))//OPTION LINE  src
		.then(inj_option 			=> injectOptionToHtml(inj_option, 'status')) //OPTION LINE 
});
$(window).resize(function () {
	$("#buyer").select2({ width: 'resolve' });
	$("#status").select2({ width: 'resolve' });
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
	status 		= $("#status").val();
	buyer  		= $( "#buyer option:selected" ).text();
    if(!from){
        alert("Periode Must Be Fill");
        return false;
    }
    if(!to){
        alert("Periode To Must Be Fill");
    }   
    if(!id_buyer){
		id_buyer = "";
    }
    if(!status){
        status = "";
    }	
    GenerateTable(from,to,id_buyer,status,buyer);
    
    
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
function GenerateTable(from,to,id_buyer,status,buyer){
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
				"url": "webservices/getDataLaporanSummary.php?from="+from+"&to="+to+"&id_buyer="+id_buyer+"&status="+status,
					"type": "POST"
				},
				        "columns": [
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.nm_buyer);
						},
						//"className": "center"
					},						
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.kpno);
						},
						//"className": "center"
					},
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.so_no);
						},
					},
					//loc_qty
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.qty_so);
						},
						"className": "right"
					},
					//is_lembar_gelar
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_cut_out);
						},
						"className": "right"
					},
				
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_qty_cut_out);
						},
						"className": "right"
						//"className": "center"
					},
					//is_sambung_duluan_bisa 10
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_cut_num);
						},
						"className": "right"
						//"className": "center"
					},
					//is_sisa_tidak_bisa 11
					{
	
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_qty_cut_num);
						},
						"className": "right"
					},
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_cut_qc);
						},
						"className": "right"
					},						
					{"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_qty_cut_qc);
						},
						"className": "right"
					},
				
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_sec_in);
						},
						"className": "right"
					},	
					
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_sec_in);
						},
						"className": "right"
					},	
					

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_sec_out);
						},
						"className": "right"
					},	

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_sec_out);
						},
						"className": "right"
					},	

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_sec_qc);
						},
						"className": "right"
					},	

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_sec_qc);
						},
						"className": "right"
					},	

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_dc_join);
						},
						"className": "right"
					},	

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_qty_dc_join);
						},
						"className": "right"
					},	

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_dc_set);
						},
						"className": "right"
					},	

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_qty_dc_set);
						},
						"className": "right"
					},	
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_mut_dc_set);
						},
						"className": "right"
					},		
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_mut_dc_set);
						},
						"className": "right"
					},						

					//sewing

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_sew_in);
						},
						"className": "right"
					},		


					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_qty_sew_in);
						},
						"className": "right"
					},

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_sew_out);
						},
						"className": "right"
					},

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_qty_sew_out);
						},
						"className": "right"
					},

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_sew_qc_in);
						},
						"className": "right"
					},

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_qty_sew_qc_in);
						},
						"className": "right"
					},

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_sew_qc_out);
						},
						"className": "right"
					},


					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_qty_sew_qc_out);
						},
						"className": "right"
					},
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_mut_sew_qc_out);
						},
						"className": "right"
					},
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_qty_mut_sew_qc_out);
						},
						"className": "right"
					},
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_steam_in);
						},
						"className": "right"
					},

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_steam_input);
						},
						"className": "right"
					},										
					

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_steam_out);
						},
						"className": "right"
					},

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_steam_output);
						},
						"className": "right"
					},

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_qc_final_in);
						},
						"className": "right"
					},

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_qc_final_input);
						},
						"className": "right"
					},

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_qc_final_out);
						},
						"className": "right"
					},

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_qc_final_output);
						},
						"className": "right"
					},	

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_qotiti);
						},
						"className": "right"
					},

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_qotiti);
						},
						"className": "right"
					},		
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_mut_qc_fin_out);
						},
						"className": "right"
					},

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_mut_qotiti);
						},
						"className": "right"
					},						

					//finishing
					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_packing);
						},
						"className": "right"
					},	

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_packing);
						},
						"className": "right"
					},	

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_mat_dec);
						},
						"className": "right"
					},	

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_met_det);
						},
						"className": "right"
					},	

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_qc_audit);
						},
						"className": "right"
					},	

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_qc_audit);
						},
						"className": "right"
					},	

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_qty_fg);
						},
						"className": "right"
					},	

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_fg);
						},
						"className": "right"
					},	

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.tot_shp);
						},
						"className": "right"
					},	

					{
						"data": null,
						"render": function (data) {
							return decodeURIComponent(data.balance_shp);
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
                        message: "Periode "+from+" Sampai "+to+" \n Buyer : "+buyer+" Status : "+status+"\n",
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
