<?php
// Load db connection
include_once '../../include/conn.php';
// Assets class (image, css, etc..)
class Config{
    // Loss percentage in fraction
    public static $loss = 0.03; //3%

    public function get_normal_balance($id_coa){
        $pref = substr($id_coa,0,1);

        if($pref == 2 or $pref == 3 or $pref == 4){
            return 'k'; // Kredit
        }else{
            return 'd'; // Debit
        }
    }
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
class Helper{
    public function count_end_balance($d, $k, $id_coa){
        $C = new Config();
        $nb = $C->get_normal_balance($id_coa);

        if($nb == 'k'){
            return $k - $d;
        }else{
            return $d - $k;
        }
    }
}
class Assets{
    // Logo path
    public static $logo = '../../include/img-01.png';
}
class Model{
    private $conn;
    private $result;
    private $fg_mapping_bs = '1';// Balance sheet flag

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

    public function get_period_key($period_from, $period_to)
    {
        $_pf = explode('/', $period_from);
        $_pf = $_pf[1].$_pf[0];

        $_pt = explode('/', $period_to);
        $_pt = $_pt[1].$_pt[0];

        // Invalid parameter, to is lower than from
        if($_pt < $_pf){
            return array();
        }

        $periods = array();
        for($p = $_pf; $p <= $_pt; $p = date('Ym', strtotime(date('Y-m-d', strtotime($p.'01')).' +1 month'))){
            $_key = $p;
            $_period = substr($p,4, 2).'/'.substr($p,0, 4);
            $periods[$_key] = $_period;
        }

        return $periods;
    }

    /**
     * Get report data and transform into report friendly format
     * @param $jo_id Job Order ID
     * @return array of Job Order and Sales Order
     */
    public function get_report($period_from, $period_to)
    {
        $sql = "
            SELECT 
                mc.id_coa id_group_coa
                ,mc.nm_coa nm_group_coa
                ,mccat.id_map
                ,mccat.nm_map
                ,mc2.id_coa
                ,mc2.nm_coa
                ,mc2.fg_posting
                ,mc2.fg_mapping
                ,mc2.fg_active
                ,mc2.post_to
            
                {PERIOD_S}
           
            FROM 
                mastercoa mc
                LEFT JOIN 
                (
                    SELECT * FROM mastercoa WHERE fg_active = '1'
                ) mc2 ON (mc.id_coa = mc2.post_to OR mc.id_coa = mc2.id_coa)
                LEFT JOIN mastercoacategory mccat ON mc.map_category = mccat.id_map
              
                {PERIOD_F}
              
            WHERE 1=1
                AND mc.fg_active = '1'
                AND mc.fg_posting = '0'
                AND mc.fg_mapping = '{$this->fg_mapping_bs}'
            ORDER BY 
                mccat.order
                ,mc.id_coa
                ,mc2.id_coa
        ";

        $periods = $this->get_period_key($period_from, $period_to);

        $sql_period_s = '';
        $sql_period_f = '';
        foreach($periods as $key => $period ){
            $sql_period_s .=
            "    ,period_$key.debit debit_$key
                ,period_$key.credit credit_$key
            ";
            $sql_period_f .=
            "    LEFT JOIN(
SELECT   sub_sub_period_$key.n_idcoa id_coa
		,sub_sub_period_$key.v_namacoa
		,sub_sub_period_$key.n_saldo
		,sub_sub_period_$key.d_dateupdate
		,sub_sub_period_$key.segment
		,sub_sub_period_$key.endbal_debit debit
		,sub_sub_period_$key.endbal_credit credit
		
		FROM(
SELECT  SALDO.n_idcoa
		,SALDO.v_namacoa
		,SALDO.n_saldo
		,SALDO.d_dateupdate
		,SALDO.segment
		,IF(SALDO.segment >= 1 AND SALDO.segment <= 3
			,ifnull(SALDO.n_saldo,0) + ifnull(sub_period_$key.debit,0)
			,0) endbal_debit
		,IF(SALDO.segment >= 1 AND SALDO.segment <= 3
			,0
			,ifnull(SALDO.n_saldo,0) + ifnull(sub_period_$key .credit,0)) endbal_credit
FROM
(SELECT n_idcoa,v_namacoa,n_saldo,d_dateupdate,SUBSTRING(n_idcoa,'1','1') segment FROM fin_history_saldo WHERE date(d_dateupdate) = DATE_SUB(DATE_FORMAT(CONCAT('$key','01'), '%Y-%m-%d'), INTERVAL 1 DAY)) SALDO
		
		LEFT JOIN ( SELECT fd.id_coa ,fd.curr ,SUM(fd.debit) debit
					,SUM(fd.credit) credit 
						FROM fin_journal_h fh 
						LEFT JOIN fin_journal_d fd 
					ON fh.id_journal = fd.id_journal 
					WHERE CONCAT(SUBSTR(fh.period,4,4), SUBSTR(fh.period,1,2)) <= '$key' AND fh.fg_post = '2' 
					GROUP BY fd.id_coa ,fd.curr 
		)sub_period_$key  ON SALDO.n_idcoa = sub_period_$key .id_coa
		GROUP BY SALDO.n_idcoa) sub_sub_period_$key 
                )period_$key ON mc2.id_coa = period_$key.id_coa
            ";
        }

