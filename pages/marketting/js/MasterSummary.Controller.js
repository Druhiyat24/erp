$( document ).ready(function() {

	
	
url =window.location.search;
console.log(url);
myCase = url.split("&id=");	
console.log(myCase[1]);
if(myCase[1]){
	GetDetailData(myCase[1]);
}	
	
	console.log(sessionStorage);
if(sessionStorage.filterCategory){
	$("#categoryFilter").val(sessionStorage.filterCategory);
	datastorage = { value:'' }
	//filterCategory(sessionStorage.filterCategory);
	setTimeout(function(){
		datastorage.value =sessionStorage.filterCategory;
	filterCategory(datastorage);
},100);
	
}
		data = {
			subtotalusd 			:0,
			subtotalidr 			:0,
			confirmprice 			:0,
			confirmpriceidr			:0,
			confirmpriceusd			:0,
			totalcostingidr			:0,
			totalcostingusd			:0,
			percentgacost 			:0,
			valuegacostidr 			:0,
			valuegacostusd 			:0,
			percentvat	 			:0,
			valuevatidr 			:0,
			grandtotalidr 			:0,
			grandtotalusd 			:0,
			valuevatusd 			:0,			
			percentcommissionfee 	:0,
			valuecommissionfeeusd 	:0,
			valuecommissionfeeidr 	:0,
			percentdealallowanceidr :0,
			percentdealallowanceusd :0,
			valuedealallowanceidr	:0,
			valuedealallowanceusd	:0,
		}

		
		

  $.fn.inputFilter = function(inputFilter) {
    return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      }
    });
  };
$("#confirmPrice").inputFilter(function(value) {
  return /^-?\d*[.]?\d*$/.test(value); });
		//myFat

$("#myFat").inputFilter(function(value) {
  return /^-?\d*[.]?\d*$/.test(value); });		
		
	//comFee


$("#gaCost").inputFilter(function(value) {
  return /^-?\d*[.,]?\d*$/.test(value); });		
		

$("#comFee").inputFilter(function(value) {
  return /^-?\d*[.,]?\d*$/.test(value); });		
	//txtsmv_min


$("#txtsmv_min").inputFilter(function(value) {
  return /^-?\d*[.,]?\d*$/.test(value); });	
	
//txtqty	

$("#txtsmv_sec").inputFilter(function(value) {
  return /^-?\d*[.,]?\d*$/.test(value); });	
		
		

$("#txtqty").inputFilter(function(value) {
  return /^-?\d*[.,]?\d*$/.test(value); });	
				//confirmPrice


$("#txtprice_cd").inputFilter(function(value) {
  return /^-?\d*[.,]?\d*$/.test(value); });	
				//confirmPrice txtprice_idr_cd
$("#txtprice_idr_cd").inputFilter(function(value) {
  return /^-?\d*[.,]?\d*$/.test(value); });					


$("#txtprice_mf").inputFilter(function(value) {
  return /^-?\d*[.,]?\d*$/.test(value); });	
				//confirmPrice txtprice_idr_cd
$("#txtprice_idr_mf").inputFilter(function(value) {
  return /^-?\d*[.,]?\d*$/.test(value); });		


$("#txtprice_ot").inputFilter(function(value) {
  return /^-?\d*[.,]?\d*$/.test(value); });	
				//confirmPrice txtprice_idr_cd
$("#txtprice_idr_ot").inputFilter(function(value) {
  return /^-?\d*[.,]?\d*$/.test(value); });		
	
	//initial();
	
});



