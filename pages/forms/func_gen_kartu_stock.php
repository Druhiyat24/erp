<?php
function gen_kartu_stock($user,$sesi,$id_item,$cribpb,$cribppb)
{	
	$cribpb=str_replace("bpbno","a.bpbno",$cribpb);
	$SQL = "delete from upload_tpb where username='$user' and sesi='$sesi'";
	insert_log($SQL,$user);
	if (substr($id_item,0,1)!="G")
	{	$gb = ""; }
	else
	{	$id_item = str_replace("G","",$id_item);
		$gb = "Y";	
	}
	
	$rscek = mysql_fetch_array(mysql_query("select * from masteritem where id_item='$id_item'"));
	$mattype = $rscek['mattype'];
	
	if ($gb=="")
	{	
		if($mattype=="N") 
		{ 
			$fld_2 = "if(bppbno_int!='',bppbno_int,bppbno)"; 
			$fld_3 = "'' styleno,'' kpno";
		} 
		else 
		{ 
			$fld_2 = "concat(if(bppbno_int!='',bppbno_int,bppbno),' ',ifnull(jo_no,''))"; 
			$fld_3 = "ac.styleno,ac.kpno";
		}
		$fld="trans_no,username,sesi,id_item,tanggal_aju,nomor_aju,supplier,
	  	jumlah_satuan,id_supplier,kode_barang,
	  	harga_penyerahan,stock,URAIAN_DOKUMEN
	  	,styleno,kpno,rakno";	
		$fld2="'Out','$user','$sesi',id_item,bppbdate,$fld_2,
	  	s.supplier,0,'',sent_to,0,a.qty,concat(jenis_dok,' (',bcno,')')
	  	,$fld_3,'RAK#'";
	}
	else
	{	
		$fld="berat_kotor,trans_no,username,sesi,id_item,tanggal_aju,nomor_aju,supplier,
	  	jumlah_satuan,id_supplier,kode_barang,
	  	harga_penyerahan,stock,URAIAN_DOKUMEN,tanggal_daftar
	  	,styleno,kpno,rakno";	
		$fld2="round(qty*price,2),'Out','$user','$sesi',id_item,bcdate,bcno,
	  	s.supplier,0,'',sent_to,0,a.qty,jenis_dok,bppbdate
	  	,ac.styleno,ac.kpno,'RAK#'";
	}
	$SQL = "insert into upload_tpb ($fld) select $fld2 from 
		bppb a left join mastersupplier s on a.id_supplier=s.id_supplier  
		left join jo on a.id_jo=jo.id 
		left join (select * from jo_det group by id_jo) jod on jo.id=jod.id_jo 
		left join so on jod.id_so=so.id 
		left join act_costing ac on so.id_cost=ac.id  
	  where id_item='$id_item' and $cribppb order by bppbdate";
	insert_log($SQL,$user);

	if ($gb=="")
	{	
		if($mattype=="N") 
		{ 
			$fld_2 = "if(a.bpbno_int!='',a.bpbno_int,a.bpbno)"; 
			$fld_3 = "'' styleno,'' kpno";
		} 
		else 
		{ 
			$fld_2 = "concat(if(a.bpbno_int!='',a.bpbno_int,a.bpbno),' ',ifnull(jo_no,''))"; 
			$fld_3 = "ac.styleno,ac.kpno";
		}
		$fld="trans_no,username,sesi,id_item,tanggal_aju,nomor_aju,supplier,jumlah_satuan,id_supplier
	  	,kode_barang,harga_penyerahan,stock,URAIAN_DOKUMEN,pono
	  	,styleno,kpno,rakno";
		$fld2="'In','$user','$sesi',a.id_item,bpbdate,$fld_2,s.supplier,0,s.supplier,
	  	'' kode_barang,a.qty,0,concat(jenis_dok,' (',bcno,')'),
	  	concat(pono,' / ',a.styleno)
	  	,$fld_3,tmprak.rakno";
	}
	else
	{	
		$fld="berat_bersih,kode_satuan,trans_no,username,sesi,id_item,tanggal_aju,nomor_aju,supplier,jumlah_satuan,id_supplier
	  	,kode_barang,harga_penyerahan,stock,URAIAN_DOKUMEN,pono,tanggal_daftar
	  	,styleno,kpno,rakno";
		$fld2="round(qty*price,2),unit,'In','$user','$sesi',a.id_item,bcdate,bcno,s.supplier,0,s.supplier,
	  	'' kode_barang,a.qty,0,jenis_dok,
	  	concat(pono,' / ',styleno),bpbdate
	  	,ac.styleno,ac.kpno,'RAK'";
	}
	$SQL = "insert into upload_tpb ($fld) select $fld2 from 
		bpb a left join mastersupplier s on a.id_supplier=s.id_supplier 
		left join jo on a.id_jo=jo.id 
	  left join (select * from jo_det group by id_jo) jod on jo.id=jod.id_jo 
		left join so on jod.id_so=so.id 
		left join act_costing ac on so.id_cost=ac.id 
		left join 
		(select distinct concat(kode_rak,' ',nama_rak) rakno,a.bpbno,a.id_jo,a.id_item from bpb_roll_h a inner join bpb_roll s 
			on a.id=s.id_h inner join master_rak d on s.id_rak_loc=d.id where a.id_item='$id_item' group by a.bpbno,a.id_jo,a.id_item) tmprak 
			on a.bpbno=tmprak.bpbno and a.id_jo=tmprak.id_jo and a.id_item=tmprak.id_item  
		where a.id_item='$id_item' and $cribpb order by bpbdate";
	insert_log($SQL,$user);
	if ($gb=="Y")
	{	
		insert_log("SET @runtot:=0",$user);
		insert_log("SET @runtotamt:=0",$user);
    insert_log("SET @urut:=0",$user);
    $SQL = "insert into gb_posisiperdok 
    	(username,sesi,qtybtb,qtybkb,sisa,sisa_amt,urut,jdokmsk,bcno,bcdate,bpbdate,
    	kditem,itemdesc,unitbtb,amtbtb,jdokkel,bcno_kel,bcdate_kel,
    	bppbdate,amtbkb) 
    	SELECT '$user','$sesi',q1.masuk,q1.keluar,(@runtot := @runtot + (q1.masuk-q1.keluar)) AS rt,
    	(@runtotamt := @runtotamt + (q1.amt_btb-q1.amt_bkb)) AS rtant,
    	(@urut := @urut + 1) AS urut,
    	if(q1.jtrans='In',URAIAN_DOKUMEN,''),if(q1.jtrans='In',nomor_aju,''),
    	if(q1.jtrans='In',tanggal_aju,''),if(q1.jtrans='In',tanggal_daftar,''),kditem,
    	itemdesc,kode_satuan,q1.amt_btb,
    	if(q1.jtrans='Out',URAIAN_DOKUMEN,''),if(q1.jtrans='Out',nomor_aju,''),
    	if(q1.jtrans='Out',tanggal_aju,''),if(q1.jtrans='Out',tanggal_daftar,''),
    	q1.amt_bkb
      FROM
      ( select trans_no jtrans,URAIAN_DOKUMEN,NOMOR_AJU,TANGGAL_AJU,tanggal_daftar,
      	pono,SUPPLIER,'' blank2 ,round(HARGA_PENYERAHAN,2) masuk,round(STOCK,2) keluar,
      	s.goods_code kditem,s.itemdesc,a.kode_satuan,berat_bersih amt_btb,berat_kotor amt_bkb 
        from upload_tpb a inner join masteritem s on a.id_item=s.id_item 
        where a.username='$user' and sesi='$sesi' 
        order by TANGGAL_AJU asc, HARGA_PENYERAHAN desc
      ) AS q1 order by q1.TANGGAL_AJU asc, q1.masuk desc, q1.keluar desc";
  	insert_log($SQL,$user);
  }
};
?>