$(document).ready(function () {
       $data = {
			id : '',
			invno :'',
			date_invoice : '',
			id_supplier :'',
			id_terms :'',
			id_coa :'',
			fg_ppn	  : '0',
    }
	$G_id_bppb = "";
	$detail =[];
	url = window.location.search;
	$split_nya = url.split("=");
	format = '1';

	
	  Option("webservices/getListBKBScrap.php")//OPTION BKB
	  .then(gen_option 				=> generate_option(gen_option,'Choose BKB'))//generate bkb 
	  .then(inj_option 				=> injectOptionToHtml(inj_option,'bkb')) //generate bkb 
	  .then(AAA						=> Option("webservices/getListBank.php")) //generate bank 
	  .then(gen_option_bank			=> generate_option(gen_option_bank,'Choose Bank')) //generate bank 
	  .then(inj_option 				=> injectOptionToHtml(inj_option,'id_coa')) //generate bank  
	  .then(AA 						=> Option("webservices/getMasterPaymentTerms.php")) //generate terms 
	  .then(gen_option_terms 		=> generate_option(gen_option_terms,'Choose Pterms'))//generate terms 
	  .then(inj_option 				=> injectOptionToHtml(inj_option,'id_terms')) //generate terms 
	  .then(A 						=> checkUrl($split_nya[2]))
	  .then(B						=> initFormat($G_kondisi))//inisialisasi format (1=NEW, 2 =UPDATE)
	  .then(C						=> getListDataHeader($split_nya[2],$G_kondisi))
	  .then(default_data			=> inisialisasi_data_header(default_data,$G_kondisi))
	  .then(D						=> inejectHeaderToHtml($data,$G_kondisi,$G_id_bppb))
	  .then(E						=> disable_bkb_check_ppn($data,$G_kondisi))
	  //.then(E						=> getDetail($G_id_bppb))
	  
	  
	    .catch(error => { 
        console.log(error); 
        alert("Some think Wrong!"); 
        });
	
});
	
function initFormat(check_url_nya){
		if ($G_kondisi == '1') {
			return format = '2';
	}
	else {
			return format = '1';
	}	
	
}	

function disable_bkb_check_ppn($json,kondisi){
	if(kondisi == '1'){
		return [
			$("#bkb").attr("disabled","true"),
			($json.fg_ppn =='1'? document.getElementById("fg_ppn").checked = true : document.getElementById("fg_ppn").checked = false)
		];	
	}else{
		return 1;
	}
}
function inejectHeaderToHtml(json,kondisi){
	if(kondisi == '1'){
		return [$("#dtpicker").val(json.date_invoice),
		$("#bkb").val($G_id_bppb).trigger("change"),
		$("#noinvoice").val(json.invno),
		$("#id_terms").val(json.id_terms).trigger("change"),
		$("#id_coa").val(json.id_coa).trigger("change"),
		];
		
	}else{
		return 1;
	}
	
}	
	
function checkUrl(check_url_nya){
		if (check_url_nya) {
			$G_id_url = check_url_nya;
			$G_kondisi = 1;
			return "1";
		//$("#ws").attr("disabled", true)

		//id_url = $split_nya[2]
	}
	else {
		$G_id_url = 'XX';
		$G_kondisi = 0;
		return false;
		//id_url = -1
	}
	
	
}	

function inisialisasi_data_header(json,kondisi){
	if(kondisi =='1'){

	
	return [
		$data.id 				=  decodeURIComponent(json.records[0].id),
		$data.invno             =  decodeURIComponent(json.records[0].invno),
		$data.date_invoice      =  decodeURIComponent(json.records[0].date_invoice),
		$data.id_supplier       =  decodeURIComponent(json.records[0].id_buyer),
		$data.id_terms          =  decodeURIComponent(json.records[0].id_terms),
		$data.id_coa          	=  decodeURIComponent(json.records[0].id_coa),
		$data.fg_ppn	        =  decodeURIComponent(json.records[0].fg_ppn),
		$G_id_bppb 				=  decodeURIComponent(json.records[0].id_bppb),
		
	];
	}
	else{
		return 1;
	}
	return 1;
}
	
