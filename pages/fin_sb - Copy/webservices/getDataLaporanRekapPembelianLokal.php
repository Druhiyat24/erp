<?php 
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



include __DIR__ .'/../../../include/conn.php';
## Read value
$data = $_GET;
$d_from = d_from($data['from']);
$d_to   = d_to($data['to']);
/* 
print_r($_POST);
die();  */
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(

SELECT P.id
,		P.nama_group
,		if(P.vendor_cat='NG','NON-GROUP','GROUP')vendor_cat
,		P.dpp_idr
,		P.price_USD
,		P.ppn
,		P.date_journal
,		P.total_idr

FROM (SELECT vendor_cat FROM mastersupplier GROUP BY vendor_cat)S LEFT JOIN 

(SELECT ifnull(FINAL.id,'-')id
,		ifnull(FINAL.nama_group,'-')nama_group
,		ifnull(FINAL.vendor_cat,'NG')vendor_cat
,		ifnull(FINAL.dpp_idr,'0')dpp_idr
,		ifnull(FINAL.price_USD,'0')price_USD
,		ifnull(FINAL.ppn,'0')ppn
,		ifnull(FINAL.date_journal,'0')date_journal
,		IFNULL((FINAL.dpp_idr+FINAL.ppn),'0')total_idr

FROM (SELECT YY.id id 																						#START NON-GROUP 
,		YY.nama_group nama_group
,		YY.vendor_cat vendor_cat
,		YY.dpp_idr dpp_idr
,		YY.price_USD price_USD
,		YY.ppn ppn
,		YY.date_journal

FROM (SELECT ITEM.id
,		ITEM.nama_group
,		B.vendor_cat
,		SUM(B.total_idr)dpp_idr
,		SUM(B.total_usd)price_USD
,		SUM(B.ppn)ppn
,		B.date_journal
FROM (SELECT a.id, a.kode_group, a.nama_group, mi.id_item
FROM mastergroup a 
INNER JOIN mastersubgroup b ON b.id_group=a.id
INNER JOIN mastertype2 c ON c.id_sub_group=b.id
INNER JOIN mastercontents d ON d.id_type=c.id
INNER JOIN masterwidth e ON e.id_contents=d.id
INNER JOIN masterlength f ON f.id_width=e.id
INNER JOIN masterweight g ON g.id_length=f.id
INNER JOIN mastercolor h ON h.id_weight=g.id
INNER JOIN masterdesc i ON i.id_color=h.id
INNER JOIN masteritem mi ON mi.id_gen=i.id
WHERE mi.non_aktif='N' AND a.kode_group!='BJ'
ORDER BY a.id ASC )ITEM

LEFT JOIN (
SELECT b.id_item
,		b.pono
, 		b.bpbno
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		ms.vendor_cat
,		fjh.date_journal
FROM bpb b
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier 
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='2' AND fjh.fg_post='2' AND b.confirm = 'Y' AND ms.vendor_cat='NG'
AND LEFT(b.bpbno,1) in ('A','F','B') 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
ORDER BY  b.bpbno ASC 
)B ON B.id_item = ITEM.id_item
GROUP BY ITEM.id #BAHAN BAKU

UNION ALL 

SELECT ifnull(M.id,'13')id
,	T.nama_group
,	ifnull(M.vendor_cat,'NG')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE SUBSTRING(goods_code,1,3)='EMB' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	B.matclass nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='EMB','13','13')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='NG'
AND SUBSTRING(mi.goods_code,1,3)='EMB'
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to' 
)B
GROUP BY B.mattype, B.vendor_cat #EMBROIDERY
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'14')id
,	T.nama_group
,	ifnull(M.vendor_cat,'NG')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE SUBSTRING(goods_code,1,3)='LSC' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	B.matclass nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='LSC','14','14')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='NG'
AND SUBSTRING(mi.goods_code,1,3)='LSC' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
)B
GROUP BY B.mattype #LASER CUTTING
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'15')id
,	T.nama_group
,	ifnull(M.vendor_cat,'NG')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE matclass='CMT' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	B.matclass nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='CMT','15','15')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='NG'
AND SUBSTRING(mi.goods_code,1,3)='CMT' OR mi.matclass ='CMT'
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
)B
GROUP BY B.mattype #CMT
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'16')id
,	T.nama_group
,	ifnull(M.vendor_cat,'GR')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE SUBSTRING(goods_code,1,3)='WSH' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	B.matclass nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='WSH','16','16')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='NG'
AND SUBSTRING(mi.goods_code,1,3)='WSH'  
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
)B
GROUP BY B.mattype #WASHING
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'17')id
,	T.nama_group
,	ifnull(M.vendor_cat,'GR')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE SUBSTRING(goods_code,1,3)='QLT' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	B.matclass nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='QLT','17','17')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='NG'
AND SUBSTRING(mi.goods_code,1,3)='QLT' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
)B
GROUP BY B.mattype #QUILTING
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'18')id
,	T.nama_group
,	ifnull(M.vendor_cat,'GR')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE SUBSTRING(goods_code,1,3)='HTS' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	B.matclass nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='HTS','18','18')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='NG'
AND SUBSTRING(mi.goods_code,1,3)='HTS' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
)B
GROUP BY B.mattype #HTS
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'19')id
,	T.nama_group
,	ifnull(M.vendor_cat,'NG')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE SUBSTRING(goods_code,1,3)='BND' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	B.matclass nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='BND','19','19')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='NG'
AND SUBSTRING(mi.goods_code,1,3)='BND' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
)B
GROUP BY B.mattype #BND
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'20')id
,	T.nama_group
,	ifnull(M.vendor_cat,'NG')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE SUBSTRING(goods_code,1,3)='PRT' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	B.matclass nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='PRT','20','20')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='NG'
AND SUBSTRING(mi.goods_code,1,3)='PRT' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
)B
GROUP BY B.mattype #PRT
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'21')id
,	T.nama_group
,	ifnull(M.vendor_cat,'NG')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT 'GENERAL' nama_group FROM masteritem WHERE mattype='N' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	if(B.matclass='-','GENERAL','GENERAL') nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(mi.mattype='N','21','21')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='2' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='NG'
AND mi.mattype='N'
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #GENERAL
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'22')id
,	T.nama_group
,	ifnull(M.vendor_cat,'NG')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT if(matclass='-','MESIN','MESIN') nama_group FROM masteritem WHERE mattype='M' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	if(B.matclass='-','MESIN','MESIN') nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(mi.mattype='M','22','22')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='2' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='NG'
AND mi.mattype='M'
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #MESIN
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'23')id
,	T.nama_group
,	ifnull(M.vendor_cat,'NG')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE mattype='S' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	if(B.matclass='MAJUN','MAJUN','MAJUN') nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(mi.mattype='S','23','23')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='2' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='NG'
AND mi.mattype='S' OR SUBSTRING(mi.goods_code,1,3)='MJN' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #SCRAP / MAJUN
)MM 

)M ON T.nama_group=M.nama_group 


) YY GROUP BY YY.id , YY.vendor_cat																	# END NON-GROUP 

