<?php
if (empty($_SESSION['username'])) {
  header("location:../../index.php");
}


$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$st_company = $rscomp["status_company"];
$logo_company = $rscomp["logo_company"];
?>
<?php if ($mod == "transfer_memo") {

  if (isset($_POST['submit_excel'])) //KLIK SUBMIT
  {
    $from = date('Y-m-d', strtotime($_POST['txtfrom']));
    $to = date('Y-m-d', strtotime($_POST['txtto']));
    $nama_supp = $_POST['nama_supp'];
    echo "<script>
    window.open ('?mod=exc_transfer_memo&from=$from&to=$to&nama_supp=$nama_supp&dest=xls', '_blank');
    </script>";
  } else {
  }

  if (isset($_POST['submit_cari'])) {
    $from = date('Y-m-d', strtotime($_POST['txtfrom']));
    $to = date('Y-m-d', strtotime($_POST['txtto']));
    $nama_supp = $_POST['nama_supp'];
  }

  ?>


  <div class='box'>
    <div class='box-body'>
      <div class='row'>
        <form method='post' name='form'>

      <!--     <div class='col-md-3'>
            <div class='form-group'>
              <label>Supplier #</label>
              <select class="form-control select2" name="nama_supp" id="nama_supp" data-dropup-auto="false" data-live-search="true" >
                <option value="ALL" <?php if ($nama_supp == "ALL") { echo "selected"; } ?>>ALL</option>                                                 
                <?php
                $data2 ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  $data2 = isset($_POST['nama_supp']) ? $_POST['nama_supp']: null;
                }                 
                $sql = mysql_query("select distinct(Supplier),id_supplier from mastersupplier where tipe_sup = 'S' order by Supplier ASC");
                while ($row = mysql_fetch_array($sql)) {
                  $data = $row['Supplier'];
                  $data2 = $row['id_supplier'];
                  if($row['id_supplier'] == $_POST['nama_supp']){
                    $isSelected = ' selected="selected"';
                  }else{
                    $isSelected = '';

                  }
                  echo '<option value="'.$data2.'"'.$isSelected.'">'. $data .'</option>';    
                }?>
              </select>
            </div>
          </div> -->


          <div class='col-md-2'>
            <div class='form-group'>
              <label>Dari *</label>
              <input type='text' class='form-control' autocomplete='off' id='datepicker1' name='txtfrom' required placeholder='Masukkan Dari Tanggal' value="<?php 
              $txtfrom = isset($_POST['txtfrom']) ? $_POST['txtfrom']: null;            
              if(!empty($_POST['txtfrom'])) {
                echo $_POST['txtfrom'];
              }
              else{
                echo date("d M Y");
              } ?>">
            </div>
          </div>

          <div class='col-md-2'>
            <div class='form-group'>
              <label>Sampai *</label>
              <input type='text' class='form-control' autocomplete='off' id='datepicker2' name='txtto' required placeholder='Masukkan Dari Tanggal' value="<?php 
              $txtto = isset($_POST['txtto']) ? $_POST['txtto']: null;            
              if(!empty($_POST['txtto'])) {
                echo $_POST['txtto'];
              }
              else{
                echo date("d M Y");
              } ?>">
            </div>
          </div>

          <div class='col-md-5'>
            <div class='form-group' style='padding-top:25px'>
              <button type='submit' name='submit_cari' class='btn btn-info'><i class="fa fa-search" aria-hidden="true"></i> Search</button>
              <button type='submit' name='submit_excel' class='btn btn-success'><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export</button>
              <a href='../shp/?mod=create_transfer_memo' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
            </div>
          </div>
        </div>
      </div>

      <table id="examplefix" class="display responsive" style="width:100%">
        <thead>
          <tr>
            <th>No Transfer</th>
            <th>Tgl Transfer</th>
            <th>Keterangan</th>
            <th>User Create</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE

          $sql = "select a.no_trans, tgl_trans, CONCAT(a.created_by,' (',a.created_at,')') create_user, a.status, a.id, upper(IFNULL(a.keterangan,b.keterangan)) keterangan from transfer_memo_exim_h a INNER JOIN transfer_memo_exim_det b on b.no_trans = a.no_trans where tgl_trans BETWEEN '$from' and '$to' GROUP BY a.id";
          
          $query = mysql_query($sql);

          // echo $sql;

          $no = 1;
          while ($data = mysql_fetch_array($query)) {
              $tgl_transfer = date('d M Y', strtotime($data[tgl_trans]));
            
            echo "<tr>";
            echo "<td value= '".$data[no_trans]."'>$data[no_trans]</td>";
            echo "<td>$tgl_transfer</td>";
            echo "<td>$data[keterangan]</td>";
            echo "<td>$data[create_user]</td>";
            echo "<td>$data[status]</td>";
            if ($data[status] == 'CANCEL') {
               echo "<td>-</td>";
            }elseif ($data[status] == 'APPROVED') {
               echo "<td><a href='http://10.10.5.60/ap/module/AP/pdf_transfer_memo.php?doc_number=".$data[no_trans]."' target='_blank'><button type='button' class='btn btn-xs btn-success'><i class='fa fa-print '> Print</i></button></a>
            </td>";
            }else{

            echo "<td><a href='http://10.10.5.60/ap/module/AP/pdf_transfer_memo.php?doc_number=".$data[no_trans]."' target='_blank'><button type='button' class='btn btn-xs btn-success'><i class='fa fa-print '> Print</i></button></a>
            <a id='cancel_trf'><button type='button' class='btn btn-xs btn-danger'><i class='fa fa-trash' aria-hidden='true' > Cancel</i></button></a>
            </td>";
            }
            echo "</tr>";
            $no++;
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</form>
</div>
</div><?php }
