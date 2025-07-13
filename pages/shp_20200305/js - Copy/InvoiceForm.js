$(document).ready(function () {
    Mydetail = {};
       Mydata = {
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
        container_no: '',
        seal_no: '',
		faktur_pajak: '',
        detail: [],
		typeinvoice :'',
		post       :'',
		ws       :'',
    }

	getTypeInvoiceFromUrl();
    localStorage.clear();
    sessionStorage.clear();
    getMasterSupplier();
    getPort();
    getMasterHsCode();
    getMasterRoute();
    getMasterShipMode();
    getMasterPaymentTerms();
    getMasterMeasurement();
    getIdFromUrl();
    //getListData();
	getListWS();
	InitData();
	generateDetail();
});
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
function getTypeInvoiceFromUrl() {
    console.log("Begin Start Gety URL");
    var url = window.location.href;
    var mySplit = url.split("&typeinvoice=");
    ids = mySplit[1];
    console.log(ids);
	if(ids == '1'){
		$("#form_").load("Invoice/TemplateLocal.php");
	}	
	else if(ids == '2'){
		$("#form_").load("Invoice/TemplateCommercial.php");
	}
	
	
	setInterval(function(){ def_var() }, 6000);	
	
} 	
	



function def_var(){
	console.log("Begin Def Var");
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
faktur_pajak          =$("#faktur_pajak").val();




            
if(txtinvno              	==''){$("#txtinvno").val($defaultData.invno);}
if(datepicker1              ==''){$("#datepicker1").val($defaultData.invdate);}
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
if(txtlc_issue_by           ==''){$("#txtlc_issue_by").val($defaultData.lc_issue_by);}
if(txths_code               ==''){$("#txths_code").val($defaultData.hs_code);}
if(datepicker2              ==''){$("#datepicker2").val($defaultData.etd);}
if(datepicker3              ==''){$("#datepicker3").val($defaultData.eta); }
if(datepicker4              ==''){$("#datepicker4").val($defaultData.eta_lax);}
if(txtseal_no               ==''){$("#txtseal_no").val($defaultData.seal_no);}
if(txtid_pterms             ==''){$("#txtid_pterms").val($defaultData.id_pterms).trigger("change");}
if(txtshipped_by            ==''){$("#txtshipped_by").val($defaultData.shipped_by).trigger("change");}
if(txtroute                 ==''){$("#txtroute").val($defaultData.route).trigger("change");}
if(txtship_to               ==''){$("#txtship_to").val($defaultData.ship_to);}
if(txtnw                    ==''){$("#txtnw").val($defaultData.nw);}
if(txtgw                    ==''){$("#txtgw").val($defaultData.gw);}
if(txtmeasurement           ==''){$("#txtmeasurement").val($defaultData.measurement).trigger("change"); }
if(faktur_pajak             ==''){ $("#faktur_pajak").val($defaultData.faktur_pajak);


}

console.log(faktur_pajak)














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
                location.reload();
            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }
        }
    });	
}
/*
function sendAdd(){
		var form          = document.createElement("form");
		form.method = "POST";
		form.action =  getId(); 
		
	var MyNoSo= document.createElement("input"); 
		MyNoSo.name = "MyNoSo";
		MyNoSo.value = $("#detail_no_so").val();
		form.appendChild(MyNoSo);  
		if(MyNoSo.value == ""){
			{ swal({ title: 'So No Tidak Boleh Kosong', patherror }); valid = false;}
			return false;
		}		
	var MyQty= document.createElement("input"); 
		MyQty.name = "MyQty";
		MyQty.value = $("#detail_qty").val();
		form.appendChild(MyQty);  
		if(MyQty.value == ""){
			{ swal({ title: 'Qty Tidak Boleh Kosong', patherror }); valid = false;}
			return false;
		}	

	var MyUnit= document.createElement("input"); 
		MyUnit.name = "MyUnit";
		MyUnit.value = $("#detail_unit").val();
		form.appendChild(MyUnit);  
		if(MyUnit.value == ""){
			{ swal({ title: 'Unit Tidak Boleh Kosong', patherror }); valid = false;}
			return false;
		}	
	var MyPrice= document.createElement("input"); 
		MyPrice.name = "MyUnit";
		MyPrice.value = $("#detail_price").val();
		form.appendChild(MyPrice);  
		if(MyPrice.value == ""){
			{ swal({ title: 'Price Tidak Boleh Kosong', patherror }); valid = false;}
			return false;
		}		
   document.body.appendChild(form);
   form.submit();			
	
}
*/
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

