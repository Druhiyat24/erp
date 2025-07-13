$( document ).ready(function() {
localStorage.clear();
sessionStorage.clear();


	data = {
		typepencarian : '',
		periode    : '',
		numberjournal  : '',
		datefrom : '',
		dateto : '',
	}
//getLisData();
generatePeriode();
generateAccountPiutang();
defaultshow();
//getListData();
});

data = {
	iddatefrom : '',
	iddateto : '',
}
function initial(){
	data.iddatefrom = $('#period_from').val();
	data.iddateto = $('#period_to').val();
}
function generatePeriode(){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getMasterPeriode.php",
        data : { code : '1'  },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
				d = JSON.parse(response);
				console.log(d.records);
				if(d.message == '1'){
					 for(x=0;x<d.records.periodeDate.length;x++){
						 console.log('123');
					$('#period_from').append('<option value="01/'+d.records.periodeDate[x]+'" >'+d.records.periodeDate[x]+'</option>');
					$('#period_to').append('<option value="01/'+d.records.periodeDate[x]+'" >'+d.records.periodeDate[x]+'</option>');						 
					 }


				}
				if(d.message == '2'){
			
				}
        }
      });	
	
	
}

function generateAccountPiutang(){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getMasterAccountPiutang.php",
        data : { code : '1'  },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
				d = JSON.parse(response);
				console.log(d.records);
				if(d.message == '1'){
					 for(x=0;x<d.records.Id_Supplier.length;x++){
						 console.log('123');
					$('#account').append('<option value="'+d.records.Id_Supplier[x]+'" >'+d.records.Supplier[x]+'</option>');						 
					 }
					//console.log(data);
					$('#content_nama').trigger('change');
					$('#footer_personpt').trigger('change');
					$('#myOverlay').css('display','none');	
				}
				if(d.message == '2'){
				}
        }
      });	
}
function getChild(X,Z,Y){
	console.log(Y.className);
	id = StoreIdSupplier[X];
	//balance = StoreOpening[X]
	$("#"+X).toggleClass('fa-plus fa-minus');
	var Myclass =  Y.className
	var Mysplit = Myclass.split(" ");
	console.log(Mysplit);
		console.log(Mysplit);
	if(Mysplit[1] == 'fa-minus' ){
		console.log('MINUS');
		$("."+id).remove();
		$("#"+Z).toggleClass('fa-plus fa-minus');
	}
	if(Mysplit[1] == 'fa-plus'){
		console.log("PERIOD:"+fixfrom);
		console.log("MASUK");
				d = JSON.parse(response);
				console.log(d.records);

				var trChild = '';
				if(d.message == '1'){

						td += '<tr class="Group'+decodeURIComponent(StoreIdSupplier[x])+'">';
						td += '<td>';
						td += ' ';
						td += '</td>';						
						td += '<td>';
						td += ' ';
						td += '</td>';
		

					
		
						td += '<td>';
						td += ' ';
						td += '</td>';	

						td += '<td>';
						td += ' ';
						td += '</td>';	


						td += '<td>';//StoreTotal
						td += 'Jatuh Tempo';
						td += '</td>';						
	

						td += '<td>';
						td += ' ';
						td += '</td>';
	
						td += '<td>';
							td += decodeURIComponent(StoreTotal2[x]);
						td += '</td>';

						td += '<td>';
						td += ' ';
						td += '</td>';

						td += '<td>';
						td += ' ';
						td += '</td>';					
		
						td += '<td>';
						td += ' ';
						td += '</td>';
		
						td += '<td>';
						td += ' ';
						td += '</td>';	

						td += '<td>';
						td += ' ';
						td += '</td>';				

						td += '<td>';
						td += ' ';
						td += '</td>';

						td += '<td>';
							td += ' ';
						td += '</td>';
						
						td += '</tr>';
					 
		


						td += '<tr class="Group'+decodeURIComponent(StoreIdSupplier[x])+'">';
						td += '<td>';
						td += ' ';
						td += '</td>';						
						td += '<td>';
						td += ' ';
						td += '</td>';
		

					
		
						td += '<td>';
						td += ' ';
						td += '</td>';	

						td += '<td>';
						td += ' ';
						td += '</td>';	


						td += '<td>';//StoreTotal
						td += 'Selisih';
						td += '</td>';						
	

						td += '<td>';
						td += ' ';
						td += '</td>';
	
						td += '<td>';
							td += decodeURIComponent(StoreTotal3[x]);
						td += '</td>';

						td += '<td>';
						td += ' ';
						td += '</td>';

						td += '<td>';
						td += ' ';
						td += '</td>';					
		
						td += '<td>';
						td += ' ';
						td += '</td>';
		
						td += '<td>';
						td += ' ';
						td += '</td>';	

						td += '<td>';
						td += ' ';
						td += '</td>';				

						td += '<td>';
						td += ' ';
						td += '</td>';

						td += '<td>';
							td += ' ';
						td += '</td>';
						
						td += '</tr>';
		
					 console.log(X);
					//$("#Group"+Z).append(trChild);
					 //$parentTR = $("#Group"+Z).closest('tr');
					  $(trChild).insertAfter($("#Group"+Z).closest('tr'));
					console.log(trChild);
					$("#example1").DataTable();
					$('#myOverlay').css('display','none');
					$("#example1").DataTable();
					$(".odd").css('display','none');
					$("#"+Z).toggleClass('fa-plus fa-minus');
					
				}
				if(d.message == '2'){
					alert("Belum ada Data untuk Part ini !");
					return false;
			
				}	
		
		
	}
		
	
	
	
	
}





