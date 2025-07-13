$(document).ready(function () {

});

function create_separator($angka, $id) {
	$ang_ka_ = $angka.replace(/,/g, '').toString();
	return new Promise(function (resolve, reject) {
		if (isNaN($ang_ka_)) {
			$res = {
				angka: 'NAN',
				id: $id,
				message: 'NO'
			}
			resolve($res);
		} else {
			$.ajax("webservices/create_separator.php",
				{
					type: "POST",
					data: { code: '1', angka: $ang_ka_, id: $id },
					success: function (data) {
						data = jQuery.parseJSON(data)
						$res = {
							angka: data.records,
							id: data.id,
							message: 'OK'
						}
						console.log(data.records);
						resolve($res);
					},
					error: function (data) {
						alert("Error req");
					}
				});
		}
	})
};


function create_separator2($angka, $id) {
	$ang_ka_ = $angka.replace(/,/g, '').toString();
	return new Promise(function (resolve, reject) {
		if (isNaN($ang_ka_)) {
			$res = {
				angka: 'NAN',
				id: $id,
				message: 'NO'
			}
			resolve($res);
		} else {
			$.ajax("webservices/create_separator2.php",
				{
					type: "POST",
					data: { code: '1', angka: $ang_ka_, id: $id },
					success: function (data) {
						data = jQuery.parseJSON(data)
						$res = {
							angka: data.records,
							id: data.id,
							message: 'OK'
						}
						console.log(data.records);
						resolve($res);
					},
					error: function (data) {
						alert("Error req");
					}
				});
		}
	})
};


function remove_separator($angka) {
	if(typeof $angka === 'undefined'){
		$ang_ka_=0;
	}else{
		$ang_ka_ = $angka.replace(/,/g, '').toString();
	}
	return parseFloat($ang_ka_);
};	


function DecodeFromServices(data){
        for(var i=0, length=data.length;i<length;i++) {
            for ( var temp in data[i] ) {
                data[i][temp] = decodeURIComponent(data[i][temp]);
            } 
        }
        return data;
    }

//PROMISE OPTION
function generate_option(PropHtmlData,judul){
	console.log(PropHtmlData);
	var option = "";
	option += "<option value=''>--"+judul+"--</option>";
	var i;
	for (i = 0; i < PropHtmlData.records.length; i++) {
		var id = decodeURIComponent(PropHtmlData.records[i].id);
		var isi = decodeURIComponent(PropHtmlData.records[i].isi);
		option += '<option value="' + id + '">' + isi + '</option>';
	}	
	return option;
}
function injectOptionToHtml(string,id){
		$("#"+id).empty();
	setTimeout(function(){
		return $("#"+id).append(string);
	},200)
}
function Option($url_opt) {
	return new Promise(function(resolve, reject) {
	$.ajax($url_opt,
		{
			type: "POST",
			data: {code:'1'},
			success: function (data) {
				resolve(jQuery.parseJSON(data));
			},
			error: function (data) {
				alert("Error req");
			}
		});
		
	});
}

function checkUrl(check_url_nya){
		if (check_url_nya) {
			$G_id_url = check_url_nya;
			$G_kondisi = 1;
			return "1";
		//$("#ws").attr("disabled", true)

		//id_url = $split_nya[2]
	}
	else {
		$G_id_url = 'XX';
		$G_kondisi = 0;
		return false;
		//id_url = -1
	}
	
	
}	


function auth_page(){
	return new Promise(function(resolve, reject) {
		var $_split_nya = url.split("=");
		if(!$_split_nya[2]){
			var $_auth = 0;
			alert("Data Tidak Lengkap!");
			window.location.href = "?mod=SpreadingReport";
		}else{
			var $_auth = 1;
		}
		resolve($_auth);
	});
	
	
}



function initFormat(check_url_nya){
		if ($G_kondisi == '1') {
			return format = '2';
	}
	else { 
			return format = '1';
	}	
	
}	

function show_loading(){
	$("#myOverlay").css("display","block");
}
function hide_loading(){
	return($("#myOverlay").css("display","none"));
}

function validasi_option($_json,$_mod_url,$_type_opt){
	if(decodeURIComponent($_json.records.length) == 0){
		alert("Data "+$_type_opt+" Kosong");
		my_back($_mod_url);
	}else{
		return $_json;
	}
	
}


function my_back($_url){
	window.location = $_url;
	
}


function parse_query_string(query) {
  var vars_ = query.split("?");
  var vars   = vars_[1].split("&");
  var query_string = {};
  for (var i = 0; i < vars.length; i++) {
    var pair = vars[i].split("=");
    var key = decodeURIComponent(pair[0]);
    var value = decodeURIComponent(pair[1]);
    // If first entry with this name
    if (typeof query_string[key] === "undefined") {
      query_string[key] = decodeURIComponent(value);
      // If second entry with this name
    } else if (typeof query_string[key] === "string") {
      var arr = [query_string[key], decodeURIComponent(value)];
      query_string[key] = arr;
      // If third or later entry with this name
    } else {
      query_string[key].push(decodeURIComponent(value));
    }
  }
  return query_string;
}

