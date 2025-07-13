<?php 

if (empty($_SESSION['username'])) { header("location:../../index.php"); }



# START CEK HAK AKSES KEMBALI

$rs=mysql_fetch_array(mysql_query("select * from userpassword where username='$user' "));

$akses = $rs['approval'];

$kode_mkt = $rs['kode_mkt'];

$jabatan = $rs['Groupp'];

if ($akses=="0") 

{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }

# END CEK HAK AKSES KEMBALI

$jenis_company = flookup("jenis_company","mastercompany","company!=''");

?>

<script>

  function App_RN()

  { var strn = document.getElementsByClassName("cboapprn");

    var ntrn = document.getElementsByClassName("txtnotesrn");

    var idrn = document.getElementsByClassName("txtidrn");

    for (var i = 0; i < strn.length; i++) 

    { if (strn[i].value !== '')

      { jQuery.ajax

        ({  url: 'ajax_app_dev.php?modeajax=RN',

            method: 'POST',

            data: {rnnya: idrn[i].value, stnya: strn[i].value, ntnya: ntrn[i].value},

            dataType: 'json',

            error: 

            function (request, status, error) 

            { if (request.responseText !== '') { alert(request.responseText); } 

            },

        });

      }

    }

    alert('Data Saved');

    window.location.reload();

  };

</script>

<?php 

# COPAS EDIT

# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama

# END COPAS VALIDASI

# COPAS ADD

?>

