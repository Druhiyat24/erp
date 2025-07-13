$(document ).ready(function() {

    GenerateTable();
    // alert('GenerateTable');
}); 

function GenerateTable(){
    table = $('#masterrak').DataTable( {
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'autoWidth':false,
      'ajax': {
          'url':'webservices/getListMasterRak.php',
      },
        'columns': [
           
        {
            "data":           "kode_rak",
        } ,

       
        {
            "data":           "nama_rak",
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


