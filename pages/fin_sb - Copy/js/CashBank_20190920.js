$( document ).ready(function() {
togglecheckboxar();
//CHECK DY DIPAGE MANA UNTUK NENTUIN KODE COA NYA	
url =window.location.search;
console.log(url);
myCase = url.split("=");
if(myCase[1] == "EntryCashBank" || myCase[1] == "EntryCashBank&id"){
	typeIdCoa = "CASH";
	getMasterSupplier();
}
else if(myCase[1] == "EntryBank" || myCase[1] == "EntryBank&id" ){
	typeIdCoa = "BANK";
	getMasterSupplier();
	//getDefaultSupplier(myCase[2]);
}

if(myCase[2]){
	setTimeout(function(){
		//check date post 
		getDatePost(myCase[2]);
		getDefaultSupplier(myCase[2]);
		//inc
		getAkunCoaInc("Neraca");
	},2000)
	
	console.log(myCase[2]);
	
}

async function terkel(){
	 var terkel = await $("#terimakeluar").val();
	 console.log(terkel);
	await terimaK(terkel);
	
	
}
//console.log(typeIdCoa);




url =url.split("&");
//console.log(url);
setTimeout(function(){ 				
if(url[1]){
	subpathurl = url[1].split("=");
	id = subpathurl[1];
	getIdAkunCashBank(id);
	getTerimaKeluar(id);
	//totalnilais();
	var vall = $("#idcoa").val();
	var angka = $("#nilai").val();
	console.log(angka);
	formatRupiah(angka);
	getMasterCurrencyInc();
	//formatRupiah(vall,'total');
	
setTimeout(function(){ 		
terkel();
}, 2000);		
}
}, 2000);	





    $("#codekonsumen").datepicker({
      format: "dd/mm/yyyy",
      autoclose: true
    });
    $("#tgltempoar").datepicker({
      format: "dd M yyyy",
      autoclose: true
    });
		
    $("#periodeakuntansi").datepicker( {
      format: "mm/yyyy",
      viewMode: "months",
      minViewMode: "months",
      autoclose: true
    });		



getAkunCashBank();
});	




 async function getDatePost(idJournals){
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/checkDatePost.php", 
        data : { code : '1', idJournal:idJournals },     // multiple data sent using ajax
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				if(d.message == '1'){
						var date_post = decodeURIComponent(d.records[0].date_post);
						console.log(date_post);
						if(date_post == "" || date_post == undefined || date_post == 'undefined' ){	
							console.log("begin undefined");						
						}
						else{
							
							console.log("begin datepost");
							$(".mySimpan").remove();
							$("#monthpicker1").attr("disabled",true);
							$("#checkno").attr("disabled",true);
							$("#nilai").attr("disabled",true);
							$("#datepicker1_new").attr("disabled",true);
							$("#no_voucher").attr("disabled",true);
							$("#reff_doc").attr("disabled",true);
							$("#faktur_pajak").attr("disabled",true);
							$("#fg_intercompany").attr("disabled",true);
							
								setTimeout(function(){ 				
									$("#umurar").attr("disabled",true);
									
								}, 3000);						
																	
							
						}
						//$("#rate").val(r);
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });		 
	 
	 
	 
 }

 function getMasterRate(){

		var tgl_Journals = $("#datepicker1_new").val();
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getMasterRate.php", 
        data : { code : '3', tgl_journal:tgl_Journals  },     // multiple data sent using ajax
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				if(d.message == '1'){
					
						var r = decodeURIComponent(d.records[0][0].rate,"rate");
						console.log(r);
						$("#rate").val(r);
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	 
}

function getKonversi(myItem){
	//alert(myItem);
	if(myItem == "USD"){
		//$(".konversi").css("display","Block");
		getMasterRate();
	}else{
		//$(".konversi").css("display","none");
		$("#rate").val(1);
	}
	
	
}

function togglecheckboxar(){
        if($('#umurar').is(':checked')){
            $('#codekonsumen').attr('disabled', false);
			$('#namakonsumen').attr('disabled', false);
        }else{
            $('#codekonsumen').attr('disabled', true);
			$('#namakonsumen').attr('disabled', true);
			$('#codekonsumen').val('').trigger('change');
			$('#namakonsumen').val('');
			
			
        }
}

function terimaK(id){
	if (id == "P1"){
		$('#umurar').attr('disabled', false);
		//$('#codekonsumen').attr('disabled', false);
		//$('#namakonsumen').attr('disabled', false);
	}else if(id == "P2"){
		document.getElementById("umurar").checked = false;
		$('#umurar').attr('disabled', true);
		$('#codekonsumen').attr('disabled', true);
		$('#namakonsumen').attr('disabled', true);
			$('#codekonsumen').val('').trigger('change');
			$('#namakonsumen').val('');		
	}else{
					 document.getElementById("umurar").checked = false;
		$('#umurar').attr('disabled', true);
		$('#codekonsumen').attr('disabled', true);
		$('#namakonsumen').attr('disabled', true);
			$('#codekonsumen').val('').trigger('change');
			$('#namakonsumen').val('');		

	}
	
}


bulan = [0,"Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
valid_journal = "";




function ConvertDate(date){
	var tmpdate = date.split(" ");
	var indexbulan = bulan.indexOf(tmpdate[1]);
	//console.log(tmpdate[1]);
var enddatebulan = [0,31,28,31,30,31,30,31,31,30,31,30,31];
	//Check tahun kabisat/ bukan
	//var kabisat = tmpdate[2] % 4;
	if(tmpdate[0] == '29' && tmpdate[1] == '29'  ){
		enddatebulan[2] = 29;
	}
	
	var finalisasidate = enddatebulan[parseFloat(indexbulan)]+"/"+bulan[parseFloat(indexbulan)]+"/"+tmpdate[2];
return finalisasidate;
	}

function validatejournal(myValidate){

	console.log(myValidate);
	valid_journal = "01/"+myValidate;
	var split = valid_journal.split("/"); 
	valid_journal = myValidate ;
	datepicker1_new = split[0]+" "+bulan[parseFloat(split[1])]+" "+split[2];
	enddate = ConvertDate(datepicker1_new);
console.log(enddate);
$("#datepicker1_new").datepicker( "setDate" , datepicker1_new );
$("#datepicker1_new").datepicker( "setEndDate" , enddate);
$("#datepicker1_new").datepicker( "setStartDate" , datepicker1_new);
}

function filter_tabless(Item){
	console.log(Item.value);
	
	
	
}

function handlekeyUpRate(Item){
	currinc = Item.value;




	if(currinc == 'IDR' || currinc == 'EUR' || currinc == 'JPY'   ){
		return false;
	}else{	
		
	
	
		var debitincVal = $("#debit").val();
			debitinc = getOriginal(debitincVal);
		var creditincVal = $("#credit").val();
			creditinc = getOriginal(creditincVal);
		var Cdebitinc = '';
		var Ccreditinc ='';
		var rateincVal = $("#rate").val();
			rateinc = getOriginal(rateincVal);
			if(Item.id == "debit"){
				
				Cdebitinc = rateinc * debitinc;
				$("#konversidebit").val(Cdebitinc);
				
				//formatRupiahInc(Cdebitinc,"konversidebit");
				console.log(Cdebitinc);
			}
			else if(Item.id == 'credit'){
				Ccreditinc = rateinc * creditinc;
				$("#konversicredit").val(Ccreditinc);					
			}
				//Cdebitinc = rateinc * debitinc;
				//Ccreditinc = rateinc * creditinc;
				//$("#konversidebit").val(Cdebitinc);
				//$("#konversicredit").val(Ccreditinc);
	}
	
	formatRupiahInc(Item.value,Item.id);
}



async function getDefaultSupplier(idJournal){
	//console.log(idJournal);
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getMasterSupplier.php", 
        data : { code : '3', idJournals:idJournal  },     // multiple data sent using ajax
        success: function (response) {
			data = response;
			//console.log(data);
				d = JSON.parse(data);
				if(d.message == '1'){


						$("#codekonsumen").val(decodeURIComponent(d.records[0].code)).trigger('change');
						$("#namakonsumen").val(decodeURIComponent(d.records[0].nama));
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
}

function getMasterSupplier(){
		var option = "";


	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getMasterSupplier.php", 
        data : { code : '1'  },     // multiple data sent using ajax
        success: function (response) {
			data = response;
		//	console.log(data);
				d = JSON.parse(data);
				if(d.message == '1'){
						for(var i=0;i<d.records.length;i++){
							option += "<option value='"+decodeURIComponent(d.records[i].code)+"' >"+decodeURIComponent(d.records[i].code)+" | "+ decodeURIComponent(d.records[i].nama) +"</option>";
						}
						$("#codekonsumen").append(option);
						//$("#rate").val(decodeURIComponent(d.records[0].rate_beli));
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
}
function getNamaSupplier(myCodeSupp){
	
	if(myCodeSupp == ""){
		return false;
	}
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getMasterSupplier.php", 
        data : { code : '2',codesup :myCodeSupp  },     // multiple data sent using ajax
        success: function (response) {
			data = response;
		//	console.log(data);
				d = JSON.parse(data);
				if(d.message == '1'){

						$("#namakonsumen").val(decodeURIComponent(d.records[0].nama));
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	  
}



function getMasterCurrencyInc(){
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getMasterCurrencyInc.php", 
        data : { code : '1'  },     // multiple data sent using ajax
        success: function (response) {
			data = response;
		//	console.log(data);
				d = JSON.parse(data);
				if(d.message == '1'){
						//$("#rate").val(decodeURIComponent(d.records[0].rate_beli));
						$("#rate").val("");
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
}
async function getDetailsInc(ids,rows){
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getDetailsInc.php", 
        data : { code : '1', id:ids,row:rows   },     // multiple data sent using ajax
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				if(d.message == '1'){
						 getAkunCoaInc(ids);
						 $("#terimakeluar").val(decodeURIComponent(d.records[0][0].id));
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
}

	function getTerimaKeluar(Item){
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getTerimaKeluar.php", 
        data : { code : '1', id:Item   },     // multiple data sent using ajax
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				if(d.message == '1'){
						$("#terimakeluar").val(decodeURIComponent(d.records[0][0].id));
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
			
		
		
		
	}


	function getIdAkunCashBank(Item){
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getIdAkunCashBank.php", 
        data : { code : '1', id:Item   },     // multiple data sent using ajax
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				if(d.message == '1'){
						$("#idcoa").val(decodeURIComponent(d.records[0][0].id));
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
			
		
		
		
	}


		function formatRupiahInc(angka,id){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

				$("#"+id).val(rupiah);
			
		}

		function formatRupiah(angka){

			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

				$("#nilai").val(rupiah);
				
		
		
			
		}
	function getnamacoa(Item){
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getNamaCoa.php", 
        data : { code : '1', id:Item   },     // multiple data sent using ajax
        success: function (response) {
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				if(d.message == '1'){
					console.log(response);
						$("#namacoa").val(decodeURIComponent(d.records[0][0].nama));
		
						
						
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
			
		
		
		
	}
	function getAkunCashBank(){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getAkunCashBank.php", 
        data : { code : '1', typeidcoa : typeIdCoa  },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				//d = response;
				option  = '';
				renders = '';
			//	console.log(d.records.length);
				if(d.message == '1'){
					//	$("#render").append(data[1]);
					//	console.log(d.records);
						for(i=0;i<d.records.length;i++){
							option += "<option value="+decodeURIComponent(d.records[i][0].id)+">"+decodeURIComponent(d.records[i][0].id) +" "+decodeURIComponent(d.records[i][0].nama)+"</option>";
						}//department
						$("#idcoa").append(option);

				setTimeout(function(){ 				

				}, 3000);						
						
						
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
}	
	
	function getAkunCoaInc(Item){
		$("#id_coa").empty();
	    $.ajax({		
        type:"POST",
        cache:false,  
        url:"webservices/getAkunByDepartment.php", 
        data : { code : '1',  idDepartment:Item },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				//d = response;
				option  = '';
				option += "<option> </option>";
				option += "<option value='' disabled selected>--Pilih Akun--</option>";
				renders = '';
				//console.log(d.records.length);
				if(d.message == '1'){
					//	$("#render").append(data[1]);
						//console.log(d.records);
						for(i=0;i<d.records.length;i++){
							option += "<option value="+decodeURIComponent(d.records[i][0].id)+">"+decodeURIComponent(d.records[i][0].id) +" "+decodeURIComponent(d.records[i][0].nama)+"</option>";
						}
						$("#id_coa").append(option);

				setTimeout(function(){ 				

				}, 3000);						
						
						
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
		
		
	}
	
	function getAkunBiayaPenyusutan(){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getAkunBiayaPenyusutan.php", 
        data : { code : '1',   },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				//d = response;
				option  = '';
				option += "<option> </option>"
				renders = '';
				//console.log(d.records.length);
				if(d.message == '1'){
					//	$("#render").append(data[1]);
						//console.log(d.records);
						for(i=0;i<d.records.length;i++){
							option += "<option value="+decodeURIComponent(d.records[i][0].id)+">"+decodeURIComponent(d.records[i][0].id) +" "+decodeURIComponent(d.records[i][0].nama)+"</option>";
						}
						$("#akunbiayapenyusutan").append(option);

				setTimeout(function(){ 				

				}, 3000);						
						
						
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	
	
}		
	
	function getAkunAkumulasiPenyusutan(){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getAkunAkumulasiPenyusutan.php", 
        data : { code : '1',   },     // multiple data sent using ajax
        success: function (response) {
//console.log(response);
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				//d = response;
				option  = '';
				option += "<option> </option>"
				renders = '';
			//	console.log(d.records.length);
				if(d.message == '1'){
					//	$("#render").append(data[1]);
					//	console.log(d.records);
						for(i=0;i<d.records.length;i++){
							option += "<option value="+decodeURIComponent(d.records[i][0].id)+">"+decodeURIComponent(d.records[i][0].id) +" "+decodeURIComponent(d.records[i][0].nama)+"</option>";
						}
						$("#akunakumulasipenyusutan").append(option);

				setTimeout(function(){ 				

				}, 3000);						
						
						
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	
	
}		
	
	function getTipeAktivaList(){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getMasterTipeAktiva.php", 
        data : { code : '1',   },     // multiple data sent using ajax
        success: function (response) {
	//		console.log(response);
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				//d = response;
				option  = '';
				option += "<option> </option>"  
				renders = '';
			//	console.log(d.records.length);
				if(d.message == '1'){
					//	$("#render").append(data[1]);
						
						for(i=0;i<d.records.length;i++){
							option += "<option value="+decodeURIComponent(d.records[i][0].kodeaktiva)+">"+decodeURIComponent(d.records[i][0].namaaktiva)+"</option>";
						}
						$("#tipeaktiva").append(option);
				setTimeout(function(){ 				

				}, 3000);						
						
						
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	
	
}

	function getOriginal(value){
		
		var wer = value.split(".");
		console.log(wer);
		console.log(wer.length);
		n_value = '';
		for(var w = 0; w < wer.length;w++){
			n_value += wer[w];
			console.log(n_value);
		}
		return n_value;
		
		
	}
