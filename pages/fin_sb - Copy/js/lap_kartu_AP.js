$(document).ready(function () {

    localStorage.clear();
    sessionStorage.clear();
    $("#period_from").datepicker({
        format: "dd M yyyy",
        autoclose: true
    });

    $("#period_to").datepicker({
        format: "dd M yyyy",
        autoclose: true
    });
});
data = {
    iddatefrom: '',
    iddateto: '',
    tipejurnal: '',
    stts: ''
}
$reload = 0;
function back() {
    overlayon();
    location.reload(true);
}
function getListData() {
    //alert("123");
    from = $("#period_from").val();
    to = $("#period_to").val();
    id_coa = $("#id_coa").val();
    if (id_coa == "") {
        type__ = "ALL";
    } else {
        type__ = $("#id_coa option:selected").text();
    }
    if (id_coa == '') {
        alert("Coa Harus Dipilih");
        return false;
    }
    /* 	if(status==''){
            alert("Status Harus Diisi");
            return false;
        }  */
    if (!from) {
        alert("Periode From Harus Diisi");
        return false;
    }
    if (!to) {
        alert("Periode To Harus Diisi");
    }
    $(".list").css("display", "");

    GenerateTable(from, to);

    $("#label_from").text(from);
    $("#label_to").text(to);
    $("#label_type_journal").text(type__);
}

function overlayon() {

    $("#myOverlay").css("display", "block");

}
function overlayoff() {
    $("#myOverlay").css("display", "none");
}

function check_journal(val) {
    if (val == '1' || val == '2' || val == '17') {
        $("#txtstatus").val('2').trigger("change");
    } else {
        $("#txtstatus").val('').trigger("change");

    }


}

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

table = "";



function GenerateTable(from_,to_){

	table = $('#laporan_jurnal_hutang').DataTable( {
      'processing': true,
      'serverSide': true,
	  "lengthMenu": [[999999999], ["All"]],
		'serverMethod': 'post',
		
        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
        "ajax": "webservices/getDataLaporanKartuAP.php?from=" + from_ + "&to=" + to_ + "&id_coa=" + id_coa,
        "columns": [
            // {
            //     "data": "no",
            //     "className": "center"
            // },
            {
                "data": "date_journal",
                "className": "center"
            },
            {
                "data": "id_journal"
            },
            {
                "data": "bpbno",
                "className": "center"
            },
            {
                "data": "v_novoucher",
                "className": "center"
            },
            {
                "data": "description"
            },
            {
                "data": "debit_ori",
                "className": "right"
            },
            {
                "data": "credit_ori",
                "className": "right"
            },
            {
                "data": "saldo_ori",
                "className": "right"
            },
            {
                "data": "rate",
                "className": "right"
            },
            {
                "data": "debit_idr",
                "className": "right"
            },
            {
                "data": "credit_idr",
                "className": "right"
            },
            {
                "data": "saldo_idr",
                "className": "right"
            }
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
        buttons: [
{

              extend: 'excel', 
              text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export to Excel',
              className: 'btn-primary',
			  //title: 'Any title for file',
			          message: "Periode "+from_+" Sampai "+to_+" \n",
					  exportOptions:{
						  search :'applied',
						  order:'applied'
					  },

					   "action": newexportaction,
					   
					   
					   

                      }       
		
			
        ], 
		header: true,
		    dom:
			"<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
			"<'row'<'col-sm-12'tr>>"+
    "<'row'<'col-sm-5'i><'col-sm-7'p>>",	
    } );
}

/*
function GenerateTable(from_, to_) {
    //overlayon();
    table = $("#laporan_jurnal_hutang").DataTable({
        "processing": true,
        "serverside": true,
        "bSort": false,
        "orderable": false,
        "columnDefs": [{
            "targets": [11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1, 0],
            "orderable": false, // set orderable false for selected columns
        }],
        "aoColumnDefs": [
            {
                "bSortable": false,
                "aTargets": [11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1, 0]
            }
        ],
        "lengthMenu": [[999999999], ["All"]],
        "serverMethod": "post",
        "ajax": "webservices/getDataLaporanKartuAP.php?from=" + from_ + "&to=" + to_ + "&id_coa=" + id_coa,
        "columns": [
            // {
            //     "data": "no",
            //     "className": "center"
            // },
            {
                "data": "date_journal",
                "className": "center"
            },
            {
                "data": "id_journal"
            },
            {
                "data": "bpbno",
                "className": "center"
            },
            {
                "data": "v_novoucher",
                "className": "center"
            },
            {
                "data": "description"
            },
            {
                "data": "debit_ori",
                "className": "right"
            },
            {
                "data": "credit_ori",
                "className": "right"
            },
            {
                "data": "saldo_ori",
                "className": "right"
            },
            {
                "data": "rate",
                "className": "right"
            },
            {
                "data": "debit_idr",
                "className": "right"
            },
            {
                "data": "credit_idr",
                "className": "right"
            },
            {
                "data": "saldo_idr",
                "className": "right"
            }
        ],
        "autoWidth": true,
        "scrollCollapse": true,
        // "order": [[0, "asc"]],
        scrollY: "500px",
        scrollX: true,
        // scrollCollapse: true,
        "destroy": true,
        paging: false,
        // order: [[0, "asc"]],
        // fixedColumns:   {
        //     leftColumns: 2
        // },
        dom: "Bfrtip",
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export to Excel',
                className: 'btn btn-success',
                //title: 'Any title for file',
                message: "Periode " + from_ + " Sampai " + to_ + " \n",
                exportOptions: {
                    search: 'applied',
                    order: 'applied'
                },
                "action": newexportaction,
            }
        ],
        header: true,
        dom:
            "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    });
}


*/

function fnExcelReport() {
    //table.destroy().draw();
    $('#laporan_jurnal').DataTable({

        "bPaginate": false,
    });
    table.destroy().draw();
    overlayon();
    setTimeout(function () {
        var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
        var textRange; var j = 0;
        tab = document.getElementById('laporan_jurnal'); // id of table

        for (j = 0; j < tab.rows.length; j++) {
            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            //tab_text=tab_text+"</tr>";
        }

        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");
        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
        {
            txtArea1.document.open("txt/html", "replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus();
            sa = txtArea1.document.execCommand("SaveAs", true, "Lap_Journal.xls");
        }
        else {               //other browser not tested on IE 11
            var uri = 'data:application/vnd.ms-excel,' + encodeURIComponent(tab_text);
            var downloadLink = document.createElement("a");
            downloadLink.href = uri;
            downloadLink.download = "Lap_Journal.xls";

            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);



            overlayoff();
            GenerateTable(from, to);
            overlayoff();
            //sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  
        }
        return (sa);
    }, 3000);


}