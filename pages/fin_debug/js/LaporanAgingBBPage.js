$( document ).ready(function() {
    localStorage.clear();
    sessionStorage.clear();
    
        $("#period_from").datepicker( {
format: "dd M yyyy",
        autoclose: true
    });   
    
/*  $("#period_from").datepicker( {
        format: "mm/yyyy",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true
    }); */
    
    $("#period_to").datepicker( {
format: "dd M yyyy",
        autoclose: true
    });     
});
data = {
    iddatefrom : '',
    iddateto : '',
    tipejurnal : '',
    stts : ''
}
$reload = 0;
function back(){
    overlayon();
    location.reload(true);
}
function getListData(){
    from = $("#period_from").val();
    to = $("#period_to").val();
    if(!from){
        alert("Periode Tanggal Harus Diisi");
        return false;
    }
    // if(!to){
    //  alert("Periode To Harus Diisi");
    // }    
    $(".list").css("display","");

    GenerateTable(from,to);

    $("#label_from").text(from);
    $("#label_to").text(to);
}

function overlayon(){

    $("#myOverlay").css("display","block");
    
}
function overlayoff(){
    $("#myOverlay").css("display","none");
}

function check_journal(val){
    if(val == '1' || val == '2' || val == '17' ){
        $("#txtstatus").val('2').trigger("change");
    }else{
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


function GenerateTable(from_,to_){
    table = $('#laporan_jurnal').DataTable( {
      'processing': true,
      'serverSide': true,
      "columnDefs": [ {
      "targets": [ 36,35,34,33,32,31,30,29,28,27,26,25,24,23,22,21,20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1,0],
      "orderable": false
    } ],
      // "lengthMenu": [[5], ["5"]],
   //    "pageLength": 5,
      "lengthMenu": [[10, 25, 50, 999999999], [10, 25, 50, "All"]],

        'serverMethod': 'post',

        
        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
        "ajax":  "webservices/getDataLaporanAgingBB.php?from="+from_+"&to="+to_,
        "columns": [
        //id_coa
        {
            "data":           "goods_code",/* 
             "render":        function (data) {
                            return decodeURIComponent(data.period);
                            } */
         } ,
        //supplier_code
        {
            "data":           "itemdesc",/* 
             "render":        function (data) {
                            return decodeURIComponent(data.status);
                            } */
         } ,    

        //kode_pterms
        {
            "data":           "no_ws",
         } ,             

        {
            "data":           "id_order",
            "className" : "center"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.status);
                            } */
         } ,
        //period
        {
            "data":           "supplier_code",
            "className" : "center"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.date_journal);
                            } */
         } ,        


        //pembelian_idr
        {
            "data":           "Supplier", 
         } ,


        //pembelian_idr_total
        {
            "data":           "no_dokumen",
         } ,
         {
            "data":           "qty_po",/* 
             "render":        function (data) {
                            return decodeURIComponent(data.status);
                            } */
         } ,    

        //kode_pterms
        {
            "data":           "qty_masuk",
            "className" : "right"
         } ,             

        {
            "data":           "keluar_qty",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.status);
                            } */
         } ,
        //period
        {
            "data":           "qty_akhir",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.date_journal);
                            } */
         } ,        
         {
            "data":           "harga_qty_po",
            "className" : "right"
            ,/* 
                                
             "render":        function (data) {
                            return decodeURIComponent(data.status);
                            } */
         } ,    

        //kode_pterms
        {
            "data":           "harga_masuk_qty",
            "className" : "right"
                        
         } ,             

        {
            "data":           "harga_keluar_qty",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.status);
                            } */
         } ,
        //period
        {
            "data":           "harga_qty_akhir",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.date_journal);
                            } */
         } ,        
         {
            "data":           "u_awal",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.status);
                            } */
         } ,    

        //kode_pterms
        {
            "data":           "u_masuk",
            "className" : "right"
         } ,             

        {
            "data":           "u_keluar",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.status);
                            } */
         } ,
        //period
        {
            "data":           "u_akhir",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.date_journal);
                            } */
         } ,        
          {
            "data":           "qty_umur_0_30",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.status);
                            } */
         } ,    

        //kode_pterms
        {
            "data":           "qty_umur_31_60",
            "className" : "right"
         } ,             

        {
            "data":           "qty_umur_61_90",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.status);
                            } */
         } ,
        //period
        {
            "data":           "qty_umur_91_120",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.date_journal);
                            } */
         } ,        
         {
            "data":           "qty_umur_121_150",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.status);
                            } */
         } ,    

        //kode_pterms
        {
            "data":           "qty_umur_151_180",
            "className" : "right",
         } ,             

        {
            "data":           "qty_umur_181_360",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.status);
                            } */
         } ,
        //period
        {
            "data":           "qty_umur_361",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.date_journal);
                            } */
         } ,    
         {
            "data":           "q_total",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.date_journal);
                            } */
         } ,    
                   {
            "data":           "price_umur_0_30",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.status);
                            } */
         } ,    

        //kode_pterms
        {
            "data":           "price_umur_31_60",
            "className" : "right"
         } ,             

        {
            "data":           "price_umur_61_90",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.status);
                            } */
         } ,
        //period
        {
            "data":           "price_umur_91_120",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.date_journal);
                            } */
         } ,        
         {
            "data":           "price_umur_121_150",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.status);
                            } */
         } ,    

        //kode_pterms
        {
            "data":           "price_umur_151_180",
            "className" : "right",
         } ,             

        {
            "data":           "price_umur_181_360",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.status);
                            } */
         } ,
        //period
        {
            "data":           "price_umur_361",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.date_journal);
                            } */
         } ,    
         {
            "data":           "r_total",
            "className" : "right"
            ,/* 
             "render":        function (data) {
                            return decodeURIComponent(data.date_journal);
                            } */
         } ,            
         ],

        "autoWidth": true,
        "scrollCollapse": true,

        // "order": [[1, 'asc']],
        scrollY:        true,
        scrollX:        true,
        // scrollCollapse: true,
        // "ordering": false,

        fixedHeader:true,
      //   fixedHeader: {
            // header: true,
            // footer: true
                //      },
                    
        "destroy": true,
        // order: [[ 5, "desc" ]],
        fixedColumns:   {
            leftColumns: 7
        },
           dom: 'Bfrtip',
        buttons: [
{

              extend: 'excel', 
              text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export to Excel',
              className: 'btn-primary',
        //title: 'Any title for file',
                message: "Periode "+from_+" \n",
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
