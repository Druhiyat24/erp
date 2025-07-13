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

function validationDate(){
	var fr_ = $("#period_from").val();
	var to_ = $("#period_to").val();
	if(!fr_ || fr_ == ''){
		alert("From Date Salah/Belum diisi");
		return false;
	}	if(!to_ || to_ == ''){
		alert("To Date Salah/Belum diisi");
		return false;
	}
	
		
	
		fr_split = fr_.split("/");
		to_split = to_.split("/");
		var $years_from = parseFloat(fr_split[1]);
		var $years_to   = parseFloat(to_split[1]);
		var $moon_from  = parseFloat(fr_split[0]);
		var $moon_to    = parseFloat(to_split[0]);
		var $valid_moon = $moon_to - $moon_from;
		console.log("yEARS to:"+$years_to);
		if($valid_moon < 0){
			if($years_from > $years_to){
				alert("Input Tanggal Salah!");
				return false;
			}else{
				console.log("OK");
				return true;
			}
		}else{
			console.log("OK");
			return true;
		}
	
}


function GenerateFieldTable(){
	return new Promise(function(resolve, reject) {
	if(validationDate()){
		var $years_from   = parseFloat(fr_split[1]);
		var $years_to     = parseFloat(to_split[1]);
		var $moon_from    = parseFloat(fr_split[0]);
		var $moon_to      = parseFloat(to_split[0]);  
		var $my_years     = $years_to - $years_from;
		var $my_moon      = $moon_to - $moon_from;
		var td            = "";
		var jumlah_th     = 0;
		var current_period= $moon_from;
		var current_years = $years_from;
		var param_nya = 13
		if($my_years == "0"){
			
			if($my_moon == '0'){
				td += "<th>"+current_period+"/"+$years_from+"</th>";
				jumlah_th++;
				
			}else{
				for(var i=0;i<$my_moon;i++){
					td += "<th>"+current_period+"/"+$years_from+"</th>";
					current_period++;
					jumlah_th++;
				}				
			}

		}else{
			for (var i=0;i <= $my_years;i++ ){
				//console.log("i_nya:"+$my_years);
					for(var j=current_period;j<param_nya;j++){
						td += "<th>"+current_period+"/"+current_years+"</th>";
						if(current_period == "12"){
							current_period = 1;
							current_years
						}						
						current_period++;
						jumlah_th++;
					}				
				current_period = 1;
				current_years++;
				if(current_years == $years_to ){
					param_nya = $moon_to+1;
				}
			}
		}
		arr_ = [];
		arr_.push(td);
		arr_.push(jumlah_th)
		resolve(arr_);
	}
			
		
	});
	
} 

function generateBodyTable(arr_){
	return new Promise(function(resolve, reject) {
	var $myBody = "";
$myBody += "<table id='neraca' class='table table-condensed table-bordered' style='width:100%'>";
$myBody += "       <thead>                                                                     ";
$myBody += "       <tr>                                                                        ";
$myBody += "           <th rowspan='2'>&nbsp;</th>                                             ";
$myBody += "           <th rowspan='2' align='center'>DESCRIPTION</th>                         ";
$myBody += "           <th colspan='"+arr_[1]+"'>PERIOD</th>                    ";
$myBody += "       </tr>                                                                       ";
$myBody += "       <tr>                                                                        ";
$myBody += 			arr_[0];//RENDER TH DYNAMIC    												;   
$myBody += "       </tr>                                                                       ";
$myBody += "       </thead>                                                                    ";
$myBody += "   </table>	                                                                       ";		

console.log($myBody);
resolve($myBody);

	});

	
	
	
	
	
}