async function generateDetail(){
	    await $.ajax({
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

function Save() {
    Mydata.detail = Mydetail;
    event.preventDefault();
    $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/UpdateInvoiceHeader.php",
        data: { code: '1', data: Mydata},     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            var options = "";
            data = response;
            d = JSON.parse(data);
            if (d.message == '1') {
                alert("Data Berhasil diupdate");
                //location.reload();
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
    else if (thisX.id == "faktur_pajak") {
        Mydata.faktur_pajak = thisX.value;
    }	
}


function InitData() {
	getListData().then(function(d) {
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
		Mydata.container_no       = decodeURIComponent(d.records[0].container_no);
		Mydata.seal_no            = decodeURIComponent(d.records[0].seal_no);
		Mydata.vessel_name        = decodeURIComponent(d.records[0].vessel_name);
		Mydata.faktur_pajak       = decodeURIComponent(d.records[0].faktur_pajak);
		Mydata.typeinvoice        = decodeURIComponent(d.records[0].typeinvoice);
		Mydata.ws       		  = decodeURIComponent(d.records[0].ws);
		Mydata.post       		  = decodeURIComponent(d.records[0].post);
		$defaultData =Mydata; 	
		
		console.log(Mydata);
		$("#txtinvno").val(Mydata.invno);
		$("#datepicker1").val(Mydata.invdate);
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
		$("#txths_code").val(Mydata.hs_code);
		$("#datepicker2").val(Mydata.etd);
		$("#datepicker3").val(Mydata.eta); //txteta
		$("#datepicker4").val(Mydata.eta_lax); //txteta_lax
		$("#txtseal_no").val(Mydata.seal_no);
		$("#txtid_pterms").val(Mydata.id_pterms).trigger("change");
		$("#txtshipped_by").val(Mydata.shipped_by).trigger("change");
		$("#txtroute").val(Mydata.route).trigger("change");
		$("#txtship_to").val(Mydata.ship_to);
		$("#txtnw").val(Mydata.nw);
		$("#txtgw").val(Mydata.gw);
		$("#txtmeasurement").val(Mydata.measurement).trigger("change"); 
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
ids = 0;
function getIdFromUrl() {
    console.log("Begin Start Gety URL");
    var url = window.location.href;
    var mySplit = url.split("&noid=");
    ids = mySplit[1];
	idsSplit = ids.split("&");
	if(idsSplit[1]){
		ids = idsSplit[0];
	}
    console.log(ids);

} 

function getListData() {
	return new Promise(function(resolve, reject) {
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getListData.php",
        data: { code: '1', id: ids },     // multiple data sent using ajax
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

async function getMasterMeasurement() {
    await $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getMasterMeasurement.php",
        data: { code: '1' },     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            var options = "";

            data = response;
            d = JSON.parse(data);
            //d = response;
            td = '';
            renders = '';

            if (d.message == '1') {

                for (var x = 0; x < d.records.length; x++) {
                    options += "<option value='" + decodeURIComponent(d.records[x].id) + "'>" + decodeURIComponent(d.records[x].nama) + "</option>"

                }

                $("#txtmeasurement").append(options);

            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }
        }
    });
}

async function getMasterRoute() {
    await $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getMasterRoute.php",
        data: { code: '1' },     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            var options = "";

            data = response;
            d = JSON.parse(data);
            //d = response;
            td = '';
            renders = '';

            if (d.message == '1') {

                for (var x = 0; x < d.records.length; x++) {
                    options += "<option value='" + decodeURIComponent(d.records[x].id) + "'>" + decodeURIComponent(d.records[x].nama) + "</option>"

                }

                $("#txtroute").append(options);

            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }
        }
    });
}
async function getMasterShipMode() {
    await $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getMasterShipMode.php",
        data: { code: '1' },     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            var options = "";

            data = response;
            d = JSON.parse(data);
            //d = response;
            td = '';
            renders = '';

            if (d.message == '1') {

                for (var x = 0; x < d.records.length; x++) {
                    options += "<option value='" + decodeURIComponent(d.records[x].id) + "'>" + decodeURIComponent(d.records[x].nama) + "</option>"

                }

                $("#txtshipped_by").append(options);

            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }
        }
    });
}

async function getMasterPaymentTerms() {
    await $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getMasterPaymentTerms.php",
        data: { code: '1' },     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            var options = "";

            data = response;
            d = JSON.parse(data);
            //d = response;
            td = '';
            renders = '';

            if (d.message == '1') {

                for (var x = 0; x < d.records.length; x++) {
                    options += "<option value='" + decodeURIComponent(d.records[x].id) + "'>" + decodeURIComponent(d.records[x].nama) + "</option>"

                }

                $("#txtid_pterms").append(options);

            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }
        }
    });
}

