<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
$nm_company = $data['company'];
$st_company = $data['status_company'];

$id_item="";

# COPAS EDIT

# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function calc_konv()
  {
    var qtyr = document.form.getElementsByClassName('jmlclass');
    var qtyrf = document.form.getElementsByClassName('jmlfclass');
    var qtyk = document.form.getElementsByClassName('jmlkclass');
    var strkonv = document.form.txtkonv.value;
    var konv = strkonv.split("|");
    var konvcri = konv[0];
    var konvnya = konv[1];
    var totdet = 0;
    var totdetf = 0;
    if (isNaN(konvnya)) { konvnya = 1; }
    for (var i = 0; i < qtyr.length; i++) 
    { 
      if (konvcri == 'Kali')
      { jmlkon = qtyr[i].value * konvnya; }
      else
      { jmlkon = qtyr[i].value / konvnya; }
      qtyk[i].value = jmlkon;
      totdet = totdet + Number(qtyr[i].value);
      totdetf = totdetf + Number(qtyrf[i].value);
    }
    $('#txtqtydetact').val(totdet);
    $('#txtqtydetfoc').val(totdetf);  
  };
  function choose_rak(id_h)
  { var html = $.ajax
    ({  type: "POST",
        url: 'ajax_bpb_jo.php?modeajax=view_list_rak_loc_trx_new',
        data: {id_h: id_h},
        async: false
    }).responseText;
    if(html)
    {  
      $("#detail_rak").html(html);
    }
    $(document).ready(function() {
      var table = $('#examplefix').DataTable
      ({  sorting: false,
          searching: false,
          paging: false,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });
  };
//ADYZ================================================================================================
  function choose_barcode(id_h)
  { var html = $.ajax
    ({  type: "POST",
        url: 'ajax_bpb_jo.php?modeajax=view_list_barcode',
        data: {id_h: id_h},
        async: false
    }).responseText;
    if(html)
    {  
      $("#detail_barcode").html(html);
    }
    $(document).ready(function() {
      var table = $('#examplefixbarcode').DataTable
      ({  sorting: false,
          searching: false,
          paging: false,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });
  };
  //----------------------------------------------------------------------------------------------------
  function validasi()
  {
    var bpbno = document.form.cbosj.value;
    var jmlroll = document.form.txtroll.value;
    var satdet = document.form.txtunitdet.value;
    var jmlbpb = document.form.txtqtybpbact.value;
    var jmldet = 0;
    var rakkos = 0;
    var qtyr = document.form.getElementsByClassName('jmlclass');
    var nmrak = document.form.getElementsByClassName('rakclass');
    var jmlbpbfoc = document.form.txtqtybpbfoc.value;
    var jmldetfoc = 0;
    var qtyrfoc = document.form.getElementsByClassName('jmlfclass');

    for (var i = 0; i < qtyr.length; i++) 
    { 
      jmldet=jmldet+Number(qtyr[i].value);
      if (nmrak[i].value == '') { rakkos = rakkos + 1; }
    }

    for (var i = 0; i < qtyrfoc.length; i++) 
    { 
      jmldetfoc=jmldetfoc+Number(qtyrfoc[i].value);
    }

    if (bpbno == '') { swal({ title: 'No. BPB Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false; }
    else if (jmlroll == '') { swal({ title: 'Jumlah Detail Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false;}
    else if (satdet == '') { swal({ title: 'Unit Detail Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false;}
    else if (Number(rakkos) > 0) { swal({ title: 'Rak Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false;}
    else if (Number(parseFloat(jmldet).toFixed(2)) != Number(parseFloat(jmlbpb).toFixed(2))) { swal({ title: 'Jumlah Detail ('+parseFloat(jmldet).toFixed(2)+') Actual ('+parseFloat(jmlbpb).toFixed(2)+')Tidak Sesuai', <?php echo $img_alert; ?> });valid = false;}
    else if (Number(jmldetfoc) != Number(jmlbpbfoc)) { swal({ title: 'Jumlah Detail FOC Tidak Sesuai', <?php echo $img_alert; ?> });valid = false;}
    else {valid = true;}
    return valid;
    exit;
  };
  function getQtyBPB(cri_item)
  { jQuery.ajax
    ({  url: 'ajax2_roll.php?modeajax=cari_qty_bpb',
        method: 'POST',
        data: {cri_item: cri_item},
        dataType: 'json',
        success: function(response)
        { $('#txtqtybpb').val(response[0]);
          $('#txtqtybpbact').val(response[0]);
          $('#txtunit').val(response[2]);
          $('#txtunitkonv').val(response[3]);
          $('#txtqtykonv').val(response[4]);
          $('#txtqtykonvact').val(response[4]);
          $('#txtkonv').val(response[5]);
          $('#txtpono').val(response[6]);
          $('#txtsupplier').val(response[7]);
          $('#txtitem').val(response[8]);
          $('#txtws').val(response[9]);  
        },
        error: function (request, status, error) {
            alert(request.responseText);
        },
    });
  };
  function getListData()
  { var cri_item = document.form.txtroll.value;
    var sat_nya = document.form.txtunitdet.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax2_roll.php?modeajax=view_list_roll',
        data: {cri_item: cri_item,sat_nya: sat_nya},
        async: false
    }).responseText;
    if(html)
    {  
      $("#detail_item").html(html);
    }
    $(".select2").select2();
  };
  function getListBPB()
  { var cri_item = document.form.txttglcut.value;
    var mat_nya = document.form.txtmat.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax2_roll.php?modeajax=cari_list_bpb',
        data: {cri_item: cri_item,mat_nya: mat_nya},
        async: false
    }).responseText;
    if(html)
    {
        $("#cbosj").html(html);
    }
  };
</script>

<!--ADYZ ========================================================================== -->
<div class="modal fade" id="myRak"  tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" style="overflow-y:auto;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pilih Detail</h4>
      </div>
      <div class="modal-body" style="overflow-y:auto; height:500px;">
        <div id='detail_rak'></div>    
      </div>
     
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
    </div>
  </div>
</div>


<div class="modal fade" id="myBarcode"  tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" style="overflow-y:auto;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Detail Roll dan Gradasi Warna</h4>
      </div>
      <div class="modal-body" style="overflow-y:auto; height:500px;">
        <div id='detail_barcode'></div>    
      </div>
      <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
    </div>
  </div>
</div>
<!-- ---------------------------------------------------------------------------->


<?php 
# END COPAS VALIDASI
# COPAS ADD
if($mod=="18sb")

{
  echo "<br>"; 
  echo "<div class='box'>";
    echo "<div class='box-body'>";
      echo "<div class='row'>";
        echo "<form method='post' name='form' action='save_data_bpb_roll_gradasi.php?mod=$mod&mode=$mode&id=$id_item' onsubmit='return validasi()'>";
       
        $sql1="SELECT DISTINCT brh.bpbno,bpb.bpbno_int,bpb.dateinput,mi.goods_code,mi.itemdesc,ac.kpno,ms.Supplier
        FROM bpb_roll_h brh INNER JOIN bpb ON bpb.bpbno=brh.bpbno
          INNER JOIN masteritem AS mi ON brh.id_item=mi.id_item
        INNER JOIN jo_det AS jd ON jd.id_jo=brh.id_jo
        INNER JOIN so  ON so.id=jd.id_so
        INNER JOIN act_costing AS ac ON ac.id=so.id_cost
        INNER JOIN mastersupplier AS ms ON ms.Id_Supplier=ac.id_buyer
        WHERE brh.id='43925' ";
    //echo ($sql);		
    $queryHD=mysql_query($sql1);	
    while($dataHD=mysql_fetch_array($queryHD))
    {
      echo '
      <p>Berikut ini adalah BPB  : <b>'.$dataHD['bpbno'].'</b></p>
      <table id="simple-table" class="table table-no-bordered">
        <tr><td>No. BPB</td><td> : '.$dataHD['bpbno_int'].'</td><td>Konsumen</td><td> : '.$dataHD['Supplier'].'</td><td>Jenis Kain</td><td> : '.$dataHD['itemdesc'].'</td></tr>
        <tr><td>Tgl. BPB</td> <td> : '.$dataHD['dateinput'].'</td><td>No WS</td><td> : '.$dataHD['kpno'].'</td><td>Kode Kain</td><td> : '.$dataHD['goods_code'].'</td></tr>			
      </table>
      ';
    }
    echo "<table id='examplefixbarcode' width='100%' style='font-size:18px'; border='1'; >";
		echo "
			<thead>		
			   <tr>
			    <th>#</th>
				  <th>Barcode</th>
					<th>Lot #</th>
					<th>Qty</th>
					<th>Qty FOC</th>
					<th>QTY USED</th>
					<th>Unit</th>
					<th>Rak # Loc</th>
          <th>Gradasi</th>
				</tr>
			</thead>
			
			<tbody>";

			$sql="select DISTINCT br.id,br.id_h,brh.id_item,brh.id_jo,roll_no,lot_no,roll_qty,roll_foc,br.unit,
				concat(mr.kode_rak,' ',mr.nama_rak) raknya,concat(mrold.kode_rak,' ',mrold.nama_rak) raknyaold,
				br.barcode,roll_qty_used from bpb_roll br inner join 
				bpb_roll_h brh on br.id_h=brh.id 
				left join master_rak mr on br.id_rak_loc=mr.id 
				left join master_rak mrold on br.id_rak=mrold.id
				where 
				brh.id='43925' 
				order by br.id";
			//echo $sql;
			$i=1;
			$query=mysql_query($sql);
			$total = 0;
			while($data=mysql_fetch_array($query))
			{	$total = $total	+ $data['roll_qty'] + $data['roll_foc'];
				echo "
					<tr>
						<td style='text-align:center' >$data[roll_no]</td>
						<td style='text-align:center' >$data[id]</td>
						<td style='text-align:center'>$data[lot_no]</td>
						<td style='text-align:right'>$data[roll_qty]</td>
						<td style='text-align:right'>$data[roll_foc]</td>
						<td style='text-align:right' >$data[roll_qty_used]</td>
						<td style='text-align:center'>$data[unit]</td>
						<td style='text-align:left'>$data[raknya]</td>
            <td><input type='text' class='form-control' id='idgradasi' name='idgradasi' > </input></td>
					</tr>";
				$i++;
			};
	echo "</tbody>
	      </table>";
              
        echo "<br>";
         echo "<div class='col-md-3'>";
            echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
            echo "           ";
            echo "<a href='../forms/?mod=18z' class='btn btn-primary btn'>Batal </a>";
         echo "</div>";
        echo "</form>";
      echo "</div>";
    echo "</div>";
  echo "</div>";
}

else
{ ?>
  <div class="box">
   
   <div class="box-header">
   <br>
    <h3 class="box-title">Tampilkan Data BPB</h3>
    </div>
   
    <div class="box-body">
      <table id="examplefix3" class="display responsive" style="width:100%;font-size:12px;">
        <thead>
          <tr>
            <th>BPB #</th>
            <th>NOSJ #</th>
            <th>WS #</th>
            <th>Item</th>
            <th>Deskripsi</th>       
            <th>Tgl Input</th>
            <th></th>     
            <!--ADYZ ========================================================================== -->     
            <th></th>
            <th></th>
            <!------------------------------------------------------------------------------------>

          </tr>
        </thead>
        <tbody>
          <?php
          # QUERY TABLE
          $sql="SELECT brh.dateinput,brh.location,bpb.invno,brh.id,brh.bpbno,ac.kpno,mi.goods_code,mi.itemdesc
            FROM bpb_roll_h brh inner join bpb_roll br on brh.id=br.id_h 
            INNER JOIN BPB ON BPB.bpbno=brh.bpbno
            inner join masteritem mi on brh.id_item=mi.id_item 
            inner join jo_det jod on brh.id_jo=jod.id_jo 
            inner join jo on jod.id_jo=jo.id  
            inner join so on jod.id_so=so.id 
            inner join act_costing ac on so.id_cost=ac.id 
            where brh.dateinput >= '2021-05-01' 
            group by brh.id order by brh.dateinput desc ";
          $query = mysql_query($sql);
         // echo $sql;
          while($data = mysql_fetch_array($query))
          { $bpbno_int=flookup("bpbno_int","bpb","bpbno='$data[bpbno]'");
            echo "
            <tr>";
              if($bpbno_int!="")
              { echo "<td>$bpbno_int</td>"; }
              else
              { echo "<td>$data[bpbno]</td>"; }
              echo "
              <td>$data[invno]</td>
              <td>$data[kpno]</td>
              <td>$data[goods_code]</td>
              <td>$data[itemdesc]</td>
              <td>".fd_view($data["dateinput"])."</td>";
              echo "
              <td>
                <button type='button' class='btn btn-primary' data-toggle='modal' 
                  data-target='#myRak' onclick='choose_rak($data[id])'>Detail</button>
              </td>                                  
              <td>
              <a href='../forms/?mod=18sb&id=$data[id]' class='btn btn-primary btn-s'>Gradasi
              </td>
              <td>
               <a href='pdfBarcode.php?mode=barcode&id=$data[id]'
                data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i></a>
             </td> 
            </button>
            </td>
            </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
<?php }

//  <button type='button' class='btn btn-primary' data-toggle='modal' 
//data-target='#myBarcode' onclick='choose_barcode($data[id])'>Gradasi
//</button>
# END COPAS ADD
?>