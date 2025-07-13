$( document ).ready(function() {
	urlPost = '';

});

function formss(type){
	 event.preventDefault();
	generateform();
	if(type == 'edit'){
		
		
	}
	 getId();
	
	
	console.log(urlPost);
	
    $('#MyDeliveryDate').datepicker
    ({  format: "dd M yyyy",
        autoclose: true
    });		
}

function getUrl(Post){
	
	return Post;
	
}


function getId(){
	var url = window.location.href;
	//http://localhost/marketing/pages/marketting/?mod=7&id=30
	splitUrl = url.split("&id=");
	id = splitUrl[1];
	if(checkAddEditByUrl(id)){
		splitidd =  id.split("&idd=");
		idd = splitidd[1];
		id =  splitidd[0];
		urlPost = 's_sales_ord_det.php?mod=8&id='+id+'&idd='+idd;

	}else{
		urlPost = 's_sales_ord_det.php?mod=8&id='+id+'&idd=';

	}
	//return id;
	
	
	
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

function generateform(){
	

	
	form_delivarydate = "<input type='text' id='MyDeliveryDate' class='form-control' style='width:100px'>";
	$('#form_deliverydate').append(form_delivarydate);

	form_destination = "<input type='text' id='MyDestination' class='form-control' style='width:100px' >";
	$('#form_destination').append(form_destination);
	
	form_color = "<input type='text' id='MyColor' class='form-control' style='width:100px' >";
	$('#form_color').append(form_color);

	form_size = "<input type='text' id='MySize' class='form-control' style='width:100px' >";
	$('#form_size').append(form_size);

	form_qty = "<input type='text' id='MyQty' class='form-control' style='width:100px' >";
	$('#form_qty').append(form_qty);

	form_qtyadd = "<input type='text' id='MyQtyAdd' class='form-control' style='width:100px' >";
	$('#form_qtyadd').append(form_qtyadd);

	form_unit = "<input type='text' id='MyUnit' class='form-control' style='width:100px' >";
	$('#form_unit').append(form_unit);

	form_price = "<input type='text' id='MyPrice' class='form-control' style='width:100px' >";
	$('#form_price').append(form_price);

	form_sku = "<input type='text' id='MySku' class='form-control' style='width:100px' >";
	$('#form_sku').append(form_sku);

	form_barcode = "<input type='text' id='MyBarcode' class='form-control' style='width:100px' >";
	$('#form_barcode').append(form_barcode);

	form_notes = "<input type='text' id='MyNotes' class='form-control' style='width:100px' >";
	$('#form_notes').append(form_notes);	
	
	form_action = "<a href='#' class='btn btn-primary' onclick='getDefaultData()' id='myAction'> Submit</button>";
	$('#form_action').append(form_action);		
	
	

}


function getDefaultData(){
	 event.preventDefault();
	console.log("Begin Post Data");
	    $.ajax({		
        type:"POST",
        cache:false,
        url: urlPost,
        data : { 
					txtdeldate : $("#MyDeliveryDate").val(),
					txtdest : $("#MyDestination").val(),
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
