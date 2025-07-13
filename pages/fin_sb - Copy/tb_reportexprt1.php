<?php
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
$thisday=date('Y-m-d');
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=TRIAL BALANCE $thisday.xls");

include_once '../../include/conn.php';
// Assets class (image, css, etc..)

$period_from=$_GET['fromdate'];
$period_to=$_GET['todate'];


class Config{
    // Loss percentage in fraction
    public static $loss = 0.03; //3%
}
class Coa_helper{
    private $coa_config;
    private $coa_separator = '.';

    public function __construct()
    {
        global $con_new;
        $this->coa_config = $this->get_coa_digit_config();
    }

    public function format_coa($coa){
        $segment = array();

        $start = 0;
        foreach ($this->coa_config as $length){
            $segment[] = substr($coa,$start,$length);
            $start += $length;
        }


        return implode($this->coa_separator, $segment);
    }

    function get_coa_digit_config(){
        global $con_new;

        $q = mysqli_query($con_new, "
            SELECT num_of_digit FROM mastercoaconfig ORDER BY `position`;
        ");

        $rows = array();
        while($row = mysqli_fetch_object($q)){
            $rows[] = $row;
        }

        $result = array();
        foreach($rows as $r){
            $result[] = $r->num_of_digit;
        }
        return $result;
    }
}
class Assets{
    // Logo path
    public static $logo = '../../include/img-01.png';
}
class Model{
    private $conn;
    private $result;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Query helper
     * @param $sql
     * @return $this
     */
    public function query($sql)
    {
        $this->result = mysqli_query($this->conn, $sql);
        if(!$this->result){
            $err = "
            <h3>Database Query Error:</h3>
            <p>".mysqli_error($this->conn)."</p>
            <p>$sql</p>
            ";
//            show_error($err);
        }
        return $this;
    }

