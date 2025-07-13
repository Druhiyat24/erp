$(document ).ready(function() {

    GenerateTable();
    // alert('GenerateTable');
}); 

function GenerateTable(){
    table = $('#msubgroup').DataTable( {
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'autoWidth':false,
      'ajax': {
          'url':'webservices/getListMasterSubGroup.php',
      },
           
        'columns': [
           
        {
            "data":           "nama_group",
        } ,

       
        {
            "data":           "kode_sub_group",
        } ,    

     
        {
            "data":           "nama_sub_group",
        } ,

        {
            "data":           "id_coa_d",
        } ,

        {
            "data":           "id_coa_k",
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


