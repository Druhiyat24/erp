$( document ).ready(function() {
	getListData();
});

function getListData(){
	$("#MyTableAllokasilinesewing").dataTable().fnDestroy();
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getListAllokasilinesewingpage.php", 
        data : { code : '1',   },     // multiple data sent using ajax
        success: function (response) {
			// console.log(response);
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				//d = response;
				td  = '';
				renders = '';
				// console.log(d.records.length);
				if(d.message == '1'){
						$("#render").append(data[1]);
						// console.log(data[1]);
						pasangDataTable();
			
				}
				if(d.message == '2'){
					alert("Input WS Salah !")
				}
        }
      })
	  
}
	async function pasangDataTable(){
	await $('#MyTableAllokasilinesewing').DataTable
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