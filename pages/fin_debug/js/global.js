$( document ).ready(function() {



urlDef =window.location.search;
console.log(urlDef);
myCaseDef = urlDef.split("=");

if(myCaseDef[2]){
	setTimeout(function(){
		//$tmp_tgl
		def_period = $("input[name=period]").val();
		$datepicker = $("#datepicker1").val();
		$datepicker_new = $("#datepicker1_new").val();
		if($datepicker){
			$tmp_tgl = $datepicker;
		}else{
			if($datepicker_new){
				$tmp_tgl = $datepicker_new;
			}
		}
		//getDefaultPeriodAccounting(def_period);
	console.log($datepicker);
	},2000)
	
	//console.log(myCaseDef[2]);
	
}	
	
});



function generateSelectTanggal(){
 var xDate = 31;
 var xBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
 //console.log(xBulan.length);
 for(x=1;x<=xDate;x++){
	  $('#content_tglday').append('<option value="'+x+'" >'+x+'</option>');
	  $('#content_tglday1').append('<option value="'+x+'" >'+x+'</option>');
	  $('#content_tglday2').append('<option value="'+x+'" >'+x+'</option>');
	  $('#content_tglday3').append('<option value="'+x+'" >'+x+'</option>');
	  $('#header_tglday').append('<option value="'+x+'" >'+x+'</option>');
	  $('#footer_tglday').append('<option value="'+x+'" >'+x+'</option>');
 }
 for(x=0;x<xBulan.length;x++){
	 $('#content_tglmonth').append('<option value="'+x+'" >'+xBulan[x]+'</option>');
	$('#content_tglmonth1').append('<option value="'+x+'" >'+xBulan[x]+'</option>');
	 $('#content_tglmonth2').append('<option value="'+x+'" >'+xBulan[x]+'</option>');
	 $('#content_tglmonth3').append('<option value="'+x+'" >'+xBulan[x]+'</option>');
	 $('#header_tglmonth').append('<option value="'+x+'" >'+xBulan[x]+'</option>');
	  $('#footer_tglmonth').append('<option value="'+x+'" >'+xBulan[x]+'</option>');
 }
	startYear =  1960;
	finishYear =2050;
    var currentYear = new Date().getFullYear(),
	xYears = [];
    startYear = startYear;  
    while ( startYear <= finishYear ) {
        xYears.push(startYear++);
    }   
     xYears; 
 
 
 for(x=0;x<xYears.length;x++){
	 $('#content_tglyears').append('<option value="'+xYears[x]+'" >'+xYears[x]+'</option>');
		$('#content_tglyears1').append('<option value="'+xYears[x]+'" >'+xYears[x]+'</option>');
	 $('#content_years2').append('<option value="'+xYears[x]+'" >'+xYears[x]+'</option>');
	 $('#content_years3').append('<option value="'+xYears[x]+'" >'+xYears[x]+'</option>');
	  $('#header_tglyears').append('<option value="'+xYears[x]+'" >'+xYears[x]+'</option>');
	  $('#footer_tglyears').append('<option value="'+xYears[x]+'" >'+xYears[x]+'</option>');
 }
 
 

	
}

function getDay(){
date = new Date().getDate();
	switch (new Date().getDay()) {
  case 0:
    day = "Minggu";
	return day;
    break;
  case 1:
    day = "Senin";
	return day;
    break;
  case 2:
     day = "Selasa";
	 return day;
    break;
  case 3:
    day = "Rabu";
	return day;
    break;
  case 4:
    day = "Kamis";
	return day;
    break;
  case 5:
    day = "Jumat";
	return day;
    break;
  case 6:
    day = "Sabtu";
	return day;
}
}
function getMonth(data){
console.log(data);
	switch (data) {
  case 0:
    months = 0;
    break;
  case 1:
   months = 1;
    break;
  case 2:
        months = 2;
    break;
  case 3:
     months = 3;
    break;
  case 4:
      months = 4;
    break;
  case 5:
       months = 5;
    break;
  case 6:
       months = 6;
	      break;
  case 7:
       months = 7;
	      break;
  case 8:
       months = 8;
	      break;
  case 9:
       months = 9;
	      break;
  case 10:
       months = 11;
	      break;
  case 11:
       months = 11;
	      break;
   
}

years = new Date().getFullYear();
}



	$(document).ready(function(){
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
		
		
		
	})

