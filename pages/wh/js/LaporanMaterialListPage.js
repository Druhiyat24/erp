$( document ).ready(function() {
	from = '';
	to ='';
		GenerateTable('X','X');
		$("#period_from").datepicker( {
format: "dd M yyyy",
        autoclose: true
	});   
	
/* 	$("#period_from").datepicker( {
		format: "mm/yyyy",
		viewMode: "months",
		minViewMode: "months",
		autoclose: true
	}); */
	
	$("#period_to").datepicker( {
format: "dd M yyyy",
        autoclose: true
	});		
});


function getListData(){
	from = $("#period_from").val();
	to = $("#period_to").val();
	if(!from){
		alert("Periode From Harus Diisi");
		return false;
	}
	if(!to){
		alert("Periode To Harus Diisi");
		return false;
	}else{
		$(".list").css("display","");
		//table.buttons()[0].inst.c.buttons[0].message = "From  "+from+" To "+to+"\n";
		GenerateTable(from,to);
		setTimeout(function(){
			//console.log(table);	
			//table.ajax.url("webservices/getListDataMaterialStatus.php?from="+from+"&to="+to).load();
		},200)
		$("#label_from").text(from);
		$("#label_to").text(to);		
	}

}


/* $(".dt-button buttons-excel .buttons-html5 btn-primary").click(function(){
	alert("123");
}) */
function defaultData(){
	console.log(sessionStorage);
	if((sessionStorage.idKontrakKerja)){
		idForm = '2';
		id = sessionStorage.idKontrakKerja;
		getDefaultData();
		//alert('123');
	}
}
function getDefaultData(){
	$('#myOverlay').css('display','block');	
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getMasterLaporanMaterial.php",
        data : { idws : $idws },     // multiple data sent using ajax
        success: function (response) {

        }
      });
}

function getMasterLaporanMaterialCount(item,type){
	event.preventDefault();

		$("#myLoading_2").css("display","block");
		console.log(item.id);
		$("#example1b").dataTable().fnDestroy();
		$("#bodyexamle234").empty();
		
	
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getMasterLaporanMaterial.php", // nama webservices nya 
        data : { idws : item, type : type },     
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				console.log(d);
				var td='';
				if(d.status == 'ok'){
					for (var i=0; i<d.records.length;i++) {
						td +="<tr>";
						td +="<td>";
						td +=i+1;
						td +="</td>";

						td +="<td>";
						td +=decodeURIComponent(d.records[i].pono);	
						td +="</td>";


						td +="<td>";
						td +=decodeURIComponent(d.records[i].item_d);	
						td +="</td>";

						td +="<td>";
						td +=decodeURIComponent(d.records[i].count_po);
						td +="</td>";

						td +="<td>";
						td +=decodeURIComponent(d.records[i].count_bpb);
						td +="</td>";
						
						

						
						td +="</tr>";
					}
					$('#bodyexamle234').append(td);
					pasangDataTable('example1b');
				}

        }

      });	
}

function getMasterLaporanMaterial(item,type){
	event.preventDefault();
		$("#myLoading").css("display","block");
		console.log(item.id);
		$("#example1a").dataTable().fnDestroy();
		$("#bodyexamle23").empty();
		
	
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getMasterLaporanMaterial.php", // nama webservices nya 
        data : { idws : item, type : type },     
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				console.log(d);
				var td='';
				if(d.status == 'ok'){
					for (var i=0; i<d.records.length;i++) {
						td +="<tr>";
						td +="<td>";
						td +=i+1;
						td +="</td>";

						td +="<td>";
						td +=decodeURIComponent(d.records[i].pono);	
						td +="</td>";

						td +="<td>";
						td +=decodeURIComponent(d.records[i].bpb);	
						td +="</td>";

						td +="<td>";
						td +=decodeURIComponent(d.records[i].item_d);	
						td +="</td>";

						td +="<td>";
						td +=decodeURIComponent(d.records[i].qty);
						td +="</td>";

						td +="<td>";
						td +=decodeURIComponent(d.records[i].qty_bpb);
						td +="</td>";
						
						
						td +="<td>";
						td +=decodeURIComponent(d.records[i].unit);
						td +="</td>";
						
						td +="</tr>";
					}
					$('#bodyexamle23').append(td);
					pasangDataTable('example1a');
				}

        }

      });	
}

