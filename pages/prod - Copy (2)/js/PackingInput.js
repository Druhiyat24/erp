$( document ).ready(function() {
	getListLine();
	getListProses();
	getListJo();
    $('#tanggalinput').datepicker
    ({  format: "dd M yyyy",
        autoclose: true
    });
	
	Ddata= {
		tanggalinput 	: '',
		notes			: '',
		line			: '',
		proses			: '',
		ws				: '',
	}
	item = [];
});



function save(){
	if(!item[0]){
		swal({ title: 'Tiada Data' })
		
	}
	
	
	
}

/* membuat select option */


function handlekeyup(myValue){
	if(myValue.id == "tanggalinput"){
		Ddata.tanggalinput = myValue.value
	}else if(myValue.id == "notes"){
		Ddata.notes = myValue.value
	}else if(myValue.id == "line"){
		Ddata.line = myValue.value
	}else if(myValue.id == "proses"){
		Ddata.proses = myValue.value
	}else if(myValue.id == "ws"){
		Ddata.ws = myValue.value
	}
	
	
}

	function getListJo(){
		$("#ws").empty();
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getListJo.php", 
        data : { code : '1'},     // multiple data sent using ajax
        success: function (response) {
			data = response;
				d = JSON.parse(data);
				//d = response;
				option  = '';
				renders = '';
				if(d.message == '1'){
					//	console.log(d.records);
						for(i=0;i<d.records.length;i++){
							option += "<option value="+decodeURIComponent(d.records[i].id)+">"+decodeURIComponent(d.records[i].id) +" "+decodeURIComponent(d.records[i].nama)+"</option>";
						}//department
						$("#ws").append(option);
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
}

	function getListProses(){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getListProses.php", 
        data : { code : '1'},     // multiple data sent using ajax
        success: function (response) {
			data = response;
				d = JSON.parse(data);
				//d = response;
				option  = '';
				renders = '';
				if(d.message == '1'){
					//	console.log(d.records);
						for(i=0;i<d.records.length;i++){
							option += "<option value="+decodeURIComponent(d.records[i].id)+">"+decodeURIComponent(d.records[i].id) +" "+decodeURIComponent(d.records[i].nama)+"</option>";
						}//department
						$("#proses").append(option);
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
}	

	function getListLine(){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getListLine.php", 
        data : { code : '1'},     // multiple data sent using ajax
        success: function (response) {
			data = response;
				d = JSON.parse(data);
				//d = response;
				option  = '';
				renders = '';
				if(d.message == '1'){
					//	console.log(d.records);
						for(i=0;i<d.records.length;i++){
							option += "<option value="+decodeURIComponent(d.records[i].id)+">"+decodeURIComponent(d.records[i].id) +" "+decodeURIComponent(d.records[i].nama)+"</option>";
						}//department
						$("#line").append(option);

				setTimeout(function(){ 				

				}, 3000);						
						
						
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
}	


function getDetail(myValue){
	console.log(Ddata);
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getDetailPackingInput.php", 
        data : { code : '1',id_jo:Ddata.ws,id_proses:Ddata.proses},     // multiple data sent using ajax
        success: function (response) {
			data = response;
				d = JSON.parse(data);
				//d = response;
				option  = '';
				renders = '';
				if(d.message == '1'){		
						rendertableDetail(d.records)
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
}	
	
	



function rendertableDetail(detail){
	item = [];
	item = detail;
	$("#myDetail").dataTable().fnDestroy();
	tableDetail();
	var td = "";
	for(var i=0;i<detail.length;i++){
		td +="<tr>";
			td += "<td>";
				td += decodeURIComponent(detail[i].no);
			td += "</td>";


			td += "<td>";
				td += decodeURIComponent(detail[i].supplier);
			td += "</td>";
			
			td += "<td>";
				td += decodeURIComponent(detail[i].styleno);
			td += "</td>";

			td += "<td>";
				td += decodeURIComponent(detail[i].kpno);
			td += "</td>";

			td += "<td>";
				td += decodeURIComponent(detail[i].so_no);
			td += "</td>";

			td += "<td>";
				td += decodeURIComponent(detail[i].buyerno);
			td += "</td>";

			td += "<td>";
				td += decodeURIComponent(detail[i].dest);
			td += "</td>";

			td += "<td>";
				td += decodeURIComponent(detail[i].color);
			td += "</td>";		
	
			td += "<td>";
				td += decodeURIComponent(detail[i].size);
			td += "</td>";	

			td += "<td>";
				td += decodeURIComponent(detail[i].dateinput);
			td += "</td>";	

			td += "<td>";
				td += decodeURIComponent(detail[i].dateoutput);
			td += "</td>";	

			td += "<td>";
				td += decodeURIComponent(detail[i].qtyout);
			td += "</td>";	

			td += "<td>";
				td += decodeURIComponent(detail[i].process);
			td += "</td>";			
		td +="</tr>";



	}//department	
	pasangDataTable();
}


async function pasangDataTable(){
	await $('#myDetail').DataTable
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

async function tableDetail(){
	var td = '';
td +="	  	<table id='myDetail' class='display responsive' style='width:100%;font-size:12px;'> ";
td +="      <thead> 																			";
td +="        <tr>  																			";
td +="	    	<th>No</th>            															";
td +="	    	<th>Buyer</th>            														";
td +="	    	<th>Style#</th>            														";
td +="	    	<th>WS#</th>            														";
td +="			<th>BuyerPO</th>	    														";
td +="	    	<th>Dest</th>           														";
td +="          <th>Color</th>            														";
td +="			<th>Size</th>             														";
td +="          <th >Qty SO</th>            													";
td +="          <th >Unit SO</th>            													";
td +="          <th >Bal SO</th>            													";
td +="          <th >Qty Output</th>          													";
td +="          <th >Proses</th>          													";
td +="		</tr>                                       ";
td +="      </thead>                                    ";
td +="      <tbody id='render'>                    ";
td +="      </tbody>                                    ";
td +="    </table>	                                    ";
$("#detail_item").empty();
	$("#detail_item").append(td);



	
}

/* membuat select option */