$(document ).ready(function() {
$("#myOverlay").css("display","block");
$("#txtpono").css("color","#EEE");
	$data = {
		format :'1',
		mod :'',
		txtdraftpo : '',
		txtdrafpodate : '', //datepicker1
		jenis_item : '',
		cbosupp :'',
		curr :'',
		txtid_terms :'',
		txtdays :'',
		txtid_dayterms :'',
		txtetddate :'', //datepicker2
		txtetadate :'', //txtetadate3
		txtexpdate :'', //txtexpdate4
		txtdisc :'',
		ppn_nya : '',
		txtnotes :'',
		cboJO :'',
		pkp :'',
		triger_ppn :'',
		n_kurs :'',
		tipe_com :'',
	}



	url = window.location.search;
	$split_nya = url.split("=");
	if($split_nya[2]){
		var tmp_mod = $split_nya[1].split("&");
		$mod = tmp_mod[0];
	}else{
		$mod = $split_nya[1];
	}
	console.log($split_nya);
	
	  Opt("webservices/Opt_jenis_item.php")//OPTION jenis item
	  .then(data 				    => generate_option(data,'Choose Jenis Item'))//generate bkb 
	  .then(inj_option 				=> injectOptionToHtml(inj_option,'jenis_item')) //generate bkb 
	  .then(AAA						=> Opt("webservices/Opt_currency.php")) //generate currency 
	  .then(data					=> generate_option(data,'Choose Currency')) //generate currency 
	  .then(inj_option 				=> injectOptionToHtml(inj_option,'curr')) // generate currency 
	  .then(AA 						=> Opt("webservices/Opt_payment_terms.php")) //generate terms 
	  .then(gen_option_terms 		=> generate_option(gen_option_terms,'Choose Pterms'))//generate terms 
	  .then(inj_option 				=> injectOptionToHtml(inj_option,'txtid_terms')) //generate terms 
	  .then(AA 						=> Opt("webservices/Opt_day_terms.php")) //generate terms 
	  .then(gen_option_day_terms 	=> generate_option(gen_option_day_terms,'Choose Day terms'))//generate terms 
	  .then(inj_option 				=> injectOptionToHtml(inj_option,'txtid_dayterms')) //generate terms 	  
	  .then(AA 						=> Opt("webservices/Opt_tax.php")) //generate terms 
	  .then(gen_option_day_terms 	=> generate_option(gen_option_day_terms,'Choose Tax'))//generate terms 
	  .then(inj_option 				=> injectOptionToHtml(inj_option,'triger_ppn')) //generate terms 	   
	  .then(A 						=> checkUrl($split_nya[2]))
	  .then(B						=> initFormat($G_kondisi))//inisialisasi format (1=NEW, 2 =UPDATE)
	  .then(C						=> getListDataHeader($split_nya[2],$G_kondisi))
	  .then(D						=> inejectHeaderToHtml(D,$G_kondisi))
	  .then(DSB						=> disable_attr($G_kondisi,'datepicker1'))
	  .then(DSB						=> disable_attr($G_kondisi,'jenis_item'))
	  .then(DSB						=> disable_attr($G_kondisi,'cbosupp'))
	  .then(DSB						=> disable_attr($G_kondisi,'curr'))
	  //.then(E						=> disable_bkb_check_ppn($data,$G_kondisi)) disable_attr
setTimeout(function(){
	
	$("#myOverlay").css("display","none");
},10000)
}); 


