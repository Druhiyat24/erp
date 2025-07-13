$( document ).ready(function() {
	localStorage.clear();
	sessionStorage.clear();
	$("#period_from").datepicker( {
		format: "mm/yyyy",
		viewMode: "months",
		minViewMode: "months",
		autoclose: true
	});
	
	$("#period_to").datepicker( {
		format: "mm/yyyy",
		viewMode: "months",
		minViewMode: "months",
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
function back(){
	overlayon();
	location.reload(true);
}
function getListData(){
	from = $("#period_from").val();
	to = $("#period_to").val();
	type_journal = $("#txttipe_jurnal").val();
	if(type_journal ==""){
		type__ = "ALL";
	}else{
		type__ = $("#txttipe_jurnal option:selected").text();
	}
	status = $("#txtstatus").val();
	console.log(type__);
	console.log(to);
	console.log(type_journal);
	console.log(status);
/*  	if(type_journal==''){
		alert("Tipe Jurnal Harus Diisi");
		return false;
	}  */ 
/* 	if(status==''){
		alert("Status Harus Diisi");
		return false;
	}  */
	if(!from){
		alert("Periode From Harus Diisi");
		return false;
	}
	if(!to){
		alert("Periode To Harus Diisi");
	}
	$(".list").css("display","");

	GenerateTable(from,to);

	$("#label_from").text(from);
	$("#label_to").text(to);
	$("#label_type_journal").text(type__);
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




function GenerateTable(from_,to_){
	
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
	
//overlayon();
	table = $('#laporan_jurnal').DataTable( {
      'processing': true,
      'serverSide': true,
	  "lengthMenu": [[10, 25, 50, 999999999], [10, 25, 50, "All"]],
		'serverMethod': 'post',
		
        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
        "ajax":  "webservices/getDataLaporanJurnal.php?from="+from_+"&to="+to_+"&type_journal="+type_journal+"&fg_post="+status,
        "columns": [
		//period
        {
			"data":           "period",/* 
             "render":        function (data) {
							return decodeURIComponent(data.period);
                            } */
         } ,
		//status
        {
			"data":           "status",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,		 
		//id journal
        {
			"data":           "id_journal",
         } ,			 

		//date journal
        {
			"data":           "date_journal",/* 
             "render":        function (data) {
							return decodeURIComponent(data.date_journal);
                            } */
         } ,		


		//id coa
        {
			"data":           "id_coa",/* 
             "render":        function (data) {
							return decodeURIComponent(data.id_coa);
                            } */
         } ,


		//nama coa
        {
			"data":           "nm_coa",/* 
             "render":        function (data) {
							return decodeURIComponent(data.nm_coa);
                            } */
         } ,
		 
		//curr
        {
			"data":           "curr",/* 
             "render":        function (data) {
							return decodeURIComponent(data.curr);
                            } */
         } ,
 
 		//debit
        {
			"data":           "debit",/* 
             "render":        function (data) {
							return decodeURIComponent(data.debit);
                            } */
         } ,

		//curr
/*         {
			"data":           "curr",/* 
             "render":        function (data) {
							return decodeURIComponent(data.curr);
                            } 
         } ,	 */

 		//credit
        {
			"data":           "credit",/* 
             "render":        function (data) {
							return decodeURIComponent(data.credit);
                            } */
         } ,		 

 		//debit_eqv
        {
			"data":           "debit_eqv",/* 
             "render":        function (data) {
							return decodeURIComponent(data.debit_eqv);
                            } */
         } ,
		 
 		//credit_eqv
        {
			"data":           "credit_eqv",/* 
             "render":        function (data) {
							return decodeURIComponent(data.credit_eqv);
                            } */
         } ,		 
		 
		//{ "data": "credit_eqv",className:"right" },
	
 		//description
        {
			"data":           "description",/* 
             "render":        function (data) {
							return decodeURIComponent(data.description);
                            } */
         } ,
			
 		//id_costcenter
        {
			"data":           "id_costcenter",/* 
             "render":        function (data) {
							return decodeURIComponent(data.id_costcenter);
                            } */
         } ,	


 		//nm_costcenter
        {
			"data":           "nm_costcenter",/* 
             "render":        function (data) {
							return decodeURIComponent(data.nm_costcenter);
                            } */
         } ,	
        ],
		"autoWidth": true,
		"scrollCollapse": true,

        "order": [[1, 'asc']],
        scrollY:        "500px",
        scrollX:        true,
        scrollCollapse: true,
		
        "destroy": true,
		order: [[ 2, "desc" ]],
        fixedColumns:   {
            leftColumns: 3
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
		    dom:
			"<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
			"<'row'<'col-sm-12'tr>>"+
    "<'row'<'col-sm-5'i><'col-sm-7'p>>",	
    } );
}

function fnExcelReport()
{
	//table.destroy().draw();
/* 	$('#laporan_jurnal').DataTable( {
			
		 "bPaginate": false,
	}); */
	table.destroy().draw();
	overlayon();
	setTimeout(function(){ 		
    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j=0;
    tab = document.getElementById('laporan_jurnal'); // id of table

    for(j = 0 ; j < tab.rows.length ; j++) 
    {     
        tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
        //tab_text=tab_text+"</tr>";
    }

    tab_text=tab_text+"</table>";
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE "); 
    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
        txtArea1.document.open("txt/html","replace");
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus(); 
        sa=txtArea1.document.execCommand("SaveAs",true,"Lap_Journal.xls");
    }  
    else  {               //other browser not tested on IE 11
	var uri = 'data:application/vnd.ms-excel,' + encodeURIComponent(tab_text);
		var downloadLink = document.createElement("a");
		downloadLink.href = uri;
		downloadLink.download = "Lap_Journal.xls";
		
		document.body.appendChild(downloadLink);
		downloadLink.click();
		document.body.removeChild(downloadLink);

	
	
	overlayoff();
	GenerateTable(from,to);
	overlayoff();
        //sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  
	}
    return (sa);
	}, 3000);	
	

}