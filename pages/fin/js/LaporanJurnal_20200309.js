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
	location.reload(true);
}
function getListData(){
	from = $("#period_from").val();
	to = $("#period_to").val();
	type_journal = $("#txttipe_jurnal").val();
	status = $("#txtstatus").val();
	console.log(from);
	console.log(to);
	console.log(type_journal);
	console.log(status);
	if(type_journal==''){
		alert("Tipe Jurnal Harus Diisi");
		return false;
	} 
	if(status==''){
		alert("Status Harus Diisi");
		return false;
	} 
	if(!from){
		alert("Periode From Harus Diisi");
		return false;
	}
	if(!to){
		alert("Periode To Harus Diisi");
	}
	$(".list").css("display","");

	GenerateTable(from,to);

}
function GenerateTable(from,to){

	table = $('#laporan_jurnal').DataTable( {
        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
        "ajax":  "webservices/getDataLaporanJurnal.php?from="+from+"&to="+to+"&type_journal="+type_journal+"&fg_post="+status,
        "columns": [
        { "data": "period" },
        { "data": "id_journal" },
        { "data": "date_journal" },
        { "data": "id_coa" },
        { "data": "nm_coa" },
        { "data": "debit" },
        { "data": "credit" },
        { "data": "remark" },
        { "data": "id_costcenter" },
        { "data": "nm_costcenter" },
        ],
        "bPaginate": false,
        "paging": false,
        "destroy": true,
        "processing": true,
        "serverSide": true,
		"autoWidth": true,
		"scrollCollapse": true,
        "fixedHeader": {
            header: true,
            footer: true
        },
        "order": [[1, 'asc']],
    } );
	table.destroy().draw();
}

function fnExcelReport()
{
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

	
	
	
        //sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  
	}
    return (sa);
}