$( document ).ready(function() {

});  

	function check_customer(id_costing,id_cst,pages){
		if(pages != "MKT"){
			$url = "../marketting/webservices/check_customer.php";
		}else{
			$url ="webservices/check_customer.php";
		}
		return new Promise(function(resolve, reject) {
	    $.ajax({		
        type:"POST",
        cache:false, 
        url:$url, 
        data : { code : '1', id_cost:id_costing,id_cst:id_cst,pages:pages },     // multiple data sent using ajax
        success: function (response) {
			data = response;
				d = JSON.parse(data);
			if(id_costing !='N'){
							if(d.message == '1'){
								
								console.log(d);
								if(d.records == '1'){
									resolve('BLOK');
									//{ swal({ title: 'Nama Buyer Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' });}
								}else{
									resolve("PASS");
								}
							}else{
									resolve('BLOCK');
									//{ swal({ title: 'Nama Buyer Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' });}
								
							}	
			}else{
				if(d.message == '1'){
					console.log(d);
					if(d.records == '1'){
						//resolve('BLOK');
						if(pages == 'INV'){
							{ swal({ title: 'SJ tidak dapat dibuat untuk buyer ini karena buyer diblock', imageUrl: '../../images/error.jpg' });}
							$("#my_sup").val("").trigger("change");
	
						}else if(pages == 'SHP'){
							{ swal({ title: 'PL/INV tidak dapat dibuat untuk buyer ini karena buyer diblock', imageUrl: '../../images/error.jpg' });}							
							resolve('BLOK');
						}
						//alert(alr);
					}else{
						if(pages == 'SHP'){
							resolve('PASS');
						}
						//resolve("PASS");
					}
						//alert("SJ tidak dapat dibuat untuk buyer ini karena buyer diblock");
				}else{ 
						if(pages == 'INV'){
							{ swal({ title: 'SJ tidak dapat dibuat untuk buyer ini karena buyer diblock', imageUrl: '../../images/error.jpg' });}
							$("#my_sup").val("").trigger("change");
						}else if(pages == 'SHP'){
							{ swal({ title: 'PL/INV tidak dapat dibuat untuk buyer ini karena buyer diblock', imageUrl: '../../images/error.jpg' });}
							resolve('BLOK');
						}
				}	

				
				
			}		

        }
      });
		});	  
	 
	 
	 
 }
