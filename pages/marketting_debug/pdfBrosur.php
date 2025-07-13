<?php
// Load db connection
include_once '../../include/conn.php';
// Assets class (image, css, etc..)
class Assets{
    // Logo path
    public static $logo = '../../include/img-01.png';
    public static $sketch_patch = 'upload_files/brosur/';
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
     * Get brosur data and transform into report friendly format
     * @param $season_id int Season ID
     * @param $product_id int Product ID
     * @return array
     */
    public function get_report($season_id, $product_id)
    {
        $sql = "
            SELECT *
                  ,'JULY 2016' as selling_week
             FROM 
                brosur b
                LEFT JOIN masterseason ms ON b.id_season = ms.id_season
                LEFT JOIN masterproduct mp ON b.id_product = mp.id
            WHERE 
                b.id_season = $season_id
                AND b.id_product = $product_id
        ";

        $raw_result = $this
            ->query($sql)
            ->result();

        if(!count( $raw_result)){
            return array(NULL, NULL, NULL);
        }

        $h = new stdClass();
        $h->season = $raw_result[0]->season_desc;
        $h->style = $raw_result[0]->styledesc;
        $h->selling_week = $raw_result[0]->selling_week;

        $col = array();
        foreach($raw_result as $r){
            $col[$r->color] = $r->color_name;
        }

//        echo '<pre>';print_r($col);exit();

        return array(
            $h,
            $col,
            $raw_result
        );;
    }
}

// Retrieve parameters
if(!isset($_GET['season_id'])){
    exit('Missing Season Id parameter');
}
if(!isset($_GET['product_id'])){
    exit('Missing Product Id parameter');
}

$season_id = $_GET['season_id'];
$product_id = $_GET['product_id'];

// Instantiate model object
$m = new Model($con_new);

// Get report data, assign into job order (header) and sales order(detail) vars
list($h, $colors, $items) = $m->get_report($season_id, $product_id);

//echo '<pre>';print_r($items);exit();

$color_row = count($colors) > 7 ? 2 : 1;
$color_col = $color_row == 2 ? ceil(count($colors)/2) : count($colors);

// Get master company
$company = $m->get_master_company();

// If header is null, then it is non existent

if(is_null($h)){
    exit('Brochure Not Found');
}

// Close db connection
$con_new->close();

