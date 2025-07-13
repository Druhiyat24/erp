$(window).on("load", function() {
sessionStorage.clear();
getdatepicker();	
	CallServicesgetMasterTaxPPN().then(function($responnya) {
		console.log($responnya);
		var $opt = '';
			$opt += "<option value='0'>--Pilih PPN";
		for(var i=0; i<$responnya.records.length;i++){
			$opt +="<option value='"+decodeURIComponent($responnya.records[i].id)+"'>"+decodeURIComponent($responnya.records[i].isi)+"</option>"
		}
		$("#ppn").append($opt);
	});	
	CallServicesgetMasterTaxPPH().then(function($responnya) {
		console.log($responnya);
		var $opt = '';
			$opt += "<option value='0'>--Pilih PPH";
		for(var i=0; i<$responnya.records.length;i++){
			$opt +="<option value='"+decodeURIComponent($responnya.records[i].id)+"'>"+decodeURIComponent($responnya.records[i].isi)+"</option>"
		}
		$("#pph").append($opt);
	});	

	
	
 url =window.location.search;
 $split_nya = url.split("&id=");
 if($split_nya[1]){
	 //lookup_bpb();
	 callBack_ListKontraBon($split_nya[1]);
	 $("#klik_saya").attr("disabled",true);



	callServices_DefaultTax().then(function($responnya) {
		//alert("123");
			//decodeURIComponent
			console.log($responnya.records[0]);
			var ii = decodeURIComponent($responnya.records[0].ppn);
			var ee = decodeURIComponent($responnya.records[0].pph);
			var uu = decodeURIComponent($responnya.records[0].fakturpajak);
			var aa = decodeURIComponent($responnya.records[0].tglpajak);
			var oo = decodeURIComponent($responnya.records[0].inv_supplier);
			var bb = decodeURIComponent($responnya.records[0].tglinvoice);
		
			$('#ppn').val(ii).trigger('change');
			$('#pph').val(ee).trigger('change');
			$('#fakturpajak').val(uu);
			$('#tglpajak').val(aa);
			$("#tglpajak").datepicker().datepicker("setDate", aa);
			$('#tglinvoice').val(aa);
			$("#tglinvoice").datepicker().datepicker("setDate", aa);			
			$('#noinvoiceSupplier').val(oo);
			//getdatepicker();
		if(decodeURIComponent($responnya.records[0].fg_tax) == '1') {
			document.getElementById("fg_tax").checked = true;
			toggleTax();
			
			
			//detail
			console.log($responnya.detail);
			if($responnya.detail.length > 0){
	setTimeout(function(){
				for($x=0;$x<$responnya.detail.length;$x++){
					$("#"+decodeURIComponent($responnya.detail[$x].id_select)).val(decodeURIComponent($responnya.detail[$x].value));
					$from_service = {id :decodeURIComponent($responnya.detail[$x].id_select),
									value : decodeURIComponent($responnya.detail[$x].value)
					}
					get_pph($from_service);
					
				}		
		
		
	},3000)

				
			}
			
			//detail
		}
	});	

	 //$("#curr").attr("disabled",true); document.getElementById("umurar").checked = false;
 
 
	CallServicesgetArrayKontraBon($split_nya[1]).then(function($responnya) {
			$supplier.arraynya = $responnya.records;
		console.log($supplier);
	});	 
 }
});

$( document ).ready(function() {
/* 
 
 
 */
});	

/* window.addEventListener('load', function () {
  alert("It's loaded!")
})
 */
$supplier = {
	nama :'',
	total_nilai :0,
	arraynya : [],
}


$(document).on("click", '.paginate_button', function(event) { 
toggleTax();
	  
});


function getdatepicker(){
	
    $("#tglpajak").datepicker({
      format: "dd M yyyy",
      autoclose: true
    });		
    $("#tglinvoice").datepicker({
      format: "dd M yyyy",
      autoclose: true
    });			
	
}

