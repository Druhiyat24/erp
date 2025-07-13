$(document).ready(function () {
    Mydetail = {};
	y = {};
       Mydata = {
		crud :'INSERT',
        id:'',
        invno: '',
        invdate: '',
        id_buyer: '',
        consignee: '',
        shipper: '',
        notify_party: '',
        country_of_origin: '',
        manufacture_address: '',
        vessel_name: '',
        port_of_loading: '',
        port_of_discharge: '',
        port_of_entrance: '',
        lc_no: '',
        lc_issue_by: '',
        hs_code:'',
        etd: '',
        eta: '',
        eta_lax: '',
        id_pterms: '',
        shipped_by: '',
        route: '',
        ship_to: '',
        nw: '',
        gw: '',
        measurement: '',
		measurement2: '',
        container_no: '',
        seal_no: '',
		faktur_pajak: '',
        detail: [],
		typeinvoice :'1',
		post       :'',
		ws       :'',
    }

	$surat_jalan = {
		arraynya : [],
	}



	getTypeInvoiceFromUrl();
    localStorage.clear();
    sessionStorage.clear();
	//getListWS();

    //getListData();
	
	
	
	
});


function GenerateDetail_from_url(that,type_detail,id__inv,$par_for){
	
	if(!id__inv){
		$__inv__ = '0';
		
	}else{
		$__inv__ = id__inv;
	}
	if(!type_detail){
		$detail_type = '1';
		
	}else{
		$detail_type = '2';
	}
	var $tmp = {
		key : '',
		bppbno_int : '',
		bppbno : '',
		idcosting :'',
		idcustomer :'',
		destination :'',
		detail_type : $detail_type,
		id_inv : $__inv__,
	}
	$tmp.key       =$('#__'+that.id).data('idcosting');
	$tmp.bppbno_int=$('#__'+that.id).data('bppbno_int');
	$tmp.bppbno    =that.id;
	$tmp.idcosting =$('#__'+that.id).data('idcosting');
	$tmp.idcustomer =$('#__'+that.id).data('idcustomer');
	$tmp.destination =$('#__'+that.id).data('destination');
	console.log($tmp);
	
$surat_jalan.arraynya.push($tmp);
	console.log(that);
	//return false;
		//GenerateTableDetail($surat_jalan);
}


function GenerateDetail(that,type_detail,id__inv){
	console.log(that);
	if(!id__inv){
		$__inv__ = '0';
		
	}else{
		$__inv__ = id__inv;
	}
	if(!type_detail){
		$detail_type = '1';
		
	}else{
		$detail_type = '2';
	}
	var $tmp = {
		key : '',
		bppbno_int : '',
		bppbno : '',
		idcosting :'',
		idcustomer :'',
		destination :'',
		detail_type : $detail_type,
		id_inv : $__inv__,
	}
	$tmp.key       =$('#'+that.id).data('idcosting');
	$tmp.bppbno_int=$('#'+that.id).data('bppbno_int');
	//alert($tmp.bppbno_int);
	
	$tmp.bppbno    =that.id;
	$tmp.idcosting =$('#'+that.id).data('idcosting');
	$tmp.idcustomer =$('#'+that.id).data('idcustomer');
	$tmp.destination =$('#'+that.id).data('destination');
	console.log($tmp);
	if ($('#'+that.id).is(':checked')) {
		if($surat_jalan.arraynya.length < 1){
			$surat_jalan.arraynya.push($tmp);
		}else{
			if(validation_customer($surat_jalan,$tmp.idcustomer,$tmp.destination) == true){
				$surat_jalan.arraynya.push($tmp);
				
			}else{
				if(ids != '0'){
					$z='';
				}else{
				$("#"+that.id).prop('checked', false);
				alert("Destinaion Harus Sama!");
				//return false;					
				}

			}
		}		
	}else{

		//alert("123");
		
		//var $split =  getIndexArray($surat_jalan,that.id);
		$indexnya = getIndexArray($surat_jalan,$tmp.idcosting);
		$surat_jalan.arraynya.splice($indexnya,1);		
	}
	console.log($surat_jalan);
		GenerateTableDetail($surat_jalan);
}

function initselect(){
	
	//$(document).find('#head_costumer').select2();
	
}
function post(){
	alert(Mydata.id);
	    $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/postPackInv.php",
		//PL = past packing list
		//INV = part invoice
        data: { code: '1', part:'PL',id:Mydata.id},     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            var options = "";
            data = response;
            d = JSON.parse(data);
			if (d.status == 'ok'){
				if (d.message == '1') {
					alert("Data berhasil di post");
					window.location="/pages/shp/?mod=3v";
				}				
				
			}


        }
    });
}
sw = 0;
function showWS(){
	//alert("123");
//	if(sw > 0){

//	}
	$("#myModalLIST").modal('show');	
sw++;
}

function my_alert(){
	//alert("123");	
};
function fromtemplate(){
		$(".select2").select2(); //txtlc_issue_by
	$('#datepicker1,#datepicker2,#datepicker3,#datepicker4').datepicker
    ({  format: "dd M yyyy",
        autoclose: true
    });
	$('#txtlc_issue_by').datepicker
    ({  format: "dd M yyyy",
        autoclose: true
    });	
	
	
    getPort().then(function(d) {
		var options = "";
			options += "<option val='XX'>--Pilih--</option>" 
        if (d.message == '1') {
            for (var x = 0; x < d.records.length; x++) {
                options += "<option value='" + decodeURIComponent(d.records[x].id) + "'>" + decodeURIComponent(d.records[x].nama) + "</option>"
            }
            $("#txtport_of_loading,#txtport_of_discharges,#txtport_of_entrances").append(options);
         //   $("#txtport_of_discharges").append(options);
         //   $("#txtport_of_entrances").append(options);
        }
        if (d.message == '2') {
            alert("Ada Error!, Silahkan Reload Page");
        }		
	});
    getMasterHsCode().then(function(d) {
		var options = "";
			options += "<option val='XX'>--Pilih--</option>" 
            if (d.message == '1') {
         
                for (var x = 0; x < d.records.length; x++) {
                    options += "<option value='" + decodeURIComponent(d.records[x].id) + "'>" + decodeURIComponent(d.records[x].nama) + "</option>"
                }
                $("#txths_code").append(options);
            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }		
			
			
			
	});;
    getMasterRoute().then(function(d) {
		var options = "";
			options += "<option val='XX'>--Pilih--</option>" 
		if (d.message == '1') {
			for (var x = 0; x < d.records.length; x++) {
				options += "<option value='" + decodeURIComponent(d.records[x].id) + "'>" + decodeURIComponent(d.records[x].nama) + "</option>"
			}
			$("#txtroute").append(options);
		}
		if (d.message == '2') {
			alert("Ada Error!, Silahkan Reload Page");
		}		
	});
    getMasterShipMode().then(function(d) {
		var options = "";
			options += "<option val='XX'>--Pilih--</option>" 
            if (d.message == '1') {
                for (var x = 0; x < d.records.length; x++) {
                    options += "<option value='" + decodeURIComponent(d.records[x].id) + "'>" + decodeURIComponent(d.records[x].nama) + "</option>"
                }
                $("#txtshipped_by").append(options);

            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }		
	});
    getMasterPilihan().then(function(d) {
		var options = "";
			options += "<option val='XX'>--Pilih--</option>" 
            if (d.message == '1') {
                for (var x = 0; x < d.records.length; x++) {
                    options += "<option value='" + decodeURIComponent(d.records[x].id) + "'>" + decodeURIComponent(d.records[x].nama) + "</option>"
                }
                $("#txtmeasurement2").append(options);

            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }		
	});	
    getMasterPaymentTerms().then(function(d) {
		var options = "";
			options += "<option val='XX'>--Pilih--</option>" 
            if (d.message == '1') {

                for (var x = 0; x < d.records.length; x++) {
                    options += "<option value='" + decodeURIComponent(d.records[x].id) + "'>" + decodeURIComponent(d.records[x].nama) + "</option>"

                }

                $("#txtid_pterms").append(options);

            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }		
	});	
	
	
/*     getMasterMeasurement().then(function(d) {
		var options = "";
			options += "<option val='XX'>--Pilih--</option>" 
            if (d.message == '1') {

                for (var x = 0; x < d.records.length; x++) {
                    options += "<option value='" + decodeURIComponent(d.records[x].id) + "'>" + decodeURIComponent(d.records[x].nama) + "</option>"

                }

                $("#txtmeasurement").append(options);

            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }		
	});	 */
	

	getIdFromUrl().then(function(d) {
		ids = d;
    if(ids != '0'){
		InitData();
		//generateDetail();
		setInterval(function(){ def_var() }, 6000);	
	}else{
		getMasterSupplier().then(function(d){
			var options = "";
			options += "<option val='XX'>--Pilih--</option>" 
				if(d.message == '1'){
			
					for(var x=0;x<d.records.length;x++){
						 options += "<option value='"+decodeURIComponent(d.records[x].id)+"'>"+decodeURIComponent(d.records[x].nama)+"</option>"
						
					}
				
					$("#head_costumer").append(options);
						
				}
				if(d.message == '2'){
					alert("Ada Error!, Silahkan Reload Page");
				}			
			
			
		})
	}		
		
	});	
	
}