function inejectHeaderToHtml(json,kondisi){
	if(kondisi == '1'){
		return [
		$("#txtpono").val(decodeURIComponent(json.records[0].draftno)),
		$("#datepicker1").val(decodeURIComponent(json.records[0].draftdate)),
		$("#jenis_item").val(decodeURIComponent(json.records[0].jenis_item)).trigger("change"),
		$("#cbosupp").val(decodeURIComponent(json.records[0].supplier)).trigger("change"),
		//$("#cbosupp").val(decodeURIComponent(json.records[0].jenis_item)).trigger("change"),
		$("#curr").val(decodeURIComponent(json.records[0].curr)),
		$("#txtid_terms").val(decodeURIComponent(json.records[0].id_terms)).trigger("change"),
		$("#txtdays").val(decodeURIComponent(json.records[0].jml_pterms)),
		$("#txtid_dayterms").val(decodeURIComponent(json.records[0].id_dayterms)).trigger("change"),
		$("#datepicker2").val(decodeURIComponent(json.records[0].etd)),
		$("#datepicker3").val(decodeURIComponent(json.records[0].eta)),
		$("#datepicker4").val(decodeURIComponent(json.records[0].expected_date)),
		$("#txtdisc").val(decodeURIComponent(json.records[0].discount)),
		$("#ppn_nya").val(decodeURIComponent(json.records[0].tax)),
		$("#txtnotes").val(decodeURIComponent(json.records[0].notes)),
		//$("#id_coa").val(decodeURIComponent(json.records[0].jenis_item).trigger("change"),
		$("#n_kurs").val(decodeURIComponent(json.records[0].kurs)),
		
		setTimeout(function(){
			console.log(json.records[0].fg_pkp);
			if(decodeURIComponent(json.records[0].fg_pkp) == '1' ){
				$("#pkp").prop( "checked", true );
				console.log("checklist_pkp");
				}
				checkpkp();
				getJOList();
				setTimeout(function(){
					var t_jo = [];
					for(var i=0;i<json.id_jo.length;i++){
						t_jo.push(json.id_jo[i].id_jo);
					}
					
					
					$("#cboJO").val(t_jo).trigger("change");
					$("#cboJO").prop('disabled',true);
					
					
				},4000);
				console.log(json.id_jo);
			
			//$("#cbosupp").val(decodeURIComponent(json.records[0].supplier)).trigger("change")
		},3000)
		];
		
	}else{
		return 1;
	}
	
}	

function getListSupp(jenis_item){
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_po.php?modeajax=get_list_supp',
        data: "jenis_item=" +jenis_item,
        async: false
    }).responseText;
    if(html)
    { $("#cbosupp").html(html); }	
}

function getListSuppGlobal(jenis_item){
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_po.php?modeajax=get_list_supp_global',
        data: "jenis_item=" +jenis_item,
        async: false
    }).responseText;
    if(html)
    { $("#cbosupp").html(html); }	
}

function checkpkp(){	
if ($('#pkp').is(':checked')) {	
		$("#pkp").val('1');	
		$("#triger_ppn").prop('disabled',false);
		$("#triger_ppn").val('11').trigger('change');
		$("#ppn_nya").val('11');
}	
else{	
		$("#pkp").val('0');	
		$("#triger_ppn").prop('disabled',true);
		$("#triger_ppn").val('').trigger('change');
		$("#ppn_nya").val('0');
		
	}	
}

function checkitem(){	
if ($('#chk_ppn').is(':checked')) {	
		$("#chk_ppn").val('1');
}	
else{	
		$("#chk_ppn").val('0');	
		
	}	
}	

function getJOList() {
	if(typeof $("#cbosupp").val() === 'undefined'){
		alert("Supplier Harus Diisi");
		$("#curr").val('');
		return false;
	}
	if($("#cbosupp").val() == ""){
		alert("Supplier Harus Diisi");
		$("#curr").val("");
		return false;
	}
	if(!$("#cbosupp").val()){
		$("#curr").val("");
		alert("Supplier Harus Diisi");
		return false;
	}	
	var id_supplier = $("#cbosupp").val();
	var jenis_item =$("#jenis_item").val();
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_po.php?modeajax=get_list_jo',
        data: {id_supp: id_supplier,jenis_item: jenis_item},
        async: false
    }).responseText;
    if(html)
    { $("#cboJO").html(html); }	
}

function getJOListGlobal() {
	if(typeof $("#cbosupp").val() === 'undefined'){
		alert("Supplier Harus Diisi");
		$("#curr").val('');
		return false;
	}
	if($("#cbosupp").val() == ""){
		alert("Supplier Harus Diisi");
		$("#curr").val("");
		return false;
	}
	if(!$("#cbosupp").val()){
		$("#curr").val("");
		alert("Supplier Harus Diisi");
		return false;
	}	
	var id_supplier = $("#cbosupp").val();
	var jenis_item =$("#jenis_item").val();
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_po.php?modeajax=get_list_jo_global',
        data: {id_supp: id_supplier,jenis_item: jenis_item},
        async: false
    }).responseText;
    if(html)
    { $("#cboJO").html(html); }	
}



