$( document ).ready(function() {
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
			
			if(type=="KB"){
				var nokontrabon   = $(this).data('id_journal');
				var tgl_kontrabon = $(this).data('date_journal');
				var no_po 		  = '';
				var tgl_po        = '';
				
			}else if(type == "PO"){
				var nokontrabon   = ''
				var tgl_kontrabon = ''
				var no_po  		  = $(this).data('id_journal');;
				var tgl_po 		  = $(this).data('date_journal');;				
				
				
			}
            var id_journal = $(this).data('id_journal');
            var nilai = $(this).data('nilai');
			get_total_nilai(n_nilai,nilai,'PLUS');
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
				,umurap
				,suppliers
				,nilai
				,umurap
				,jatuhtempo
                ,btn
            ] ).draw( false );
            return false;
        });


		
});	
		Senddata = {
			id : '',
			type : ''
		};
		
		
    var tbl2;
    function validasi() {
        valid = true;

        return valid;
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
		 var nilai = $(this).data('nilai');
		 var param_nilai = $("#n_nilai").val();
		if(get_total_nilai_minus(n_nilai,nilai,param_nilai,"Minus") == '0'){
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
			
		}else{
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

function get_total_nilai_minus($n_nilai,$new_nilai,$param_nilai,$operation){
		    $.ajax({		
        type:"POST",
        cache:false, 
        url:"webservices/GetTotalNilaiListPayment.php", 
        data : { code : '1', n_nilai:$n_nilai, new_nilai : $new_nilai, operation : $operation,param_nilai:$param_nilai },     // multiple data sent using ajax
        success: function (response) {
			data = response;
			console.log(data);
				d = JSON.parse(data);
				console.log(d.message);
				if(d.message == '1'){
					n_nilai = d.total_nilai;
					$("#outstanding_nilai").val(number_format(n_nilai,'X'));
					$valid = 1;
				}
				
				else{
					alert("Ada Kesalahan Input Nilai, Periksa Kembali Nilai nya!");
					$valid = 0;
				}
        }
      });	
return $valid;

	
}

function get_total_nilai($n_nilai,$new_nilai,$operation){
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