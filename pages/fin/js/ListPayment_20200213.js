
$( document ).ready(function() {
	//url =window.location.search;
document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
var $_GET = {};
    function decode(s) {
        return decodeURIComponent(s.split("+").join(" "));
    }

    $_GET[decode(arguments[1])] = decode(arguments[2]);
 
console.log($_GET.id);
if($_GET.id){
	Get_Detail_Nilai($_GET.id,$_GET.id,"EDIT").then(function(response){
		console.log(response.records[0]);
		$("#outstanding_nilai").val(number_format(response.records[0].total));
		$("#sisa_nilai").val(number_format(response.records[0].sisa));
		$("#n_nilai").val(number_format(response.records[0].nilai_input));
		document.getElementById("is_partial").checked = true;
		is_partials();
	});
}else{
	//alert("123");//outstanding_nilai
	$("#n_nilai").val("0.00");
	$("#sisa_nilai").val("0.00");
	$("#outstanding_nilai").val("0.00");
}
});
	n_nilai =0;
        tbl2 = $('#tbl2').DataTable
        ({  scrollY: "300px",
            scrollCollapse: true,
            paging: true,
            pageLength: 20,
            /*fixedColumns:
                { leftColumns: 1,
                    rightColumns: 1
                }*/
        });
		
	        tbl4= $('#tbl4').DataTable
        ({  scrollY: "300px",
            scrollCollapse: true,
            paging: true,
            pageLength: 20,
            /*fixedColumns:
                { leftColumns: 1,
                    rightColumns: 1
                }*/
        });		
		
        tbl3= $('#tbl3').DataTable
        ({  scrollY: "300px",
            scrollCollapse: true,
            paging: true,
            pageLength: 20,
            /*fixedColumns:
                { leftColumns: 1,
                    rightColumns: 1
                }*/
        });	


        tbl1= $('#tbl1').DataTable
        ({  scrollY: "300px",
            scrollCollapse: true,
            paging: true,
            pageLength: 20,
            /*fixedColumns:
                { leftColumns: 1,
                    rightColumns: 1
                }*/
        });			
	
	
		content = "";
		filter_table();	
		id_s = tmp_s;
		console.log(id_s);
        $('.btn-add').click(function(){
			if($(this).is('[disabled=disabled]')){
				return false;
				alert("123");
			}
			var idsupplier=$(this).data('idsupplier');
			if(!checkSupplier(idsupplier)){
				return false;
			};			
			$(this).attr('disabled',true);
			var type        =$(this).data('type');
			var kodesupplier=$(this).data('kodesupplier');
			var umurap      =$(this).data('umurap');
			var jatuhtempo  =$(this).data('jatuhtempo');
			var is_partial  =$(this).data('is_partial');
			if(is_partial == 'Y') //document.getElementById("is_partial").checked = true;
				{
					//is_partials()
					if($('#is_partial').is(':checked')){
						
					}else{
						document.getElementById("is_partial").checked = true;
					}	
					is_partials();
				}
				
			if(type=="KB"){
				var nokontrabon   = $(this).data('id_journal');
				var tgl_kontrabon = $(this).data('date_journal');
				var no_po 		  = '';
				var tgl_po        = '';
				var list_payment  = '';
				var tgl_list_payment = '';
				var tot_nilai = "N";	
			}else if(type == "PO"){
				var nokontrabon   = '';
				var tgl_kontrabon = '';
				var no_po  		  = $(this).data('id_journal');;
				var tgl_po 		  = $(this).data('date_journal');
				var list_payment  = '';
				var tgl_list_payment = '';		
				var tot_nilai = 'N';				
				
				
			}else if(type == "PY"){
				var nokontrabon   = '';
				var tgl_kontrabon = '';
				var no_po  		  = '';
				var tgl_po 		  = '';
				var list_payment     = $(this).data('id_journal');;
				var tgl_list_payment = $(this).data('date_journal');		
				var tot_nilai = 'Y';
				
			}
            var id_journal = $(this).data('id_journal');
            var nilai = number_format($(this).data('nilai'));
			get_total_nilai(n_nilai,nilai,'PLUS',list_payment);
            var date_journal = $(this).data('date_journal');
            var suppliers = $(this).data('suppliers');
			var source= $(this).data('source');
			//rep_1 = id_journal.replace('/',"_")+'__R';
			//rep_2 = rep_1.replace('/',"_");
			var rep_2 = id_journal.replace(/[^\w\s]/gi, '_')+'__R';
            var btn = '<input type="hidden" name="id_journal[]" value="'+id_journal+'" />'+
                '<input type="hidden" name="nilai[]" value="'+nilai+'" />'+
				'<input type="hidden" name="date_journal[]" value="'+date_journal+'" />'+
				'<input type="hidden" name="idsupplier[]" value="'+idsupplier+'" />'+
				'<input type="hidden" name="suppliers[]" value="'+suppliers+'" />'+
				'<input type="hidden" name="source[]" value="'+source+'" />'+
                '<a href="#" data-myids="'+idsupplier+'" id ="'+rep_2+'" data-nilai="'+nilai+'" class="btn btn-danger btn-del">Remove</a>';
            tbl2.row.add( [
				 nokontrabon  
				,tgl_kontrabon
				,no_po 		 
				,tgl_po   
				,list_payment 		 
				,tgl_list_payment   				
				,umurap
				,suppliers
				,nilai
				,umurap
				,jatuhtempo
                ,btn
            ] ).draw( false );
            return false;
        });

