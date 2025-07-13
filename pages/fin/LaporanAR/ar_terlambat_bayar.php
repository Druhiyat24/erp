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

<div id="ar-terlambat-bayar"class="container" style="overflow:scroll;height:400px;text-align:center;">       
<form style="text-align:left;">
	<label name="pt" id="pt">PT </label><br /> 
    <p style="text-align:left;">LAPORAN PIUTANG DAGANG TERLAMBAT BAYAR</p>
   	<label for="name">PER TGL</label>
	<input name="name" type="text" value="PERTGL" /> 
</form>
  
   <table id="table-ar-terlambat-bayar" border="1" class="table table-bordered">
    <thead>
        <tbody>
         <tr>
            <th rowspan="2">NO</th>
            <th colspan="4">KONSUMEN</th>
            <th rowspan="2">TOP</th>
            <th rowspan="2">PERIODE TAGIHAN</th>
            <th rowspan="2">RUPIAH</th>
            <th rowspan="2">TOTAL RUPIAH</th>
            <th rowspan="2">KETERANGAN</th>
            <th rowspan="2">AR DAYS</th>
         </tr>
            <tr>
            <th>COA</th>
            <th>ID</th>
            <th>NAMA</th>
            <th>NAMA ALIAS</th>
            </tr>
        <tr> 
            <td>1</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
        </tr>  
        <tr> 
            <td>2</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
        </tr>
        <tr> 
            <td>3</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
         </tr>  
         <tr> 
            <td>4</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
         </tr>  
         <tr> 
            <td>5</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
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