function getJO()
  { var id_jo = $('#cboJO').val();
    var id_supp =  $("#cbosupp").val();
    var jenis_item = $("#jenis_item").val();
	console.log($G_kondisi);
	if($G_kondisi == '1'){
		var url_nya = "ajax_po_draf.php?modeajax=view_list_jo";
		var data_nya = { id_supp: id_supp,jenis_item: jenis_item,id_draft_po : $G_id_url,id_jo: id_jo }
	}else{
		var url_nya = "ajax_po.php?modeajax=view_list_jo";
		var data_nya = { id_supp: id_supp,jenis_item: jenis_item,id_jo: id_jo };		
	}		
	
    var html = $.ajax
    ({  type: "POST",
        url: url_nya,
        data: data_nya,
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }



    $(document).ready(function() {
      var table = $('#examplefix2').DataTable
      ({  scrollCollapse: true,
          paging: false,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });
    $(".select2").select2();
  };


  function getJOGlobal()
  { var id_jo = $('#cboJO').val();
    var id_supp =  $("#cbosupp").val();
    var jenis_item = $("#jenis_item").val();
		var url_nya = "ajax_po.php?modeajax=view_list_jo_global";
		var data_nya = { id_supp: id_supp,jenis_item: jenis_item,id_jo: id_jo };				
    var html = $.ajax
    ({  type: "POST",
        url: url_nya,
        data: data_nya,
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }



    $(document).ready(function() {
      var table = $('#examplefix2').DataTable
      ({  scrollCollapse: true,
          paging: false,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });
    $(".select2").select2();
  };



function getListDataHeader($id) {
	return new Promise(function (resolve, reject) {
		$.ajax({
			type: "POST",
			url: "webservices/GetListDataPoDraft_Form.php",
			data: { code: '1', id: $id },     // multiple data sent using ajax
			success: function (response) {
				data = response;
				//console.log(data);
				d = JSON.parse(data);
				//console.log(d.message);
				if (d.status == 'ok') {
					// alert($id)
					$data.id = decodeURIComponent(d.records[0].id);
					$G_array = d;
					$("#ws").val(decodeURIComponent(d.records[0].ws)).trigger("change");
					resolve(d);
				}
				else {
					alert('getListDataHeader')
				}
			}
		});
	});
}
  
  function select_all()
  {
	var chks = document.form.getElementsByClassName('chkclass');
    for (var i = 0; i < chks.length; i++) 
    { chks[i].checked = true; }
  };





  function calc_amt_po(that)



  {







  };







  function calc_amt_po_err(that)

	{ 



    // INI ID JO DAN ID GEN JADI SAMA KLO ADA BEBERAPA JO



		var qtyo = document.form.getElementsByClassName('qtyclass'); 
	 // var pxo = document.form.getElementsByClassName('priceclass'); 
	  var totamto = document.form.getElementsByClassName('totamtclass'); 
	  var curr = document.form.txtcurr.value;
	  var tanggalnya = $("#datepicker1").val();
	  classprice = that.className;
	  console.log(that.name);
	  if(curr == "IDR"){
		 $("input[name='"+that.name+"']").siblings().val(that.value);
		 // $("."+classprice).parent('<td>').child('.totamtclass').val(tanggalnya);
		  return false;
	  } 

	  var totqty = 0;
//	  for (var i = 0; i < qtyo.length; i++) 

//	  {
		//..  if (Number(pxo[i].value) > 0)

	   // { 
	    	var pxnya = that.value;
	      var totamtnya;
	      jQuery.ajax
		    ({  
		      url: "../forms/ajax3.php?modeajax=conv_amt_to_usds",
		      method: 'POST',
		      data: {pricenya: pxnya,currnya: curr,tglnya :tanggalnya},
		      dataType: 'json',
		      success: function(response)
		      { 
		      	totamtnya = response;
				$("input[name='"+that.name+"']").siblings().val(totamtnya[0]);
		      	//console.log(totamtnya);
		     	},
		      error: function (request, status, error) 
		      { alert(request.responseText); },
		    });
		//    totamto[i].value = totamtnya[0];
	   // }
	};




function Save(){
		$data.mod 			=$mod;	
		$data.txtdraftpo    =$('#txtdraftpo').val();
		$data.txtdrafpodate =$('#datepicker1').val(); 
		$data.jenis_item    =$('#jenis_item').val();
		$data.cbosupp       =$('#cbosupp').val();
		$data.curr          =$('#curr').val();
		$data.txtid_terms   =$('#txtid_terms').val();
		$data.txtdays       =$('#txtdays').val();
		$data.txtid_dayterms=$('#txtid_dayterms').val(); 
		$data.txtetddate    =$('#datepicker2').val();
		$data.txtetadate    =$('#datepicker3').val();
		$data.txtexpdate    =$('#datepicker4').val();
		$data.txtdisc       =$('#txtdisc').val();
		$data.ppn_nya       =$('#ppn_nya').val();
		$data.txtnotes      =$('#txtnotes').val();
		$data.cboJO         =$('#cboJO').val();
		$data.pkp           =$('#pkp').val();
		$data.triger_ppn    =$('#triger_ppn').val();
		$data.n_kurs        =$('#n_kurs').val();
		$data.txt_tipecom   =$('#txt_tipecom').val();		

var unitkos = 0;
    var qtykos 		= 0;
    var qtyover		= 0;
    var qtymin 		= 0;
	var prices		= 0;
    var chks 		= document.form.getElementsByClassName('chkclass');
    var units 		= document.form.getElementsByClassName('unitclass');
    var qtys 		= document.form.getElementsByClassName('qtyclass');
    var qtybtss 	= document.form.getElementsByClassName('qtybtsclass'); //priceclass idjoclass document.querySelector('input[name=hey]').value
	var price 		= document.form.getElementsByClassName('priceclass');
	var idjoclass 	= document.form.getElementsByClassName('idjoclass');
	var itembb		= document.form.getElementsByClassName('itembbclass');
	var idpoi		= document.form.getElementsByClassName('id_poiclass');
		pilih 	= 0;
	if(typeof chks=== 'undefined'){
		chks = 0;
	}	
    for (var i = 0; i < chks.length; i++)
		{
			if (chks[i].checked) 
				{ pilih = pilih + 1;
					if (units[i].value == '')
					{ unitkos = unitkos + 1; }
					if (qtys[i].value == '' || qtys[i].value <= 0)
					{ qtykos = qtykos + 1; }
					if (parseFloat(qtys[i].value) > (parseFloat(qtybtss[i].value) + 1 ) )
					{ qtyover = qtyover + 1; }
					if (qtys[i].value < 0)
					{ qtymin = qtymin + 1; }
					if (price[i].value =='' || price[i].value< 1)
					{ prices = prices + 1; }				
				}
				console.log(qtys[i].value);
				console.log(qtybtss[i].value);
		}
		
/* 			console.log(qtyover);
		return false;	
		console.log($data.txtdrafpodate); */
	if(!$data.txtdrafpodate){
		swal({ title: 'Tanggal Draft/PO Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
	}			
			if($data.txtdrafpodate == ''){
		swal({ title: 'Tanggal Draft/PO Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
	}	
	if($data.jenis_item ==''){
		swal({ title: 'Jenis Item Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
		
	}	
	if($data.cbosupp ==''){
		swal({ title: 'Supplier Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
		
	}	
	if($data.curr ==''){
		swal({ title: 'Currency Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
		
	}	
	if($data.txtid_terms ==''){
		swal({ title: 'Payemnt Terms Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
	}		
	if($data.txtdays ==''){
		swal({ title: 'Day Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
	}	
	if($data.txtid_dayterms ==''){
		swal({ title: 'Day Terms Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
	}
	if($data.txt_tipecom ==''){
		swal({ title: 'Tipe Commersial Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
	}				
	
	if(!$split_nya[2]){
		
		
		if(!$data.cboJO){
			swal({ title: 'Jo Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
		}				
		if($data.cboJO ==''){
			swal({ title: 'Jo Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
		}				
		if(pilih =='0'){
			swal({ title: 'Tiada Data Yang dipilih', imageUrl: '../../images/error.jpg' }); return false;;
		}		
		if(unitkos >0){
			swal({ title: 'Currency Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
		}	
		if(qtykos >0){
			swal({ title: 'Qty Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
		}	
		if(qtymin >0){
			swal({ title: 'Qty Tidak Boleh Lebih Kecil Nol', imageUrl: '../../images/error.jpg' }); return false;;
		}	
/* 		if(qtyover >0){
			swal({ title: 'Qty Melebihi Kebutuhan', imageUrl: '../../images/error.jpg' }); return false;; //success
		} */
/* 		if(prices >0){
			swal({ title: 'Price Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;; //success
		} */		
/* 		console.log(qtyover);
		return false; */
	}
	detail = {
		itemchk	 	: [],
		itembb 		: [],
		idjo		: [],
		itemunit	: [],
		itemqty 	: [],
		itemprice 	: [],
		id_poi		: [],
	};
	//GET DATA DETAIL
    for (var i = 0; i < chks.length; i++)
		{
			if (chks[i].checked) 
				{
					detail.itemchk.push(chks[i].value);
					detail.itembb.push(itembb[i].value);
					detail.idjo.push(idjoclass[i].value);
					detail.itemunit.push(units[i].value);
					detail.itemqty.push(qtys[i].value);
					detail.itemprice.push(price[i].value);
					if($G_kondisi == '1'){
						detail.id_poi.push(idpoi[i].value);
					}				
				}
		}
		$("#myOverlay").css("display","block");
		
	$data.format = format;	
	if($G_kondisi == '1'){
		$data.id = $G_id_url;
	}
		
	$.ajax("webservices/save_po_bw_form.php",
		{
			type: "POST",
			data :{header:$data, detail:detail},
			success: function (data) {
				
				data =jQuery.parseJSON(data);
				if(data.respon){
					console.log(data.respon == '200');
					swal({ title: data.message,  imageUrl: '../../images/success.jpg'});  //success
									
				}else{
					swal({ title: data.message, imageUrl: '../../images/error.jpg' }); //success
				}
				setTimeout(function(){
					$("#myOverlay").css("display","none");
					window.location.href="./?mod=3L";	
				},3000)
			},
			error: function (data) {
				alert("Error req");
			}
		});	
}

function Save_global(){
		$data.mod 			=$mod;	
		$data.txtdraftpo    =$('#txtdraftpo').val();
		$data.txtdrafpodate =$('#datepicker1').val(); 
		$data.jenis_item    =$('#jenis_item').val();
		$data.cbosupp       =$('#cbosupp').val();
		$data.curr          =$('#curr').val();
		$data.txtid_terms   =$('#txtid_terms').val();
		$data.txtdays       =$('#txtdays').val();
		$data.txtid_dayterms=$('#txtid_dayterms').val(); 
		$data.txtetddate    =$('#datepicker2').val();
		$data.txtetadate    =$('#datepicker3').val();
		$data.txtexpdate    =$('#datepicker4').val();
		$data.txtdisc       =$('#txtdisc').val();
		$data.ppn_nya       =$('#ppn_nya').val();
		$data.txtnotes      =$('#txtnotes').val();
		$data.cboJO         =$('#cboJO').val();
		$data.pkp           =$('#pkp').val();
		$data.triger_ppn    =$('#triger_ppn').val();
		$data.n_kurs        =$('#n_kurs').val();
		$data.txt_tipecom   =$('#txt_tipecom').val();		

var unitkos = 0;
    var qtykos 		= 0;
    var qtyover		= 0;
    var qtymin 		= 0;
	var prices		= 0;
    var chks 		= document.form.getElementsByClassName('chkclass');
    var units 		= document.form.getElementsByClassName('unitclass');
    var qtys 		= document.form.getElementsByClassName('qtyclass');
    var qtybtss 	= document.form.getElementsByClassName('qtybtsclass'); //priceclass idjoclass document.querySelector('input[name=hey]').value
	var price 		= document.form.getElementsByClassName('priceclass');
	var idjoclass 	= document.form.getElementsByClassName('idjoclass');
	var itembb		= document.form.getElementsByClassName('itembbclass');
	var idpoi		= document.form.getElementsByClassName('id_poiclass');
		pilih 	= 0;
	if(typeof chks=== 'undefined'){
		chks = 0;
	}	
    for (var i = 0; i < chks.length; i++)
		{
			if (chks[i].checked) 
				{ pilih = pilih + 1;
					if (units[i].value == '')
					{ unitkos = unitkos + 1; }
					if (qtys[i].value == '' || qtys[i].value <= 0)
					{ qtykos = qtykos + 1; }
					if (parseFloat(qtys[i].value) > (parseFloat(qtybtss[i].value) + 1 ) )
					{ qtyover = qtyover + 1; }
					if (qtys[i].value < 0)
					{ qtymin = qtymin + 1; }
					if (price[i].value =='' || price[i].value< 1)
					{ prices = prices + 1; }				
				}
				console.log(qtys[i].value);
				console.log(qtybtss[i].value);
		}
		
/* 			console.log(qtyover);
		return false;	
		console.log($data.txtdrafpodate); */
	if(!$data.txtdrafpodate){
		swal({ title: 'Tanggal Draft/PO Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
	}			
			if($data.txtdrafpodate == ''){
		swal({ title: 'Tanggal Draft/PO Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
	}	
	if($data.jenis_item ==''){
		swal({ title: 'Jenis Item Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
		
	}	
	if($data.cbosupp ==''){
		swal({ title: 'Supplier Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
		
	}	
	if($data.curr ==''){
		swal({ title: 'Currency Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
		
	}	
	if($data.txtid_terms ==''){
		swal({ title: 'Payemnt Terms Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
	}		
	if($data.txtdays ==''){
		swal({ title: 'Day Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
	}	
	if($data.txtid_dayterms ==''){
		swal({ title: 'Day Terms Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
	}
	if($data.txt_tipecom ==''){
		swal({ title: 'Tipe Commersial Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
	}				
	
	if(!$split_nya[2]){
		
		
		if(!$data.cboJO){
			swal({ title: 'Jo Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
		}				
		if($data.cboJO ==''){
			swal({ title: 'Jo Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
		}				
		if(pilih =='0'){
			swal({ title: 'Tiada Data Yang dipilih', imageUrl: '../../images/error.jpg' }); return false;;
		}		
		if(unitkos >0){
			swal({ title: 'Currency Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
		}	
		if(qtykos >0){
			swal({ title: 'Qty Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;;
		}	
		if(qtymin >0){
			swal({ title: 'Qty Tidak Boleh Lebih Kecil Nol', imageUrl: '../../images/error.jpg' }); return false;;
		}	
/* 		if(qtyover >0){
			swal({ title: 'Qty Melebihi Kebutuhan', imageUrl: '../../images/error.jpg' }); return false;; //success
		} */
/* 		if(prices >0){
			swal({ title: 'Price Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); return false;; //success
		} */		
/* 		console.log(qtyover);
		return false; */
	}
	detail = {
		itemchk	 	: [],
		itembb 		: [],
		idjo		: [],
		itemunit	: [],
		itemqty 	: [],
		itemprice 	: [],
		id_poi		: [],
	};
	//GET DATA DETAIL
    for (var i = 0; i < chks.length; i++)
		{
			if (chks[i].checked) 
				{
					detail.itemchk.push(chks[i].value);
					detail.itembb.push(itembb[i].value);
					detail.idjo.push(idjoclass[i].value);
					detail.itemunit.push(units[i].value);
					detail.itemqty.push(qtys[i].value);
					detail.itemprice.push(price[i].value);
					if($G_kondisi == '1'){
						detail.id_poi.push(idpoi[i].value);
					}				
				}
		}
		$("#myOverlay").css("display","block");
		
	$data.format = format;	
	if($G_kondisi == '1'){
		$data.id = $G_id_url;
	}
		
	$.ajax("webservices/save_po_global_bw_form.php",
		{
			type: "POST",
			data :{header:$data, detail:detail},
			success: function (data) {
				
				data =jQuery.parseJSON(data);
				if(data.respon){
					console.log(data.respon == '200');
					swal({ title: data.message,  imageUrl: '../../images/success.jpg'});  //success
									
				}else{
					swal({ title: data.message, imageUrl: '../../images/error.jpg' }); //success
				}
				setTimeout(function(){
					$("#myOverlay").css("display","none");
					window.location.href="./?mod=po_header";	
				},3000)
			},
			error: function (data) {
				alert("Error req");
			}
		});	
}




function cancel_poi(id_poi){
	$.ajax("webservices/cancel_po_item.php",
		{
			type: "POST",
			data :{id_poi:id_poi},
			success: function (data) {
				
				data =jQuery.parseJSON(data);
				if(data.respon){
					swal({ title: data.message,  imageUrl: '../../images/success.jpg'});  //success
					getJO();
									
				}else{
					swal({ title: data.message, imageUrl: '../../images/error.jpg' }); //success
				}

			},
			error: function (data) {
				alert("Error req");
			}
		});		
}