function GetDetailData(ids){
	//date = document.form.txtcost_date.value;
	    $.ajax({		
		type:"POST",
		cache:false,
        url:"ajax_sum_cost.php",
		data: {data:{id : ids }},    // multiple data sent using ajax
		success: function (response) {
			d = JSON.parse(response);
			console.log(d.records);
			if(d.status == 'ok'){	
				data.subtotalusd 			  = decodeURIComponent(d.records[0].total_cost)
				data.subtotalidr 			  = decodeURIComponent(d.records[0].total_cost_idr)
				data.confirmprice 			  = decodeURIComponent(d.records[0].cfm_price) 
				data.confirmpriceidr		  = decodeURIComponent(d.records[0].cfm_price_idr)
				data.confirmpriceusd		  = decodeURIComponent(d.records[0].cfm_price_usd)
				data.totalcostingidr		  = decodeURIComponent(d.records[0].total_cost_idr)
				data.totalcostingusd		  = decodeURIComponent(d.records[0].total_cost)
				data.percentgacost 			  = decodeURIComponent(d.records[0].ga)
				data.valuegacostidr 		  = decodeURIComponent(d.records[0].total_ga_cost)
				data.valuegacostusd 		  = decodeURIComponent(d.records[0].total_ga_cost_idr)
				data.percentvat	 			  = decodeURIComponent(d.records[0].vat)
				data.valuevatidr 			  = decodeURIComponent(d.records[0].total_vat_idr)
				data.grandtotalidr 			  = decodeURIComponent(d.records[0].total_cost_plus_idr)
				data.grandtotalusd 			  = decodeURIComponent(d.records[0].total_cost_plus)
				data.valuevatusd 			  = decodeURIComponent(d.records[0].total_vat)
				data.percentcommissionfee 	  = decodeURIComponent(d.records[0].com_fee)
				data.valuecommissionfeeusd 	  = decodeURIComponent(d.records[0].com_fee_usd)
				data.valuecommissionfeeidr 	  = decodeURIComponent(d.records[0].com_fee_idr)
				data.percentdealallowanceidr  = decodeURIComponent(d.records[0].deal)
				data.percentdealallowanceusd  = decodeURIComponent(d.records[0].deal)
				data.valuedealallowanceidr	  = decodeURIComponent(d.records[0].total_deal_idr)
               data.valuedealallowanceusd	  = decodeURIComponent(d.records[0].total_deal)

console.log(data);
$("#footerConfirmPriceIdr").text(formatRupiah(data.confirmpriceidr));
$("#footerConfirmPriceUsd").text(formatRupiah(data.confirmpriceusd));	
$("#footerTotalCosting").text(formatRupiah(data.subtotalusd));
$("#footerTotalCostingIdr").text(formatRupiah(data.subtotalidr));	
$("#footerCommissionFee").text(formatRupiah(data.valuecommissionfeeusd));
$("#footerCommissionFeeidr").text(formatRupiah(data.valuecommissionfeeidr));
$("#footergacost").text(formatRupiah(data.valuegacostusd));
$("#footergacostidr").text(formatRupiah(data.valuegacostidr));
$("#footerVatUsd").text(formatRupiah(data.valuevatusd));
$("#footerVatIdr").text(formatRupiah(data.valuevatidr));
$("#footerGrandTotal").text(formatRupiah(data.grandtotalusd));
$("#footerGrandTotalIdr").text(formatRupiah(data.grandtotalidr));
$("#footerAllowanceValUsd").text(formatRupiah(data.valuedealallowanceusd));
$("#footerAllowanceValIdr").text(formatRupiah(data.valuedealallowanceidr));
$("#footerAllowancepercent").text(formatRupiah(data.percentdealallowanceidr));
$("#footerAllowancepercentIdr").text(formatRupiah(data.percentdealallowanceidr));
$("#myDeal").val(data.percentdealallowanceidr);


								
			}			
			
        }
      });
	
	
}


/* 

						<option value="1">Costing Detail</option>
						<option value="2">Manufacturing - Complexity</option>
						<option value="3">Other Cost</option>

*/

