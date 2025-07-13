<?php
// Load db connection
include_once '../../include/conn.php';
// Assets class (image, css, etc..)
class Assets{
    // Logo path
    public static $logo = '../../include/img-01.png';
    public static $sketch_patch = 'upload_files/costing/';
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
            die($err);
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
    public function get_report($jo_id)
    {
        $sql = "
            SELECT 
                jo.id jo_id
                ,jo.jo_no
                ,jo.jo_date
                ,so.id so_id
                ,so.so_no
                ,so.so_date
                ,sod.qty so_qty
                ,so.unit so_unit
                ,so.fob so_fob
                ,sod.dest
                ,sod.color
                ,sod.size
                ,sod.qty sod_qty
                ,sod.unit sod_unit
                ,ac.id act_cost_id
                ,ac.cost_no
                ,ac.cost_date
                ,ac.kpno
                ,ac.id_smode shipmode_id
                ,ac.brand
                ,ac.main_dest
                ,msp.shipmode
                ,sod.sku
                ,sod.deldate_det delivery_date
                ,ac.curr
                ,mp.id product_id
                ,mp.product_group
                ,mp.product_item
                ,ac.styleno
                ,ms.id_supplier
                ,ms.supplier_code
                ,ms.supplier
                -- TODO: change below fields with actual data
                ,mseas.season as season
                ,jo.username as md
				,ifnull(jo.attach_file,0)attach_file
                ,so.buyerno as po_no
                ,sod.notes as remark
                ,sod.notes as remark
                ,concat(sod.id,sod.dest,sod.sku,sod.color,sod.size) as parsum
                ,concat(so.buyerno,sod.dest,sod.sku) as skugrp
                ,'(update)' as `update`
            FROM 
                jo
                INNER JOIN jo_det jd ON jo.id = jd.id_jo
                INNER JOIN so  ON so.id=jd.id_so
                LEFT JOIN masterseason mseas ON so.id_season=mseas.id_season
                INNER JOIN so_det sod ON so.id = sod.id_so
                INNER JOIN act_costing ac ON so.id_cost=ac.id 
                INNER JOIN masterproduct mp ON ac.id_product=mp.id 
                INNER JOIN mastersupplier ms ON ac.id_buyer=ms.id_supplier
                LEFT JOIN mastershipmode msp ON ac.id_smode=msp.id
                LEFT JOIN mastersize msize ON sod.size=msize.size 
            WHERE jo.id = $jo_id and so.cancel_h='N' and sod.cancel='N' 
            order by so.buyerno,sod.dest,sod.sku,sod.color,msize.urut";

        $raw_result = $this
            ->query($sql)
            ->result();

        if(!count((array) $raw_result)){
            return array(null, null, null);
        }

        $jo = array();
        $so = array();
        $so_total = array();
        $_prev_so = array();
        foreach($raw_result as $r){
            if(!count((array)$jo)){
                $jo = array(
                    'jo_id' => $r->jo_id,
                    'jo_no' => $r->jo_no,
                    'jo_date' => $r->jo_date,
                    'jo_unit' => $r->so_unit,
                    'styleno' => $r->styleno,
                    'season' => $r->season,
                    'kpno' => $r->kpno,
                    'brand' => $r->brand,
                    'main_dest' => $r->main_dest,
                    'md' => $r->md,
                    'product_item' => $r->product_item,
                    'product_group' => $r->product_group,
                    'update' => $r->update,
                    'buyer' => $r->supplier,
					'gambar' => $r->attach_file,
                );
            }

            if(!count($_prev_so)){
                $jo['jo_qty'] = $r->so_qty;
                $jo['jo_fob'] = $r->so_fob;
                $_prev_so[] = $r->parsum;
            }elseif(!in_array($r->parsum, $_prev_so)){
                $jo['jo_qty'] += $r->so_qty;
                $jo['jo_fob'] += $r->so_fob;
                $_prev_so[] = $r->parsum;
            }

            if(!isset($so[$r->skugrp])){
                $so[$r->skugrp] = array(
                    'dest' => $r->dest,
                    'so_id' => $r->so_id,
                    'so_no' => $r->so_no,
                    'so_date' => $r->so_date,
                    'so_qty' => $r->so_qty,
                    'so_unit' => $r->so_unit,
                    'so_fob' => $r->so_fob,
                    'po_no' => $r->po_no,
                    'delivery_date' => $r->delivery_date,
                    'shipmode' => $r->shipmode,
                    'sku' => $r->sku,
                    'remark' => $r->remark,
                    'colors' => array(),
                    'sizes' => array(),
                    'details' => array(),
                );

            }

            @$so[$r->skugrp]['details'][$r->color][$r->size] += $r->sod_qty;
            $so[$r->skugrp]['colors'][$r->color] = $r->color;
            $so[$r->skugrp]['sizes'][$r->size] = $r->size;

            @$so[$r->skugrp]['details']['SUM_COLOR'][$r->color] += $r->sod_qty;
            @$so[$r->skugrp]['details']['SUM_SIZE'][$r->size] += $r->sod_qty;
            @$so[$r->skugrp]['details']['SUM_ALL'] += $r->sod_qty;

            $so_total['sizes'][$r->size] = $r->size;
            $so_total['colors'][$r->color] = $r->color;
            @$so_total['assortments'][$r->color][$r->size] += $r->sod_qty;
            @$so_total['assortments']['SUM_COLOR'][$r->color] += $r->sod_qty;
            @$so_total['assortments']['SUM_SIZE'][$r->size] += $r->sod_qty;
            @$so_total['assortments']['SUM_ALL'] += $r->sod_qty;

            $so_total['dests'][$r->dest] = $r->dest;
            @$so_total['dest'][$r->dest][$r->size] += $r->sod_qty;
            @$so_total['dest']['SUM_DEST'][$r->dest] += $r->sod_qty;
            @$so_total['dest']['SUM_SIZE'][$r->size] += $r->sod_qty;
            @$so_total['dest']['SUM_ALL'] += $r->sod_qty;

        }



        return(array(
            json_decode(json_encode($jo)),
            json_decode(json_encode($so)),
            json_decode(json_encode($so_total)),
        ));
    }
}