function callServices_DefaultTax(){
	return new Promise(function(resolve, reject) {
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getDefaultTax.php",
        data: { code: '1', journal: $split_nya[1]},     // multiple data sent using ajax
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

function callBack_ListKontraBon($id_journalnya){
	callServices_ListKontraBon($id_journalnya).then(function($responnya) {
		console.log($responnya);
		$supplier.nama = $responnya.nama;
		$supplier.total_nilai = $responnya.total_nilai;
		$supplier.arraynya = DecodeFromServices($responnya.arraynya);
		console.log($supplier);
		$("#amount").val($supplier.total_nilai);
		$("#curr").val($supplier.arraynya[0].curr);
	});
}
function callServices_ListKontraBon($id_journalnya){
	return new Promise(function(resolve, reject) {
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getDetailKontraBon.php",
        data: { code: '1', id: $split_nya[1]},     // multiple data sent using ajax
        success: function (response) {
            d = JSON.parse(response);
            console.log(d.arraynya.length);
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
	console.log(samecheckarray);
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



	function CallServicesgetMasterTaxPPN(){
	return new Promise(function(resolve, reject) {
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getMasterTax.php", 
        data : { code : '1',type_tax:'PPN' },     // multiple data sent using ajax
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				if(d.message == '1'){
					resolve(d);
						//$("#rate").val(r);
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });			 	
	 });		
		
	}
	
	function CallServicesgetMasterTaxPPH(){
	return new Promise(function(resolve, reject) {
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getMasterTax.php", 
        data : { code : '1',type_tax:'PPH' },     // multiple data sent using ajax
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				if(d.message == '1'){
					resolve(d);
						//$("#rate").val(r);
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });			 	
	 });		
		
	}
	function CallServicesgetArrayKontraBon($id_journal){
	return new Promise(function(resolve, reject) {
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getArrayKontraBon.php", 
        data : { code : '1',id_journal: $id_journal},     // multiple data sent using ajax
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				if(d.message == '1'){
					resolve(d);
						//$("#rate").val(r);
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });			 	
	 });		
		
	}	
	

function toggleTax(){
    if($('#fg_tax').is(':checked')){
        $('#ppn').attr('disabled', false);
		$('.pph').attr('disabled', false);
     }else{
        $('#ppn').attr('disabled', true);
		$('.pph').attr('disabled', true);
$('#ppn').val('0').trigger('change');
/* $('#pph').val('0').trigger('change');*/	
sessionStorage.clear();
    }
	
	
	
	
}





$array_pph = [];
function get_pph(that){
	$pecah_id = that.id.split("_");
	$j_pph = {'id' : $pecah_id[1],
				'value':that.value
	}

if(check_exis_pph($j_pph) == '1'){
	console.log($array_pph);
	return false;
	}else{
		$array_pph.push($j_pph);
		console.log($array_pph);
	}
	
}

function check_exis_pph(that){
	for($i=0;$i<$array_pph.length;$i++){
		if($array_pph[$i].id == that.id){
			$push = 1;
			if(that.value == "0"){
					var $split_pph =  getIndexArrayGlobal($array_pph,that.id);
					console.log($split_pph);
					$array_pph.splice($split_pph,1);	
					return $push;
			}else{
				$array_pph[$i].value = that.value;
				return $push;
			}	
		}else{
			$push = 0;
		}
	}
	return 0;
}
function getTotal_nilai(Json_nya){
	Json_nya.total_nilai = 0;
		console.log(Json_nya.total_nilai);
		for(var v=0;v<Json_nya.arraynya.length;v++){
			Json_nya.total_nilai = parseFloat(Json_nya.total_nilai) + parseFloat(Json_nya.arraynya[v].nilai);
		}		
		console.log(Json_nya);
	return Json_nya;
}
function supplierCheck(that,bpb){
	getReference($('#'+that.id).data('bpbintnya')).then(function(responnya) {
		var $tmpbpb      =$('#'+that.id).data('bpbnya');
		var $tmpnilai    =$('#'+that.id).data('nilainya');
		var $tmpsupplier =$('#'+that.id).data('suppliernya');
		var $tmpbpbno_int=$('#'+that.id).data('bpbintnya');
		if ($('#'+that.id).is(':checked')) {
			//$supplier.arraynya.splice
			var check =samecheckarray($supplier.arraynya,that.value,$tmpbpb,$tmpnilai,that.id,$tmpbpbno_int,responnya);
			console.log(check);
				if(check.tmpkey == 1){
					alert("Supplier yang dipilih Harus Sama");
					$("#"+that.id).prop('checked', false);
					$supplier =  getTotal_nilai($supplier);
					return false
				}
				else{
					
					$supplier.arraynya = check.arraynya;
					$supplier.total_nilai = check.total_nilai;		
					//console.log($supplier.arraynya);		
				}
		}
		else{
			//alert("123");
			var $split =  getIndexArray($supplier,that.id);
			console.log($split);
			$supplier.arraynya.splice($split,1);
		}	
		$supplier =  getTotal_nilai($supplier);
		//console.log($supplier.arraynya.indexOf('412__10'));
		//console.log($supplier);
		//$("#amount").val(number_format($supplier.total_nilai));tmpsupplier customer
		$conf = number_format($supplier.total_nilai);
		$supplier.nama = $tmpsupplier;
		$("#amount").val($conf);
		$("#customer").val($tmpsupplier);
		//bpbnointnya
		//getReference($tmpbpbno_int);			
			
			
			
			
	})
}

function getIndexArray($seArr,$carinya){
	
	console.log($seArr);
	for(var $search=0;$search<$seArr.arraynya.length;$search++){
		console.log($seArr.arraynya[$search].key);
		if($seArr.arraynya[$search].key == $carinya ){
			
			return $search;
			
		}
		else{
			console.log("FINDING!");
		}
	}
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




function getListBpbRekap(){
	rendertableListDetail($supplier.arraynya);	
};

function headerListRekap(){
//$("#myListRekap").dataTable().fnDestroy();
var td = '';
td +="	  	<table id='myListRekap' class='display responsive' style='width:100%;font-size:12px;'> ";
td +="      <thead> 																			";
td +="        <tr>  																			";
td +="	    	<th>Bpb No Int</th>            															";
td +="	    	<th>Amount</th>            												";
td +="	    	<th>Supplier</th>            												";
td +="	    	<th>Journal Reference</th>            												";
td +="	    	<th>Date Journal Reference</th>            												";
td +="	    	<th>Invoice</th>            												";
td +="	    	<th>Tgl Jatuh Tempo</th>            												";
td +="      </thead>                                    										";
td +="      <tbody id='renderListDetail'>                    												";
td +="      </tbody>                                    										";
td +="    </table>	                                    										";	
$("#ListModal").empty();
	$("#ListModal").append(td);		


}



async function rendertableListDetail(detail){
	stage = 1;
	headerListRekap();
	var td = "";
	no = 0;
	for(var i=0;i<detail.length;i++){
		td +="<tr>";		

			td += "<td>";
				td += decodeURIComponent(detail[i].bpbnoint);
			td += "</td>";			
			td += "<td align='right'>";
				td +=decodeURIComponent(detail[i].curr)+" "+ decodeURIComponent(detail[i].nilai);
			td += "</td>";
			td += "<td>";
				td += decodeURIComponent($supplier.nama);
			td += "</td>";
			td += "<td>";
				td += decodeURIComponent(detail[i].journal_reff);
			td += "</td>";
			
			td += "<td>";
				td += decodeURIComponent(detail[i].date_journal);
			td += "</td>";

			td += "<td>";
				td += decodeURIComponent(detail[i].invno);
			td += "</td>";
			td += "<td>";
				td += decodeURIComponent(detail[i].date_inv);
			td += "</td>";
		td +="</tr>";
		no++;
	}//department
	$("#renderListDetail").append(td);
	pasangDataTable('myListRekap');
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

function convert_totext(){
	return new Promise(function(resolve, reject) {
		var $stringify = JSON.stringify($supplier);
		resolve($stringify);
		//$("#reff_doc").val($stringify);
	});
	
	
	
}

async function pasangDataTable(id){
	await $('#'+id).DataTable
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
}