function injectOptionToHtml(string,id){
	return $("#"+id).append(string);
}
function generate_option(PropHtmlData,judul){
	var option = "";
	option += "<option value=''>--"+judul+"--</option>";
	var i;
	for (i = 0; i < PropHtmlData.records.length; i++) {
		var id = decodeURIComponent(PropHtmlData.records[i].id);
		var isi = decodeURIComponent(PropHtmlData.records[i].isi);
		option += '<option value="' + id + '">' + isi + '</option>';
	}	
	return option;
}


function getDetail($my_id) {
	if ($my_id == ""){
		return 1;
	}else{
		
	getSupplierByBkbHeader($my_id)
		.then(array_supplier => InjectNamaSuppleir(array_supplier)) // Create $G_id_supplier
		.then(B 			 => InitSupplier($G_id_supplier)) //
		.then(C 			 => GenerateTableDetail($my_id,$G_id_url)) //
	    .catch(error => {
        alert("Some think Wrong!"); 
        });		
	}
}

function getSupplierByBkbHeader($my_id){
	return new Promise(function(resolve, reject) {
	$.ajax("webservices/getSupplierScrap.php?id_bkb="+$my_id,
		{
			type: "POST",
			processData: false,
			contentType: false,
			success: function (data) {
				resolve(jQuery.parseJSON(data));
			},
			error: function (data) {
				alert("Error req");
			}
		});
	});	
}


function Save() {
	if (typeof $G_id_supplier === 'undefined'){
		alert("Isi Data Dahulu Sebelum Save!");
		return false;
	}

			//$data.id 			=
			//$data.invno 		=
			$data.date_invoice 	= $("#dtpicker").val();
			$data.id_supplier 	= $G_id_supplier;
			$data.id_terms 		= $("#id_terms").val();
			$data.id_coa 		= $("#id_coa").val();
			$data.fg_ppn	  	= ($("#fg_ppn").is(":checked") ? "1":"0" )//$("#fg_ppn").val();

	//VALIDASI HEADER
	if(!$data.date_invoice){
		alert("Tanggal Harus Diisi");
		return false;
	}if(!$data.id_coa){
		alert("Bank Harus Diisi");
		return false;
	}if(!$data.id_supplier){
		alert("Supplier Harus Diisi");
		return false;
	}if(!$data.id_terms){
		alert("Pterms Harus Diisi");
		return false;
	}
	

	//validation $data
	if ($data.id_supplier == '') {
			alert("Customer harus Diisi");
		return false;
	}
	for(var i=0;i<$detail.length;i++){
		$detail[i].curr = $("#curr_"+i).val();
		$detail[i].qty = $("#qty_"+i).val();
		$detail[i].price = $("#price_"+i).val();
		$detail[i].discount = $("#discount_"+i).val();
		

		
		if($detail[i].curr.length > 3){
			alert("Currency Harus Diisi");
			return false;
		}
		
		
		if(isNaN($detail[i].qty)){
			alert("Input Qty Salah!");
			return false;
		}
		if($detail[i].qty == '0'){
			alert("Qty Harus Lebih Dari 0")
			return false;
		}
		if((($detail[i].stock) - ($detail[i].qty) ) <0){
			alert("Qty Tidak Boleh Melebihi Stock")
			return false;
		}
			if((parseFloat($detail[i].stock) <1)){
			alert("Stock Tidak Mencukupi")
			return false;
		}	
		
		if(isNaN($detail[i].price)){
			alert("Input Price Salah!");
			return false;
		}
		if($detail[i].price == '0'){
			alert("Price Tidak Boleh Kosong")
			return false;
		}		
		if($detail[i].price == ''){
			alert("Price Tidak Boleh Kosong")
			return false;
		}	


		if(isNaN($detail[i].discount)){
			alert("Input discount Salah!");
			return false;
		}
/* 		if($detail[i].discount == '0'){
			alert("discount Tidak Boleh Kosong")
			return false;
		}		
		if($detail[i].discount == ''){
			alert("discount Tidak Boleh Kosong")
			return false;
		}	 */	
		
	}
	$.ajax({
		type: "POST",
		cache: false,
		url: "webservices/SaveInvoiceScrap.php",
		data: { code: '1', format: format, data: $data, detail: $detail },     // multiple data sent using ajax
		success: function (response) {
			data = response;
			// console.log(data);
			d = JSON.parse(data);
			//console.log(d.message);
			if (d.respon == '200') {
				// Swal.fire("Success!", "Data Has Been Save", "success");
				// setTimeout(function () {
				alert('Data Berhasil Di Save')
				window.location.href = "?mod=InvocieScrapPage";
				// }, 3000)
			}
			else {
				alert('response');
				window.location.href = "?mod=InvocieMaterialPage";					
				Swal.fire({
					type: 'Error!',
					title: 'Validation',
					text: d.message,
					footer: '-_-'
				});
			}
		}
	});
}


