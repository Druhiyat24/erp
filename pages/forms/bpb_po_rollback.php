<?php 

if (empty($_SESSION['username'])) { header("location:../../"); }



$mode = $_GET['mode'];

if (isset($_GET['mod'])) { $mod = $_GET['mod']; } else { $mod = ""; } 

# START CEK HAK AKSES KEMBALI

if($mode=="General")

{ $akses = flookup("bpb_gen","userpassword","username='$user'"); }

else if($mode=="WIP")

{ $akses = flookup("mnuBPBWIP_PO","userpassword","username='$user'"); }

else

{ $akses = flookup("bpb_po","userpassword","username='$user'"); }


$akses_date = flookup("original_date","userpassword","username='$user'"); 


 

if ($akses=="0") 

{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }

# END CEK HAK AKSES KEMBALI



$mod = $_GET['mod'];

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));

  $nm_company = $rscomp["company"];

  $st_company = $rscomp["status_company"];

  $link_to_security = $rscomp["link_to_security"];

  $gr_over_po = $rscomp['gr_over_po'];

  $whs_input_bc_dok = $rscomp['whs_input_bc_dok'];

  $logo_company = $rscomp['logo_company'];

if($link_to_security=="Y") { $read=" readonly"; } else { $read=""; }

if ($nm_company=="PT. Nirwana Alabare Garment")

{ $c_nom_order = "Nomor WS"; }

else

{ $c_nom_order = "Nomor Order"; }

if ($nm_company=="PT. Gaya Indah Kharisma")

{ $sql="update masteritem set matclass=mattype where matclass='-'"; 

  insert_log($sql,$user);

}

if (isset($_GET['noid'])) {$bpbno = $_GET['noid']; } else {$bpbno = "";}

if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

if (isset($_GET['kp'])) {$kp = $_GET['kp'];} else {$kp = "";}



if ($mode=="Bahan_Baku" OR $mode=="Bahan Baku (Import)") 

{ if ($st_company=="GB" OR $st_company=="MULTI_WHS")

  { $titlenya="Barang"; } 

  else

  { $titlenya=$c5; } 

  $filternya="mattype in ('A','F','B')";

}

else if ($mode=="General") 

{ $titlenya="Item General"; 

  $filternya="mattype in ('N','M')";

}

else if ($mode=="Scrap") 

{ $titlenya="Scrap"; 

  $filternya="mattype in ('S','L')";

}

else if ($mode=="Mesin") 

{ $titlenya=$caption[1]; 

  $filternya="mattype in ('M')";

}

else if ($mode=="WIP") 

{ if ($nm_company=="PT. Youngil Leather Indonesia") { $titlenya="Chemical"; } else { $titlenya="WIP"; } 

  $filternya="mattype in ('C')";

}

elseif ($mode=="FG") 

{ $titlenya="Barang Jadi"; 

  $filternya=" ";

}

else

{ echo "<script>

    alert('Terjadi kesalahan');

    window.location.href='index2.php';

  </script>";

}



# COPAS EDIT

if ($bpbno=="" AND $id_item=="")

{ $id_item = "";

  $qty = "";

  $unit = "";

  $curr = "";

  $price = "";

  $remark = "";

  $nomor_rak = "";

  $jam_masuk = "";

  $berat_bersih = "";

  $berat_kotor = "";

  $nomor_mobil = "";

  $pono = "";

  $kpno = "";

  $id_supplier = "";

  $invno = "";

  $bcno = "";

  $bcdate = date('d M Y');

  $bcaju = "";

  $tglaju = date('d M Y');

  $no_fp = "";

  $tglfp = date('d M Y');

  $bpbno = "";

  $bpbdate = date('d M Y');

  $status_kb = "";

  $txttujuan = "";

  $data_oto="N";

  $last_date_bpb = date('d M Y');

}

else if ($bpbno<>"" AND $id_item=="")