function filterCategory(Data){
	id = {
		value:''
	}
	
	$("#category").css("display","none");
$("#category").empty();
console.log(Data.value);


var generateoption = '';
if(Data.value == "Costing Detail"){
	generateoption += "<option value='-1'>--Choose Category--</option>";
	generateoption += "<option value='1'>Costing Detail</option>";
$("#category").append(generateoption);
id.value="1";
changecategory(id);
$("#category").val(id.value);
}
else if(Data.value == "Manufacturing - Complexity"){
	generateoption += "<option value='-1'>--Choose Category--</option>";
	generateoption += "<option value='2'>Manufacturing - Complexityl</option>";
$("#category").append(generateoption);
id.value="2";
changecategory(id);
$("#category").val(id.value);
}
else if(Data.value == "Other Cost"){
	generateoption += "<option value='-1'>--Choose Category--</option>";
	generateoption += "<option value='3'>Other Cost</option>";
$("#category").append(generateoption);
id.value="3";
changecategory(id);
$("#category").val(id.value);
}
else if(Data.value == "-1"){
	$("#categoryFilter").val("-1");
	Data.value = "";
	$("#category").val(id.value);


id.value="-1";
changecategory(id);	
}

if(Data.value != "Costing Detail" && Data.value != "Manufacturing - Complexity" && Data.value != "Other Cost" ){
 $("#category").css("display","none");	
	
	
}
$('#example1').DataTable().column(0).search( Data.value ).draw();
sessionStorage.setItem("filterCategory",Data.value);
console.log("SESSION:"+sessionStorage);
}




function getSumTotal(){

	
}
function comissionfee(){
	console.log("Begin CommisionFee");
	data.valuecommissionfeeusd = ((data.percentcommissionfee)/100) * data.confirmpriceusd;
	data.valuecommissionfeeidr = ((data.percentcommissionfee)/100) * data.confirmpriceidr;
	console.log(((data.percentcommissionfee)/100) * data.confirmpriceidr);
	console.log("End CommisionFee");
	}
function gacost(){
	console.log("Begin GaCost");
	data.valuegacostusd = ((data.percentgacost)/100) * parseInt(data.subtotalusd);
console.log(((data.percentgacost)/100)+" *"+data.subtotalidr);
	data.valuegacostidr = ((data.percentgacost)/100) * data.subtotalidr;	
	console.log("END GaCost");
	}
function totalcosting(){
	console.log("Begin totalcosting");
	data.totalcostingidr = parseFloat(data.valuegacostidr) + parseFloat(data.subtotalidr) + parseFloat(data.valuecommissionfeeidr);
	data.totalcostingusd = (data.valuegacostusd) + parseFloat(data.subtotalusd) + (data.valuecommissionfeeusd);
console.log(data.totalcostingusd );
	console.log("Begin totalcosting");
	}
function grandtotal(){
	
	console.log("Begin Grand Total");
	data.grandtotalidr = data.totalcostingidr + data.valuevatidr;
	data.grandtotalusd = data.totalcostingusd + data.valuevatusd;
	console.log(data.totalvatidr);
	console.log("End Grand Total");
	}



function dealallowance(){
	console.log("Begin Margin Deallowance");
	data.valuedealallowanceidr = data.confirmpriceidr - data.totalcostingidr;
	data.valuedealallowanceusd = data.confirmpriceusd - data.totalcostingusd;
	
	

	data.percentdealallowanceidr = (data.valuedealallowanceidr / data.confirmpriceidr)*100;
	data.percentdealallowanceusd = (data.valuedealallowanceusd / data.confirmpriceusd)*100;
console.log(data.valuedealallowanceusd);
	console.log("End Margin Deallowance");
	}	
	

function confirmprice(){
	var c_tag = $('#textCurr').val()
	if(c_tag == "USD"){
		GetUsdIdr();
	}
	if(c_tag == "IDR"){
		GetIdrUsd();
	}	
}



function vat(){
	console.log("Begin Vat");
	data.valuevatidr = (data.percentvat/100)*parseInt(data.totalcostingidr);
	data.valuevatusd =  (data.percentvat/100)*parseInt(data.totalcostingusd);
	console.log(data.valuevatidr);
	console.log("End Vat");
}	


