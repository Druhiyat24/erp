$(document ).ready(function() {

    GenerateTable();
    // alert('GenerateTable');
}); 

function GenerateTable(){
    table = $('#mproduct').DataTable( {
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'autoWidth':false,
      'ajax': {
          'url':'webservices/getListMasterProduct.php',
      },
        'columns': [
           
        {
            "data":           "product_group",
        } ,

       
        {
            "data":           "product_item",
        } ,  

        // {
        //     "data":           "model",
        // } ,  

        // {
        //     "data":           "berat",
        // } ,  
        
        // {
        //     "data":           "berat_kotor",
        // } ,   

             {
                "data":null,
                "render": function (data) {
                    return decodeURIComponent(data.button);
                            }
            }
         ],
        
    } );
}


