$( document ).ready(function() {
	
url =window.location.search;
url =url.split("&");
console.log(url);
setTimeout(function(){ 				
if(url[1]){
	subpathurl = url[1].split("=");
	id = subpathurl[1];
	getComponentAttr(id);
	totalnilais();
	var vall = $("#totalnilai").val();
	formatRupiah(vall,'total');
	
}
}, 2000);	




getTipeAktivaList();
getAkunActiva();
getAkunBiayaPenyusutan();
getAkunAkumulasiPenyusutan();
getMasterCostCenter();

    $("#tanggalbeli").datepicker( {
      format: "dd/mm/yyyy",
      autoclose: true
    });
	
    $("#tanggalpakai").datepicker( {
      format: "dd/mm/yyyy",
      autoclose: true
    });	
	
    $("#periodeakuntansi").datepicker( {
      format: "mm/yyyy",
      viewMode: "months",
      minViewMode: "months",
      autoclose: true
    });		




	
});
	
	
		function formatRupiah(angka,field){
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
			if(field == "nilai"){
				$("#nilai").val(rupiah);
				
			}
			if(field == "total"){
				$("#totalnilai").val(rupiah);
				
			}			
			
		}

function totalnilais(){
	nilaiss = $("#nilai").val();
	nilaiss = nilaiss.replace(/[^,\d]/g, '')
	formatRupiah(nilaiss,'nilai');
	
	
	
	
	qtyss = $("#qty").val();
	//nilaiss = $("#nilai").val();
	
	
	total = qtyss * nilaiss;
	$("#totalnilai").val(total);
}

function changenilai(Item){
	console.log(Item);
	formatRupiah(Item.value,'total');
	
}


	
	function getComponentAttr(Item){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getComponentAttr.php", 
        data : { code : '1',id_journal:Item },     // multiple data sent using ajax
        success: function (response) {
			console.log(response);
				d = JSON.parse(response);
				//d = response;
				tempdepartment = {value:''};
				tempsubdepartment  = '';
				if(d.message == '1'){
						$("#tipeaktiva").val(d.id[0][0].id);
						$("#akunactiva").val(d.akun[0][0].akunactiva);
						$('#akunactiva').trigger('change');
						$("#akunakumulasipenyusutan").val(d.akun[0][0].akunakumulasipenyusutan);
						$("#department").val(d.akun[0][0].department);
						$('#akunakumulasipenyusutan').trigger('change');
						$("#akunbiayapenyusutan").val(d.akun[0][0].akunbiayapenyusutan);
						$('#akunbiayapenyusutan').trigger('change');
						tempdepartment.value  = d.akun[0][0].department;
						tempsubdepartment = d.akun[0][0].subdepartment;
						console.log(d.akun[0][0].subdepartment);
						getMasterSubCostCenter(tempdepartment);
						
						setTimeout(function(){ 				
						console.log("Begin val subdepartment")
						$("#subdepartment").val(tempsubdepartment);
}, 1000);
						
						
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	
	
}		
	
	
	function getMasterCostCenter(){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getMasterCostCenter.php", 
        data : { code : '1' },     // multiple data sent using ajax
        success: function (response) {
			option = '';
			option += "<option> </option>"
			//console.log(response);
				d = JSON.parse(response);
				//d = response;
console.log(d);
				if(d.message == '1'){
						for(i=0;i<d.records.length;i++){
							option += "<option value="+decodeURIComponent(d.records[i][0].id)+">"+decodeURIComponent(d.records[i][0].nama)+"</option>";
						}//department
						$("#department").append(option);
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	
	
}		
	

	function getMasterSubCostCenter(Item){
		console.log(Item);
	$("#subdepartment").empty();
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getMasterSubCostCenter.php", 
        data : { code : '1',id:Item.value },     // multiple data sent using ajax
        success: function (response) {
			option = "";
			option += "<option> </option>";
			//console.log(response);
				d = JSON.parse(response);
				//d = response;
console.log(d);
				if(d.message == '1'){ 
						for(i=0;i<d.records.length;i++){
							option += "<option value="+decodeURIComponent(d.records[i][0].id)+">"+decodeURIComponent(d.records[i][0].nama)+"</option>";
						}//department
						$("#subdepartment").append(option);
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
}		
	function getTipeAktivaDetail(datass){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getMasterDetailTipeAktiva.php", 
        data : { code : '1', codeaktiva : datass.value },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
				d = JSON.parse(response);
				//d = response;
				option  = '';
				renders = '';
				//console.log(d.records.length);
				if(d.message == '1'){
						$("#kodeaktiva").val(d.records[0][0].kodeaktivacustom);
						$("#umurtahunactiva").val(d.records[0][0].years);
						$("#umuractiabulan").val(d.records[0][0].month);
						$("#namaaktiva").val(d.records[0][0].namaaktiva);
				setTimeout(function(){ 				

				}, 3000);						
						
						
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	
	
}	
	
	function getAkunActiva(){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getAkunActiva.php", 
        data : { code : '1',   },     // multiple data sent using ajax
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
							option += "<option value="+decodeURIComponent(d.records[i][0].id)+">"+decodeURIComponent(d.records[i][0].id) +" "+ decodeURIComponent(d.records[i][0].nama)+"</option>";
						}//department
						$("#akunactiva").append(option);

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


