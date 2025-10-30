$(document ).ready(function() {

    GenerateTable();
    // alert('GenerateTable');
}); 

function GenerateTable(){
    table = $('#mlength').DataTable( {
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'autoWidth':false,
    'ordering': true, // ✅ enable ordering
    'order': [[1, 'desc']], // ✅ auto order by 2nd column (index starts from 0)
      'ajax': {
          'url':'webservices/getListMasterLength.php',
      },
        'columns': [
           
        {
            "data":           "tampil",
        } ,

       
        {
            "data":           "kode_length",
        } ,    

     
        {
            "data":           "nama_length",
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