function getTypeInvoiceFromUrl() {
    console.log("Begin Start Gety URL");
    var url = window.location.href;
    var mySplit = url.split("&type_invoice=");
    ids_i = mySplit[1].substring(0,1);
    console.log(ids_i);
	if(ids_i == '1'){
		Mydata.typeinvoice = ids_i;
		$("#form_").load("PackingListPage/TemplateLocal.php", function() {
			fromtemplate();
   // $(document).find('head_costumer').select2();
});
		
	}	
	else if(ids_i == '2'){
		Mydata.typeinvoice = ids_i;
		$("#form_").load("PackingListPage/TemplateCommercial.php", function() {
			fromtemplate();
   // $(document).find('head_costumer').select2();
});
	}
	
	

	
} 	
	



function def_var(){
	console.log("Begin Def Var");
	if(ids != "0"){
		console.log($defaultData);
		txtinvno              =$("#txtinvno").val();
		datepicker1           =$("#datepicker1").val();
		head_costumer         =$("#head_costumer").val();
		txtconsignee          =$("#txtconsignee").val();
		txtshipper            =$("#txtshipper").val();
		txtnotify_party       =$("#txtnotify_party").val();
		txtcountry_of_origin  =$("#txtcountry_of_origin").val();
		txtmanufacture_address=$("#txtmanufacture_address").val();
		txtvessel_name        =$("#txtvessel_name").val();
		txtport_of_loading    =$("#txtport_of_loading").val();
		txtport_of_discharges =$("#txtport_of_discharges").val();
		txtport_of_entrances  =$("#txtport_of_entrances") .val();
		txtcontainer_no       =$("#txtcontainer_no").val();
		txtlc_no              =$("#txtlc_no").val();
		txtlc_issue_by        =$("#txtlc_issue_by").val();
		txths_code            =$("#txths_code").val();
		datepicker2           =$("#datepicker2").val();
		datepicker3           =$("#datepicker3").val();
		datepicker4           =$("#datepicker4").val();
		txtseal_no            =$("#txtseal_no").val();
		txtid_pterms          =$("#txtid_pterms").val();
		txtshipped_by         =$("#txtshipped_by").val();
		txtroute              =$("#txtroute").val();
		txtship_to            =$("#txtship_to").val();
		txtnw                 =$("#txtnw").val();
		txtgw                 =$("#txtgw").val();
		txtmeasurement        =$("#txtmeasurement").val();
		txtmeasurement2        =$("#txtmeasurement2").val();
		faktur_pajak          =$("#faktur_pajak").val();
		
		
		
		
					
		if(txtinvno              	==''){$("#txtinvno").val($defaultData.invno);}
		if(datepicker1              ==''){$("#datepicker1").datepicker().datepicker("setDate", $defaultData.invdate);} 
		if(head_costumer            ==''){$("#head_costumer").val($defaultData.id_buyer).trigger("change"); }
		if(txtconsignee             ==''){$("#txtconsignee").val($defaultData.consignee);}
		if(txtshipper               ==''){$("#txtshipper").val($defaultData.shipper);}
		if(txtnotify_party          ==''){$("#txtnotify_party").val($defaultData.notify_party);}
		if(txtcountry_of_origin     ==''){$("#txtcountry_of_origin").val($defaultData.country_of_origin);}
		if(txtmanufacture_address   ==''){$("#txtmanufacture_address").val($defaultData.manufacture_address);}
		if(txtvessel_name           ==''){$("#txtvessel_name").val($defaultData.vessel_name);}
		if(txtport_of_loading       ==''){$("#txtport_of_loading").val($defaultData.port_of_loading).trigger("change");}
		if(txtport_of_discharges    ==''){$("#txtport_of_discharges").val($defaultData.port_of_discharge).trigger("change");}
		if(txtport_of_entrances     ==''){$("#txtport_of_entrances").val($defaultData.port_of_entrance).trigger("change");}
		if(txtcontainer_no          ==''){$("#txtcontainer_no").val($defaultData.container_no);}
		if(txtlc_no                 ==''){$("#txtlc_no").val($defaultData.lc_no);}
		if(txtlc_issue_by           ==''){$("#txtlc_issue_by").datepicker().datepicker("setDate", $defaultData.lc_issue_by);} 
		if(txths_code               ==''){$("#txths_code").val($defaultData.hs_code);}
		if(datepicker2              ==''){$("#datepicker2").datepicker().datepicker("setDate", $defaultData.etd);}
		if(datepicker3              ==''){$("#datepicker3").datepicker().datepicker("setDate", $defaultData.eta);}
		if(datepicker4              ==''){$("#datepicker4").datepicker().datepicker("setDate", $defaultData.eta_lax);}
		if(txtseal_no               ==''){$("#txtseal_no").val($defaultData.seal_no);}
		if(txtid_pterms             ==''){$("#txtid_pterms").val($defaultData.id_pterms).trigger("change");}
		if(txtshipped_by            ==''){$("#txtshipped_by").val($defaultData.shipped_by).trigger("change");}
		if(txtroute                 ==''){$("#txtroute").val($defaultData.route).trigger("change");}
		if(txtship_to               ==''){$("#txtship_to").val($defaultData.ship_to);}
		if(txtnw                    ==''){$("#txtnw").val($defaultData.nw);}
		if(txtgw                    ==''){$("#txtgw").val($defaultData.gw);}
	//	if(txtmeasurement           ==''){$("#txtmeasurement").val($defaultData.measurement).trigger("change"); }
		if(txtmeasurement           ==''){$("#txtmeasurement").val($defaultData.measurement); }
		if(txtmeasurement2           ==''){$("#txtmeasurement2").val($defaultData.measurement2).trigger("change"); }
		if(faktur_pajak             ==''){ $("#faktur_pajak").val($defaultData.faktur_pajak);		}
console.log(faktur_pajak);
}


}
		
patherror = "../../images/error.jpg";


function handle_templete(){
	
	
	
}
function handleKeyDetail(Item) {
    console.log(Mydetail);
    var tmpid = Item.id;
    explodetmpid = tmpid.split("_");
    console.log(explodetmpid[1]);
    if (explodetmpid[0] == "MyDetailLot") {
        console.log("Begin Lot Handle");
		Mydetail[explodetmpid[1]].lot = Item.value;

    }  
    else if (explodetmpid[0] == "MyDetailCarton") {
        console.log("Begin Carton Handle");
        Mydetail[explodetmpid[1]].carton = Item.value;
    }
    else if (explodetmpid[0] == "MyDetailQty") {
        console.log("Begin Qty Handle");
        Mydetail[explodetmpid[1]].i_qty = Item.value;
    }	
	console.log(Mydetail);
}
function generateLot(x,valuenya) {
    var form = "";
  //  var hanyabaca = "";
  //  if (x > 0) {
  //      hanyabaca = "disabled";
  //  }

    form = "<input type='text' id='MyDetailLot_" + x + "' value='"+ valuenya +"' class='form-control'  placeholder='Masukkan Lot' onkeyup='handleKeyDetail(this)'>";
    return form;
}

