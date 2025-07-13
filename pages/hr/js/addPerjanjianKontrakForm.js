$( document ).ready(function() {
	getNama();
	generateSelectTanggal();
	getMonth(new Date().getMonth());
	console.log(months);
	getDay();
	  setTimeout(function(){
		 data = {
			 id 			: '',
			 nama 			: '',
			 nama2 			: '',
			 nik 			: '',
			 nik2 			: '',
			 bagian 		: '',
			 alamat			: '',
			 periodekontrak :'',
			 jabatan 		: '',
			 jabatan2 		: '',
			 tglhrd 		: '',
			 tglkaryawan	: '',			 
			 idForm			: '1',
		 } ;
		 initial();
		 defaultData();
	  },1000);
	

	
    $('#footer_tanggalhrd').datepicker
    ({  format: "dd M yyyy",
        autoclose: true
    });	
	    $('#footer_tanggalpekerja').datepicker
    ({  format: "dd M yyyy",
        autoclose: true
    });	
	
});


	function handleChange(Data){
		
		if(Data.id == 'content_periodekontrak'){
			data.periodekontrak = Data.value;
			//console.log(data.periodekontrak);

		}
		else{
			
			data.tglkaryawan = Data.value;
			data.tglhrd = Data.value;
			console.log(data.tglhrd);
			$("#footer_tanggalpekerja").val(Data.value);
			$("#footer_tanggalhrd").val(Data.value);			
		}
		
	}
function back(){
	window.location = './?mod=34A';
}
function defaultData(){
	console.log(sessionStorage);
	console.log(sessionStorage.idPerjanjianKerja);
	if((sessionStorage.idPerjanjianKerja)){
		data.idForm = '2';
		getDefaultData();
	}
}
function getDefaultData(){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getDefaultDataPerjanjianKerja.php",
        data : { id : sessionStorage.idPerjanjianKerja,code : '1'  },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
				d = JSON.parse(response);
				console.log(d.records);
				if(d.message == '1'){
					/*
			 id 			: '',
			 nama 			: '',
			 nomorsurat		: '',
			 nama2 			: '',
			 nik 			: '',
			 nik2 			: '',
			 bagian 		: '',
			 alamat			: '',
			 periodekontrak :'',
			 jabatan 		: '',
			 jabatan2 		: '',
			 tglhrd 		: '',
			 tglkaryawan	: '',			 
			 idForm			: '1',					
					
					
					*/
					data.nama      = decodeURIComponent(d.records['namaPPT']);
					data.nama2     = decodeURIComponent(d.records['nama']);
					data.nik2      = decodeURIComponent(d.records['nik2']);
					data.nik       = decodeURIComponent(d.records['nik']);
					data.nomorsurat= decodeURIComponent(d.records['nomorsurat']);
					data.tglhrd= decodeURIComponent(d.records['d_create']);
					data.tglkaryawan= decodeURIComponent(d.records['d_create']);
					data.jabatan2  = decodeURIComponent(d.records['jabatan2']);
					data.jabatan   = decodeURIComponent(d.records['jabatan']);
					data.alamat    = decodeURIComponent(d.records['alamat']);
					data.periodekontrak = decodeURIComponent(d.records['periodekontrak']);
					$('#content_periodekontrak').val(data.periodekontrak);
					data.idForm    = '2';
					data.id        =  sessionStorage.idPerjanjianKerja;
					initial();
					//$('#content_nama').trigger('change');
					console.log(data);
					setTimeout(function(){
						$('#content_nama').trigger('change');
						$('#content_melaporkepada').trigger('change');
						
					},2000);
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
	$("#content_alamatkaryawan").val(data.alamat);
	$("#content_posisikaryawan").val(data.jabatan2);
	$("#content_melaporkepada").val(data.nik);
	$("#footer_namahrd").val(data.nama);
	$("#footer_posisihrd").val(data.jabatan);
	$("#footer_namapekerja").val(data.nama2);
	$("#footer_tanggalpekerja").val(data.tglhrd);
	$("#footer_tanggalhrd").val(data.tglhrd);
//$('#content_jabatan').select2().select2('val', $('.select2 option:eq(1)').val());
}
	function getNama(){
     $.ajax({
        type:"POST",
        cache:false,
        url:"webservices/getNamaPerjanjianKerja.php",
        success: function (response) {
			//console.log(response);
			d = JSON.parse(response);
			var StoreNik = d.records['nik'];
			var StoreNama = d.records['nama'];
			for (x = 0; x <= StoreNik.length; x++) {
		        $('#content_nama').append('<option value="'+decodeURIComponent(StoreNik[x])+'" >'+decodeURIComponent(StoreNama[x])+'</option>');
				$('#content_melaporkepada').append('<option value="'+decodeURIComponent(StoreNik[x])+'" >'+decodeURIComponent(StoreNama[x])+'</option>');
		    }
        }
      });
      return false;
	}
	function getDetailNama(Data,code){
		console.log(code);
		if(code == '1'){
			data.nik = Data.value;
//			console.log(nik);
		}
		if(code == '2'){
			data.nik2 = Data.value;
		}	
    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getDetailNamaPerjanjianKontrak.php",
        data : { nik : Data.value,code: code  },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
				d = JSON.parse(response);
				//console.log(d.message);
				if(d.message == '1'){
					data.jabatan = decodeURIComponent(d.records['jabatan']);
					data.department = decodeURIComponent(d.records['department']);
					data.nama = decodeURIComponent(d.records['nama']);
					initial();
				}
				if(d.message == '2'){
					data.jabatan2 = decodeURIComponent(d.records['jabatan']);
					data.nama2 = decodeURIComponent(d.records['nama']);
					data.alamat = decodeURIComponent(d.records['alamat']);
					initial();
					console.log(data.nama2);
				}
        }
      });
	}
	
	function save(){
	
    $.ajax({		
		type:"POST",
		cache:false,
        url:"webservices/savePerjanjianKerja.php",
		data : { data: data },     // multiple data sent using ajax
		success: function (response) {
			d = JSON.parse(response);
			if(d.status == 'ok'){
				alert("Data BerHasil Disimpan");
				window.location="./?mod=34A";
			}
        }
      });		
	}