{ $id_item = "";

  $qty = "";

  $unit = "";

  $curr = "";

  $price = "";

  $remark = "";

  $nomor_rak = "";

  $jam_masuk = "";

  $berat_bersih = "";

  $berat_kotor = "";

  $nomor_mobil = "";

  $last_date_bpb = "";

  $query = mysql_query("SELECT * FROM bpb where bpbno='$bpbno' ORDER BY id_item ASC");

  $data = mysql_fetch_array($query);

  $pono = $data['pono'];

  $kpno = "";

  $id_supplier = $data['id_supplier'];

  $invno = $data['invno'];

  $bcno = $data['bcno'];

  if ($bcno=="") {  $bcno="-";  }

  if ($data['bcdate']=="0000-00-00")

  { $bcdate = date('d M Y',strtotime($data['bpbdate'])); }

  else

  { $bcdate = date('d M Y',strtotime($data['bcdate'])); }

  $bcaju = $data['nomor_aju'];

  $tglaju = date('d M Y',strtotime($data['tanggal_aju']));

  $no_fp = $data['no_fp'];

  $tglfp = date('d M Y',strtotime($data['tanggal_aju']));

  $bpbno = $data['bpbno'];

  $bpbdate = date('d M Y',strtotime($data['bpbdate']));

  $status_kb = $data['jenis_dok'];

  $txttujuan = $data['tujuan'];

  $data_oto="N";

  $last_date_bpb= date('d M Y',strtotime($data['last_date_bpb']));

}

else

{ $query = mysql_query("SELECT * FROM bpb where bpbno='$bpbno' and id_item='$id_item' and kpno='$kp' ORDER BY id_item ASC");

  $data = mysql_fetch_array($query);

  $id_item = $data['id_item'];

  $qty = $data['qty'];

  $unit = $data['unit'];

  $curr = $data['curr'];

  $price = $data['price'];

  $remark = $data['remark'];

  $nomor_rak = $data['nomor_rak'];

  $jam_masuk = $data['jam_masuk'];

  $berat_bersih = $data['berat_bersih'];

  $berat_kotor = $data['berat_kotor'];

  $nomor_mobil = $data['nomor_mobil'];

  $pono = $data['pono'];

  $kpno = $data['kpno'];

  $id_supplier = $data['id_supplier'];

  $invno = $data['invno'];

  $bcno = $data['bcno'];

  if ($bcno=="") {  $bcno="-";  }

  if ($data['bcdate']=="0000-00-00")

  { $bcdate = date('d M Y',strtotime($data['bpbdate'])); }

  else

  { $bcdate = date('d M Y',strtotime($data['bcdate'])); }

  $bcaju = $data['nomor_aju'];

  $tglaju = date('d M Y',strtotime($data['tanggal_aju']));

  $no_fp = $data['no_fp'];

  $tglfp = date('d M Y',strtotime($data['tgl_fp']));

  $bpbno = $data['bpbno'];

  $bpbdate = date('d M Y',strtotime($data['bpbdate']));

  $status_kb = $data['jenis_dok'];

  $txttujuan = $data['tujuan'];

  if ($data['kpno']!="" AND $data['bcdate']=="0000-00-00" AND $data['jenis_dok']=="INHOUSE")

  { $data_oto="Y"; }

  else

  { $data_oto="N"; }

  $last_date_bpb = date('d M Y',strtotime($data['last_date_bpb']));

}

# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama

echo "

