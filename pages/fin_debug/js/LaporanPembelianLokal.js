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
}
$reload = 0;
function back(){
	location.reload(true);
}
function getListData(){
	from = $("#period_from").val();
	to = $("#period_to").val();
	console.log(from);
	console.log(to);
	if(!from){
		alert("Periode From Harus Diisi");
		return false;
	}
	if(!to){
		alert("Periode To Harus Diisi");
	}
	$(".list").css("display","");
	$('#lapemlokal').DataTable().destroy();
	//$('#lapemlokal').empty();
	GenerateTable(from,to);
}
function GenerateTable(from,to){
	table = $('#lapemlokal').DataTable( {
        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
        "ajax":  "webservices/getDataLapemlok.php?from="+from+"&to="+to,
        "columns": [
      //  1
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.goods_code);
                            }
            },
      //  {2
		
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.itemdesc);
                            }
            },		
        //3
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.matclass);
                            }
            },			
		
		
      //  4
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.kpno);
                            }
            },					
        //{5
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.Styleno);
                            }
            },			
      //  6
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.supplier_code);
                            }
            },			
    //    7
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.Supplier);
                            }
            },	
//8			
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.byr_code);
                            }
            },			
			
        
       //9
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.buyer);
                            }
            },		
       // 10
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.bcno);
                            }
            },		
        //11
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.bcdate);
                            }
            },		
      //  12
            {
                "data":           null,
                "render": function (data) {
                    return '-';
                            }
            },		
       // 13
            {
                "data":           null,
                "render": function (data) {
                    //return decodeURIComponent(data.tgl_fp);
					 return decodeURIComponent(data.bpbno_int);
                            }
            },		
       // 14
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.bpbdate);
                            }
            },		
      //  15
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.pono);
                            }
            },		
      //  16
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.podate);
                            }
            },		
      //  17
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.qty_po_item);
                            }
            },		
      //  18
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.qty_bpb);
                            }
            },		
      // 19
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.qty_outstanding);
                            }
            },		
        //20
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.jo_no);
                            }
            },		
      //  21
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.jo_date);
                            }
            },		
     //   22
	            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.username);
                            }
            },	
      //23
	            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.so_no);
                            }
            },	
      //  24
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.invno);
                            }
            },		
        //25
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.bpbdate);
                            }
            },		
        //26
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.no_fp);
                            }
            },		
       //27
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.tgl_fp);
                            }
            },		
     //  28
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.unit);
                            }
            },		
       // 29
            {
                "data":           null,
                "render": function (data) {
                   return decodeURIComponent(data.tgl_fp);
                            }
            },
            {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.unit);
                            }
            },		
       // 30
            {
                "data":           null,
                "render": function (data) {
                   return decodeURIComponent(data.qty);
                            }
            },
			 // 31
            {
                "data":           null,
                "render": function (data) {
                     return decodeURIComponent(data.price);
                            }
            },
			 // 32
            {
                "data":           null,
                "render": function (data) {
                       return '-';
                            }
            },	
 // 33	
{
                "data":           null,
                "render": function (data) {
                    return '-';
                            }
            },
			 // 34
            {
                "data":           null,
                "render": function (data) {
					return decodeURIComponent(data.dpp);
                 
                            }
            },	
			 // 35
            {
                "data":           null,
                "render": function (data) {
                       return '-';
                            }
            },	
			 // 36
 {
                "data":           null,
                "render": function (data) {
                    return '-';
                            }
            },
			 // 37
            {
                "data":           null,
                "render": function (data) {
					 return decodeURIComponent(data.ppn);
                
                            }
            },	
			 // 38
            {
                "data":           null,
                "render": function (data) {
                   return '-';
                            }
            },	
			{
			//39           {
                "data":           null,
                "render": function (data) {
                    return '-';
                            }
            },
			 // 40
            {
                "data":           null,
                "render": function (data) {
					 return decodeURIComponent(data.after_ppn);
                    
                            }
            }				
       //30
		
        //32
/*             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.byr_code);
                            }
            }, */		
        ],
        "order": [[1, 'asc']],
		        fixedHeader: {
            header: true,
            footer: true
        }
    } );

}	