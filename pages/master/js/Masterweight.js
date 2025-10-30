$(document ).ready(function() {

    GenerateTable();
    // alert('GenerateTable');
}); 

function GenerateTable(){
    table = $('#mweight').DataTable( {
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'autoWidth':false,
                'ordering': true, // ✅ enable ordering
    'order': [[1, 'desc']], // ✅ auto order by 2nd column (index starts from 0)
      'ajax': {
          'url':'webservices/getListMasterWeight.php',
      },
        'columns': [
           
        {
            "data":           "tampil",
        } ,

       
        {
            "data":           "kode_weight",
        } ,    

     
        {
            "data":           "nama_weight",
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

