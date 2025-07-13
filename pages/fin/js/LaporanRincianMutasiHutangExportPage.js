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

    /* 	$("#period_from").datepicker( {
            format: "mm/yyyy",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true
        }); */

    // $("#period_to").datepicker( {
    // 	format: "mm/yyyy",
    // 	viewMode: "months",
    // 	minViewMode: "months",
    // 	autoclose: true
    // });		
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
    from = $("#period_from").val();
    to = $("#period_to").val();
    if (!from) {
        alert("Periode From Harus Diisi");
        return false;
    } if (!to) {
        alert("Periode To Harus Diisi");
        return false;
    }
    $(".list").css("display", "");

    GenerateTable(from, to);

    $("#label_from").text(from);
    $("#label_to").text(to);
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



/* For Export Buttons available inside jquery-datatable "server side processing" - Start
- due to "server side processing" jquery datatble doesn't support all data to be exported
- below function makes the datatable to export all records when "server side processing" is on */

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
//For Export Buttons available inside jquery-datatable "server side processing" - End
table = "";

function GenerateTable(from_, to_) {
    table = $("#laporan_jurnal").DataTable({
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[10, 25, 50, 999999999], [10, 25, 50, "All"]],
        "serverMethod": "post",
        "ajax": "webservices/getDataLaporanRincianMutasiHutangExport.php?from=" + from_ + "&to=" + to_,
        "columns": [
            //1
            {
                "data": "supplier_code"
            },
            //2	
            {
                "data": "supplier"
            },
            //4
            {
                "data": "tgl_inv"
            },
            //3	
            {
                "data": "invno"
            },	
            //5
            //4
            {
                "data": "tgl_inv"
            },
            //3	
            {
                "data": "invno"
            },	
            //7
            {
                "data": "d_pib"
            },
            //8
            {
                "data": "nomor_pib"
            },
            //9
            {
                "data": "tgl_fp"
            },
            //10
            {
                "data": "tgl_fp"
            },
            //11			
            {
                "data": "date_reff"
            },
            //12
            {
                "data": "reff_doc"
            },
            //13
            {
                "data": "id_order"
            },
            //14
            {
                "data": "top"
            },
            //15
            {
                "data": "tambah_pembelian_usd",
                "className": "right"
            },
            //16
            {
                "data": "rate",
                "className": "right"
            },
            //17
            {
                "data": "tambah_pembelian_idr",
                "className": "right"
            },
            //18
            {
                "data": "penambahan_lain_usd",
                "className": "right"
            },
            //19
            {
                "data": "rate",
                "className": "right"
            },
            //20
            {
                "data": "penambahan_lain_idr",
                "className": "right"
            },
            //21
            {
                "data": "reff_dokumen"
            },
            //22
            {
                "data": "no_journal_pengurangan"
            },
            //23
            {
                "data": "payment_voucher"
            },
            //24
            {
                "data": "payment_voucher_date"
            },


            //25
            {
                "data": "ket_pengurangan"
            },
            //26			
            {
                "data": "pengurangan_pembayaran_usd",
                "className": "right"
            },
            //27
            {
                "data": "rate",
                "className": "right"
            },
            //28
            {
                "data": "pengurangan_pembayaran_idr",
                "className": "right"
            },
            //29
            {
                "data": "pengurangan_retur_usd",
                "className": "right"
            },
            //30
            {
                "data": "rate",
                "className": "right"
            },
            //31
            {
                "data": "pengurangan_retur_idr",
                "className": "right"
            },
            //32
            {
                "data": "diskon_usd",
                "className": "right"
            },
            //33
            {
                "data": "rate",
                "className": "right"
            },
            //34
            {
                "data": "diskon_usd",
                "className": "right"
            },
            //35
            {
                "data": "pengurangan_lain_usd",
                "className": "right"
            },
            //36
            {
                "data": "rate",
                "className": "right"
            },
            //37
            {
                "data": "pengurangan_lain_idr",
                "className": "right"
            },
        ],
        "autoWidth": true,
        "scrollCollapse": true,
        "order": [[1, 'asc']],
        scrollY: "500px",
        scrollX: true,
        scrollCollapse: true,
        "destroy": true,
        order: [[6, "desc"]],
        fixedColumns: {
            leftColumns: 2
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export to Excel',
                className: 'btn-primary',
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