<?php if ($mod=="sampledev1") {?>

<div class="box">

  <div class="box-header">

    <h3 class="box-title">Outstanding List Sample Development</h3>

  </div>

  <div class="box-body">

    <table id="examplefix" class="display responsive" style="width:100%">

      <thead>

      <tr>

        <th>No</th>

        <th>Sample #</th>

        <th>Sample Date</th>

        <th>Buyer</th>

        <?php if($jenis_company=="VENDOR LG") { ?>

          <th>Model</th>

        <?php } else { ?>

          <th>Style #</th>

        <?php } ?>

        <th>Delv. Date</th>

        <th>Total Cost</th>

        <th>Created By</th>

        <th width='10%'>Action</th>

      </tr>

      </thead>

      <tbody>

        <?php

        # QUERY TABLE

        if ($nm_company=="PT. Bintang Mandiri Hanafindo") {$fld_gr="";} else {$fld_gr="and kode_mkt='$kode_mkt'";}

        $query = mysql_query("select a.id,cost_no,cost_date,supplier,styleno,

          deldate,fullname from act_development a inner join act_development_mat acm on a.id=acm.id_act_cost 

          inner join mastersupplier f on 

          a.id_buyer=f.id_supplier inner join userpassword up on a.username=up.username 

          where  app1='W' group by a.cost_no"); 



        if (!$query) {die(mysql_error());}

        $no = 1; 



        while($rs = mysql_fetch_array($query))

        { $id=$data['id'];

          $tot_mat = flookup("sum((price*cons)+((price*cons)*allowance/100))","act_development_mat","id_act_cost='$rs[id]'");

          $tot_mfg = flookup("sum((price*cons)+((price*cons)*allowance/100))","act_development_mfg","id_act_cost='$rs[id]'");

          $tot_oth = flookup("sum(price)","act_development_oth","id_act_cost='$rs[id]'");

          $tot_cost = $tot_mat + $tot_mfg + $tot_oth;

          $tot_cost = fn($tot_cost,2);

          echo "<tr>";

            echo "<td>$no</td>"; 

            echo "

              <td>$rs[cost_no]</td>

              <td>".fd_view($rs['cost_date'])."</td>

              <td>$rs[supplier]</td>

              <td>$rs[styleno]</td>

              <td>".fd_view($rs['deldate'])."</td>

              <td>$tot_cost</td>

              <td>$rs[fullname]</td>

              ";

//			  <a class='btn btn-primary btn-s' href='../marketting/pdfCost.php?id=$rs[id]'

  //              data-toggle='tooltip' title='Save PDF' target='_blank'><i class='fa fa-file-pdf-o'></i></a>

            echo "

            <td>

              

              <a class='btn btn-info btn-s' href='app_act_dev.php?mod=$mod&trx=CS&id=$rs[id]'

                data-toggle='tooltip' title='Approve'><i class='fa fa-check-circle-o'></i></a>

            </td>";

          echo "</tr>";

          $no++; // menambah nilai nomor urut

        }

        ?>

      </tbody>

    </table>

  </div>

</div>

<?php } ?>

<?php if ($mod=="sampledev2") {?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Outstanding List Purchase Request Development</h3>
  </div>
  <div class="box-body">
    <table id="examplefix2" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <th>Buyer</th>
        <th>Job Order #</th>
        <th>Job Order Date</th>
		<th>Style #</th>
        <th>Created By</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select a.id,a.jo_no,a.jo_date,fullname,msup.supplier,ac.styleno 
          from jo_dev a inner join bom_dev_jo_item s on a.id=s.id_jo
          inner join jo_det_dev jod on a.id=jod.id_jo
          inner join so_dev so on jod.id_so=so.id inner join act_development ac on so.id_cost=ac.id 
          inner join mastersupplier msup on ac.id_buyer=msup.id_supplier 
          inner join userpassword up on a.username=up.username where a.app='W' group by a.jo_no"); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
            echo "<td>$no</td>"; 
            echo "<td>$data[supplier]</td>";
            echo "<td>$data[jo_no]</td>";
            echo "<td>".fd_view($data['jo_date'])."</td>";
            echo "<td>$data[styleno]</td>";
            echo "<td>$data[fullname]</td>";
//              <a class='btn btn-primary btn-s' href='../marketting/pdfBOM.php?id=$data[id]'
   //             data-toggle='tooltip' title='Save PDF' target='_blank'><i class='fa fa-file-pdf-o'></i></a>

            echo "
            <td>
              <a class='btn btn-info btn-s' href='app_act_dev.php?mod=$mod&trx=JO&id=$data[id]'
                data-toggle='tooltip' title='Approve'><i class='fa fa-check-circle-o'></i></a>
            </td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>
<?php if ($mod=="sampledev3") {?>

<div class="box">

  <div class="box-header">

    <h3 class="box-title">Outstanding List Purchase Order Development</h3>

  </div>

  <div class="box-body">

    <table id="examplefix3" class="display responsive" style="width:100%">

      <thead>

      <tr>

        <th>No</th>

        <th>PO #</th>

        <th>Rev</th>

        <th>PO Date</th>

        <th>Supplier</th>

        <th>P.Terms</th>

        <th>Buyer</th>

        <th>WS #</th>

        <th>Style #</th>

        <th>Action</th>

      </tr>

      </thead>

      <tbody>

        <?php

        # QUERY TABLE

        $sql="select tmppoit.kpno,tmppoit.styleno,a.revise,a.jenis,a.app,a.id,pono,podate,supplier,

          nama_pterms,tmppoit.buyer from po_header_dev a inner join 

          mastersupplier s on a.id_supplier=s.id_supplier inner join 

          masterpterms d on a.id_terms=d.id

          inner join 

          (select ac.kpno,ac.styleno,poit.id_jo,poit.id_po,ms.supplier buyer from po_item_dev poit 

          inner join jo_det_dev jod on jod.id_jo=poit.id_jo 

          inner join so_dev so on jod.id_so=so.id

          inner join act_development ac on so.id_cost=ac.id 

          inner join mastersupplier ms on ac.id_buyer=ms.id_supplier

          where poit.cancel='N' 

          group by poit.id_po) tmppoit 

          on tmppoit.id_po=a.id where a.app='W' and jenis in ('M','P') 

          union all 

          select '','',a.revise,a.jenis,a.app,a.id,pono,podate,supplier,

          nama_pterms,tmppoit.buyer from po_header_dev a inner join 

          mastersupplier s on a.id_supplier=s.id_supplier inner join 

          masterpterms d on a.id_terms=d.id

          inner join 

          (select poit.id_jo,poit.id_po,'' buyer from po_item_dev poit 

          inner join reqnon_header_dev reqnonh on reqnonh.id=poit.id_jo 

          where poit.cancel='N' 

          group by poit.id_po) tmppoit 

          on tmppoit.id_po=a.id where a.app='W' and jenis='N'";

        $query = mysql_query($sql);

        $no = 1; 

        while($data = mysql_fetch_array($query))

        { echo "<tr>";

            echo "<td>$no</td>"; 

            echo "<td>$data[pono]</td>";

            echo "<td>$data[revise]</td>";

            echo "<td>".fd_view($data['podate'])."</td>";

            echo "<td>$data[supplier]</td>";

            echo "<td>$data[nama_pterms]</td>";

            echo "<td>$data[buyer]</td>";

            echo "<td>$data[kpno]</td>";

            echo "<td>$data[styleno]</td>";

            if($data['jenis']=="N") {$nmpdf="pdfPOG";} else {$nmpdf="pdfPO";}

            echo "

            <td>

              <a class='btn btn-primary btn-s' href='../pur/$nmpdf.php?id=$data[id]'

                data-toggle='tooltip' title='Preview' target='_blank'><i class='fa fa-file-pdf-o'></i></a>

              <a class='btn btn-info btn-s' href='app_act_dev.php?mod=$mod&trx=PO&id=$data[id]'

                data-toggle='tooltip' title='Approve'><i class='fa fa-check-circle-o'></i></a>

            </td>";

          echo "</tr>";

          $no++; // menambah nilai nomor urut

        }

        ?>

      </tbody>

    </table>

  </div>

</div>

<?php } ?>

<?php if ($mod=="5") {?>

<div class="box">

  <div class="box-header">

    <h3 class="box-title">Outstanding List Genereal Request</h3>

  </div>

  <div class="box-body">

    <table id="examplefix3" class="display responsive" style="width:100%">

      <thead>

      <tr>

        <th>Req #</th>

        <th>Req Date</th>

        <th>Description Goods</th>

        <th>Notes</th>

        <th>Created By</th>

        <th>Created Date</th>

        <th>Approve By</th>

        <th>Approve By2</th>

        <th>Status</th>

        <th>Notes</th>

        <th></th>

        <th></th>

      </tr>

      </thead>

      <tbody>

        <?php

        # QUERY TABLE

        $query = mysql_query("select a.*,GROUP_CONCAT(DISTINCT mi.itemdesc) list_item from 

          reqnon_header_dev a inner join reqnon_item_dev s on a.id=s.id_reqno 

          inner join masteritem mi on s.id_item=mi.id_item where s.cancel='N' and 

          (((a.app='W' or a.app='R') and a.app_by='$user') or ((a.app2='W' or a.app2='R') and a.app_by2='$user')) group by reqno"); 

        $no = 1; 

        while($data = mysql_fetch_array($query))

        { echo "<tr>";

            echo '

            <td>'.$data["reqno"].'</td>

            <td>'.fd_view($data["reqdate"]).'</td>

            <td>'.$data["list_item"].'</td>

            <td>'.$data["notes"].'</td>

            <td>'.$data["username"].'</td>

            <td>'.fd_view_dt($data["dateinput"]).'</td>

            <td>'.$data['app_by']." (".$data["app"].')'.'</td>

            <td>'.$data['app_by2']." (".$data["app2"].')'.'</td>';

            echo "

            <td>

              <select name='cboapprn$data[id]' id='cboapprn$data[id]' class='cboapprn'>";

                $sql="select left(nama_pilihan,1) isi,nama_pilihan tampil from masterpilihan where kode_pilihan='Jen_App'";

                IsiCombo($sql,'','Status');

              echo "

              </select>

            </td>

            <td>

              <input type='text' size='25' name='txtnotesrn$data[id]' id='txtnotesrn$data[id]' class='txtnotesrn'>

              <input type='hidden' name='txtidrn$data[id]' id='txtidrn$data[id]' class='txtidrn' value='$data[id]'>

            </td>";

            echo "

            <td>

              <a href='../others/pdfReq.php?id=$data[id]'

                data-toggle='tooltip' title='Preview' target='_blank'><i class='fa fa-file-pdf-o'></i></a>

            </td>

            <td></td>";

            // <td>

            //   <a href='app_act.php?mod=$mod&trx=RN&id=$data[id]'

            //     data-toggle='tooltip' title='Approve'><i class='fa fa-check-circle-o'></i></a>

            // </td>";

          echo "</tr>";

          $no++; // menambah nilai nomor urut

        }

        ?>

      </tbody>

    </table>

    <br>

    <button class='btn btn-primary' onclick='App_RN()'>Simpan</button>

  </div>

</div>

<?php } ?>

<?php if ($mod=="6") {?>

<div class="box">

  <div class="box-header">

    <h3 class="box-title">Outstanding List Permintaan TK</h3>

  </div>

  <div class="box-body">

    <table id="examplefix3" class="display responsive" style="width:100%">

      <thead>

      <tr>

        <th>Diajukan Oleh</th>

        <th>NIK</th>

        <th>Department</th>

        <th>Bagian</th>

        <th>Rencana Kebutuhan</th>

        <th>Bagian</th>

        <th>Tanggal</th>

        <th>Jumlah</th>

        <th>Created Date</th>

        <th>Created By</th>

        <th>Approval</th>

        <th>Action</th>

      </tr>

      </thead>

      <tbody>

        <?php

        # QUERY TABLE

        $cricek="(setuju1='$user' and setuju1_app='W')";

        $cricek=$cricek." or (setuju2='$user' and setuju2_app='W')";

        $cricek=$cricek." or (setuju3='$user' and setuju3_app='W')";

        $cricek=$cricek." or (ketahui='$user' and ketahui_app='W')";

        $query = mysql_query("select * from 

          form_tenaga_kerja where $cricek "); 

        $no = 1; 

        while($data = mysql_fetch_array($query))

        { $approve=$data["setuju1"]." (".$data["setuju1_app"].")";

          $approve=$approve." ".$data["setuju2"]." (".$data["setuju2_app"].")";

          $approve=$approve." ".$data["setuju3"]." (".$data["setuju3_app"].")";

          $approve=$approve." ".$data["ketahui"]." (".$data["ketahui_app"].")";

          echo "<tr>";

            echo '

            <td>'.$data["do_nama"].'</td>

            <td>'.$data["do_nik"].'</td>

            <td>'.$data["do_department"].'</td>

            <td>'.$data["do_bagian"].'</td>

            <td>'.$data["rk_department"].'</td>

            <td>'.$data["rk_bagian"].'</td>

            <td>'.$data["rk_tanggal"].'</td>

            <td>'.$data["rk_jumlah"].'</td>

            <td>'.$data["dateinput"].'</td>

            <td>'.$data["username"].'</td>

            <td>'.$approve.'</td>';

            echo "

            <td>

              <a class='btn btn-primary btn-s' href='../hr/pdfPTK.php?id=$data[id_tk]'

                data-toggle='tooltip' title='Preview' target='_blank'><i class='fa fa-file-pdf-o'></i></a>

              <a class='btn btn-info btn-s' href='app_act_dev.php?mod=$mod&trx=PTK&id=$data[id_tk]'

                data-toggle='tooltip' title='Approve'><i class='fa fa-check-circle-o'></i></a>

            </td>";

          echo "</tr>";

          $no++; // menambah nilai nomor urut

        }

        ?>

      </tbody>

    </table>

  </div>

</div>

<?php } ?>