<script type='text/javascript'>

  function validasi()

  { var ponya = document.form.cbopo.value;

    var invno = document.form.txtinvno.value;";

    if($link_to_security=="Y")

    { echo "var id_sec = document.form.txtid_sec.value;"; }

    if($whs_input_bc_dok=="Y")

    { echo "

      var bcno = document.form.txtbcno.value;

      var bcdate = document.form.txtbcdate.value;";

    }

    else

    { echo "

      var bcno = '-';

      var bcdate = '-';";

    }

    echo "

    var bpbdate = document.form.txtbpbdate.value;

    var status_kb = document.form.txtstatus_kb.value;

    var qtyo = document.form.getElementsByClassName('qtyclass');

    var qtyb = document.form.getElementsByClassName('qtybalclass');

    var nrak = document.form.getElementsByClassName('nomrakclass');

    var nodata = 0;

    var dataover = 0;

    var norakkos = 0;

    for (var i = 0; i < qtyo.length; i++) 

    { if (Number(qtyo[i].value) > 0)

      { nodata = nodata + 1; break; }

    }

    for (var i = 0; i < nrak.length; i++) 

    { if (Number(qtyo[i].value) > 0 && nrak[i].value == '')

      { norakkos = norakkos + 1; break; }

    }";

    if($gr_over_po=="N")

    { echo "

      for (var i = 0; i < qtyo.length; i++) 

      { if (Number(qtyo[i].value) > Number(qtyb[i].value))

        { dataover = dataover + 1; break; }

      }";

    }

    echo "

    if (ponya == '') 

    { valid = false; 

      swal({ title: 'PO Tidak Boleh Kosong', $img_alert }); 

    }";

    if($link_to_security=="Y")

    { echo "else if (id_sec == '') 

      { valid = false; 

        swal({ title: 'Id Security Kosong', $img_alert }); 

      }";

    }

    echo "

    else if (nodata == 0) 

    { valid = false; 

      swal({ title: 'Tidak Ada Data', $img_alert }); 

    }";

    // else if (norakkos > 0) 

    // { valid = false; 

    //   swal({ title: 'Nomor Rak Tidak Boleh Kosong', $img_alert }); 

    // }

    echo "else if (new Date(bpbdate) > new Date()) 

    { valid = false;

      swal({ title: 'Tgl. Transaksi Tidak Boleh Melebihi Hari Ini', $img_alert });

    }";

    echo "

    else if (dataover > 0) 

    { valid = false; 

      swal({ title: 'Qty Sudah Melebihi Qty PO', $img_alert }); 

    }

    else if (invno == '') 

    { valid = false; 

      swal({ title: 'Inv # / SJ # Tidak Boleh Kosong', $img_alert }); 

    }

    else if (status_kb == '') 

    { valid = false; 

      swal({ title: 'Jenis Dokumen Tidak Boleh Kosong', $img_alert }); 

    }

    else if (bcno == '' && status_kb !== 'INHOUSE') 

    { valid = false; 

      swal({ title: 'BC # Tidak Boleh Kosong', $img_alert }); 

    }

    else if (bcdate == '' && status_kb !== 'INHOUSE') 

    { valid = false; 

      swal({ title: 'BC Date Tidak Boleh Kosong', $img_alert }); 

    }

    else valid = true;

    return valid;

    exit;

  }

</script>";

# END COPAS VALIDASI

?>



