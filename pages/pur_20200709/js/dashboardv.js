$( document ).ready(function() {
	// alert('123');

	GenerateTable();
	// localStorage.clear();
	// sessionStorage.clear();

});
data = {
	iddatefrom : '',
	iddateto : '',
}
$reload = 0;
function back(){
	overlayon();
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


//For Export Buttons available inside jquery-datatable "server side processing" - End
table = "";





function GenerateTable(){

	
/* if(table !=""){
	table.colReorder.reset();
} */


//overlayon();
table = $('#dashboardv').DataTable( {
	'processing': true,
	'serverSide': true,
	"lengthMenu": [[25, 50, 999999999], [25, 50, "All"]],
	'serverMethod': 'post',

        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
        "ajax":  "webservices/list_dashboard.php",
        "columns": [
		//matclass
		{
			"data":           "kpno",
		},
		//vendor_cat
		{
			"data":           "styleno",
		} ,	
		{
			"data":           "itemdesc",
		} ,

		//price_USD
		{
			"data":           "qty_pr",
		} ,
		{
			"data":           "deldate_det",
		},
		{
			"data":           "min_deldate_det",
		}
		

		],
		"autoWidth": true,
		 "scrollCollapse": true,

        "order": [[1, 'asc']],
         scrollY:        "300px",
         scrollX:        true,
        scrollCollapse: true,

        "destroy": true,
		//order: [[ 12, "desc" ]],
        fixedColumns:   {
            leftColumns: 3
        },
		
		
        //dom: 'Bfrtip',
/*         buttons: [
        {

        	extend: 'excel', 
        	text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export to Excel',
        	className: 'btn-primary',
			  //title: 'Any title for file',
			  message: "List PO",
			  exportOptions:{
			  	search :'applied',
			  	order:'applied'
			  },
			  "action": newexportaction

			}       

			
			], 		 */	
			

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