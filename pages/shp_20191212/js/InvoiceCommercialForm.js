$( document ).ready(function() {
localStorage.clear();
sessionStorage.clear();
//getNoBPB();
getNoInvoice();
getListBank();
datas= {
	id : '',
	bpb_no : '',
	id_invoiceheader : '',
	fakturpajak : '',
	invno : '',
	from : '',
	to : '',
	po : '',
	post : '',
	typeinvoice :'',
	amount : '',
	idcoa : '',
	save : "INSERT",
}





checkurl();
});

async function checkurl(){
var url = window.location.href;
splitUrl = url.split("&id=");
var idurl = splitUrl[1];
console.log(idurl);
if(idurl !=null){
	
getDetailInvoiceActual(idurl);
	
}
}


function post(){
	
	
	if(datas.bpb_no == ''){
		alert("isian belum benar")
		return false;
	}
	if(datas.from == ''){
		alert("isian belum benar")
		return false;
	}	
	if(datas.to == ''){
		alert("isian belum benar")
		return false;
	}	
	console.log(datas.id_invoiceheader);
	    $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/postPackInv.php",
		//PL = past packing list
		//INV = part invoice
        data: { code: '1', part:'INV',id:datas.id_invoiceheader},     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            var options = "";
            data = response;
            d = JSON.parse(data);
			if (d.status == 'ok'){
				if (d.message == '1') {
					alert("Data berhasil di post");
					window.location="/pages/shp/?mod=InvoiceCommercialPage";
				}
			}
        }
    });
}

