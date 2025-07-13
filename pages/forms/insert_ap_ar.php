<?PHP
function insert_data_ap_ar($trans,$transno,$user)
{	if($trans!="" and $transno!="")
	{	if($trans=="AP")
		{	$tbl="acc_pay";	
			$tbl_trx="bpb";
			$fld_trx="bpbno trx_no,bpbdate trx_date";
			$fld_cri="bpbno";
		}
		else
		{	$tbl="acc_rec";	
			$tbl_trx="bppb";
			$fld_trx="bppbno trx_no,bppbdate trx_date";
			$fld_cri="bppbno";
		}
		$sql_data="select $fld_trx,id_supplier,invno,no_fp,tgl_fp,curr,sum(qty*price) amt_trx 
			from $tbl_trx where $fld_cri='$transno'";
		$rs=mysql_fetch_array(mysql_query($sql_data));
		$txtinv_date = fd($rs['trx_date']);
		$txtinv_no = strtoupper($rs['invno']);
		$txtno_faktur = strtoupper($rs['no_fp']);
		$txtid_supplier = strtoupper($rs['id_supplier']);
		$txtcurr = strtoupper($rs['curr']);
		$txtamount = strtoupper($rs['amt_trx']);
		$txttt_date = fd($rs['trx_date']);
		$txtdue_date = "";
		$txtpay_date="";
		$txtpay_bank="";
		$txtbyrke="";
		$cek = flookup("count(*)",$tbl,"$fld_cri='$transno'");
		if ($cek!="0")
		{	$sql = "update $tbl set inv_no='$txtinv_no',inv_date='$txtinv_date',
				no_faktur='$txtno_faktur', id_supplier='$txtid_supplier',
				curr='$txtcurr', amount='$txtamount', tt_date='$txttt_date',
				due_date='$txtdue_date', pay_date='$txtpay_date',pay_bank='$txtpay_bank',
				byr_ke='$txtbyrke' 
				where $fld_cri='$transno'";
			insert_log($sql,$user);
		}
		else
		{	$sql = "insert into $tbl ($fld_cri,inv_date,inv_no,no_faktur,id_supplier,curr,amount,tt_date,due_date,pay_date,
				pay_bank,byr_ke) values ('$transno','$txtinv_date','$txtinv_no','$txtno_faktur','$txtid_supplier','$txtcurr',
				'$txtamount','$txttt_date','$txtdue_date','$txtpay_date','$txtpay_bank','$txtbyrke')";
			insert_log($sql,$user);
		}	
	}
};
function insert_jurnal($trans,$transno,$user)
{	if($trans!="" and $transno!="")
	{	if($trans=="AP")
		{	$tbl="fin_journal_h";	
			$tbl_trx="bpb";
			$fld_trx="bpbno trx_no,bpbdate trx_date";
			$fld_cri="bpbno";
			$type_journal="2";
		}
		else
		{	$tbl="fin_journal_h";	
			$tbl_trx="bppb";
			$fld_trx="bppbno trx_no,bppbdate trx_date";
			$fld_cri="bppbno";
			$type_journal="1";
		}
		if($trans=="AP")
		{	$sql_data="select $fld_trx,a.id_supplier,invno,no_fp,tgl_fp,curr,sum(qty*price) amt_trx,
				msu.id_coa_d,msu.id_coa_k,mcoa_d.nm_coa nm_coa_d,mcoa_k.nm_coa nm_coa_k,supplier  
				from $tbl_trx a inner join masteritem s on a.id_item=s.id_item 
				inner join masterdesc mde on s.id_gen=mde.id 
				inner join mastercolor mco on mde.id_color=mco.id 
				inner join masterweight mwe on mco.id_weight=mwe.id
				inner join masterlength mle on mwe.id_length=mle.id
				inner join masterwidth mwi on mle.id_width=mwi.id 
				inner join mastercontents mcn on mwi.id_contents=mcn.id 
				inner join mastertype2 mty on mcn.id_type=mty.id 
				inner join mastersubgroup msu on mty.id_sub_group=msu.id
				inner join mastercoa mcoa_d on msu.id_coa_d=mcoa_d.id_coa 
				inner join mastercoa mcoa_k on msu.id_coa_k=mcoa_k.id_coa 
				inner join mastersupplier msup on a.id_supplier=msup.id_supplier where $fld_cri='$transno'";
		}
		else
		{	$sql_data="select $fld_trx,a.id_supplier,invno,no_fp,tgl_fp,a.curr,sum(a.qty*a.price) amt_trx,
				'5106001' id_coa_d,'1431002' id_coa_k,'HARGA POKOK PENJUALAN' nm_coa_d,'PERSEDIAAN BARANG JADI - LOKAL' nm_coa_k,supplier  
				from $tbl_trx a inner join masterstyle s on a.id_item=s.id_item 
				inner join mastersupplier msup on a.id_supplier=msup.id_supplier where $fld_cri='$transno'";
		}
		#echo $sql_data;
		$rs=mysql_fetch_array(mysql_query($sql_data));
		$nm_company=flookup("company","mastercompany","company!=''");
		$period=date("m/Y",strtotime($rs['trx_date']));
		$num_journal=urutkan_inq("J-".$period,"J/".$period);
		$date_journal=fd($rs['trx_date']);
		$reff_doc=$transno;
		$fg_intercompany="0";
		$fg_post="0";
		$dateadd=date('Y-m-d H:m:s');
		$cek = flookup("count(*)",$tbl,"reff_doc='$transno'");
		if ($cek=="0")
		{	$sql = "insert into $tbl (company,period,num_journal,date_journal,type_journal,reff_doc,fg_intercompany,
				fg_post,dateadd,useradd) 
				values ('$nm_company','$period','$num_journal','$date_journal','$type_journal',
				'$reff_doc','$fg_intercompany','$fg_post',
				'$dateadd','$user')";
			insert_log($sql,$user);
			$id_journal = flookup("id_journal",$tbl,"reff_doc='$transno'");
			$row_id = "1";
			$curr = $rs['curr'];
			$id_coa = $rs['id_coa_d'];
			$nm_coa = $rs['nm_coa_d'];
			$debit = $rs['amt_trx'];
			$credit = 0;
			$description = $rs['supplier'];
			// Debet
			$sql = "insert into fin_journal_d (id_journal,row_id,id_coa,nm_coa,curr,debit,credit,description,dateadd,useradd) 
				values ('$id_journal','$row_id','$id_coa','$nm_coa','$curr','$debit','$credit',
				'$description','$dateadd','$user')";
			insert_log($sql,$user);
			// Kredit
			$row_id = "2";
			$id_coa = $rs['id_coa_k'];
			$nm_coa = $rs['nm_coa_k'];
			$debit = 0;
			$credit = $rs['amt_trx'];
			$sql = "insert into fin_journal_d (id_journal,row_id,id_coa,nm_coa,curr,debit,credit,description,dateadd,useradd) 
				values ('$id_journal','$row_id','$id_coa','$nm_coa','$curr','$debit','$credit',
				'$description','$dateadd','$user')";
			insert_log($sql,$user);
		}	
	}
};
function insert_jurnal_retur($trans,$transno,$user)
{	if($trans!="" and $transno!="")
	{	if($trans=="AP")
		{	$tbl="fin_journal_h";	
			$tbl_trx="bpb";
			$fld_trx="bpbno trx_no,bpbdate trx_date";
			$fld_cri="bpbno";
			$type_journal="2";
		}
		else
		{	$tbl="fin_journal_h";	
			$tbl_trx="bppb";
			$fld_trx="bppbno trx_no,bppbdate trx_date";
			$fld_cri="bppbno";
			$type_journal="1";
		}
		if($trans=="AP" or $trans=="AR")
		{	$sql_data="select $fld_trx,a.id_supplier,invno,no_fp,tgl_fp,curr,sum(qty*price) amt_trx,
				msu.id_coa_d,msu.id_coa_k,mcoa_d.nm_coa nm_coa_d,mcoa_k.nm_coa nm_coa_k,supplier  
				from $tbl_trx a inner join masteritem s on a.id_item=s.id_item 
				inner join masterdesc mde on s.id_gen=mde.id 
				inner join mastercolor mco on mde.id_color=mco.id 
				inner join masterweight mwe on mco.id_weight=mwe.id
				inner join masterlength mle on mwe.id_length=mle.id
				inner join masterwidth mwi on mle.id_width=mwi.id 
				inner join mastercontents mcn on mwi.id_contents=mcn.id 
				inner join mastertype2 mty on mcn.id_type=mty.id 
				inner join mastersubgroup msu on mty.id_sub_group=msu.id
				inner join mastercoa mcoa_d on msu.id_coa_d=mcoa_d.id_coa 
				inner join mastercoa mcoa_k on msu.id_coa_k=mcoa_k.id_coa 
				inner join mastersupplier msup on a.id_supplier=msup.id_supplier where $fld_cri='$transno'";
		}
		#echo $sql_data;
		$rs=mysql_fetch_array(mysql_query($sql_data));
		$nm_company=flookup("company","mastercompany","company!=''");
		$period=date("m/Y",strtotime($rs['trx_date']));
		$num_journal=urutkan_inq("J-".$period,"J/".$period);
		$date_journal=fd($rs['trx_date']);
		$reff_doc=$transno;
		$fg_intercompany="0";
		$fg_post="0";
		$dateadd=date('Y-m-d H:m:s');
		// KEBALIKAN DEBET JADI KREDIT DAN SEBALIKNYA
		$cek = flookup("count(*)",$tbl,"reff_doc='$transno'");
		if ($cek=="0")
		{	$sql = "insert into $tbl (company,period,num_journal,date_journal,type_journal,reff_doc,fg_intercompany,
				fg_post,dateadd,useradd) 
				values ('$nm_company','$period','$num_journal','$date_journal','$type_journal',
				'$reff_doc','$fg_intercompany','$fg_post',
				'$dateadd','$user')";
			insert_log($sql,$user);
			$id_journal = flookup("id_journal",$tbl,"reff_doc='$transno'");
			$row_id = "1";
			$curr = $rs['curr'];
			$id_coa = $rs['id_coa_k'];
			$nm_coa = $rs['nm_coa_k'];
			$debit = $rs['amt_trx'];
			$credit = 0;
			$description = $rs['supplier'];
			// Debet
			$sql = "insert into fin_journal_d (id_journal,row_id,id_coa,nm_coa,curr,debit,credit,description,dateadd,useradd) 
				values ('$id_journal','$row_id','$id_coa','$nm_coa','$curr','$debit','$credit',
				'$description','$dateadd','$user')";
			insert_log($sql,$user);
			// Kredit
			$row_id = "2";
			$id_coa = $rs['id_coa_d'];
			$nm_coa = $rs['nm_coa_d'];
			$debit = 0;
			$credit = $rs['amt_trx'];
			$sql = "insert into fin_journal_d (id_journal,row_id,id_coa,nm_coa,curr,debit,credit,description,dateadd,useradd) 
				values ('$id_journal','$row_id','$id_coa','$nm_coa','$curr','$debit','$credit',
				'$description','$dateadd','$user')";
			insert_log($sql,$user);
		}	
	}
};
function insert_jurnal_paid($trans,$transno,$user)
{	if($trans!="" and $transno!="")
	{	if($trans=="AP")
		{	$tbl="fin_journal_h";	
			$tbl_trx="bpb";
			$fld_trx="a.bpbno trx_no,pay_date trx_date";
			$fld_cri="a.bpbno";
			$type_journal="2";
		}
		else
		{	$tbl="fin_journal_h";	
			$tbl_trx="bppb";
			$fld_trx="a.bppbno trx_no,pay_date trx_date";
			$fld_cri="a.bppbno";
			$type_journal="1";
		}
		if($trans=="AP")
		{	$sql_data="select $fld_trx,a.id_supplier,invno,no_fp,tgl_fp,a.curr,sum(ap.amount) amt_trx,
				msu.id_coa_d,msu.id_coa_k,mcoa_d.nm_coa nm_coa_d,mcoa_k.nm_coa nm_coa_k,supplier,ap.pay_bank  
				from $tbl_trx a inner join masteritem s on a.id_item=s.id_item 
				inner join masterdesc mde on s.id_gen=mde.id 
				inner join mastercolor mco on mde.id_color=mco.id 
				inner join masterweight mwe on mco.id_weight=mwe.id
				inner join masterlength mle on mwe.id_length=mle.id
				inner join masterwidth mwi on mle.id_width=mwi.id 
				inner join mastercontents mcn on mwi.id_contents=mcn.id 
				inner join mastertype2 mty on mcn.id_type=mty.id 
				inner join mastersubgroup msu on mty.id_sub_group=msu.id
				inner join mastercoa mcoa_d on msu.id_coa_d=mcoa_d.id_coa 
				inner join mastercoa mcoa_k on msu.id_coa_k=mcoa_k.id_coa 
				inner join mastersupplier msup on a.id_supplier=msup.id_supplier 
				inner join acc_pay ap on a.bpbno=ap.bpbno where invno='$transno'";
		}
		else
		{	$sql_data="select $fld_trx,a.id_supplier,invno,no_fp,tgl_fp,a.curr,sum(ar.amount) amt_trx,
				'1301001' id_coa_k,'Piutang Usaha Lokal' nm_coa_k,supplier,ar.pay_bank  
				from $tbl_trx a inner join masterstyle s on a.id_item=s.id_item 
				inner join mastersupplier msup on a.id_supplier=msup.id_supplier 
				inner join acc_rec ar on a.bppbno=ar.bppbno where invno='$transno'";
		}
		#echo $sql_data;
		$rs=mysql_fetch_array(mysql_query($sql_data));
		$nm_company=flookup("company","mastercompany","company!=''");
		$period=date("m/Y",strtotime($rs['trx_date']));
		$num_journal=urutkan_inq("J-".$period,"J/".$period);
		$date_journal=fd($rs['trx_date']);
		$reff_doc=$transno;
		$fg_intercompany="0";
		$fg_post="0";
		$dateadd=date('Y-m-d H:m:s');
		$cek = flookup("count(*)",$tbl,"reff_doc='$transno'");
		if ($cek=="0")
		{	$sql = "insert into $tbl (company,period,num_journal,date_journal,type_journal,reff_doc,fg_intercompany,
				fg_post,dateadd,useradd) 
				values ('$nm_company','$period','$num_journal','$date_journal','$type_journal',
				'$reff_doc','$fg_intercompany','$fg_post',
				'$dateadd','$user')";
			insert_log($sql,$user);
			$id_journal = flookup("id_journal",$tbl,"reff_doc='$transno'");
			$row_id = "1";
			$curr = $rs['curr'];
			if($trans=="AP")
			{	$id_coa = $rs['id_coa_k'];
				$nm_coa = $rs['nm_coa_k'];
			}
			else
			{	$pay_bank=$rs['pay_bank'];
				$rsbank=mysql_fetch_array(mysql_query("select a.id_coa,s.nm_coa from masterbank a inner join mastercoa s 
					on a.id_coa=s.id_coa where id='$pay_bank'"));
				$id_coa = $rsbank['id_coa'];
				$nm_coa = $rsbank['nm_coa'];	
			}
			$debit = $rs['amt_trx'];
			$credit = 0;
			$description = $rs['supplier'];
			// Debet
			$sql = "insert into fin_journal_d (id_journal,row_id,id_coa,nm_coa,curr,debit,credit,description,dateadd,useradd) 
				values ('$id_journal','$row_id','$id_coa','$nm_coa','$curr','$debit','$credit',
				'$description','$dateadd','$user')";
			insert_log($sql,$user);
			// Kredit
			$row_id = "2";
			if($trans=="AP")
			{	$pay_bank=$rs['pay_bank'];
				$rsbank=mysql_fetch_array(mysql_query("select a.id_coa,s.nm_coa from masterbank a inner join mastercoa s 
					on a.id_coa=s.id_coa where id='$pay_bank'"));
				$id_coa = $rsbank['id_coa'];
				$nm_coa = $rsbank['nm_coa'];
			}
			else
			{	$id_coa = $rs['id_coa_k'];
				$nm_coa = $rs['nm_coa_k'];
			}
			$debit = 0;
			$credit = $rs['amt_trx'];
			$sql = "insert into fin_journal_d (id_journal,row_id,id_coa,nm_coa,curr,debit,credit,description,dateadd,useradd) 
				values ('$id_journal','$row_id','$id_coa','$nm_coa','$curr','$debit','$credit',
				'$description','$dateadd','$user')";
			insert_log($sql,$user);
		}	
	}
};
?>
