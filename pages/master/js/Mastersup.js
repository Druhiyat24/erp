$(document ).ready(function() {

    GenerateTable();
    // alert('GenerateTable');
}); 

function GenerateTable(){
    table = $('#msup').DataTable( {
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'autoWidth':false,
      'ajax': {
          'url':'webservices/getListMasterSup.php',
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
            "data":           "product_name",
        } ,  
        
        {
            "data":           "moq",
        } , 

        {
            "data":           "lead_time",
        } , 

        {
            "data":           "moq_lead_time",
        } , 

        {
            "data":           "pkp",
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


