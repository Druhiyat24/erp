<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$mod=$_GET['mod'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

$id_sub_group=$_POST['cboid'];
$type=nb($_POST['txttype']);
$type_desc=nb($_POST['txttype_desc']);
$cek=flookup("kode_desc","masterdesc","id_color='$id_sub_group' and 
  kode_desc='$type' and nama_desc='$type_desc' ");
if ($cek!="")
{ $_SESSION['msg'] = "XData Sudah Ada";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else if ($id_item!="")
{ $cek=flookup("id_item","masteritem","id_gen='$id_item'");
  if($cek=="")
  { $sql="update masterdesc set id_color='$id_sub_group',
    kode_desc='$type',nama_desc='$type_desc' 
    where id='$id_item'";
    insert_log($sql,$user);
    $_SESSION['msg'] = "Data Berhasil Dirubah";
  }
  else
  { $_SESSION['msg'] = "XData Tidak Bisa Dirubah"; }
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
else
{ $sql="insert into masterdesc (id_color,kode_desc,nama_desc) 
    values ('$id_sub_group','$type','$type_desc')";
  insert_log($sql,$user);
  $sql="insert into masteritem (mattype,id_gen,matclass,size,color,goods_code,itemdesc)
    SELECT if(a.nama_group regexp 'FABRIC','F','A'),j.id,a.nama_group,f.nama_width,i.nama_color,concat(if(a.kode_group='-','',a.kode_group),
    if(s.kode_sub_group='-','',s.kode_sub_group),if(d.kode_type='-','',d.kode_type),
    if(e.kode_contents='-','',e.kode_contents),if(f.kode_width='-','',f.kode_width),
    if(g.kode_length='-','',g.kode_length),
    if(h.kode_weight='-','',h.kode_weight),
    if(i.kode_color='-','',i.kode_color),
    if(j.kode_desc='-','',j.kode_desc)) gen_kode,
    concat(a.nama_group,
    if(s.nama_sub_group='-','',concat(' ',s.nama_sub_group)),
    if(d.nama_type='-','',concat(' ',d.nama_type)),
    if(e.nama_contents='-','',concat(' ',e.nama_contents)),
    if(f.nama_width='-','',concat(' ',f.nama_width)),
    if(g.nama_length='-','',concat(' ',g.nama_length)),
    if(h.nama_weight='-','',concat(' ',h.nama_weight)),
    if(i.nama_color='-','',concat(' ',i.nama_color)),
    if(j.nama_desc='-','',concat(' ',j.nama_desc))) itemdesc 
    FROM mastergroup a inner join mastersubgroup s on a.id=s.id_group
    inner join mastertype2 d on s.id=d.id_sub_group
    inner join mastercontents e on d.id=e.id_type
    inner join masterwidth f on e.id=f.id_contents 
    inner join masterlength g on f.id=g.id_width
    inner join masterweight h on g.id=h.id_length
    inner join mastercolor i on h.id=i.id_weight
    inner join masterdesc j on i.id=j.id_color
    left join masteritem mi on j.id=mi.id_gen 
    where mi.id_item is null";
  insert_log($sql,$user); 
  $_SESSION['msg'] = "Data Berhasil Disimpan";
  echo "<script>window.location.href='index.php?mod=$mod';</script>";
}
?>