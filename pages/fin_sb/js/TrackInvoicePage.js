$( document ).ready(function() {
localStorage.clear();
sessionStorage.clear();


GenerateTable();
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

function print(id,typeinvoice){
	if(typeinvoice == '1'){
		window.location.href="PdfInvoiceLocal.php?id="+id;
	}else if(typeinvoice == '2'){
		window.location.href="PdfInvoiceCommercial.php?id="+id;
	}
}
$reload = 0;
table = "";

function GenerateTable(){
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
          'url':'webservices/getListDataTrackInvoice.php',
      },
			"aoColumns": [
			{data : 'invoice'              }, 
             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.penjualan);
                            }
            }, 
             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.rekapar);
                            }
            }, 
             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.penerimaan);
                            }
            }, 
             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.allokasiar);
                            }
            }, 
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


/*
	function getListData(){
		td = "";
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getListDataInvoiceActual.php", 
        data : { code : '1',   },     // multiple data sent using ajax
        success: function (response) {
			console.log(response);
			data = response;
				d = JSON.parse(data);
				//d = response;
				td  = '';
				renders = '';
				var length = d.records.length;
				console.log(d.records.length);
				console.log(d.records);
				if(d.message == '1'){
					for(var e=0;e < length;e++){
						td += "<tr>"
							td += "<td>"
							td += decodeURIComponent(d.records[e].no_surat);
							td += "</td>"

							td += "<td>"
							td += decodeURIComponent(d.records[e].ws);
							td += "</td>"	

							td += "<td>"
							td += decodeURIComponent(d.records[e].style);
							td += "</td>"	
	
							td += "<td>"
							td += decodeURIComponent(d.records[e].date);
							td += "</td>"	

							td += "<td>"
							td += decodeURIComponent(d.records[e].po);
							td += "</td>"		

							td += "<td>"//fa fa-file-pdf-o
								if(parseFloat(decodeURIComponent(d.records[e].post)) != 2){
									td += "<a href='#' class='btn btn-primary' onclick='edit("+'"'+ decodeURIComponent(d.records[e].id) +'"'+")' ><i class='fa fa-pencil'> </i></a>";
									td += "<a href='#' class='btn btn-info' onclick='send("+'"'+ decodeURIComponent(d.records[e].id_invoiceheader) +'",'+'"INV"'+")' ><i class='fa fa-send'> </i></a>";			
									td +=" <a href='#' class='btn btn-warning' onclick='print("+'"'+ decodeURIComponent(d.records[e].id) +'",'+'"'+ decodeURIComponent(d.records[e].typeinvoice) +'"'+")' ><i class='fa fa-file-pdf-o'> </i></a>"									
									
								}else{
									td +=" <a href='#' class='btn btn-primary' onclick='print("+'"'+ decodeURIComponent(d.records[e].id) +'",'+'"'+ decodeURIComponent(d.records[e].typeinvoice) +'"'+")' ><i class='fa fa-file-pdf-o'> </i></a>"
									
								}
							
							//td += "<a href='#' class='btn btn-primary' onclick='edit("+'"'+ decodeURIComponent(d.records[e].id) +'"'+")' ><i class='fa fa-pencil'> </i></a> <a href='#' class='btn btn-primary' onclick='print("+'"'+ decodeURIComponent(d.records[e].id_invoiceheader) +'"'+")' ><i class='fa fa-file-pdf-o'> </i></a>  "
							
							
							td += "</td>"
							
						td += "</tr>"
						
					}
						$("#render").append(td);
						getDataTable();
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	
	
}
*/


