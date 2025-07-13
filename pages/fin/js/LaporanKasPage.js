$( document ).ready(function() {
	localStorage.clear();
	sessionStorage.clear();
	getAkunCashBank("CASH");
	
		$("#period_from").datepicker( {
      format: "dd MM yyyy",

      autoclose: true
	});   
	
/* 	$("#period_from").datepicker( {
		format: "mm/yyyy",
		viewMode: "months",
		minViewMode: "months",
		autoclose: true
	}); */
	
	$("#period_to").datepicker( {
      format: "dd MM yyyy",

      autoclose: true
	});		
});
data = {
	iddatefrom : '',
	iddateto : '',
	tipejurnal : '',
	stts : ''
}
$reload = 0;

	function getAkunCashBank(myCoas){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getAkunCashBank.php", 
        data : { code : '1', type:"Laporan", typeidcoa: myCoas },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				//d = response;
				option  = '';
				renders = '';
			//	console.log(d.records.length);
				if(d.message == '1'){
					//	$("#render").append(data[1]);
					//	console.log(d.records);
						for(i=0;i<d.records.length;i++){
							option += "<option value="+decodeURIComponent(d.records[i][0].id)+">"+decodeURIComponent(d.records[i][0].id) +" | "+decodeURIComponent(d.records[i][0].nama)+"</option>";
						}//department
						$("#idcoa").append(option);

				setTimeout(function(){ 				

				}, 3000);						
						
						
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	
	
}	


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
	}
	if(!to){
		alert("Periode To Harus Diisi");
	}	
	$(".list").css("display","");
	//alert(CashBankId);
	GenerateTable(from,to,CashBankId);
	dumptitle=$( "#idcoa option:selected" ).text();
	split = dumptitle.split("|");	
	
	
	
	$("#label_from").text(from);
	$("#label_to").text(to);
	$("#bukukas").text(split[1]);
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
CashBankId = '';
function getidcashbank(idcoa){
	CashBankId = idcoa;
	//.alert(CashBankId);
}
function GenerateTable(from_,to_,CashBankId_){

	table = $('#laporan_jurnal').DataTable( {
      'processing': true,
      'serverSide': true,
	  "lengthMenu": [[999999999], ["All"]],
		'serverMethod': 'post',
		
        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
        "ajax":  "webservices/getDataLaporanKas.php?from="+from_+"&to="+to_+"&akun="+CashBankId_,
        "columns": [
		//date_journal
        {
			"data":           "date_journal",/* 
             "render":        function (data) {
							return decodeURIComponent(data.period);
                            } */
         } ,
		//id_journal
        {
			"data":           "id_journal",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,	
		 
		//v_novoucher
        {
			"data":           "v_novoucher",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,		 
		 

		//id_coa
        {
			"data":           "id_coa",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,
		 
		//nm_coa
        {
			"data":           "nm_coa",
         } ,			 

		//id_coa_lawan
        {
			"data":           "id_coa_lawan",/* 
             "render":        function (data) {
							return decodeURIComponent(data.date_journal);
                            } */
         } ,		


		//nm_coa_lawan
        {
			"data":           "nm_coa_lawan",/* 
             "render":        function (data) {
							return decodeURIComponent(data.id_coa);
                            } */
         } ,


		//v_fakturpajak
        {
			"data":           "v_fakturpajak",/* 
             "render":        function (data) {
							return decodeURIComponent(data.nm_coa);
                            } */
         } ,
		 
		//reff_doc
        {
			"data":           "reff_doc",/* 
             "render":        function (data) {
							return decodeURIComponent(data.nm_coa);
                            } */
         } ,		 

		 
		//debit_idr
        {
			"data":           "debit_idr",
			"className" : "right",/*
             "render":        function (data) {
							return decodeURIComponent(data.nm_coa);
                            } */
         } ,	

		 
		//credit_idr
        {
			"data":           "credit_idr",
			"className" : "right",/*
             "render":        function (data) {
							return decodeURIComponent(data.nm_coa); 
                            } */
         } ,	

		//saldo_berjalan
        {
			"data":           "saldo_berjalan",
			"className" : "right",/*
             "render":        function (data) {
							return decodeURIComponent(data.nm_coa); saldo_berjalan
                            } */
         } ,	


        ],
		"autoWidth": true,
		"scrollCollapse": true,
        scrollY:        "500px",
        scrollX:        true,
        scrollCollapse: true,
		
        "destroy": true,
		//order: [[ 1, "asc" ]],
		"ordering": false,
        fixedColumns:   {
            leftColumns: 7
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
