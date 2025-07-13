$( document ).ready(function() {

});	
		Senddata = {
			id : '',
			type : ''
		};
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

function DecodeURIComponents(data){
        for(var i=0, length=data.length;i<length;i++) {
            for ( var temp in data[i] ) {
                data[i][temp] = decodeURIComponent(data[i][temp]);
            } 
        }
        return data;
    }