$( document ).ready(function() {
	localStorage.clear();
	sessionStorage.clear();
	GenerateTable();
		$("#period_from").datepicker( {
      format: "dd MM yyyy",

      autoclose: true
	});   
	
/* 	$("#period_from").datepicker( {
		format: "mm/yyyy",
		viewMode: "months",
		minViewMode: "months",
		autoclose: true
	}); */
	
	$("#period_to").datepicker( {
      format: "dd MM yyyy",

      autoclose: true
	});		
});
data = {
	iddatefrom : '',
	iddateto : '',
	tipejurnal : '',
	stts : ''
}
$reload = 0;
function back(){
	overlayon();
	location.reload(true);
}
function overlayon(){

	$("#myOverlay").css("display","block");
	
}
function overlayoff(){
	$("#myOverlay").css("display","none");
}

function check_journal(val){
	if(val == '1' || val == '2' || val == '17' ){
		$("#txtstatus").val('2').trigger("change");
	}else{
		$("#txtstatus").val('').trigger("change");
		
	}
	
	
}
//For Export Buttons available inside jquery-datatable "server side processing" - End
table = "";
$populasi_data = [];
function get_idx_array(arr,cari){
	for(var i=0;i<arr.length;i++){
		if(arr[i].id_sup == cari){
			return i
		}else{
			console.log("checking");
			
		}
	}
}
function handle(that){
	arr_attr_id = that.id.split("_");
	var id_sup = arr_attr_id[1];
	var field = arr_attr_id[0];
	idx = get_idx_array($populasi_data,id_sup);
	setTimeout(function(){
		if(field == 'mkt' ){
			if ($('#mkt_'+id_sup).is(':checked')) {
				$populasi_data[idx].b_mkt = '1';
			}else{
				$populasi_data[idx].b_mkt = '0';
			}
		}else if(field == 'shp'){
			if ($('#shp_'+id_sup).is(':checked')) {
				$populasi_data[idx].b_shp = '1';
			}else{
				$populasi_data[idx].b_shp = '0';
			}			
			
		}else if(field == 'inv'){
			if ($('#inv_'+id_sup).is(':checked')) {
				$populasi_data[idx].b_inv = '1';
			}else{
				$populasi_data[idx].b_inv = '0';
			}			
			
		}
		setTimeout(function(){
			console.log($populasi_data[idx]);
		},500)
		
	},500);

	
}

function save(){
	
	


	event.preventDefault();
	json = JSON.stringify($populasi_data);
    $.ajax({
        type: "POST",
        cache: false,
        url: "../marketting/webservices/saveblockcustomer.php",
        data: { code: '1', data:json },     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            var options = "";
            data = response;
            d = JSON.parse(data);
            if (d.message == '1') {
                alert("Data Berhasil diupdate");
					 window.location.href="?mod=block_customer";
				
            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }
        }
    });	
}

function GenerateTable(){

	table = $('#block_customer').DataTable( {
      'processing': true,
      'serverSide': true,
	  "lengthMenu": [[/*10, 25, 50,*/ 99999], [/*10, 25, 50,*/ "All"]],
		'serverMethod': 'post',
		
        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
        "ajax":  "../marketting/webservices/getBlockCustomer.php",
        "columns": [
		//code_customer
        {
			"data":           "code_customer",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,
		//nama_customer
        {
			"data":           "nama_customer",
         } ,			 

	

		 
		//b_mkt
        {
			"data":           null,
             "render":        function (data) {
				 
							return decodeURIComponent(data.c_mkt);
                            } 
         } ,	

		//b_inv
        {
			"data":           null,
             "render":        function (data) {
							return decodeURIComponent(data.c_inv);
                            } 
         } ,	

		//b_shp
        {
			"data":          null,
             "render":        function (data) {
							return decodeURIComponent(data.c_shp);
                            } 
         } ,	
        ],
 /*        "columnDefs": [
            {
                "targets": [ 3 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 4 ],
                "visible": false
            }
        ],	 */	
		
		
   "rowCallback": function( nRow, data, index ) {
	   var t_json = {
		   id_sup :'',
		   b_mkt   :'',
		   b_inv   :'',
		   b_shp   :''
	   }
			t_json.id_sup = data.id_sup;
			t_json.b_mkt = data.b_mkt;
			t_json.b_inv = data.b_inv;
			t_json.b_shp = data.b_shp;
			$populasi_data.push(t_json);
      },
      		
		"autoWidth": true,
		"scrollCollapse": true,
        scrollY:        "400px",
        scrollX:        true,
        scrollCollapse: true,
		
        "destroy": true,
		//order: [[ 1, "desc" ]],
		"ordering": false,
/*         fixedColumns:   {
            leftColumns: 1
        }, */
      dom: 'Bfrtip',
/*         buttons: [
{

              extend: 'excel', 
              text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export to Excel',
              className: 'btn-primary',
			  //title: 'Any title for file',
			          message: "Periode "+from_+" Sampai "+to_+" \n",
					  exportOptions:{
						  search :'applied',
						  order:'applied'
					  },

					   "action": newexportaction,
					   
					   
					   

                      }       
		
			
        ],  */
		//header: true,
		   /*  dom:
			"<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
			"<'row'<'col-sm-12'tr>>"+
    "<'row'<'col-sm-5'i><'col-sm-7'p>>", */	
    } );
}
