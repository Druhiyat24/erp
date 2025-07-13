$( document ).ready(function() {
 url =window.location.search;
 $split_nya = url.split("&id=");
 if($split_nya[1]){
	 //lookup_bpb();
	 //callBack_ListKontraBon($split_nya[1]);	 
	 //$("#klik_saya").attr("disabled",true);
	 //$("#curr").attr("disabled",true);
	CallExistingData()	 
 }
});	
$myPayment = 'XXXXX';
$json_detail_payment = [];
$onload = '1';


function CallExistingData(){
	getExistingData().then(function(responnya) {
			$myPayment = responnya;
			getDetailPayment($myPayment).then(function(responnya) {
				$json_detail_payment = responnya;
				get_sum_detail();
				getAkunCoa();
				console.log($json_detail_payment);
		});
	});

}

function getAkunCoa(){
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getCoaByJournal.php", 
        data : { code : '1', journal:$split_nya[1] },     // multiple data sent using ajax
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				console.log(d.message);
				if(d.message == '1'){
					var my_coa = decodeURIComponent(d.records[0].id_coa);
					console.log(d);
					$("#bank").val(my_coa).trigger("change"); 
					
				}
				
				else{
					alert(d.records);
					
				}
        }
      });		 	
	
	
}
function getExistingData(){
	return new Promise(function(resolve, reject) {
		var $payment_exs = $("#reff_doc").val();
		if(!$payment_exs){
			alert("Ada Erro!");
		}else{
			resolve($payment_exs);
		}		
		

		});
	
	
	
	
}
function convert_totext(){  
	return new Promise(function(resolve, reject) {
		var $stringify = JSON.stringify($json_detail_payment);
		resolve($stringify);
		//$("#reff_doc").val($stringify);
	});
}

function Call_Detail_Payment_2(){
	headerListRekap_2().then(function(responnya) {
		$("#ListModal_2").empty();
		$("#ListModal_2").append(responnya);
		if($onload == '1'){
			$onload = 0;
			getDetailPayment($myPayment).then(function(responnya) {
				$json_detail_payment = responnya;
				rendertableListDetail_2($json_detail_payment).then(function(responnya_2){
				$("#renderListDetail_2").append(responnya_2);
				pasangDataTable('myListRekap_2');
			});
			});

		}else{
				rendertableListDetail_2($json_detail_payment).then(function(responnya_2){
				$("#renderListDetail_2").append(responnya_2);
				pasangDataTable('myListRekap_2');
			});			
			
		}

	});
}
function getListBpbRekap(){
	rendertableListDetail_2($supplier.arraynya);	
};
function getDetailPayment($no_payment){
	return new Promise(function(resolve, reject) {
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getDetailPayment.php",
        data: { code: '1', no_payment: $no_payment },     // multiple data sent using ajax
        success: function (response) {
            console.log(response);
            var options = "";
            console.log(response);
            datass = response;
            d = JSON.parse(datass);
            //d = response;
            td = '';
            renders = '';
            console.log(d.records.length);
			if(d.status == 'ok'){
            if (d.message == '1') {
				resolve(d.records);
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


function headerListRekap_2(){
	return new Promise(function(resolve, reject) {
		var td = '';
		td +="<table id='myListRekap_2' class='display responsive' style='width:100%;font-size:12px;'> ";
		td +="  <thead> 																			";
		td +="    <tr>  																			";
		td +=" 	<th>*</th>            																";
		td +=" 	<th>Reference</th>            														";
		td +=" 	<th>No Payment</th>            															";
		td +=" 	<th>Amount</th>            															";
		td +="  </thead>                                    										";
		td +="    </tr>  																			";		
		td +="  <tbody id='renderListDetail_2'>                    									";
		td +="  </tbody>                                    										";
		td +="</table>	                                    										";			
		resolve(td);
	});


	//$("#ListModal_2").empty();
	//$("#ListModal_2").append(td);		
//return td;

}



function rendertableListDetail_2(detail){
	console.log(detail);
	return new Promise(function(resolve, reject) {
		console.log(detail);
	stage = 1;
	headerListRekap_2();
	var td = "";
	no = 0;
	for(var i=0;i<detail.length;i++){
		td +="<tr>";				
			td += "<td>";
				td += "<a href='#' class='btn btn-warning delete_payment' >Hapus</a>";
			td += "</td>";
			
			td += "<td>";
				td += decodeURIComponent(detail[i].reference);
			td += "</td>";		
			
			td += "<td >";
				td +=decodeURIComponent(detail[i].no_payment);
			td += "</td>";
			
			td += "<td>";
				td += decodeURIComponent(detail[i].amount);
			td += "</td>";
			

		td +="</tr>";
		no++;		
	}
			resolve(td);
	});


	//department
//	$("#renderListDetail_2").append(td);
//	pasangDataTable('myListRekap_2');
}





$(document).on( 'click', '.delete_payment', function () {
	if($json_detail_payment.length < 2){
		alert("Data Tidak Boleh Kosong !");
		
	}else{
	splice_array(table_nya.row($(this).parents('tr')).data()[1])	
    table_nya
        .row( $(this).parents('tr') )
        .remove()
        .draw();		
	}	

} );

function get_sum_detail(){
	$tmp_nilai = 0;
	for(var x=0; x< $json_detail_payment.length;x++){
		$tmp_nilai = parseFloat($tmp_nilai) + parseFloat($json_detail_payment[x].amount);
	}
		
	$("#nilai").val($tmp_nilai);
}
function splice_array(value_nya){
	//$tmp_nilai = 0
	console.log($json_detail_payment);	
	for(var x=0; x< $json_detail_payment.length;x++){
		//$tmp_nilai = parseFloat($tmp_nilai) + parseFloat($json_detail_payment[x].amount);
		if($json_detail_payment[x].reference == value_nya){
			$json_detail_payment.splice(x,1);
			console.log($json_detail_payment);
			get_sum_detail();
			return false;
		}else{
			console.log("checking...");

				
			
		}
		
	}
	
	
}



function  getReference(tmpbpbno){
	return new Promise(function(resolve, reject) {
  $.ajax({		
        type:"POST",
        cache:false, 
        url:"ajax_fin.php?mdajax=get_posted_bpb", 
        data : { bpbno_internal :tmpbpbno },     // multiple data sent using ajax
        success: function (response) {
			data = response;
			//console.log(data);
				d = JSON.parse(data);
				console.log(d);
				resolve(d);
        }
      });		 	
	 });
}


/* async function pasangDataTable(id){
	table_nya = $('#'+id).DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        },
    	//dom: "<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +"<'row'<'col-sm-12'tr>>" 
	
    });	
		 setTimeout(function(){ 	
	console.log("Begin");
		$(".sorting").trigger("click");
	$(".loading").css("display","none");
	}, 2500);
} */