function generateQty(x,valuenya) {
    var form = "";
    form = "<input type='text' id='MyDetailQty_" + x + "' value='"+ valuenya +"'  placeholder='Masukkan Qty Invoice' class='form-control' onkeyup='handleKeyDetail(this)'>";
    return form;
}

function generateCarton(x,valuenya) {
    var form = "";
    form = "<input type='text' id='MyDetailCarton_" + x + "' value='"+ valuenya +"'  placeholder='Masukkan Carton' class='form-control' onkeyup='handleKeyDetail(this)'>";
    return form;
}
async function getDetailByWs(Item){
	$("#MasterCurrencyTable").dataTable().fnDestroy()
   await $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getDetailByWs.php",
        data: { code: '1',id:Item},     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            var options = "";
			$("#renders").empty();
			renders = '';
			td = '';
            data = response;
            d = JSON.parse(data);
            if (d.message == '1') {
                Mydetail = d.records;
                console.log(Mydetail);

				for (var x = 0; x < d.records.length; x++) {
					td +="<tr>";
					
						td +="<td>";
							td += decodeURIComponent(d.records[x].so_no);
						td +="</td>";
						
						td +="<td>";
							td += decodeURIComponent(d.records[x].dest)
						td +="</td>";
	
						td +="<td>";
							td += decodeURIComponent(d.records[x].size);
						td +="</td>";
						

						td +="<td>";
							td += decodeURIComponent(d.records[x].qty)
						td +="</td>";
	
						td +="<td>";
							td += decodeURIComponent(d.records[x].unit);
						td +="</td>";

						td +="<td>";
							td += decodeURIComponent(d.records[x].price);
						td +="</td>";
                    td += "<td>";
                    td += generateCarton(x);
                    td += "</td>";

                    td += "<td>";
                    td += generateLot(x);
                    td += "</td>";	



				/*		td +="<td>";
							td += "<a href='#' class='btn btn-primary' onclick='editDetail("+'"'+d.records[x].id+'"'+")'><i class='fa fa-pencil'> </i> <a href='#' class='btn btn-primary' onclick='deleteDetail(\'d.records[x].id\")'><i class='fa fa-trash'> </i> ";
						td +="</td>";
				*/
						
					td +="</tr>";   
				}					
            }
			$("#renders").append(td);
			var table = $('#MasterCurrencyTable').DataTable
				({
					scrollY: "300px",
					scrollCollapse: true,
					order: [[0, "desc"]],
					paging: true,
					pageLength: 20,
					fixedColumns:
					{
						leftColumns: 1,
						rightColumns: 1
					}
				
				});				
			
			
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }
        }
    });		
}


function saveDetail(){



	event.preventDefault();
		
    $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/SaveDetail.php",
        data: { code: '1', data:Mydetail},     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            var options = "";
            data = response;
            d = JSON.parse(data);
            if (d.message == '1') {
                alert("Data Berhasil diupdate");
				if(Mydata.type_invoice == '1'){
					 window.location.href="../?mod=DeliveryOrderPage";
				}
				else{
					 window.location.href="../?mod=PackingListPage"
				}
            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }
        }
    });	
}
async function getListDetailInvoice(idss){
	    await $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getListDetailInvoice.php",
        data: { code: '1',id:idss },     // multiple data sent using ajax
        success: function (response) {
            var options = "";
            data = response;
            d = JSON.parse(data);
            td = '';
            renders = '';
            if (d.message == '1') {
					$("#detail_no_so").val(decodeURIComponent(d.records[0].v_noso)).trigger('change');
					$("#detail_qty").val(decodeURIComponent(d.records[0].qty));
					$("#detail_unit").val(decodeURIComponent(d.records[0].unit));
					$("#detail_price").val(decodeURIComponent(d.records[0].price));
					
            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }
        }
    });	
	
}
async function getNoSo(){
	    await $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getNoSo.php",
        data: { code: '1' },     // multiple data sent using ajax
        success: function (response) {
            var options = "";
            data = response;
            d = JSON.parse(data);
            td = '';
            renders = '';
            if (d.message == '1') {
                for (var x = 0; x < d.records.length; x++) {
                    options += "<option value='" + decodeURIComponent(d.records[x].nama) + "'>" + decodeURIComponent(d.records[x].nama) + "</option>"
                }
                $("#detail_no_so").append(options);
            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }
        }
    });
}

function editDetail(id){
	Mydata.MydetailId = id;
	$("#table_no_so").empty();
	$("#table_qty").empty();
	$("#table_unit").empty();
	$("#table_price").empty();	
	$("#table_action").empty();	
	event.preventDefault();
	generateForm("Edit");
	getNoSo();
	getListDetailInvoice(id);
}

function AddDetail(){
	$("#table_no_so").empty();
	$("#table_qty").empty();
	$("#table_unit").empty();
	$("#table_price").empty();
	$("#table_action").empty();	
	event.preventDefault();
	generateForm("Add");
	getNoSo();
}
function generateForm(crud){
	if(crud == "Add" ){
			form_action = "<a href='#' class='btn btn-primary' autocomplete='off' onclick='saveDetail(\"Add\",\"9999\")' id='myAction'> Submit</a>";
			$('#table_action').append(form_action);				
	}
	else if(crud == "Edit"){
			form_action = "<a href='#' class='btn btn-primary' autocomplete='off' onclick='saveDetail(\"Edit\")' id='myAction'> Submit</a>";
			$('#table_action').append(form_action);				
	}
	
	
	form_detail_no_so = "<select class='form-control select2' id='detail_no_so'> </select>";
	$("#table_no_so").append(form_detail_no_so);
	form_detail_qty = "<input type='text' placeholder ='qty' class='form-control' id='detail_qty' />";
	$("#table_qty").append(form_detail_qty);
	form_detail_unit = "<input type='text' placeholder ='unit' class='form-control' id='detail_unit' />";
	$("#table_unit").append(form_detail_unit);
	form_detail_price = "<input type='text' placeholder ='price' class='form-control' id='detail_price' />";
	$("#table_price").append(form_detail_price);
	
	
	
}