// Begin Capturing Output
ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <style>
            html {
                -webkit-text-size-adjust: 100%;
                -ms-text-size-adjust: 100%;
            }
            html, body{
                font-family: sans-serif;
                width: 100%;
                height: 100%;
                margin:0;
                padding:0;
            }
            table {
                border-spacing: 0;
                border-collapse: collapse;
                margin:0;
                padding:0;
            }
            td,
            th {
                padding: 2px;
                margin:0;
            }
            .table {
                border-collapse: collapse !important;
                width: 100%;
                max-width: 100%;
                font-size: 8px;
            }
            .table td{
                background-color: #fff;
                font-weight: bold;
                text-align: center;
                vertical-align: top;
            }
            .table th {
                background-color: #fff;
                font-weight: normal;
            }
            .table-bordered th,
            .table-bordered td {
                border: 1px solid #959E9C !important;
            }
            .table td.frame{
                background-color: #959E9C !important;
            }
            .text-right {
                text-align: right !important;
            }
            .text-center {
                text-align: center !important;
            }
            .text-left{
                text-align: left !important;
            }
            table td.text-left{
                text-align: left !important;
            }
            .borderless td{
                border:none !important;
            }
        </style>
        <title>Brochure</title>
    </head>
    <body>


    <table class="table table-bordered">
        <tr>
            <td class="frame">&nbsp;</td>
            <td class="frame text-left" colspan="8">PRE-PRODUCTION LAYOUT</td>
            <td class="frame" rowspan="6">&nbsp;</td>
        </tr>
        <tr>
            <td class="frame">&nbsp;</td>
            <td colspan="5" style="padding: 0">
                <table class="borderless" style="width: 500px">
                    <tr>
                        <td style="font-size: 14px;">
                            Season <?=$h->season?><br>
                            <h4 style="font-size: 18px;"><?=$h->style?></h4>
                        </td>
                        <td>
                            KEY COLOR
                            <table>
                                <?php for($i=1;$i<=$color_row;$i++):?>
                                <tr>
                                    <?php for($j=1;$j<=$color_col;$j++):?>
                                        <?php
                                            $color_code = key($colors);
                                            $color = current($colors);
                                            next($colors);
                                        ?>
                                        <?php if($color):?>
                                        <td style="width:20px;height:20px;font-size:5px;color:#ffffff;text-align:left;vertical-align:bottom;background-color:<?=$color_code?>">
                                            <?=$color;?>
                                        </td>
                                        <?php else:?>
                                        <td>

                                        </td>
                                        <?php endif;?>
                                    <?php endfor;?>
                                </tr>
                                <?php endfor;?>
                            </table>
                        </td>
                        <td>
                            <div>SHOP CATEGORY</div>
                            <div>SELLING WEEK</div>
                            <div><?=$h->selling_week?></div>
                        </td>
                    </tr>
                </table>

            </td>
            <td>CAMPAIGN DISPLAY</td>
            <td rowspan="4">
                <img height="100px" style="max-width: 100px" src="<?=Assets::$sketch_patch.'tr.jpg'?>" >
            </td>
        </tr>
        <tr>
            <td class="frame" text-rotate="90">HIGH FASHION</td>
            <?php for($n=0; $n<=4;$n++):?>
            <td>
                <?php $item = isset($items[$n]) ? $items[$n] : false;?>
                <?php if($item):?>
                    <img height="100px" style="max-width: 100px" src="<?=Assets::$sketch_patch.$item->nm_file?>" ><br>
                    <?=nl2br($item->itemname)?>
                <?php endif;?>
            </td>
            <?php endfor;?>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td class="frame" text-rotate="90">CURRENT FASHION</td>
            <?php for($n=5; $n<=9;$n++):?>
                <td>
                    <?php $item = isset($items[$n]) ? $items[$n] : false;?>
                    <?php if($item):?>
                        <img height="100px" style="max-width: 100px" src="<?=Assets::$sketch_patch.$item->nm_file?>" ><br>
                        <?=nl2br($item->itemname)?>
                    <?php endif;?>
                </td>
            <?php endfor;?>
            <td rowspan="2">PRESS COLLECTION LOOKBOOK</td>
        </tr>
        <tr>
            <td class="frame" text-rotate="90">MODERN BASIC</td>
            <?php for($n=10; $n<=14;$n++):?>
                <td>
                    <?php $item = isset($items[$n]) ? $items[$n] : false;?>
                    <?php if($item):?>
                        <img height="100px" style="max-width: 100px" src="<?=Assets::$sketch_patch.$item->nm_file?>" ><br>
                        <?=nl2br($item->itemname)?>
                    <?php endif;?>
                </td>
            <?php endfor;?>
        </tr>
        <tr>
            <td class="frame">&nbsp;</td>
            <td class="frame">BLOUSE/SHIRT/TUNIC</td>
            <td class="frame">DRESSES</td>
            <td class="frame">JACKETS</td>
            <td class="frame">BOTTOMS</td>
            <td class="frame">FANCY JERSEY/KNITWEAR</td>
            <td class="frame">&nbsp;</td>
            <td class="frame">&nbsp;</td>
        </tr>
    </table>

    </body>
    </html>
<?php
// Store output into vars
$html = ob_get_clean();
//exit($html);
// Convert output into pdf
include("../../mpdf57/mpdf.php");
$mpdf=new mPDF();
$mpdf->WriteHTML($html);
$mpdf->Output();
?>