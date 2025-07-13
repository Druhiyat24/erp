$( document ).ready(function() {
	urlPost = '';
/*
	  var table = $('#addNumberOfSize').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });
		function edit(){  
		alert(data);
	}
 

*/
if(sessionStorage.id){
	sessionStorage.clear();

	
}


	  var table =  $('#exampleForm').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        pageLength: 20,
		searching: false,
		filter:false,
		bPaginate:false,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });


});

patherror = "../../images/error.jpg";


function handleSubDetail(MySubItem){
	console.log(MySubItem.id);
	if(MySubItem.id == 'JenisKain'){
		console.log("Begin JenisKain");
		kain0 =$("#kain0").val();
		if(kain0 != null){
			console.log("Begin JenisKain");
			for(var x=0;x < no_roll.length;x++){
				$("#Kain"+x).val(MySubItem.value);
			}
		}
	}	
	else if(MySubItem.id == 'MyColor'){
		console.log("Begin MyColor");
		color0 =$("#color0").val();
		if(color0 != null){
			console.log("Begin MyColor");
			for(var x=0;x < no_roll.length;x++){
				$("#color"+x).val(MySubItem.value);
			}
		}
	}	
	else if(MySubItem.id == 'MySku'){
		console.log("Begin MyColor");
		sku0 =$("#sku0").val();
		if(sku0 != null){
			console.log("Begin MySku");
			for(var x=0;x < no_roll.length;x++){
				$("#sku"+x).val(MySubItem.value);
			}
		}
	}	
}


function Save(mySave){
	sessionStorage.setItem("id","true");
	event.preventDefault();
	if(mySave == 'Add'){
		sendAdd();
	}else if(mySave == 'Edit'){
	//alert("123");	
	sendEdit();
	
		
	}
	

}

function validasi(){

}

function sendEdit(){
	var form          = document.createElement("form");
		form.method = "POST";
		form.action =  getId();   	
		console.log(getId());
	//MyQty
	//MyPrice
	
	var MyPrice= document.createElement("input"); 
		MyPrice.name = "txtprice";
		MyPrice.value = $("#MyPrice").val();
		form.appendChild(MyPrice);  	
	
	var MyQty= document.createElement("input"); 
		MyQty.name = "txtqty";
		MyQty.value = $("#MyQty").val();
		form.appendChild(MyQty);  
		if(MyQty.value == ""){
			{ swal({ title: 'Qty Tidak Boleh Kosong', patherror }); valid = false;}
			return false;
		}	
		
	var JenisKain = document.createElement("input");  
		JenisKain.name = "txtkain";
	    JenisKain.value = $("#JenisKain").val();
		form.appendChild(JenisKain);  
			if(JenisKain.value == ""){
			{ swal({ title: 'Jenis Kain Tidak Boleh Kosong', patherror }); valid = false;}
			return false;
		}	
		
	var MyColor = document.createElement("input");  
		MyColor.name = "txtcolor";
	    MyColor.value = $("#MyColor").val();
		form.appendChild(MyColor);  
			if(MyColor.value == ""){
			{ swal({ title: 'Color Tidak Boleh Kosong', patherror }); valid = false;}
			return false;
		}	
	var MySku = document.createElement("input");  
		MySku.name = "txtsku";
	    MySku.value = $("#MySku").val();	
		form.appendChild(MySku);
		form.appendChild(MyColor);  
			if(MySku.value == ""){
			{ swal({ title: 'SKU Tidak Boleh Kosong', patherror }); valid = false;}
			return false;
		}		
	var MyBarcode     = document.createElement("input"); 	
		MyBarcode.name = "txtbarcode";
		MyBarcode.value = $("#MyBarcode").val();
		form.appendChild(MyBarcode);

	var MyNotes       = document.createElement("input"); 		
		MyNotes.name  = "txtnotes";
		MyNotes.value = $("#MyNotes").val();
		form.appendChild(MyNotes);
		

   document.body.appendChild(form);
   form.submit();		
}