function CallDetailServices($stringify__){
	if(ids){
		par_url = ids;
	}else{
		par_url = "00";
	}
		return new Promise(function(resolve, reject) {
  $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getListInvoiceDetail.php?bppbno_int="+$stringify__+"&id="+par_url,
        data : { bpbno_internal__ :'--' },     // multiple data sent using ajax
        success: function (response) {
			data = response;
				d = JSON.parse(data);
		
					resolve(d);

        }
      });		 	
	 });
	
	
}
/*
 function generateDetail(){
	     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getListInvoiceDetail.php",
        data: { code: '1', id:ids},     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            var options = "";

            data = response;
            d = JSON.parse(data);
			Mydetail = d.records;
            //d = response;
            td = '';
            renders = '';

            if (d.message == '1') {

                for (var x = 0; x < d.records.length; x++) {
					td +="<tr>";
					
						td +="<td>";
							td += decodeURIComponent(d.records[x].v_noso);
						td +="</td>";
					
						td +="<td>";
							td += decodeURIComponent(d.records[x].style);
						td +="</td>";
						

						td +="<td>";
							td += decodeURIComponent(d.records[x].dest);
						td +="</td>";	

						td +="<td>";
							td += decodeURIComponent(d.records[x].color);
						td +="</td>";	
												
						td +="<td>";
							td += decodeURIComponent(d.records[x].size);
						td +="</td>";	
						
						td +="<td>";
							td += decodeURIComponent(d.records[x].qty)
						td +="</td>";
						
						td +="<td>";
							td += decodeURIComponent(d.records[x].qtybpb)
						td +="</td>";
			 
						td +="<td>";
							td += generateQty(x,decodeURIComponent(d.records[x].i_qty));
						td +="</td>";

			
						td +="<td>";
							td += decodeURIComponent(d.records[x].unit);
						td +="</td>";

						td +="<td>";
							td += decodeURIComponent(d.records[x].price);
						td +="</td>";			
							td +="<td>";
							//td += decodeURIComponent(d.records[x].carton);
							td += generateCarton(x,decodeURIComponent(d.records[x].carton));
						td +="</td>";	
						
						td +="<td>";
							//td += decodeURIComponent(d.records[x].lot);
							td +=generateLot(x,decodeURIComponent(d.records[x].lot))
						td +="</td>";							

					//	td +="<td>";
					//		td += "<a href='#' class='btn btn-primary' onclick='editDetail("+'"'+d.records[x].id+'"'+")'><i class='fa //fa-pencil'> </i> <a href='#' class='btn btn-primary' onclick='deleteDetail(\'d.records[x].id\")'><i class='fa //fa-trash'> </i> ";
					//	td +="</td>";

						
					td +="</tr>";

                }
				
					$("#renders").append(td);
					var table = $('#MasterCurrencyTable').DataTable
						({
							scrollY: "300px",
							scrollCollapse: true,
							order: [[0, "desc"]],
							paging: true,
							pageLength: 20,
							fixedColumns:
							{
								leftColumns: 1,
								rightColumns: 1
							}
					
						});					
                $("#txtmeasurement").append(options);
            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }
        }
    });
}
*/
$val_save = 0;
$is_bussy = 0;
function Save() {
 	//alert($val_save);
	if($val_save > 0){
		if(($is_bussy > 0) && ($val_save > 0)){
			return false;
		}
		$is_bussy++;
		$val_save =0;
		return false
	} 
	$val_save++;
	if(Mydata.typeinvoice == "1"){
	console.log(Mydata);		
	
		if(Mydata.invdate            ==''){alert('Tanggal PackingList/Invoice Harus diisi');$val_save =0;return false}
		if(Mydata.head_costumer          ==''){alert('Customer Harus Diisi');$val_save =0;return false}
		if(Mydata.consignee           ==''){alert('Buyer/Penerima Harus Diisi');$val_save =0;return false}
		if(Mydata.shipper             ==''){alert('Seller/Sender Pengirim Harus Diisi');$val_save =0;return false}
		if(Mydata.manufacture_address ==''){alert('Buyer/Receiver Adress Harus Diisi');$val_save =0;return false}
		if(Mydata.vessel_name         ==''){alert('Vessel Name Harus Diisi');$val_save =0;return false}
		if(Mydata.lc_no               ==''){alert('Contract No# Harus Diisi');$val_save =0;return false}
		if(Mydata.lc_issue_by         ==''){alert('Contract Date Harus Diisi');$val_save =0;return false}
		if(Mydata.hs_code             ==''){alert('HS Code Harus Diisi');$val_save =0;return false}
		if(Mydata.etd                 ==''){alert('Delivery Time Harus Diisi');$val_save =0;return false}
		if(Mydata.id_pterms           ==''){alert('Payment Terms Harus Diisi');$val_save =0;return false}
		if(Mydata.shipped_by          ==''){alert('Delivary By Harus Diisi');$val_save =0;return false}
		if(Mydata.nw                  ==''){alert('Net Weight Harus Diisi');$val_save =0;return false}
		if(Mydata.gw                  ==''){alert('Gross Weight Harus Diisi');$val_save =0;return false}
		if(Mydata.measurement         ==''){alert('Measurement Harus Diisi');$val_save =0;return false}
		if(Mydata.measurement2         ==''){alert('Measurement Harus Diisi');$val_save =0;return false}
	}else{
		if(Mydata.invdate            ==''){alert('Tanggal PackingList/Invoice Harus diisi');$val_save =0;return false}
		if(Mydata.head_costumer          ==''){alert('Customer Harus Diisi');$val_save =0;return false}
		if(Mydata.consignee           ==''){alert('Buyer/Penerima Harus Diisi');$val_save =0;return false}
		if(Mydata.shipper             ==''){alert('Seller/Sender Pengirim Harus Diisi');$val_save =0;return false}
		if(Mydata.manufacture_address ==''){alert('Buyer/Receiver Adress Harus Diisi');$val_save =0;return false}
		if(Mydata.vessel_name         ==''){alert('Vessel Name Harus Diisi');$val_save =0;return false}
		if(Mydata.lc_issue_by         ==''){alert('Lc No Harus Diisi');$val_save =0;return false}
		if(Mydata.hs_code             ==''){alert('HS Code Harus Diisi');$val_save =0;return false}
		if(Mydata.etd                 ==''){alert('Delivery Time Harus Diisi');$val_save =0;return false}
		if(Mydata.id_pterms           ==''){alert('Payment Terms Harus Diisi');$val_save =0;return false}
		if(Mydata.shipped_by          ==''){alert('Shipped By Harus Diisi');$val_save =0;return false}
		if(Mydata.nw                  ==''){alert('Net Weight Harus Diisi');$val_save =0;return false}
		if(Mydata.gw                  ==''){alert('Gross Weight Harus Diisi');$val_save =0;return false}
		if(Mydata.port_of_loading         ==''){alert('POL Harus Diisi');;$val_save =0;return false}		
		if(Mydata.port_of_discharge         ==''){alert('POD Harus Diisi');$val_save =0;return false}	
		if(Mydata.container_no         ==''){alert('Container Harus Diisi');$val_save =0;return false}	
		if(Mydata.lc_no         ==''){alert('LC Harus Diisi');return false}	
		/*&
  port_of_loading: '',
        port_of_discharge: '',
        port_of_entrance: '',
container_no		
lc_no
		*/
	}
	//validasi detail
	$cont = $myjson_detail.data.length;
	for(var w=0;w<$myjson_detail.data.length;w++){
		if($myjson_detail.data[w].qty_invoice_val == ''){
			alert('Qty Invoice Baris ke '+w+ ' Harus diisi');
			return false;
		}
		if($myjson_detail.data[w].carton_val == ''){
			alert('Qty Carton ke '+w+ ' Harus diisi');
			return false;
		}		
		if($myjson_detail.data[w].lot_val == ''){
			alert('Lot Baris ke '+w+ ' Harus diisi');
			return false;
		}		
		if($myjson_detail.data[w].price_val == ''){
			alert('Price Baris ke '+w+ ' Harus diisi');
			return false;
		}			
	}
    Mydata.detail = $myjson_detail;
    event.preventDefault();
		JSON__ =JSON.stringify(Mydata);
    $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/UpdateInvoiceHeader.php",
        data: { code: '1', data: JSON__},     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            var options = "";
            data = response;
            d = JSON.parse(data);
            if (d.message == '1') {
                alert("Data Berhasil diupdate");
                //location.reload();
				if(Mydata.typeinvoice == '1'){
					 window.location.href="?mod=DeliveryOrderPage";
				}
				else{
					 window.location.href="?mod=PackingListPage"
				}				
            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }
        }
    });
}


function handleKeyUp(thisX) {
	
    if (thisX.id == "datepicker1") {
        Mydata.invdate = thisX.value;
		//console.log("Begin InvDate");
    }
    else if (thisX.id == "head_costumer") {
        Mydata.id_buyer = thisX.value;
    }
    else if (thisX.id == "txtconsignee") {
        Mydata.consignee = thisX.value;
    }
    else if (thisX.id == "txtshipper") {
        Mydata.shipper = thisX.value;
    }
    else if (thisX.id == "txtnotify_party") {
        Mydata.notify_party = thisX.value;
    }
    else if (thisX.id == "txtcountry_of_origin") {
        Mydata.country_of_origin = thisX.value;
    }
    else if (thisX.id == "txtmanufacture_address") {
        Mydata.manufacture_address = thisX.value;
    }
    else if (thisX.id == "txtvessel_name") {
        Mydata.vessel_name = thisX.value;
    }
    else if (thisX.id == "txtport_of_loading") {
        Mydata.port_of_loading = thisX.value;
    }
    else if (thisX.id == "txtport_of_discharges") {
        Mydata.port_of_discharge = thisX.value;
    }
    else if (thisX.id == "txtport_of_entrances") {
        Mydata.port_of_entrance = thisX.value;
    }
    else if (thisX.id == "txtcontainer_no") {
        Mydata.container_no = thisX.value;
    }
    else if (thisX.id == "txtlc_no") {
        Mydata.lc_no = thisX.value;
    }
    else if (thisX.id == "txtlc_issue_by") {
        Mydata.lc_issue_by = thisX.value;
    }
    else if (thisX.id == "txths_code") {
        Mydata.hs_code = thisX.value;
    }
    else if (thisX.id == "datepicker2") {
        Mydata.etd = thisX.value;
    }
    else if (thisX.id == "datepicker3") {
        Mydata.eta = thisX.value;
    }
    else if (thisX.id == "datepicker4") {
        Mydata.eta_lax = thisX.value;
    }
    else if (thisX.id == "txtseal_no") {
        Mydata.seal_no = thisX.value;
    }
    else if (thisX.id == "txtid_pterms") {
        Mydata.id_pterms = thisX.value;
    }
    else if (thisX.id == "txtshipped_by") {
        Mydata.shipped_by = thisX.value;
    }
    else if (thisX.id == "txtroute") {
        Mydata.route = thisX.value;
    }
    else if (thisX.id == "txtship_to") {
        Mydata.ship_to = thisX.value;
    }
    else if (thisX.id == "txtnw") {
        Mydata.nw = thisX.value;
    }
    else if (thisX.id == "txtgw") {
        Mydata.gw = thisX.value;
    }
    else if (thisX.id == "txtmeasurement") {
        Mydata.measurement = thisX.value;
    }
    else if (thisX.id == "txtmeasurement2") {
        Mydata.measurement2 = thisX.value;
    }	
    else if (thisX.id == "faktur_pajak") {
        Mydata.faktur_pajak = thisX.value;
    }	
	//console.log(thisX.id);
}



