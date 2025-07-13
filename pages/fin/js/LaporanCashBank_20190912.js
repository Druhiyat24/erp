$( document ).ready(function() {
getAkunCashBank();
//getListData();
	  var table = $('#examplefix1010').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });

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
CashBankId = '';
function getidcashbank(idcoa){
	CashBankId = idcoa;
	//alert(CashBankId);
}

	function getAkunCashBank(){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getAkunCashBank.php", 
        data : { code : '1', type:"Laporan"  },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				//d = response;
				option  = '';
				renders = '';
			//	console.log(d.records.length);
				if(d.message == '1'){
					//	$("#render").append(data[1]);
					//	console.log(d.records);
						for(i=0;i<d.records.length;i++){
							option += "<option value="+decodeURIComponent(d.records[i][0].id)+">"+decodeURIComponent(d.records[i][0].id) +" "+decodeURIComponent(d.records[i][0].nama)+"</option>";
						}//department
						$("#idcoa").append(option);

				setTimeout(function(){ 				

				}, 3000);						
						
						
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	
	
}	
	


function getLaporan() {
	froms = $("#fromdate").val();
	tos   = $("#todate").val();
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

function getChild(Item) {
	//alert(Item);
	console.log(Item);
   classs = $("#" + Item).attr('class');
    console.log(classs);
    var Mysplit = classs.split(" ");
    if (Mysplit[1] == 'fa-minus') {
        console.log('MINUS');
        $("." + Item).remove();
        $("#" + Item).toggleClass('fa-plus fa-minus');
        return false;
    }
    $("#" + Item).toggleClass('fa-plus fa-minus');
    $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getChildCashBank.php",
        data: { code: '1', from: froms, to: tos,akun:Item,idcashbank : CashBankId  },     // multiple data sent using ajax
        success: function (response) {
            console.log(response);
            data = response.split("<-|->");
            d = JSON.parse(data[0]);
            //d = response;
            td = '';
            renders = '';
            console.log(d.records.length);
            if (d.message == '1') {
               // $("#Group" + Item).append(data[1]);
                $(data[1]).insertAfter($("#Group" + Item).closest('tr'));
                setTimeout(function () {

                    //console.log(Ddata);	
                    $("#uijurnal").css("display", "block");
                    $("#backs").css("display", "block");
                    $("#abcd").css("display", "none");

                }, 3000);


            }
            if (d.message == '2') {
                alert("Input Tanggal Salah !")
            }
        }
    });	
	
	
}

	function getListData(){
	    $.ajax({		
        type:"POST",
        cache:false,
		 async: false,
        url:"webservices/getListLaporanCashBank.php", 
        data : { code : '1',  from: froms, to :tos,idcashbank : CashBankId },     // multiple data sent using ajax
        success: function (response) {
			console.log(response);
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				//d = response;
				td  = '';
				renders = '';
				Induk = d.records[0];
				console.log(d.records[0]);
				if(d.message == '1'){
						$("#render").append(data[1]);
						
				setTimeout(function(){ 				
				
				//console.log(Ddata);	
				$("#uijurnal").css("display","block");
				$("#backs").css("display","block");
				$("#abcd").css("display","none");
				//console.log(CashBankId);
				if(CashBankId != ''){
					tempid = "Group"+Induk;
					console.log(tempid);
					getChild(Induk);
					
				}
				
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
