$(document ).ready(function() {

    GenerateTable();
    // alert('GenerateTable');
}); 

function GenerateTable(){
    table = $('#mcf').DataTable( {
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'autoWidth':false,
      'ajax': {
          'url':'webservices/getListMasterCf.php',
      },
        'columns': [
           
        {
            "data":           "cfcode",
        } ,

       
        {
            "data":           "cfdesc",
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