<script type="text/javascript">

  <?php if ($nm_company!="PT. Sinar Gaya Busana") { ?>

    function calc_total()

    { var qtyo = document.form.getElementsByClassName('qtyclass'); 

      var totqty = 0;

      for (var i = 0; i < qtyo.length; i++) 

      { if (Number(qtyo[i].value) > 0)

        { 

          totqty = totqty + Number(qtyo[i].value);

        }

        $('#txtcalc').val(totqty);

      }



    };

    function getTujuan(cri_item)

    {   var html = $.ajax

        ({  type: "POST",

            url: 'ajax.php?modeajax=cari_tujuan',

            data: "cri_item=" +cri_item,

            async: false

        }).responseText;

        if(html)

          { $("#cbotujuan").html(html); }

    };

  <?php } ?>

  // function getListKPNo(cri_item)

  // { var tglfr = document.form.txttglcut.value;

  //   var tglto = document.form.txttglcutto.value;

  //   var html = $.ajax

  //   ({  type: "POST",

  //       <?php 

  //       if($mode=="General")

  //       { echo "url: 'ajax2.php?modeajax=cari_list_po_gen',"; }

  //       else

  //       { echo "url: 'ajax2.php?modeajax=cari_list_po',"; }

  //       ?>

  //       data: {tglfr: tglfr,tglto: tglto},

  //       async: false

  //   }).responseText;

  //   if(html)

  //   { $("#cbopo").html(html); }

  // };

  function getListKPNo(cri_item)

  { var nama_sup = document.form.cbosupfil.value;

    var html = $.ajax

    ({  type: "POST",

        <?php 

        if($mode=="General")

        { echo "url: 'ajax2.php?modeajax=cari_list_po_gen',"; }

        else if($mode=="WIP")

        { echo "url: 'ajax2.php?modeajax=cari_list_po_wip',"; }

        else

        { echo "url: 'ajax2.php?modeajax=cari_list_po',"; }

        ?>

        data: {nama_sup: nama_sup},

        async: false

    }).responseText;

    if(html)

    { $("#cbopo").html(html); }

  };

  function getListData(cri_item)

  { jQuery.ajax

    ({  url: 'ajax3.php?modeajax=copy_po',

        method: 'POST',

        data: {cri_item: cri_item}, 

        success: function(response){

          jQuery('#txtpono').val(response);  

        },

        error: function (request, status, error) 

        { alert(request.responseText); },

    });

    var html = $.ajax

    ({  type: "POST",

        url: 'ajax3.php?modeajax=cari_sup',

        data: "cri_item=" +cri_item,

        async: false

    }).responseText;

    if(html)

    { $("#cbosup").html(html); }

    var html = $.ajax

    ({  type: "POST",

        url: 'ajax3_1.php?modeajax=view_list_po',

        data: "cri_item=" +cri_item,

        async: false

    }).responseText;

    if(html)

    { $("#detail_item").html(html); }

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

    <?php if($link_to_security=="Y") { ?>

    var html = $.ajax

    ({  type: "POST",

        url: 'ajax2.php?modeajax=cari_list_sec',

        data: "cri_item=" +cri_item,

        async: false

    }).responseText;

    if(html)

    { $("#cbosec").html(html); }

    <?php } ?>

  };

  <?php if($link_to_security=="Y") { ?>

  function getListSec(id_sec)

  { jQuery.ajax

    ({  

      url: "ajax3.php?modeajax=cari_data_sec",

      method: 'POST',

      data: {id_sec: id_sec},

      dataType: 'json',

      success: function(response)

      { $('#txtinvno').val(response[0]); 

        $('#txtnomor_mobil').val(response[1]);

        $('#txtjam_masuk').val(response[2]);

      },

      error: function (request, status, error) 

      { alert(request.responseText); },

    }); 

  };

  <?php } ?>  

</script>



<?php

# COPAS ADD

if($mod=="26")