function sendAdd(){
	var form          = document.createElement("form");
		form.method = "POST";
		form.action =  getId();   	
		
	var JenisKain = document.createElement("input");  
		JenisKain.name = "txtkain";
	    JenisKain.value = $("#JenisKain").val();
		form.appendChild(JenisKain);  
			if(JenisKain.value == ""){
			{ swal({ title: 'Jenis Kain Tidak Boleh Kosong', patherror }); valid = false;}
			return false;
		}	



	var MyColor = document.createElement("input");  
		MyColor.name = "txtcolor";
	    MyColor.value = $("#MyColor").val();
		form.appendChild(MyColor);  
		if(MyColor.value == ""){
			{ swal({ title: 'Color Tidak Boleh Kosong', patherror }); valid = false;}
			return false;
		}

/*	var MySku = document.createElement("input");  
		MySku.name = "txtsku";
	    MySku.value = $("#MySku").val();	
		form.appendChild(MySku);
		if(MySku.value == ""){
			{ swal({ title: 'SKU Tidak Boleh Kosong', patherror }); valid = false;}
			return false;
		}

	
	var MyBarcode     = document.createElement("input"); 	
		MyBarcode.name = "txtbarcode";
		MyBarcode.value = $("#MyBarcode").val();
		form.appendChild(MyBarcode);



	var MyNotes       = document.createElement("input"); 		
		MyNotes.name  = "txtnotes";
		MyNotes.value = $("#MyNotes").val();
		form.appendChild(MyNotes);

	
	var MyUnit        = document.createElement("input"); 
		MyUnit.name  = "txtunit2";
	    MyUnit.value = $("#MyUnit").val();
		form.appendChild(MyUnit);
		if(MyUnit.value == ""){
			{ swal({ title: 'Unit Tidak Boleh Kosong', patherror }); valid = false;}
			return false;
		}
*/

	
	var MyQtyAdd      = document.createElement("input"); 
		MyQtyAdd.name = "addqty";
	    MyQtyAdd.value = $("#MyQtyAdd").val();
		form.appendChild(MyQtyAdd);
	
	var NumberOfSize  = document.createElement("input");  
		NumberOfSize.name = "txtroll";
        NumberOfSize.value = $("#NumberOfSize").val();
		console.log(NumberOfSize.value);
		form.appendChild(NumberOfSize);
		if(NumberOfSize.value == "99"){
			{ swal({ title: 'Jml Size Tidak Boleh Kosong', patherror }); valid = false;}
			return false;
		}
		
/*
no_roll = [];
jml_roll = [];
addqty = [];
barcode = [];
pxdet = [];
*/	
console.log(no_roll);
for(var y=0;y<no_roll.length;y++){
	var NoRoll  = document.createElement("input");  
		NoRoll.name = "no_roll[]";
        NoRoll.value = no_roll[y];
		form.appendChild(NoRoll);
		if(NoRoll.value == ""){
			{ swal({ title: 'Size Tidak Boleh Kosong', patherror }); valid = false;}
			return false;
		}		
	var JmlRoll  = document.createElement("input");  
		JmlRoll.name = "jml_roll[]";
        JmlRoll.value = jml_roll[y];
		form.appendChild(JmlRoll);
		if(JmlRoll.value == ""){
			{ swal({ title: 'Qty Tidak Boleh Kosong', patherror }); valid = false;}
			return false;
		}			
	var kainForm  = document.createElement("input");  
		kainForm.name = "kain[]";
        kainForm.value = kain[y];
		form.appendChild(kainForm);
	var colorForm  = document.createElement("input");  
		colorForm.name = "color[]";
        colorForm.value = color[y];	
form.appendChild(colorForm);
	var PxDet  = document.createElement("input");  
		PxDet.name = "pxdet[]";
        PxDet.value = pxdet[y];	
		form.appendChild(PxDet);
			
	
}

   document.body.appendChild(form);
   form.submit();		
		
}
function no(Z){
	var input = "";
	input = "<input id='no"+Z+"'  type='text' value='' placeholder='no' style='width:70px;visibility:hidden'>";
	return input;	
}
function no_rolls(Z){
	var input = "";
	input = "<select id='no_roll___"+Z+"'  onChange='HandleDetail(this)' placeholder='Size'>";
	input += "<option value=''>Pilih Size</option>";
	input += "<option value='S'>S</option>";
	input += "<option value='M'>M</option>";
	input += "<option value='L'>L</option>";
	input += "<option value='XL'>XL</option>";
	input += "<option value='XXL'>XXL</option>";
	input += "<option value='XXXL'>XXXL</option>";
	input += "</select>";
	return input;	
}


