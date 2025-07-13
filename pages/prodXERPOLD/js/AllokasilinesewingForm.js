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
		ws				: '',
	}
	item = [];
});
function save(){
	if(Ddata.tanggalinput == ''){
		alert("Tanggal Input Harus diisi");
		return false;
	}else if(Ddata.notes == ''){
		alert("Notes Harus diisi");
		return false;
	}else if(Ddata.ws == ''){
		alert("WS Harus diisi");
		return false;
	}
	for(var q=0;q < item.length;q++){
		if(item[q].qty__in == ''){
			alert("Quantity Cutting input Baris ke "+i+" Harus diinput");
			return false;
		}else{
			if(parseFloat(item[q].qty__in) >  parseFloat(item[q].stock_cutin)){
				alert("Jumlah Qty IN Tidak Valid!");
				return false;
			}
		}
	}
	Ddata.detail = [];
	Ddata.detail = item;
	
	
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/saveCuttingInput.php", 
        data : { code : '1', data:Ddata},     // multiple data sent using ajax
        success: function (response) {
			data = response;
				d = JSON.parse(data);
				//d = response;
				options  = '';
				renders = '';
				if(d.message == '1'){
					//	console.log(d.records);
							alert("Data berhasil Disimpan")
						window.location.href = "?mod=CuttingInputPage";
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	

}
/* membuat select option */
async function handlekeyup(myValue){
	
	var tmpid = myValue.id.split("__");
	if(tmpid[1]){
		item[tmpid[1]].qty__in = myValue.value;
	}
else{
	
		
	if(myValue.id == "datepicker4"){

		Ddata.tanggalinput = myValue.value
	}else if(myValue.id == "notes"){
		Ddata.notes = myValue.value
	}else if(myValue.id == "line"){
		Ddata.line = myValue.value
	}else if(myValue.id == "proses"){
		Ddata.proses = myValue.value
	}else if(myValue.id == "ws"){
		
		//Ddata.ws = myValue.value;
		await getDetail(myValue.value);
	}	
	
	// console.log(Ddata);
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
				options  = '';
				renders = '';
				if(d.message == '1'){
				options = "<option >--Pilih WS--</option>";
					//	console.log(d.records);

						for(i=0;i<d.records.length;i++){
				options += "<option value='"+decodeURIComponent(d.records[i].id)+"'>"+decodeURIComponent(d.records[i].id) +" "+decodeURIComponent(d.records[i].nama)+"</option>";
						}//department
						// console.log(options)
						$("#ws").append(options);
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
	console.log(myValue);
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getDetailAllokasilinesewing.php", 
        data : { code : '1',id:myValue},     // multiple data sent using ajax
        success: function (response) {
        	console.log(response);
			// data = response;
			// 	d = JSON.parse(data);
			// 	d.records = DecodeURIComponents(d.records);
			// 	//d = response;
			// 	option  = '';
			// 	renders = '';
			// 	if(d.message == '1'){		
			// 	if(d.records[0].ws){

			// 		// console.log("==================");

			// 		// console.log(d.records[0].ws);
			// 		Ddata.ws =decodeURIComponent(d.records[0].ws);

			// 		// console.log("XXXXXXXXXXXXXX");
			// 		// console.log(d.records);
			// 		rendertableDetail(d.records);	
			// 			  // console.log(Ddata);
					
			// 	}
			// 	else{
			// 		alert("Belum Ada Data");
			// 	}
			// 	}
			// 	if(d.message == '2'){
			// 		alert("Input Tanggal Salah !")
			// 	}
        }
      });	

}	
	
	



	
async function rendertableDetail(detail){
	item = [];
	item = detail;
	// console.log(detail[0]);
	$("#myDetail").dataTable().fnDestroy();
	tableDetail();
	var td = "";
	no = 0;
	for(var i=0;i<detail.length;i++){
		no = 0;
		td +="<tr>";
			td += "<td>";
				td += detail[i].ws;
			td += "</td>";


			td += "<td>";
				td += detail[i].styleno;
			td += "</td>";
			
			td += "<td>";
				td += detail[i].buyer;
			td += "</td>";

			td += "<td>";
				td += detail[i].goods_code;
			td += "</td>";

			td += "<td>";
				td += detail[i].description;
			td += "</td>";

			td += "<td>";
				td += detail[i].norak;
			td += "</td>";

			td += "<td>";
				td += detail[i].stock_cutin;
			td += "</td>";

			td += "<td>";
				td += detail[i].unit;
			td += "</td>";		
			
			td += "<td>";
				td += "<input type='text' value='"+detail[i].stock_cutin+"' onkeyup='handlekeyup(this)' placeholder='Input Qty Input' id=qtyinput__"+no+">";
			td += "</td>";					
	/*
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
*/			
		td +="</tr>";
		// console.log(td);
		$("#render").append(td);
	}//department	
	pasangDataTable();
}

function getListQtyCuttingInput(){}

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
td +="	    	<th>WS#</th>            															";
td +="	    	<th>Style</th>            														";
td +="	    	<th>Buyer</th>            														";
td +="	    	<th>Kode Bahan Baku#</th>            														";
td +="	    	<th>Deskripsi</th>            														";
td +="			<th>No.Rak</th>	    														";
td +="	    	<th>Stock Cutting In</th>           														";
td +="          <th>Unit</th>            														";
td +="			<th>Qty Input</th>             														";
td +="		</tr>                                       ";
td +="      </thead>                                    ";
td +="      <tbody id='render'>                    ";
td +="      </tbody>                                    ";
td +="    </table>	                                    ";

$("#detail_item").empty();
	$("#detail_item").append(td);



	
}


function DecodeURIComponents(data){
        for(var i=0, length=data.length;i<length;i++) {
            for ( var temp in data[i] ) {
                data[i][temp] = decodeURIComponent(data[i][temp]);
            } 
        }
        return data;
    }

/* membuat select option */