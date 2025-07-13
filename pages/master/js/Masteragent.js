$(document ).ready(function() {

    GenerateTable();
    // alert('GenerateTable');
}); 

function GenerateTable(){
    table = $('#magent').DataTable( {
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'autoWidth':false,
      'ajax': {
          'url':'webservices/getListMasterAgent.php',
      },
        'columns': [
           
        {
            "data":           "id",
        } ,

        {
            "data":           "buyer",
        } ,

       
        {
            "data":           "agent",
        } ,

        {
            "data":           "aktif",
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


