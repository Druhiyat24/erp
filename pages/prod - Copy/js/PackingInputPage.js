$( document ).ready(function() {

});

function getListData(){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getListCuttingInput.php", 
        data : { code : '1',   },     // multiple data sent using ajax
        success: function (response) {
			console.log(response);
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				//d = response;
				td  = '';
				renders = '';
				console.log(d.records.length);
				if(d.message == '1'){
						$("#render").append(data[1]);
			
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	