UNION ALL

SELECT Y.id id 																				#START GROUP 
,		Y.nama_group nama_group
,		Y.vendor_cat vendor_cat
,		Y.dpp_idr dpp_idr
,		Y.price_USD price_USD
,		Y.ppn ppn
,		Y.date_journal
FROM (

SELECT ITEM.id
,		ITEM.nama_group
,		ifnull(B.vendor_cat,'GR')vendor_cat
,		SUM(B.total_idr)dpp_idr
,		SUM(B.total_usd)price_USD
,		SUM(B.ppn)ppn
,		B.date_journal
FROM (SELECT a.id, a.kode_group, a.nama_group, mi.id_item
FROM mastergroup a 
INNER JOIN mastersubgroup b ON b.id_group=a.id
INNER JOIN mastertype2 c ON c.id_sub_group=b.id
INNER JOIN mastercontents d ON d.id_type=c.id
INNER JOIN masterwidth e ON e.id_contents=d.id
INNER JOIN masterlength f ON f.id_width=e.id
INNER JOIN masterweight g ON g.id_length=f.id
INNER JOIN mastercolor h ON h.id_weight=g.id
INNER JOIN masterdesc i ON i.id_color=h.id
INNER JOIN masteritem mi ON mi.id_gen=i.id
WHERE mi.non_aktif='N'  AND a.kode_group!='BJ'
ORDER BY a.id ASC )ITEM 

LEFT JOIN (
SELECT b.id_item
,		b.pono
, 		b.bpbno
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		ms.vendor_cat
,		fjh.date_journal
FROM bpb b
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier 
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='2' AND fjh.fg_post='2' AND b.confirm = 'Y' AND ms.vendor_cat='GR'
AND LEFT(b.bpbno,1) in ('A','F','B') 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
ORDER BY  b.bpbno ASC , b.qty ASC 
)B ON B.id_item = ITEM.id_item
GROUP BY ITEM.id #BAHAN BAKU


UNION ALL 

SELECT ifnull(M.id,'13')id
,	T.nama_group
,	ifnull(M.vendor_cat,'GR')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE SUBSTRING(goods_code,1,3)='EMB' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT  ifnull(B.mattype,'13')id
,	ifnull(B.matclass,'EMBRODEIRY')nama_group
,	ifnull(B.vendor_cat,'GR')vendor_cat
,	ifnull(SUM(B.total_idr),'0')dpp_idr
,	ifnull(SUM(B.total_usd),'0')price_USD
,	ifnull(SUM(B.ppn),'0')ppn
,	ifnull(B.date_journal,'N/A')date_journal


FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='EMB','13','13')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='GR'
AND SUBSTRING(mi.goods_code,1,3)='EMB' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
ORDER BY  b.qty ASC
)B 
GROUP BY B.mattype, B.vendor_cat #EMBROIDERY
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'14')id
,	T.nama_group
,	ifnull(M.vendor_cat,'GR')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE SUBSTRING(goods_code,1,3)='LSC' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	B.matclass nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='LSC','14','14')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='GR'
AND SUBSTRING(mi.goods_code,1,3)='LSC' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #LASER CUTTING
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'15')id
,	T.nama_group
,	ifnull(M.vendor_cat,'GR')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE matclass='CMT' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	B.matclass nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='CMT','15','15')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='GR'
AND mi.matclass ='CMT'
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'

)B
GROUP BY B.mattype #CMT
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'16')id
,	T.nama_group
,	ifnull(M.vendor_cat,'GR')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE SUBSTRING(goods_code,1,3)='WSH' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	B.matclass nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='WSH','16','16')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='GR'
AND SUBSTRING(mi.goods_code,1,3)='WSH' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #WASHING
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'17')id
,	T.nama_group
,	ifnull(M.vendor_cat,'GR')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE SUBSTRING(goods_code,1,3)='QLT' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	B.matclass nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='QLT','17','17')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='GR'
AND SUBSTRING(mi.goods_code,1,3)='QLT' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #QUILTING
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'18')id
,	T.nama_group
,	ifnull(M.vendor_cat,'GR')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE SUBSTRING(goods_code,1,3)='HTS' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	B.matclass nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='HTS','18','18')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='GR'
AND SUBSTRING(mi.goods_code,1,3)='HTS' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #HTS
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'19')id
,	T.nama_group
,	ifnull(M.vendor_cat,'GR')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE SUBSTRING(goods_code,1,3)='BND' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	B.matclass nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='BND','19','19')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='GR'
AND SUBSTRING(mi.goods_code,1,3)='BND' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #BND
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'20')id
,	T.nama_group
,	ifnull(M.vendor_cat,'GR')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE SUBSTRING(goods_code,1,3)='PRT' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	B.matclass nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='PRT','20','20')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='GR'
AND SUBSTRING(mi.goods_code,1,3)='PRT' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #PRT
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'21')id
,	T.nama_group
,	ifnull(M.vendor_cat,'GR')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT 'GENERAL' nama_group FROM masteritem WHERE mattype='N' AND matclass='-' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	if(B.matclass='-','GENERAL','GENERAL') nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(mi.mattype='N','21','21')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='2' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='GR'
AND mi.mattype='N'
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #GENERAL
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'22')id
,	T.nama_group
,	ifnull(M.vendor_cat,'GR')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE matclass='MESIN' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	if(B.matclass='-','MESIN','MESIN') nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(mi.mattype='M','22','22')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='2' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='GR'
AND mi.mattype='M'
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #MESIN
)MM 

)M ON T.nama_group=M.nama_group 

