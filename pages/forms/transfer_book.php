<?php 

if (empty($_SESSION['username'])) { header("location:../../index.php"); }



# START CEK HAK AKSES KEMBALI

$mod=$_GET['mod'];

$akses = flookup("transfer_post_app","userpassword","username='$user'");

if ($akses=="0") 

{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }

$tp_need_qc_app=flookup("tp_need_qc_app","mastercompany","company!=''");

$akses_qc_tp_app=flookup("qc_booking_app","userpassword","username='$user'");

# END CEK HAK AKSES KEMBALI

# COPAS EDIT

# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama

?>

<script type='text/javascript'>

  function validasi()

  { var filenya = document.form.txtfile.value;

    if (filenya == '') { swal({ title: 'File Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false;}

    else valid = true;

    return valid;

    exit;

  }

</script>

<?php

# END COPAS VALIDASI

# COPAS ADD

?>

<div class="box">

  <div class="box-header">

    <h3 class="box-title">List Booking Stock</h3>

    <!-- <a href='../pur/?mod=5' class='btn btn-primary btn-s'>

      <i class='fa fa-plus'></i> New

    </a> -->

  </div>

  <div class="box-body">

    <table id="examplefix" class="display responsive" style="width:100%">

      <thead>

      <tr>

        <th>No</th>

        <th>Booking #</th>

        <th>Booking Date</th>

        <!-- <th>Kode Barang</th> -->

        <th>Nama Barang</th>

        <th>From WS</th>
        <th>From Style</th>
        <th>From Buyer</th>

        <th>To WS</th>
        <th>To Style</th>
        <th>To Buyer</th>
        <th>Qty PR</th>

        <th>Qty</th>

        <th>Unit</th>

        <th>Notes</th>
        <th>App WHS</th>
        <th>App QC</th>

        <th></th>

      </tr>

      </thead>

      <tbody>

        <?php

        # QUERY TABLE

        $query = mysql_query("select a.*,acfrom.kpno jo_from,acto.kpno jo_to,s.goods_code,s.itemdesc,
          msfrom.supplier buyer_from,msto.supplier buyer_to,acfrom.styleno style_from,acto.styleno style_to,
          a.id_jo_from,a.id_jo_to,a.id_item,a.user_app,a.user_app_qc,a.tgl_app,a.tgl_app_qc   
          from transfer_post a inner join masteritem s on a.id_item=s.id_item
          inner join jo on jo.id=a.id_jo_from inner join jo jo2 on jo2.id=a.id_jo_to 
          inner join (select id_so,id_jo from jo_det group by id_jo) tmpfrom on tmpfrom.id_jo=a.id_jo_from 
          inner join (select id_so,id_jo from jo_det group by id_jo) tmpto on tmpto.id_jo=a.id_jo_to 
          inner join so so_from on so_from.id=tmpfrom.id_so 
          inner join so so_to on so_to.id=tmpto.id_so 
          inner join act_costing acfrom on so_from.id_cost=acfrom.id 
          inner join act_costing acto on so_to.id_cost=acto.id 
          inner join mastersupplier msfrom on msfrom.id_supplier=acfrom.id_buyer  
          inner join mastersupplier msto on msto.id_supplier=acto.id_buyer  
          where a.cancel='N'"); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { 
          $qty_pr = flookup("round(sum(l.qty*k.cons),2)","bom_jo_item k inner join jo on k.id_jo=jo.id 
            inner join so_det l on k.id_so_det=l.id","k.id_jo='$data[id_jo_to]' and k.id_item='$data[id_item]'");
          $app_whs = $data['user_app']." ".$data['tgl_app'];
          $app_qc = $data['user_app_qc']." ".$data['tgl_app_qc'];
          
          echo "
          <tr>";
            echo "<td>$no</td>"; 
            echo "<td>$data[bookno]</td>";
            echo "<td>".fd_view($data['bookdate'])."</td>";
            // echo "<td>$data[goods_code]</td>";
            echo "<td>$data[itemdesc]</td>";
            echo "
            <td>$data[jo_from]</td>
            <td>$data[style_from]</td>
            <td>$data[buyer_from]</td>";

            echo "
            <td>$data[jo_to]</td>
            <td>$data[style_to]</td>
            <td>$data[buyer_to]</td>
            <td>$qty_pr</td>";

            echo "<td>$data[qty]</td>";

            echo "<td>$data[unit]</td>";

            echo "<td>$data[notes]</td>";
            echo "<td>$app_whs</td>";
            echo "<td>$app_qc</td>";

            if($tp_need_qc_app=="Y")

            { if($data['status_app']=="N" and $data['status_app_qc']=="Y")

              { echo "

                <td><a href='app_tp.php?mod=$mod&id=$data[id]&idd=$data[id_item]'

                  data-toggle='tooltip' title='Approve' ";?> 

                  onclick="return confirm('Apakah Anda Yakin ?')">

                  <?php echo "<i class='fa fa-check'></i>"."</a>

                </td>";

              }

              else if($data['status_app']=="N" and $data['status_app_qc']=="N" and $akses_qc_tp_app=="1")

              { echo "

                <td><a href='app_tp.php?mod=$mod&id=$data[id]&idd=$data[id_item]&app=QC'

                  data-toggle='tooltip' title='QC Approve' ";?> 

                  onclick="return confirm('Apakah Anda Yakin ?')">

                  <?php echo "<i class='fa fa-check'></i>"."</a>

                </td>";

              }

              else

              { echo "<td>Approved</td>";  }

            }

            else

            { if($data['status_app']=="N")

              { echo "

                <td><a href='app_tp.php?mod=$mod&id=$data[id]&idd=$data[id_item]'

                  data-toggle='tooltip' title='Approve' ";?> 

                  onclick="return confirm('Apakah Anda Yakin ?')">

                  <?php echo "<i class='fa fa-check'></i>"."</a>

                </td>";

              }

              else

              { echo "<td>Approved</td>";  }

            }

          echo "

          </tr>";

          $no++; // menambah nilai nomor urut

        }

        ?>

      </tbody>

    </table>

  </div>

</div>

<?php

# END COPAS ADD

?>