//1. STEP 1 GET NILAI CONFIRM PRICE
//setTimeout(function(){
	//confirmprice();
//},1000);
//setTimeout(function(){
	
	//RunFormula();
	
//},3000)


function initial(){
	data.percentgacost = $("#gaCost").val();
	$("#footergapercent").text(data.percentgacost);
	
	//data.percentvat = $("#myFat").val();
	$("#footervatpercent").text(data.percentvat);
	data.confirmprice = $("#confirmPrice").val();
	data.subtotalusd = $('#footerSubtotal').text();
	data.subtotalusd = data.subtotalusd.replace(",", "");
	data.subtotalusd = data.subtotalusd.replace(",", "");
	data.subtotalusd = data.subtotalusd.replace(",", "");
	data.subtotalidr = $('#footerSubtotalIdr').text();
	data.subtotalidr = data.subtotalidr.replace(",", "");
	data.subtotalidr = data.subtotalidr.replace(",", "");
	data.subtotalidr = data.subtotalidr.replace(",", "");
	console.log(data.subtotalidr);
	data.percentcommissionfee = $('#comFee').val();	
	$('#footercommissionfee').text(data.percentcommissionfee);
}


function handleCh(data){
	if (data.id == 'confirmPrice'){
		data.confirmprice = $("#confirmPrice").val();
	}
	if (data.id == 'myFat'){
		data.percentvat = $("#myFat").val();
	}	
	if (data.id == 'gaCost'){
		data.percentgacost = $("#gaCost").val();
	}
	if (data.id == 'comFee'){
		data.percentcommissionfee = $("#comFee").val();
	}	
	setTimeout(function(){
		initial();
	},1000);

	setTimeout(function(){
		confirmprice();
	},3000);		
	
	setTimeout(function(){
		RunFormula();
	},5000);	
	
}


function RunFormula(){
		  if(data.subtotalidr < 1  ){
			  $('#myDeal').val(0);
			    $('#footerAllowance').val(0);
				$("#footerAllowanceidr").val(0);
			  return false;
			  
			  
		  }
		  else{

setTimeout(function(){
	comissionfee();
	gacost();
	totalcosting();
	vat();
	grandtotal();
	dealallowance();
	console.log(data);
	},2000)		

	

setTimeout(function(){
	
	if(data.subtotalidr > 0){
		$("#myDeal").val(formatRupiah(data.percentdealallowanceidr));
	}
else{
	$("#myDeal").val(0);
	
}
	
$("#footerConfirmPriceIdr").text(formatRupiah(data.confirmpriceidr));
$("#footerConfirmPriceUsd").text(formatRupiah(data.confirmpriceusd));	
$("#footerTotalCosting").text(formatRupiah(data.totalcostingusd.toFixed(4)));
$("#footerTotalCostingIdr").text(formatRupiah(data.totalcostingidr.toFixed(4)));	
$("#footerCommissionFee").text(formatRupiah(data.valuecommissionfeeusd));
$("#footerCommissionFeeidr").text(formatRupiah(data.valuecommissionfeeidr));
$("#footergacost").text(formatRupiah(data.valuegacostusd));
$("#footergacostidr").text(formatRupiah(data.valuegacostidr));
$("#footerVatUsd").text(formatRupiah(data.valuevatusd));
$("#footerVatIdr").text(formatRupiah(data.valuevatidr));
$("#footerGrandTotal").text(formatRupiah(data.grandtotalusd));
//$("#footerGrandTotalIdr").text(formatRupiah(data.grandtotalidr));
//$("#footerAllowanceValUsd").text(formatRupiah(data.valuedealallowanceusd));
$("#footerAllowanceValIdr").text(formatRupiah(data.valuedealallowanceidr));
$("#footerAllowancepercent").text(formatRupiah(data.percentdealallowanceidr));
$("#footerAllowancepercentIdr").text(formatRupiah(data.percentdealallowanceidr));

console.log(data);
},8000);	  
			  
		  }
	}
