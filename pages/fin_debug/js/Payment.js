$( document ).ready(function() {
 url =window.location.search;
 $split_nya = url.split("&id=");
 if($split_nya[1]){
	 $("#myOverlay").css("display","block");
	 //lookup_bpb();
	 //callBack_ListKontraBon($split_nya[1]);	 
	 //$("#klik_saya").attr("disabled",true);
	 //$("#curr").attr("disabled",true);
	 CallServiceArrayListPayment($split_nya[1]).then(function(responnya) {
		CallExistingData();
		getAkunCoa($split_nya[1]);
		$("#myOverlay").css("display","none");
		
	});
 }
});	
$myPayment = 'XXXXX';
$json_detail_payment = [];
$onload = '1';
$payment_array = [];





function CallExistingData(){
			getDetailPayment($payment_array).then(function(responnya) {
				$json_detail_payment = responnya;
				rendertableListDetail_2($json_detail_payment).then(function(responnya_2){
				$("#renderListDetail_2").append(responnya_2);
				pasangDataTable_('myListRekap_2');
			});
			});

}
function getAkunCoa(url_){
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getCoaByJournal.php", 
        data : { code : '1', journal:url_ },     // multiple data sent using ajax
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
/* function getExistingData(){
	return new Promise(function(resolve, reject) {
		var $payment_exs = $("#reff_doc").val();
		if(!$payment_exs){
			alert("Ada Erro!");
		}else{
			resolve($payment_exs);
		}		
		

		}); 
	
	
	
	
}*/
function convert_totext(){  
	return new Promise(function(resolve, reject) {
		var $stringify = JSON.stringify($payment_array);
		resolve($stringify);
		console.log($stringify);
		return false;
		
		//$("#reff_doc").val($stringify);
	});
}

function Call_Detail_Payment_2(){
	headerListRekap_2().then(function(responnya) {
		$("#ListModal_2").empty();
		$("#ListModal_2").append(responnya);
		if($onload == '1'){
			//alert("123");
			//$onload = 0;
			getDetailPayment($payment_array).then(function(responnya) {
				$json_detail_payment = responnya;
				rendertableListDetail_2($json_detail_payment).then(function(responnya_2){
				$("#renderListDetail_2").append(responnya_2);
				pasangDataTable_('myListRekap_2');
			});
			});

		}else{
			console.log($json_detail_payment);
				rendertableListDetail_2($json_detail_payment).then(function(responnya_2){
				$("#renderListDetail_2").append(responnya_2);
				pasangDataTable_('myListRekap_2');
			});			
			
		}

	});
}
function getListBpbRekap(){
	rendertableListDetail_2($supplier.arraynya);	
};

function CallServiceArrayListPayment($id_journal){
	return new Promise(function(resolve, reject) {
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getArrayListPayment.php",
        data: { code: '1', id_journal: $id_journal },     // multiple data sent using ajax
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
			if(d.respon == '200'){
				for(i=0;i<d.records.length;i++){
						$json = {
							id_supplier : d.records[i].id_supplier,
							id : d.records[i].id,
							nilai : d.records[i].nilai
						}
					$payment_array.push($json);
					
				} 
				setTimeout(function(){ 
					resolve($payment_array);
				},4000);
            
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


function getDetailPayment($arr){
	return new Promise(function(resolve, reject) {
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getDetailPayment.php",
        data: { code: '1', payment_array: $arr },     // multiple data sent using ajax
        success: function (response) {
            console.log(response);
            var options = "";
            console.log(response);
            datass = response;
            d = JSON.parse(datass);
			$("#nilai").val(d.records[0].curr+" "+d.total_nilai);
			$("#tot_amnt_new").val(d.records[0].curr+" "+d.total_nilai);
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
		//td +=" 	<th>*</th>            																";
	 td +=" 	<th>Reference</th>            														"; 
		td +=" 	<th>No Payment</th>            															";
		td +=" 	<th>curr</th>            															";
		td +=" 	<th >Amount</th>            															";
		td +="  </thead>                                    										";
		td +="    </tr>  																			";		
		td +="  <tbody id='renderListDetail_2'>                    									";
		td +="  </tbody>                                    										";
      td +="   <tfoot>                                                                               "
      td +="       <tr>                                                                              "
      td +="           <th colspan='3' style='text-align:right'>Total:</th>                          "
      td +="           <th style=text-align:right></th>                                                                     "
      td +="       </tr>                                                                             "
      td +="   </tfoot>																				"
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
/* 			td += "<td>";
				td += "<a href='#' class='btn btn-warning delete_payment' >Hapus</a>";
			td += "</td>"; */
			
			td += "<td>";
				td += decodeURIComponent(detail[i].reference);
			td += "</td>";		
			
			td += "<td >";
				td +=decodeURIComponent(detail[i].no_payment);
			td += "</td>";
	
			td += "<td >";
				td +=decodeURIComponent(detail[i].curr);
			td += "</td>";
	
			td += "<td style=text-align:right>";
				td += decodeURIComponent(detail[i].amnt_view);
			td += "</td>";
			

		td +="</tr>";
		no++;		
	}
			resolve(td);
	});


	//department
//	$("#renderListDetail_2").append(td);
//	pasangDataTable_('myListRekap_2');
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

/* function get_sum_detail(){
	$tmp_nilai = 0;
	$curr = 'IDR';
	for(var x=0; x< $json_detail_payment.length;x++){
		$tmp_nilai = parseFloat($tmp_nilai) + parseFloat($json_detail_payment[x].amount,"XX");
		$curr = $json_detail_payment[x].curr
	}
$aaa = number_format($tmp_nilai);
		 setTimeout(function(){ $("#nilai").val($curr+ " "+ $aaa);},2000);
} */
/* function splice_array(value_nya){
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
	
	
} */



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

$amnt = 0;
function klik_lookup(that){
	$val_nilai =0;
	$json = {
		id_supplier : '',
		id : '',
		nilai : '',
		curr : '',
	}
		if ($('#'+that.id).is(':checked')) {
			//$supplier.arraynya.splice
			if($payment_array.length == '0'){
				$json.id_supplier = $('#'+that.id).data('id_supplier');
				$json.id 	= $('#'+that.id).data('n_id_ap');		
				$json.nilai 	= $('#'+that.id).data('nilai');
				$json.curr 	= $('#'+that.id).data('curr');
				//$('#nilai').val($curr+" "+$amnt);				
				$payment_array.push($json);
				console.log($json);
				$curr = $('#'+that.id).data('curr');	
				console.log($payment_array);	
				getNilai().then(function(responnya) {
					$('#nilai').val($curr+" "+responnya);
					$('#tot_amnt_new').val($curr+" "+responnya);
					
				});				
				
				
				return false;
			}else{
 				$json.id_supplier = $('#'+that.id).data('id_supplier');
				$json.id = $('#'+that.id).data('n_id_ap');	 
				$json.nilai 	= $('#'+that.id).data('nilai');
				$json.curr 	= $('#'+that.id).data('curr');
 				if(check_existing_supplier($payment_array,$json.id_supplier) == 'N'){
					$payment_array.push($json);
					$amnt = parseFloat($amnt) + $val_nilai;
	$curr = $('#'+that.id).data('curr');	
	console.log($payment_array);	
	getNilai().then(function(responnya) {
		$('#nilai').val($curr+" "+responnya);
		$('#tot_amnt_new').val($curr+" "+responnya);
	});
	
					return false;
				}else{
					$("#"+that.id).prop('checked', false);
					alert("Supplier Harus Sama!");
					return false;
				} 
			}
		}else{
				$json.id_supplier = $('#'+that.id).data('id_supplier');
				$json.id 	= $('#'+that.id).data('n_id_ap');		
				$json.nilai 	= $('#'+that.id).data('nilai');
				$json.curr 	= $('#'+that.id).data('curr');
			var $split =  getIndexArrayGlobal($payment_array,$json.id);
			console.log($split);
			$payment_array.splice($split,1);
	$curr = $('#'+that.id).data('curr');	
	console.log($payment_array);	
	getNilai().then(function(responnya) {
		$('#nilai').val($curr+" "+responnya);
		$('#tot_amnt_new').val($curr+" "+responnya);
		
	});
		}

}



function getNilai(){
	return new Promise(function(resolve, reject) {
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getTotalNilaiPayment.php",
        data: { code: '1', payment_array: $payment_array },     // multiple data sent using ajax
        success: function (response) {
            console.log(response);
            var options = "";
            console.log(response);
            datass = response;
            d = JSON.parse(datass);
            //d = response;
            td = '';
            renders = '';
			if(d.status == 'ok'){
            if (d.message == '1') {
				resolve(d.total_nilai);
			
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

 function check_existing_supplier($array,$cari_id){
			for(var i=0; i<$array.length;i++){
				console.log($array[i].id_supplier);
				console.log($cari_id);
			if($array[i].id_supplier != $cari_id){
				result = "Y";	
			
				return result;
			}else{
				console.log("FINDING");
			}
		}
		return "N";
	
}
 
function getIndexArrayGlobal($seArr,$carinya){
	console.log($seArr);
	for(var $search=0;$search<$seArr.length;$search++){
		console.log($seArr[$search].id);
		if($seArr[$search].id == $carinya ){
			
			return $search;
			
		}
		else{
			console.log("FINDING!");
		}
	}
	return 0;
}

function samecheckarray(varArray,valuenya,bpbnya,nilainya,keynya,bpbno_intnya,reference){
	console.log(varArray);
	var result = {
		tmpkey : 0,
		total_nilai : 0,
		arraynya :varArray,
	};
	var value_ =valuenya;
	var bpb_ = bpbnya;
	var nilai_ = nilainya;
	var key_ = keynya;
	var bpbnoint_ = bpbno_intnya;
	if(result.arraynya.length == '0'){				
			var tmpJson = {
				key : key_,
				id : value_,
				bpb : bpb_,
				nilai : nilai_,
				bpbnoint : bpbnoint_,
				journal_reff : reference.data.id_journal,
				date_journal : reference.data.date_journal,
				invno : reference.data.invno,
				date_inv : reference.data.due_date,
				pono : reference.data.pono,
				curr : reference.data.curr
			}		
		result.arraynya.push(tmpJson);
	}else{
		for(var i=0; i<result.arraynya.length;i++){
			if(result.arraynya[i].id != value_){
				result.tmpkey = 1;	
				return result;
			}	
		}
			var tmpJson = {
				key : key_,
				id : value_,
				bpb : bpb_, 
				nilai : nilai_,
				bpbnoint : bpbnoint_,
				journal_reff : reference.data.id_journal,
				date_journal : reference.data.date_journal,
				invno : reference.data.invno,
				date_inv : reference.data.due_date,
				pono : reference.data.pono,
				curr : reference.data.curr
			}
		result.arraynya.push(tmpJson);

	}
	return result;
}

// file currency.js
 
function currency(values, separator) {
	
    if (typeof values == "undefined") return "0";
    if (typeof separator == "undefined" || !separator) separator = ",";
 
    return values.toString()
                .replace(/[^\d]+/g, "")
                .replace(/\B(?=(?:\d{3})+(?!\d))/g, separator);
}
/*  
window.addEventListener('keyup', function(e) {
    var el = e.target;
    if (el.classList.contains('currency')) {
        el.value = currency(el.value, el.getAttribute('data-separator'));
    }
false}); */


 async function pasangDataTable_(id){
	table_nya = $('#'+id).DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        pageLength: 20,
/*         fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }, */
		 "footerCallback": function ( row, data, start, end, display ) {
			  var api = this.api(), data;
			    // Remove the formatting to get integer data for summation
			  var intVal = function ( i ) {
                return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                        i : 0;
            };
            // Total over all pages
            total = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );			
			 // Update footer
			 console.log("total:");
			  console.log(total);
            $( api.column( 3 ).footer() ).html(
                number_format_new(total)
            );
		 }
		
    	//dom: "<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +"<'row'<'col-sm-12'tr>>" 
	
    });	
		 setTimeout(function(){ 	
	console.log("Begin");
		$(".sorting").trigger("click");
	$(".loading").css("display","none");
	}, 2500);
} 