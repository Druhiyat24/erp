<?php

include('../../mpdf57/mpdf.php');

include '../../include/conn.php';

include 'fungsi.php';



ob_start();



$quenya = "Select * from mastercompany Where company!='' ";

$strsql = mysql_query($quenya);

$rs = mysql_fetch_array($strsql);

  $nm_company = $rs['company'];

  $add_company = $rs['alamat1'];

  $add2_company = $rs['alamat2'];

  $kota_comp = $rs['kota'];

  $add3_company = "Kec. ".$rs['kec'].' '.$rs['kota'].' '.$rs['propinsi'].' '.$rs['kodepos'];

  $add4_company = "Telp. ".$rs['telp'];

  if ($rs['logo_company']=="Y")

  { $logo = "<img src='../../include/logo.jpg' width='50'>"; }

  else

  { $logo = ""; }



$bppbno=$_GET['noid'];

$mode=$_GET['mode'];

if ($mode=="In")

{
  if(substr($bppbno,0,1)=="C"){

    $rstot=mysql_fetch_array(mysql_query("select count(*) jdata,sum(qty) tqty, sum(qty-qty_reject) tqtyg, sum(qty_reject) tqtyr from bpb where bpbno='$bppbno' 

    group by bpbno"));

  $jdata=$rstot['jdata'];

  $tqty=$rstot['tqty'];
  $tqtyg=$rstot['tqtyg'];
  $tqtyr=$rstot['tqtyr'];

  } else {

    $rstot=mysql_fetch_array(mysql_query("select count(*) jdata,sum(qty) tqty from bpb where bpbno='$bppbno' 

    group by bpbno"));

  $jdata=$rstot['jdata'];

  $tqty=$rstot['tqty'];


  }

}

else

{ $rstot=mysql_fetch_array(mysql_query("select count(*) jdata,sum(qty) tqty from bppb where bppbno='$bppbno' 

    group by bppbno"));

  $jdata=$rstot['jdata'];

  $tqty=$rstot['tqty']; 

}

if ($jdata>=1 and $jdata<8)
{ 
  if(substr($bppbno,-1,1)=="R")
  {
    $space_head=55;  
  }
  else
  {
    $space_head=52;  
  }
}
else
{ $space_head=55; }

if (substr($bppbno,0,2)=="FG" OR substr($bppbno,3,2)=="FG")
{ 
  $tbl_mst = "masterstyle"; 

  $fld_mst2 = "goods_code";

  if ($nm_company=="PT. Bangun Sarana Alloy")

  { $fld_mst = "concat(itemname,' Size ',size)"; }

  else if ($nm_company=="PT. Tun Hong")

  { $fld_mst = "concat('Style # ',s.styleno,' ',itemname,' Size ',size,' Warna ',color)"; }

  else

  { $fld_mst = "concat(goods_code,' - ',itemname)"; }

  $sql_join_fg = "so_det sod on sod.id=a.id_so_det 

    left join so on so.id=sod.id_so  

    left join act_costing ac on so.id_cost=ac.id";

  $fld_add_fg3=",ifnull(ac.styleno,s.styleno)";

  $fld_add_4="ifnull(ac.kpno,s.kpno)";

  $fld_add_fg2=",ifnull(so.buyerno,s.buyerno),ifnull(sod.dest,'-'),ifnull(sod.color,s.color),ifnull(sod.size,s.size)";

  $fld_add_fg31="

  <td align='center'>Style #</td>";

  $fld_add_fg="

  <td align='center'>PO #</td>

  <td align='center'>Dest</td>

  <td align='center'>Color</td>

  <td align='center'>Size</td>";

  $jml_fld = 10;

  $jml_fld_sp = 8;

}

else

{ $tbl_mst = "masteritem"; 

  $fld_mst2 = "goods_code";

  if ($nm_company=="PT. Bangun Sarana Alloy")

  { $fld_mst = "concat(goods_code,' ',itemdesc,' Size ',size)"; }

  else if ($nm_company=="PT. Tun Hong")

  { $sql="update bpb a inner join masterstyle s on a.id_item_fg=s.id_item set a.styleno=s.styleno where bpbno='$bppbno' "; 

    insert_log($sql,"");

    $sql="update bppb a inner join masterstyle s on a.id_item_fg=s.id_item set a.styleno=s.styleno where bppbno='$bppbno' "; 

    insert_log($sql,"");

    $fld_mst = "concat('Style # ',a.styleno,' ',itemdesc,' Size ',size,' Warna ',color)"; 

  }

  else

  { if(substr($bppbno,0,1)=="N")

    {

      $fld_mst = "concat(goods_code,' ',itemdesc)";

    }

    else

    {

      $fld_mst = "concat(itemdesc,' ',add_info)";

    }  

  }

  $sql_join_fg = "(select id_jo,id_so from jo_det group by id_jo) jod on a.id_jo=jod.id_jo 

    left join so on jod.id_so=so.id 

    left join act_costing ac on so.id_cost=ac.id";

  $fld_add_fg3="";

  $fld_add_4="ifnull(ac.kpno,'-')";

  $fld_add_fg31="";

  $fld_add_fg="";

  $fld_add_fg2="";

  $jml_fld = 5;

  $jml_fld_sp = 3;

}

$logo = "";



if ($nm_company=="PT. Bangun Sarana Alloy")
{ $tglcetak=""; 
  $orientasi_kertas = "L"; # L = Landscape P = Portrait
  $ukuran_kertas=array(105,148.5); # Ukuran kertas W/Lebar x H/Tinggi
}
else
{ $tglcetak="Dicetak : ".date('Y-m-d H:i'); 
  $ukuran_kertas="A4"; 
  $orientasi_kertas = "P"; # L = Landscape P = Portrait
}

if ($mode=="In")

{ $head_cap = "Bukti Penerimaan Barang";

  $rsh=mysql_fetch_array(mysql_query("select group_concat(distinct ac.kpno) wsno,POH.pono,
    a.invno,a.bpbdate,id_item,a.id_jo,a.qty_reject,a.jenis_dok,a.bcno,a.bcdate from bpb a left join jo_det jod on a.id_jo=jod.id_jo
    left join (select id, 
id_po, 
id_jo, 
id_gen, 
qty, 
unit, 
curr, 
price,
cancel from po_item where cancel != 'Y') POI ON POI.id = a.id_po_item 
    left join po_header POH ON POH.id = POI.id_po
    left join so on jod.id_so=so.id left join act_costing ac on so.id_cost=ac.id  
    where a.bpbno='$bppbno' limit 1"));

  $head_data = '
  <table width="100%" style="border:none; font-size:10pt">
    <tr>

      <td width="10%"></td>
      <td></td>
      <td width="10%">SJ # / Inv #</td>
      <td> : '.$rsh['invno'].'</td>
    </tr>
    <tr>
      <td>No PO</td>
      <td> : '.$rsh['pono'].'</td>
      <td>Tgl. BPB</td>
      <td> : '.fd_view($rsh['bpbdate']).'</td>    
    </tr>
    <tr>
      <td>Dok. BC</td>
      <td> : '.$rsh['jenis_dok'].' '.$rsh['bcno'].'</td>
      <td>Tgl. Dok BC</td>
      <td> : '.fd_view($rsh['bcdate']).'</td>
    </tr>

  </table>';

  $quenya = "Select if(a.bpbno_int<>'',a.bpbno_int,a.bpbno) trans_no,

    a.bpbdate trans_date,a.*,s.supplier,s.alamat,s.alamat2,up.fullname,upcfm.fullname cfmfullname,a.qty_reject  

    from bpb a inner join mastersupplier s 

    on a.id_supplier=s.id_supplier 

    left join userpassword up on a.username=up.username 

    left join userpassword upcfm on a.confirm_by=upcfm.username 

    Where bpbno='$bppbno'";

}

else
{ $head_cap = "Surat Jalan";

  $rsh=mysql_fetch_array(mysql_query("SELECT * FROM M.* FROM(
SELECT POH.pono,a.*,s.goods_code,s.itemdesc itemdesc,ms.supplier,mb.supplier buyer, a.last_date_bppb,
          ac.styleno,ac.kpno,a.username,group_concat(distinct concat(' ',jo.jo_no)) jo_nya,a.jenis_dok,a.bcno,a.bcdate  
          FROM bppb a inner join masteritem s on a.id_item=s.id_item
          inner join mastersupplier ms on a.id_supplier=ms.id_supplier 
          inner join jo_det jod on a.id_jo=jod.id_jo 
          inner join jo on jod.id_jo=jo.id  
          inner join so on jod.id_so=so.id 
          inner join act_costing ac on so.id_cost=ac.id 
          inner join mastersupplier mb on ac.id_buyer=mb.id_supplier  
          LEFT JOIN (SELECT * FROM po_item WHERE 1=1 GROUP BY id_po,id_jo,id_gen)POI ON POI.id_jo = a.id_jo AND POI.id_gen = a.id_item
          LEFT JOIN po_header POH ON POH.id = POI.id_po
          where mid(bppbno,4,1)='C' and a.id_jo!='' #AND a.bppbno = 'SJ-C01525'
          GROUP BY a.bppbno ASC order by bppbdate desc)M WHERE M.bppbno = '$bppbno'"));
	if(!ISSET($rsh['bppbdate'])){
		
		if(substr($bppbno,3,1) == 'C' ){
			$on = " POI.id_gen = a.id_item AND POI.id_jo = a.id_jo";
			$LEFT_JOIN = "	
 left join po_header POH ON POH.id = a.id_po			
			INNER JOIN masteritem MI ON MI.id_item = a.id_item
    left join (select id, 
	id_po, 
	id_jo, 
	id_gen, 
	qty, 
	unit, 
	curr, 
	price,
	cancel from po_item where cancel != 'Y') POI ON POI.id_po = POH.id";
   
		}else{
			$on = " POI.id_gen = MI.id_gen AND POI.id_jo = a.id_jo";
			$LEFT_JOIN = "	INNER JOIN masteritem MI ON MI.id_item = a.id_item
    left join (select id, 
	id_po, 
	id_jo, 
	id_gen, 
	qty, 
	unit, 
	curr, 
	price,
	cancel from po_item where cancel != 'Y') POI ON {$on}
    left join po_header POH ON POH.id = POI.id_po";
		}
		
		
		$__sql="select group_concat(distinct ac.kpno) wsno,POH.pono,
    /*a.invno*/
	ifnull(IH.invno,IC.v_noinvoicecommercial)invno
	,a.bppbdate,a.id_item,a.id_jo,a.jenis_dok,a.bcno,a.bcdate from bppb a left join jo_det jod on a.id_jo=jod.id_jo
	$LEFT_JOIN
    left join so on jod.id_so=so.id left join act_costing ac on so.id_cost=ac.id  
	LEFT JOIN (SELECT bppbno,id_inv FROM invoice_detail GROUP BY bppbno) ID ON ID.bppbno = a.bppbno_int
	LEFT JOIN (SELECT id,invno FROM invoice_header) IH ON ID.id_inv = IH.id
	LEFT JOIN (SELECT * FROM invoice_commercial WHERE 1=1 GROUP BY bpbno)IC ON TRIM(IC.bpbno) = TRIM(a.bppbno_int)	
    where a.bppbno='$bppbno' and a.cancel='N' and a.confirm = 'Y'  limit 1";

  $rsh=mysql_fetch_array(mysql_query($__sql));	
	}	 

	/*
  $rsh=mysql_fetch_array(mysql_query("select group_concat(distinct ac.kpno) wsno,POH.pono,
    a.invno,a.bppbdate,id_item,a.id_jo from bppb a left join jo_det jod on a.id_jo=jod.id_jo
    left join (select id, 
	id_po, 
	id_jo, 
	id_gen, 
	qty, 
	unit, 
	curr, 
	price,
	cancel from po_item where cancel != 'Y') POI ON POI.id_jo = a.id_jo 
    left join po_header POH ON POH.id = POI.id_po
    left join so on jod.id_so=so.id left join act_costing ac on so.id_cost=ac.id  
    where a.bppbno='$bppbno' and a.cancel='N' and a.confirm = 'Y'  limit 1"));


*/	
		 
		 
  if(substr($bppbno,10,1)=="R")
  {
    $sql="select a.*,s.pono from bppb a 
    left join bpb s on a.bpbno_ro=s.bpbno where a.bppbno='$bppbno'";
    $rsh = mysql_fetch_array(mysql_query($sql));
  }
  $head_data = '
  <table width="100%" style="border:none; font-size:10pt">
    <tr>
      <td width="20%"></td>
      <td></td>
      <td width="20%">SJ # / Inv #</td>
      <td> : '.$rsh['invno'].'</td>
    </tr>
    <tr>
      <td>No PO </td>
      <td> : '.$rsh['pono'].'</td>
      <td>Tgl. BPPB</td>
      <td> : '.fd_view($rsh['bppbdate']).'</td>
    </tr>
    <tr>
      <td>Dok. BC</td>
      <td> : '.$rsh['jenis_dok'].' '.$rsh['bcno'].'</td>
      <td>Tgl. Dok BC</td>
      <td> : '.fd_view($rsh['bcdate']).'</td>
    </tr>';
    if(substr($bppbno,-1,1)=="R")
    {
      $bpbnonya = flookup("bpbno_ro","bppb","bppbno='$bppbno'");
      $eksdok = flookup("bcno","bpb","bpbno='$bpbnonya'");
      $teksdok = flookup("bcdate","bpb","bpbno='$bpbnonya'");
      $jeksdok = flookup("jenis_dok","bpb","bpbno='$bpbnonya'");
      $head_data = $head_data.'
      <tr>
        <td>BC In</td>
        <td> : '.$jeksdok.' '.$eksdok.'</td>
        <td>Tgl. BC In</td>
        <td> : '.fd_view($teksdok).'</td>
      </tr>';
    }
  $head_data = $head_data.'
  </table>';

  $quenya = "Select if(a.bppbno_int<>'',a.bppbno_int,a.bppbno) trans_no,a.bppbdate trans_date,a.*,s.supplier,

    s.alamat,s.alamat2,up.fullname,upcfm.fullname cfmfullname from bppb a inner join mastersupplier s 

    on a.id_supplier=s.id_supplier 

    left join userpassword up on a.username=up.username 

    left join userpassword upcfm on a.confirm_by=upcfm.username  

    Where bppbno='$bppbno'";

}

$strsql = mysql_query($quenya);

$rs = mysql_fetch_array($strsql);

  $tglsj=$rs['trans_date'];

  $sentto=$rs['supplier'];

  $add_sentto=$rs['alamat'];

  $add2_sentto=$rs['alamat2'];

  $kota=$kota_comp.", ".fd_view($tglsj);

  $fullname=$rs['fullname'];

  $cfmfullname  =$rs['cfmfullname'];

  $dateinput=$rs['dateinput'];

  $cfmdateinput=$rs['confirm_date'];

if ($mode=="Out")

{ 

  $ttdnya = 

  '<tr>

    <td width="200px" style="margin-right:-5px;border:none;" align="left">

      Created By.

    </td>


';

    // if(substr($bppbno,0,2)!="FG" and substr($bppbno,3,2)!="FG") 

    { $ttdnya = $ttdnya.

      '<td width="200px" style="margin-left:-5px;border:none;" align="left">

        Approved By.

      </td>

      <td style="margin-left:-5px;border:none;" align="left">

        Received By.

      </td>'; 

    }

    $ttdnya = $ttdnya.

    '</tr>

    <tr>

      <td width="200px" style="margin-right:-5px;border:none;" align="left">

        '.$fullname.'

      </td>

      <td width="200px" style="margin-right:-5px;border:none;" align="left">

       '.$cfmfullname.'

      </td>      ';

      // if(substr($bppbno,0,2)!="FG" and substr($bppbno,3,2)!="FG")

      // { $ttdnya = $ttdnya.'<td width="200px" style="margin-right:-5px;border:none;" align="left">

      //     '.$cfmfullname.'<br>'.$cfmdateinput.' 

      //   </td>';

      // }

      $ttdnya = $ttdnya.'<td style="margin-right:-5px;border:none;" align="right" height="80px"></td>

    </tr>';

}

else

{ $ttdnya = 

  '<tr>

    <td width="300px" style="margin-right:-5px;border:none;" align="left">

      Created By.

    </td>';

    if(substr($bppbno,0,2)!="FG" and substr($bppbno,3,2)!="FG") 

    { $ttdnya = $ttdnya.

      '<td width="200px" style="margin-left:-5px;border:none;" align="left">

        Approved By.

      </td>

      <td style="margin-left:-5px;border:none;" align="left">

        Received By.

      </td>'; 

    }

    $ttdnya = $ttdnya.

  '</tr>

  <tr>

    <td width="200px" style="margin-right:-5px;border:none;" align="left">

      '.$fullname.'<br>'.$dateinput.' 

    </td>';

    if(substr($bppbno,0,2)!="FG" and substr($bppbno,3,2)!="FG")

    { $ttdnya = $ttdnya.'<td width="200px" style="margin-right:-5px;border:none;" align="left">

        '.$cfmfullname.'<br>'.$cfmdateinput.' 

      </td>';

    }

    $ttdnya = $ttdnya.'<td style="margin-right:-5px;border:none;" align="right" height="80px"></td>

  </tr>';

}
$footernyaX = "";
$footernya = 

  '<table cellpadding=0 cellspacing=0 style="border:none;">

    <tr>

      <td style="margin-right:-5px;border:none;font-size:8px;" align="left">

        Halaman: {PAGENO} / {nb}

      </td>

      <td style="margin-right:-5px;border:none;" align="left">

      '.$tglcetak.'

      </td>

    </tr>

  </table>';

?>
<br>
<br>
<br>
<br>
<table class='main' repeat_header="1" border="1" cellspacing="0" width="100%" 

  style="border-collapse: collapse; width:100%; font-size:10pt;">

  <thead>

    <tr class="head">

      <td align='center' height="20">No.</td>

      <td align='center'>WS #</td>

      <?php echo $fld_add_fg31; ?>

      <td align='center'>Nama Barang</td>

      <?php echo $fld_add_fg; ?>

      <?php
      if((substr($bppbno,0,1)=="C") || (substr($bppbno,0,1)=="N"))
      {
        $jml_fld = 7;
        echo "
        <td align='center'>Full Qty</td>
        <td align='center'>Unit</td>   
        <td align='center'>Good Qty</td>
        <td align='center'>Reject Qty</td>";
      } 
      else 
      {
        echo "
        <td align='center'>Jumlah</td>
        <td align='center'>Satuan</td>";
      }
      ?>   


      <td align='center'>Keterangan</td>

    </tr>

  </thead>

  <tbody>

    <?php

      if ($mode=="In")
	
      { 
	  tampil_data("select $fld_add_4 $fld_add_fg3 ,$fld_mst $fld_add_fg2 ,a.qty,a.unit,(a.qty-a.qty_reject)gqty,a.qty_reject,a.remark

          from bpb a inner join $tbl_mst s on a.id_item=s.id_item 

          left join 

          $sql_join_fg 

          where bpbno='$bppbno'",$jml_fld);

        if(substr($bppbno,0,1)=="C")
        {

        echo "

        <tr>

          <td colspan='$jml_fld_sp' align='right'>Total </td>

          <td align='right'>".round($tqty,2)."</td>

          <td style='background-color: #999'></td>

          <td align='right'>".round($tqtyg,2)."</td>

          <td align='right'>".round($tqtyr,2)."</td>

          <td style='background-color: #999'></td>

        </tr>

        ";
        }
        else
        {
        echo "

        <tr>

          <td colspan='$jml_fld_sp' align='right'>Total </td>

          <td align='right'>".round($tqty,2)."</td>

        </tr>

        ";
        }

      }
      else
      { 
        if(substr($bppbno,3,1)=="M" or substr($bppbno,3,1)=="N") { $fldws = "'' kpno"; } else { $fldws = "ac.kpno"; }
        tampil_data("select $fldws $fld_add_fg3 ,$fld_mst $fld_add_fg2 ,a.qty,a.unit,a.remark  
          from bppb a inner join $tbl_mst s on a.id_item=s.id_item 

          left join 

          $sql_join_fg   

          where bppbno='$bppbno'",$jml_fld);

        echo "

        <tr>

          <td colspan='$jml_fld_sp' align='right'>Total </td>

          <td align='right'>".round($tqty,2)."</td>

        </tr>

        ";

      }

    ?>

  </tbody>

</table>

<table style="font-size:12px;" width="100%">

  <?php 

  echo $ttdnya;

  ?>

</table>

<?php

$header = $footernya.'<table cellpadding=0 cellspacing=0 style="border:none;">

            <tr>

              <td style="border:none;" align="left">'.$logo.'</td>

              <td width="100%" style="border:none;"><h4>'.$nm_company.'</h4></td>

              <td width="100%" style="border:none; font-size:7pt;">'.$kota.'</td>

            </tr>

            <tr>

              <td></td>

              <td width="100%" style="border:none; font-size:7pt;">'.$add_company.'</td>

              <td width="100%" style="border:none; font-size:7pt;">'.$sentto.'</td>

            </tr>

            <tr>

              <td></td>

              <td width="100%" style="border:none; font-size:7pt;">'.$add2_company.'</td>

              <td width="100%" style="border:none; font-size:7pt;">'.$add_sentto.'</td>

            </tr>

            <tr>

              <td></td>

              <td width="100%" style="border:none; font-size:7pt;">'.$add3_company.'</td>

              <td width="100%" style="border:none; font-size:7pt;">'.$add2_sentto.'</td>

            </tr>

            <tr>

              <td></td>

              <td width="100%" style="border:none; font-size:7pt;">'.$add4_company.'</td>

            </tr>

          </table>

          <table width="100%" style="border:none;">

            <tr>

              <td align="center" style="border:none;"><h2>'.$head_cap.'</h2></td>

            </tr>

            <tr>

              <td align="center" style="border:none; font-size:14pt;">'.$rs['trans_no'].'</td>

            </tr>

          </table>

          '.$head_data.'';

$content = ob_get_clean();

$footer =  $footernyaX;           

try {

    # $mpdf=new mPDF('utf-8', "A4", Ukuran_Font ,'Arial', Margin_Kiri, Margin_Kanan, Space_Header_Dengan_Contents, 5, Space_Atas_Dengan_Header, Space_Bawah_Dengan_Footer, Orientation);

    $mpdf=new mPDF('utf-8', $ukuran_kertas, 9 ,'Arial', 5, 5, $space_head, 5, 5, 1, $orientasi_kertas);

    $mpdf->SetTitle("Laporan");

    $mpdf->setHTMLHeader($header);

    $mpdf->setHTMLFooter($footer);

    $mpdf->WriteHTML($content);

    $mpdf->Output("laporan.pdf","I");

} catch(Exception $e) {

    echo $e;

    exit;

}

?>