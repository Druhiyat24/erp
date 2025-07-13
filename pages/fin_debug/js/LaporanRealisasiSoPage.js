$( document ).ready(function() {
    localStorage.clear();
    sessionStorage.clear();
    

    
        $("#period_from").datepicker( {
      format: "dd MM yyyy",

      autoclose: true
    });   
    
/*  $("#period_from").datepicker( {
        format: "mm/yyyy",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true
    }); */
    
    $("#period_to").datepicker( {
      format: "dd MM yyyy",

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

    function getId_Coa(myCoas){
        $.ajax({        
        type:"POST",
        cache:false,
        url:"webservices/getAkunIdCoa.php", 
        data : { code : '1', type:"Laporan", typeidcoa: myCoas },     // multiple data sent using ajax
        success: function (response) {
            //console.log(response);
            data = response.split("<-|->");
                d = JSON.parse(data[0]);
                //d = response;
                option  = '';
                renders = '';
            //  console.log(d.records.length);
                if(d.message == '1'){
                    //  $("#render").append(data[1]);
                    //  console.log(d.records);
                        for(i=0;i<d.records.length;i++){
                            option += "<option value="+decodeURIComponent(d.records[i][0].id)+">"+decodeURIComponent(d.records[i][0].id) +" | "+decodeURIComponent(d.records[i][0].nama)+"</option>";
                        }//department
                        $("#idcoa").append(option);

                setTimeout(function(){              

                }, 3000);                       
                        
                        
                }
                if(d.message == '2'){
                    alert("Input Tanggal Salah !")
                }
        }
      });   
    
    
}   


function Back(){
    overlayon();
    location.reload(true);
}
function getListData(){
    from = $("#period_from").val();
    to = $("#period_to").val();
    if(!from){
        alert("Periode From Harus Diisi");
        return false;
    }
    if(!to){
        alert("Periode To Harus Diisi");
    }   
    $(".list").css("display","");
    //alert(CoaId);
    GenerateTable(from,to);
    dumptitle=$( "#idcoa option:selected" ).text();
    split = dumptitle.split("|");   
    
    
    
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
CoaId = '';
function getidcoa(idcoa){
    CoaId = idcoa;
    //.alert(CoaId);
}
function GenerateTable(from_,to_){

    table = $('#laporan_jurnal').DataTable( {
      'processing': true,
      'serverSide': true,
      'orderable': false,
      'columnDefs': [ {
        'targets': [11,10,9,8,7,6,5,4,3,2,1,0],
        'orderable': false, // set orderable false for selected columns
     }],
     'aoColumnDefs': [
        { 'bSortable': false, 'aTargets': [ 11,10,9,8,7,6,5,4,3,2,1,0] }
    ],
       "lengthMenu" : [[10, 25, 50, 999999999], [10, 25, 50, "All"]],
        'serverMethod': 'post',
        
        //"ajax": "webservices/getDataLaporanTrialBalance.php?from="+from+"&to="+to,
        "ajax":  "webservices/getDataLaporanRealisasiSo.php?from="+from_+"&to="+to_,
        "columns": [
        //date_journal
       {
      "data":           "so_no",
        } ,
    //id_coa
        {
      "data":           "so_date",
        } , 
        //Supplier
        {
            "data":           "deldate_det",
        } , 
        //kode_pterms
        {
      "data":           "revise",
         } ,     
    //saldo_awal_idr
        {
      "data":           "supplier_code",
         } ,       
    //pembelian_idr
        {
      "data":           "Supplier",
         } ,    
    //lain_lain_idr
        {
      "data":           "goods_code",
         } ,
    //jumlah_idr_beli
        {
      "data":           "color_size",
         } ,     
    //retur_idr
        {
      "data":           "itemdesc",
         } ,
         {
      "data":           "unit",
         } ,
         {
      "data":           "qty_so",
         } ,
         {
      "data":           "price",
         } ,
        {
      "data":           "total_price",
         } ,
        {
      "data":           "bppbno_int",
         } ,    
          {
      "data":           "bppbdate",
         } ,
          {
      "data":           "id_order",
         } ,
          {
      "data":           "qty_surat_jalan",
         } , 
         {
      "data":           "total_qty",
         } , 
         {
      "data":           "total_price_sj",
                         className:"right"
         } , 

      //    {
      // "data":           "outstanding_qty",
      //    } ,    


        ],
        "autoWidth": true,
        "scrollCollapse": true,
        "destroy":true,
        scrollY:        true,
        scrollX:        true,
        // scrollCollapse: true,
        
        // "destroy": true,
        //order: [[ 1, "asc" ]],
        // "ordering": false,
  //       fixedColumns:   {
  //           leftColumns: 7
  //       },
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
