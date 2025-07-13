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
			 alamat		: '',
			 department : '',
			 jabatan 	: '',
			 day 		: date,
			 month 		: months,
			 years 		: years,
			 dayheader 	: date,
			 monthheader: months,
			 yearsheader: years,			
			 dayfooter 	: date,
			 monthfooter: months,
			 yearsfooter: years,				 
			 idForm		: '1',
		 } ;
		 initial();
		 defaultData();
	  },1000);
	
});

function back(){
	window.location = './?mod=32A';
	
}


function defaultData(){

	console.log(sessionStorage.idPengunduranDiri);
	if((sessionStorage.idPengunduranDiri)){
		data.idForm = '2';
		getDefaultData();
	}
}

function getDefaultData(){
	$('#myOverlay').css('display','block');	
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getDefaultDataPengunduranDiri.php",
        data : { id : sessionStorage.idPengunduranDiri,code : '1'  },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
				d = JSON.parse(response);
				console.log(d.records);
				if(d.message == '1'){
					data.jabatan    = decodeURIComponent(d.records['jabatan']);
					data.department = decodeURIComponent(d.records['department']);
					data.alamat     = decodeURIComponent(d.records['alamat']);
					data.nama       = decodeURIComponent(d.records['nama']);
					data.nik2       = decodeURIComponent(d.records['nik']);
					data.nik        = decodeURIComponent(d.records['nik']);
					data.alamat     = decodeURIComponent(d.records['alamat']);
					data.jabatan    = decodeURIComponent(d.records['jabatan']);
					data.day        = decodeURIComponent(d.records['day']);
					data.month      = decodeURIComponent(d.records['month']);
					data.years      = decodeURIComponent(d.records['years']);
					data.dayheader  = decodeURIComponent(d.records['dayheader']);
					data.monthheader= decodeURIComponent(d.records['monthheader']);
					data.yearsheader= decodeURIComponent(d.records['yearsheader']);  
					data.dayfooter  = decodeURIComponent(d.records['dayfooter']);
					data.monthfooter= decodeURIComponent(d.records['monthfooter']);
					data.yearsfooter= decodeURIComponent(d.records['yearsfooter']);     
					data.idForm     = '2';
					data.id     =  sessionStorage.idPengunduranDiri;
					initial();
					console.log(data);
					$('#content_nama').trigger('change');
					$('#myOverlay').css('display','none');	
				}
				if(d.message == '2'){
			
				}
        }
      });
}
function handleKeyUp(props){
	if(props.id == 'content_tglday'){
		data.day = props.value;
	}
	if(props.id == 'content_tglmonth'){
		data.month = props.value;
	}
	if(props.id == 'content_tglyears'){
		data.year = props.value;
	}	
	if(props.id == 'header_tglday'){
		data.dayheader = props.value;
		data.dayfooter = props.value;
		console.log(props.id);
		initial();
		
	}	
	if(props.id == 'header_tglmonth'){
		data.monthheader = props.value;
		data.monthfooter = props.value;
		initial();
	}
	if(props.id == 'header_tglyears'){
		data.yearsheader = props.value;
		data.yearsfooter = props.value;
		initial();

	}	
	if(props.id == 'footer_tglday'){
		data.dayfooter = props.value;
	}	
	if(props.id == 'footer_tglmonth'){
		data.monthfooter = props.value;
	}
	if(props.id == 'footer_tglyears'){
		data.yearsfooter = props.value;
	}
}

function initial(){
	$("#content_nama").val(data.nik2);
	$("#content_jabatan").val(data.jabatan);
	$("#content_tglday").val(data.day);
	$("#content_tglmonth").val(data.month);
	$("#content_tglyears").val(data.years);
	$("#header_tglday").val(data.dayheader);
	$("#content_alamat").val(data.alamat);
	$("#header_tglmonth").val(data.monthheader);
	$("#header_tglyears").val(data.yearsheader);
	$("#footer_tglday").val(data.dayfooter);
	$("#footer_tglmonth").val(data.monthfooter);
	$("#footer_tglyears").val(data.yearsfooter);	



//$('#content_jabatan').select2().select2('val', $('.select2 option:eq(1)').val());

}
	function getNama(){
     $.ajax({
        type:"POST",
        cache:false,
        url:"webservices/getNamaPengunduranDiri.php",
        success: function (response) {
			//console.log(response);
			d = JSON.parse(response);
			var StoreNik = d.records['nik'];
			var StoreNama = d.records['nama'];
			for (x = 0; x <= StoreNik.length; x++) {
		        $('#content_nama').append('<option value="'+decodeURIComponent(StoreNik[x])+'" >'+decodeURIComponent(StoreNama[x])+'</option>');
		    }
        }
      });
      return false;
	}
	function getDetailNama(Data,code){
		if(code == '1'){
			data.nik = Data.value;
//			console.log(nik);
		}
		if(code == '2'){
			data.nik2 = Data.value;
			return false;
		}	
    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getDetailNamaPengunduranDiri.php",
        data : { nik : Data.value,code: code  },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
				d = JSON.parse(response);
				console.log(d);
				if(d.message == '1'){
					data.jabatan = decodeURIComponent(d.records['jabatan']);
					data.department = decodeURIComponent(d.records['department']);
					data.alamat = decodeURIComponent(d.records['alamat']);
					data.nama = decodeURIComponent(d.records['nama']);
					data.day        = decodeURIComponent(d.records['day']);
					data.month      = decodeURIComponent(d.records['month']);
					data.years      = decodeURIComponent(d.records['years']);					
					initial();
					console.log(data);
				}
				if(d.message == '2'){
			
				}
        }
      });
	}
	
	function save(){
	//console.log(DataMaster);
	console.log(data);
	if(data.nik2 == ''){
		alert('Nama Karyawan belum diisi');
		return false;
	}

	if(data.jabatan == ''){
		alert('Jabatan Harus diisi');
		return false;
	}	
	
	if(data.idForm == '')
	{
		alert('Ada Kesalahan Mohon Reload halaman ini !');
		return false;
		
	}

	if(data.day == '-1'){
		alert('tanggal masuk harus diisi');
	return false;
	}
	if(data.month == '-1'){
		alert('bulan masuk diisi');	
	return false;
	}
	if(data.years == '-1'){
		alert('tahun nasuk harus diisi');
	return false;
	}	
	
	
	if(data.dayheader == '-1'){
		alert('header tanggal harus diisi');
		return false;
	}
	if(data.monthheader == '-1'){
		alert('bulan harus diisi');
return false;		
	}
	if(data.yearsheader == '-1'){
		alert('tahun header harus diisi');
	return false;
	}
	if(data.dayfooter == '-1'){
		alert('footer tanggal harus diisi');
	return false;
	}
	if(data.monthfooter == '-1'){
		alert('bulan footer diisi');	
	return false;
	}
	if(data.yearsfooter == '-1'){
		alert('tahun footer harus diisi');
	return false;
	}	
	
	
	if(data.alamat == ''){
		alert('tahun footer harus diisi');
	return false;
	}		
	$('#myOverlay').css('display','block');	
    $.ajax({		
		type:"POST",
		cache:false,
        url:"webservices/savePengunduranDiri.php",
		data : { data: data },     // multiple data sent using ajax
		success: function (response) {
			d = JSON.parse(response);
			if(d.status == 'ok'){
				alert("Data BerHasil Disimpan");
				window.location="./?mod=32A";
			}
        }
      });		
	}