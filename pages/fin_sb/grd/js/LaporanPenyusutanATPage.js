$( document ).ready(function() {

//getListData();


    $("#fromdate").datepicker( {
      format: "mm/yyyy",
      viewMode: "months",
      minViewMode: "months",
      autoclose: true
    });
    $("#todate").datepicker( {
      format: "mm/yyyy",
      viewMode: "months",
      minViewMode: "months",
      autoclose: true
    });
});


function getLaporan() {

	froms = $("#fromdate").val();
	tos   = $("#todate").val();
	kd_tipe_activas   = $("#kd_tipe_activa").val();
    $("#loading").css("display", "block");
    $("#search").css("display", "none");
	if(froms == '') {alert("From date harus diisi");return false}
	if(tos  == '')  {alert("To date harus diisi");return false}
	getListData();

	  setTimeout(function(){     
	  
	  var table = $('#examplefix1010').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        pageLength: 20,
        dom: 'Bfrtip',
        buttons: [
                'excel'
        ],
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });
		function edit(){  
		alert(data);
	}
	}, 3000);	
	
}
	function getListData(){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getListLaporanPenyusutanAT.php", 
        data : { code : '1',  from: froms, to :tos ,kd_tipe_activa: kd_tipe_activas},     // multiple data sent using ajax
        success: function (response) {
			console.log(response);
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				//d = response;
				td  = '';
				renders = '';
				console.log(d.records.length);
				if(d.message == '1'){
                    var froms = $("#fromdate").val();
                    var tos   = $("#todate").val();

                    $('#label_from').html(froms);
                    $('#label_to').html(tos);

					$("#render").append(data[1]);
					setTimeout(function(){
						//console.log(Ddata);
						$("#uijurnal").css("display","block");
						$("#backs").css("display","block");
						$("#abcd").css("display","none");
					}, 3000);
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	
	
}

function back(){
	 location.reload();
	
}