function sku(Z){
	var input = "";
	var value = ''; 
	value = $("#MySku").val();	
	input = "<input id='sku"+Z+"' readonly type='text' value='"+value+"' placeholder='Sku'>";
	return input;	
}


function notes(Z){
	var input = "";
	var value = '';
	value = $("#MyNotes").val();	
	input = "<input id='notes"+Z+"' readonly type='text' value='"+value+"' placeholder='Notes'>";
	return input;	
}

function unit(Z){
	var input = "";
	var value = '';
	value = $("#MyUnit").val();	
	input = "<input id='unit"+Z+"' readonly type='text' value='"+value+"' placeholder='Unit'>";
	return input;	
}



function kains(Z){
	var input = "";
	var value = '';
	value = $("#JenisKain").val();		
	input = "<input id='kain___"+Z+"' type='text'  onKeyUp='HandleDetail(this)' placeholder='kain'>";
	return input;	
}

function colors(Z){
	var input = "";
	var value = '';
	value = $("#MyColor").val();		
	input = "<input id='color___"+Z+"' type='text'  onKeyUp='HandleDetail(this)' placeholder='color'>";
	return input;	
}
//jml_roll[1]
function jml_rolls(Z){
	var input = "";
	input = "<input id='jml_roll___"+Z+"' onkeyup='HandleDetail(this)' type='text'  value='' placeholder='Qty'>";
	return input;	
}
//addqty
function addqtys(Z){
	var input = "";
	input = "<input id='addqty___"+Z+"' onkeyup='HandleDetail(this)'   type='text' value='' placeholder='AddQty'>";
	return input;	
}
//barcode
function barcodes(Z){
	var input = "";
	input = "<input id='barcode___"+Z+"' onkeyup='HandleDetail(this)' type='text' value='' placeholder='barcode'>";
	return input;
}
//pxdet
function pxdets(Z){
	var input = "";
	input = "<input type='text' id='pxdet___"+Z+"' onkeyup='HandleDetail(this)'  value='' placeholder='Price'>";
	return input;	
}

function HandleDetail(Item){
	console.log(Item.id);
	
	var id = Item.id;
	var posisi = id.split("___");
	console.log(posisi);
	if(posisi[0] == "no_roll" ) {
		no_roll[posisi[1]] = Item.value;
		
	}else if(posisi[0] == "jml_roll"){
		jml_roll[posisi[1]] = Item.value;
		
	}else if(posisi[0] == "kain"){
		kain[posisi[1]] = Item.value;
		console.log(kain);
	}else if(posisi[0] == "color"){
		color[posisi[1]] = Item.value;
		
	}else if(posisi[0] == "pxdet"){
		pxdet[posisi[1]] = Item.value;	
	}
}



async function getListData(Item) {
no_roll = [];
jml_roll = [];
kain = [];
color = [];
pxdet = [];
	
$("#addNumberOfSize").dataTable().fnDestroy()
$("#render").empty();
  var td = "";
  for(var x=0;x<Item.value;x++){
	  no_roll.push('');
	  jml_roll.push('');
	  kain.push('');
	  color.push('');
	  pxdet.push('');
	  td += "<tr>";
	  td += "<td style='padding-left:0'>"+no(x)+"</td>";
	  td += "<td style='padding-left:0'>"+kains(x)+"</td>";
	  td += "<td style='padding-left:0'>"+colors(x)+"</td>";
	  td += "<td style='padding-left:0'>"+no_rolls(x)+"</td>";
	  td += "<td style='padding-left:0'>"+jml_rolls(x)+"</td>";
	 // td += "<td style='padding-left:0'>"+addqtys(x)+"</td>";
	  // td += "<td style='padding-left:0'>"+unit(x)+"</td>";
	//  td += "<td style='padding-left:0'>"+barcodes(x)+"</td>";
	//  td += "<td style='padding-left:0'>"+pxdets(x)+"</td>";
	//  td += "<td style='padding-left:0'>"+sku(x)+"</td>";
	  //td += "<td style='padding-left:0'>"+barcodes(x)+"</td>";
	//  td += "<td style='padding-left:0'>"+notes(x)+"</td>";
	  td += "</tr>";
	  
  }
  $("#render").append(td);
  try {
	  var table = await $('#addNumberOfSize').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        pageLength: 20,
		searching: false,
		filter:false,
		bPaginate:false,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });
    console.log(result);
  } catch (error) {
    console.log(error);
  }
}

