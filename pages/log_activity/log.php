<?php

function insert_log_nw($Querynya, $Trans_User)
{	
	include __DIR__.'/../../include/conn.php';
	if (!empty($_SERVER["HTTP_CLIENT_IP"]))
	{	$ip = $_SERVER["HTTP_CLIENT_IP"]; }
	elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
	{ $ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; }
	else
	{ $ip = $_SERVER["REMOTE_ADDR"]; }
	$host_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$Trans_Date = date("Y-m-d H:i:s");
	$Trans_Host = $Trans_User.'/'.$ip.'/'.$host_name;
/* 	if (substr($Querynya,0,1)!="X")
	{	$strsql=mysql_query($Querynya);
		if (!$strsql) { die($Querynya. mysql_error()); }
	} */
	if (substr($Querynya,0,1)!="X")
	{$QuerynyaRep = str_replace("'", "!",$Querynya);}
	else
	{$QuerynyaRep = str_replace("'", "!",substr($Querynya,1,strlen($Querynya)));}
	$que = "insert into act_hist (Trans_Date,Trans_Desc,Trans_Host) values ('$Trans_Date','$QuerynyaRep','$Trans_Host')";
	$strsql=mysql_query($que);
	if (!$strsql) { die($que. mysql_error()); }
};



?>