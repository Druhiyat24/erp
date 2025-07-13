<?php

// Load db connection
include_once '../../include/conn.php';
// Assets class (image, css, etc..)


// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("F_L_Bagan_Akun","userpassword","username='$user'");
echo "AKSES:".$akses;
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }


class Config{
    // Loss percentage in fraction
    public static $loss = 0.03; //3%
}
class Assets{
    // Logo path
    public static $logo = '../../include/img-01.png';
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

    public function get_coa_digit_config(){
        $rows = $this->query("
            SELECT num_of_digit FROM mastercoaconfig ORDER BY `position`;
        ")->result();

        $result = array();
        foreach($rows as $r){
            $result[] = $r->num_of_digit;
        }
        return $result;
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
    public function get_report()
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
            ORDER BY 
                mc.id_coa
                ,mc2.id_coa
        ";

        $query = $this
            ->query($sql)
            ->result();

        $tb = array();
        if(count($query)){
            foreach($query as $r){
                if($r->fg_posting == '0'){
                    $tb[$r->id_group_coa]['h'] = $r;
                }else{
                    $tb[$r->id_group_coa]['d'][$r->id_coa] = $r;
                }

            }
        }
        return $tb;
    }
}

// Instantiate model object
$m = new Model($con_new);
$ch = new Coa_helper();

$tb = $m->get_report();

// Get master company
$company = $m->get_master_company();

$acct_period = $m->get_accounting_period();
$curr_period = date('m/Y', time());

// Close db connection
$con_new->close();
?>

<?php if(count($tb)):?>
<a href="coa_reportexprt.php" class="btn btn-info" title="Export Excel" ><i class='fa fa-file-excel-o'> Export Excel</i></a>



    <div class='box'>
        <div class='box-body'>
            <table class="table table-condensed table-bordered">
                <thead>
                <tr>
                    <th rowspan="2">&nbsp;</th>
                    <th rowspan="2">COA ID</th>
                    <th rowspan="2">ACCOUNT</th>
                    <th colspan="2">CATEGORY</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($tb as $_id_group_coa => $_coa): if($_id_group_coa=='total'){continue;}?>
                    <tr class="">
                        <td><a class="btn btn-xs btnToggle_<?=str_replace('.','_',$_id_group_coa)?>" onclick="toggle_detail('<?=str_replace('.','_',$_id_group_coa)?>')" ><i class="fa fa-plus"></i></a></td>
                        <td><?=$ch->format_coa($_coa['h']->id_coa)?></td>
                        <td><?=$_coa['h']->nm_coa?></td>
                        <td><?=$_coa['h']->nm_map?></td>
                    </tr>
                    <?php if(isset($_coa['d'])):?>
                        <?php foreach($_coa['d'] as $__coa):?>
                            <tr class="item_<?=str_replace('.','_',$_id_group_coa)?> success hidden">
                            
								<?php 
									$explodeid = explode('.',$ch->format_coa($__coa->id_coa));
									if($explodeid[0] == '2' && $explodeid[1] == '10' ){

											//print_r($explodeid[1]);
										$right = intval($explodeid[2]);
										if($right >=1 && $right <=49){
											$style = "display:none";
										
										
										}
										else{ ?>
										    <td>&nbsp;</td>
										                                <td  ><?=$ch->format_coa($__coa->id_coa)?></td>
                                <td><?=$__coa->nm_coa?></td>
                                <td><?=$__coa->nm_map2?></td>
									
                            </tr>	
										
									<?php	}
									}
								
								?>



                        <?php endforeach;?>
                    <?php endif;?>
                <?php endforeach;?>
                </tbody>
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
