$(document).ready(function () {
	GenerateTable();
});

function newexportaction(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;
    dt.one('preXhr', function (e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;
        dt.one('preDraw', function (e, settings) {
            // Call the original action function
            if (button[0].className.indexOf('buttons-copy') >= 0) {
                $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-print') >= 0) {
                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
            }
            dt.one('preXhr', function (e, s, data) {
                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                // Set the property to what it was before exporting.
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });
            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
            setTimeout(dt.ajax.reload, 0);
            // Prevent rendering of the full data to the DOM
            return false;
        });
    });
    // Requery the server with the new one-time export settings
    dt.ajax.reload();
};
function GenerateTable(){
	table = $('#Inv_Scrap').DataTable( {
      'processing': true,
      'serverSide': true,
	  "lengthMenu": [[999999999], ["All"]],
		'serverMethod': 'post',
		
        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
        "ajax":  "webservices/getDataInvoiceScrapPage.php",
        "columns": [
		//date_journal
        {
			"data":           "invno",/* 
             "render":        function (data) {
							return decodeURIComponent(data.period);
                            } */
         } ,
		//id_journal
        {
			"data":           "buyer",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,		 
		//id_journal
        {
			"data":           "user_insert",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,	
		 
		//v_novoucher
        {
			"data":           "d_insert",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,		 
		 

		//id_coa
        {
			"data":           "user_update",/* 
             "render":        function (data) {
							return decodeURIComponent(data.status);
                            } */
         } ,
		 
		//nm_coa
        {
			"data":           "d_update",
         } ,		
				{
					"data": null,
					"render": function (data) {
						return decodeURIComponent(data.button);
					}
				},		 

        ],
		"autoWidth": true,
		"scrollCollapse": true,
        scrollY:        "500px",
        scrollX:        true,
        scrollCollapse: true,
		
        "destroy": true,
		//order: [[ 1, "asc" ]],
		"ordering": false,
/*         fixedColumns:   {
            leftColumns: 7
        }, */
      dom: 'Bfrtip',
		header: true,
		    dom:
			"<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
			"<'row'<'col-sm-12'tr>>"+
    "<'row'<'col-sm-5'i><'col-sm-7'p>>",	
    } );
}