{ echo "<div class='box'>";

    echo "<div class='box-body'>";

      echo "<div class='row'>";

        echo "<form method='post' name='form' action='save_data_bpb_po.php?mod=$mod&mode=$mode&noid=$bpbno&id=$id_item&kp=$kp' onsubmit='return validasi()'>";

          ?>

          <div class='col-md-12'>

            <div class="nav-tabs-custom">

              <ul class="nav nav-tabs">

                <li class="active"><a href="#tab_1" data-toggle="tab">Filter Data</a></li>

                <li><a href="#tab_2" data-toggle="tab">Header</a></li>

                <li><a href="#tab_3" data-toggle="tab">Detail</a></li>

              </ul>

            </div>

            <div class="tab-content">

              <div class="tab-pane active" id="tab_1">

                <div class='col-md-3'>

                  <?php

                  echo "

                  <div class='form-group'>

                    <label>Supplier *</label>";

                    if($mode=="General")

                    { $sql_cri_jen="and s.jenis='N'"; }

                    else if($mode=="WIP")

                    { $sql_cri_jen="and s.jenis='P'"; }

                    else

                    { $sql_cri_jen="and s.jenis='M'"; }

                    $sql = "select a.id_supplier isi,supplier tampil from mastersupplier a  

                      inner join po_header s on a.id_supplier=s.id_supplier  

                      where tipe_sup='S' $sql_cri_jen group by a.id_supplier";

                    echo "<select class='form-control select2' style='width: 100%;' name='cbosupfil' 

                      onchange='getListKPNo(this.value)'>";

                    IsiCombo($sql,'',$cpil.' Supplier');

                    echo "</select>

                  </div>

                  <div class='form-group'>

                    <label>Nomor PO *</label>

                    <select class='form-control select2' style='width: 100%;' name='cbopo' id='cbopo' 

                      onchange='getListData(this.value)'>

                    </select>

                  </div>";

                  if($link_to_security=="Y")

                  { echo "

                    <div class='form-group'>

                      <label>Id Security *</label>

                      <select class='form-control select2' style='width: 100%;' name='txtid_sec' id='cbosec'

                        onchange='getListSec(this.value)'>

                      </select>

                    </div>";

                  }

                  ?>

                </div>

              </div>

              <div class="tab-pane" id="tab_2">

                <div class='col-md-3'>

                  <?php

                  echo "

                  <div class='row'>

                    <div class='col-md-6'>

                      <div class='form-group'>";

                      if ($mod=="51r")

                      { echo "<label>$caption[4]</label>"; }

                      else

                      { echo "<label>$caption[2]</label>"; }

                      echo "<input type='text' class='form-control' name='txtbpbno' placeholder='$cmas $caption[2]' readonly value='$bpbno'>";

                      echo "</div>

                    </div>

                    <div class='col-md-6'>

                      <div class='form-group'>";

                        if ($mod=="51r")

                        { echo "<label>$caption[5] *</label>"; }

                        else

                        { echo "<label>$caption[3] *</label>"; }

                        echo "<input type='text' class='form-control' name='txtbpbdate' id='datepicker2' readonly 

                          value='$bpbdate'>";

                      echo "

                      </div>

                    </div>

                  </div>";

                  echo "

                  <div class='form-group'>

                    <label>Supplier *</label>

                    <select class='form-control select2' style='width: 100%;' id='cbosup' name='txtid_supplier'>

                    </select>

                  </div>

                  <div class='form-group'>

                    <label>$c40 *</label>

                    <input type='text' id='txtpono' class='form-control' name='txtpono' readonly>

                  </div>";

                  ?>

                </div>

                <div class='col-md-3'>

                  <?php

                  echo "

                  <div class='row'>

                    <div class='col-md-6'>

                      <div class='form-group'>";

                        echo "<label>$c36</label>";

                        echo "<input type='text' class='form-control' name='txtjam_masuk' id='txtjam_masuk'  

                          placeholder='$cmas $c36' value='$jam_masuk' $read>";

                      echo "</div>

                    </div>

                    <div class='col-md-6'>

                      <div class='form-group'>";

                        echo "<label>$c39</label>";

                        echo "<input type='text' class='form-control' name='txtnomor_mobil' id='txtnomor_mobil' 

                          placeholder='$cmas $c39' value='$nomor_mobil' $read>";

                      echo "</div>

                    </div>

                  </div>";

                  if ($st_company=="MULTI_WHS")

                  { echo "

                    <div class='form-group'>

                      <label>Gudang *</label>";

                      $sql = "select id_supplier isi,supplier tampil from mastersupplier 

                        where tipe_sup='G' ";

                      echo "<select class='form-control select2' style='width: 100%;' name='txtid_gudang'>";

                      IsiCombo($sql,$id_whs,$cpil.' Gudang');

                      echo "</select>

                    </div>";

                  }

                  echo "

                  <div class='form-group'>

                    <label>$c41 *</label>

                    <input type='text' class='form-control' name='txtinvno' id='txtinvno' 

                      placeholder='$cmas $c41' value='$invno' $read>

                  </div>";

                  if ($st_company!="MULTI_WHS")

                  { echo "

                    <div class='row'>

                      <div class='col-md-6'>

                        <div class='form-group'>

                          <label>$c46 *</label>";

                          if ($st_company=="KITE") 

                          { $status_kb_cri="Status KITE In"; }

                          else

                          { $status_kb_cri="Status KB In"; }

                          $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 

                                kode_pilihan='$status_kb_cri' order by nama_pilihan";

                          echo "<select class='form-control select2' style='width: 100%;' onchange='getTujuan(this.value)' name='txtstatus_kb'>";

                          IsiCombo($sql,$status_kb,$cpil.' '.$c46);

                          echo "</select>

                        </div>

                      </div>

                      <div class='col-md-6'>

                        <div class='form-group'>

                          <label>$c47</label>

                          <select class='form-control select2' style='width: 100%;' id='cbotujuan' name='txttujuan' disabled>";

                          if ($bpbno!="")

                          { $sql = "select nama_pilihan isi,nama_pilihan tampil 

                          from masterpilihan where kode_pilihan='$status_kb'";

                          IsiCombo($sql,trim($txttujuan),$cpil.' '.$c47);

                          }

                          echo "</select>

                        </div>

                      </div>

                    </div>";

                  }

                  ?>

                </div>

                <div class='col-md-3'>

                <?php

                if($whs_input_bc_dok=="Y")

                {

                  echo "

                  <div class='col-md-6'>

                    <div class='form-group'>

                      <label>Nomor Aju</label>

                      <input type='text' class='form-control' name='txtbcaju' placeholder='Masukan Nomor Aju' value='$bcaju'>

                    </div>

                  </div>

                  <div class='col-md-6'>

                    <div class='form-group'>

                      <label>Tanggal Aju</label>

                      <input type='text' class='form-control' id='datepicker3' readonly name='txttglaju' placeholder='Masukan Tanggal Aju' value='$tglaju'>

                    </div>

                  </div>";

                  echo "

                  <div class='col-md-6'>

                    <div class='form-group'>

                      <label>$c42 *</label>

                      <input type='text' class='form-control' name='txtbcno' placeholder='$cmas $c42' value='$bcno'>

                    </div>

                  </div>

                  <div class='col-md-6'>

                    <div class='form-group'>

                      <label>$c43 *</label>

                      <input type='text' class='form-control' id='datepicker1' readonly name='txtbcdate' placeholder='$cmas $c43' value='$bcdate'>

                    </div>

                  </div>

                  <div class='col-md-6'>

                    <div class='form-group'>

                      <label>No. Faktur Pajak</label>

                      <input type='text' class='form-control' name='txtno_fp' placeholder='Masukan Nomor Faktur Pajak' value='$no_fp'>

                    </div>

                  </div>

                  <div class='col-md-6'>

                    <div class='form-group'>

                      <label>Tgl. Faktur Pajak</label>

                      <input type='text' class='form-control' id='datepicker4' readonly name='txttglfp' placeholder='Masukan Tanggal Faktur Pajak' value='$tglfp'>

                    </div>

                  </div>";

                }

                ?>

                </div>

              </div>

              <div class="tab-pane" id="tab_3">

                <div class='col-md-2'>

                  <input type='text' size='5' id='txtcalc' readonly>

                </div>

                <div class='col-md-12'>

                  <p>

                  <div id='detail_item'></div>

                </div>

              </div>

            </div>

          </div>

        <?php

        echo "<div class='col-md-3'>

            <br>

            <button type='submit' name='submit' class='btn btn-primary'>$csim</button>

            <a class='btn btn-success btn-s' href='?mod=$mod&mode=$mode'>Baru</a>

          </div>";

        echo "</form>";

      echo "</div>";

    echo "</div>";

  echo "</div>";

  # END COPAS ADD

}

else if ($mod=="26v"){ 
  include "raw_material_po/HeaderPage.php";
?>

  

<?php } 

?>