function InitData() {
	getListData(ids).then(function(d) {
		var opt_ = '';
		opt_  += "<option value='"+decodeURIComponent(d.records[0].id_buyer)+"'>"+decodeURIComponent(d.records[0].supplier)+"</option>";
		$("#head_costumer").append(opt_);

		
		y = d;
		Mydata.crud  			  ='UPDATE';
		Mydata.id                 = decodeURIComponent(d.records[0].id);
		Mydata.invno              = decodeURIComponent(d.records[0].invno);
		Mydata.invdate            = decodeURIComponent(d.records[0].invdate);
		Mydata.id_buyer           = decodeURIComponent(d.records[0].id_buyer);
		Mydata.consignee          = decodeURIComponent(d.records[0].consignee);
		Mydata.shipper            = decodeURIComponent(d.records[0].shipper);
		Mydata.notify_party       = decodeURIComponent(d.records[0].notify_party);
		Mydata.country_of_origin  = decodeURIComponent(d.records[0].country_of_origin);
		Mydata.manufacture_address= decodeURIComponent(d.records[0].manufacture_address);
		Mydata.port_of_loading    = decodeURIComponent(d.records[0].port_of_loading);
		Mydata.port_of_discharge  = decodeURIComponent(d.records[0].port_of_discharge);
		Mydata.port_of_entrance   = decodeURIComponent(d.records[0].port_of_entrance);
		Mydata.lc_no              = decodeURIComponent(d.records[0].lc_no);
		Mydata.lc_issue_by        = decodeURIComponent(d.records[0].lc_issue_by);
		Mydata.hs_code            = decodeURIComponent(d.records[0].hs_code);
		Mydata.etd                = decodeURIComponent(d.records[0].etd);
		Mydata.eta                = decodeURIComponent(d.records[0].eta);
		Mydata.eta_lax            = decodeURIComponent(d.records[0].eta_lax);
		Mydata.id_pterms          = decodeURIComponent(d.records[0].id_pterms);
		Mydata.shipped_by         = decodeURIComponent(d.records[0].shipped_by);
		Mydata.route              = decodeURIComponent(d.records[0].route);
		Mydata.ship_to            = decodeURIComponent(d.records[0].ship_to);
		Mydata.nw                 = decodeURIComponent(d.records[0].nw);
		Mydata.gw                 = decodeURIComponent(d.records[0].gw);
		Mydata.measurement        = decodeURIComponent(d.records[0].measurement);
		Mydata.measurement2        = decodeURIComponent(d.records[0].measurement2);
		Mydata.container_no       = decodeURIComponent(d.records[0].container_no);
		Mydata.seal_no            = decodeURIComponent(d.records[0].seal_no);
		Mydata.vessel_name        = decodeURIComponent(d.records[0].vessel_name);
		Mydata.faktur_pajak       = decodeURIComponent(d.records[0].faktur_pajak);
		Mydata.typeinvoice        = decodeURIComponent(d.records[0].typeinvoice);
		Mydata.ws       		  = decodeURIComponent(d.records[0].ws);
		Mydata.post       		  = decodeURIComponent(d.records[0].post);
		$defaultData =Mydata; 	
		
		
		$("#txtinvno").val(Mydata.invno);
		$("#datepicker1").datepicker().datepicker("setDate", Mydata.invno);
		$("#head_costumer").val(Mydata.id_buyer).trigger("change"); //get list ws
		$("#txtconsignee").val(Mydata.consignee);
		$("#txtshipper").val(Mydata.shipper);
		$("#txtnotify_party").val(Mydata.notify_party);
		$("#ws_nya").val(Mydata.ws);
		$("#txtcountry_of_origin").val(Mydata.country_of_origin);
		$("#txtmanufacture_address").val(Mydata.manufacture_address);
		$("#txtvessel_name").val(Mydata.vessel_name);
		$("#txtport_of_loading").val(Mydata.port_of_loading).trigger("change");
		$("#txtport_of_discharges").val(Mydata.port_of_discharge).trigger("change");
		$("#txtport_of_entrances").val(Mydata.port_of_entrance).trigger("change");
		$("#txtcontainer_no").val(Mydata.container_no);
		$("#txtlc_no").val(Mydata.lc_no);
		$("#txtlc_issue_by").val(Mydata.lc_issue_by);
		$("#txths_code").val(Mydata.hs_code).trigger('change');
		$("#datepicker2").datepicker().datepicker("setDate", Mydata.etd);
		$("#datepicker3").datepicker().datepicker("setDate", Mydata.eta); //txteta 
		$("#datepicker4").datepicker().datepicker("setDate", Mydata.eta_lax); //txteta //txteta_lax
		$("#txtseal_no").val(Mydata.seal_no);
		$("#txtid_pterms").val(Mydata.id_pterms).trigger("change");
		$("#txtshipped_by").val(Mydata.shipped_by).trigger("change");
		$("#txtroute").val(Mydata.route).trigger("change");
		$("#txtship_to").val(Mydata.ship_to);
		$("#txtnw").val(Mydata.nw);
		$("#txtgw").val(Mydata.gw);
		//$("#txtmeasurement").val(Mydata.measurement).trigger("change"); 
		
		$("#txtmeasurement").val(Mydata.measurement); 
		$("#txtmeasurement2").val(Mydata.measurement2).trigger("change"); 
			$("#faktur_pajak").val(Mydata.faktur_pajak);
			$("#type_invoice").val(Mydata.typeinvoice);
			if(Mydata.post == '1'){
				$(".myPost").css("display","none");
			}
			else{
				$(".myPost").css("display","");
			}
	});
}
ids_nyaa = 0;
function getIdFromUrl() {
	
		//alert($ids);
	return new Promise(function(resolve, reject) {
    console.log("Begin Start Gety URL");
    var url = window.location.href;
    var mySplit = url.split("&noid=");
	console.log(mySplit[1]);
	if(!mySplit[1]){
		ids_nyaa = 0
	}else{
		idsSplit = mySplit[1].split("&");
		if(idsSplit[0]){
			ids_nyaa = idsSplit[0];
				//console.log(ids);
		}else{
			ids_nyaa = 0;
		}		
	}		
	resolve(ids_nyaa);
	});

} 
 
