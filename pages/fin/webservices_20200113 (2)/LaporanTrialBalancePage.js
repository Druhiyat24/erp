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

	if(!from){
		alert("Periode From Harus Diisi");
		return false;
	}
	if(!to){
		alert("Periode To Harus Diisi");
	}


	CheckSaldoAwal(from).then(function($responnya) {
		console.log($responnya);
		if($responnya.records[0].status=='OK'){
			if($reload > 0){
				$('#trial_balance').DataTable().ajax.reload();
				
			}else{
				$(".list").css('display',''); //type
				$(".type").css('display','none');
				GenerateTable(from,to);
			}				
			
		}else{
			$(".list").css('display','');
			return false;
			
		}
	});

	

	
}	
	
	
function CheckSaldoAwal($from){
		return new Promise(function(resolve, reject) {
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/CheckLaporan.php",
        data: { code: '1', period: $from},     // multiple data sent using ajax
        success: function (response) {
            d = JSON.parse(response);
			if(d.status == 'ok'){
            if (d.message == '1') {
				resolve(d);
            }
            if (d.message == '2') {
                alert("Data Belum ada");
            }				
			}else{
				alert(d.message);
				
			}
        }
    });
	});

}	
	
function GenerateTable(my_from,my_to){
  table = $('#trial_balance').DataTable( {
        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
		"sAjaxSource":  "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
        "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            }, 
			{ "data": "id_coa" },
			{ "data": "nm_coa" },
            { "data": "beg_debit_total",className:"right" },
            { "data": "beg_credit_total",className:"right" },
            { "data": "mut_debit_total",className:"right" },
            { "data": "mut_credit_total",className:"right" },
			{ "data": "end_debit_total",className:"right" },
			{ "data": "end_credit_total",className:"right" }
        ],
        "order": [[1, 'asc']]
    } );
     
    // Add event listener for opening and closing details
    $('#trial_balance tbody').on('click', 'td.details-control', function () {
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
	
function format ( d ) {
	console.log(d.detail);
	my_td = "";
	for(var i=0;i<d.detail.length;i++){
		my_td += '<tr class="'+d.id_coa+'">';
		my_td += '<td>&nbsp;</td>';
		my_td += '<td>'+d.detail[i].id_coa+'</td>';
		my_td += '<td>'+d.detail[i].nm_coa+'</td>';
		my_td += '<td class="right">'+d.detail[i].beg_debit_total+'</td>';
		my_td += '<td class="right">'+d.detail[i].beg_credit_total+'</td>';
		my_td += '<td class="right">'+d.detail[i].mut_debit_total+'</td>';
		my_td += '<td class="right">'+d.detail[i].mut_credit_total+'</td>';
		my_td += '<td class="right">'+d.detail[i].end_debit_total+'</td>';
		my_td += '<td class="right">'+d.detail[i].end_credit_total+'</td>';
		my_td += '</tr>';		
	}
	
	//return false;
	return my_td;

}

async function pasangDataTable(){
	await $("#trial_balance").DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        },
    	dom: "<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +"<'row'<'col-sm-12'tr>>" 
	
    });	
	

}