function handleCurrNew(datas){
	if(datas.value == "IDR"){
		//handleCh
		$("#confirmPrice").val(data.confirmpriceidr);
		data.confirmfrice = data.confirmpriceidr;
		//alert(data.confirmpriceidr);
		console.log(data);
		//alert(data);
	}else if(datas.value == "USD"){
		$("#confirmPrice").val(data.confirmpriceusd);
		data.confirmfrice = data.confirmpriceusd;
	}
	
}
		function formatRupiah(bilangan){
	console.log(bilangan);		
bilangan = parseFloat(bilangan);
bilangan = bilangan.toFixed(4);			
			
			
var	number_string = bilangan.toString(),
	split	= number_string.split('.'),
	sisa 	= split[0].length % 3,
	rupiah 	= split[0].substr(0, sisa),
	ribuan 	= split[0].substr(sisa).match(/\d{1,3}/gi);
if (ribuan) {
	separator = sisa ? ',' : '';
	
	rupiah += separator + ribuan.join(',');
}

rupiah = split[1] != undefined ? rupiah + '.' + split[1] : rupiah;	
rupiah = rupiah.replace("-,", "-");
return rupiah;
		
		}
		function formatDollar(bilangan){

bilangan = parseFloat(bilangan);
bilangan = bilangan.toFixed(4);			
						
		return bilangan	
			
			
		}