function getListData($ids) {
	//alert($ids);
	return new Promise(function(resolve, reject) {
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getListData.php",
        data: { code: '1', id: $ids },     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            var options = "";
            console.log(response);
            datass = response;
            d = JSON.parse(datass);
            //d = response;
            td = '';
            renders = '';
            console.log(d.records.length);
			if(d.status == 'ok'){
            if (d.message == '1') {
				resolve(d);
				
				
				
/* 				Mydata.id                 = decodeURIComponent(d.records[0].id);
				Mydata.invno              = decodeURIComponent(d.records[0].invno);
				Mydata.invdate            = decodeURIComponent(d.records[0].invdate);
				Mydata.id_buyer           = decodeURIComponent(d.records[0].id_buyer);
				Mydata.consignee          = decodeURIComponent(d.records[0].consignee);
				Mydata.shipper            = decodeURIComponent(d.records[0].shipper);
				Mydata.notify_party       = decodeURIComponent(d.records[0].notify_party);
				Mydata.country_of_origin  = decodeURIComponent(d.records[0].country_of_origin);
				Mydata.manufacture_address= decodeURIComponent(d.records[0].manufacture_address);
				Mydata.port_of_loading    = decodeURIComponent(d.records[0].port_of_loading);
				Mydata.port_of_discharge  = decodeURIComponent(d.records[0].port_of_discharge);
				Mydata.port_of_entrance   = decodeURIComponent(d.records[0].port_of_entrance);
				Mydata.lc_no              = decodeURIComponent(d.records[0].lc_no);
				Mydata.lc_issue_by        = decodeURIComponent(d.records[0].lc_issue_by);
				Mydata.hs_code            = decodeURIComponent(d.records[0].hs_code);
				Mydata.etd                = decodeURIComponent(d.records[0].etd);
				Mydata.eta                = decodeURIComponent(d.records[0].eta);
				Mydata.eta_lax            = decodeURIComponent(d.records[0].eta_lax);
				Mydata.id_pterms          = decodeURIComponent(d.records[0].id_pterms);
				Mydata.shipped_by         = decodeURIComponent(d.records[0].shipped_by);
				Mydata.route              = decodeURIComponent(d.records[0].route);
				Mydata.ship_to            = decodeURIComponent(d.records[0].ship_to);
				Mydata.nw                 = decodeURIComponent(d.records[0].nw);
				Mydata.gw                 = decodeURIComponent(d.records[0].gw);
				Mydata.measurement        = decodeURIComponent(d.records[0].measurement);
				Mydata.container_no       = decodeURIComponent(d.records[0].container_no);
				Mydata.seal_no            = decodeURIComponent(d.records[0].seal_no);
				Mydata.vessel_name        = decodeURIComponent(d.records[0].vessel_name);
				Mydata.faktur_pajak       = decodeURIComponent(d.records[0].faktur_pajak);
				Mydata.typeinvoice       = decodeURIComponent(d.records[0].typeinvoice);
				Mydata.post       			= decodeURIComponent(d.records[0].post);
				$defaultData =Mydata; */
				//InitData();
            }
            if (d.message == '2') {
                alert("Data Belum ada");
            }				
			}else{
				alert(d.message);
				
			}
        }
    });
	});
}

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
            data = response;
            d = JSON.parse(data);
			resolve(d);
        }
    });		
		
	});
}

function getMasterRoute() {
		return new Promise(function(resolve, reject) {
			$.ajax({
				type: "POST",
				cache: false,
				url: "webservices/getMasterRoute.php",
				data: { code: '1' },     // multiple data sent using ajax
				success: function (response) {
					data = response;
					d = JSON.parse(data);
					resolve(d);

				}
			});			
			
			
		});
	

}
function getMasterShipMode() {
	return new Promise(function(resolve, reject) {
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getMasterShipMode.php",
        data: { code: '1' },     // multiple data sent using ajax
        success: function (response) {
            var options = "";
            data = response;
            d = JSON.parse(data);
			resolve(d);
        }
    });		
		
	});
}

function getMasterPilihan() {
	return new Promise(function(resolve, reject) {
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getMasterPilihan.php",
        data: { code: '1' },     // multiple data sent using ajax
        success: function (response) {
            var options = "";
            data = response;
            d = JSON.parse(data);
			resolve(d);
        }
    });		
		
	});
}

function getMasterPaymentTerms() {
	return new Promise(function(resolve, reject) {
    $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getMasterPaymentTerms.php",
        data: { code: '1' },     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            var options = "";

            data = response;
            d = JSON.parse(data);
			resolve(d);
        }
    });		
	});
}

function getMasterHsCode() {
	return new Promise(function(resolve, reject) {
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getMasterHsCode.php",
        data: { code: '1' },     // multiple data sent using ajax
        success: function (response) {
            data = response;
            d = JSON.parse(data);
			resolve(d);
        }
    });
	})
}
function getPort() {
	
	return new Promise(function(resolve, reject) {
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getPort.php",
        data: { code: '1' },     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
           // var options = "";

            data = response;
            d = JSON.parse(data);
			resolve(d);
        }
    });		
		
		
	});
	

}


function getMasterSupplier(){
	return new Promise(function(resolve, reject) {
		$.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getMasterSupplier.php", 
        data : { code : '1'},     // multiple data sent using ajax
        success: function (response) {
			data = response;
				d = JSON.parse(data);
				resolve(d);
        }
      });		
		
		
	});
}

async function getListWS($id_customer){
	GenerateTableWs($id_customer);


if(!ids){
	var pass='';
}else{
	
	if(ids != 0){
			setTimeout(function(){
		CallServiceWs($id_customer).then(function($responnya) {
			var par_ = $responnya.data.length;
			par__ = par_ - 1;
			console.log($responnya);
			
			//console.log($responnya.data[0]['bppbno']);
			for(var o=0;o<$responnya.data.length;o++){			
			$my_that = {
					id : $responnya.data[o]['idcosting'],
					
				}
				//console.log($my_that);
				GenerateDetail_from_url($my_that,'EXS',ids);
		
			}
			GenerateTableDetail($surat_jalan);
				
		
			//GenerateDetail($my_that,'EXS',ids);
			
			
		});
			},5000);
	}
	
}

}
function CallServiceWs($id_customer){
	if(!ids){
		ids__ = '0'
	}else{
		ids__ = ids
		
	}	
		return new Promise(function(resolve, reject) {
  $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/getListWS.php?id_buyer="+$id_customer+"&ids="+ids__,
        data : { bpbno_internal__ :'--' },     // multiple data sent using ajax
        success: function (response) {
			data = response;
				d = JSON.parse(data);
					resolve(d);
        }
      });		 	
	 });	
	
	
}

function getJO(Item){
/*	//console.log(Item);
		 $.ajax({		
        type:"POST",
        cache:false,  
        url:"webservices/getJO.php", 
        data : { codes: '1',id:Item},     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
			$("#cbokp").empty();
			var options = "";
			console.log(response);
			data = response;
				d = JSON.parse(data);
				//d = response;
				td  = '';
				renders = '';
				console.log(d.records.length);
				if(d.message == '1'){
					for(var x=0;x<d.records.length;x++){
						 options += "<option value='"+decodeURIComponent(d.records[x].id)+"'>"+decodeURIComponent(d.records[x].nama)+"</option>"
						
					}
					console.log(options);
					$("#cbokp").append(options);
						
				}
				if(d.message == '2'){
					alert("Ada Error!, Silahkan Reload Page");
				}
        }
      });

*/
}


tablews = "";
TableDetail = "";
function GenerateTableWs($id_customer){
	if(!ids){
		ids__ = '0'
	}else{
		ids__ = ids
		
		
	}
   if(tablews !=""){
    tablews.clear();
	tablews.destroy();
    //table.rows.add(newDataArray);
    //table.draw();	   
   }
  tablews = $('#TableWs').DataTable( {
        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
		"ajax":  "webservices/getListWS.php?id_buyer="+$id_customer+"&ids="+ids__,
        "columns": [
			{

                "orderable":      '',
                "data":           null,
                "mRender": function (data) {
                    return decodeURIComponent(data.checkbox);
                            }
            } ,
			{ "data": "ws"},
			{ "data": "styleno"},
			{ "data": "destination" },
			{ "data": "qty" },
            { "data": "customer" },
			{ "data": "bppbno_int" },

            { "data": "bppbno"}, 

        ],
        "order": [[0, 'desc']]
    } );
	console.log(tablews.columns);
	

}	


function getJsonDetail(){
	var my_row = TableDetail.row( tr );
	$myjson_detail = my_row.data();

	
}

function getTotalCarton(to,from,index){
	total_carton =to - from + 1;
	$(".TOTAL_CARTON__"+index).val(total_carton);
	return total_carton;
}