async function getDetailInvoiceActual(Items){
		await $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getDetailInvoiceActual.php", 
        data : { code : '1',id:Items   },     // multiple data sent using ajax
        success: function (response) {
			console.log(response);
			data = response;
				d = JSON.parse(data);
				//d = response;
				if(d.message == '1'){
					datas.id                =decodeURIComponent(d.records[0].id);
					datas.bpb_no            =decodeURIComponent(d.records[0].bpbno);
					datas.id_invoiceheader  =decodeURIComponent(d.records[0].n_idinvoiceheader);
					datas.from              =decodeURIComponent(d.records[0].v_from);
					datas.to                =decodeURIComponent(d.records[0].v_to);
					datas.invno                =decodeURIComponent(d.records[0].invno);
					datas.fakturpajak                =decodeURIComponent(d.records[0].fakturpajak);
					datas.typeinvoice        =decodeURIComponent(d.records[0].typeinvoice);
					datas.post                =decodeURIComponent(d.records[0].post);
					datas.po                =decodeURIComponent(d.records[0].v_pono);
					datas.amount            =decodeURIComponent(d.records[0].n_amount); 
					datas.idcoa				=decodeURIComponent(d.records[0].idcoa); 
					console.log(datas.idcoa);
					datas.save            	="UPDATE"; 					
					getNoBPB(datas.bpb_no )
					
					setTimeout(function(){ 				
						default_data();
				}, 4000);	
					console.log(datas);
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
}
function Save(){
	console.log(datas);
	$.ajax({		
		type:"POST",
		cache:false,
		url:"webservices/SaveInvoiceActualDetail.php", 
		data : { code : '1', datass:datas },     // multiple data sent using ajax
		success: function (response) {
			console.log(response);
			data = response;
				d = JSON.parse(data);
				//d = response;

				if(d.message == '1'){
					window.location.href="?mod=InvoiceCommercialPage";
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	
		
	
	
}
async function default_data(){
    await $("#id_invoiceheader").val(datas.id_invoiceheader).trigger("change");
	await $("#bpb_no").val(datas.bpb_no).trigger("change");
	await $("#type_invoice").val(datas.typeinvoice);
	await $("#from").val(datas.from);
	await $("#to").val(datas.to);
	await $("#po").val(datas.po);
	await $("#amount").val(datas.amount);
	console.log(datas.idcoa);
	await $("#idcoa").val(datas.idcoa).trigger('change');
	if(datas.post == '2'){
		await $(".myPost").css("display","none");
	}
	else {
		await $(".myPost").css("display","");
		
	}
}
function handlekeyup(Item){
	console.log(Item.id);
	if(Item.id == "id_invoiceheader"){//idcoa
		
		datas.id_invoiceheader = Item.value;
	}else if(Item.id == "bpb_no"){
		//getDetailBpb(Item.value)
		datas.bpb_no = Item.value;
	}else if(Item.id == "from"){
		datas.from = Item.value;
	}else if(Item.id == "to"){
		datas.to = Item.value;
	}else if(Item.id == "po"){
		datas.po = Item.value;
	}else if(Item.id == "amount"){
		datas.amount = Item.value;
	
	}else if(Item.id == "idcoa"){
		datas.idcoa = Item.value;
	}	
}

async function getDataTable(){
	  var table = await $('#MasterInvoiceActualTable').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,	
        paging: true,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });		
}

	function getNoInvoice(){
		var options = "";
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getNoInvoice.php", 
        data : { code : '1',   },     // multiple data sent using ajax
        success: function (response) {
			console.log(response);
			data = response;
				d = JSON.parse(data);
				//d = response;

				if(d.message == '1'){
						options += "<option value='x99x' >--PILIH Invoice-- </option>"
						for(var x=0;x< d.records.length;x++){
						options += "<option value='"+decodeURIComponent(d.records[x].id)+"'>"+decodeURIComponent(d.records[x].nama)+" </option>"
					}	
					$("#id_invoiceheader").append(options);		
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	
	
}



function getListBank() {
    $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getListBank.php",
        data: { code: '1' },     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            var options = "";
            console.log(response);
            d = JSON.parse(response);
            //d = response;
			options += "<option value='x99x' >--PILIH Bank-- </option>"
            renders = '';
            console.log(d.records.length);
			if(d.status == 'ok'){
            if (d.message == '1') {
						for(var x=0;x< d.records.length;x++){
						options += "<option value='"+decodeURIComponent(d.records[x].id)+"'>"+decodeURIComponent(d.records[x].tampil)+" </option>"
					}				
				
            }
			$("#idcoa").append(options);	
            if (d.message == '2') {
                alert("Data Belum ada");
            }				
			}else{
				alert(d.message);
				
			}
        }
    });
}




	function getNoBPB(bpbs){
		var options = "";
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getNoBPB.php", 
        data : { code : '1',  bpb:bpbs },     // multiple data sent using ajax
        success: function (response) {
			console.log(response);
			data = response;
				d = JSON.parse(data);
				//d = response;

				if(d.message == '1'){
						options += "<option value='x99x' >--PILIH BPB-- </option>"
						for(var x=0;x< d.records.length;x++){
						options += "<option value='"+decodeURIComponent(d.records[x].id)+"'>"+decodeURIComponent(d.records[x].nama)+" </option>"
					}	
					$("#bpb_no").append(options);		
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
}


	function getListData(){
		td = "";
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getDetailInvoiceActual.php", 
        data : { code : '1',   },     // multiple data sent using ajax
        success: function (response) {
			console.log(response);
			data = response;
				d = JSON.parse(data);
				//d = response;
				td  = '';
				renders = '';
				var length = d.records.length;
				console.log(d.records.length);
				if(d.message == '1'){

						$("#render").append(td);
						getDataTable();
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	
	
}
	function getDetailBpb(id){
		if(!id){
			return false;
		}
		
		datas.bpb_no            =id; 
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getDetailBpb.php", 
        data : { code : '1', id:id  },     // multiple data sent using ajax
        success: function (response) {
			console.log(response);
			data = response;
				d = JSON.parse(data);
				//d = response;
				td  = '';
				renders = '';
				if(d.message == '1'){
					datas.po                =decodeURIComponent(d.records[0].buyerno);
					datas.amount            =decodeURIComponent(d.records[0].n_amount); 
					
					$("#po").val(datas.po);
					$("#amount").val(datas.amount);
					console.log(datas);
					return false;
				}else{
					
					datas.po                = "";
					datas.amount            = ""; 			
					$("#po").val("");
					$("#amount").val("");					
					return false;
					
				}
        }
      });	
	
	
}


