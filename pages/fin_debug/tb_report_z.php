<?php
// Load db connection
include_once '../../include/conn.php';
// Assets class (image, css, etc..)
class Config{
    // Loss percentage in fraction
    public static $loss = 0.03; //3%
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
            show_error($err);
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
                    GROUP BY 
                        fd.id_coa
                        ,fd.curr
                ) mutation ON mc2.id_coa = mutation.id_coa
            WHERE 1=1
                AND mc.fg_active = '1'
                AND mc.fg_posting = '0'
            ORDER BY 
                mc.id_coa
                ,mc2.id_coa
        ";
        #echo $sql;
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

// Get report data, assign into job order (header) and sales order(detail) vars
if($period_from and $period_to) {
    $tb = $m->get_report($period_from, $period_to);
//    echo '<pre>';print_r($tb);exit();
}else{
    $tb = array();
}

// Get master company
$company = $m->get_master_company();

// Close db connection
$con_new->close();
?>

<div id="formTb" class='box'>
    <div class='box-body'>
        <form method='get' name='form' enctype='multipart/form-data' action="" onsubmit='return validasi()'>
            <div class="panel panel-default">
                <div class="panel-heading">Periode Akuntansi</div>
                <div class="panel-body row">
                    <div class="col-md-3">
                        <div class='form-group'>
                            <label>Dari</label>
                            <input type='text' id='datemy' class='form-control' name='period_from'
                                   placeholder='MM/YYYY' value='<?=isset($period_from)?$period_from:''?>' required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class='form-group'>
                            <label>Sampai</label>
                            <input type='text' id='datemy1' class='form-control' name='period_to'
                                   placeholder='MM/YYYY' value='<?=isset($period_to)?$period_to:''?>' required>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="hidden" name="mod" value="reptb" />
                            <button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if(count($tb)):?>
<div class='box'>
    <div class='box-body'>
    <table class="table table-condensed table-bordered">
        <thead>
        <tr>
            <th rowspan="2">&nbsp;</th>
            <th rowspan="2">COA ID</th>
            <th rowspan="2">ACCOUNT</th>
            <th colspan="2">BEGINNING BALANCE</th>
            <th colspan="2">MUTATION</th>
            <th colspan="2">ENDING BALANCE</th>
        </tr>
        <tr>
            <th>DEBIT</th>
            <th>CREDIT</th>
            <th>DEBIT</th>
            <th>CREDIT</th>
            <th>DEBIT</th>
            <th>CREDIT</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($tb as $_id_group_coa => $_coa): if($_id_group_coa=='total'){continue;}?>
        <tr class="">
            <td><a class="btn btn-xs btnToggle_<?=$_id_group_coa?>" onclick="toggle_detail('<?=$_id_group_coa?>')" ><i class="fa fa-plus"></i></a></td>
            <td><?=$_coa['h']->id_coa?></td>
            <td><?=$_coa['h']->nm_coa?></td>
            <td class="text-right"><?=number_format($_coa['h']->begbal_debit)?></td>
            <td class="text-right"><?=number_format($_coa['h']->begbal_credit)?></td>
            <td class="text-right"><?=number_format($_coa['h']->mutation_debit)?></td>
            <td class="text-right"><?=number_format($_coa['h']->mutation_credit)?></td>
            <td class="text-right"><?=number_format($_coa['h']->endbal_debit)?></td>
            <td class="text-right"><?=number_format($_coa['h']->endbal_credit)?></td>
        </tr>
            <?php if(isset($_coa['d'])):?>
                <?php foreach($_coa['d'] as $__coa):?>
                    <tr class="item_<?=$_id_group_coa?> success hidden">
                        <td>&nbsp;</td>
                        <td><?=$__coa->id_coa?></td>
                        <td><?=$__coa->nm_coa?></td>
                        <td class="text-right"><?=number_format($__coa->begbal_debit)?></td>
                        <td class="text-right"><?=number_format($__coa->begbal_credit)?></td>
                        <td class="text-right"><?=number_format($__coa->mutation_debit)?></td>
                        <td class="text-right"><?=number_format($__coa->mutation_credit)?></td>
                        <td class="text-right"><?=number_format($__coa->endbal_debit)?></td>
                        <td class="text-right"><?=number_format($__coa->endbal_credit)?></td>
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
    </div>
</div>
<?php endif;?>

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
