<?php
include_once "../../../include/conn.php";

if (empty($_SESSION['username'])) { header("location:../../../index.php"); }
$user=$_SESSION['username'];

$sesi=$_SESSION['sesi'];

# CEK HAK AKSES KEMBALI

$akses = flookup("F_L_AP_RIN_KON_BON","userpassword","username='$user'");

if ($akses=="0") 

{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }

?>

<div class="container-fluid">

  <section class="content">

    <div class="box">

      <div class="box-body">

        <form method='get' name='form' enctype='multipart/form-data' action="">

          <div class="panel panel-default">

            <div class="panel-heading">Periode Journal</div>

            <div class="panel-body row">

              <div class="col-md-3">

                <div class='form-group'>

                  <label for="period_from">Dari</label>



                  <input type='text' id="datepicker1" class='form-control datepicker' name='period_from'

                  placeholder='DD/MM/YYYY' value='<?=isset($period_from)?$period_from:''?>' autocomplete="off" >

                </div>

              </div>

              <div class="col-md-3">

                <div class='form-group'>

                  <label>Sampai</label>

                  <input type='text' id="datepicker2" class='form-control datepicker' name='period_to'

                  placeholder='DD/MM/YYYY' value='<?=isset($period_to)?$period_to:''?>' autocomplete="off" >

                </div>

              </div>

              <div class="col-md-3">

                <div class="form-group"> 

                  <input type="hidden" name="mod" value="rikontrabon" />
                  <label>&nbsp;</label><br>
                  <button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>

                </div>

              </div>

            </div>

          </div>

        </form>
<div class="clearfix"></div>




        <table id="examplefix" class="display responsive" style="width:100%;">
          <thead>
           <tr>
            <th rowspan="2" style="" >No.</th>
            <th colspan="4" style="text-align: center;">KONSUMEN</th>
            <th rowspan="2">TOP</th>
            <th rowspan="2">PERIODE TAGIHAN</th>
            <th colspan="2" style="text-align: center;">KONTRABON</th>
            <th colspan="2" style="text-align: center;">INVOICE</th>
            <th colspan="2" style="text-align: center;">SURAT JALAN</th>
            <th rowspan="2">TOTAL RUPIAH</th>
            <th rowspan="2">KETERANGAN</th>
          </tr>
          <tr>
            <th>COA</th>
            <th>ID</th>
            <th>NAMA</th>
            <th>NAMA ALIAS</th>
            <th>TANGGAL</th>
            <th>NOMOR</th>
            <th>TANGGAL</th>
            <th>NOMOR</th>
            <th>TANGGAL</th>
            <th>NOMOR</th>
          </tr>

        </thead>

        <tbody>

          <?php

          if (isset($_GET['submit'])){

            $from = $_GET['period_from'];

            $from = str_replace('/', '-', $from);

            $from = date("Y-m-d", strtotime($from));
           

            $to = $_GET['period_to'];

            $to = str_replace('/', '-', $to);  

            $to = date("Y-m-d", strtotime($to));   





         

            $sql = "SELECT DISTINCT mc.post_to
            , ms.Id_Supplier
            , ms.Supplier
            , ms.supplier_code
            , mpt.days_pterms  
            , mpt.nama_pterms
            , fjh.period
            , fjh.date_journal
            , fjh.id_journal
            -- , fjh.d_invoice 
            , ifnull(fjh.d_invoice,'%d/%m/%Y')
            , fjh.inv_supplier
            , fjd.amount_original
            , b.bpbdate
            , b.invno

            FROM fin_journal_h fjh
            LEFT JOIN fin_journal_d fjd ON fjd.id_journal=fjh.id_journal
            LEFT JOIN mastercoa mc ON mc.id_coa=fjd.id_coa
            LEFT JOIN bpb b ON b.bpbno_int=fjd.reff_doc
            LEFT JOIN jo j ON j.id=b.id_jo
            LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.id_supplier
            LEFT JOIN po_header poh ON poh.pono=b.pono
            LEFT JOIN masterpterms mpt ON mpt.id=poh.id_terms
            WHERE fjh.type_journal = '14' AND fjh.fg_post = '2' AND ms.tipe_sup='S' AND fjh.date_journal >= '".$from."' and fjh.date_journal <= '".$to."' ORDER BY fjh.date_journal DESC"; 
        //  echo $sql;  
            $query = mysql_query($sql);   

            $no = 1;

            while($data = mysql_fetch_array($query))
            {

              echo "<tr>";

              echo "<td>$no</td>";
              echo "<td>$data[post_to]</td>";
              echo "<td>$data[Id_Supplier]</td>";
              echo "<td>$data[Supplier]</td>";
              echo "<td>$data[supplier_code]</td>";
              //echo "<td>$data[days_pterms] days, $data[nama_pterms]</td>";
              echo "<td>$data[nama_pterms]</td>";
              echo "<td>$data[period]</td>";
              echo "<td>".fd_view($data['date_journal'])."</td>";
              echo "<td>$data[id_journal]</td>";
              echo "<td>".fd_view($data['d_invoice'])."</td>";
              echo "<td>$data[inv_supplier]</td>";
              echo "<td>".fd_view($data['bpbdate'])."</td>";
              echo "<td>$data[invno]</td>";
              echo "<td>".(number_format("$data[amount_original]"))."</td>";
              echo "<td>-</td>";
              // echo "<td>-</td>";
              echo "</tr>";

              $no++;
            }
          }

          ?>
        </tbody>

      </table>

    </div>

  </div>

</section>

</div>