function getListData(){
	GenerateFieldTable().then(function(arr_) {
		console.log(arr_);
		//return false;
		generateBodyTable(arr_).then(function(body_table) {
			$("#body_neracass").append(body_table);
			console.log(body_table);
			//return false;
			 
			from = $("#period_from").val();
			to = $("#period_to").val();
			CheckSaldoAwal(from).then(function($responnya) {
				if($responnya.records[0].status=='OK'){
					$(".list").css('display',''); //type
					$(".type").css('display','none');
					GenerateTable(from,to,arr_[1]);
					
				}else{
					pasangDataTable();
					return false;
					
				}			
			});
			
		});
		
		
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
	
function GenerateTable(my_from,my_to,$jumlah_th){
	console.log($jumlah_th);
	var my_triger = $jumlah_th - 1;
	var op = {};
	var json__ = "";
var columns = [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            }, 
			{
				"data" : "nama_category"
			},
			

];	
			var n = 1
			for(var m=0;m<$jumlah_th;m++){
				
				
				if(m == 0 ){
				op =	{ data: "total_neraca_first",className:"right" };
				columns.push(op);
					
				}else{
				op =	{ data: "total_neraca_"+n+"",className:"right" };
				columns.push(op);	
				n++;				
				}
			}	
	
//	op = JSON.parse(op);

console.log(columns);
	//return false;
	
  table = $('#neraca').DataTable( {
        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
		"sAjaxSource":  "webservices/getDataLaporanNeraca.php?from="+from+"&to="+to,
		"columns" : columns,
        //"order": [[1, 'asc']]
    } );
     
    // Add event listener for opening and closing details
    $('#neraca tbody').on('click', 'td.details-control', function () {
		var row = "";
        var tr = $(this).closest('tr');
        var row = table.row( tr );
		var my_trnya = format(row.data(),$jumlah_th);
		var my_class = String(tr.attr('class'));
			my_class = my_class.split(" ");
			if(my_class[1]){
				tr.removeClass('shown');
				$("."+row.data().map_category).remove().draw( false );
				
			}else{
 		index_tr = tr;
		index_tr.after(my_trnya); 
				 tr.addClass('shown');
			}

    } );	
	
   /* $('#neraca tbody').on('click', 'td.details-control_sub', function () {
		var row = "";
        var tr = $(this).closest('tr');   
        var row = table.row( tr );
		console.log(tr);
		var my_trnya = format_sub(row.data(),$jumlah_th);
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
	*/
	//$reload++;
}	
	
function format ( d,$j_th ) {
	console.log(d.detail);
	my_td = "";
	for(var i=0;i<d.detail.length;i++){
		var class_nya = d.map_category;
		var sp_coa = d.detail[i].id_coa.split("-");
		console.log(class_nya);
		my_td += '<tr class="'+class_nya+'">';
	my_td += "<td class='details-control_sub' data-group_coa='"+sp_coa[0]+"' data-mappingnya='"+d.map_category+"' id='"+sp_coa[0]+"' data-j_detail='"+$j_th+"' data-arr='"+JSON.stringify(d.detail[i])+"' onclick='format_sub(this)'></td>";
		my_td += '<td>'+d.detail[i].id_coa+'</td>';
			var n = 1;
			for(var m=0;m<$j_th;m++){
				if(m == 0 ){
				my_td += '<td style="text-align:right">'+d.detail[i].total_neraca_first+'</td>';
				}else{
				my_td += '<td style="text-align:right">0.00</td>';
				n++;				
				}
			}	
		my_td += '</tr>';		
	}
	//return false;
	return my_td;

}


function generate_detail_coa(array_nya,$j_th,m_category,gro_coa){
	console.log(array_nya);
	my_td = '';
	for(var i=0;i<array_nya.length;i++){
		var class_nya = array_nya[i].id_coa.split("-");
		var n = 1;
		console.log(class_nya[0]);
		my_td += '<tr class="'+m_category+" "+gro_coa+'">';
	my_td += "<td >&nbsp;</td>";
		my_td += '<td>'+array_nya[i].id_coa+'</td>';
			
			for(var m=0;m<$j_th;m++){
				console.log(m);
				if(m == 0 ){
				my_td += '<td style="text-align:right">'+array_nya[i].total_neraca_first+'</td>';
				}else{
				my_td += '<td style="text-align:right">0.00</td>';
				n++;				
				}
			}	
		my_td += '</tr>';		
	}
	//return false;
	return my_td;	
	
}

function format_sub ( that ) {
	console.log($('#'+that.id).data('arr'));
	var str = $('#'+that.id).data('arr');
	var obj =str;
	console.log(obj);
	var detail_coa = obj.detail_coa;
	var j_th = $('#'+that.id).data('j_detail');
	var tr_2 =  $(that).parent();
	var map_cate = $('#'+that.id).data('mappingnya');
	var gr_coa_nya = $('#'+that.id).data('group_coa');
	 my_class_2 = String(tr_2.attr('class'));
	my_class_2 = my_class_2.split(" ");	
			if(my_class_2[1]){
				tr_2.removeClass('shown');
				//$("."+row.data().id_coa).remove().draw( false );
				$("."+gr_coa_nya).remove().draw( false );
				
			}else{
		my_td = generate_detail_coa(detail_coa,j_th,map_cate,gr_coa_nya);
		tr_2.after(my_td); 		
		tr_2.addClass('shown');
	return false;
	
			}	
	return false;
	
/*

	my_td = generate_detail_coa(detail_coa,j_th);
	console.log(my_td);
	 var tr_2 = $(that).parent();
	 console.log(tr_2);
	 		tr_2.after(my_td); 
*/
	return my_td;

}

async function pasangDataTable(){
	await $("#neraca").DataTable
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



