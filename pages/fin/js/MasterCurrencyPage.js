$( document ).ready(function() {
localStorage.clear();
sessionStorage.clear();



getListData();
kursTipe = "HARIAN";

$("#rate").inputFilter(function(value) {
  return /^-?\d*[.]?\d*$/.test(value); });
  
 $("#ratejual").inputFilter(function(value) {
  return /^-?\d*[.]?\d*$/.test(value); }); 
  
  $("#ratebeli").inputFilter(function(value) {
  return /^-?\d*[.]?\d*$/.test(value); });
  
    $("#tanggaltos").datepicker( {
      format: "dd/mm/yyyy",
      autoclose: true
    });
    $("#tanggal").datepicker( {
      format: "dd/mm/yyyy",
      autoclose: true
    });
	
	



	
});

function removeAllTable(){
	$("#contentpajak").empty();
	$("#contentcosting3").empty();
	$("#contentcosting6").empty();
	$("#contentcosting8").empty();
	$("#contentcosting12").empty();
	$("#contentcostingall").empty();
	
}
function GetContent(MyContent){
	$("#MasterKursPajak").dataTable().fnDestroy();
	$("#MasterCurrencyTable").dataTable().fnDestroy();
	removeAllTable();
	$(".loading").css("display","block");
	getListDataKursPajak(MyContent);


if(MyContent == "HARIAN"){
	kursTipe = "HARIAN";
	console.log(kursTipe);
	  var table =  $('#MasterCurrencyTable').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
		order: [[ 0, "desc" ]],
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false
            }
        ],		
        paging: true,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }	
    });		


	 setTimeout(function(){ 	
	console.log("Begin");
		$(".sorting").trigger("click");
	$(".loading").css("display","none");
	}, 500);
}
else{
	kursTipe = "";
	
}




}



function getRateTengah(){
	if(kursTipe == 'HARIAN'){
		ratejual = $("#ratejual").val();
		ratebeli = $("#ratebeli").val();
		rate = (parseFloat(ratejual) + parseFloat(ratebeli))/2;
		if(isNaN(rate)){
			return false;
			
		}
		else{
						console.log(rate);

			$("#rate").val(rate);
		}

		
	}
}


async function generateDatatableKursPajak(){
	  var table =  $('#MasterKursPajak').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
		order: [[ 0, "desc" ]],
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false
            }
        ],		
        paging: true,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }	
    });		
	// $("#MasterKursPajak").trigger('click');
	 setTimeout(function(){ 	
	console.log("Begin");
		$(".sorting").trigger("click");
	$(".loading").css("display","none");
	}, 500);
	
}  


async function generateDatatableKursHarian(){
	  var table = await  $('#MasterCurrencyTable').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
		order: [[ 0, "desc" ]],
        "columnDefs": [

            {
                "targets": [ 0 ],
                "visible": false
            }
        ],		
        paging: true,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }	
		
    });		
	
		 setTimeout(function(){ 	
	console.log("Begin");
		$(".sorting").trigger("click");
	
	}, 500);
	await $(".loading").css("display","none");
}   	


			Ddata ={  tanggal :'' 
						,tanggalto:''
					,curr    :'USD'
					,rate    :''
					,ratejual:''
					,ratebeli:''
					,type    :'Add'
					,lastDate : ''
					
					}


function formatdate(id){
	var id = new Date(id);
	console.log(id);
	var day =id.getDate();
	var month = id.getMonth()+1;
	var years =id.getFullYear();

	if(day < 10){
		day = "0"+day;
	}
	if(month <10){
		month = "0"+month;
	}
	var finaldate = day+"/"+month+"/"+years;
	console.log(finaldate);
	return finaldate
}

function formatdate2(id){
	var seplit =id.split("/");
	var finaldate = seplit[2]+"-"+seplit[1]+"-"+seplit[0];
	return finaldate;
}


function ConvertDate(date,triggertambah){
	var datess = new Date(date);
	console.log(datess);
	d = datess.getTime();
	plus = parseFloat(d)  + (1000*(parseFloat(triggertambah)*86400));
	//minus = parseFloat(plus) - (1000*(30*86400));
	minus = parseFloat(plus) - (1000*(1*86400));
	console.log(datess);
	console.log(new Date(plus));
    var t = new Date(minus);
	var finalisasidate = formatdate(t)
return finalisasidate;

	}
