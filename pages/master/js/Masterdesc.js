$(document ).ready(function() {

    GenerateTable();
    // alert('GenerateTable');
}); 

function GenerateTable(){
    table = $('#listmdesc').DataTable( {
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'autoWidth':false,
      'ajax': {
          'url':'webservices/getListMasterDesc.php',
      },
        // order :[[2, 'asc']],
        'columns': [
        {
            "data":           "id_contents",
        } ,
           
        {
            "data":           "tampil",
        } ,

       
        {
            "data":           "kode_desc",
        } ,    

     
        {
            "data":           "nama_desc",
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