function getListDataHeader(my_id,kondisi){
	if(kondisi == '1'){
	
	return new Promise(function(resolve, reject) {
	$.ajax("webservices/getListDataInvoiceScrapHeader.php",
		{
			type: "POST",
			data: {id:my_id},
			success: function (data) {
				resolve(jQuery.parseJSON(data));
			},
			error: function (data) {
				alert("Error req");
			}
		});
		
	});	
	}else{
		return 1;
	}
	
}

function GenerateTableDetail(id_bkb,id_url_) {
	$detail.length=0;
/* 	i_trigger =i_trigger +1; 
	$detail = [];
	i = 1; */
	//alert("123");
	// alert(cost)
	// return false;
	//if(i_trigger > 1){
		TableDetail = $('#inv_scrap_detail').DataTable({
			"serverSide": true,
			"bSort": false,
			"destroy": true,
			"method" :"POST",
			"ajax": "webservices/getInvoiceScrapDetail.php?id_bkb=" + id_bkb+"&id_url="+id_url_,
			"columns": [
				// { "data": "kpno" },
				{ "data": "bkb" },
				{ "data": "tipe_scrap" },
				{ "data": "stock" },
				{ "data": "unit" },
				//{ "data": "curr" },
				{ "data": "qty_bkb" },
				//{ "data": "price_bkb" },
				{
					"data": null,
					"render": function (data) {
						return decodeURIComponent(data.button3);
					}
				},	
				{
					"data": null,
					"render": function (data) {
						return decodeURIComponent(data.button);
					}
				},
				
				{
					"data": null,
					"render": function (data) {
						return decodeURIComponent(data.button2);
					}
				},		
				{
					"data": null,
					"render": function (data) {
						return decodeURIComponent(data.button4);
					}
				},					
			],
			"rowCallback": function (nRow, data, index) {
						$detail.push(data);
						//i_trigger = 1+1;
			},
			scrollX: true,
			scrollY: "500px",
			scrollCollapse: true,
			scrollXInner: "100%",
			paging: false,
			fixedColumns: true,
	
		});
	//}
	console.log($detail);
}


function InjectNamaSuppleir(nama_sup){
	$G_id_supplier = decodeURIComponent(nama_sup.records[0].id_supplier);
	return $("#text_supplier").val(decodeURIComponent(nama_sup.records[0].supplier));
}

function InitSupplier(id_supp){

	return $data.id_supplier = id_supp;
}
function Option($url_opt) {
	return new Promise(function(resolve, reject) {
	$.ajax($url_opt,
		{
			type: "POST",
			data: {code:'1'},
			success: function (data) {
				resolve(jQuery.parseJSON(data));
			},
			error: function (data) {
				alert("Error req");
			}
		});
		
	});
}
	
function handleKeyUp(that) {
	var id = that.id;

	var index_array = id.split('_');
	var idx = index_array[1];

	var vl = that.value;
	// console.log(that.value)
	// return false;
	if (index_array[0] == 'qty') {
		$detail[idx].qty = vl;
		console.log($detail)
	}
	else if (index_array[0] == 'price') {
		$detail[idx].price = vl;
	}
	else if (index_array[0] == 'curr') {
		$detail[idx].curr = vl;
	}	
	else if (index_array[0] == 'curr') { //discount
		$detail[idx].curr = vl;
	}		
	else if (index_array[0] == 'discount') { //discount
		$detail[idx].discount = vl;
	}	
	console.log($detail[idx].discount);
}

function Cancel(){
	window.location.href = "?mod=InvocieScrapPage";
}
		
	