// Retrieve parameters
if(!isset($_GET['id'])){
    exit('Missing Job Order Id parameter');
}
$id = $_GET['id'];

// Instantiate model object
$m = new Model($con_new);

//echo '<pre>';print_r($m->get_report($id));exit();

// Get report data, assign into job order (header) and sales order(detail) vars
list($jo, $so, $so_total) = $m->get_report($id);

// Get master company
$company = $m->get_master_company();
$attach_file = $jo->gambar;

$img = "<img src='upload_files/ws/".$attach_file ."' class='img-responsive' alt='-'>";
// If job order is null, then it is non existent
if(is_null($jo)){
    exit('Job Order Not Found');
}

$header = "<table class='table' style='border-bottom: 2px solid #000000; margin-bottom:5px;'>
        <tr>
            <td><img src='".Assets::$logo."' width='100px' height='70px'> </td>
            <td style='text-align: right;vertical-align: bottom;font-size: 16px;'>".strtoupper($company->company)."</td>
        </tr>
    </table>";

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
                font-size: 12px;
            }
            .table td{
                background-color: #fff;
                font-weight: bold;
            }
            .table th {
                background-color: #fff;
                font-weight: normal;
            }
            .table-bordered th,
            .table-bordered td {
                border: 1px solid #999 !important;
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
        </style>
        <title>Worksheet</title>
    </head>
    <body>
		<?php echo $header ?>
    <h3>WORKSHEET</h3>
    <table class="table table-condensed" >
        <tr>
            <th colspan="4">&nbsp;</th>
        </tr>
        <tr>
            <th class="text-left" style="width: 160px">Create Date : <?=$jo->jo_date?></th>
            <!-- <td class="text-left" style="width: 100px"><?=$jo->jo_date?></td> -->
            <th class="text-left" style="width: 200px">Buyer : <?=$jo->buyer?></th>
            <!-- <td class="text-left" style="width: 160px"><?=$jo->buyer?></td> -->
            <th colspan="4"></th>
        </tr>
    </table>
    <table class="table table-bordered table-condensed">
        <tr>
            <th style="width: 100px" class="text-right">Style #</th>
            <td class="text-left"><?=$jo->styleno?></td>
            <th style="width: 100px" class="text-right">Season</th>
            <td style="width: 100px" class="text-left"><?=$jo->season?></td>
            <th style="width: 100px" class="text-right">MD</th>
            <td style="width: 100px" class="text-left"><?=$jo->md?></td>
        </tr>
        <tr>
            <th style="width: 100px" class="text-right">WS #</th>
            <td class="text-left"><?=$jo->kpno?></td>
            <th style="width: 100px" class="text-right">Qty</th>
            <td class="text-left"><?=number_format($jo->jo_qty).' '.$jo->jo_unit?></td>
            <th style="width: 100px" class="text-right">Item Name</th>
            <td class="text-left"><?=$jo->product_item?></td>
        </tr>
        <tr>
            <th style="width: 100px" class="text-right">Product Group</th>
            <td class="text-left"><?=$jo->product_group?></td>  
            <th style="width: 100px" class="text-right">Brand</th>
            <td class="text-left"><?=$jo->brand?></td>  
            <th style="width: 100px" class="text-right">Main Dest</th>
            <td class="text-left"><?=$jo->main_dest?></td>                                 
        </tr>
    </table>


    <?php if(count((array) $so)):?>
        <?php $so_seq = 1;?>
        <?php foreach($so as $s):?>
                <h4><?=$so_seq++;?></h4>
                <table class="table table-condensed">
                    <tr>
                        <th class="text-left" style="width:100px;">Buyer PO:</th>
                        <td class="text-left"><?=$s->po_no?></td>
                        <th class="text-left" style="width:100px;">Dest:</th>
                        <td class="text-left"><?=$s->dest?></td>
                    </tr>
                    <!-- <tr>
                        <th class="text-left" style="width:100px;">Qty:</th>
                        <td class="text-left"><?=number_format($s->so_qty).' '.$s->so_unit?></td>
                        <th class="text-left" style="width:100px;">Ship Mode:</th>
                        <td class="text-left"><?=$s->shipmode?></td>
                    </tr> -->
                    <tr>
                        <th class="text-left" style="width:100px;">Del Date:</th>
                        <td class="text-left"><?=date('D, d-M-Y', strtotime($s->delivery_date))?></td>
                        <th class="text-left" style="width:100px;">SKU:</th>
                        <td class="text-left"><?=$s->sku?></td>
                    <tr>
                        <th class="text-left" style="width:100px;">Remark:</th>
                        <td class="text-left" colspan="3"><?=$s->remark?></td>
                    </tr>
                </table>

                <?php if(isset($s->details) and count((array) $s->details)):?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="background-color: #e6e6e6" class="text-center">COLOR</th>
                            <?php foreach($s->sizes as $size):?>
                            <th style="background-color: #e6e6e6"><?=$size?></th>
                            <?php endforeach;;?>
                            <th style="background-color: #e6e6e6"><b>TOTAL<b></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($s->colors as $color):?>
                        <tr>
                            <th style="background-color: #e6e6e6"><?=$color?></th>
                            <?php foreach($s->sizes as $size):?>
                            <td class="text-right"><?=isset($s->details->$color->$size) ? number_format($s->details->$color->$size) : 0?></td>
                            <?php endforeach;?>
                            <td class="text-right"><?=number_format($s->details->SUM_COLOR->$color)?></td>
                        </tr>
                        <?php endforeach;?>
                        <tr>
                            <th style="background-color: #e6e6e6"><b>TOTAL<b></th>                            <?php foreach($s->sizes as $size):?>
                            <td class="text-right"><?=isset($s->details->SUM_SIZE->$size) ? number_format($s->details->SUM_SIZE->$size) : 0?></td>
                            <?php endforeach;?>
                            <td class="text-right"><?=number_format($s->details->SUM_ALL)?></td>
                        </tr>
                    </tbody>
                </table>
                <?php endif;?>
                <br>
        <?php endforeach;?>

        <br><br><h5>Total Assortment Based Color</h5>
        <?php if(count((array) $so_total->colors)):?>
            <!-- Total Assortments By Color-->
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="background-color: #e6e6e6">COLOR</div></th><!-- <th>Total Assortment Based Color</th> -->
                    <?php foreach($so_total->sizes as $size):?>
                        <th style="background-color: #e6e6e6"><?=$size?></th>
                    <?php endforeach;;?>
                    <th style="background-color: #e6e6e6"><b>TOTAL<b></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($so_total->colors as $color):?>
                    <tr>
                        <th style="background-color: #e6e6e6"><?=$color?></th>
                        <?php foreach($so_total->sizes as $size):?>
                        <td class="text-right"><?=isset($so_total->assortments->$color->$size)? number_format($so_total->assortments->$color->$size):0?></td>
                        <?php endforeach;?>
                        <td class="text-right"><?=number_format($so_total->assortments->SUM_COLOR->$color)?></td>
                    </tr>
                <?php endforeach;?>
                <tr>
                    <th style="background-color: #e6e6e6"><b>TOTAL<b></th>
                    <?php foreach($so_total->sizes as $size):?>
                    <td class="text-right"><?=isset($so_total->assortments->SUM_SIZE->$size) ? number_format($so_total->assortments->SUM_SIZE->$size) : 0?></td>
                    <?php endforeach;?>
                    <td class="text-right"><?=number_format($so_total->assortments->SUM_ALL)?></td>
                </tr>
                </tbody>
            </table>
        <?php endif;?>

        <br><br><h5>Total Assortment Based Destination</h5>

        <?php if(count((array) $so_total->dests)):?>
            <!-- Total Assortments By Destination-->
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="background-color: #e6e6e6">COLOR</th><!-- <th>Total Assortment Based Destination</th> -->
                    <?php foreach($so_total->sizes as $size):?>
                        <th style="background-color: #e6e6e6"><?=$size?></th>
                    <?php endforeach;;?>
                    <th style="background-color: #e6e6e6"><b>TOTAL<b></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($so_total->dests as $dest):?>
                    <tr>
                        <th style="background-color: #e6e6e6"><?=$dest?></th>
                        <?php foreach($so_total->sizes as $size):?>
                        <td style="text-align: right;"><?=isset($so_total->dest->$dest->$size) ? number_format($so_total->dest->$dest->$size) : 0?></td>
                        <?php endforeach;?>
                        <td style="text-align: right;"><?=$so_total->dest->SUM_DEST->$dest?></td>
                    </tr>
                <?php endforeach;?>
                <tr>
                    <th style="background-color: #e6e6e6"><b>TOTAL<b></th>
                    <?php foreach($so_total->sizes as $size):?>
                        <td style="text-align: right;"><?=isset($so_total->dest->SUM_SIZE->$size) ? number_format($so_total->dest->SUM_SIZE->$size) : 0?></td>
                    <?php endforeach;?>
                    <td style="text-align: right;"><?=$so_total->dest->SUM_ALL?></td>
                </tr>
                </tbody>
            </table>
        <?php endif;?>

    <?php endif;?>

    </body>
    </html>
<?php
$html = ob_get_clean();
include("../../mpdf57/mpdf.php");
$mpdf=new mPDF();
$mpdf->WriteHTML($html);
if($attach_file != '0'){
$mpdf->AddPage();
$gambar_s = $header." ".$img;
$mpdf->WriteHTML($gambar_s);	
}
$mpdf->Output();
?>