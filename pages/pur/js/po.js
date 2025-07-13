$(document ).ready(function() {

    GenerateTable();
}); 


function GenerateTable(){
	table = $('#list_po').DataTable( {
      'processing': true,
      'serverSide': true,
	  'destroy': true,
	  'ordering' :false,
      'paging' : true,
		'serverMethod': 'post',
        //'aaSorting': [[ 2, 'desc' ]],
        
		
        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
        'ajax': {
          'url':'webservices/getDataPurchaseOrder.php',
      },
        "columns": [
		//id_coa
        {
			"data":           "pono",/* 
             "render":        function (data) {
							return decodeURIComponent(data.period);
                            } */
         } ,
		//supplier_code
        {
			"data":           "revise",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,	

		//supplier
        {
			"data":           "podate",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,
		 
		//iinvdate
        {
			"data":           "supplier",
         } ,			 

		//surat_jalan
        {
			"data":           "nama_pterms",/* 
             "render":        function (data) {
							return decodeURIComponent(data.date_journal);
                            } */
         } ,		

{
            "data":           "buyer",/* 
             "render":        function (data) {
                            return decodeURIComponent(data.date_journal);
                            } */
         } ,        


		//invdate
        {
			"data":           "kpno",/* 
             "render":        function (data) {
							return decodeURIComponent(data.id_coa);
                            } */
         } ,


        {
			"data":           "styleno",/* 
             "render":        function (data) {
							return decodeURIComponent(data.curr);
                            } */
         } ,
 
 		//nomor_kb
        {
			"data":           "notes",/* 
             "render":        function (data) {
							return decodeURIComponent(data.debit);
                            } */
         } ,
		 
 		//tgl_fakturpajak
        {
			"data":           "n_kurs",/* 
             "render":        function (data) {
							return decodeURIComponent(data.debit);
                            } */
         } ,		
         {
            "data":           "app",/* 
             "render":        function (data) {
                            return decodeURIComponent(data.debit);
                            } */
         } , 		
         {
            "data":           "app_draft",/* 
             "render":        function (data) {
                            return decodeURIComponent(data.debit);
                            } */
         } , 		
         {
            "data":           "app_po",/* 
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
         ],
		"rowCallback": function( nRow, data, index ) {
			var is_color = decodeURIComponent(data.is_color);
				//$node = this.api().row(row).nodes().to$();
			if (is_color == "YELLOW" ) {
					$('td', nRow).css('background-color', 'Yellow');
			}else if (is_color == "RED" ) {
					$('td', nRow).css('color', 'Red');
			}		else if (is_color == "GREEN" ) {
					$('td', nRow).css('background-color', 'Green');
			}			 
		}
    } );
}