function getTotalNw(tot_carton,nilai_nw,index){
		console.log(tot_carton);
	console.log(nilai_nw);
	total_nw =tot_carton * nilai_nw
	$(".TOTAL_NW__"+index).val(total_nw);
	return total_nw;
}
function getTotalGw(tot_carton,nilai_gw,index){

	total_gw =tot_carton * nilai_gw
	$(".TOTAL_GW__"+index).val(total_gw);
	return total_gw;
}
function getTotalPcs(tot_carton,qty_inv,index){
	console.log(tot_carton);
	console.log(qty_inv);
	console.log(index);
	if(tot_carton < 1){
		total_pcs = 0;
	}else{
		total_pcs = qty_inv*tot_carton;
	}
	$(".TOTAL_PCS__"+index).val(total_pcs);
	return total_pcs;
}
$_array_nw = [];
$_array_gw = [];

function generate_array_nw(array_nya){
	total_nw_header = 0;
	for(var z=0;z<array_nya.length;z++){
		total_nw_header = parseFloat(total_nw_header) + parseFloat(array_nya[z].nw_val);
	}
	Mydata.nw =total_nw_header
	$("#txtnw").val(total_nw_header);
}
function generate_array_gw(array_nya){
	total_gw_header = 0;
	for(var z=0;z<array_nya.length;z++){
		total_gw_header = parseFloat(total_gw_header) + parseFloat(array_nya[z].gw_val);
	}
	Mydata.gw =total_gw_header
	$("#txtgw").val(total_gw_header);
}
function handledetail(that){
 	that.value = that.value.toString();
	that.value = that.value.replace(".", ","); 	
	
	
	if (Number.isInteger(parseFloat(that.value))){
 	that.value = that.value.toString();
	that.value = that.value.replace(",", "."); 
	var splt = that.className.split("__");
	var ind =parseFloat(splt[1]) - 1;
	var ind_ori = parseFloat(splt[1])
	if(splt[0] == 'UNIT' ){
		$myjson_detail.data[ind].unit_val = that.value;
	}else if(splt[0] == 'PRICE'){
		$myjson_detail.data[ind].price_val = that.value;
		//price_val
	console.log("PRICE");
	console.log($myjson_detail.data[ind].price_val);		
		console.log($myjson_detail);
	}/* 	else if(splt[0] == 'QTYINV'){
		$myjson_detail.data[ind].qty_invoice_val = that.value;
	}	 */else if(splt[0] == 'CARTON'){
		$myjson_detail.data[ind].carton_val = that.value;
		$_tot_carton = getTotalCarton($myjson_detail.data[ind].carton_to_val,that.value,ind_ori);
		setTimeout(function(){
			getTotalPcs($_tot_carton ,$myjson_detail.data[ind].qty_invoice_val,ind_ori);
		},3000);
	}	else if(splt[0] == 'LOT'){
		$myjson_detail.data[ind].lot_val = that.value;//qty_invoice_val
	}	else if(splt[0] == 'CARTON_TO'){
		$myjson_detail.data[ind].carton_to_val = that.value;
		//total_carton =that.value - $myjson_detail.data[ind].carton_val + 1;
		//$(".TOTAL_CARTON__"+ind_ori).val(total_carton);	
		$_tot_carton = getTotalCarton(that.value,$myjson_detail.data[ind].carton_val,ind_ori);
		setTimeout(function(){
			getTotalPcs($_tot_carton ,$myjson_detail.data[ind].qty_invoice_val,ind_ori);
		},3000)

		
	}	else if(splt[0] == 'NW'){
		$myjson_detail.data[ind].nw_val = that.value;
		$_tot_carton = $(".TOTAL_CARTON__"+ind_ori).val();
		setTimeout(function(){
			getTotalNw($_tot_carton,that.value,ind_ori);
			generate_array_nw($myjson_detail.data);
		},3000)		
		
	}	else if(splt[0] == 'GW'){
		$myjson_detail.data[ind].gw_val = that.value;
		$_tot_carton = $(".TOTAL_CARTON__"+ind_ori).val();
		setTimeout(function(){
			getTotalGw($_tot_carton,that.value,ind_ori);
			generate_array_gw($myjson_detail.data);
		},3000)		
	}		
		$("."+that.className).val(that.value);
		console.log($myjson_detail);
	}else{
		console.log("NOT INT");
	}
}
function GenerateTableDetail($arr_bppb){
	//alert("123");
	var $stringify = JSON.stringify($arr_bppb);
	console.log($arr_bppb);
	

    //table.rows.add(newDataArray);
    //table.draw();	   
	CallDetailServices($stringify).then(function($responnya) {

   if(TableDetail !=""){
/*     TableDetail.clear();
	TableDetail.destroy(); */
    TableDetail.clear();
	TableDetail.destroy();	
    }
		
		$myjson_detail = {};
		$myjson_detail = $responnya;
		console.log($myjson_detail);
	if(ids){
		par_url = ids;
	}else{
		par_url = "00";
	}		
		
		TableDetail = $('#TableDetail').DataTable( {

				//"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
					"ajax":  "webservices/getListInvoiceDetail.php?bppbno_int="+$stringify+"&id="+par_url,
				"columns": [
						{ "data": "bppbno_int"}, 
						{ "data": "ws"},
						{ "data": "styleno"},
						{ "data": "so" },
						//{ "data": "buyerpo" },
						{ "data": "dest" },
						
						{ "data": "color"}, 
						{ "data": "size"}, 
						{ "data": "qty_so"}, //7
						{ "data": "qty_bpb"}, 
						{
			
						"orderable":      '',
						"data":           null,
						"mRender": function (data) {
							return decodeURIComponent(data.unit);
									}
					} , 
						
						{ "data": "bal"}, 
						{
			
						"orderable":      '',
						"data":           null,
						"mRender": function (data) {
							return decodeURIComponent(data.qty_invoice);
									}
					} ,  
						{
			
						"orderable":      '',
						"data":           null,
						"mRender": function (data) {
							return decodeURIComponent(data.carton);//1
									}
					} ,
						{
			
						"orderable":      '',
						"data":           null,
						"mRender": function (data) {
							return decodeURIComponent(data.carton_to);//2
									}
					} ,
						{
			
						"orderable":      '',
						"data":           null,
						"mRender": function (data) {
							return decodeURIComponent(data.sum_carton);//3
									}
					} ,
						{
			
						"orderable":      '',
						"data":           null,
						"mRender": function (data) {
							return decodeURIComponent(data.sum_pcs);//4
									}
					} ,
						{
			
						"orderable":      '',
						"data":           null,
						"mRender": function (data) {
							return decodeURIComponent(data.nw);//5
									}
					} ,
						{
			
						"orderable":      '',
						"data":           null,
						"mRender": function (data) {
							return decodeURIComponent(data.gw);//6
									}
					} ,
						{
			
						"orderable":      '',
						"data":           null,
						"mRender": function (data) {
							return decodeURIComponent(data.sum_nw);//7
									}
					} ,
						{
			
						"orderable":      '',
						"data":           null,
						"mRender": function (data) {
							return decodeURIComponent(data.sum_gw);//8
									}
					} 
				],

        

        scrollX				:        "100%",
        scrollCollapse		: true,
		scrollXInner		:"150%",
        paging				:         false,
		fixedColumns 		:true,
fixedColumns: {
                leftColumns: 1
                },	
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
            // var subTotal_satutigasatu = new Array();
            var groupID = -1;
            var aData = new Array();
            var index = 0;
            
            api.column(4, {page:'current'} ).data().each( function ( group, i ) {
            	
              // console.log(group+">>>"+i);
            
              var vals = api.row(api.row($(rows).eq(i)).index()).data();
              	vals_qty_so = vals.qty_so;
				vals_qty_bpb = vals.qty_bpb;
				vals_bal = vals.bal;
				vals_qty_inv = vals.qty_invoice_val;
				//TOTAL CARTON :
				//alert(val_total_carton);
					if(vals.carton_val === vals.carton_to_val){
						val_total_carton = 0;
					}else{
						val_total_carton = parseFloat(vals.carton_to_val) - parseFloat(vals.carton_val) + 1;
					}
					//alert(val_total_carton);
				//END TOTAL CARTON :
				vals_total_pcs = vals.sum_pcs_val;
				vals_nw = vals.nw_val;
				vals_gw = vals.gw_val;				
				vals_total_nw = vals.sum_nw_val;
				vals_total_gw = vals.sum_gw_val;
              	 
              var nilai_qty_SO = vals.qty_so ? parseFloat(vals_qty_so) : 0;
			  var nilai_qty_BPB = vals.qty_bpb ? parseFloat(vals_qty_bpb) : 0;
			  var nilai_bal = vals.bal ? parseFloat(vals_bal) : 0;
			  var nilai_qty_inv = vals.qty_invoice_val ? parseFloat(vals_qty_inv) : 0;
			  var nilai_total_carton = val_total_carton ? parseFloat(val_total_carton) : 0;
			  var nilai_total_pcs = vals.sum_pcs_val ? parseFloat(vals_total_pcs) : 0;
			  var nilai_nw = vals.nw_val ? parseFloat(vals_nw) : 0;
			  var nilai_gw = vals.gw_val ? parseFloat(vals_gw) : 0;				  
			  var nilai_total_nw = vals.sum_nw_val ? parseFloat(vals_total_nw) : 0;
			  var nilai_total_gw = vals.sum_gw_val ? parseFloat(vals_total_gw) : 0;			  
              if (typeof aData[group] == 'undefined') {
                 aData[group] = new Array();
                 aData[group].rows = [];
                 aData[group].nilai_qty_SO = [];
				 aData[group].nilai_qty_BPB = [];
				 aData[group].nilai_bal = [];
				 aData[group].nilai_qty_inv = [];
				 aData[group].nilai_total_carton = [];
				 aData[group].nilai_total_pcs = [];
				 aData[group].nilai_nw = [];
				 aData[group].nilai_gw = [];					 
				 aData[group].nilai_total_nw = [];
				 aData[group].nilai_total_gw = [];				 
              }
           		aData[group].rows.push(i); 
        			aData[group].nilai_qty_SO.push(nilai_qty_SO); 
					aData[group].nilai_qty_BPB.push(nilai_qty_BPB); 
					aData[group].nilai_bal.push(nilai_bal);
					aData[group].nilai_total_carton.push(nilai_total_carton);
					aData[group].nilai_total_pcs.push(nilai_total_pcs);
					aData[group].nilai_gw.push(nilai_gw);   					
 					aData[group].nilai_nw.push(nilai_nw);
					aData[group].nilai_total_nw.push(nilai_total_nw);
					aData[group].nilai_total_gw.push(nilai_total_gw);               
            });
    

            var idx= 0;

      
          	for(var row in aData){
       
									 idx =  Math.max.apply(Math,aData[row].rows);
      
                   var sum_qty_so = 0; 
				   var sum_qty_bpb = 0;
				   var sum_bal = 0;
				   var sum_qty_inv = 0;
				   var sum_total_carton = 0;
				   var sum_total_pcs = 0;
				   var sum_nw = 0;
				   var sum_gw = 0;					   
				   var sum_total_nw = 0;
				   var sum_total_gw = 0;				   
                   $.each(aData[row].nilai_qty_SO,function(k,v){
                        sum_qty_so = sum_qty_so + v;
                   });      

                    $.each(aData[row].nilai_qty_BPB,function(k,v){
                        sum_qty_bpb = sum_qty_bpb + v;
                   });   	
                    $.each(aData[row].nilai_bal,function(k,v){
                        sum_bal = sum_bal + v;
                   });	
                    $.each(aData[row].nilai_qty_inv,function(k,v){
                        sum_qty_inv = sum_qty_inv + v;
                   });	
                    $.each(aData[row].nilai_total_carton,function(k,v){
                        sum_total_carton = sum_total_carton + v;
                   });			
                    $.each(aData[row].nilai_total_pcs,function(k,v){
                        sum_total_pcs = sum_total_pcs + v;
                   });		
                    $.each(aData[row].nilai_nw,function(k,v){
                        sum_nw = sum_nw + v;
                   });	
                    $.each(aData[row].nilai_gw,function(k,v){
                        sum_gw = sum_gw + v;
                   });					   
                    $.each(aData[row].nilai_total_nw,function(k,v){
                        sum_total_nw = sum_total_nw + v;
                   });	
                    $.each(aData[row].nilai_total_gw,function(k,v){
                        sum_total_gw = sum_total_gw + v;
                   });					   
                   $(rows).eq( idx ).after(
                        '<tr class="group"><td ><b>SUBTOTAL<b></td><td ><b>&nbsp;<b></td><td ><b>&nbsp;<b></td>'+
                        '<td colspan="5" style="text-align:right;">'+number_format(sum_qty_so)  +'</td>'+
						 '<td  style="text-align:right;">'+number_format(sum_qty_bpb)  +'</td>'+
						 '<td colspan="2" style="text-align:right;">'+number_format(sum_bal)  +'</td>'+
						 '<td colspan="4" style="text-align:right;">'+number_format(sum_total_carton)  +'</td>'+
						 '<td  style="text-align:right;">'+number_format(sum_total_pcs)  +'</td>'+
						 '<td  style="text-align:right;">'+number_format(sum_nw)  +'</td>'+
						'<td  style="text-align:right;">'+number_format(sum_gw)  +'</td>'+
						 '<td  style="text-align:right;">'+number_format(sum_total_nw)  +'</td>'+
						'<td  style="text-align:right;">'+number_format(sum_total_gw)  +'</td>'+						
						'</tr>'
                    );
                    
            };

        }	
			} );

		setTimeout(function(){
			generate_array_nw($myjson_detail.data);
		},3000)		

		setTimeout(function(){
			generate_array_gw($myjson_detail.data);
		},5000)		

	});	
	
	
	
  

	//console.log(TableDetail);
	
}


