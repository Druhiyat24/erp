<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>laporan aging&jt-ar bulanan</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <link rel="stylesheet" href="/media/css/site.css?_=c863b7da7e72b0e94c16b81c38293467">
	
		<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css">

  </head>
  <body>
    
  <div class="container">
    <div class="row" style="margin-top:75px;">
    
      <div class="col-md-2">
        <div class="input-group">
              From date          
         <input type="date" class="form-control" name="from date"  id="from_date">
        </div>
            
      </div>
                     
        
      <div class="col-md-2">
        <div class="input-group">
              To Date         
         <input type="date" class="form-control" name="to_date" id="to_date">
        </div>
      </div> 
          
      <div class="col-md-8">
        <div class="input-group">
             &nbsp;
        </div>
      </div>
           
     </div> 


   </div>   
    <div class="container">
      <div class="row" >
        <div class="col-md-2">
          <div class="input-group">
          <br>
          <button id="submit" class="btn btn-primary"  >submit</button>
          </div><!-- /input-group -->
            <br> 
    </div>
     
    <div class="col-md-8">
      <div class="input-group">
           &nbsp;
      </div>
     </div>
           
    </div> 
          
      
   </div> 

<div id="rincian-mutasi"class="container" style="overflow: scroll;height:400px;text-align:center;">       
<form style="text-align:left;">
	<label name="pt" id="pt">PT </label><br /> 

</form>
   <p style="text-align:left;">PERINCIAN MUTASI PIUTANG DAGANG</p>
   <p style="text-align:left;">Periode : <input type="text" ="" id="periode" value="1/8/2019 S/D 31/8/2019"></p>
   <table id="table-rincian-mutasi" border="1" class="table table-bordered">
    <thead>
  
    <tbody>
        <tr>
            <th rowspan="3">NO</th>
            <th colspan="3">KONSUMEN</th>
            <th colspan="2">SURAT JALAN</th>
            <th colspan="2">INVOICE</th>
            <th colspan="2">KONTRABON</th>
            <th colspan="2">FAKTUR PAJAK</th>
            <th rowspan="3">NOMOR SALES ORDER</th>
            <th rowspan="3">ID ORDER</th>
            <th rowspan="3">TOP</th>
            <th colspan="3">SALDO AWAL</th>
            <th colspan="6">PENAMBAHAN</th>
            <th colspan="12">PENGURANGAN</th>
            <th colspan="3">SALDO AKHIR</th>
        </tr>
        <tr>
            <th rowspan="2">COA</th>
            <th rowspan="2">ID</th>
            <th rowspan="2">NAMA</th>
            <th rowspan="2">NO</th>
            <th rowspan="2">TANGGAL</th>
            <th rowspan="2">NO</th>
            <th rowspan="2">TANGGAL</th>
            <th rowspan="2">NO</th>
            <th rowspan="2">TANGGAL</th>
            <th rowspan="2">NO</th>
            <th rowspan="2">TANGGAL</th>
            <th rowspan="2">US$</th>
            <th rowspan="2">KURS</th>
            <th rowspan="2">IDR</th>
            <th colspan="3">PENJUALAN</th>
            <th colspan="3">LAIN-LAIN</th>
            <th rowspan="2">REF DOKUMEN</th>
            <th rowspan="2">TANGGAL</th>
            <th rowspan="2">KET</th>
            <th colspan="3">PELUNASAN</th>
            <th colspan="3">RETUR/POT</th>
            <th colspan="3">LAIN-LAIN</th>
            <th rowspan="2">US$</th>
            <th rowspan="2">KURS</th>
            <th rowspan="2">IDR</th>
          </tr>
            <tr>
              <th>US$</th>
              <th>KURS</th>
              <th>IDR</th>
              <th>US$</th>
              <th>KURS</th>
              <th>IDR</th>
              <th>US$</th>
              <th>KURS</th>
              <th>IDR</th>
              <th>US$</th>
              <th>KURS</th>
              <th>IDR</th>
              <th>US$</th>
              <th>KURS</th>
              <th>IDR</th>
            </tr>
          <tr>
            <td class="no">1</td><td class="coa"></td><td class="id"></td><td class="nama">admin</td>
            <td class="no_jalan">1</td>
            <td class="tgl_jalan">3/8/2019</td>
            <td class="no_inv"></td>
            <td class="tgl_inv"></td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
          </tr>
          <tr>
            <td class="no">1</td><td class="coa"></td><td class="id"></td><td class="nama">admin</td>
            <td class="no_jalan">1</td>
            <td class="tgl_jalan">3/8/2019</td>
            <td class="no_inv"></td>
            <td class="tgl_inv"></td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
          </tr>
          <tr>
            <td class="no">1</td><td class="coa"></td><td class="id"></td><td class="nama">admin</td>
            <td class="no_jalan">1</td>
            <td class="tgl_jalan">3/8/2019</td>
            <td class="no_inv"></td>
            <td class="tgl_inv"></td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
          </tr>
          <tr>
            <td class="no">1</td><td class="coa"></td><td class="id"></td><td class="nama">admin</td>
            <td class="no_jalan">1</td>
            <td class="tgl_jalan">3/8/2019</td>
            <td class="no_inv"></td>
            <td class="tgl_inv"></td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
          </tr>
          <tr>
            <td class="no">1</td><td class="coa"></td><td class="id"></td><td class="nama">admin</td>
            <td class="no_jalan">1</td>
            <td class="tgl_jalan">3/8/2019</td>
            <td class="no_inv"></td>
            <td class="tgl_inv"></td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
          </tr>
          <tr>
            <td class="no">1</td><td class="coa"></td><td class="id"></td><td class="nama">admin</td>
            <td class="no_jalan">1</td>
            <td class="tgl_jalan">3/8/2019</td>
            <td class="no_inv"></td>
            <td class="tgl_inv"></td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
          </tr>
          <tr>
            <td class="no">1</td><td class="coa"></td><td class="id"></td><td class="nama">admin</td>
            <td class="no_jalan">1</td>
            <td class="tgl_jalan">3/8/2019</td>
            <td class="no_inv"></td>
            <td class="tgl_inv"></td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
            <td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
          </tr>
    </tbody>
 </table>
   
<!-- DivTable.com -->
 </div>  
 
   




    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    
    <script>
    $(document).ready( function () {
        $('#example').DataTable();
    } );
    </script>
   
  </body>
</html>