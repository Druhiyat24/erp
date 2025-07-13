$( document ).ready(function() {
	
	getNama();
	generateSelectTanggal();
getMonth(new Date().getMonth());
console.log(months);
	  getDay();
	  idForm = '1';
	  nik = '';
	  nik2 = '';
	  n_id = '';
	  id = '';
	  
	  setTimeout(function(){
    $("#nama_skk").val('');
	$("#jabatan_skk").val('');
	$("#perusahaan_skk").val('');
	$("#alamat_skk").val('');
	$("#nama2_skk").val('');
	$("#department2_skk").val('');
	$("#bagian2_skk").val('');
	$("#tgllahir2_skk").val('');
	$("#tempatlahir2_skk").val('');
	$("#alamat2_skk").val('');
	$("#content_tglday1").val(date);
	$("#content_tglmonth1").val(months);
	$("#content_tglyears1").val(years);
	$("#footer_tglday").val(date);
	$("#footer_tglmonth").val(months);
	$("#footer_tglyears").val(years);	
	$("#footer_tglday2").val(date);
	$("#footer_tglmonth2").val(months);
	$("#footer_tglyears2").val(years);		
	$("#content_tglday2").val('');
	$("#content_tglmonth2").val('');
	$("#content_years2").val('');
	$("#content_tglday3").val('');
	$("#content_tglmonth3").val('');
	$("#content_years3").val('');
	$("#content_posisi").val('');
	$("#content_jabatan").val('');		
	$("#kontrak").val('');	
$("#gaji").val('');	
		  /*
		  			array_push($kontrakke,rawurlencode($row['kontrakkee']));
			array_push($partheader,rawurlencode($row['partheaderr']));
			array_push($partfooter,rawurlencode($row['partfooterr']));	
		  
		  */
		  
	  },2000);
	  		   setTimeout(function(){
			   defaultData();
		   },2000)
	//alert('123');
	
	
	    $('#tgllahir2_skk').datepicker
    ({  format: "yyyy-mm-dd",
        autoclose: true
    });		
	
});


function back(){
	$('#myOverlay').css('display','block');	
	window.location="./?mod=31A";
	
}


function print(){
	//window.location = './KontrakKerjaForm/pdf/index.php';
	window.open('./KontrakKerjaForm/pdf/index.php', '_blank');
}
	function getNama(){
		$('#myOverlay').css('display','block');	
     $.ajax({
        type:"POST",
        cache:false,
        url:"webservices/getNama.php",
        success: function (response) {
			console.log(response);
			d = JSON.parse(response);
			var StoreNik = d.records['nik'];
			var StoreNama = d.records['nama'];
			for (x = 0; x <= StoreNik.length; x++) {
		        $('#nama_skk').append('<option value="'+decodeURIComponent(StoreNik[x])+'" >'+decodeURIComponent(StoreNama[x])+'</option>');
				$('#nama2_skk').append('<option value="'+decodeURIComponent(StoreNik[x])+'" >'+decodeURIComponent(StoreNama[x])+'</option>');
		    }
        }
      });
	  $('#myOverlay').css('display','none');	
      return false;
	}
	function getDetailNama(Data,code){
		
		
		if(code == '1'){ 
		$('#myOverlay').css('display','block');
			nik = Data.value;
			console.log(nik);
		}
		if(code == '2'){
			nik2 = Data.value;
			console.log(nik2);
		}
			
			
    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getDetailnama.php",
        data : { nik : Data.value,code: code  },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
				d = JSON.parse(response);
				//console.log(d.message);
				if(d.message == '1'){
					var nik = Data.value;
					$('#jabatan_skk').val(decodeURIComponent(d.records['jabatan']));
					$('#perusahaan_skk').val(decodeURIComponent(d.records['perusahaan']));
					$('#alamat_skk').val(decodeURIComponent(d.records['alamat']));			
				}
				if(d.message == '2'){
					var nik2 = Data.value;
					$('#alamat2_skk').val(decodeURIComponent(d.records['alamat_karyawan']));	
					$('#department2_skk').val(decodeURIComponent(d.records['department']));
					$('#bagian2_skk').val(decodeURIComponent(d.records['bagian']));
					$('#tempatlahir2_skk').val(decodeURIComponent(d.records['tempat_lahir']));	
					$('#tgllahir2_skk').val(decodeURIComponent(d.records['tgl_lahir']));
					$("#content_posisi").val(decodeURIComponent(d.records['bagian']));
					$("#content_jabatan").val(decodeURIComponent(d.records['jabatan']));			
				}
        }
      });
				
			
			$('#myOverlay').css('display','none');	
	}
			

