function Update_demo(){
	var idJournals =$("#kb").val();
	if(!idJournals){
		alert("Kontra Bon Harus Diisi!");
		return false;
	}
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/UpdateReturPengeluaran.php", 
        data : { code : '1', idJournal:idJournals },     // multiple data sent using ajax
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				if(d.message == '1'){
					if(d.message_report == '0'){
						alert("Kontra Bon Tidak Ditemukan");
						return false;
					}
					alert("Data Berhasil DiUpdate!");
					location.reload();
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });		 
 }
 function Update_live(){
 	var idJournals =$("#kb_live").val();
	if(!idJournals){
		alert("Kontra Bon Harus Diisi!");
		return false;
		
	}
	    $.ajax({		
        type:"POST",
		cache:false, 
        url:"http://nag.ip-dynamic.com:8080/erp/pages/fin/webservices/updatePembelian.php", 
        data : { code : '1', idJournal:idJournals },     // multiple data sent using ajax
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				if(d.message == '1'){
					if(d.message_report == '0'){
						alert("Kontra Bon Tidak Ditemukan");
						return false;
					}
					alert("Data Berhasil DiUpdate!");
					location.reload();
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
 }

 
 function GetServer(val){
	 if(val == '2'){
		 $("#Demo").css('display','none');
		 $("#Live").css('display','');
		 
	 }else{
		$("#Demo").css('display','');
		 $("#Live").css('display','none'); 
	 }
	 
	 
 }
