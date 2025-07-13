$(document ).ready(function() {

    GenerateTable();
    // alert('GenerateTable');
}); 

function GenerateTable(){
    table = $('#mdefect').DataTable( {
      'processing': true,
      'serverSide': true,
      'destroy': true,
      'serverMethod': 'post',
      'autoWidth':false,
      'ajax': {
          'url':'webservices/getListMasterDefect.php',
      },
        'columns': [
           
        {
            "data":           "jenis_defect",
        } ,
       
        {
            "data":           "mattype",
        } ,  

        {
            "data":           "kode_defect",
        } ,  

        {
            "data":           "nama_defect",
        } ,

        {
            "data":           "kode_posisi",
        } ,  

        {
            "data":           "nama_posisi",
        } , 
         
        {
            "data":           "remark",
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


