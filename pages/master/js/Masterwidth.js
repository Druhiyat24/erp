$(document ).ready(function() {

    GenerateTable();
    // alert('GenerateTable');
}); 

function GenerateTable(){
    table = $('#mwidth').DataTable( {

      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'autoWidth':false,
    'ordering': true, // ✅ enable ordering
    'order': [[1, 'desc']], // ✅ auto order by 2nd column (index starts from 0)
      'ajax': {
          'url':'webservices/getListMasterWidth.php',
      },
        'columns': [
           
        {
            "data":           "tampil",
        } ,

       
        {
            "data":           "kode_width",
        } ,    

     
        {
            "data":           "nama_width",
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

