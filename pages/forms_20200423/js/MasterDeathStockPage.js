$( document ).ready(function() {
localStorage.clear();
sessionStorage.clear();
getListData();
data = {
	 crud               :'INSERT'
	,c_type            :'0'
	,n_id              :''
	,v_klasifikasi     :''
	,v_c_bahan_baku    :''
	,v_n_bahan_baku    :''
	,v_color           :''
	,v_ukuran          :''
	,n_qty             :''
	,v_unit            :'0'
	,v_no_rak          :''
	,v_keterangan      :''
	,d_insert          :''
	,v_user_insert     :''
	,d_update          :''
	,v_user_update	   :''
}



    getMasterMeasurement().then(function(d) {
		var options = "";
			options += "<option value='0' disabled> --Pilih Unit-- </option>";
            if (d.message == '1') {

                for (var x = 0; x < d.records.length; x++) {
                    options += "<option value='" + decodeURIComponent(d.records[x].id) + "'>" + decodeURIComponent(d.records[x].nama) + "</option>"

                }

                $("#v_unit").append(options);

            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }		
	});	

});
table = '';


function getMasterMeasurement() {
	return new Promise(function(resolve, reject) {
    $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getMasterMeasurement.php",
        data: { code: '1' },     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            var options = "";
            var data_measurement = response;
            d = JSON.parse(data_measurement);
			resolve(d);
        }
    });		
		
	});
}



function news(){
	


$('#c_type').val('0');
$('#n_id').val('');
$('#v_klasifikasi').val('');
$('#v_c_bahan_baku').val('');
$('#v_n_bahan_baku').val('');
$('#v_color').val('');
$('#v_ukuran').val('');
$('#n_qty').val('');
$('#v_unit').val('0').trigger('change');
$('#v_no_rak').val('');
$('#v_keterangan').val('');
$('#d_insert').val('');
$('#v_user_insert').val('');
$('#d_update').val('');
$('#v_user_update').val('');
	
	
data.crud              ='INSERT';
data.c_type            ='0';
data.n_id              ='';
data.v_klasifikasi     ='';
data.v_c_bahan_baku    ='';
data.v_n_bahan_baku    ='';
data.v_color           ='';
data.v_ukuran          ='';
data.n_qty             ='';
data.v_unit            ='';
data.v_no_rak          ='';
data.v_keterangan      ='';
data.d_insert          ='';
data.v_user_insert     ='';
data.d_update          ='';
data.v_user_update	   ='';

$('#myModal_ds').modal('show'); 
}
function getListData(){
   if(table !=""){
    table.clear();
	table.destroy();
    //table.rows.add(newDataArray);
    //table.draw();	   
   }
  table = $('#MasterDeathStock').DataTable( {
		"ajax": "webservices/getListDataMasterDeathStock.php",
        "columns": [
			{ "data": "c_type" },
			{ "data": "v_klasifikasi"},
			{ "data": "v_c_bahan_baku" },
            { "data": "v_n_bahan_baku" },
            { "data": "v_color"}, 
			{ "data": "v_ukuran"},
			{ "data": "n_qty"},
            { "data": "v_unit" },
			{ "data": "v_no_rak" },
			{ "data": "v_keterangan" },
             {

                "orderable":      '',
                "data":           null,
                "mRender": function (data) {
                    return decodeURIComponent(data.button);
                            }
            } 
        ],
        "order": [[0, 'desc']]
    } );
}





function edit($id_nya){
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getListDataMasterDeathStock.php",
        data: { code: '1', id: $id_nya },     // multiple data sent using ajax
        success: function (response) {
            datass = response;
            d = JSON.parse(datass);
            console.log(d.records.length);
			if(d.status == 'ok'){
            if (d.message == '1') {
				$('#c_type').val(d.records[0].c_type);
				$('#n_id').val(d.records[0].n_id);
				$('#v_klasifikasi').val(d.records[0].v_klasifikasi);
				$('#v_c_bahan_baku').val(d.records[0].v_c_bahan_baku);
				$('#v_n_bahan_baku').val(d.records[0].v_n_bahan_baku);
				$('#v_color').val(d.records[0].v_color);
				$('#v_ukuran').val(d.records[0].v_ukuran);
				$('#n_qty').val(d.records[0].n_qty);
				$('#v_unit').val(d.records[0].v_unit).trigger('change');
				$('#v_no_rak').val(d.records[0].v_no_rak);
				$('#v_keterangan').val(d.records[0].v_keterangan);
				$('#d_insert').val(d.records[0].d_insert);
				$('#v_user_insert').val(d.records[0].v_user_insert);
				$('#d_update').val(d.records[0].d_update);
				$('#v_user_update').val(d.records[0].v_user_update);
				data = d.records[0];
				data.crud = 'UPDATE';
				$('#myModal_ds').modal('show'); 
            }
            if (d.message == '2') {
                alert("Data Belum ada");
            }				
			}else{
				alert(d.message);
				
			}
        }
    });	
}
$my_saver = 0;
function save(){
	if(data.crud != 'DELETE'){
		if(data.c_type        =='0'){ alert('Tipe Harus Diisi');return false; }		
		if(data.v_klasifikasi ==''){ alert('Klasifikasi Harus Diisi');return false; }		
		if(data.v_c_bahan_baku==''){ alert('Kode Bahan Baku Harus Diisi');return false; }		
		if(data.v_n_bahan_baku==''){ alert('Nama Bahan Baku Harus Diisi');return false; }		
		if(data.v_color       ==''){ alert('Color Harus Diisi');return false; }		
		if(data.v_ukuran      ==''){ alert('Ukuran Harus Diisi');return false; }		
		if(data.n_qty         ==''){ alert('Qty Harus Diisi');return false; }		
		if(data.v_unit        =='0'){ alert('Unit Harus Diisi');return false; }		
		if(data.v_no_rak      ==''){ alert('No. Rak Harus Diisi');return false; }		
	}
	$("#my_loading").css("display","");
	if($my_saver > 0){
		return false;
	}
	$my_saver++;
     $.ajax({	
        type: "POST",
        cache: false,
        url: "webservices/ServicesDeathStock.php",
        data: { code: '1', data:data },     // multiple data sent using ajax
        success: function (response) {
            datass = response;
            d = JSON.parse(datass);
            console.log(d.records.length);
			if(d.status == 'ok'){
            if (d.message == '1') {
				alert("Data Berhasil Di Update!");
				$my_saver = 0;
				getListData();
				$('#myModal_ds').modal('hide');
				$("#my_loading").css("display","none");
            }
            if (d.message == '2') {
                alert("Data Belum ada");
            }				
			}else{
				alert(d.message);
				
			}
        }
    });		
}

function deletes($id_nya){
	data.crud = 'DELETE';
	data.n_id = $id_nya;
	save();
	
}


function handleKeyUp(that){
	console.log(that.id);
	if(that.id=='v_keterangan'){data.v_keterangan = that.value; }
		
	else if(that.id=='c_type'){data.c_type = that.value; }
	else if(that.id=='v_klasifikasi'){data.v_klasifikasi = that.value; }
	else if(that.id=='v_c_bahan_baku'){data.v_c_bahan_baku = that.value; }
	else if(that.id=='v_n_bahan_baku'){data.v_n_bahan_baku = that.value; }
	else if(that.id=='v_color'){data.v_color = that.value; }
	else if(that.id=='v_ukuran'){data.v_ukuran = that.value; }
	else if(that.id=='n_qty'){data.n_qty = that.value; }
	else if(that.id=='v_unit'){data.v_unit = that.value; }
	else if(that.id=='v_no_rak'){data.v_no_rak = that.value; }
}