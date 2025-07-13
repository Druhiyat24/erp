$(document ).ready(function() {

    GenerateTable();
    // alert('GenerateTable');
}); 

function GenerateTable(){
    table = $('#masterpanel').DataTable( {
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'autoWidth':false,
      'ajax': {
          'url':'webservices/getListMasterPanel.php',
      },
        'columns': [
           
        {
            "data":           "id",
        } ,

       
        {
            "data":           "nama_panel",
        } ,    

     
        {
            "data":           "description",
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


