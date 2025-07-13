$( document ).ready(function() {
localStorage.clear();
sessionStorage.clear();
 url =window.location.search;
 $split_nya = url.split("&type_invoice=");
 if($split_nya[1]){
	 if(!isNaN){
		 $myTypeInvoice = $split_nya[1];
	 }else{
		 $myTypeInvoice = 1;
	 }
 }else{
	$myTypeInvoice = 1;
	 
 }
 console.log($myTypeInvoice);

GenerateTable($myTypeInvoice);
});

function edit(id){
	window.location.href="?mod=InvoiceCommercialForm&id="+id;
	
	
}

function send(id,part){
	window.location.href="webservices/postPackInv.php?id="+id+"&part="+part;
}
function myForm(type,id){
	if(type=="ADD"){
		window.location.href="?mod=InvoiceCommercialForm";
	}

	
}

function cancel($no_bpb){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/SaveBpbCancel.php", 
        data : { code : '1', no_bpb:$no_bpb  },     // multiple data sent using ajax
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);			
			//console.log(response);
			if(d.status == 'no'){
				alert(d.message);
				return false;
			}else{
				if(d.message == '1'){
					alert("Data Berhasil Di Update!");
					location.reload();
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !");
					return false;
				}				
			}

        }
      });	
}
$reload = 0;
table = "";

function GenerateTable($myTypeInvoice_){
   if(table !=""){
    table.clear();
	table.destroy();
    //table.rows.add(newDataArray);
    //table.draw();	   
   }
  table = $('#MasterInvoiceActualTable').DataTable( {
        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
		//"sAjaxSource":  "webservices/getListDataInvoiceActual.php?type_invoice="+$myTypeInvoice_,
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
          'url':'webservices/getListDataBpbCancel.php?',
      },
			"aoColumns": [
			{data : 'bpbno_int'              }, 
			{data : 'bpbdate' },
			{data : 'pono'         },
			{data : 'podate'              },
			{data : 'kpno'                  },
             {
                "data":           null,
                "render": function (data) {
					console.log(data);
                    return decodeURIComponent(data.button);
                            }
            } 
			]
    } );
	console.log(table.columns);
    // Add event listener for opening and closing details
    $('#MasterInvoiceActualTable tbody').on('click', 'td.details-control', function () {
		var row = "";
        var tr = $(this).closest('tr');
        var row = table.row( tr );
		var my_trnya = format(row.data());
		var my_class = String(tr.attr('class'));
			my_class = my_class.split(" ");
			if(my_class[1]){
				tr.removeClass('shown');
				$("."+row.data().id_coa).remove().draw( false );
				
			}else{
 		index_tr = tr;
		index_tr.after(my_trnya); 
				 tr.addClass('shown');
			}

    } );	
	$reload++;
}	