async function getMasterHsCode() {
    await $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getMasterHsCode.php",
        data: { code: '1' },     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            var options = "";
        
            data = response;
            d = JSON.parse(data);
            //d = response;
            td = '';
            renders = '';
     
            if (d.message == '1') {
         
                for (var x = 0; x < d.records.length; x++) {
                    options += "<option value='" + decodeURIComponent(d.records[x].id) + "'>" + decodeURIComponent(d.records[x].nama) + "</option>"

                }

                $("#txths_code").append(options);

            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }
        }
    });
}
async function getPort() {
    await $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getPort.php",
        data: { code: '1' },     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            var options = "";

            data = response;
            d = JSON.parse(data);
            //d = response;
            td = '';
            renders = '';

            if (d.message == '1') {
             
                for (var x = 0; x < d.records.length; x++) {
                    options += "<option value='" + decodeURIComponent(d.records[x].id) + "'>" + decodeURIComponent(d.records[x].nama) + "</option>"

                }
         
                $("#txtport_of_loading").append(options);
                $("#txtport_of_discharges").append(options);
                $("#txtport_of_entrances").append(options);

            }
            if (d.message == '2') {
                alert("Ada Error!, Silahkan Reload Page");
            }
        }
    });
}


async function getMasterSupplier(){
		await $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getMasterSupplier.php", 
        data : { code : '1'},     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
			var options = "";
		
			data = response;
				d = JSON.parse(data);
				//d = response;
				td  = '';
				renders = '';
	
				if(d.message == '1'){
			
					for(var x=0;x<d.records.length;x++){
						 options += "<option value='"+decodeURIComponent(d.records[x].id)+"'>"+decodeURIComponent(d.records[x].nama)+"</option>"
						
					}
				
					$("#head_costumer").append(options);
						
				}
				if(d.message == '2'){
					alert("Ada Error!, Silahkan Reload Page");
				}
        }
      });
}

function getListWS(){
	//console.log(Item);
		 $.ajax({		
        type:"POST",
        cache:false,  
        url:"webservices/getListWS.php", 
        data : { codes: '1'},     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
			$("#cbokp").empty();
			var options = "";

			data = response;
				d = JSON.parse(data);
				//d = response;
				td  = '';
				renders = '';
	
				if(d.message == '1'){
					for(var x=0;x<d.records.length;x++){
						 options += "<option value='"+decodeURIComponent(d.records[x].id)+"'>"+decodeURIComponent(d.records[x].nama)+"</option>"
						
					}
	
					$("#cbokp").append(options);
						
				}
				if(d.message == '2'){
					alert("Ada Error!, Silahkan Reload Page");
				}
        }
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