function Show(Item){
	/*
			typepencarian : '',
		dateform   : '',
		dateto
		numberjournal  : '',
	
	
	
	*/
	if(Item == '1'){
		// A : All
		data.typepencarian = 'A';
		$(".type").css('display','none');
		$(".all").css('display','block');
		$(".boxPencarian").css('display','block');
		$(".account").css('display','none');

	}
	if(Item == '2'){
		console.log(Item);
		//P : search by account(partial)
		data.pencarian = 'P';
		$(".type").css('display','none');
		$(".partial").css('display','block');
		$(".boxPencarian").css('display','block');
		$(".account").css('display','block');
		
	}	
		//getListData();
		
	}
function back(){
	location.reload();
	
	
}	
	

function defaultshow(){
	$(".type").css('display','block');
	$(".partial").css('display','none');
	$(".all").css('display','none');
	$(".list").css('display','none');
	$(".boxPencarian").css('display','none');
	$(".account").css('display','none');
}
	
		function getAccountPeriode(X,dst){
			
			if(dst == 'from'){
				console.log(X.value);
				data.datefrom = X.value;
			}
			if(dst == 'to'){
				console.log(X.value);
				data.dateto = X.value;	
			}
			if(dst == 'acc'){
				data.numberjournal = X.value;
			}
		}
	function getListData(){
		$("#bodyexamle1").children().remove();
		if(data.pencarian == 'P'){
			if(data.numberjournal == '' ||  data.numberjournal == undefined){
				alert("Account Harus diisi");
				return false;
			}
		}
		console.log("FROM:"+data.datefrom);
		if(data.datefrom == '' || data.datefrom == undefined){
			alert("Periode From Harus Diisi");
			return false;
		}

		if(data.dateto == '' || data.dateto == undefined){
			alert("Periode To Harus Diisi");
			return false;
		}		

	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getListPiutang.php", 
        data : { code : '1', data:data  },     // multiple data sent using ajax
        success: function (response) {
			console.log(response);
				d = JSON.parse(response);
				console.log(d.records);
				StoreArday       = d.records['aktual']['arday'];
				StoreBulanBerjalan       = d.records['aktual']['bulan_berjalan'];
				StoreTotal       = d.records['aktual']['total'];
				StoreTotal2      = d.records['aktual']['total2'];
				StoreTotal3      = d.records['aktual']['total3'];
				StoreSatu        = d.records['aktual']['m1'];
				StoreDua         = d.records['aktual']['m2'];
				StoreTiga        = d.records['aktual']['m3'];
				StoreEmpat       = d.records['aktual']['m4'];
				StoreLima        = d.records['aktual']['m5'];
				StoreEnam        = d.records['aktual']['m6'];
				StoreIdSupplier  = d.records['idsupplier'];
				StoreNamaSupplier= d.records['namasupplier'];
				td  = '';
				renders = '';
				console.log()
				if(d.message == '1'){
					fixfrom = data.datefrom;
					fixto = data.dateto;
					$(".list").css('display','block');
					 for(x=0;x<StoreIdSupplier.length;x++){
						td += '<tr id="Group'+decodeURIComponent(StoreIdSupplier[x])+'">';
						td += '<td>';
						td += '<i class="fa fa-plus" style="cursor:pointer" id="'+decodeURIComponent(StoreIdSupplier[x])+'" onclick="getChild('+decodeURIComponent(x)+','+StoreIdSupplier[x]+',this)"> </i>';
						td += '</td>';						
						td += '<td>';
						td += decodeURIComponent(StoreIdSupplier[x]);
						td += '</td>';
		

					
		
						td += '<td>';
						td += decodeURIComponent(StoreNamaSupplier[x]);
						td += '</td>';	

						td += '<td>';
						td += ' ';
						td += '</td>';	


						td += '<td>';//StoreTotal
						td += 'Aktual';
						td += '</td>';						


						td += '<td>';
							td += decodeURIComponent(StoreTotal[x]);
						td += '</td>';	

						td += '<td>';
							if(StoreEnam[x] == null){
									StoreEnam[x] = 0;
								
								
							}
							td += StoreEnam[x];
						td += '</td>';
	


						td += '<td>';
							if(StoreLima[x] == null){
									StoreLima[x] = 0;
								
								
							}
												
							td += decodeURIComponent(StoreLima[x]);
						td += '</td>';
							if(StoreEmpat[x] == null){
									StoreEmpat[x] = 0;
								
								
							}
						
						td += '<td>';
							td += decodeURIComponent(StoreEmpat[x]);
						td += '</td>';					
							if(StoreTiga[x] == null){
									StoreTiga[x] = 0;
								
								
							}		
						td += '<td>';
							td += (decodeURIComponent(StoreTiga[x]));
						td += '</td>';
							if(StoreDua[x] == null){
									StoreDua[x] = 0;
								
								
							}			
						td += '<td>';
							td += decodeURIComponent(StoreDua[x]);
						td += '</td>';	
							if(StoreSatu[x] == null){
									StoreSatu[x] = 0;
								
								
							}	
						td += '<td>';
							td += decodeURIComponent(StoreSatu[x]);
						td += '</td>';				

						td += '<td>';
							td += decodeURIComponent(StoreBulanBerjalan[x]);
						td += '</td>';

						td += '<td>';
							td += ' ';
						td += '</td>';
						
						td += '</tr>';
						
						
						td += '<tr class="Group'+decodeURIComponent(StoreIdSupplier[x])+'">';
						td += '<td>';
						td += ' ';
						td += '</td>';						
						td += '<td>';
						td += ' ';
						td += '</td>';
		

					
		
						td += '<td>';
						td += ' ';
						td += '</td>';	

						td += '<td>';
						td += ' ';
						td += '</td>';	


						td += '<td>';//StoreTotal
						td += 'Jatuh Tempo';
						td += '</td>';						
	
						td += '<td>';
							td += decodeURIComponent(StoreTotal2[x]);
						td += '</td>';
						
						td += '<td>';
						td += ' ';
						td += '</td>';
	


						td += '<td>';
						td += ' ';
						td += '</td>';

						td += '<td>';
						td += ' ';
						td += '</td>';					
		
						td += '<td>';
						td += ' ';
						td += '</td>';
		
						td += '<td>';
						td += ' ';
						td += '</td>';	

						td += '<td>';
						td += ' ';
						td += '</td>';				

						td += '<td>';
						td += ' ';
						td += '</td>';

						td += '<td>';
							td += ' ';
						td += '</td>';
						
						td += '</tr>';
					 
		


						td += '<tr class="Group'+decodeURIComponent(StoreIdSupplier[x])+'">';
						td += '<td>';
						td += ' ';
						td += '</td>';						
						td += '<td>';
						td += ' ';
						td += '</td>';
		

					
		
						td += '<td>';
						td += ' ';
						td += '</td>';	

						td += '<td>';
						td += ' ';
						td += '</td>';	


						td += '<td>';//StoreTotal
						td += 'Selisih';
						td += '</td>';						
						td += '<td>';
							td += decodeURIComponent(StoreTotal3[x]);
						td += '</td>';	

						td += '<td>';
						td += ' ';
						td += '</td>';
	


						td += '<td>';
						td += ' ';
						td += '</td>';

						td += '<td>';
						td += ' ';
						td += '</td>';					
		
						td += '<td>';
						td += ' ';
						td += '</td>';
		
						td += '<td>';
						td += ' ';
						td += '</td>';	

						td += '<td>';
						td += ' ';
						td += '</td>';				

						td += '<td>';
						td += ' ';
						td += '</td>';

						td += '<td>';
							td += ' ';
						td += '</td>';
						
						td += '</tr>';						
						
						
						
					 }

					console.log(renders);
					renders = td;
					$('#bodyexamle1').append(renders);
					$("#example1").DataTable();
					$('#myOverlay').css('display','none');
					$("#example1").DataTable();
					$(".odd").css('display','none');
				}
				if(d.message == '2'){
					alert("Maaf Belum Ada Data untuk account nomor ini !")
				}
        }
      });	
	
	
}


