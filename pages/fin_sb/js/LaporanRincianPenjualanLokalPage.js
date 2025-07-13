$( document ).ready(function() {
	localStorage.clear();
	sessionStorage.clear();
	
		$("#period_from").datepicker( {
format: "dd M yyyy",
        autoclose: true
	});   
	   
        $("#period_to").datepicker( {
format: "dd M yyyy",
        autoclose: true
    });   
    
/* 	$("#period_from").datepicker( {
		format: "mm/yyyy",
		viewMode: "months",
		minViewMode: "months",
		autoclose: true
	}); */
	
	// $("#period_to").datepicker( {
	// 	format: "mm/yyyy",
	// 	viewMode: "months",
	// 	minViewMode: "months",
	// 	autoclose: true
	// });		
});
data = {
	iddatefrom : '',
	iddateto : '',
	tipejurnal : '',
	stts : ''
}
$reload = 0;
function back(){
	overlayon();
	location.reload(true);
}
function getListData(){
	from = $("#period_from").val();
	to = $("#period_to").val();
	if(!from){
		alert("Periode From Harus Diisi");
		return false;
	}	if(!to){
		alert("Periode To Harus Diisi");
		return false;
	}
	$(".list").css("display","");

	GenerateTable(from,to);

	$("#label_from").text(from);
	$("#label_to").text(to);
}

function overlayon(){

	$("#myOverlay").css("display","block");
	
}
function overlayoff(){
	$("#myOverlay").css("display","none");
}

function check_journal(val){
	if(val == '1' || val == '2' || val == '17' ){
		$("#txtstatus").val('2').trigger("change");
	}else{
		$("#txtstatus").val('').trigger("change");
		
	}
	
	
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

function GenerateTable(from_,to_){
	table = $('#laporan_jurnal').DataTable( {
      'processing': true,
      'serverSide': true,
	  "lengthMenu": [[10, 25, 50, 999999999], [10, 25, 50, "All"]],
		'serverMethod': 'post',
		
        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
        "ajax":  "webservices/getDataLaporanRincianPenjualanLokal.php?from="+from_+"&to="+to_,
        "columns": [

		//coa
        {
			"data":           "coa",
        } ,	
        //kode_sup
        {
            "data":           "kode_sup",
        } , 
        //nama_sup
        {
			"data":           "nama_sup",
         } ,
		 
		//alias
        {
			"data":           "alias",
         } ,			 

		//nomor_so
        {
			"data":           "nomor_so",
         } ,		


		//nomor_po
        {
			"data":           "nomor_po",
         } ,


		//nomor_ws
        {
			"data":           "nomor_ws",
         } ,
		 
		//id_order
        {
			"data":           "id_order",
         } ,
 
 		//top
        {
			"data":           "top",
         } ,
		 
 		//periode
        {
			"data":           "periode",
         } ,		 
	
 		//tgl_sj
        {
			"data":           "tgl_sj",
         } ,			 
	
 		//nomor_sj
        {
			"data":           "nomor_sj",
         } ,		 

 		//tgl_inv
        {
			"data":           "tgl_inv",
         } ,			 
	
 		//nomor_inv
        {
			"data":           "nomor_inv",
         } ,			 

 		//tgl_fp
        {
			"data":           "tgl_fp",
         } ,			 
	
 		//nomor_fp
        {
			"data":           "nomor_fp",
         } ,		 
		
 		//nomor_bc
        {
			"data":           "nomor_bc",
         } ,

 		//kode_barang
        {
			"data":           "kode_barang",
         } ,		 
		
 		//warna_barang
        {
			"data":           "warna_barang",
         } , 
		
 		//nama_style
        {
			"data":           "nama_style",
         } ,	

 		//satuan_barang
        {
			"data":           "satuan_barang",
         } ,	

 		//qty_barang
        {
			"data":           "qty_barang",
         } ,

 		//harga_barang
        {
			"data":           "harga_barang",
         } ,		 
		
 		//ppn
        {
			"data":           "ppn",
         } ,		 
		
		
 		//total_rupiah
        {
			"data":           "total_rupiah",
         } ,		 
				
 		//keterangan
        {
			"data":           "keterangan",
         } ,		 
		

        ],
		"autoWidth": true,
		"scrollCollapse": true,

        "order": [[1, 'asc']],
        scrollY:        "500px",
        scrollX:        true,
        scrollCollapse: true,
		
        "destroy": true,
		order: [[ 1, "desc" ]],
        fixedColumns:   {
            leftColumns: 4
        },
      dom: 'Bfrtip',
        buttons: [
{

              extend: 'excel', 
              text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export to Excel',
              className: 'btn-primary',
			  //title: 'Any title for file',
			         message: "Periode "+from_+" Sampai "+to_+" \n",
					  exportOptions:{
						  search :'applied',
						  order:'applied'
					  },

					   "action": newexportaction,
					   
					   
					   

                      }       
		
			
        ], 
		header: true,
		    dom:
			"<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
			"<'row'<'col-sm-12'tr>>"+
    "<'row'<'col-sm-5'i><'col-sm-7'p>>",	
    } );
}