UNION ALL 

SELECT ifnull(M.id,'23')id
,	T.nama_group
,	ifnull(M.vendor_cat,'GR')vendor_cat
,	ifnull(M.dpp_idr,'0')dpp_idr
,	ifnull(M.price_USD,'0')price_USD
,	ifnull(M.ppn,'0')ppn
,	ifnull(M.date_journal,'N/A')date_journal

FROM
(SELECT matclass nama_group FROM masteritem WHERE mattype='S' LIMIT 1)T LEFT JOIN (
SELECT  MM.id
,	MM.nama_group
,	MM.vendor_cat
,	MM.dpp_idr
,	MM.price_USD
,	MM.ppn
,	MM.date_journal

FROM (SELECT B.mattype id
,	if(B.matclass='MAJUN','MAJUN','MAJUN') nama_group
,	B.vendor_cat
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(mi.mattype='S','23','23')mattype
,		mi.matclass
,		ms.vendor_cat
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='2' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND ms.vendor_cat='GR'
AND mi.mattype='S' OR SUBSTRING(mi.goods_code,1,3)='MJN' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #SCRAP / MAJUN
)MM 

)M ON T.nama_group=M.nama_group 


) Y GROUP BY Y.id, Y.vendor_cat																			# END GROUP 

)FINAL 