is_partials();
		
});	
		Senddata = {
			id : '',
			type : ''
		};
		
		
    var tbl2;
    function validasi() {
    }
	
	
	
	function before_validasi(){
        //valid = true;
        var outstanding_nilai = $("#outstanding_nilai").val();
		var nilai_input = $("#n_nilai").val();
		var sisa = $("#sisa_nilai").val();
		if(!outstanding_nilai){
			alert("Total Nilai Salah/Data Belum diisi!");
			return false;
		}else{
			if(outstanding_nilai == '0'){
				alert("Total Nilai Salah/Data Belum diisi!");
				return false;				
			}
		}
		if(!nilai_input){
				alert("Please Input Nilai Yang Akan dibayar");
				return false;			
		}else{
			if(nilai_input == '0'){
				alert("Please Input Nilai Yang Akan dibayar");
				return false;				
			}
		}

		ValidasiNilaiBeforeSaveListPayment(outstanding_nilai,nilai_input,sisa).then(function(responnya) {
			if(responnya.status == 'ok'){
				if(responnya.message == '1' && responnya.key == '1' ){
					console.log(responnya.status);
					$( "#submit" ).trigger( "click" );
					//return true;	
				}else{
					console.log(responnya.description);
					alert(responnya.description);
					return false;
				}
				
			}
			return false;
		});
     		
		
		
		
		
		
		
	}
	
function ValidasiNilaiBeforeSaveListPayment($outstanding_nilai,$nilai_input,$sisa){
	return new Promise(function(resolve, reject) {
			var my_id_rekap = $("#id_rekap").val();
			if(!my_id_rekap){
				my_id_rekap = "NA";
			}				
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/ValidasiNilaiBeforeSaveListPayment.php",
        data: { code: '1', outstanding_nilai: $outstanding_nilai,nilai_input:$nilai_input,sisa:$sisa,id_rekap:my_id_rekap },     // multiple data sent using ajax
        success: function (response) {
            console.log(response);
            var options = "";
            console.log(response);
            datass = response;
            d = JSON.parse(datass);
            //d = response;
            td = '';
            renders = '';
			
			resolve(d);
            
			

        }
    });
	});	
	
	
	
	
}


function Get_Detail_Nilai($list_payment,$list_src,$source){
	return new Promise(function(resolve, reject) {
     $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getDetailListPayment.php",
        data: { code: '1', v_list_code: $list_payment,list_src:$list_src,source:$source},     // multiple data sent using ajax
        success: function (response) {
            console.log(response);
            var options = "";
            console.log(response);
            datass = response;
            d = JSON.parse(datass);
            //d = response;
			
			resolve(d);
        }
    });
	});	
}	
	
	function is_partials(){
    if($('#is_partial').is(':checked')){
        $('#n_nilai').prop('readonly', false);
     }else{
		 $('#n_nilai').prop('readonly', true);
	 }
	}

	id_s = [];
	function checkSupplier(id){
		console.log(id);
		var key = 0;
		var count = id_s.length;

			for(var i=0;i<count;i++){
				if(id == id_s[i]){
					key = 0;
				}else{
					if(id_s[i] == 'XX'){
						key = 0;
					}else{
						key = 1;
					} 
				}
			}
			
		
		console.log(id_s);
		if(key > 0){
			alert("Supplier harus sama!");
			return false;
		}
		else{
			id_s.push(id);
			return true;
		}
	}
		
	


	function klik(Item){
	 setTimeout(function(){ 	
	console.log("Begin");
		$(".sorting").trigger("click");
	}, 1000);			
			
			
	content = Item;
	filter_table();	
		
	}
	
    $(document).on( 'click', '.btn-del', function () {
		 var myids = $(this).data('myids');
		 var nilai_pengurang = $(this).data('nilai');
		 var nilai_input = $("#n_nilai").val();
		 var sisa =  $("#sisa_nilai").val();
		 var total_nilai =  $("#outstanding_nilai").val();
		if(get_total_nilai_minus(total_nilai,nilai_pengurang,nilai_input,sisa,"Minus") == '0'){
			alert("Ada Kesalahan Input!");
			return false;
			
		}else{
		var idTmp = this.id;
			idTmp = idTmp.split("__R");
			console.log(idTmp);
			idTmp = idTmp[0];
			console.log(idTmp);
		$("#"+idTmp).removeAttr('disabled');
	
	   tbl2
         .row( $(this).parents('tr') )
         .remove()
         .draw();
		var key= id_s.indexOf(myids);
		id_s.splice(key, 1, 'XX');
		console.log(id_s);
        return false;

			
		}		 

		
		
		
		
    } );
	
	
					
	
	    function filter_table()
    {	

			var filter_supplier = $('#filter_supplier').val();
		if(content == "KB"){
			tbl1.column(4).search(filter_supplier);
			 tbl1.draw();
		}
		else if(content == "PO"){
			tbl3.column(4).search(filter_supplier);
			 tbl3.draw();
			
		}		else if(content == "PY"){
			tbl4.column(4).search(filter_supplier);
			 tbl4.draw();
			
		}
		
		
		else{
			content = "KB";
			
			
		}



    }	
	
		
