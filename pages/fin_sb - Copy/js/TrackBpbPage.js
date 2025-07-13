$( window ).on( "load", function() {
localStorage.clear();
sessionStorage.clear();

setTimeout(function(){
	GenerateTable();
},3000);
});

$reload = 0;
table = "";


$x_datatable = "0"
function GenerateTable() {


 table = $("#MasterInvoiceActualTable").DataTable({
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[10, 25, 50, 999999999], [10, 25, 50, "All"]],
        "serverMethod": "post",
        "ajax": "webservices/getListDataTrackBpb.php",
        "columns": [
            //1
             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.bpbno_int);
                            }
            }, 
			             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.bppbno_int_ret);
                            }
            
			
            }, 
			             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.bpbno_int_ri);
                            }
            }, 
             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.is___no_pembelian);
                            }
            }, 
             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.is___no_kb);
                            }
            }, 			
             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.is___v_listcode);
                            }
            },			
			
             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.is___no_pembayaran);
                            }
            }, 
             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.is___no_pembelian_ret);
                            }
            }, 
             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.is___no_kb_ret);
                            }
            }, 
             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.is___no_pembayaran_ret);
                            }
            }, 
             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.is___v_listcode_ret);
                            }
            }, 			
             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.is___no_pembelian_ri);
                            }
            }, 
             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.is___no_kb_ri);
                            }
            }, 
             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.is___v_listcode_ri);
                            }
            }, 
             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.is___no_pembayaran_ri);
                            }
            }
			
			
        ],
        "autoWidth": true,
        "scrollCollapse": true,
        "order": [[1, 'asc']],
        scrollY: "300px",
        scrollX: true,
        scrollCollapse: true,
        "destroy": true,
        order: [[6, "desc"]],
        fixedColumns: {
            leftColumns: 3
        },    "drawCallback": function( settings ) {
/* 				table.settings()[0].jqXHR.abort();
				return false; */
    },


    });

	
	
	
   
}


