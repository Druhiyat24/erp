$(document ).ready(function() {

    GenerateTable();
    // alert('GenerateTable');
}); 

function GenerateTable(){
    table = $('#mcolor').DataTable( {
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'autoWidth':false,
      'ajax': {
          'url':'webservices/getListMasterColor.php',
      },
        'columns': [
        {
            "data":           "id",
        } ,

        {
            "data":           "id_contents",
        } ,                  
           
        {
            "data":           "tampil",
        } ,

       
        {
            "data":           "kode_color",
        } ,    

     
        {
            "data":           "nama_color",
        } ,

        {
            "data":           "phantom",
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