function setDealAllowance(){
	//hitung GACOST
		  if(data.subtotalidr == '0'  ){
			  $('#myDeal').val(0);
			    $('#footerAllowance').val(0);
				$("#footerAllowanceidr").val(0);
			  return false;
			  
			  
		  }
				if(data.subtotalidr == '0'  ){
					 $('#myDeal').val(0);
					 $('#footerAllowance').val(0);
					 $("#footerAllowanceidr").val(0);
			  return false;  
		  }	
	
		   cp = $("#confirmPrice").val();
		  $('#footerConfirmPrice').text(crr);
		  var crr = $("#textCurr").val();
		   $('#footerCurr').text(crr);
		  if(crr == "USD"){
			  GetUsdIdr();
		  } 
		  if(crr == "IDR"){
			  GetIdrUsd();  
		  }
		   
	
	
	myGaCost = $('#gaCost').val();
	myComFee = $('#comFee').val();
	myCurr = $('#textCurr').val();
	if(myGaCost == ''){
		myGaCost = 0
		$('#gaCost').val('0');
	}
	if(myComFee == ''){
		myComFee = 0
		$('#comFee').val('0');
	}
	
	
	data.confirmprice = $('#confirmPrice').val();
	 $('#footerConfirmPriceUsd').text(formatDollar(data.confirmpriceusd, ' '));
	 $('#footerConfirmPriceIdr').text(formatRupiah(data.confirmpriceidr, ' '));
	 
		  var crr = $("#textCurr").val();
		   $('#footerCurr').text(crr);	 
	 		  var cff = $("#comFee").val();
		   $('#footerCommissionFee').text(cff);	
		   $('#footerCommissionFeeidr').text(cff);
		   
	//$('#footerConfirmPriceUsd').val(data.confirmprice);
	var NANCINFIRMPRICE = parseFloat(data.confirmprice) * 1;
	var NANCOST = myGaCost * 1;
	var NANCOMFEE = myComFee * 1;
	data.gaCost 
	if(Number.isNaN(NANCOST)){
	$('#gaCost').val(0);
	return false;
	}
	if(Number.isNaN(NANCOMFEE)){
	$('#comFee').val(0);
	return false;
	}
	if(Number.isNaN(NANCINFIRMPRICE)){
	$('#confirmPrice').val(0);
	$('#footerConfirmPrice').val(0);
	return false;
	}	
	//HITUNG GA COST
	//	data.percentgacost = myGaCost;
	//	data.valuegacost = parseFloat(data.percentgacost)/100;
	//HITUNG COMMISSION FEE	
	//	data.percentcommissionfee = myComFee;
	//	data.valuecommissionfee  = parseFloat(data.percentcommissionfee)/100;
	//CHECK 
	
	//VAT
	//	var totalcostingusd = $("#footerTotalCosting").text();
	//	var totalcostingidr =$("#footerTotalCostingIdr").text();
	//	totalcostingidr = totalcostingidr.replace(",", "");
	//	totalcostingidr = totalcostingidr.replace(",", "");
	//	totalcostingidr = parseFloat(totalcostingidr);
	//	var vatpersen = $("#myFat").val();
	//	var valval = 1 + (vatpersen/100);
		
		//IDR
	//		var sumvatpersentusd = totalcostingusd * valval
	//		console.log(sumvatpersentusd +"="+  totalcostingusd + "*" + valval );
		//USD
	//		var sumvatpersentidr = totalcostingidr * valval
	//	$("#footerVatUsd").text(sumvatpersentusd.toFixed(2));
	//	$("#footerVatIdr").text(sumvatpersentidr.toFixed(2));
		
	//VAT
	
	
	
	if(myCurr == 'IDR'){
		console.log(data);
		data.valuedealallowance	= (parseFloat(data.confirmpriceidr)) - (parseFloat(data.subtotalidr)) - (parseFloat(data.valuegacost)) - (parseFloat(data.valuecommissionfee));
		data.percentdealallowance = ((parseFloat(data.valuedealallowance))/(parseFloat(data.confirmpriceidr)))*100;		
		$('#myDeal').val(data.percentdealallowance.toFixed(2));
		$('#footerAllowanceidr').text(data.percentdealallowance.toFixed(2));
		$('#footerAllowance').text(data.percentdealallowance.toFixed(2));
		$('#footerAllowanceValIdr').text(data.valuedealallowance.toFixed(2))
		
		
		console.log("HITUNGAN IDR");
	}
	if(myCurr == 'USD'){
		console.log(data);
		data.valuedealallowance	= (parseFloat(data.confirmpriceusd)) - (parseFloat(data.subtotalusd)) - (parseFloat(data.valuegacost)) - (parseFloat(data.valuecommissionfee));
		
		console.log(data.valuedealallowance);
		data.percentdealallowance = ((parseFloat(data.valuedealallowance))/(parseFloat(data.confirmpriceusd)))*100;	
		console.log(data.confirmpriceusd);
		$('#myDeal').val(data.percentdealallowance.toFixed(2));
		$('#footerAllowance').text(data.percentdealallowance.toFixed(2));  
		$('#footerAllowanceidr').text(data.percentdealallowance.toFixed(2)); 
		$('#footerAllowanceValUsd').text(data.valuedealallowance.toFixed(2))
		$('#footerAllowanceValIdr').text(data.valuedealallowance.toFixed(2))
		console.log("HITUNGAN USD");
	}	
}
function GetIdrUsd(){
	date = document.form.txtcost_date.value;
	    $.ajax({		
		type:"POST",
		cache:false,
        url:"ajax_master_currency.php",
		data: {data:{price : data.confirmprice, date : date,type:'IDR' }},    // multiple data sent using ajax
		success: function (response) {
			d = JSON.parse(response);
			if(d.status == 'ok'){	
				d.records['hasil'];
				data.confirmpriceusd = d.records['hasilbeli'];
				data.confirmpriceidr = data.confirmprice;
				//comissionfee();				
			}			
			
        }
      });
	
	
}
function GetUsdIdr(){
	date = document.form.txtcost_date.value;
    $.ajax({		
		type:"POST",
        url:"ajax_master_currency.php",
		data: {data:{price : data.confirmprice, date : date,type:'USD' }},    // multiple data sent using ajax
		success: function (response) {
			d = JSON.parse(response);
			console.log(d.records);
			if(d.status == 'ok'){	
				d.records['hasil'];
				
				data.confirmpriceidr = d.records['hasiljual'][0];
				data.confirmpriceusd = data.confirmprice;
				//comissionfee();
			}			
			
        }
      });	
}


  
   Category = '';

  function changecategory(Item){
	  Category = Item.value;
	  if(Item.value == '1'){
		$(".selectCD").css("display","block");
		$(".selectMC").css("display","none");
		$(".selectOT").css("display","none");
	  }
	  if(Item.value == '2'){
		  $(".selectCD").css("display","none");
		 $(".selectMC").css("display","block");
		  $(".selectOT").css("display","none");	  
	  }
	  if(Item.value == '3'){
	  $(".selectCD").css("display","none");
		 $(".selectMC").css("display","none");
		  $(".selectOT").css("display","block");
	  }
	  if(Item.value == '-1'){
		$(".selectCD").css("display","none");
		$(".selectMC").css("display","none");
		$(".selectOT").css("display","none");		  
		  
		  
	  }
	  



  }
  
  	function Routes(Data){
		event.preventDefault();		
		if(Category == '1'){
				add_cd(Data);
				console.log(Category);
				console.log('1');
		}
		if(Category == '2'){
				add_mf(Data);
				console.log(Category);
				console.log('1');
		}
		if(Category == '3'){
			add_ot(Data);
			console.log(Category);
			console.log('1');
		}			
	}  
  	function RoutesEdit(Data){
		event.preventDefault();
		kategori = $('#val_c').val();
		console.log(kategori);
		if(kategori == '1'){
				add_cd(Data)
		}
		if(kategori == '2'){
				add_mf(Data)
		}
		if(kategori == '3'){
			add_ot(Data);
		}			
	} 	
	
	function EditRoute(id,kategori){
		event.preventDefault();
		$("#val_c").val(kategori);
		if(kategori == '1'){
			edit_cd(id);
			$(".modalCD").css("display","block");
			$(".modalMF").css("display","none");
			$(".modalOT").css("display","none");				
		}
		if(kategori == '2'){
			edit_mf(id);
			$(".modalCD").css("display","none");
			$(".modalMF").css("display","block");
			$(".modalOT").css("display","none");			
		}		
		if(kategori == '3'){
			edit_ot(id);
			$(".modalCD").css("display","none");
			$(".modalMF").css("display","none");
			$(".modalOT").css("display","block");			
		}		
	}

