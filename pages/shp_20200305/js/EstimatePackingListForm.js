$( document ).ready(function() {
localStorage.clear();
sessionStorage.clear();
getNoSO();
getNoInvoice();
datas= {
	id : '',
	so_no : '',
	id_invoiceheader : '',
	from : '',
	to : '',
	po : '',
	amount : '',
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

getDetailEstimatePackingList(idurl);
	
}
}

function getDetailEstimatePackingList(Items){
		$.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getDetailEstimatePackingList.php", 
        data : { code : '1',id:Items   },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
			data = response;
				d = JSON.parse(data);
				console.log(d);
				//d = response;
				if(d.message == '1'){
					datas.id                =decodeURIComponent(d.records[0].id);
					datas.so_no            = d.records[0].so_no;
					datas.id_invoiceheader  =d.records[0].n_idinvoiceheader;
					datas.from              =decodeURIComponent(d.records[0].v_from);
					datas.to                =decodeURIComponent(d.records[0].v_to);
					datas.po                =decodeURIComponent(d.records[0].v_pono);
					datas.amount            =decodeURIComponent(d.records[0].n_amount); 
					datas.save            	="UPDATE"; 					
					default_data();
					console.log(datas);
					console.log(d.records[0].n_idinvoiceheader);
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
		url:"webservices/SaveEstimatePackingList.php", 
		data : { code : '1', datass:datas },     // multiple data sent using ajax
		success: function (response) {
			console.log(response);
			data = response;
				d = JSON.parse(data);
				//d = response;

				if(d.message == '1'){
					window.location.href="?mod=EstimatePackingListPage";
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	
		
	
	
}

async function default_data(){
await $("#id_invoiceheader").val(datas.id_invoiceheader).trigger("change");
await 	$("#so_no").val(datas.so_no).trigger("change");
await 	$("#from").val(datas.from);
await 	$("#to").val(datas.to);
await 	$("#po").val(datas.po);
await 	$("#amount").val(datas.amount);
}


function handlekeyup(Item){
	console.log(Item.id);
	if(Item.id == "id_invoiceheader"){
		
		datas.id_invoiceheader = Item.value;
	}else if(Item.id == "so_no"){
		datas.so_no = Item.value;
	}else if(Item.id == "from"){
		datas.from = Item.value;
	}else if(Item.id == "to"){
		datas.to = Item.value;
	}else if(Item.id == "po"){
		datas.po = Item.value;
	}else if(Item.id == "amount"){
		datas.amount = Item.value;
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
        url:"webservices/getNoProformaInvoice.php", 
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

	function getNoSO(){
		var options = "";
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getNoSo.php", 
        data : { code : '1',   },     // multiple data sent using ajax
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
					$("#so_no").append(options);		
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