GROUP BY FINAL.id, FINAL.vendor_cat 
ORDER BY FIELD(FINAL.id,1,2,3,12,13,14,15,16,17,18,19,20,21,22,23), FIELD(FINAL.vendor_cat,'GR','NG')

)P ON P.vendor_cat = S.vendor_cat

GROUP BY P.id, P.vendor_cat

ORDER BY FIELD(P.id,1,2,3,12,13,14,15,16,17,18,19,20,21,22,23), FIELD(P.vendor_cat,'GR','NG')

)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

  X.matclass      =  LIKE'%".$searchValue."%'
	X.vendor_cat     =  LIKE'%".$searchValue."%'
	X.price_USD     =  LIKE'%".$searchValue."%'
	X.dpp_idr     		=  LIKE'%".$searchValue."%'
	X.ppn     		=  LIKE'%".$searchValue."%'
	
)
";
}

## Total number of records without filtering
$sel = mysqli_query($conn_li,"select count(*) allcount from $table");
$records = mysqli_fetch_assoc($sel); 

$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($conn_li,"select count(*)  allcount from $table WHERE 1 ".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
$colomn = "		 
				X.nama_group     
				,X.vendor_cat
				,X.price_USD       
				,X.dpp_idr           
				,X.ppn        
				,X.total_idr        
				";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// echo $empQuery;
$count=0;
$hitung_price_usd=0;
$hitung_dpp_idr=0;
$hitung_ppn=0;
$hitung_total_idr=0;

while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];
$hitung_price_usd=$hitung_price_usd+$row['price_USD'];
$hitung_dpp_idr=$hitung_dpp_idr+$row['dpp_idr'];
$hitung_ppn=$hitung_ppn+$row['ppn'];
$hitung_total_idr=$hitung_total_idr+$row['total_idr'];

   $data[] = array(
"nama_group"=>htmlspecialchars($row['nama_group']),
"vendor_cat"=>htmlspecialchars($row['vendor_cat']),
"price_USD"=>htmlspecialchars(number_format((float)$row['price_USD'], 2, '.', ',')),
"dpp_idr"=>htmlspecialchars(number_format((float)$row['dpp_idr'], 2, '.', ',')),
"ppn"=>htmlspecialchars(number_format((float)$row['ppn'], 2, '.', ',')),
"total_idr"=>htmlspecialchars(number_format((float)$row['total_idr'], 2, '.', ','))

   );

$count++;
      if($count>=2)
      {
      	$data[] = array(
      	"nama_group"=>htmlspecialchars(""),
		"vendor_cat"=>htmlspecialchars("SUBTOTAL          :"),
		"price_USD"=>htmlspecialchars(number_format((float)$hitung_price_usd, 2, '.', ',')),
		"dpp_idr"=>htmlspecialchars(number_format((float)$hitung_dpp_idr, 2, '.', ',')),
		"ppn"=>htmlspecialchars(number_format((float)$hitung_ppn, 2, '.', ',')),
		"total_idr"=>htmlspecialchars(number_format((float)$hitung_total_idr, 2, '.', ',')),
		);
        $count=0;
        $hitung_price_usd=0;
        $hitung_dpp_idr=0;
        $hitung_ppn=0;
        $hitung_total_idr=0;
      }
    

}
## Response
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecordwithFilter,
  "iTotalDisplayRecords" => $totalRecords,
  "aaData" => $data
);
echo json_encode($response);
?>