mySumDeal = {
	gaCost : 0,
	comFee : 0,
	confirmPrice : 0,
	myDeal : 0,
}



function ValidateFileUpload(id_nya) {
    var fuData = id_nya //document.getElementById('txtattach_file');
    var FileUploadPath = fuData.value;

	//To check if user upload any file
			if (FileUploadPath == '') {
				alert("Please upload an image");
	
			} else {
				var Extension = FileUploadPath.substring(
						FileUploadPath.lastIndexOf('.') + 1).toLowerCase();
				//The file uploaded is an image
				if (Extension == "gif" || Extension == "png" || Extension == "bmp"|| Extension == "jpeg" || Extension == "jpg") {
				// To Display
					if (fuData.files && fuData.files[0]) {
						return true;
					}
					else{
						return false;
					}
				
				} 
				//The file upload is NOT an image
				else {
								alert("Photo only allows file types of GIF, PNG, JPG, JPEG and BMP. ");
								return false;
				
				}
			}
    }
	
	
  function USD_IDR(Data)
  { 
  
	if(Data == 'add'){
		  var pxnya = document.getElementById('txtprice_cd').value;
		  
	}
	if(Data == 'edit'){
		  var pxnya = document.getElementById('txtprice_cd2').value;
	}	
  	var tglnya = document.form.txtdeldate.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_usd_idr",
      method: 'POST',
      data: {pxnya: pxnya,tglnya: tglnya},
      dataType: 'json',
      success: function(response)
      {	$('#txtprice_idr_cd').val(response[0]);
      	$('#txtjrate_cd').val(response[1]); 
		$('#txtprice_idr_cd2').val(response[0]);
      	$('#txtjrate_cd2').val(response[1]); 		
		
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function USD_IDR_MF(data)
  { 
	console.log(data);
	if(data == 'add'){
		var pxnya = document.getElementById('txtprice_mf').value;
		console.log(pxnya);
	}
	if(data == 'edit'){
		 var pxnya = document.getElementById('txtprice_mf2').value;
		console.log(pxnya);
	}
  

  	var tglnya = document.form.txtdeldate.value;
	tanggalGlobal = document.form.txtdeldate.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_usd_idr",
      method: 'POST',
      data: {pxnya: pxnya,tglnya: tglnya},
      dataType: 'json',
      success: function(response)
      {	$('#txtprice_idr_mf').val(response[0]);
      	$('#txtjrate_mf').val(response[1]); 
		$('#txtprice_idr_mf2').val(response[0]);
      	$('#txtjrate_mf2').val(response[1]); 
console.log($('#txtprice_idr_mf2').val());		
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function USD_IDR_OT(Data)
  { 
  
  if(Data == 'add'){
	 var pxnya = document.getElementById('txtprice_ot').value; 
	  
  }
  if(Data == 'edit'){
	 var pxnya = document.getElementById('txtprice_ot2').value; 
	  
  } 
  	var tglnya = document.form.txtdeldate.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_usd_idr",
      method: 'POST',
      data: {pxnya: pxnya,tglnya: tglnya},
      dataType: 'json',
      success: function(response)
      {	$('#txtprice_idr_ot').val(response[0]);
      	$('#txtjrate_ot').val(response[1]); 
		$('#txtprice_idr_ot2').val(response[0]);
      	$('#txtjrate_ot2').val(response[1]);		
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
	
	
  function IDR_USD(Data)
  { 
	if(Data == 'add'){
		//var pxnya = document.getElementById('txtprice_idr_cd').value;
		var pxnya = $("#txtprice_idr_cd").val();
	}
  	if(Data == 'edit'){
		//var pxnya = document.getElementById('txtprice_idr_cd2').value;
		var pxnya = $("#txtprice_idr_cd2").val();
		 console.log(pxnya);
	}
  
 // var pxnya = document.getElementById('txtprice_idr_cd').value;
  	var tglnya = document.form.txtdeldate.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_idr_usd",
      method: 'POST',
      data: {pxnya: pxnya,tglnya: tglnya},
      dataType: 'json',
      success: function(response)
      {	//$('#txtprice_cd').val(response[0]);
      	//$('#txtjrate_cd').val(response[1]);

		$('#txtprice_cd').val(response[0]);
      //$('#txtjrate_cd').val(response[1]); 
		$('#txtprice_cd2').val(response[0]);
      	//$('#txtjrate_cd2').val(response[1]);
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function IDR_USD_MF(Data)
  {
	 
		  if(Data == "add"){
			var pxnya = document.getElementById('txtprice_idr_mf').value;
	  }
	  if(Data == "edit"){
		  
		  var pxnya = document.getElementById('txtprice_idr_mf2').value;
	  }

  	var tglnya = document.form.txtdeldate.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_idr_usd",
      method: 'POST',
      data: {pxnya: pxnya,tglnya: tglnya},
      dataType: 'json',
      success: function(response)
      {	$('#txtprice_mf').val(response[0]);
      	$('#txtjrate_mf').val(response[1]); 
		$('#txtprice_mf2').val(response[0]);
      	$('#txtjrate_mf2').val(response[1]); 		
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function IDR_USD_OT()
  { var pxnya = document.getElementById('txtprice_idr_ot').value;
  	var tglnya = document.form.txtdeldate.value;
  	jQuery.ajax
    ({  
      url: "ajax_act_cost.php?mdajax=calc_idr_usd",
      method: 'POST',
      data: {pxnya: pxnya,tglnya: tglnya},
      dataType: 'json',
      success: function(response)
      {	$('#txtprice_ot').val(response[0]);
      	$('#txtjrate_ot').val(response[1]); 
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  