        $sql = str_replace('{PERIOD_S}', $sql_period_s, $sql);
        $sql = str_replace('{PERIOD_F}', $sql_period_f, $sql);

        $query = $this
            ->query($sql)
            ->result();
		//echo "$sql";
        $tb = array();
        if(count($query)){
            foreach($query as $r){
                if(!isset($tb[$r->id_map]['h'])) {
                    $tb[$r->id_map]['h'] = clone($r);
                }

                if($r->fg_posting == '0'){
                    $tb[$r->id_map]['d'][$r->id_group_coa]['h'] = clone($r);
                }else{
                    $tb[$r->id_map]['d'][$r->id_group_coa]['d'][$r->id_coa] = clone($r);
                    foreach($periods as $key => $period){
                        $tb[$r->id_map]['d'][$r->id_group_coa]['h']->{'debit_'.$key} += $r->{'debit_'.$key};
                        $tb[$r->id_map]['d'][$r->id_group_coa]['h']->{'credit_'.$key} += $r->{'credit_'.$key};

                        $tb[$r->id_map]['h']->{'debit_'.$key} += $r->{'debit_'.$key};
                        $tb[$r->id_map]['h']->{'credit_'.$key} += $r->{'credit_'.$key};
                    }
                }
            }
        }

        return $tb;
    }
}

// Retrieve parameters
if(!isset($_GET['period_from'])){
    $period_from = '';
}else{
    $period_from = $_GET['period_from'];
}
if(!isset($_GET['period_to'])){
    $period_to = '';
}else{
    $period_to = $_GET['period_to'];
}
// Instantiate model object
$m = new Model($con_new);
$ch = new Coa_helper();

// Instantiate helper object
$h = new Helper();

// Get report data, assign into job order (header) and sales order(detail) vars
if($period_from and $period_to) {
    $tb = $m->get_report($period_from, $period_to);
    $periods = $m->get_period_key($period_from, $period_to);
//    echo '<pre>';print_r($tb);exit();
}else{
    $tb = array();
    $periods = array();
}

// Get master company
$company = $m->get_master_company();

$acct_period = $m->get_accounting_period();
$curr_period = date('m/Y', time());

// Close db connection
$con_new->close();
?>

<div id="formBs" class='box'>
    <div class='box-body'>
        <form method='get' name='form' enctype='multipart/form-data' action="" onsubmit='return validasi()'>
            <div class="panel panel-default">
                <div class="panel-heading">Periode Akuntansi</div>
                <div class="panel-body row">
                    <div class="col-md-3">
                        <div class='form-group'>
                            <label for="period_from">Dari</label>

                            <input type='text' id='period_from' class='form-control monthpicker' name='period_from'
                                   placeholder='MM/YYYY' value='<?=isset($period_from)?$period_from:''?>' required>
                            <?php /*
                            <select id='period_from' class='form-control select2' name='period_from'>
                                <option value="" disabled selected>Pilih Periode</option>
                                <?php if(@$period_from):?>
                                <?php foreach($acct_period as $_id):?>
                                    <option value="<?=$_id?>" <?=(@$period_from==$_id) ? 'selected':''?> ><?=$_id?></option>
                                <?php endforeach;?>
                                <?php else:?>
                                <?php foreach($acct_period as $_id):?>
                                    <option value="<?=$_id?>" <?=(@$curr_period==$_id) ? 'selected':''?> ><?=$_id?></option>
                                <?php endforeach;?>
                                <?php endif;?>
                            </select>*/?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class='form-group'>
                            <label>Sampai</label>

                            <input type='text' id='period_to' class='form-control monthpicker' name='period_to'
                                   placeholder='MM/YYYY' value='<?=isset($period_to)?$period_to:''?>' required>
                            <?php /*
                            <select id='period_to' class='form-control select2' name='period_to'>
                                <option value="" disabled selected>Pilih Periode</option>
                                <?php if(@$period_from):?>
                                    <?php foreach($acct_period as $_id):?>
                                        <option value="<?=$_id?>" <?=(@$period_to==$_id) ? 'selected':''?> ><?=$_id?></option>
                                    <?php endforeach;?>
                                <?php else:?>
                                    <?php foreach($acct_period as $_id):?>
                                        <option value="<?=$_id?>" <?=(@$curr_period==$_id) ? 'selected':''?> ><?=$_id?></option>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </select>*/?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="hidden" name="mod" value="repbs" />
                            <button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if(count($tb)):?>
