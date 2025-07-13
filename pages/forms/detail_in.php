<?php 
include ("../../include/conn.php");
$rs=mysql_fetch_array(mysql_query("select * from mastercompany"));
$nm_company=$rs['company'];
$trans_no = $_POST['id'];
echo "<table class='table table-bordered' style='width:100%;'>";
	echo "<thead>";
	echo "<tr>";
		if (substr($trans_no,0,2)=="FG" OR substr($trans_no,0,5)=="SJ-FG")
		{ echo "<th>Kode Barang</th>"; 
		  echo "<th>Buyer PO</th>";
		}
		else
		{ echo "<th style='width:10%;'>Kode Barang</th>"; }
		echo "<th>Nama Barang</th>";
		if ($nm_company=="PT. Sandrafine Garment")
		{	if (substr($trans_no,0,2)!="FG" OR substr($trans_no,0,5)!="SJ-FG")
			{	echo "<th>Stock Card</th>"; }
		}
		if (substr($trans_no,0,1)=="C")
		{
			echo "<th>Qty Good</th>";
			echo "<th>Qty Reject</th>";
		}
		else
		{
			echo "<th>Jumlah</th>";
		}
		echo "<th>Satuan</th>";
		if (substr($trans_no,0,1)!="C")
		{
			// echo "<th>Curr</th>";
			// echo "<th>Harga</th>";
		}
	echo "</tr>";
	echo "</thead>";
	# QUERY TABLE
	if (substr($trans_no,0,1)=="C")
	{
		$add_fld = ",ifnull(a.qty_reject,0)qty_reject";
	}
	else
	{
		$add_fld = "";
	}
	if (substr($trans_no,0,2)=="FG")
	{ $tblmst = "masterstyle"; 
	  $flddesc = "buyerno,itemname";
	  $fldgcode = "goods_code";
	  $tbltrans = "bpb";
	  $fldtrans = "bpbno";
	  $fldtrans_int = "bpbno_int";
	}
	else if (substr($trans_no,0,5)=="SJ-FG")
	{ $tblmst = "masterstyle"; 
	  $flddesc = "buyerno,itemname";
	  $fldgcode = "goods_code";
	  $tbltrans = "bppb";
	  $fldtrans = "bppbno";
	  $fldtrans_int = "bppbno";
	}
	else if (substr($trans_no,0,5)!="SJ-FG" AND substr($trans_no,0,3)=="SJ-")
	{ $tblmst = "masteritem"; 
	  $flddesc = "stock_card,itemdesc";
	  $fldgcode = "goods_code";
	  $tbltrans = "bppb";
	  $fldtrans = "bppbno";
	  $fldtrans_int = "bppbno";
	}
	else
	{ $tblmst = "masteritem"; 
	  $flddesc = "'' stock_card,concat(itemdesc,' ',color,' ',size,' ',add_info)";
	  $fldgcode = "goods_code";
	  $tbltrans = "bpb";
	  $fldtrans = "bpbno";
	  $fldtrans_int = "bpbno_int";
	}
	$query = mysql_query("SELECT a.curr,a.price,a.id_item,s.goods_code,$flddesc itemdesc,a.qty,a.unit $add_fld  
   FROM $tbltrans a inner join $tblmst s on a.id_item=s.id_item 
   where $fldtrans='$trans_no' or $fldtrans_int='$trans_no' ORDER BY a.id_item ASC");
	$no=1;
	while($data = mysql_fetch_array($query))
  	{ echo "<tr>";
		  echo "<td>$data[goods_code]</td>"; 
		  if (substr($trans_no,0,2)=="FG" OR substr($trans_no,0,5)=="SJ-FG")
		  { echo "<td>$data[buyerno]</td>"; }
		  echo "<td>$data[itemdesc]</td>";
		  if ($nm_company=="PT. Sandrafine Garment")
			{	if (substr($trans_no,0,2)!="FG" OR substr($trans_no,0,5)!="SJ-FG")
				{	echo "<td>$data[stock_card]</td>"; }
			}
			$qty_good = $data['qty'] - $data['qty_reject'];
			echo "<td>".$qty_good."</td>";
		  if (substr($trans_no,0,1)=="C")
		  {
		  	echo "<td>$data[qty_reject]</td>";
		  }
		  echo "<td>$data[unit]</td>"; 
		  if (substr($trans_no,0,1)!="C")
		  {
		  	// echo "<td>$data[curr]</td>"; 
		  	// echo "<td>$data[price]</td>";
		  }
		echo "</tr>";		
	  $no++;
		$qty += $data['qty'];
		$reject += $data['qty_reject'];
		$qty_good = $qty - $reject;	  
	}

  	echo "<tr>";
		  echo "<td></td>"; 
		  echo "<td></td>";
		  $qty += $data['qty'];
		  $reject += $data['qty_reject'];
			$qty_good = $qty - $reject;
			echo "<td>".$qty_good."</td>";
		  if (substr($trans_no,0,1)=="C")
		  {
		  	echo "<td>".$reject."</td>";
		  }
		  echo "<td></td>"; 
		  if (substr($trans_no,0,1)!="C")
		  {
		  	// echo "<td>$data[curr]</td>"; 
		  	// echo "<td>$data[price]</td>";
		  }
		echo "</tr>";	
echo "</table>";

?>