function jatuhTempoDate(myDate){
		$.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getJatuhTempo.php", 
        data : { code : '1', data:myDate  },     // multiple data sent using ajax
        success: function (response) {
			console.log(response);
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				//d = response;
				td  = '';
				renders = '';
				console.log(d.records.length);
				if(d.message == '1'){
					alert("Data Berhasil Disimpan");
						 location.reload();
				}
				if(d.message == '2'){
					alert("Ada Error!, Silahkan Reload Page");
				}
        }
      });	
}


function getDefaultNumber(that){
	rupiah =number_format(that.value,'X');
	console.log(that);
	$("#"+that.id).val(rupiah);
	
	if((that.id == "debit") || (that.id == "credit") ){
		var rate =$("#rate").val();
			rate = rate.replace(/,/g, '').toString();
		var konversi = that.value.replace(/,/g, '').toString();
		console.log(konversi);
		console.log(rate);
		hasil_konversinya = parseFloat(konversi) * parseFloat(rate);
		$("#konversi"+that.id).val(number_format(hasil_konversinya));
	}
}

Number.prototype.pad = function(size) {
    var s = String(this);
    while (s.length < (size || 2)) {s = "0" + s;}
    return s;
}
		function number_format_new(bilangan,defaults){
			console.log(defaults);
			
		if(!defaults){
			var	number_string = bilangan.toString();
				split	= number_string.split('.');
				split[0] = split[0].replace(/[^,\d]/g, '').toString();
				// = split[1].pad(2);
				console.log(split);
				var tmp_ = "";
				if(split[1] == undefined){
					tmp_ += "00";
					split[1] = tmp_;
				}else{
					lengths = split[1].length;
					if(lengths < 2){
						
						trigger = 2 - parseFloat(lengths);
						for(var w=0;w<trigger;w++){
							tmp_ +="0";
						}
					}
					if(!tmp_){
						tmp_ += "00";
						split[1] = tmp_;
					}else{
						split[1] = split[1]+tmp_;
					}
					console.log(tmp_);					
				}			
			
		}else{
		
			var	number_string = bilangan.replace(/,/g, '').toString();
			console.log(bilangan+" "+number_string);
				split	= number_string.split('.');	
				//var number_string = angka.replace(/[^,\d]/g, '').toString(),
		}
	
	sisa 	= split[0].length % 3;	
	rupiah 	= split[0].substr(0, sisa);
	ribuan 	= split[0].substr(sisa).match(/\d{1,3}/gi);
if (ribuan) {
	separator = sisa ? ',' : '';
	
	rupiah += separator + ribuan.join(',');
}
console.log(split);
rupiah = split[1] != undefined ? rupiah + '.' + split[1] : rupiah;	
rupiah = rupiah.replace("-,", "-");
return rupiah;	
		}


		function number_format(bilangan,defaults){
			console.log(defaults);
		if(!defaults){
			var	number_string = bilangan.toString();
				split	= number_string.split('.');
				split[0] = split[0].replace(/[^,\d]/g, '').toString();
				console.log(bilangan+" "+number_string);
			
		}else{
		
			var	number_string = bilangan.replace(/,/g, '').toString();
			console.log(bilangan+" "+number_string);
				split	= number_string.split('.');	
				//var number_string = angka.replace(/[^,\d]/g, '').toString(),
		}
	
	sisa 	= split[0].length % 3;	
	rupiah 	= split[0].substr(0, sisa);
	ribuan 	= split[0].substr(sisa).match(/\d{1,3}/gi);
if (ribuan) {
	separator = sisa ? ',' : '';
	
	rupiah += separator + ribuan.join(',');
}
console.log(split);
rupiah = split[1] != undefined ? rupiah + '.' + split[1] : rupiah;	
rupiah = rupiah.replace("-,", "-");
return rupiah;	
		}
	function getOriginal(value){
		console.log(value);
		var wer = value.split(",");
		
		console.log(wer);
		console.log(wer.length);
		n_value = '';
		for(var w = 0; w < wer.length;w++){
			n_value += wer[w];
			console.log(n_value);
		}
		n_value = n_value.replace(',', '.');
		return n_value;
	}		
	
	function getCurrencyDetail(that){
		$.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getTypeCurrency.php", 
        data : { code : '1', curr:that  },     // multiple data sent using ajax
        success: function (response) {
			console.log(response);
			data = response;
				d = JSON.parse(data);
				//d = response;
				td  = '';
				renders = '';
				console.log(d.records);
				if(d.message == '1'){
					console.log(d.records[0].curr);
					$("#curr").val(d.records[0].curr).trigger('change');
				}
				else{
					alert(d.message);
				}
        }
      });			
	}
	$x = 0;
	
	function getDefaultPeriodAccounting(that){
		$x++;
		$.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getPeriodAccounting.php", 
        data : { code : '1', period:that  },     // multiple data sent using ajax
        success: function (response) {
			$x = 0;
			console.log(response);
			data = response;
				d = JSON.parse(data);
				//d = response;
				td  = '';
				renders = '';
				console.log(d.records);
				if(d.message == '1'){
					if(d.records[0].status != '1'){
						$(".validasi_proses").css("display","none");
					}
				}
				else{
					alert(d.message);
				}
        }
      });			
	}	
	
	$tmp_tgl = "";
	function getPeriodAccounting(that){
		if(!that.value){
			return false;
		}		
		if(that.id == "datepicker1" || that.id == "datepicker1_new"){
			$string_tgl = String(that.value);
			var $period = $string_tgl.split(' ');
				$period = $periode_array[$period[1]]+"/"+$period[2];
			that.value= $period;
		}

		if($x > 0){
			return false;
		}
		$x++;
		console.log($x);
		$.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getPeriodAccounting.php", 
        data : { code : '1', period:that.value  },     // multiple data sent using ajax
        success: function (response) {
			$x = 0;
			console.log(response);
			data = response;
				d = JSON.parse(data);
				//d = response;
				td  = '';
				renders = '';
				console.log(d.records);
				if(d.message == '1'){
					if(d.records[0].status != '1'){
						alert("Periode "+that.value+ " Belum dibuka");
						$("#datepicker1_new").val($tmp_tgl);
						$("#datepicker1").val($tmp_tgl);	
						$("#datepicker1_new").datepicker().datepicker("setDate", $tmp_tgl);
						$("#datepicker1").datepicker().datepicker("setDate", $tmp_tgl);						
						$tmp_tgl = $tmp_tgl;
						//$("#"+that.id).val("");
						//$("#datepicker1_new").val("");
						//$("#datepicker1").val("");
						//$("#datepicker1_new").attr("disabled",true);
						//$("#datepicker1").attr("disabled",true);
						$(".btn-primary").attr("disabled",true);
						return false
					}
					else{
						//validatejournal(that);
						$("#datepicker1_new").val($string_tgl);
						$("#datepicker1").val($string_tgl);	
						$(".btn-primary").attr("disabled",false);
						$("#periodpicker_").val($period);
						$tmp_tgl = $string_tgl;

					}
				}
				else{
					alert(d.message);
				}
        }
      });			
	}
	
