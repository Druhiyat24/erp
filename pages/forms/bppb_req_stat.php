<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("req_mat","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

if (isset($_GET['id'])) {$id_req=$_GET['id'];} else {$id_req="";}
if (isset($_GET['mode'])) {$mode=$_GET['mode'];} else {$mode="";}

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $st_company = $rscomp["status_company"];
  $harus_bpb = $rscomp["req_harus_bpb"];
  $logo_company = $rscomp["logo_company"];
$id_item="";
if($mod=="61rvp")
{
  $nmpdf="pdfPickList.php";
}
else
{
  $nmpdf="pdfPickListReq.php";
}
# COPAS EDIT
  $frdate=date("d M Y");
  $kedate=date("d M Y");

  $tglf=date("d M Y");
  $tglt=date("d M Y");

  $dtf=date("d M Y");
  $dtt=date("d M Y");

  $perf=date("d M Y");
  $pert=date("d M Y");

  if (isset($_POST['submit']))
  {
    $excel="N";
    $tglf = fd($_POST['frdate']);
	  $perf = date('d M Y', strtotime($tglf));
	  $tglt = fd($_POST['kedate']);
	  $pert = date('d M Y', strtotime($tglt));

  }
  

  if (isset($_GET['flg']))
  {
    $excel="Y";
    $tglf = $_GET['parfrom'];
	  $perf = date('d M Y', strtotime($tglf));

	  $tglt = $_GET['parto'];
	  $pert = date('d M Y', strtotime($tglt));

  }

  /*
  if (isset($_GET['parfromv']))
 {
    $tglf = $_GET['parfrom'];
	  $perf = date('d M Y', strtotime($tglf));

	  $tglt = $_GET['parto'];
	  $pert = date('d M Y', strtotime($tglt));

  }
  elseif (isset($_POST['submit']))
  {
    $tglf = fd($_POST['frdate']);
	  $perf = date('d M Y', strtotime($tglf));
	  $tglt = fd($_POST['kedate']);
	  $pert = date('d M Y', strtotime($tglt));

    $dtf = fd($_POST['frdate']);
    $dtt = fd($_POST['kedate']);

  }
*/
if ($id_req=="")
{ $reqno="";
  
}
else
{ $cekbppb=flookup("count(*)","bppb","bppbno_req='$id_req'");
  if($cekbppb<>"0")
  {
    $_SESSION['msg']="XRequest # Sudah Ada Pengeluaran";
    echo "
    <script>
      window.location.href='?mod=1';
    </script>";
  }          
 
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  { 
    valid = true;
    return valid;
    exit;
  }
</script>
<?php
# END COPAS VALIDASI

# COPAS ADD
?>
<script type="text/javascript">
  function getJO()
  { var id_jo = $('#cboJO').val();
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax3.php?modeajax=view_list_stock',
        data: {id_jo: id_jo},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
  $(document).ready(function() {
      var table = $('#examplefix2').DataTable
      ({  scrollCollapse: true,
          paging: false,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });
  };
</script>
<?php 
if ($mod=="61rs" and $excel=="N") { ?>
<div class='box'>
  <div class='box-body'>
    <div class='row'>
    <form action="" method="post">
  <!--  <form method='post' name='form' action='s_bppb_req.php?mod=<?php echo $mod; ?>&id=<?php echo $id_req; ?>' onsubmit='return validasi()'>-->
       <div class='col-md-4'>

          <div class='col-md-5'>                            
                <label>From Date (RQ) : </label>
                <input type='text' class='form-control' id='datepicker1' name='frdate' placeholder='Masukkan From Date' value='<?php echo $perf;?>' >
             
          </div>

          <div class='col-md-5'>
                 <label>To Date (RQ) : </label>
                 <input type='text' class='form-control' id='datepicker2' name='kedate' placeholder='Masukkan To Date' value='<?php echo $pert;?>' >
          </div> 

        <div class='col-md-3'>
          <div>
          <br>
              <button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>              
          </div>         
        </div>
      </form>
    </div>
  </div>
</div><?php } 




# END COPAS ADD
#if ($id_req=="") {

if ($mod=="61rs" or $mod=="61rvp") {
?>
  <div class="box-body">
    <table id="examplefix3" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <th>Request #</th>
        <th>Request Date</th>
        <th>Buyer</th>
        <th>Style #</th>
        <th>WS #</th>
        <th>Sent To</th>
        <th>Created By</th>
        <th>No. BPPB</th>
        <th></th>
        <th></th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select a.username,bppbno,bppbdate,s.supplier,ac.kpno,ac.styleno,ms.supplier buyer  
          from bppb_req a inner join mastersupplier s on a.id_supplier=s.id_supplier 
          inner join jo_det jod on a.id_jo=jod.id_jo 
          inner join so on jod.id_so=so.id 
          inner join act_costing ac on so.id_cost=ac.id 
          inner join mastersupplier ms on ac.id_buyer=ms.id_supplier  
          where a.bppbdate >='$tglf' and a.bppbdate <='$tglt'
          group by bppbno order by bppbdate desc "); 
         
          $no = 1; 
        while($data = mysql_fetch_array($query))
        { $cekbppb=flookup("if(bppbno_int!='',bppbno_int,bppbno)","bppb","bppbno_req='$data[bppbno]'");
          echo "<tr>";
            echo "
            <td>$no</td>
            <td>$data[bppbno]</td>
            <td>$data[bppbdate]</td>
            <td>$data[buyer]</td>
            <td>$data[styleno]</td>
            <td>$data[kpno]</td>
            <td>$data[supplier]</td>
            <td>$data[username]</td>
            <td>$cekbppb</td>";
            if($cekbppb=="")
            {
              echo "
              <td>
                <a href='?mod=61re&mode=Bahan_Baku&id=$data[bppbno]'
                  data-toggle='tooltip' title='View'><i class='fa fa-eye'></i></a>
              </td>";
            }
            else
            {
              echo "<td></td>";
            }
          
            echo "
            <td>
              <a href='$nmpdf?id=$data[bppbno]'
                data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i></a>
            </td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
        </table>
    <?php
      IF ($excel=="N")
      {
        echo "<a class='btn btn-info btn-sm' href='?mod=61rs&flg=1&parfrom=$tglf&parto=$tglt&parfromv=$perf&dest=xls'>Save To XLS</a> ";
        $excel="Y" ;
      }
    
 ?>
  </div>
</div>

</div>
<?php } ?>