async function pasangDataTable(id){
	await $('#'+id).DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });
	$("#myLoading").css("display","none");
	$("#myLoading_2").css("display","none");
}

			
x= 0;
function GenerateTable(from_,to_){
		if (typeof table === 'undefined') {
			

		} else {
			//table.destroy();

			if (x==1) {
				x = 0;
				//alert("click 1 x lagi!");
				//table.destroy();
			}
		}	
	$.fn.dataTable.ext.errMode = 'none';
	table = $('#laporan_jurnal').DataTable( {
      'processing': true,
      'serverSide': true,
	  "lengthMenu": [[10, 25, 50, 999999999], [10, 25, 50, "All"]],
		'serverMethod': 'post',
		
        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
        "ajax":  "webservices/getListDataMaterialStatus.php?from="+from_+"&to="+to_,
        "columns": [
		//kpno
        {
			"data":           "kpno",/* 
             "render":        function (data) {
							return decodeURIComponent(data.period);
                            } */
         } ,
		//deldate
        {
			"data":           "deldate",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,	

		//eta_fabric
        {
			"data":           "eta_fabric",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,
		 
		//rfpd_fabric
        {
			"data":           "rfpd_fabric",
         } ,			 

		//button_items_complete_fabric
        {
			"data":           null,
			/*"className" : "right", */
             "render":        function (data) {
							return decodeURIComponent(data.button_items_complete_fabric);
                            } 
         } ,		


		//button_qty_complete_fabric
        {
			"data":           null,
			/*"className" : "right",*/
             "render":        function (data) {
							return decodeURIComponent(data.button_qty_complete_fabric);
                            } 
         } ,


		//percent_fabric
        {
			"data":           "percent_fabric",
			"className" : "right",/*
             "render":        function (data) {
							return decodeURIComponent(data.nm_coa);
                            } */
         } ,
		 
		//eta_sewing
        {
			"data":           "eta_sewing",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,		 

		//rfpd_sewing
        {
			"data":           "rfpd_sewing",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,		 
		 
		 
		 
		//button_items_complete_sewing
        {
			"data":           null,
			/*"className" : "right",*/
             "render":        function (data) {
							return decodeURIComponent(data.button_items_complete_sewing);
                            } 
         } ,
 
 		//button_qty_complete_sewing
        {
			"data":           null,
			/*"className" : "right",*/
             "render":        function (data) {
							return decodeURIComponent(data.button_qty_complete_sewing);
                            } 
         } ,
 
 
 		//percent_sewing
        {
			"data":           "percent_sewing",
			"className" : "right",/*
             "render":        function (data) {
							return decodeURIComponent(data.debit);
                            } */
         } ,

		//eta_packing
        {
			"data":           "eta_packing",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,		 

		//rfpd_packing
        {
			"data":           "rfpd_packing",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,		 
		 
		 
		 
 		//button_items_complete_packing
        {
			"data":           null,
			/*"className" : "right",*/
             "render":        function (data) {
							return decodeURIComponent(data.button_items_complete_packing);
                            } 
         } ,

 		//button_qty_complete_packing
        {
			"data":           null,
			/*"className" : "right",*/
             "render":        function (data) {
							return decodeURIComponent(data.button_qty_complete_packing);
                            } 
         } ,		 
 		//percent_packing
        {
			"data":           "percent_packing",
			"className" : "right",/*
             "render":        function (data) {
							return decodeURIComponent(data.debit);
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
        fixedColumns:   {
            leftColumns: 2
        },
      dom: 'Bfrtip',
        buttons: [
{

              extend: 'excel', 
              text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export to Excel',
              className: 'btn-primary',
			  //title: 'Any title for file',
			          message: message(),
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
function message(){
	return "From  "+from+" To "+to+"\n";
}


function newexportaction(e, dt, button, config) {
	console.log(button);
	console.log(dt);
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