/*
  function getListData(Item)
  {   
  
	  var url = window.location.href;
	  mysp = url.split('id=');
	  console.log(mysp);
	  var id_so = mysp[1] ;
      var cri_item = Item.value;
  // var html = $.ajax({  type: "POST",
  //      url: '../forms/ajax2.php?modeajax=view_list_size',
  //      data: {cri_item: cri_item, id_so: id_so},
  //       async: false
  //    }).responseText;
  //    if(html)
  //    {	 
  //        $("#detail_item").a(html);
  //    }
		    $.ajax({		
        type:"POST",
        cache:false,
        url: '../forms/ajax2.php?modeajax=view_list_size', 
        data: {cri_item: cri_item, id_so: id_so},
        success: function (response) {
			$("#detail_item").html(response);
        }
      });		  
	  
  }
*/

function deleteForm(){

$('#form_kain').empty();
$('#form_color').empty();
$('#form_size').empty();
$('#form_qty').empty();
$('#form_qtyadd').empty();
$('#form_unit').empty();
$('#form_price').empty();
$('#form_sku').empty();
$('#form_barcode').empty();
$('#form_notes').empty();	
$('#form_numberofsize').empty();	
$('#form_action').empty();						
}

function inputpicker(){
	$('#MyDeliveryDate').datepicker
    ({  
		setDate: new Date(),
		format: "dd M yyyy",
        autoclose: true
    });	
	
	
	
}

async function formss(type,id,idd){
	
	
	event.preventDefault();
	deleteForm();
	await generateform(type,id,idd);

	 var s = getId(type,id,idd);
	console.log(s);
	
	console.log(urlPost);
	await inputpicker();
	
}

function getUrl(Post){
	
	return Post;
	
}


function getId(type,id,idd){
	var url = window.location.href;
	type = 'add';
	//http://localhost/marketing/pages/marketting/?mod=7&id=30
	if(type == 'add'){  
		splitUrl = url.split("&id=");
		id = splitUrl[1];
		console.log(url);
	
		urlPost = 's_req_det_dev.php?mod=8&id='+id+'&idd=';
	}else if(type == 'edit'){
		urlPost = 's_sales_ord_dev.php?mod=8&id='+id+'&idd='+idd;
	}

	return urlPost;
	
	
	
}

function checkAddEditByUrl(id){
	var checkId =  id.split("&idd=");
	//console.log(checkId);
	if(checkId[1] == null ){

		return false
		
	}
	else{

		return true;
		
	}
	
	
	
	
}

function getEdit(type,ids,idds){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getDevelopmentDetailSalesOrder.php",
        data : { code : '1', idd: idds  },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
				d = JSON.parse(response);
				console.log(d.records);
				if(d.message == '1'){
					var dates = decodeURIComponent(d.records[0][0].deldate);
					$("#JenisKain").val(decodeURIComponent(d.records[0][0].kain));
					$("#MyColor").val(decodeURIComponent(d.records[0][0].color));
					$("#MyQty").val(decodeURIComponent(d.records[0][0].qty));
					$("#MyPrice").val(decodeURIComponent(d.records[0][0].price));
					$("#MySku").val(decodeURIComponent(d.records[0][0].sku));
					$("#MyBarcode").val(decodeURIComponent(d.records[0][0].barcode));
					$("#MyNotes").val(decodeURIComponent(d.records[0][0].notes));
					$("#MyDeliveryDate").datepicker("setDate", new Date(dates));
					return;

				}
				if(d.message == '2'){
					return;
				}
        }
      });	
	
}