function executed(ids,types){
			Senddata.id = ids;
			Senddata.type = types;
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/SendApproval.php", 
        data : { code : '1', Senddata },     // multiple data sent using ajax
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				console.log(d.message);
				if(d.message == '1'){
					if(Senddata.type == "SEND"){
						alert("Data Berhasil dikirim");
						
					}
					else if(Senddata.type == "DELETE"){
						alert("Data Berhasil di Delete");
					}
					location.reload();
				}
				
				else{
					alert(d.records);
					
				}
        }
      });		 
	 
	 	
	
	
	
}
function executedAR(ids,types){

			Senddata.id = ids;
			Senddata.type = types;
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/DeleteAR.php", 
        data : { code : '1', Senddata },     // multiple data sent using ajax
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				console.log(d.message);
				if(d.message == '1'){
					if(Senddata.type == "DELETE"){
						alert("Data Berhasil di Delete");
					}
					location.reload();
				}
				
				else{
					alert(d.records);
					
				}
        }
      });		 
	 
	 	
	
	
	
}

function get_total_nilai_minus($total_nilai,$nilai_pengurang,$nilai_input,$sisa,$operation){
		    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/GetTotalNilaiListPayment.php", 
        data : { code : '1', total_nilai:$total_nilai, nilai_pengurang : $nilai_pengurang, operation : $operation,nilai_input:$nilai_input,sisa:$sisa },     // multiple data sent using ajax
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				console.log(d.message);
				if(d.message == '1'){
					n_nilai = d.total_nilai;
					//sisa = d.sisa;  
					$("#outstanding_nilai").val(number_format(n_nilai,'X'));
					$("#sisa_nilai").val(number_format(d.sisa,'X'));
					$valid = 1;
					return $valid;
				}
				
				else{
					alert("Ada Kesalahan Input Nilai, Periksa Kembali Nilai nya!");
					$valid = 0;
					return $valid;
				}
        }
      });	
	 // console.log($valid);
//return $valid;

	
}

function get_total_nilai($n_nilai,$new_nilai,$operation,tot_nilai){
		    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/GetTotalNilaiListPayment.php", 
        data : { code : '1', n_nilai:$n_nilai, new_nilai : $new_nilai, operation : $operation },     // multiple data sent using ajax
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				console.log(d.message);
				if(d.message == '1'){
					n_nilai = d.total_nilai;
					$("#outstanding_nilai").val(number_format(n_nilai,'X'));
					$("#sisa_nilai").val(number_format(n_nilai,'X'));
					$("#n_nilai").val(number_format(n_nilai,'X'));
					console.log(tot_nilai);
					if(tot_nilai != ''){
						Get_Detail_Nilai(tot_nilai,"NA","ADD").then(function(response){
							console.log(response.records[0]);
							$("#outstanding_nilai").val(number_format(response.records[0].total));
							$("#sisa_nilai").val(number_format(response.records[0].sisa));
							$("#n_nilai").val(number_format(response.records[0].sisa));
							document.getElementById("is_partial").checked = true;
							is_partials();
						});
					}
					
				}
				
				else{
					alert(d.records);
					
				}
        }
      });	


	
}

function DecodeURIComponents(data){
        for(var i=0, length=data.length;i<length;i++) {
            for ( var temp in data[i] ) {
                data[i][temp] = decodeURIComponent(data[i][temp]);
            } 
        }
        return data;
    }