bulan = [0,"Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
$periode_array =[];
$periode_array["Jan"] = 1;
$periode_array["Feb"] = 2;
$periode_array["Mar"] = 3;
$periode_array["Apr"] = 4;
$periode_array["May"] = 5;
$periode_array["Jun"] = 6;
$periode_array["Jul"] = 7;
$periode_array["Aug"] = 8;
$periode_array["Sep"] = 9;
$periode_array["Oct"] = 10;
$periode_array["Nov"] = 11;
$periode_array["Dec"] = 12;
 
valid_journal = "";


	
function validatejournal(myValidate){
	console.log(myValidate.value);
	valid_journal = "01/"+myValidate.value;
	var split = valid_journal.split("/"); 
	valid_journal = myValidate.value ;
	datepicker1_new = split[0]+" "+bulan[parseFloat(split[1])]+" "+split[2];
	enddate = ConvertDate(datepicker1_new);
console.log(enddate);
$("#datepicker1_new").datepicker( "setDate" , datepicker1_new );
$("#datepicker1_new").datepicker( "setEndDate" , enddate);
$("#datepicker1_new").datepicker( "setStartDate" , datepicker1_new);

$("#datepicker1").datepicker( "setDate" , datepicker1_new );
$("#datepicker1").datepicker( "setEndDate" , enddate);
$("#datepicker1").datepicker( "setStartDate" , datepicker1_new);
}	

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

function DecodeFromServices(data){
        for(var i=0, length=data.length;i<length;i++) {
            for ( var temp in data[i] ) {
                data[i][temp] = decodeURIComponent(data[i][temp]);
            } 
        }
        return data;
    }

	