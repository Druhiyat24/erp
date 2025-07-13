$(document ).ready(function() {

    GenerateTable();
    // alert('GenerateTable');
}); 

function GenerateTable(){
    table = $('#mcus').DataTable( {
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'autoWidth':false,
      'ajax': {
          'url':'webservices/getListMasterCus.php',
      },
        'columns': [
           
        {
            "data":           "supplier_code",
        } ,

       
        {
            "data":           "Supplier",
        } ,  

        {
            "data":           "alamat",
        } ,  

        {
            "data":           "areanya",
        } ,  

        {
            "data":           "country",
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