async function generateform(type,id,idd){
		if(type == 'add'){  
			form_unit = "<input type='text' id='MyUnit' placeholder='Unit' autocomplete='off' class='form-control' style='width:100px' >";
			$('#form_unit').append(form_unit);
			form_numberofsize = "<select onchange='getListData(this)' id='NumberOfSize' placeholder='Number Of Size' class='form-control select2' style='width:70px'><option value='99'>--Number Of Size--</select> ";

			
			$('#form_numberofsize').append(form_numberofsize);	
			options = '';
			
			for(x=1;x<=100;x++){
				options +="<option value='"+x+"'>"+x+"</option>";
			}
			$("#NumberOfSize").append(options);
				form_action = "<a href='#' class='btn btn-primary' autocomplete='off' onclick='Save(\"Add\")' id='myAction'> Submit</button>";
				$('#form_action').append(form_action);	
			
			$("#MyUnit").val($('[name="txtunit"]').val());
		}
		else if(type == 'edit'){
			form_qty = "<input type='text' id='MyQty' placeholder='Qty' autocomplete='off' class='form-control' style='width:100px' >";
			$('#form_qty').append(form_qty);			
			form_price = "<input type='text' id='MyPrice' placeholder='Price' autocomplete='off' class='form-control' style='width:100px' >";
			$('#form_price').append(form_price);			
			form_action = "<a href='#' class='btn btn-primary' autocomplete='off' onclick='Save(\"Edit\")' id='myAction'> Submit</button>";
			$('#form_action').append(form_action);			
			await getEdit(type,id,idd);
			
			
		}
	
	form_kain = "<input type='text' id='JenisKain' onkeyup='handleSubDetail(this)' placeholder='Kain' autocomplete='off' class='form-control' style='width:100px' >";
	$('#form_kain').append(form_kain);
	
	form_color = "<input type='text' id='MyColor' onkeyup='handleSubDetail(this)' placeholder='Color' autocomplete='off' class='form-control' style='width:100px' >";
	$('#form_color').append(form_color);

	//form_size = "<input type='text' id='MySize' autocomplete='off' class='form-control' style='width:100px' >";
	//$('#form_size').append(form_size);

	//form_qty = "<input type='text' id='MyQty' placeholder='Qty' autocomplete='off' class='form-control' style='width:100px' >";
	//$('#form_qty').append(form_qty);

	form_qtyadd = "<input type='text' id='MyQtyAdd' placeholder='Qtyadd' autocomplete='off' class='form-control' style='width:100px' >";
	$('#form_qtyadd').append(form_qtyadd);

	//form_unit = "<input type='text' id='MyUnit' placeholder='Unit' autocomplete='off' class='form-control' style='width:100px' >";
	//$('#form_unit').append(form_unit);

	//form_price = "<input type='text' id='MyPrice' placeholder='Price' autocomplete='off' class='form-control' style='width:100px' >";
	//$('#form_price').append(form_price);

	form_sku = "<input type='text' id='MySku' onkeyup='handleSubDetail(this)' placeholder='SKU' autocomplete='off' class='form-control' style='width:100px' >";
	$('#form_sku').append(form_sku);

	form_barcode = "<input type='text' autocomplete='off' placeholder='Barcode' id='MyBarcode' class='form-control' style='width:100px' >";
	$('#form_barcode').append(form_barcode);

	form_notes = "<input type='text' id='MyNotes' onkeyup='handleSubDetail(this)' placeholder='Notes' autocomplete='off' class='form-control' style='width:100px' >";
	$('#form_notes').append(form_notes);	
	
	
	//form_numberofsize = "<select id='NumberOfSize' placeholder='Number Of Size' class='form-control' style='width:100px'><option>--Number Of Size--</select>";
	//$('#form_numberofsize').append(form_notes);	
	
	//form_action = "<a href='#' class='btn btn-primary' autocomplete='off' onclick='sendAdd()' id='myAction'> Submit</button>";
	//$('#form_action').append(form_action);		
	
	

}


function getDefaultData(){
	 event.preventDefault();
	console.log("Begin Post Data");
	    $.ajax({		
        type:"POST",
        cache:false,
        url: urlPost,
        data : { 	
					txtkain : $("#JenisKain").val(),
					txtcolor : $("#MyColor").val(),
					txtsku : $("#MySku").val(),
					txtbarcode : $("#MyBarcode").val(),
					txtnotes : $("#MyNotes").val(),
					txtunit : $("#MyUnit").val(),
					jml_roll : $("#MySize").val(),

					

				},     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
				d = JSON.parse(response);

        }
      });
}