<a href="bs_reportexprt.php?&fromdate=<?=$period_to?>&todate=<?=$period_to?>" class="btn btn-info" title="Export Excel" ><i class='fa fa-file-excel-o'> Export Excel</i></a>
<div class='box'>
    <div class='box-body'>
    <table class="table table-condensed table-bordered">
        <thead>
        <tr>
            <th rowspan="2">&nbsp;</th>
            <th rowspan="2">DESCRIPTION</th>
            <th colspan="<?=count($periods)?>">PERIODE</th>
        </tr>
        <tr>
            <?php foreach($periods as $period):?>
            <th><?=$period?></th>
            <?php endforeach;?>
        </tr>
        </thead>
        <tbody>
        <?php foreach($tb as $_id_map => $_map): ?>
            <tr class="">
                <td><a class="btn btn-xs btnToggleMap_<?=$_id_map?>" onclick="toggle_map_detail('<?=$_id_map?>')" ><i class="fa fa-plus"></i></a></td>
                <td><?=$_map['h']->nm_map?></td>
                <?php foreach($periods as $key => $period):?>
                    <td class="text-right">
                        <?=number_format($h->count_end_balance($_map['h']->{'debit_'.$key}, $_map['h']->{'credit_'.$key}, $_map['h']->id_coa))?>
                    </td>
                <?php endforeach;?>
            </tr>
            <?php foreach($_map['d'] as $_id_group_coa => $_coa): if($_id_group_coa=='total'){continue;}?>
                <tr class="map_item_<?=$_id_map?> warning hidden">
                    <td> <a class="btn btn-xs btnToggle_<?=$_id_group_coa?>" onclick="toggle_detail('<?=$_id_group_coa?>')" ><i class="fa fa-plus"></i></a></td>
                    <td><?=$ch->format_coa($_coa['h']->id_coa)?> - <?=$_coa['h']->nm_coa?></td>
                    <?php foreach($periods as $key => $period):?>
                    <td class="text-right">
                        <?=number_format($h->count_end_balance($_coa['h']->{'debit_'.$key}, $_coa['h']->{'credit_'.$key}, $_coa['h']->id_coa))?>
                    </td>
                    <?php endforeach;?>
                </tr>
                <?php if(isset($_coa['d'])):?>
                    <?php foreach($_coa['d'] as $__coa):?>
                        <tr class="item_<?=$_id_group_coa?> success hidden">
                            <td>&nbsp;</td>
                            <td><?=$ch->format_coa($__coa->id_coa)?> - <?=$__coa->nm_coa?></td>
                            <?php foreach($periods as $key => $period):?>
                            <td class="text-right">
                                <?=number_format($h->count_end_balance($__coa->{'debit_'.$key}, $__coa->{'credit_'.$key}, $__coa->id_coa))?>
                            </td>
                            <?php endforeach;?>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
            <?php endforeach;?>
        <?php endforeach;?>
        </tbody>
        <?php /*
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
        </tfoot> */ ?>
    </table>
    </div>
</div>
<?php endif;?>

<script>
    function validasi(){
        var from = $('#period_from').val();
        var to = $('#period_to').val();

        if(from && to){
            return true;
        }else{
            alert("Mohon pilih periode");
            return false;
        }
    }

    function toggle_map_detail(group){
        if( $('.map_item_'+group).hasClass('hidden') ){
            $('.map_item_'+group).removeClass('hidden');
            $('.btnToggleMap_'+group).html('<i class="fa fa-minus"></i>');
        }else{
            $('.map_item_'+group).addClass('hidden');
            $('.btnToggleMap_'+group).html('<i class="fa fa-plus"></i>');
        }
    }
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