function get_url_old($url_search){
	return new Promise(function (resolve, reject) {
		$split = $url_search.split("=");
		$populasi_id_url = "";
		if(typeof $split[2] === 'undefined'){
			$populasi_id_url = {G_kondisi : '0',format:'1'}
			console.log($populasi_id_url);
			resolve($populasi_id_url);
		}else{
			var $_i = $split.length;
					populasi_params_by_url = [];
					//console.log($_i);
					populasi_value_by_url = [];
					trigger = $_i - 1;
					var $outp = "";
					//if ($outp != "") {$outp .= ",";}
					for(var i=0;i<$_i;i++){
						if( (i >=0) && (i <=1) ){
							outs = "";
						}else{
								var $tmp_split = $split[i].split("&");
								populasi_value_by_url.push($tmp_split[0]);
							
						}
					}
					
					for(var j=0;j<trigger;j++){
						if( (j == 0) ){
							outs = "";
						}else{
								var $tmp_split = $split[j].split("&");
								populasi_params_by_url.push($tmp_split[1]);
						}
					}	
					if ($outp != "") {$outp += ",";}
					$outp += "{";
					var tr_ger = populasi_params_by_url.length - 1;
					for(var k=0;k<populasi_params_by_url.length;k++){
						if(k == tr_ger){
						var $tanda_baca = "}";
						}else{
						var $tanda_baca = ",";
						}
						$outp += '"'+populasi_params_by_url[k]+'":"'+populasi_value_by_url[k]+'"'+$tanda_baca
					}
						$populasi_id_url =JSON.parse($outp);
						$populasi_id_url.G_kondisi = '1';
						$populasi_id_url.format = '2';
						console.log($populasi_id_url);
						resolve($populasi_id_url);			
		}
		
	});
}



function get_url($url_search){
	console.log($url_search);
	return new Promise(function (resolve, reject) {
		$populasi_id_url =  parse_query_string($url_search);
		setTimeout(function(){
			if(($populasi_id_url.cr == 'is_add') ){
				$_default_url = "?mod="+$populasi_id_url.mod+"&cr=is_add";
				if($_default_url.length != $url_search.length){
					window.location = "../prod/"+$_default_url;
				}
				$populasi_id_url.G_kondisi = '0';
				$populasi_id_url.format = "1";								
			}else{
				$populasi_id_url.G_kondisi = '1';
				$populasi_id_url.format = '2';				
			}
			setTimeout(function(){
				console.log($populasi_id_url.format);
					resolve($populasi_id_url);
			},2000)
			console.log($populasi_id_url);
			return $populasi_id_url;			
		},100)


		
	});
}

function is_route(akses){
	if(akses != '1'){
		alert("Access Not Allowd!")
		window.location = "../prod/?mod=1";
	}else{
		return 1;
	}
}

function check_cr(){
	if ( ($populasi_id_url.cr == "is_add") || ($populasi_id_url.cr == "is_edit") || ($populasi_id_url.cr == "is_view")){
		return 1;
	}else{
		console.log($populasi_id_url);
		alert("Access Problem! Session maybe Expired!!")
		window.location = "../prod/?mod=1";		
	}
}

function check_validation_again(){
	if($populasi_id_url.G_kondisi == '1'){
		if( ($populasi_id_url.cr == "is_edit") || ($populasi_id_url.cr == "is_view") ){
			return 1;			
		}else{
			alert("Access Problem!#555");
			window.location = "../prod/?mod=1";		
		}
	}else if($populasi_id_url.G_kondisi == '0'){
		if($populasi_id_url.cr == "is_add"){
				return 1;						
		}else{
			alert("Access Problem!#556");
			window.location = "../prod/?mod=1";
		}		
	}else{
		return 1;
	}
	
	
}

function is_view(){
	if($populasi_id_url.cr == 'is_view'){
		return disabled_all();
		
	}else{
		return 1;
	}
}

function disabled_all(){
	$("body :input").attr("disabled","disabled");
	$(".btn-primary").remove();
	
}
function auth($mod,$type_crud){
	
	return new Promise(function(resolve, reject) {
	$.ajax("webservices/AUTH.php?mod="+$mod+"&type_crud="+$type_crud,
		{
			type: "POST",
			data: {code:'1'},
			success: function (data) {
				$___d = jQuery.parseJSON(data)
				resolve($___d.records[0].akses);
			},
			error: function (data) {
				alert("Error req");
			}
		});
		
	});
}



function init_url(pop_url){
	return global_populasi_params = pop_url;
	
}