/*
function ConvertDate(date,triggertambah){
	// date format : dd/mm/yyyy
	var tmpdate = date.split("/");
	var enddatebulan = [0,31,28,31,30,31,30,31,31,30,31,30,31];
	//Check tahun kabisat/ bukan
	var kabisat = tmpdate[2] % 4;
	if(kabisat == '0'){
		enddatebulan[2] = 29;
	}
	//TAMBAH DATE NYA
	
	finaldate = parseFloat(tmpdate[0]) + parseFloat(triggertambah);
	finalmonth = tmpdate[1];
	tmpdate[1] = parseFloat(tmpdate[1]) + 1;
	finalyears =  tmpdate[2];
	console.log(finaldate) ;
	//CHECK apakah datenya melebihi nilai endbulan
	parammonth = parseFloat(enddatebulan[parseFloat(tmpdate[1])]);
	if(finaldate > parammonth){
			console.log(parammonth);
			console.log(finaldate);
			ttambahdate = finaldate - parammonth;
			finaldate = 0 + ttambahdate
			finalmonth = parseFloat(tmpdate[1]) + 1;
			console.log(finalmonth);
	if(finalmonth < 10){
	finalmonth = "0"+finalmonth;
	
}			
			}
			
	//CHECK apakah bulannya melebihi 12		
			
if(finalmonth > 12){
	finalmonth = "01";
	finalyears = parseFloat(tmpdate[2]) + 1;

}	

if(finaldate < 10){
	finaldate = "0"+finaldate;
	
}

var finalisasidate = finaldate+"/"+finalmonth+"/"+finalyears;
return finalisasidate;

	}
*/	
	


function handleKeyUp(Item){
	if(Item.id == 'tanggal'){
		Ddata.tanggal = Item.value;
		$("#tanggalto").val(Ddata.tanggal);
		Ddata.tanggalto = Ddata.tanggal;
		
	}else if(Item.id == 'rate'){
		Ddata.rate =Item.value;
	}else if(Item.id == 'ratejual'){
		Ddata.ratejual = Item.value;
		
	}else if(Item.id == 'ratebeli'){
		Ddata.ratebeli = Item.value;
	}
	else if(Item.id == 'tanggalto'){
		Ddata.tanggalto = Item.value;
	}	
}


function differentday(){
	var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
            var firstDate = new Date($("#tanggal").val());
            var secondDate = new Date($("#tanggalto").val());
            var diffDays = Math.round(Math.round((secondDate.getTime() - firstDate.getTime()) / (oneDay)));
           console.log(diffDays);
	
	
}

function deletes(Item){
	
	Ddata.type = "Delete";
	dataJ.id = Item;
	save();
}
function save(){
	if(Ddata.type == "Delete"){
			ww ="hahaha";
	}
	else{
			if(Ddata.type == 'Add'){
				if(Ddata.tanggal == ''  ){alert('Tanggal Harus Diisi');return false;}  
			}
			if(Ddata.type == 'Edit'){
				Ddata.tanggalto = ''; 
			}	
			if(Ddata.tanggal == ''  ){alert('Tanggal Harus Diisi');return false;}  
			if(Ddata.curr    == '' ){alert('Currency Harus Diisi');return false;}  
			if(Ddata.rate    == '' ){alert('Rate Harus Diisi');return false;}
			//if(Ddata.ratejual== '' ){alert('Rate Jual Harus Diisi');return false;} 
			//if(Ddata.ratebeli== '' ){alert('Rate Beli Harus Diisi');return false;} 
	
			
		
	}

		    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/saveMasterCurrency.php", 
        data : { code : '1', data:Ddata, dataDelete : dataJ },     // multiple data sent using ajax
        success: function (response) {
			console.log(response);
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				//d = response;
				td  = '';
				renders = '';
				console.log(d.records.length);
				if(d.status == 'ok'){		
				if(d.message == '1'){
					if(Ddata.type == "Delete"){
						alert("Data Berhasil Dihapus");
					}else{
						alert("Data Berhasil Disimpan");
					}
						 location.reload();
				}
				if(d.message == '2'){
					alert("Ada Error!, Silahkan Reload Page");
				}
				}
				else if(d.status == 'no'){
					alert(d.status.message);
					
				}
				
        }
      });	
	
	
	
}

function add(kurs){
	$("#tanggal").prop("disabled","disabled");
		console.log(kurs);
		$("#tanggal").val('');
	if(kurs == "KURSPAJAK"){
		var parameterday = 7;
	}else if(kurs == "COSTING3"){
		var parameterday = 90;
	}else if(kurs == "COSTING6"){
		var parameterday = 180;
	}else if(kurs == "COSTING8"){
		var parameterday = 240;
	}else if(kurs == "COSTING12"){
		var parameterday = 365;
	}
	else{
		$("#tanggal").prop("disabled","");
	}
	Ddata.lastDate = new Date(Ddata.lastDate).toDateString("yyyy-mm-dd"); 
	//Ddata.lastDate = Ddata.lastDate.format("yyyy-MM-dd");
	console.log(Ddata.lastDate);
	if(kursTipe == "HARIAN"){
		var today = formatdate(new Date());
		Ddata.tanggalto = formatdate(new Date());
		console.log("Begin Harians");
		
	}else{
		var today = formatdate(Ddata.lastDate);
		Ddata.tanggalto = ConvertDate(Ddata.lastDate,parameterday);
	}
	Ddata.tanggal = today;
	console.log(Ddata.tanggal);
	$("#tanggal").val(today);  
	
	//Ddata.tanggalto = ConvertDate(Ddata.lastDate,parameterday);
	console.log(Ddata.tanggalto);
	$("#tanggalto").val(Ddata.tanggalto); 	
	$("#labelTo").css("display","");
	//$("#tanggal").prop("disabled","");
	//Ddata.tanggal ='';
	Ddata.curr    ='USD';
	Ddata.rate    ='';
	Ddata.ratejual='';
	Ddata.ratebeli='';
	Ddata.type    ='Add';
	$("#id").val('');
	//$("#tanggal").val('');
	//$("#tanggalto").val('');
	$("#curr").val('');
	$("#rate").val('');
	$("#ratejual").val('');
	$("#ratebeli").val('');
	console.log('');
	$("#type").val("Add");	
	$('#exampleModal').modal('show');
}

	


