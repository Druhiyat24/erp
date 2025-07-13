<?PHP
$user = $_SESSION["username"];
$st_company = flookup("status_company", "mastercompany", "company!=''");
// $cek_expired = flookup("notif_expired", "userpassword", "username='$user'");
// if ($_SESSION["first"] == "Y" and $cek_expired == "1") {
//   $last_ann_fee = flookup("last_annual_fee", "mastercompany", "company!=''");
//   $tgl_val_ori = date_create($last_ann_fee);
//   date_add($tgl_val_ori, date_interval_create_from_date_string("-30 days"));
//   $tgl_val = date_format($tgl_val_ori, "Y-m-d");
//   $tgl_skrg = date("Y-m-d");
//   if ($tgl_skrg >= $tgl_val) {
//     if ($tgl_skrg >= $last_ann_fee) {
//       $_SESSION["expired"] = "Y";
//       $msgtext = "Hosting Sudah Expired Sejak " . date_format(date_create($last_ann_fee), "d-M-Y");
//       echo "<script>swal({ title: '$msgtext', imageUrl: '../../images/error.jpg' });</script>";
//     } else {
//       $_SESSION["expired"] = "N";
//       $msgtext = "Hosting Akan Expired Pada " . date_format(date_create($last_ann_fee), "d-M-Y");
//       echo "<script>swal({ title: '$msgtext', imageUrl: '../../images/warning.jpg' });</script>";
//     }
//   }
//   $_SESSION["first"] = "N";
// }
if ($st_company == "") {
  echo "<script>
		alert('Status Company Tidak Ditemukan');
		window.location.href='../../index.php';
	</script>";
}
$user = $_SESSION['username'];
$bln = date('m');
$thn = date('Y');
if ($st_company != "MULTI_WHS") {
  $query = mysql_query("select jenis_dok,count(distinct bcno) jml_dok,count(distinct bpbno) jml_trans from bpb where 
    month(bpbdate)=$bln and year(bpbdate)=$thn
    and jenis_dok not in ('INHOUSE','','-') group by  jenis_dok");
  $jml_bc20 = 0;
  $jml_bclkl = 0;
  $jml_bc24 = 0;
  $jml_bcjuallkl = 0;
  $jml_bc23 = 0;
  $jml_bc40 = 0;
  $jml_bc27 = 0;
  $jml_bc262 = 0;
  while ($data = mysql_fetch_array($query)) {
    if ($data['jenis_dok'] == "BC 2.3") {
      $jml_bc23 = $data['jml_dok'];
    } else if ($data['jenis_dok'] == "BC 2.0") {
      $jml_bc20 = $data['jml_dok'];
    } else if ($data['jenis_dok'] == "LOKAL") {
      $jml_bclkl = $data['jml_trans'];
    } else if ($data['jenis_dok'] == "BC 4.0") {
      $jml_bc40 = $data['jml_dok'];
    } else if ($data['jenis_dok'] == "BC 2.7") {
      $jml_bc27 = $data['jml_dok'];
    } else if ($data['jenis_dok'] == "BC 2.6.2") {
      $jml_bc262 = $data['jml_dok'];
    }
  }

  $query = mysql_query("select jenis_dok,count(distinct bcno) jml_dok from bppb where 
     month(bppbdate)=$bln and year(bppbdate)=$thn
  and jenis_dok not in ('INHOUSE','','-') group by  jenis_dok");
  $jml_bc30 = 0;
  $jml_bc41 = 0;
  $jml_bc27out = 0;
  $jml_bc261 = 0;
  $jml_bc25 = 0;
  while ($data = mysql_fetch_array($query)) {
    if ($data['jenis_dok'] == "BC 3.0") {
      $jml_bc30 = $data['jml_dok'];
    } else if ($data['jenis_dok'] == "BC 2.4") {
      $jml_bc24 = $data['jml_dok'];
    } else if ($data['jenis_dok'] == "JUAL LOKAL") {
      $jml_bcjuallkl = $data['jml_dok'];
    } else if ($data['jenis_dok'] == "BC 4.1") {
      $jml_bc41 = $data['jml_dok'];
    } else if ($data['jenis_dok'] == "BC 2.7") {
      $jml_bc27out = $data['jml_dok'];
    } else if ($data['jenis_dok'] == "BC 2.6.1") {
      $jml_bc261 = $data['jml_dok'];
    } else if ($data['jenis_dok'] == "BC 2.5") {
      $jml_bc25 = $data['jml_dok'];
    }
  }
}

$fullname1 = flookup("fullname", "userpassword", "username='$user'");
$fullname = ucwords(strtolower($fullname1));
?>

<!-- <div class="box">
  <div class="box-header">
    <h3 class="box-title"><?php echo "Hai $fullname,"; ?></h3>
    <br>
    <h3 class="box-title"><?php echo $c1; ?></h3>
  </div>
  <div class="box-body">
    <div class="row">
      <?php
      if ($st_company == "KITE") {
        echo "
    <div class='col-lg-3 col-xs-6'>
      <div class='small-box bg-yellow'>
        <div class='inner'>
          <h3>$jml_bc20<p>$c25</p></h3><h4>BC 2.0</h4>
        </div>
      </div>
    </div>";
        echo "
    <div class='col-lg-3 col-xs-6'>
      <div class='small-box bg-green'>
        <div class='inner'>
          <h3>$jml_bclkl<p>Pembelian</p></h3><h4>LOKAL</h4>
        </div>
      </div>
    </div>";
      } else if ($st_company == "GB") {
        echo "
    <div class='col-lg-3 col-xs-6'>
      <div class='small-box bg-yellow'>
        <div class='inner'>
          <h3>$jml_bc23<p>$c25</p></h3><h4>BC 2.3</h4>
        </div>
      </div>
    </div>";
        echo "
    <div class='col-lg-3 col-xs-6'>
      <div class='small-box bg-aqua'>
        <div class='inner'>
          <h3>$jml_bc27<p>$c25</p></h3><h4>BC 2.7</h4>
        </div>
      </div>
    </div>";
      } else if ($st_company == "MULTI_WHS") {
        $query = mysql_query("select supplier,sum(qty) jml_qty from bpb a inner join mastersupplier s 
      on a.id_gudang=s.id_supplier where 
      month(bpbdate)=$bln and year(bpbdate)=$thn
      and tipe_sup='G' group by  supplier");
        while ($data = mysql_fetch_array($query)) {
          echo "
        <div class='col-lg-3 col-xs-6'>
          <div class='small-box bg-yellow'>
            <div class='inner'>
              <h3>$data[jml_qty]<p>Qty Terima</p></h3><h4>$data[supplier]</h4>
            </div>
          </div>
        </div>";
        }
      } else {
        echo "
    <div class='col-lg-3 col-xs-6'>
      <div class='small-box bg-yellow'>
        <div class='inner'>
          <h3>$jml_bc23<p>$c25</p></h3><h4>BC 2.3</h4>
        </div>
      </div>
    </div>";
        echo "
    <div class='col-lg-3 col-xs-6'>
      <div class='small-box bg-blue'>
        <div class='inner'>
          <h3>$jml_bc40<p>$c25</p></h3><h4>BC 4.0</h4>
        </div>
      </div>
    </div>";
        echo "
    <div class='col-lg-3 col-xs-6'>
      <div class='small-box bg-aqua'>
        <div class='inner'>
          <h3>$jml_bc27<p>$c25</p></h3><h4>BC 2.7</h4>
        </div>
      </div>
    </div>";
        echo "
    <div class='col-lg-3 col-xs-6'>
      <div class='small-box bg-green'>
        <div class='inner'>
          <h3>$jml_bc262<p>$c25</p></h3><h4>BC 2.6.</h4>
        </div>
      </div>
    </div>";
      }
      ?>
    </div>
  </div>
</div>
<div class="box">
  <div class="box-header">
    <h3 class="box-title"><?php echo $c2; ?></h3>
  </div>
  <div class="box-body">
    <div class="row">
      <?php
      if ($st_company == "KITE") {
        echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-yellow'>
          <div class='inner'>
            <h3>$jml_bc30<p>$c25</p></h3><h4>BC 3.0</h4>
          </div>
        </div>
      </div>";
        echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-blue'>
          <div class='inner'>
            <h3>$jml_bc24<p>$c25</p></h3><h4>BC 2.4</h4>
          </div>
        </div>
      </div>";
        echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-green'>
          <div class='inner'>
            <h3>$jml_bcjuallkl<p>Penjualan</p></h3><h4>LOKAL</h4>
          </div>
        </div>
      </div>";
      } else if ($st_company == "GB") {
        echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-yellow'>
          <div class='inner'>
            <h3>$jml_bc30<p>$c25</p></h3><h4>BC 3.0</h4>
          </div>
        </div>
      </div>";
        echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-aqua'>
          <div class='inner'>
            <h3>$jml_bc27out<p>$c25</p></h3><h4>BC 2.7</h4>
          </div>
        </div>
      </div>";
      } else if ($st_company == "MULTI_WHS") {
      } else {
        echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-yellow'>
          <div class='inner'>
            <h3>$jml_bc30<p>$c25</p></h3><h4>BC 3.0</h4>
          </div>
        </div>
      </div>";
        echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-blue'>
          <div class='inner'>
            <h3>$jml_bc41<p>$c25</p></h3><h4>BC 4.1</h4>
          </div>
        </div>
      </div>";
        echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-aqua'>
          <div class='inner'>
            <h3>$jml_bc27out<p>$c25</p></h3><h4>BC 2.7</h4>
          </div>
        </div>
      </div>";
        echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-green'>
          <div class='inner'>
            <h3>$jml_bc261<p>$c25</p></h3><h4>BC 2.6.1</h4>
          </div>
        </div>
      </div>";
      }
      if ($st_company != "MULTI_WHS" and $st_company != "KITE") {
        echo "
      <div class='col-lg-3 col-xs-6'>
        <div class='small-box bg-purple'>
          <div class='inner'>
            <h3>$jml_bc25<p>$c25</p></h3><h4>BC 2.5</h4>
          </div>
        </div>
      </div>";
      }
      ?>



    </div>
  </div>
</div> -->


<div class="box" style="height: 510px;width: auto;background-color: grey;border-radius:10px;">
  <div class="box-body">
    <div class="row">

      <div class='col-lg-7 col-xs-6'>
        <div class="card-body" style="border-radius:10px;">
          <table id="" class="display responsive bg-blue" style="width: 100%;font-size:14px;text-align:center;border-radius:10px;">
            <thead>
              <tr style="line-height: 30px;">
                <th width='20%' style="text-align:center;background-color: black;">Kode Rak</th>
                <th width='30%' style="text-align:center;background-color: black;">Nama Rak</th>
                <th width='35%' style="text-align:center;background-color: black;">Balance Kapasitas Rak</th>
                <th width='15%' style="text-align:center;background-color: black;">Persentase</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <div class="card-body table-responsive p-0 " style="height: 450px;">
            <table id="dashboard1" class="display responsive" style="width: 100%;font-size:13px;text-align:center;color:white;">
              <thead>
                <!-- <tr>
              <th>Kode Rak</th>
              <th>Nama Rak</th>
              <th>Tanggal Penerimaan</th>
              <th>Progres pemakaian rak</th>
              <th>Persentase</th>
            </tr> -->
              </thead>
              <tbody>

                <?php
                # QUERY TABLE
                //     $query = mysql_query("select kode_rak,nama_rak,date_input,kapasitas,COALESCE(round(qty,2),0) qty,round((COALESCE(qty,0) / kapasitas * 100),2) persen from (select * from (select kode_rak,nama_rak, kapasitas from m_rak) a left join
                // (select kode_rak koderak,count(roll_qty) as qty, max(date_input) date_input from in_material_det where cancel = 'N' group by kode_rak) b on a.kode_rak = b.koderak) a order by a.kode_rak asc");
                $query = mysql_query("SELECT rak kode_rak, 
nama_rak, 
coalesce(max(a.kapasitas),0) kapasitas, 
count(rak) terisi,
coalesce(max(a.kapasitas),0) - count(rak)  sisa,
round((count(rak)/ max(a.kapasitas)) * 100,2) persen
FROM `upload_rak` a
inner join master_rak mr on a.rak = mr.kode_rak
group by rak");
                $no = 1;
                $supplier = null;
                $sizeStartRow = 0;
                $counter = 0;
                while ($data = mysql_fetch_array($query)) {
                  $kode_rak = $data[kode_rak];
                  // $dateinput = $data[date_input];
                  // if ($dateinput != '') {
                  //   $tglinput = fd_view($data[date_input]);
                  // } else {
                  //   $tglinput = '-';
                  // }

                  if ($data['persen'] == "0") {
                    $col = "";
                    $col = "#008000";
                    $col2 = "black";
                    $width = $data['persen'];
                  } else if ($data['persen'] > "0" && $data['persen'] <= '25') {
                    $col = "#32CD32";
                    $col2 = "white";
                    $width = $data['persen'];
                  } else if ($data['persen'] > "25" && $data['persen'] <= '50') {
                    $col = "#FFD700";
                    $col2 = "white";
                    $width = $data['persen'];
                  } else if ($data['persen'] > "50" && $data['persen'] <= '75') {
                    $col = "#FF8C00";
                    $col2 = "white";
                    $width = $data['persen'];
                  } else if ($data['persen'] > "75") {
                    $col = "#A52A2A";
                    $col2 = "white";
                    $width = $data['persen'];
                  }

                  echo "<tr style='background-color: black;'>";
                  echo "
            <td width='20%' value = " . $data[kode_rak] . ">$data[kode_rak]</td>
            <td width='30%'>$data[nama_rak]</td>
            <td width='35%' style = 'padding-top:30px;'>
            <div class='progress' style='outline: 1px solid  $col;height: 13px;border-radius: 10px'>
            <div class='progress-bar' role='progressbar'  style='width: $width%;background-color:$col	;color: $col2;' aria-valuenow='$width'
             aria-valuemin='0' aria-valuemax='100'></div>
             </div>
            </td>
            <td width='15%'>$data[persen] %</td>";
                  echo "</tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class='col-lg-5 col-xs-6' style="height: 200px;">

        <div class='small-box ' style="height: 200px;border-radius: 10px;background-color: black">
          <div class='inner'>

            <div class="form-group">
              <div class='col-lg-6'>
                <div id="chart_dashboard1" name="chart_dashboard1" style="margin-bottom: -1rem; margin-top: -2rem;">
                </div>
              </div>
              <div id="div_dashboard3" name="div_dashboard3" class='col-lg-6' style="padding-top: 30px;">
                <?php
                # QUERY TABLE
                // $query4 = mysql_query("select kode_rak,nama_rak,date_input,kapasitas,COALESCE(qty,0) qty,(COALESCE(qty,0) / kapasitas * 100) persen , (kapasitas - COALESCE(qty,0)) sisa from (select * from (select kode_rak,nama_rak, kapasitas from m_rak) a left join (select kode_rak koderak,count(roll_qty) as qty, max(date_input) date_input from in_material_det where cancel = 'N' group by kode_rak) b on a.kode_rak = b.koderak) a where a.kode_rak = (select kode_rak from m_rak order by kode_rak asc limit 1)");
                // $data4 = mysql_fetch_array($query4);
                $query4 = mysql_query("SELECT rak kode_rak, 
nama_rak, 
coalesce(max(a.kapasitas),0) kapasitas, 
count(rak) terisi,
coalesce(max(a.kapasitas),0) - count(rak)  sisa,
round((count(rak)/ max(a.kapasitas)) * 100,2) persen
FROM `upload_rak` a
inner join master_rak mr on a.rak = mr.kode_rak
group by rak 
limit 1");
                $data4 = mysql_fetch_array($query4);

                $nama_rak = $data4[nama_rak];
                ?>

                <p class="box-title" style="text-align: center;color:white"><b><?= $nama_rak ?></b></p>
                <table id="dashboard3" class="display responsive" style="width: 100%;font-size:13px;text-align:left;color:white">
                  <thead>
                    <!-- <tr>
              <th>Kode Rak</th>
              <th>Nama Rak</th>
              <th>Tanggal Penerimaan</th>
              <th>Progres pemakaian rak</th>
              <th>Persentase</th>
            </tr> -->
                  </thead>
                  <tbody>

                    <?php
                    # QUERY TABLE
                    // $query3 = mysql_query("select kode_rak,nama_rak,date_input,kapasitas,COALESCE(qty,0) qty,(COALESCE(qty,0) / kapasitas * 100) persen , (kapasitas - COALESCE(qty,0)) sisa from (select * from (select kode_rak,nama_rak, kapasitas from m_rak) a left join (select kode_rak koderak,count(roll_qty) as qty, max(date_input) date_input from in_material_det where cancel = 'N' group by kode_rak) b on a.kode_rak = b.koderak) a where a.kode_rak = (select kode_rak from m_rak order by kode_rak asc limit 1)");
                    $query3 = mysql_query("SELECT rak kode_rak, 
nama_rak, 
coalesce(max(a.kapasitas),0) kapasitas, 
count(rak) terisi,
coalesce(max(a.kapasitas),0) - count(rak)  sisa,
round((count(rak)/ max(a.kapasitas)) * 100,2) persen
FROM `upload_rak` a
inner join master_rak mr on a.rak = mr.kode_rak
group by rak");
                    $no = 1;
                    $supplier = null;
                    $sizeStartRow = 0;
                    $counter = 0;
                    $data3 = mysql_fetch_array($query3);
                    $kode_rak = $data3[kode_rak];

                    echo "<tr class='table-success'>
                            <td width='15%' >Kode Rak</td>
                            <td width='15%' >: $data3[kode_rak]</td>
                          </tr>
                            <tr class='table-success'>
                            <td width='15%' >Kapasitas</td>
                            <td width='15%' >: $data3[kapasitas]</td>
                          </tr>
                          <tr class='table-success'>
                            <td width='15%' >Terpakai</td>
                            <td width='15%' >: $data3[terisi]</td>
                          </tr>
                          <tr class='table-success'>
                            <td width='15%' >Sisa Kapasitas</td>
                            <td width='15%' >: $data3[sisa]</td>
                          </tr>";
                    ?>
                  </tbody>
                </table>
              </div>

            </div>
          </div>
        </div>

        <div class='small-box ' style="height: 260px;border-radius: 10px;background-color: black;">
          <div class='inner'>
            <p class="box-title" style="color: white"><b>List Detail Material</b></p>
            <div id="div_dashboard2" name="div_dashboard2" class="card-body table-responsive p-0" style="height: 200px;">
              <table id="dashboard2" class="display responsive " style="width: 100%;font-size:12px;text-align:center;color:white;">
                <thead>
                  <th width='20%' style="text-align:left;">Buyer</th>
                  <th width='20%' style="text-align:left;">WS</th>
                  <th width='40%' style="text-align:left;">Nama Barang</th>
                  <th width='10%' style="text-align:left;">Lot</th>
                  <th width='10%' style="text-align:center;">Satuan</th>
                </thead>
                <tbody>

                  <?php
                  # QUERY TABLE
                  // $query2 = mysql_query("select a.id,a.kode_rak, b.no_dok,b.tgl_dok,b.supplier, a.lot_no, CONCAT(count(roll_qty),' ','Roll') qty from in_material_det a inner join in_material b on b.id = a.id_in_material where a.kode_rak = (select kode_rak from m_rak order by kode_rak asc limit 1) group by a.id");
                  $query2 = mysql_query("
                  select * from upload_rak");

                  while ($data2 = mysql_fetch_array($query2)) {
                      echo "<tr>";
                      echo "
            <td style='text-align:left;'>$data2[buyer]</td>
            <td style='text-align:left;'>$data2[ws]</td>
            <td style='text-align:left;'>$data2[desc]</td>
            <td style='text-align:left;'>$data2[lot]</td>
            <td style='text-align:center;'>$data2[stock] $data2[satuan]</td>";
                      echo "</tr>";
                    
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>