function getTestRow() {
    var testRow = '';
    for (var i=0;i<$('#TableDetail th').length;i++) {
        var rand = Math.floor((Math.random()*100)+1);
        testRow+='<td>col '+rand+'</td>';
    }
    testRow='<tr>'+testRow+'</tr>';
    return testRow;
}

//$(document).ready(function () { ..

	
function getIndexArray($seArr,$carinya){
	console.log($seArr);
	for(var $search=0;$search<$seArr.arraynya.length;$search++){
		console.log($seArr.arraynya[$search].key);
		if($seArr.arraynya[$search].key == $carinya ){
			return $search;
		}
		else{
			console.log("FINDING!");
		}
	}
}
function validation_customer($seArr,$carinya_customer,$cari_destination){
	console.log($seArr);
	//alert("123");
	console.log($cari_destination);
	for(var $search=0;$search<$seArr.arraynya.length;$search++){
		if($seArr.arraynya[$search].destination == $cari_destination ){
			$valid = true;
		}
		else{
			console.log("FINDING!");
			$valid =  false;
		}
	}	
	return $valid;
	
}


function validasi_before_save(){
	if(Mydata.typeinvoice == "1"){
		if(Mydata.datepicker1            ==''){alert('Tanggal PackingList/Invoice Harus diisi');return false}
		if(Mydata.head_costumer          ==''){alert('Customer Harus Diisi');return false}
		if(Mydata.txtconsignee           ==''){alert('Buyer/Penerima Harus Diisi');return false}
		if(Mydata.txtshipper             ==''){alert('Seller/Sender Pengirim Harus Diisi');return false}
		if(Mydata.txtmanufacture_address ==''){alert('Buyer/Receiver Adress Harus Diisi');return false}
		if(Mydata.txtvessel_name         ==''){alert('Vessel Name Harus Diisi');return false}
		if(Mydata.txtlc_no               ==''){alert('Contract No# Harus Diisi');return false}
		if(Mydata.txtlc_issue_by         ==''){alert('Contract Date');return false}
		if(Mydata.txths_code             ==''){alert('HS Code Harus Diisi');return false}
		if(Mydata.txtetd                 ==''){alert('Delivary Time Harus Diisi');return false}
		if(Mydata.txtid_pterms           ==''){alert('Payment Terms Harus Diisi');return false}
		if(Mydata.txtshipped_by          ==''){alert('Delivary By Harus Diisi');return false}
/* 		if(Mydata.txtnw                  ==''){alert('Net Weight Harus Diisi');return false}
		if(Mydata.txtgw                  ==''){alert('Gross Weight Harus Diisi');return false} */
		if(Mydata.txtmeasurement         ==''){alert('Measurement Harus Diisi');return false}
		if(Mydata.txtmeasurement2         ==''){alert('Measurement Harus Diisi');return false}
	}else{
		
		
		
	}
	
}