$( document ).ready(function() {
	getListData();
	//rendertableDetail("x");
	/*Ddata ={
		check : [],
		no_list : [],
		no_kontra_bon : [],
		tanggal_kontra_bon : [],
		nama_supplier : [],
		nilai_kontra_bon : [],
		umur_utang : [],
		tanggal_jatuh_tempo : [],
		status : [],
		notes : [],
	}
	*/
	Store = [];
	StoreList = [];
	stage = 0;
	
});


function back(){
	getListDataRekap(tmp_list_code);
	tmp_list_code = '';
	
	
	
}

function Save(){
	
	//validasi
	var ck = 0;
for(var i=0;i<Store.length;i++){
	if(Store[i].checkbox == '1'){
		ck++;
		if(Store[i].code_status == 'S'){
			alert("Status Harus Diisi");
			return false;
			
		}
		
	}
	
	
}
if(ck < 1){
	alert("Tidak ada data yang dipilih!");
	return false;
}
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/SaveApproval.php", 
        data : { code : '1', data:Store  },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
				if(d.message == '1'){
					alert("Data Berhasil Di Update!");
					location.reload();
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	  
}

tmp_list_code = '';

function getDetailPo(carinya,l_code,po_no){
tmp_list_code = l_code;
stage = 2;	
$("#ListModal").empty();
$("#HeaderModal").text(l_code +' | '+ po_no);
	//$("#ListModal").append(td);	
	    $.ajax({		
        type:"POST",
        cache:false,
        //url:"webservices/getDetailPo.php", 
		url:"../appr/webservices/getDetailPo.php", 
        data : { code : '1', no_list : l_code,po:po_no,cari:carinya },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
			data = response
				d = JSON.parse(data);
				//d = response;
				//console.log(d.records);
				if(d.message == '1'){
						StoreList = DecodeURIComponents(d.records);
						console.log(StoreList);
						rendertablePo(d.records,d.total_price,d.total_qty);
						price_net = d.total_price;
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	  
}
function getListDataRekap(Item){
$("#HeaderModal").text(Item);
	//$("#ListModal").append(td);	
	    $.ajax({		
        type:"POST",
        cache:false,
        //url:"webservices/getDetailRekapApprovalPayment.php", 
		url:"../appr/webservices/getDetailRekapApprovalPayment.php", 
        data : { code : '1', no_list : Item   },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
			data = response
				d = JSON.parse(data);
				//d = response;
				//console.log(d.records);
				if(d.message == '1'){
						StoreList = DecodeURIComponents(d.records);
						console.log(StoreList);
						rendertableListDetail(d.records);
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	  
}

function getListData(){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getApprovalKontraBon.php", 
        data : { code : '1',   },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
			data = response
				d = JSON.parse(data);
				//d = response;
				//console.log(d.records);
				if(d.message == '1'){
						Store = DecodeURIComponents(d.records);
						console.log(Store);
						rendertableDetail(d.records);
						$("#loading").css("display","none");
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	  
}


function headerDetailPo(){
	
		var td = '';
td +="	  	<table id='myListRekap' class='display responsive' style='width:100%;font-size:12px;'> ";
td +="      <thead> 																			";
td +="        <tr>  																			";

td +="	    	<th>No Po</th>            															";
td +="	    	<th>Po Date</th>            												";
td +="	    	<th>Item</th>            												";
td +="	    	<th>Qty</th>            												";
//td +="	    	<th>Currency</th>            												";
td +="	    	<th>Unit Price</th>            												";
td +="	    	<th>Ppn(%)</th>            												";
td +="	    	<th>Net Price</th>            												";
td +="	    	<th>Net Price After Ppn</th>            												";
//td +="	    	<th>Net Price</th>            												";
//td +="	    	<th>Size</th>            												";
//td +="	    	<th>Color</th>            												";

//td +="	    	<th>Action</th>            														";
td +="		</tr>                                       										";
td +="      </thead>                                    										";
td +="      <tbody id='renderListDetail'>                    												";
td +="      </tbody>                                    										";
td +="    </table>	                                    										";

td += "<div id='tot_price'> <div id='tot_qty'> </div>"


$("#ListModal").empty();
	$("#ListModal").append(td);
	
	
}

function net_price(price_nya){
	var td = '';

				
	td += "<div style='float:right';margin-right:30%>";
		td += price_nya;
	td += "</div>";			




return td;
	
}

async function tableDetail(){
	var td = '';
td +="	  	<table id='myDetail' class='display responsive' style='width:100%;font-size:12px;'> ";
td +="      <thead> 																			";
td +="        <tr>  																			";
td +="	    	<th><center><input type='checkbox' id='header_checkbox' onclick='header_CheckList(this)' class='myCheckbox' ></center></th>            															";
td +="	    	<th>Tanggal List</th>            															";
td +="	    	<th>No. List</th>            												";
td +="	    	<th>Kode Supplier</th>            												";
td +="	    	<th>Nama Supplier</th>            											";
td +="	    	<th>Nilai List Payment</th>            													";
td +="	    	<th>Status</th>            													";
td +="	    	<th>Notes</th>            													";
//td +="	    	<th>Action</th>            														";
td +="		</tr>                                       										";
td +="      </thead>                                    										";
td +="      <tbody id='render'>                    												";
td +="      </tbody>                                    										";
td +="    </table>	                                    										";


td += "<button class='btn btn-primary' id='mySimpan' onclick='Save()'>Simpan </button>";


$("#detail_item").empty();
	$("#detail_item").append(td);

}


function HtmlStatus(id,code){
	var html = '';
		html +="<select id='status_"+id+"' onchange='changestatus(this)'  class='form-control'>";
		html +="<option value='S'>Status</option>"
		html +="<option value='A'>Approve</option>"
		html +="<option value='R'>Reject</option>"
		html +="<option value='C'>Cancel</option>"
		html +="</selct>";
	return html;
}

function changestatus(that){
	var split = that.id.split("_");
	Store[split[1]].code_status = that.value;
	$('#Checkbox_'+split[1]).prop('checked', true);
	Store[split[1]].checkbox = '1';
	console.log(Store[split[1]]);
}
function handlekeyup(that){
	var split = that.id.split("_");
	Store[split[1]].notes = that.value;
	console.log(Store[split[1]]);
}

function header_CheckList(that){
	if($("#header_checkbox").is(":checked")){
		for(var i=0;i<Store.length;i++){ //Checkbox_0
			//$('#status_'+i).attr('disabled', false);
			//$('#notes_'+i).attr('disabled', false);
			$('#status_'+i).val('A');
			$('#notes_'+i).val('APPROVE');
			$('#Checkbox_'+i).prop('checked', true);
			
			
			Store[i].checkbox = '1';
			Store[i].notes = 'APPROVE';
			Store[i].code_status = 'A';
		}
		
	}else{
		for(var i=0;i<Store.length;i++){ //Checkbox_0
			//$('#status_'+i).attr('disabled', true);
			//$('#notes_'+i).attr('disabled', true);
			$('#status_'+i).val('X');
			$('#notes_'+i).val('');
			$('#Checkbox_'+i).prop('checked', false);
			
			Store[i].checkbox = '0';
			Store[i].notes = '';
			Store[i].code_status = '';
		}		
		
		
	}
	
}

function checkList(that){
		console.log(that);
	var split = that.id.split("_");
	    if($('#'+that.id).is(':checked')){
			//$('#notes_'+split[1]).attr('disabled', false);
			//$('#status_'+split[1]).attr('disabled', false);
			Store[split[1]].checkbox = '1'
        }else{
			//$('#notes_'+split[1]).attr('disabled', true);
			//$('#status_'+split[1]).attr('disabled', true);
			Store[split[1]].checkbox = '0'
        }
}

function HtmlNotes(id){
	var html = '';
		html +="<input type='text' id='notes_"+id+"' onkeyup='handlekeyup(this)' class='form-control'>";
	return html;
}



async function rendertableListDetail(detail){
	console.log(detail[0].curr);
	stage = 1;
	$("#myListRekap").dataTable().fnDestroy();
	headerListRekap();
	var td = "";
	no = 0;
	for(var i=0;i<detail.length;i++){
		td +="<tr>";
	//		td += "<td>";
	//			td += decodeURIComponent(detail[i].no_kontra_bon);
	//		td += "</td>";

			td += "<td>";
				td += "<div onclick='getDetailPo("+'"KB"'+',"'+decodeURIComponent(detail[i].list_code)+'"'+',"'+decodeURIComponent(detail[i].no_kontra_bon)+'"'+")', style='cursor:pointer;color:blue'>";
				td += decodeURIComponent(detail[i].no_kontra_bon);
				td += "</div>"				
			td += "</td>";				
			
			
			


			
			td += "<td>";
				td += decodeURIComponent(detail[i].tgl_kontra_bon);
			td += "</td>";

 
				td += "<td>";
					td += "<div onclick='getDetailPo("+'"PO"'+',"'+decodeURIComponent(detail[i].list_code)+'"'+',"'+decodeURIComponent(detail[i].no_po)+'"'+")', style='cursor:pointer;color:blue'>";
					td += decodeURIComponent(detail[i].no_po);
					td += "</div>"				
				td += "</td>";			

				
						
			td += "<td>";
				td += decodeURIComponent(detail[i].tgl_po);
			td += "</td>";
			td += "<td>";
				td += decodeURIComponent(detail[i].kode_supplier);
			td += "</td>";

			td += "<td>";
				td += decodeURIComponent(detail[i].nama_supplier);
			td += "</td>";
	
			td += "<td>";
				td += decodeURIComponent(detail[0].curr)+" "+decodeURIComponent(detail[i].pph);
			td += "</td>";	
	
			td += "<td>";
				td += decodeURIComponent(detail[0].curr)+" "+decodeURIComponent(detail[i].nilai);
			td += "</td>";
			td += "<td>";
				td += decodeURIComponent(detail[0].curr)+" "+decodeURIComponent(detail[i].nilai_after_pph);
			td += "</td>";


			td += "<td>";
				td += decodeURIComponent(detail[i].umur_ap);
			td += "</td>";

			td += "<td>";
				td += decodeURIComponent(detail[i].jatuh_tempo);
			td += "</td>";

		td +="</tr>";
		no++;
	}//department
	$("#renderListDetail").append(td);
	pasangDataTable('myListRekap');

	
}

 function rendertablePo(detail,price_net,qty_net){
console.log(detail);
	$("#myListRekap").dataTable().fnDestroy();
//$("#ListModal").empty();
	headerDetailPo();
	var td = "";
	no = 0;
	for(var i=0;i<detail.length;i++){
		td +="<tr>";
	
			td += "<td>";
				td += detail[i].po;
			td += "</td>";			

			td += "<td>";
				td += detail[i].podate;
			td += "</td>";				
			td += "<td>";
				td += detail[i].item;
			td += "</td>";
	
			td += "<td>";
				td += detail[i].qty;
			td += "</td>";
		
			td += "<td>";
				td += detail[i].curr+" "+detail[i].price;
			td += "</td>";
			td += "<td>";
				td += detail[i].ppn;
			td += "</td>";							
			td += "<td align='right'>";
				td += detail[i].curr+" "+detail[i].netprice;
			td += "</td>";			
				td += "<td>";
				td += detail[i].netprice_after_ppn;
			td += "</td>";	

		td +="</tr>";
		no++;
	}//department

	
	$("#renderListDetail").append(td);
	pasangDataTable('myListRekap');//myListRekap_info
	$("#tot_price").append("Total Price :"+net_price(price_net));
	$("#tot_qty").append("Total Qty :"+net_price(qty_net));

	
}



 function rendertableDetail(detail){
	item = [];
	item = detail;

	$("#myDetail").dataTable().fnDestroy();
	$("#ListModal").empty();
	tableDetail();
	var td = "";
	no = 0;
	for(var i=0;i<detail.length;i++){
		td +="<tr>";
			td += "<td>";
				td += "<center><input type='checkbox' id='Checkbox_"+no+"' onclick='checkList(this)' class='myCheckbox' ></center>";
			td += "</td>";	
			
				td += "<td>";
				td += decodeURIComponent(detail[i].tanggal_kontrabon);
			td += "</td>";		
			
			td += "<td>";
				td += decodeURIComponent(detail[i].nolist);
				td += "<div style='float:right;margin-right:10px;font-weight:bold;color:#0000ff;max-height:1px;'><button  data-toggle='modal' data-target='#myModalLIST' onclick='getListDataRekap("+'"'+decodeURIComponent(detail[i].nolist)+'"'+")'>...</button></div>"
			td += "</td>";


			td += "<td>";
				td += decodeURIComponent(detail[i].supplier_code);
			td += "</td>";			
			
			td += "<td>";
				td += decodeURIComponent(detail[i].nama_supplier);
			td += "</td>";
			td += "<td>";
				td += decodeURIComponent(detail[i].nilai_kontrabon);
			td += "</td>";


			td += "<td>";
				//td += decodeURIComponent(detail[i].status);
				td += HtmlStatus(no,decodeURIComponent(detail[i].code_status));
			td += "</td>";
			td += "<td>";
				//td += decodeURIComponent(detail[i].notes);
				td += HtmlNotes(no,);
			td += "</td>";		
			
		td += "</td>";
		td +="</tr>";
		no++;
	}//department
	$("#render").append(td);
	pasangDataTable('myDetail');

	
}


function headerListRekap(){
var td = '';
td +="	  	<table id='myListRekap' class='display responsive' style='width:100%;font-size:12px;'> ";
td +="      <thead> 																			";
td +="        <tr>  																			";
td +="	    	<th>No. Kontra Bon</th>            															";
td +="	    	<th>Tanggal Kontra Bon</th>            												";
td +="	    	<th>No PO</th>            												";
td +="	    	<th>Tanggal PO</th>            											";
td +="	    	<th>Kode Supplier</th>            													";
td +="	    	<th>Nama Supplier</th>            													";
td +="	    	<th>Pph(%)</th>            													";
td +="	    	<th>Nilai</th>            													";
td +="	    	<th>Nilai After Pph</th>            													";
td +="	    	<th>Umur AP</th>            													";
td +="	    	<th>Tanggal Jatuh Tempo</th>            													";
td +="		</tr>                                       										";
td +="      </thead>                                    										";
td +="      <tbody id='renderListDetail'>                    												";
td +="      </tbody>                                    										";
td +="    </table>	                                    										";	
	

$("#ListModal").empty();
	$("#ListModal").append(td);	
	
}


function DecodeURIComponents(data){
        for(var i=0, length=data.length;i<length;i++) {
            for ( var temp in data[i] ) {
                data[i][temp] = decodeURIComponent(data[i][temp]);
            } 
        }
        return data;
    }


async function pasangDataTable(id){
	await $('#'+id).DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        },
    	dom: "<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +"<'row'<'col-sm-12'tr>>" 
	
    });	
	
	if(stage > 1){
		$("#myBack").css("display","block");
		
	}else{
		$("#myBack").css("display","none");
		
	}
	
		 setTimeout(function(){ 	
	console.log("Begin");
		$(".sorting").trigger("click");
	$(".loading").css("display","none");
	}, 2500);
}

