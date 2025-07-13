<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

if ($excel=="N") 
{ echo "<a href='index.php?mod=8&mode=$mode&dest=xls'>Save To Excel</a></br>"; }

$rs=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $nm_company=$rs['company'];
?>

<?PHP
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<table id='example' class='display displayresponsive'>";
      echo "<thead>";
        echo "<tr>";
          echo "<th style='width: 5%;'>No</th>";
          if ($mode=="FG") 
          { echo "<th>Style / Model</th>"; 
            echo "<th>No. Order</th>"; 
          }
          echo "<th>Kategori</th>";
          echo "<th style='width: 10%;'>Kode Barang</th>";
          echo "<th>Nama Barang</th>";
          echo "<th>Warna</th>"; 
          echo "<th>Ukuran</th>";
          if($mode!="FG" and $nm_company=="PT. Sandrafine Garment")
          { echo "<th>Stock Card</th>"; }
          echo "<th>Kode Odo</th>";
          echo "<th>Item Odo</th>";
          echo "<th style='width: 15%;'>Stock</th>";
          echo "<th>Riwayat</th>";
        echo "</tr>";
      echo "</thead>";
      echo "<tfoot>";
        echo "<tr>";
          if ($mode=="FG")
          { echo "<th colspan='7' style='text-align:right'>Total:</th>"; }
          else
          { echo "<th colspan='5' style='text-align:right'>Total:</th>"; }
          echo "<th></th>";
        echo "</tr>";
      echo "</tfoot>";
      echo "<tbody>";
      if ($mode=="FG") 
      { 
        $qry="select styleno,kpno,goods_code,itemname,color,size,stock,a.id_item 
          from stock a inner join masterstyle s on a.mattype='FG'
          and a.id_item=s.id_item where stock<>0";
        $query = mysql_query($qry);
        if (!$query) { die($qry. mysql_error()); }
        $jumrowdata=mysql_num_rows($query);
        $no = 1;
        $jml_kolom=8; 
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
          echo "<th scope='row'>$no</th>";
          for ($i = 0; $i < $jml_kolom; $i++)
          { if ($i==7)
            { echo "<td><a href='?mod=14&mode=$mode&id=$data[$i]'>Riwayat</a></td>"; }
            else
            { if (is_numeric($data[$i]))
              { echo "<td align='right'>$data[$i]</td>"; }
              else
              { echo "<td>$data[$i]</td>"; }
            }
          }
          echo "</tr>";
          $no++;
        }
      } 
      else 
      { 
        if($mode=="General") 
        { $sql_cri = " and s.mattype='N'"; } 
        else if($mode=="Bahan_Baku") 
        { $sql_cri = " and s.mattype in ('A','F')"; } 
        else 
        { $sql_cri = " and s.mattype not in ('N','A','F')"; }
		    $qry="select s.matclass,if(s.goods_code='-' or s.goods_code='',concat(s.mattype,' ',s.id_item),s.goods_code) goods_code,
          s.itemdesc,s.color,s.size,odo.goods_code kode_odo,odo.itemdesc item_odo,round(a.stock,2) stock,a.id_item 
          from stock a inner join masteritem s on a.mattype=s.mattype
          and a.id_item=s.id_item 
          left join masteritem_odo odo on s.id_item_odo=odo.id_item_odo 
          where a.stock<>0 $sql_cri ";
        $query = mysql_query($qry);
        if (!$query) { die($qry. mysql_error()); }
        $jumrowdata=mysql_num_rows($query);
        $no = 1;
        $jml_kolom=9;
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
          echo "<th scope='row'>$no</th>";
          for ($i = 0; $i < $jml_kolom; $i++)
          { 
            if($i==8 and $mode!="FG" and $nm_company!="PT. Sandrafine Garment")
            { echo "<td><a href='?mod=14&mode=$mode&id=$data[$i]'>Riwayat</a></td>"; }
            else
            { if (is_numeric($data[$i]))
              { echo "<td align='right'>$data[$i]</td>"; }
              else
              { echo "<td>$data[$i]</td>"; }
            }
          }
          echo "</tr>";
          $no++;
        }
      }
      echo "</tbody>";
    echo "</table>";
  echo "</div>";
echo "</div>";
?>