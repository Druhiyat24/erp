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
	return $("#"+id).append(string);
}
function Opt($url_opt) {
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

function initFormat(check_url_nya){
		if ($G_kondisi == '1') {
			return format = '2';
	}
	else {
			return format = '1';
	}	
	
}	

function disable_attr(kondisi,my_id){
	if(kondisi == '1'){
		return [
			$("#"+my_id).attr("disabled","true"),
		];	
	}else{
		return 1;
	}
}

