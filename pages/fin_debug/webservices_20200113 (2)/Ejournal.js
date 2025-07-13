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
generateAccountJournal();
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

function generateAccountJournal(){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getMasterNumberJournal.php",
        data : { code : '1'  },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
				d = JSON.parse(response);
				console.log(d.records);
				if(d.message == '1'){
					 for(x=0;x<d.records.numberjournal.length;x++){
						 console.log('123');
					$('#account').append('<option value="'+d.records.id[x]+'" >'+d.records.numberjournal[x]+'</option>');						 
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
	id = StoreNumJournal[X];
	balance = StoreOpening[X]
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
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getListSubEjournal.php",
	
        data : { id : id, balance : balance,code:'1',from:fixfrom,to:fixto  },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
				d = JSON.parse(response);
				console.log(d.records);
				var StoreChildSegment     = d.records.segment;
				var StoreChildNamaCoa     = d.records.nm_coa;
				var StoreChildIdCoa       = d.records.id_coa;
				var StoreChildChildOpening= d.records.openingbalance;
				var StoreChildIdJournal   = d.records.id_journal;
				var StoreChildDatePost    = d.records.date_post;
				var StoreChildTypeJournal = d.records.type_journal;
				var StoreChildNumJournal  = d.records.num_journal;
				var StoreChildCredit      = d.records.credit;
				var StoreChildDescription = d.records.description;
				var StoreChildDebit       = d.records.debit;
				var StoreChildSaldoAkhir       = d.records.saldoakhir;
				var trChild = '';
				if(d.message == '1'){
					 for(x=0;x<d.totaldata;x++){
						trChild += '<tr class="'+id+'">';
						trChild += '<td>';
						trChild += ' ';
						trChild += '</td>';
						
						
						trChild += '<td>';
						trChild += decodeURIComponent(StoreChildNumJournal[x]);
						trChild += '</td>';						
						
						
						trChild += '<td>';
						trChild += decodeURIComponent(StoreChildIdCoa[x]);
						trChild += '</td>';							
						
						
						trChild += '<td>';
						trChild += decodeURIComponent(StoreChildNamaCoa[x]);
						trChild += '</td>';		
	

						trChild += '<td>';
						trChild += decodeURIComponent(StoreChildDatePost[x]);
						trChild += '</td>';		
	
						trChild += '<td>';
						trChild += decodeURIComponent(StoreChildTypeJournal[x]);
						trChild += '</td>';							
						
						trChild += '<td>';
						trChild += decodeURIComponent(StoreChildDescription[x]);
						trChild += '</td>';							
	

						trChild += '<td>';
						trChild += decodeURIComponent(StoreChildDebit[x]);
						trChild += '</td>';	


						trChild += '<td>';
						trChild += decodeURIComponent(StoreChildCredit[x]);
						trChild += '</td>';	

							trChild += '<td>';
						trChild += decodeURIComponent(StoreChildSaldoAkhir[x]);
						trChild += '</td>';	

						
						trChild	+= '</tr>'; 	
					 
					 }
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
      });			
		
		
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
        url:"webservices/getListEjournal.php", 
        data : { code : '1', data:data  },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
				d = JSON.parse(response);
				console.log(d.records);
				StoreNumJournal    = d.records.num_journal;
				StoreDatePost      = d.records.date_post;
				StoreTypeJournal   = d.records.type_journal;
				StoreSegment       = d.records.segment;
				StoreOpeningBalance= d.records.openingbalance;
				StoreSegment       = d.records.segment;
				StoreIdCoa         = d.records.id_coa;
				StoreOpening       = d.records.openingbalance;
				renders            = '';
				td  = '';
				console.log()
				if(d.message == '1'){
					fixfrom = data.datefrom;
					fixto = data.dateto;
					$(".list").css('display','block');
					 for(x=0;x<StoreSegment.length;x++){
						td += '<tr id="Group'+decodeURIComponent(StoreNumJournal[x])+'">';
						td += '<td>';
						td += '<i class="fa fa-plus" style="cursor:pointer" id="'+decodeURIComponent(StoreNumJournal[x])+'" onclick="getChild('+decodeURIComponent(x)+','+StoreNumJournal[x]+',this)"> </i>';
						td += '</td>';						
						td += '<td>';
						td += decodeURIComponent(StoreNumJournal[x]);
						td += '</td>';
		
						td += '<td>';
						td += ' ';
						td += '</td>';
						
						td += '<td>';
						td += ' ';
						td += '</td>';						
		
						td += '<td>';
						td += decodeURIComponent(StoreDatePost[x]);
						td += '</td>';	

						td += '<td>';
						td += decodeURIComponent(StoreTypeJournal[x]);
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
						td += decodeURIComponent(StoreOpeningBalance[x]);
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