    /**
     * Fetch multiple rows helper
     * @return array
     */
    public function result()
    {
        $rows = array();
        if(!$this->result){
            return $rows;
        }
        while($row = mysqli_fetch_object($this->result)){
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Fetch single row helper
     * @return null|object
     */
    public function row()
    {
        return mysqli_fetch_object($this->result);
    }

    public function get_master_company()
    {
        return $this->query("
            SELECT * FROM mastercompany;
        ")->row();
    }

    public function get_accounting_period()
    {
        //TODO: fetch from actual table
        $periods = array();
        for($i=-12;$i<=12;$i++){
            if($i<0){
                $periods[] = date('m/Y', strtotime(date('Y-m-d', time())." $i month"));
            }elseif($i==0){
                $periods[] = date('m/Y', time());
            }else{
                $periods[] = date('m/Y', strtotime(date('Y-m-d', time())." +$i month"));
            }
        }

        return $periods;
//        return array(
//            date('m/Y', strtotime(date('Y-m-d', time()).' -6 month')),
//            date('m/Y', time()),
//            date('m/Y', strtotime(date('Y-m-d', time()).' +6 month')),
//        );
    }

    /**
     * Get report data and transform into report friendly format
     * @param $jo_id Job Order ID
     * @return array of Job Order and Sales Order
     */
	 
	 public function ms_coa($id_coa){
		 //print_r($id_coa);
		 

        $x=$this->query("
            SELECT id_coa,nm_coa from mastercoa where id_coa = '$id_coa';
        ")->row()->nm_coa;
		return $x;

			
		 
	 }
	 
    public function get_report($period_from, $period_to)
    {
		$lepas_period = explode("/",$period_from);
		$tanggal_saldo = $lepas_period[1]."-".$lepas_period[0]."-01";
		$tanggal_saldo = date('Y-m-d', strtotime('-1 days', strtotime($tanggal_saldo))); 

      /*  $sql = "
            SELECT 
                mc.id_coa id_group_coa
                ,mc.nm_coa nm_group_coa
                ,mc2.id_coa
                ,mc2.nm_coa
                ,mc2.fg_posting
                ,mc2.fg_mapping
                ,mc2.fg_active
                ,mc2.post_to
                ,begbal.debit begbal_debit
                ,begbal.credit begbal_credit
                ,mutation.debit mutation_debit
                ,mutation.credit mutation_credit
                ,(IFNULL(begbal.debit,0) + IFNULL(mutation.debit,0)) endbal_debit
                ,(IFNULL(begbal.credit,0) + IFNULL(mutation.credit,0)) endbal_credit
            FROM 
                mastercoa mc
                LEFT JOIN 
                (
                    SELECT * FROM mastercoa WHERE fg_active = '1'
                ) mc2 ON (mc.id_coa = mc2.post_to OR mc.id_coa = mc2.id_coa)
                LEFT JOIN
                (
                    SELECT 
                        fd.id_coa
                        ,fd.curr
                        ,SUM(fd.debit) debit
                        ,SUM(fd.credit) credit
                    FROM 
                        fin_journal_h fh
                        LEFT JOIN fin_journal_d fd ON fh.id_journal = fd.id_journal
                    WHERE fh.period < '$period_from'
                      AND fh.fg_post = '2'
                    GROUP BY 
                        fd.id_coa
                        ,fd.curr
                )begbal ON mc2.id_coa = begbal.id_coa
                LEFT JOIN
                (
                    SELECT 
                        fd.id_coa
                        ,fd.curr
                        ,SUM(fd.debit) debit
                        ,SUM(fd.credit) credit
                    FROM 
                        fin_journal_h fh
                        LEFT JOIN fin_journal_d fd ON fh.id_journal = fd.id_journal
                    WHERE fh.period >= '$period_from' AND fh.period <= '$period_to' 
                      AND fh.fg_post = '2'
                    GROUP BY 
                        fd.id_coa
                        ,fd.curr
                ) mutation ON mc2.id_coa = mutation.id_coa
            WHERE 1=1
                AND mc.fg_active = '1'
                AND mc.fg_posting = '0'
                AND mc2.id_coa NOT IN ('21001', 	'21002', 	'21003', 	'21004', 	'21005', 	'21006', 	'21007', 	'21008', 	'21009', 	'21010', 	'21011', 	'21049', 	'21101', 	'21102', 	'21103', 	'21104', 	'21105', 	'21106', 	'21107', 	'21108', 	'21109', 	'21110', 	'21111', 	'21149')
            ORDER BY 
                mc.id_coa
                ,mc2.id_coa
        ";
	*/
	
	
	
		$sql = "SELECT 		
			COA.id_group_coa
			,COA.nm_group_coa
            ,COA.id_coa
            ,COA.nm_coa
			,IF(COA.segment >= 1 AND COA.segment <= 3,ifnull(SALDO.n_saldo,0),'0') begbal_debit
			,IF(COA.segment >= 1 AND COA.segment <= 3,'0',ifnull(SALDO.n_saldo,0)) begbal_credit	
			,IF(COA.segment >= 1 AND COA.segment <= 3
				,ifnull(SALDO.n_saldo,0) + ifnull(COA.endbal_debit,0)
				,0) endbal_debit
			,IF(COA.segment >= 1 AND COA.segment <= 3
				,0
				,ifnull(SALDO.n_saldo,0) + ifnull(COA.endbal_credit,0)) endbal_credit
            ,COA.fg_posting
            ,COA.fg_mapping
            ,COA.fg_active
            ,COA.post_to

            ,COA.mutation_debit
            ,COA.mutation_credit

			,COA.segment
			from
(SELECT 
                 mc.id_coa id_group_coa
                ,mc.nm_coa nm_group_coa
                ,mc2.id_coa
                ,mc2.nm_coa
                ,mc2.fg_posting
                ,mc2.fg_mapping
                ,mc2.fg_active
                ,mc2.post_to
                ,begbal.debit begbal_debit
                ,begbal.credit begbal_credit
                ,mutation.debit mutation_debit
                ,mutation.credit mutation_credit
                ,(IFNULL(begbal.debit,0) + IFNULL(mutation.debit,0)) endbal_debit
                ,(IFNULL(begbal.credit,0) + IFNULL(mutation.credit,0)) endbal_credit
				,SUBSTRING(mc.id_coa,'1','1') segment
            FROM 
                mastercoa mc
                LEFT JOIN 
                (
                    SELECT * FROM mastercoa WHERE fg_active = '1'
                ) mc2 ON (mc.id_coa = mc2.post_to OR mc.id_coa = mc2.id_coa)
                LEFT JOIN
                (
                    SELECT 
                        fd.id_coa
                        ,fd.curr
                        ,SUM(fd.debit) debit
                        ,SUM(fd.credit) credit
                    FROM 
                        fin_journal_h fh
                        LEFT JOIN fin_journal_d fd ON fh.id_journal = fd.id_journal
                    WHERE fh.period < '$period_from'
                      AND fh.fg_post = '2'
                    GROUP BY 
                        fd.id_coa
                        ,fd.curr
                )begbal ON mc2.id_coa = begbal.id_coa
                LEFT JOIN
                (
                    SELECT 
                        fd.id_coa
                        ,fd.curr
                        ,SUM(fd.debit) debit
                        ,SUM(fd.credit) credit
                    FROM 
                        fin_journal_h fh
                        LEFT JOIN fin_journal_d fd ON fh.id_journal = fd.id_journal
                    WHERE fh.period >= '$period_from' AND fh.period <= '$period_to' 
                      AND fh.fg_post = '2'
                    GROUP BY 
                        fd.id_coa
                        ,fd.curr
                ) mutation ON mc2.id_coa = mutation.id_coa
            WHERE 1=1
                AND mc.fg_active = '1'
                AND mc.fg_posting = '0'
                AND mc2.id_coa NOT IN ('21001', 	'21002', 	'21003', 	'21004', 	'21005', 	'21006', 	'21007', 	'21008', 	'21009', 	'21010', 	'21011', 	'21049', 	'21101', 	'21102', 	'21103', 	'21104', 	'21105', 	'21106', 	'21107', 	'21108', 	'21109', 	'21110', 	'21111', 	'21149')
            ORDER BY 
                mc.id_coa
                ,mc2.id_coa)COA
		LEFT JOIN		
(SELECT n_idcoa,v_namacoa,n_saldo,d_dateupdate FROM fin_saldoawal )SALDO
	ON COA.id_coa = SALDO.n_idcoa
WHERE date(SALDO.d_dateupdate) = '$tanggal_saldo'";


        $query = $this
            ->query($sql)
            ->result();

        $tb = array(
                'total' => array(
                       'begbal_debit' => 0, 
                       'begbal_credit' => 0, 
                       'mutation_debit' => 0, 
                       'mutation_credit' => 0, 
                       'endbal_debit' => 0, 
                       'endbal_credit' => 0, 
                )
        );
//echo "$sql";
        $tb['total'] = json_decode(json_encode($tb['total']));
        if(count($query)){
            foreach($query as $r){
                if($r->fg_posting == '0'){
                    $tb[$r->id_group_coa]['h'] = $r;
                }else{
                    $tb[$r->id_group_coa]['d'][$r->id_coa] = $r;
                    $tb[$r->id_group_coa]['h']->begbal_debit += $r->begbal_debit;
                    $tb[$r->id_group_coa]['h']->begbal_credit += $r->begbal_credit;
                    $tb[$r->id_group_coa]['h']->mutation_debit += $r->mutation_debit;
                    $tb[$r->id_group_coa]['h']->mutation_credit += $r->mutation_credit;
                    $tb[$r->id_group_coa]['h']->endbal_debit += $r->endbal_debit;
                    $tb[$r->id_group_coa]['h']->endbal_credit += $r->endbal_credit;
                }

                $tb['total']->begbal_debit += $r->begbal_debit;
                $tb['total']->begbal_credit += $r->begbal_credit;
                $tb['total']->mutation_debit += $r->mutation_debit;
                $tb['total']->mutation_credit += $r->mutation_credit;
                $tb['total']->endbal_debit += $r->endbal_debit;
                $tb['total']->endbal_credit += $r->endbal_credit;
            }
        }

        return $tb;
    }
}


// Instantiate model object
$m = new Model($con_new);
$ch = new Coa_helper();

// Get report data, assign into job order (header) and sales order(detail) vars
if($period_from and $period_to) {
    $tb = $m->get_report($period_from, $period_to);
//   echo '<pre>';print_r($tb);exit();
}else{
    $tb = array();
}

// Get master company
$company = $m->get_master_company();
$acct_period = $m->get_accounting_period();
$curr_period = date('m/Y', time());


	



// Close db connection
//$con_new->close();
?>



<h3>TRIALE BALANCE </h3>
<h5> -</h5>

            
<table border="1" cellpadding="5">
        <thead >
        <tr style='background-color: yellow; text-align:left; color:Black; font-weight:bold;'>
            <th rowspan="2">GROUP ID</th>
            <th rowspan="2">COA ID</th>
            <th rowspan="2">ACCOUNT</th>
            <th colspan="2">BEGINNING BALANCE</th>
            <th colspan="2">MUTATION</th>
            <th colspan="2">ENDING BALANCE</th>
        </tr>
        <tr style='background-color: yellow; text-align:left; color:Black; font-weight:bold;'>
            <th width='100'>DEBIT</th>
            <th width='100'>CREDIT</th>
            <th width='100'>DEBIT</th>
            <th width='100'>CREDIT</th>
            <th width='100'>DEBIT</th>
            <th width='100'>CREDIT</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($tb as $_id_group_coa => $_coa): if($_id_group_coa=='total'){continue;} $_segment = substr($_id_group_coa,0,1);?>
        <tr class="" style='background-color: #fffad2; color:Black; font-weight:bold; '>
            <td><?=str_replace('.','_',$_id_group_coa)?></td>
            <td><?=$_id_group_coa?></td>
            <td width='400'><?php echo $m->ms_coa($_id_group_coa);?></td>
            <td class="text-right" style='text-align:right;'><?=number_format($_coa['h']->begbal_debit)?></td>
            <td class="text-right" style='text-align:right;'><?=number_format($_coa['h']->begbal_credit)?></td>
            <td class="text-right" style='text-align:right;'><?=number_format($_coa['h']->mutation_debit)?></td>
            <td class="text-right" style='text-align:right;'><?=number_format($_coa['h']->mutation_credit)?></td>
            <?php 
				if((intval($_segment) >= 1) AND (intval($_segment) <=3)) 
				{ ?>
            <td class="text-right" style='text-align:right;'><?=number_format($_coa['h']->endbal_debit); ?></td>
            <td class="text-right" style='text-align:right;'>0</td>
            <?php } else { ?>
            <td class="text-right" style='text-align:right;'>0</td>
           <td class="text-right" style='text-align:right;'><?=number_format($_coa['h']->endbal_credit); ?></td>
            <?php } ?>
        </tr>
            <?php if(isset($_coa['d'])):?>
                <?php foreach($_coa['d'] as $__coa):?>
                    <tr class="item_<?=str_replace('.','_',$_id_group_coa)?> success hidden">
                        <td>&nbsp;</td>
                        <td><?=$ch->format_coa($__coa->id_coa)?></td>
                        <td><?=$__coa->nm_coa?></td>
                        <td class="text-right" style='text-align:right;'><?=number_format($__coa->begbal_debit)?></td>
                        <td class="text-right" style='text-align:right;'><?=number_format($__coa->begbal_credit)?></td>
                        <td class="text-right" style='text-align:right;'><?=number_format($__coa->mutation_debit)?></td>
                        <td class="text-right" style='text-align:right;'><?=number_format($__coa->mutation_credit)?></td>
						<?php if((intval($_segment) >= 1) AND (intval($_segment) <=3)) 
							{ ?>
						<td class="text-right" style='text-align:right;'><?=number_format($__coa->endbal_debit); ?></td>
						<td class="text-right" style='text-align:right;'>0</td>
						<?php } else { ?>
						<td class="text-right" style='text-align:right;'>0</td>
						<td class="text-right" style='text-align:right;'><?=number_format($__coa->endbal_credit); ?></td>
						<?php } ?>						
						
						

                    </tr>
                <?php endforeach;?>
            <?php endif;?>
        <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="3">TOTAL</th>
            <th class="text-right"><?=number_format($tb['total']->begbal_debit)?></th>
            <th class="text-right"><?=number_format($tb['total']->begbal_credit)?></th>
            <th class="text-right"><?=number_format($tb['total']->mutation_debit)?></th>
            <th class="text-right"><?=number_format($tb['total']->mutation_credit)?></th>
            <th class="text-right"><?=number_format($tb['total']->endbal_debit)?></th>
            <th class="text-right"><?=number_format($tb['total']->endbal_credit)?></th>
        </tr>
        </tfoot>
    </table>

    <script>
    function toggle_detail(group){
        if( $('.item_'+group).hasClass('hidden') ){
            $('.item_'+group).removeClass('hidden');
            $('.btnToggle_'+group).html('<i class="fa fa-minus"></i>');
        }else{
            $('.item_'+group).addClass('hidden');
            $('.btnToggle_'+group).html('<i class="fa fa-plus"></i>');
        }
    }
</script>

