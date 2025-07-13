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

        <form   enctype='multipart/form-data' >

          <div class="panel panel-default">

            <div class="panel-heading">Periode Journal</div>

            <div class="panel-body row">

              <div class="col-md-3">

                <div class='form-group'>

                  <label for="period_from">Dari</label>



                  <input type='text' id="period_from" class='form-control datepicker' name='period_from'

                  placeholder='DD/MM/YYYY'  autocomplete="off" >

                </div>

              </div>

              <div class="col-md-3">

                <div class='form-group'>

                  <label>Sampai</label>

                  <input type='text' id="period_to" class='form-control datepicker' name='period_to'

                  placeholder='DD/MM/YYYY' 
                   
                  autocomplete="off" >

                </div>

              </div>

              <div class="col-md-3">

                <div class="col-md-3">
                        <div class="form-group">
                            <a href="#" id="submit" class='btn btn-primary' onclick="getListData()"/>Tampilkan</a>
                        </div>
                    </div>

              </div>

            </div>

          </div>

        </form>
<div class="box list"></div>
       <table rules="all" id="laporan_jurnal" class="" style="width:100%;height:300px;font-size:12px">
          <thead>
           <tr border=1>
            <!-- <th rowspan="2" style="" >No.</th>
 -->            <th colspan="4" style="text-align: center;background-color:#ffffff;border:1px solid #000000">SUPPLIER</th>
            <th rowspan="2">TOP</th>
            <th rowspan="2">PERIODE TAGIHAN</th>
            <th colspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">KONTRABON</th>
            <th colspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">INVOICE</th>
            <th colspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">SURAT JALAN</th>
            <th rowspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">TOTAL RUPIAH</th>
            <th rowspan="2" style="text-align: center;background-color:#ffffff;border:1px solid #000000">KETERANGAN</th>
          </tr>
          <tr>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">COA</th>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">ID</th>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">NAMA</th>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">NAMA ALIAS</th>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">TANGGAL</th>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">NOMOR</th>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">TANGGAL</th>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">NOMOR</th>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">TANGGAL</th>
            <th style="text-align: center;background-color:#ffffff;border:1px solid #000000">NOMOR</th>
          </tr>

        </thead>
      </table>

    </div>

  </div>

</section>

</div>


