$(document ).ready(function() {

    GenerateTable();
}); 


function GenerateTable(){
	table = $('#list_po').DataTable( {
      'processing': true,
      'serverSide': true,
	  'destroy': true,
		'serverMethod': 'post',
        'aaSorting': [[ 2, 'desc' ]],
        
		
        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
        'ajax': {
          'url':'webservices/getDataDraftPurchaseOrderGeneral.php',
      },
        "columns": [
		//draftno
        {
			"data":           "draftno",/* 
             "render":        function (data) {
							return decodeURIComponent(data.period);
                            } */
         } ,

		//draftdate
        {
			"data":           "draftdate",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,
		//nama_supplier
        {
			"data":           "nama_supplier",/* 
             "render":        function (data) {
							return decodeURIComponent(data.date_journal);
                            } */
         } ,		 
		//kode_pterms
        {
			"data":           "kode_pterms",
         } ,			 


{
            "data":           "reqno",/* 
             "render":        function (data) {
                            return decodeURIComponent(data.date_journal);
                            } */
         } ,        
 		//tgl_fakturpajak
        {
			"data":           "n_kurs",/* 
             "render":        function (data) {
							return decodeURIComponent(data.debit);
                            } */
         } ,		
 		//status
        {
			"data":           "status",/* 
             "render":        function (data) {
							return decodeURIComponent(data.debit);
                            } */
         } ,		 
         	
         {
            "data":           "app_by",/* 
             "render":        function (data) {
                            return decodeURIComponent(data.debit);
                            } */
         } , 		
         {
            "data":           "app_date",/* 
             "render":        function (data) {
                            return decodeURIComponent(data.debit);
                            } */
         } ,    
             {
                "data":           null,
                "render": function (data) {
                    return decodeURIComponent(data.button);
                            }
            } 
         ]
/* 		"rowCallback": function( nRow, data, index ) {
			var is_color = decodeURIComponent(data.is_color);
				//$node = this.api().row(row).nodes().to$();
			if (is_color == "YELLOW" ) {
					$('td', nRow).css('background-color', 'Yellow');
			}else if (is_color == "RED" ) {
					$('td', nRow).css('background-color', 'Green');
			}		 
		} */
    } );
}