dataJ = {id:'', code:''}
	function edit(datas){  
	$("#tanggal").prop("disabled","disabled");
	$("#tanggalo").val("");
	dataJ.id = datas;
	dataJ.code = 1;
		$.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getDetailListMasterCurrency.php", 
        data : dataJ,     // multiple data sent using ajax
        success: function (response) {
			console.log(response);
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				//d = response;
				td  = '';
				renders = '';
				console.log(d.records.length);
				if(d.message == '1'){
		/*						$outp .= '[{"id":"'.rawurlencode($row['id']).'",';
			$outp .= '"tanggal":"'. rawurlencode($row["tanggal"]). '",'; 
			$outp .= '"curr":"'. rawurlencode($row["curr"]). '",'; 
			$outp .= '"rate":"'. rawurlencode($row["rate"]). '",'; 
			$outp .= '"ratejual":"'. rawurlencode($row["rate_jual"]). '",'; 
			$outp .= '"ratebeli":"'. rawurlencode($row["rate_beli"]). '"}]'; 
		*/
				$("#id").val(d.records[0][0].id);
				$("#tanggal").val(decodeURIComponent(d.records[0][0].tanggal));
				$("#tanggalto").val(decodeURIComponent(d.records[0][0].tanggalto));
				$("#curr").val(d.records[0][0].curr);
				$("#rate").val(d.records[0][0].rate);
				$("#ratejual").val(d.records[0][0].ratejual);
				$("#ratebeli").val(d.records[0][0].ratebeli);
				console.log(d.records[0][0].tanggal);
				$("#type").val("Edit");	
				$('#exampleModal').modal('show');
				setTimeout(function(){ 				
				Ddata.id =   $("#id").val();
				Ddata.tanggal =  decodeURIComponent(d.records[0][0].tanggal);
				Ddata.tanggalto =   decodeURIComponent(d.records[0][0].tanggalto);
				Ddata.curr    =   'USD';
				Ddata.rate    = 	 $("#rate").val();		
				Ddata.ratejual= 	 $("#ratejual").val();		
				Ddata.ratebeli= 	 $("#ratebeli").val();		
				Ddata.type    = 	 $("#type").val();		

				console.log(Ddata);	
				}, 2000);
				
			
				}
				
				
				
				
				
				
				
				if(d.message == '2'){
					alert("Maaf Belum Ada Data untuk account nomor ini !")
				}
        }
      });	
	}

	
	function getListData(){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getListMasterCurrency.php", 
        data : { code : '1',   },     // multiple data sent using ajax
        success: function (response) {
			console.log(response);
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				//d = response;
				td  = '';
				renders = '';
				console.log(d.records.length);
				if(d.message == '1'){
						$("#render").append(data[1]);
				
				generateDatatableKursHarian();	
						
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	
	
}

	
	async function getListDataKursPajak(MyContent){
	    await $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getListMasterCurrencyKursPajak.php", 
        data : { code : '1', content:MyContent   },     // multiple data sent using ajax
        success: function (response) {
			console.log(response);
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				//d = response;
				td  = '';
				renders = '';
				
					Ddata.lastDate = decodeURIComponent(d.lastDate);
				
				console.log(Ddata.lastDate);
				console.log(d.records.length);
				if(d.message == '1'){
					if(MyContent == "PAJAK"){
						$("#contentpajak").append(data[1]);
						$(".formHarian").css("display","none");
					}else if(MyContent == "COSTING3"){
						$("#contentcosting3").append(data[1]);
						$(".formHarian").css("display","none");
					}else if(MyContent == "COSTING6"){
						$("#contentcosting6").append(data[1]);
						$(".formHarian").css("display","none");
					}else if(MyContent == "COSTING8"){
						$("#contentcosting8").append(data[1]);
						$(".formHarian").css("display","none");
					}else if(MyContent == "COSTING12"){
						$("#contentcosting12").append(data[1]);
						$(".formHarian").css("display","none");
					}else if(MyContent == "COSTINGALL"){
						$("#contentcostingall").append(data[1]);
						$(".formHarian").css("display","none");
					}else if(MyContent == "HARIAN"){
						$(".formHarian").css("display","block");
						$(".loading").css("display","none")
						return false;
					}
				 generateDatatableKursPajak();	
						
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	
	
}


