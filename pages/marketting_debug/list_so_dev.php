<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
$nm_company = $data['company'];
$st_company = $data['status_company'];

$id_item="";
$from=$_POST['txtfrom'];
$to=$_POST['txtto'];
if (isset($_POST['txtid_buyer'])) {$id_buyer=$_POST['txtid_buyer'];} else {$id_buyer="";}
if (isset($_POST['txtstyle'])) {$styleno=$_POST['txtstyle'];} else {$styleno="";}
# COPAS EDIT

# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama

# END COPAS VALIDASI
?>
<?PHP
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_ws_dev.php?mod=12' onsubmit='return validasi()'>";
        echo "<div class='col-md-12'>";
          echo "<div class='form-group'>";
          ?>
          <table id="examplefix2" class="display responsive" style="width:100%">
            <thead>
            <tr>
              <?php if ($mod=="23v") { ?>
                <th>..</th>
              <?php } ?>
              <th>SO #</th>
              <th>Buyer PO</th>
              <th>WS #</th>
              <th>Cost #</th>
              <th>Buyer</th>
              <th>Product</th>
              <th>Item Name</th>
              <th>Style #</th>
              <th>Qty</th>
              <th>Unit</th>
              <th>Delv Date</th>
            </tr>
            </thead>
            <tbody>
              <?php
              # QUERY TABLE
              if ($mod=="23v")
              {$sql_add=" j.id_so is null and id_buyer='$id_buyer' 
                and styleno='$styleno'";
              }
              else
              {$sql_add="";}
		  
              $sql2="select a.id,so_no,buyerno,kpno,
                cost_no,supplier,product_group,product_item,styleno,
                a.qty,a.unit,
                sod.deldate from so_dev a inner join act_development s on 
                a.id_cost=s.id inner join 
                (select id_so,max(deldate_det) deldate from so_det_dev
                  where cancel='N' and deldate_det between '".fd($from)."' and '".fd($to)."'
                  group by id_so) sod 
                on a.id=sod.id_so
                inner join mastersupplier g on 
                s.id_buyer=g.id_supplier inner join masterproduct h 
                on s.id_product=h.id left join jo_det_dev j on a.id=j.id_so and 'N'=j.cancel 
                where  
                $sql_add ";
              //echo $sql2;
              $query = mysql_query($sql2); 
              if (!$query) {die(mysql_error());}
              $no = 1; 
              while($data = mysql_fetch_array($query))
              { $id=$data['id'];
                echo "<tr>";
                  if ($mod=="23v")
                  {echo "<td><input type ='checkbox' name ='itemchk[$id]' id='chkajax$id' class='chkclass' ></td>";} 
                  echo "<td>$data[so_no]</td>";
                  echo "<td>$data[buyerno]</td>";
                  echo "<td>$data[kpno]</td>";
                  echo "<td>$data[cost_no]</td>";
                  echo "<td>$data[supplier]</td>";
                  echo "<td>$data[product_group]</td>";
                  echo "<td>$data[product_item]</td>";
                  echo "<td>$data[styleno]</td>";
                  echo "<td>$data[qty]</td>";
                  echo "<td>$data[unit]</td>";
                  echo "<td>".fd_view($data['deldate'])."</td>";
                echo "</tr>";
                $no++; // menambah nilai nomor urut
              }
              ?>
            </tbody>
          </table>
          <?php    
          echo "</div>";
        echo "</div>";
        if ($mod=="23v")
        { echo "<div class='col-md-3'>";
            echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
          echo "</div>";
        }
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>