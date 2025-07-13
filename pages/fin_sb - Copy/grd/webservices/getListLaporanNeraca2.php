<?php 
		$data = $_POST;

if($data['code'] == '1' ){
	$getListData = new getListData();
	$List = $getListData->get($_POST['from'],$_POST['to']);
	print_r($List);
}
//else{
//	exit;
//}
class getListData {
	public function get($from,$to){
        //echo $from;
		$explode = explode("/",$from);
		$from = $explode[1]."-".$explode[0]."-01";
		$explode = explode("/",$to);
		$to = $explode[1]."-".$explode[0]."-01";
        $to = date('Y-m-d', strtotime($to.' +1 month'));

        $months = array();
        $mIterator = $from;
        $quit = false;
        do{
            $months[$mIterator] = date('M y', strtotime($mIterator));
            if($mIterator == $to){
                unset($months[$mIterator]);
                $quit = true;
            }else {
                $mIterator = date('Y-m-d', strtotime($mIterator . ' +1 month'));
            }
        }
        while(!$quit);

//print_r($to);
		include __DIR__ .'/../../../include/conn.php';
		$q = "
			select 
				mc.id_group_coa
				,mc.nm_group_coa 
	            ,mc.post_to
				,mc.id_coa 
				,mc.nm_coa 
				,beg.beg_debit
				,beg.beg_credit
				,tr.bulan
				,tr.t_debit
				,tr.t_credit
			from 
			(
				SELECT 
					mc.id_coa id_group_coa
					,mc.nm_coa nm_group_coa
					,mc2.id_coa
					,mc2.nm_coa
					,mc2.fg_posting
					,mc2.fg_mapping
					,mc2.fg_active
					,mc2.post_to
					,mccat.id_map
					,mccat.nm_map
					,mccat2.id_map id_map2
					,mccat2.nm_map nm_map2
				FROM 
					mastercoa mc
					LEFT JOIN 
					(
						SELECT * FROM mastercoa WHERE fg_active = '1'
					) mc2 ON (mc.id_coa = mc2.post_to OR mc.id_coa = mc2.id_coa)
					LEFT JOIN mastercoacategory mccat ON mc.map_category = mccat.id_map
					LEFT JOIN mastercoacategory mccat2 ON mc2.map_category = mccat2.id_map
				WHERE 1=1
					AND mc.fg_active = '1'
					AND mc.fg_posting = '0'
			) mc
			left join (
				select 
					id_coa, 
					sum(beg_debit) beg_debit, 
					sum(beg_credit) beg_credit
				from (
				select 
					fjd.id_coa
					,fjh.date_journal 
					,fjd.curr
					,IFNULL(rate.rate,1) rate
					,(fjd.debit * IFNULL(rate.rate,1)) beg_debit
					,(fjd.credit * IFNULL(rate.rate,1)) beg_credit
				from fin_journal_d fjd 
				inner join fin_journal_h fjh on fjd.id_journal = fjh.id_journal 
				LEFT JOIN (
					SELECT rate,tanggal,curr FROM masterrate WHERE v_codecurr='HARIAN'
				)rate on rate.tanggal = fjh.date_journal and rate.curr = fjd.curr 
				where 1=1
					and fjd.id_coa is not null
					and fjd.id_coa != ''
					and fjh.fg_post = 2
					and fjh.date_journal < '$from'
				) beg
				group by
					id_coa
			) beg on mc.id_coa = beg.id_coa
			left join (
				select 
					id_coa, 
					DATE_FORMAT(date_journal, \"%Y-%m-01\") bulan,
					sum(beg_debit) t_debit, 
					sum(beg_credit) t_credit
				from (
				select 
					fjd.id_coa
					,fjh.date_journal 
					,fjd.curr
					,IFNULL(rate.rate,1) rate
					,(fjd.debit * IFNULL(rate.rate,1)) beg_debit
					,(fjd.credit * IFNULL(rate.rate,1)) beg_credit
				from fin_journal_d fjd 
				inner join fin_journal_h fjh on fjd.id_journal = fjh.id_journal 
				LEFT JOIN (
					SELECT rate,tanggal,curr FROM masterrate WHERE v_codecurr='HARIAN'
				)rate on rate.tanggal = fjh.date_journal and rate.curr = fjd.curr 
				where 1=1
					and fjd.id_coa is not null
					and fjd.id_coa != ''
					and fjh.fg_post = 2
					and fjh.date_journal >= '$from'
					and fjh.date_journal < '$to'
				) tr
				group by
					id_coa
					,DATE_FORMAT(date_journal, \"%Y-%m-01\")
			) tr on mc.id_coa = tr.id_coa
			ORDER BY 
				mc.id_group_coa
				,mc.id_coa
		";

// echo $q;
		$stmt = mysql_query($q);
		$outp = '';
		$td = '';


		$rows = array();
        while($row = mysql_fetch_array($stmt)){
        	$rows[] = $row;
        }

        $group_rows = array();
        $debit_per_id = array();
        $credit_per_id = array();
        foreach($rows as $r){
        	if(!isset($group_rows[$r['id_coa']])){
                $group_rows[$r['id_coa']] = $r;
                $group_rows[$r['id_coa']]['months'] = array();
                // Dari beginning balance, nilainya di bawa terus sampai terakhir
                $debit_per_id[$r['id_coa']] = (float) $r['beg_debit'];
                $credit_per_id[$r['id_coa']] = (float) $r['beg_credit'];
			}

			if($r['bulan']){
                $debit_per_id[$r['id_coa']] += $r['t_debit'];
                $credit_per_id[$r['id_coa']] += $r['t_credit'];

                $_debit = $debit_per_id[$r['id_coa']];
        	    $_credit = $credit_per_id[$r['id_coa']];

                $group_rows[$r['id_coa']]['months'][$r['bulan']] = array(
                	'd' => $_debit,
                	'c' => $_credit,
				);
			}
		}


		foreach($group_rows as $row){
            if($row['post_to']=="0") {
                $td .= "<tr class='info'>";
                $td .= "<td align='left'><strong>$row[id_coa]</strong></td>";
                $td .= "<td align='left'><strong>$row[nm_coa]</strong></td>";
                foreach ($months as $k => $v) {
                    $td .= "<td align='right'>-</td>";
                }
                $td .= "</tr>";
            }else{
                $td .= "<tr>";
                $td .= "<td align='left'>$row[id_coa]</td>";
                $td .= "<td align='left'>$row[nm_coa]</td>";
                foreach ($months as $k => $v) {
                    if (isset($row['months'][$k])) {
                        $_val = $row['months'][$k]['d'] - $row['months'][$k]['c'];
                        $td .= "<td align='right'>".number_format($_val)."</td>";
                    } else {
                        $td .= "<td align='right'>0</td>";
                    }
                }
                $td .= "</tr>";
            }
		}

		$tbl = '
        <thead>
        <tr>
            <th colspan="'.(count($months)+2).'" style="font-size: 12px; border-collapse: collapse; border: none;"><strong>PT.NIRWANA ALABARE GARMENT</strong></th>
        </tr>
        <tr>
            <th colspan="'.(count($months)+2).'" style=" border-collapse: collapse; border: none;"><strong>RINCIAN BALANCE SHEET</strong></th>
        </tr>
        <tr>
            <th colspan="'.(count($months)+2).'" style="font-size: 12px; border-collapse: collapse; border: none;" >
            <strong>PERIODE: <span id="label_from"> </span> s/d <span id="label_to"> </span></strong>
            </th>
        </tr>
        <tr>
            <th rowspan="2">COA</th>
            <th rowspan="2">DESCRIPTIONS</th>
            <th colspan="'.count($months).'">BULAN</th>
		</tr>
		<tr>';
        foreach($months as $m){
            $tbl .= "<th>$m</th>";
        }
        $tbl .= '</tr></thead>';

        $tbl .= "<tbody>$td</tbody>";
		
		$result = '{ "status":"ok", "message":"1", "records":['.$outp.']    } <-|->'.$tbl;
		return $result;
	}
}




?>




