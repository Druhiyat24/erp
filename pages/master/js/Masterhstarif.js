$(document ).ready(function() {

    GenerateTable();
    // alert('GenerateTable');
}); 

function GenerateTable(){
    table = $('#mhstarif').DataTable( {
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'autoWidth':false,
      'ajax': {
          'url':'webservices/getListMasterHstarif.php',
      },
        // order :[[2, 'asc']],
        'columns': [
           
        {
            "data":           "kode_hs",
        } ,

       
        {
            "data":           "nama_hs",
        } ,    

     
        {
            "data":           "tarif_hs",
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

