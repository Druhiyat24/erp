$(document ).ready(function() {

    GenerateTable();
    // alert('GenerateTable');
}); 

function GenerateTable(){
    table = $('#mcontent').DataTable( {
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'autoWidth':false,
      'ajax': {
          'url':'webservices/getListMasterContent.php',
      },
        'columns': [
        {
            "data":           "id",
        } ,
		
        {
            "data":           "tampil",
        } ,

       
        {
            "data":           "kode_contents",
        } ,    

     
        {
            "data":           "nama_contents",
        } ,



             {
                "data":null,
                "render": function (data) {
                    return decodeURIComponent(data.button);
                            }
            }
         ],
        
    } );
}

