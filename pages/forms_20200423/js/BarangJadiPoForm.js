$( document ).ready(function() {

});

function getPoBySupplier($that){
	$populasi_po = "";
	$populasi_po += "<option value='XX'>--Pilih Po--</option>";	
	$("#cboPO").empty();
	CallServicesPoBySupplier($that).then(function(responnya) {
		for(var i=0;i<responnya.records.length;i++){
			$populasi_po += "<option value='"+decodeURIComponent(responnya.records[i].id)+"'>"+decodeURIComponent(responnya.records[i].pono)+"</option>";
			
		}
		$("#cboPO").append($populasi_po);
	});
}
function CallServicesPoBySupplier($that){
		return new Promise(function(resolve, reject) {
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getListPoBySupplier.php",
        data: { code: '1', id_supplier: $that},     // multiple data sent using ajax
        success: function (response) {
            d = JSON.parse(response);
			if(d.status == 'ok'){
            if (d.message == '1') {
				resolve(d);
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






  function getJO_po($that)

  { var id_po = $that;
    var html = $.ajax

    ({  type: "POST",

        url: 'ajax_bpb_so_po.php?modeajax=view_list_jo',

        data: {id_po: id_po}, 

        async: false

    }).responseText;

    if(html)

    {  

        $("#detail_item").html(html);

    }

    $(document).ready(function() {

      var table = $('#examplefix2').DataTable

      ({  scrollCollapse: true,

          paging: false,

          fixedColumns:   

          { leftColumns: 1,

            rightColumns: 1

          }

      });

    });

  };$( document ).ready(function() {

});

function getPoBySupplier($that){
	$populasi_po = "";
	$populasi_po += "<option value='XX'>--Pilih Po--</option>";	
	$("#cboPO").empty();
	CallServicesPoBySupplier($that).then(function(responnya) {
		for(var i=0;i<responnya.records.length;i++){
			$populasi_po += "<option value='"+decodeURIComponent(responnya.records[i].id)+"'>"+decodeURIComponent(responnya.records[i].pono)+"</option>";
			
		}
		$("#cboPO").append($populasi_po);
	});
	
	
}
function CallServicesPoBySupplier($that){
		return new Promise(function(resolve, reject) {
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getListPoBySupplier.php",
        data: { code: '1', id_supplier: $that},     // multiple data sent using ajax
        success: function (response) {
            d = JSON.parse(response);
			if(d.status == 'ok'){
            if (d.message == '1') {
				resolve(d);
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






  function getJO($that)

  { var id_po = $that;
	//alert(id_po);

    var html = $.ajax

    ({  type: "POST",

        url: 'ajax_bpb_so_po.php?modeajax=view_list_jo',

        data: {id_po: id_po}, 

        async: false

    }).responseText;

    if(html)

    {  

        $("#detail_item").html(html);

    }

    $(document).ready(function() {

      var table = $('#examplefix2').DataTable

      ({  scrollCollapse: true,

          paging: false,

          fixedColumns:   

          { leftColumns: 1,

            rightColumns: 1

          }

      });

    });

  };