function defaultData(){
	console.log(sessionStorage);
	if((sessionStorage.idKontrakKerja)){
		idForm = '2';
		id = sessionStorage.idKontrakKerja;
		getDefaultData();
		//alert('123');
	}
}



function getDefaultData(){
	$('#myOverlay').css('display','block');	
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getDefaultDataKontrakKerja.php",
        data : { id : sessionStorage.idKontrakKerja,code : '1'  },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
				d = JSON.parse(response);
				console.log(d.records['gaji']);
				if(d.message == '1'){
						n_id = decodeURIComponent(d.records['n_id'])
						$("#nama_skk").val(decodeURIComponent(d.records['nik1']));
						$('#nama_skk').trigger('change');
						$("#jabatan_skk").val(decodeURIComponent(d.records['jabatanPT']));
						$("#perusahaan_skk").val(decodeURIComponent(d.records['perusahaan']));
						$("#alamat_skk").val(decodeURIComponent(d.records['alamatPT']));
						$("#nama2_skk").val(decodeURIComponent(d.records['nik2']));
						$('#nama2_skk').trigger('change');
						$("#department2_skk").val(decodeURIComponent(d.records['department']));
						$("#bagian2_skk").val(decodeURIComponent(d.records['bagian']));
						$("#tgllahir2_skk").val(decodeURIComponent(d.records['tanggallahir']));
						$("#tempatlahir2_skk").val(decodeURIComponent(d.records['tempatlahir']));
						$("#alamat2_skk").val(decodeURIComponent(d.records['alamat']));
						$("#content_tglday1").val(decodeURIComponent(d.records['day']));
						$("#content_tglmonth1").val(decodeURIComponent(d.records['month']));
						$("#content_tglyears1").val(decodeURIComponent(d.records['years']));
						$("#footer_tglday2").val(decodeURIComponent(d.records['d_insert_day']));
						$("#footer_tglmonth2").val(decodeURIComponent(d.records['d_insert_month']));
						$("#footer_tglyears2").val(decodeURIComponent(d.records['d_insert_years']));						
						$("#footer_tglday").val(decodeURIComponent(d.records['d_insert_day']));
						$("#footer_tglmonth").val(decodeURIComponent(d.records['d_insert_month']));
						$("#footer_tglyears").val(decodeURIComponent(d.records['d_insert_years']));								
						$("#content_tglday2").val(decodeURIComponent(d.records['dayheader']));
						$("#content_tglmonth2").val(decodeURIComponent(d.records['monthheader']));
						$("#content_years2").val(decodeURIComponent(d.records['yearsheader']));
						$("#content_tglday3").val(decodeURIComponent(d.records['dayfooter']));
						$("#content_tglmonth3").val(decodeURIComponent(d.records['monthfooter']));
						$("#content_years3").val(decodeURIComponent(d.records['yearsfooter']));
						$("#content_posisi").val(decodeURIComponent(d.records['bagian']));
						$("#content_jabatan").val(decodeURIComponent(d.records['jabatan']));
						$("#kontrak_ke").val(decodeURIComponent(d.records['kontrakke'][0]));
						$("#part_no").text(decodeURIComponent(d.records['partheader'][0]));
						$("#part_no_footer").text(decodeURIComponent(d.records['partfooter'][0]));		
						$("#gaji").val(decodeURIComponent(d.records['gaji'][0]));									
					idForm     = '2';
					//data.id     =  sessionStorage.idKeteranganKerja;
					//initial();
					//console.log(data);
					$('#myOverlay').css('display','none');	
				}
				if(d.message == '2'){
			
				}
        }
      });
}

			


	function save(){
    $('#myOverlay').css('display','block');	
	DataMaster ={
		nik                   :	nik,
		nik2                  :	nik2,
		nama_skk              :	$("#nama_skk").val() ,
		jabatan_skk           :	$("#jabatan_skk").val(),
		perusahaan_skk        :	$("#perusahaan_skk").val(),
		alamat_skk            :	$("#alamat_skk").val(),
		nama2_skk             :	$("#nama2_skk").val(),
		department2_skk       :	$("#department2_skk").val(),
		bagian2_skk           :	$("#bagian2_skk").val(),
		tgllahir2_skk         :	$("#tgllahir2_skk").val(),
		tempatlahir2_skk      :	$("#tempatlahir2_skk").val(),
		alamat2_skk           :	$("#alamat2_skk").val(),
		content_tglday1       :	$("#content_tglday1").val(),
		content_tglmonth1     :	$("#content_tglmonth1").val(),
		content_tglyears1     :	$("#content_tglyears1").val(),
		content_tglday2       :	$("#content_tglday2").val(),
		content_tglmonth2     :	$("#content_tglmonth2").val(),
		content_years2        :	$("#content_years2").val(),
		content_tglday3       :	$("#content_tglday3").val(),
		content_tglmonth3     :	$("#content_tglmonth3").val(),
		content_years3        :	$("#content_years3").val(),
		content_posisi        :	$("#content_posisi").val(),
		content_jabatan       :	$("#content_jabatan").val(), 
		content_kontrak_ke    :	$("#kontrak_ke").val(),
		content_part_no		  :	$("#part_no").text(),
		content_part_no_footer:	$("#part_no_footer").text(),  
		content_gaji          :	$("#gaji").val(),
		idForm				  : idForm,
		n_id			      : n_id,
		id 				      : id,
	}
	console.log(DataMaster);
	/*validdatiion */
	
	if(DataMaster.content_gaji == ''){
		alert('gaji Harus Diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}	
	
	if(DataMaster.content_kontrak_ke == ''){
		alert('Ups, Mohon isi field kontrak keberapa di nomor surat ?');
		 $('#myOverlay').css('display','none');	
		return false
	}	
	
	if(DataMaster.nik == ''){
		alert('Nama Pemberi Kontrak  Belum dipilih');
		 $('#myOverlay').css('display','none');	
		return false
	}
	if(DataMaster.nik2 == ''){
		alert('Nama Pekerja Belum dipilih');
		 $('#myOverlay').css('display','none');	
		return false
	}	
	
	if(DataMaster.jabatan_skk == ''){
		alert('Jabatan Pemberi Kontrak Harus diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}		
	if(DataMaster.jabatan_skk == ''){
		alert('Nama Perusahaan Harus diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}			
	if(DataMaster.alamat_skk == ''){
		alert('ALamat Perusahaan Harus diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}	
	if(DataMaster.department2_skk == ''){
		alert('Department Pekerja Harus diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}	
	if(DataMaster.bagian2_skk == ''){
		alert('Bagian/posisi pekerja harus diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}		
	if(DataMaster.tempatlahir2_skk == ''){
		alert('Tempat lahir pekerja harus diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}	
	if(DataMaster.bagian2_skk == ''){
		alert('Bagian/posisi pekerja harus diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}
	if(DataMaster.tgllahir2_skk == ''){
		alert('Tanggal lahir pekerja harus diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}	
	if(DataMaster.alamat2_skk == ''){
		alert('Alamat Detail pekerja harus diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}	
	if(DataMaster.content_tglday1 < 1){
		alert(DataMaster.content_tglday);
		console.log(DataMaster.content_tglday1);
		alert('Tanggal hari ini harus diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}	
	if(DataMaster.content_tglmonth1 < 1){
		alert('Bulan hari ini harus diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}	
	if(DataMaster.content_years1 < 1){
		
		alert('Tahun hari ini harus diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}	
	
	if(DataMaster.content_tglday2 < 1){
		alert('Tanggal mulai kontrak harus diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}	
	if(DataMaster.content_tglmonth2 < 1){
		alert('Bulan mulai kontrak harus diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}	
	if(DataMaster.content_years2 < 1){
		alert('Tahun mulai kontrak harus diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}


	
	if(DataMaster.content_tglday3 < 1){
		alert('Tanggal selesai kontrak harus diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}	
	if(DataMaster.content_tglmonth3 < 1){
		alert('Bulan selesai kontrak harus diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}	
	if(DataMaster.content_years3 < 1){
		alert('Tahun selesai kontrak harus diisi');
		 $('#myOverlay').css('display','none');	
		return false
	}
	

	
	 /*validation */
	//console.log(DataMaster);
    $.ajax({		
		type:"POST",
		cache:false,
        url:"webservices/saveKontrakKerja.php",
		data : { data: DataMaster },     // multiple data sent using ajax
		success: function (response) {
			d = JSON.parse(response);
			if(d.status == 'ok'){
				alert("Data BerHasil Disimpan");
				window.location="./?mod=31A";
			}
        }
      });		
	}