<?php

include_once "../../../include/conn.php";

?>



<div class="container-fluid">
  <section class="content">

    <div class="box">
      <div class="box-body">

        <div style="overflow: scroll; width: 100%; height: 700px; font-size: 9pt">
          <table id="example1" class="table table-condensed table-bordered display" border="1">
            <thead id="freeze_thead">
              <tr>
                <th rowspan="2" style="text-align: center;">NO</th>
                <th rowspan="2" style="text-align: center;">KODE BARANG</th>
                <th rowspan="2" style="text-align: center;">NAMA BARANG</th>
                <th rowspan="2" style="text-align: center;">JENIS BARANG</th>
                <th rowspan="2" style="text-align: center;"># WS</th>
                <th rowspan="2" style="text-align: center;">STYLE</th>
                <th rowspan="2" style="text-align: center;">ID SUPPLIER</th>
                <th rowspan="2" style="text-align: center;">NAMA SUPPLIER</th>
                <th rowspan="2" style="text-align: center;">ID KONSUMEN</th>
                <th rowspan="2" style="text-align: center;">NAMA KONSUMEN</th>
                <th colspan="2" style="text-align: center;">BC40</th>
                <th rowspan="2" style="text-align: center;">ID ORDER</th>
                <th colspan="2" style="text-align: center;">BPB</th>
                <th colspan="5" style="text-align: center;">PO</th>
                <th colspan="3" style="text-align: center;">PR</th>
                <th rowspan="2" style="text-align: center;"># SO</th>
                <th colspan="2" style="text-align: center;">SJ SUPPLIER</th>
                <th colspan="2" style="text-align: center;">INVOICE</th>
                <th colspan="2" style="text-align: center;">FAKTUR PAJAK</th>
                <th rowspan="2" style="text-align: center;">SATUAN QTY</th>
                <th rowspan="2" style="text-align: center;">QTY</th>
                <th rowspan="2" style="text-align: center;">UNIT PRICE</th>
                <th colspan="3" style="text-align: center;">DPP</th>
                <th colspan="3" style="text-align: center;">PPN</th>
                <th colspan="3" style="text-align: center;">TOTAL</th>
              </tr>
              <tr>
                <th style="text-align: center;">NOMOR PENDAFTARAN</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">NOMOR</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">NOMOR</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">TOTAL QTY</th>
                <th style="text-align: center;">REALISASI QTY</th>
                <th style="text-align: center;">OUTSTANDING QTY</th>
                <th style="text-align: center;">NOMOR</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">USER</th>
                <th style="text-align: center;">NOMOR</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">NOMOR</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">NOMOR</th>
                <th style="text-align: center;">TANGGAL</th>
                <th style="text-align: center;">US$</th>
                <th style="text-align: center;">KURS</th>
                <th style="text-align: center;">IDR</th>
                <th style="text-align: center;">US$</th>
                <th style="text-align: center;">KURS</th>
                <th style="text-align: center;">IDR</th>
                <th style="text-align: center;">US$</th>
                <th style="text-align: center;">KURS</th>
                <th style="text-align: center;">IDR</th>
              </tr>

            </thead>

            <tbody>
              <?php

              $query = mysql_query("SELECT mi.goods_code
                , mi.itemdesc
                , mi.matclass
                , ac.kpno 
                , mst.Styleno
                , s.so_no
                , b.bpbno_int
                , b.bpbdate
                , b.pono
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
                , (poi.qty-b.qty)qty_outstanding
                , b.qty
                , b.unit
                , b.price
                , b.curr
                , (b.price*b.qty)dpp
                , poh.ppn
                , poh.podate
                , ((b.price*b.qty)+(poh.ppn/100))after_ppn
                , byr1.Supplier AS buyer
                , byr1.supplier_code AS byr_code


                FROM fin_journal_h fjh INNER JOIN (SELECT bpbno_int, 
                bpbdate, 
                id_supplier, 
                id_item,
                id_jo,
                id_po_item,
                qty,
                price,
                unit,
                curr,
                invno, 
                pono, 
                bcno, 
                bcdate,
                no_fp,
                tgl_fp

                FROM bpb WHERE jenis_dok='BC 4.0' AND id_jo!='')b ON b.bpbno_int=fjh.reff_doc

                INNER JOIN mastersupplier ms ON ms.Id_Supplier=b.id_supplier

                INNER JOIN masteritem mi ON mi.id_item=b.id_item

                INNER JOIN jo j ON j.id=b.id_jo

                INNER JOIN jo_det jod ON jod.id_jo=j.id

                INNER JOIN so s ON s.id=jod.id_so

                INNER JOIN so_det sod ON sod.id_so=s.id

                INNER JOIN act_costing ac ON ac.id=s.id_cost


                INNER JOIN (SELECT Supplier,
                Id_Supplier,
                tipe_sup,
                supplier_code
                FROM mastersupplier WHERE area='L' AND tipe_sup='C')byr1 ON byr1.Id_Supplier=ac.id_buyer 
                
                INNER JOIN po_item poi ON poi.id=b.id_po_item

                INNER JOIN po_header poh ON poh.id=poi.id_po

                INNER JOIN masterstyle mst ON mst.id_so_det=sod.id

                WHERE fjh.fg_post='2' AND fjh.type_journal='2' AND ms.area='L'

                ORDER BY b.bpbdate DESC");

              $no = 1;

              while($data = mysql_fetch_array($query))

                { echo "<tr>";

              echo "<td>$no</td>";
              echo "<td>$data[goods_code]</td>";
              echo "<td>$data[itemdesc]</td>";
              echo "<td>$data[matclass]</td>";
              echo "<td>$data[kpno]</td>";
              echo "<td>$data[Styleno]</td>";
              echo "<td>$data[supplier_code]</td>";
              echo "<td>$data[Supplier]</td>";
              echo "<td>$data[byr_code]</td>";
              echo "<td>$data[buyer]</td>";
              echo "<td>$data[bcno]</td>";
              echo "<td>$data[bcdate]</td>";
              echo "<td>-</td>";
              echo "<td>$data[bpbno_int]</td>";
              echo "<td>$data[bpbdate]</td>";
              echo "<td>$data[pono]</td>";
              echo "<td>$data[podate]</td>";
              echo "<td style='text-align: right;'>$data[qty_po_item]</td>";
              echo "<td style='text-align: right;'>$data[qty_bpb]</td>";
              echo "<td style='text-align: right;'>$data[qty_outstanding]</td>";
              echo "<td>$data[jo_no]</td>";
              echo "<td>$data[jo_date]</td>";
              echo "<td>$data[username]</td>";
              echo "<td>$data[so_no]</td>";
              echo "<td>$data[invno]</td>";
              echo "<td>$data[bpbdate]</td>";
              echo "<td>$data[invno]</td>";
              echo "<td>$data[bpbdate]</td>";
              echo "<td>$data[no_fp]</td>";
              echo "<td>$data[tgl_fp]</td>";
              echo "<td>$data[unit]</td>"; //satuan qty
              echo "<td>$data[qty]</td>"; //qty
              echo "<td style='text-align: right;'>".(number_format("$data[price]"))."</td>"; //unit price
              echo "<td style='text-align: right;'>-</td>"; //DPP US$
              echo "<td style='text-align: right;'>-</td>"; //DPP KURS
              echo "<td style='text-align: right;'>".(number_format("$data[dpp]"))."</td>"; //DPP IDR
              echo "<td style='text-align: right;'>-</td>"; //PPN US$
              echo "<td style='text-align: right;'>-</td>"; //PPN KURS
              echo "<td style='text-align: right;'>".(number_format("$data[ppn]"))."</td>"; //PPN IDR
              echo "<td style='text-align: right;'>-</td>"; //TOTAL US$  (TOTAL=DPP-PPN)
              echo "<td style='text-align: right;'>-</td>"; //TOTAL KURS
              echo "<td style='text-align: right;'>".(number_format("$data[after_ppn]"))."</td>"; //TOTAL IDR



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




