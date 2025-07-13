$(document ).ready(function() {

    GenerateTable();
    // alert('GenerateTable');
}); 

function GenerateTable(){
    table = $('#mwidth').DataTable( {
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'autoWidth':false,
      'ajax': {
          'url':'webservices/getListMasterWidth.php',
      },
        'columns': [
           
        {
            "data":           "tampil",
        } ,

       
        {
            "data":           "kode_width",
        } ,    

     
        {
            "data":           "nama_width",
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

