$( document ).ready(function() {
localStorage.clear();
sessionStorage.clear();
	/*  var table = $('#MasterInvoiceActualTable').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,	
        paging: true,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
		
    });	
	*/
getListData();
});
function edit(id){
	window.location.href="?mod=EstimatePackingListForm&id="+id;
	
	
}

function myForm(type,id){
	if(type=="ADD"){
		window.location.href="?mod=EstimatePackingListForm";
		
	}

	
}

function print(id){
	window.location.href="PdfEstimatePackingList.php?id="+id;
}

async function getDataTable(){
	  var table = await $('#MasterInvoiceActualTable').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,	
        paging: true,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });		
}

	function getListData(){
		td = "";
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getListDataEstimatePackingList.php", 
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
				if(d.message == '1'){
					for(var e=0;e < length;e++){
						td += "<tr>"
							td += "<td>"
							td += decodeURIComponent(d.records[e].no_surat);
							td += "</td>"
	
							td += "<td>"
							td += decodeURIComponent(d.records[e].date);
							td += "</td>"	

							td += "<td>"
							td += decodeURIComponent(d.records[e].po);
							td += "</td>"		

							td += "<td>"//fa fa-file-pdf-o
							td += "<a href='#' class='btn btn-primary' onclick='edit("+'"'+ decodeURIComponent(d.records[e].id) +'"'+")' ><i class='fa fa-pencil'> </i></a> <a href='#' class='btn btn-primary' onclick='print("+'"'+ decodeURIComponent(d.records[e].id) +'"'+")' ><i class='fa fa-file-pdf-o'> </i></a>  "
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


