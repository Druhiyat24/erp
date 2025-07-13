<?php

include_once "../../include/conn.php";
include_once "../forms/fungsi.php";



	 function d_from($from){
		$d_from = explode("/",$from."/01");
		$d_from = $d_from[1]."-".$d_from[0]."-".$d_from[2];
		return $d_from;
	}
	 function d_to($to){
		$d_to = explode("/",$to."/01");
		$d_to = $d_to[1]."-".$d_to[0]."-".$d_to[2];
		$d_to = date("Y-m-d", strtotime("1 month",strtotime($d_to)));
		$d_to = date("Y-m-d", strtotime("-1 days",strtotime($d_to)));
		return $d_to;
	}
$d_from = "1970-01-01";
$d_to = "1970-01-01";
if( (ISSET($_GET['period_from'])) &&  (ISSET($_GET['period_from']))){
	$d_from = date('Y-m-d',strtotime($_GET['period_from']));
	$d_to = date('Y-m-d',strtotime($_GET['period_to']));
	
}
echo $d_from;


?>



<div class="container-fluid">
  <section class="content">
    <div class="box">
      <div class="box-body">
        <form method='get' name='form' enctype='multipart/form-data' action="">

            <div class="panel panel-default">

                <div class="panel-heading">Periode Journal</div>

                <div class="panel-body row">

                    <div class="col-md-3">

                        <div class='form-group'>

                            <label for="period_from">Dari</label>



                            <input type='text' id="datepicker1" class='form-control datepicker' name='period_from'

                            placeholder='DD/MM/YYYY' value='<?=isset($period_from)?$period_from:''?>' autocomplete="off" >

                        

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class='form-group'>

                            <label>Sampai</label>



                            <input type='text' id="datepicker2" class='form-control datepicker' name='period_to'

                                   placeholder='DD/MM/YYYY' value='<?=isset($period_to)?$period_to:''?>' autocomplete="off" >

                     

                        </div>

                    </div>

                    <!-- <div class="clearfix"></div> -->

                    <div class="col-md-3">

                        <div class="form-group"> 

                            <input type="hidden" name="mod" value="lapemim" />
                            <label>&nbsp;</label><br>
                            <button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>

                        </div>

                    </div>

                </div>

            </div>

        </form>	  
	  
	  </div>
	  </div>
    <div class="box">
      <div class="box-body">

        <div style="overflow: scroll; width: 100%; height: 500px; font-size: 9pt">
          <table id="example1" class="table table-condensed table-bordered display" border="1">
            <thead>
              <tr>
                <th style="text-align: center;" rowspan="2">NO</th>
                <th style="text-align: center;" rowspan="2">KODE BARANG</th>
                <th style="text-align: center;" rowspan="2">NAMA BARANG</th>
                <th style="text-align: center;" rowspan="2">JENIS BARANG</th>
                <th style="text-align: center;" rowspan="2"># WS</th>
                <th style="text-align: center;" rowspan="2">STYLE</th>
                <th style="text-align: center;" rowspan="2">ID SUPPLIER</th>
                <th style="text-align: center;" rowspan="2">NAMA SUPPLIER</th>
                <th style="text-align: center;" rowspan="2">ID KONSUMEN</th>
                <th style="text-align: center;" rowspan="2">NAMA KONSUMEN</th>
                <th style="text-align: center;" rowspan="2">ID ORDER</th>
                <th style="text-align: center;" colspan="2">BPB</th>
                <th style="text-align: center;" colspan="5">PO</th>
                <th style="text-align: center;" rowspan="2"># SO</th>
                <th style="text-align: center;" colspan="2">BL/AWB</th>
                <th style="text-align: center;" colspan="2">INVOICE</th>
                <th style="text-align: center;" colspan="2">PIB/DOC BC23</th>
                <th style="text-align: center;" colspan="2">DOKUMEN LC</th>
                <th style="text-align: center;" rowspan="2">SATUAN QTY</th>
                <th style="text-align: center;" rowspan="2">QTY</th>
                <th style="text-align: center;" rowspan="2">UNIT PRICE</th>
                <th style="text-align: center;" colspan="3">DPP</th>
                <th style="text-align: center;" colspan="3">TAXES</th>
                <th style="text-align: center;" rowspan="2">TOTAL</th>
              </tr>
              <tr>
                <th style="text-align: center;">NOMOR</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">NOMOR</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">TOTAL QTY</th>
                <th style="text-align: center;">REALISASI QTY</th>
                <th style="text-align: center;">OUTSTANDING QTY</th>
                <th style="text-align: center;">NOMOR</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">NOMOR</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">NOMOR</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">NOMOR</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">US$</th>
                <th style="text-align: center;">KURS</th>
                <th style="text-align: center;">IDR</th>
                <th style="text-align: center;">BEA MASUK</th>
                <th style="text-align: center;">PPh22</th>
                <th style="text-align: center;">PPN</th>
              </tr>
            </thead>


            <tbody>
              <?php

              $query = mysql_query("SELECT mi.goods_code
                , mi.itemdesc
                , mi.matclass
				,MR.rate kurs
                , ac.kpno 
				,b.invno
                , mst.Styleno
                , s.so_no
                , b.bpbno_int
				,b.idbpb
                , b.bpbdate
                , b.pono
				,b.confirm
                , b.bcno
                , b.bcdate
                , b.no_fp
                , b.tgl_fp
                , ms.Supplier
                , ms.Attn
                , ms.supplier_code
                , ms.short_name
                , j.jo_no
                , j.jo_date
                , j.username
                , poi.qty AS qty_po_item
                , b.qty AS qty_bpb
                , ROUND(poi.qty-b.qty,2)qty_outstanding
                , b.qty
                , b.unit
                , b.price
                , b.curr
                , (b.price*b.qty)dpp_usd
				, ((MR.rate) *  b.price*b.qty)dpp_idr
                , poh.ppn
                , poh.podate
                , ((b.price*b.qty)+ ((poh.ppn/100)*(b.price*b.qty)) )after_ppn_usd
				, ( ( (b.price*b.qty)+ ((poh.ppn/100)*(b.price*b.qty)) ) * MR.rate )after_ppn_idr
                , byr1.Supplier AS buyer
                , byr1.supplier_code AS byr_code
                , db.bm AS bea_masuk
                , db.pph AS pph22

                FROM fin_journal_h fjh INNER JOIN (SELECT bpbno_int, 
                bpbdate, 
                id_supplier, 
                id_item,
                id_jo,
				confirm,
                id_po_item,
                qty,
				id idbpb,
                price,
                unit,
                curr,
                invno, 
                pono, 
                bcno, 
                bcdate,
                no_fp,
                tgl_fp,
                jenis_dok

                FROM bpb WHERE jenis_dok='BC 2.3' AND id_jo!='')b ON b.bpbno_int=fjh.reff_doc

                INNER JOIN mastersupplier ms ON ms.Id_Supplier=b.id_supplier

                INNER JOIN masteritem mi ON mi.id_item=b.id_item

                INNER JOIN jo j ON j.id=b.id_jo

                INNER JOIN jo_det jod ON jod.id_jo=j.id

                INNER JOIN so s ON s.id=jod.id_so

                INNER JOIN so_det sod ON sod.id_so=s.id

                INNER JOIN act_costing ac ON ac.id=s.id_cost
                
                LEFT JOIN detail_bm db ON db.jenis_dok=b.jenis_dok

                INNER JOIN (SELECT Supplier,
                Id_Supplier,
                tipe_sup,
                supplier_code
                FROM mastersupplier WHERE area='I' AND tipe_sup='C')byr1 ON byr1.Id_Supplier=ac.id_buyer 
                
                INNER JOIN po_item poi ON poi.id=b.id_po_item

                INNER JOIN po_header poh ON poh.id=poi.id_po

                INNER JOIN masterstyle mst ON mst.id_so_det=sod.id

				INNER JOIN (SELECT tanggal,rate,v_codecurr FROM masterrate WHERE v_codecurr='HARIAN')MR
				ON MR.tanggal = b.bpbdate
				
                WHERE fjh.fg_post='2' AND fjh.type_journal='2' AND ms.area='I'  AND db.bcno=b.bcno
 AND (fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to') and b.confirm = 'Y' GROUP BY b.idbpb
                ORDER BY b.bpbdate DESC");

              $no = 1;

              while($data = mysql_fetch_array($query))

                { echo "<tr>";

              echo "<td>$no</td>";
              echo "<td>$data[goods_code]</td>"; // kode barang
              echo "<td>$data[itemdesc]</td>"; // nama barang
              echo "<td>$data[matclass]</td>"; // jenis barang
              echo "<td>$data[kpno]</td>"; // nomor WS
              echo "<td>$data[Styleno]</td>"; // Nama Style
              echo "<td>$data[supplier_code]</td>"; // ID Supplier
              echo "<td>$data[Supplier]</td>"; // Nama Supplier
              echo "<td>$data[byr_code]</td>"; // ID Konsumen/Buyer
              echo "<td>$data[buyer]</td>"; // Nama Konsumen/Buyer
              echo "<td>-</td>"; // ID Order
              echo "<td>$data[bpbno_int]</td>"; // No BPB
              echo "<td>".fd_view($data['bpbdate'])."</td>"; // Tanggal BPB
              echo "<td>$data[pono]</td>"; // Nomor PO
              echo "<td>".fd_view($data['podate'])."</td>"; // Tanggal PO
              echo "<td style='text-align: right;'>$data[qty_po_item]</td>"; // Total Qty PO
              echo "<td style='text-align: right;'>$data[qty_bpb]</td>"; // Qty BPB / Realisasi PO
              echo "<td style='text-align: right;'>$data[qty_outstanding]</td>"; // Qty Outstanding / Qty BPB Pending
              echo "<td>$data[so_no]</td>"; // Nomor SO
              echo "<td>-</td>"; // nomor BL/AWB
              echo "<td>-</td>"; // TAnggal BL / AWB
              echo "<td>$data[invno]</td>"; // Nomor INvoice
              echo "<td>".fd_view($data['bpbdate'])."</td>"; // Tanggal Invoice
              echo "<td>$data[bcno]</td>"; // Nomor BC23
              echo "<td>".fd_view($data['bcdate'])."</td>"; // Tanggal BC23
              echo "<td>-</td>"; // nomor Dokumen LC
              echo "<td>-</td>"; // Tgl Dokumen LC
              echo "<td>$data[unit]</td>"; //satuan qty
              echo "<td>$data[qty]</td>"; //qty
              echo "<td style='text-align: right;'>".(number_format((float)$data['price'], 2, '.', ','))."</td>"; //unit price
               echo "<td style='text-align: right;'>".(number_format((float)$data['dpp_usd'], 2, '.', ','))."</td>"; //DPP IDR
              echo "<td style='text-align: right;'>".(number_format((float)$data['kurs'], 2, '.', ','))."</td>"; //DPP IDR
              echo "<td style='text-align: right;'>".(number_format((float)$data['dpp_idr'], 2, '.', ','))."</td>"; //DPP IDR
              echo "<td style='text-align: right;'>".(number_format((float)$data['bea_masuk'], 2, '.', ','))."</td>"; //BEA MASUK
              echo "<td style='text-align: right;'>".(number_format((float)$data['pph22'], 2, '.', ','))."</td>"; //PPh22
              echo "<td style='text-align: right;'>".(number_format((float)$data['ppn'], 2, '.', ','))."</td>"; //PPN
              echo "<td style='text-align: right;'>".(number_format((float)$data['after_ppn_usd'], 2, '.', ','))."</td>"; //PPN//TOTAL



              echo "</tr>";

              $no++;

            }

            ?>
          </tbody>

        </table>
      </div>

    </div>
  </div>
</section>
</div>




