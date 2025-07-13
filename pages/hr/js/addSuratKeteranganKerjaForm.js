$( document ).ready(function() {
	getNama();
	generateSelectTanggal();
	getMonth(new Date().getMonth());
	console.log(months);
	getDay();
	  setTimeout(function(){
		 data = {
			 id 		: '',
			 nama 		: '',
			 nama2 		: '',
			 nik 		: '',
			 nik2 		: '',
			 bagian 	: '',
			 department : '',
			 jabatan 	: '',
			 lamabekerja: '',
			 reason 	: '',
			 day 		: date,
			 month 		: months,
			 years 		: years,
			 idForm		: '1',
		 } ;
		 initial();
		 defaultData();
	  },1000);
});



function defaultData(){

	//console.log(sessionStorage);
	if((sessionStorage.idKeteranganKerja)){
		data.idForm = '2';
		getDefaultData();
	}
}

function getDefaultData(){
	$('#myOverlay').css('display','block');	
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getDefaultDataKeteranganKerja.php",
        data : { id : sessionStorage.idKeteranganKerja,code : '1'  },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
				d = JSON.parse(response);
				console.log(d.records);
				if(d.message == '1'){
					data.jabatan    = decodeURIComponent(d.records['jabatan']);
					data.department = decodeURIComponent(d.records['department']);
					data.alamat     = decodeURIComponent(d.records['alamat']);
					data.nama       = decodeURIComponent(d.records['nama']);
					data.nik2       = decodeURIComponent(d.records['nik2']);
					data.nik        = decodeURIComponent(d.records['nik']);
					data.day        = decodeURIComponent(d.records['day']);
					data.month      = decodeURIComponent(d.records['month']);
					data.years      = decodeURIComponent(d.records['years']);    
					data.lamabekerja      = decodeURIComponent(d.records['lamakerja']); 
					data.reason      = decodeURIComponent(d.records['reason']); 
					data.idForm     = '2';
					data.id     =  sessionStorage.idKeteranganKerja;
					initial();
					console.log(data);
					$('#content_nama').trigger('change');
					
					$('#footer_personpt').trigger('change');
					$('#myOverlay').css('display','none');	
				}
				if(d.message == '2'){
			
				}
        }
      });
}





function back(){
	$('#myOverlay').css('display','block');
	window.location="./?mod=33A";
}

function handleKeyUp (props){

	if(props.id == 'content_lamabekerja'){
		data.lamabekerja = props.value;
		console.log(props.value);
	}
	if(props.id == 'content_alasan'){
		data.reason = props.value;	
		console.log(props.value);
	}
}

function initial(){
	$("#content_nama").val(data.nik2);
	$("#footer_personpt").val(data.nik);
	$("#content_noinduk").val(data.nik2);
	$("#content_department").val(data.department);
	$("#content_jabatan").val(data.jabatan);
	$("#content_lamabekerja").val(data.lamabekerja);
	$("#content_alasan").val(data.reason);
	$("#footer_tglday").val(data.day);
	$("#footer_tglmonth").val(data.month);
	$("#footer_tglyears").val(data.years);	
}

	function getNama(){
     $.ajax({
        type:"POST",
        cache:false,
        url:"webservices/getNamaKeteranganKerja.php",
        success: function (response) {
			console.log(response);
			d = JSON.parse(response);
			var StoreNik = d.records['nik'];
			var StoreNama = d.records['nama'];
			for (x = 0; x <= StoreNik.length; x++) {
		        $('#content_nama').append('<option value="'+decodeURIComponent(StoreNik[x])+'" >'+decodeURIComponent(StoreNama[x])+'</option>');
				$('#footer_personpt').append('<option value="'+decodeURIComponent(StoreNik[x])+'" >'+decodeURIComponent(StoreNama[x])+'</option>');
		    }
        }
      });
      return false;
	}
	function getDetailNama(Data,code){
		$('#myOverlay').css('display','block');
		if(code == '1'){
			data.nik2 = Data.value;
//			console.log(nik);
		}
		if(code == '2'){
			data.nik = Data.value;
			return false;
		}
			
    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getDetailKeteranganKerja.php",
        data : { nik : Data.value,code: code  },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
				d = JSON.parse(response);
				console.log(d.message);
				if(d.message == '1'){
					data.jabatan = decodeURIComponent(d.records['jabatan']);
					data.department = decodeURIComponent(d.records['department']);
						  $('#myOverlay').css('display','none');
					
					initial();
				}
				if(d.message == '2'){
						  $('#myOverlay').css('display','none');
			
				}
        }
      });

	}
	
	function save(){
	//console.log(DataMaster);
	/*
		data.jabatan    = decodeURIComponent(d.records['jabatan']);
		data.department = decodeURIComponent(d.records['department']);
		data.alamat     = decodeURIComponent(d.records['alamat']);
		data.nama       = decodeURIComponent(d.records['nama']);
		data.nik2       = decodeURIComponent(d.records['nik2']);
		data.nik        = decodeURIComponent(d.records['nik']);
		data.day        = decodeURIComponent(d.records['day']);
		data.month      = decodeURIComponent(d.records['month']);
		data.years      = decodeURIComponent(d.records['years']);    
		data.lamabekerja      = decodeURIComponent(d.records['lamakerja']); 
		data.reason      = decodeURIComponent(d.records['reason']); 
		data.idForm     = '2';
		data.id     =  sessionStorage.idPengunduranDiri;	
	
	*/
	
	if(data.jabatan == ''){
		alert('Jabatan harus diisi')
		return false;
		
	}

	if(data.department == ''){
		alert('Department harus diisi')
		return false;
		
	}
	if(data.nik2 == ''){
		alert('Seperti nya ada kesalahan, mohon reload page ini !')
		return false;
		
	}
	if(data.nik == ''){
		alert('Nama Footer harus diisi !')
		return false;
		
	}	
	if(data.day == ''){
		alert('Tanggal Surat Harus Diisi !')
		return false;
		
	}	
	if(data.month == ''){
		alert('Bulan Surat Harus Diisi !')
		return false;
		
	}		
	if(data.years == ''){
		alert('Tahun Surat Harus Diisi !')
		return false;
		
	}		
	if(data.lamabekerja == ''){
		alert('lama Bekerja harus diisi Harus Diisi !')
		return false;
		
	}	
	if(data.reason == ''){
		alert('Alasan keluar harus diisi Harus Diisi !')
		return false;
	}	
	if(data.idForm == ''){
			alert('Seperti nya ada kesalahan, mohon reload page ini !')
		return false;
	}	

	$('#myOverlay').css('display','block');
    $.ajax({		
		type:"POST",
		cache:false,
        url:"webservices/saveKeteranganKerja.php",
		data : { data: data },     // multiple data sent using ajax
		success: function (response) {
			d = JSON.parse(response);
			if(d.status == 'ok'){
				alert("Data BerHasil Disimpan");
				window.location = './?mod=33A'
				
			}

        }
      });		
	}