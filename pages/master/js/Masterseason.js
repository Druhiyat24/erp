$(document ).ready(function() {

    GenerateTable();
    // alert('GenerateTable');
}); 

function GenerateTable(){
    table = $('#mseason').DataTable( {
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'autoWidth':false,
      'ajax': {
          'url':'webservices/getListMasterSeason.php',
      },
        'columns': [
           
        {
            "data":           "season",
        } ,

       
        {
            "data":           "season_desc",
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


