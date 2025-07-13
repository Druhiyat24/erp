$( document ).ready(function() {
url();	
//getAkunCashBank();
    $("#fromdate").datepicker( {
      format: "dd MM yyyy",

      autoclose: true
    });
    $("#todate").datepicker( {
      format: "dd MM yyyy",

      autoclose: true
    });
});
GenerateTable  = '';
CashBankId = '';
cc = 0;


function url(){
	/*
	url =window.location.search;
	console.log(url);
	myCase = url.split("=");
	if(myCase[1] == "LaporanCash"){
		GenerateTable = headerTableCash;
		getAkunCashBank("CASH");
	}
	else if(myCase[1]== "LaporanBank"){
		GenerateTable = headerTableBank;	
		getAkunCashBank("BANK");
	}
	*/
	
	url =window.location.search;
	console.log(url);
	myCase = url.split("=");
	if(myCase[1] == "LaporanCash" || myCase[1] == "LaporanCashRekap" ){
		GenerateTable = headerTableCash;
		getAkunCashBank("CASH");
		if(myCase[1] == "LaporanCash"){
			console.log("Begin Cash");
			$("#headertitle").text("LAPORAN CASH");
			$("#tablelaporan").css("display","block");
			$("#tablerekap").css("display","none");	
			
		}else if(myCase[1] == "LaporanCashRekap"){
			console.log("Begin Cash Cash");
			$("#headertitle").text("LAPORAN CASH REKAP");
			$("#tablelaporan").css("display","none");
			$("#tablerekap").css("display","block");
			
		}
	}
	else if(myCase[1]== "LaporanBank" || myCase[1] == "LaporanBankRekap" ){
		GenerateTable = headerTableBank;	
		getAkunCashBank("BANK");
		if(myCase[1] == "LaporanBank"){
			$("#headertitle").text("LAPORAN BANK");
			$("#tablelaporan").css("display","block");
			$("#tablerekap").css("display","none");		
		}else if(myCase[1] == "LaporanBankRekap"){
			$("#headertitle").text("LAPORAN BANK REKAP");
			$("#tablelaporan").css("display","none");
			$("#tablerekap").css("display","block");		
		}		
	}
		
	
}

//x=$( "#id_coa option:selected" ).text();

async function rekapTableBank(){
	var td = '';
td +="	  	<table id='examplerekap' class='display responsive' style='width:100%;font-size:12px;'> ";
td +="      <thead>                                                                                 ";
td +="        <tr>                                                                                  ";
td +="	    	<th rowspan='2'>&nbsp;</th>                                                         ";
td +="			<th rowspan='2'>Nomor Akun</th>		                                                ";
td +="	    	<th rowspan='2'>Nama Akun</th>                                                      ";
td +="            <th colspan='2'>Debit</th>                                                        ";
td +="			<th colspan='2'>Kredit</th>                                                         ";
td +="            <th colspan='2'>Saldo</th>                                                        ";
td +="		</tr>                                                                                   ";
td +="        <tr>                                                                                  ";
td +="            <th>Rp</th>                                                                    ";
td +="            <th>$USD</th>                                                                    ";
td +="			<th>Rp</th>                                                                     ";
td +="          <th>$USD</th>                                                                    ";
td +="            <th>Rp</th>                                                                     ";
td +="            <th>$USD</th>                                                                   ";
td +="		</tr>                                                                                   ";
td +="      </thead>                                                                                ";
td +="      <tbody id='renderrekap'>                                                                ";
td +="	                                                                                            ";
td +="      </tbody>                                                                                ";
td +="    </table>	                                                                                ";
	$("#tablerekap").append(td);



	
}

async function rekapTableCash(){
	var td = '';
	
td +="	  	<table id='examplerekap' class='display responsive' style='width:100%;font-size:12px;'> ";
td +="      <thead>                                                                                 ";
td +="        <tr>                                                                                  ";
td +="	    	<th>&nbsp;</th>                                                                     ";
td +="			<th>Nomor Akun</th>		                                                            ";
td +="	    	<th>Nama Akun</th>                                                                  ";
td +="            <th>Debit</th>                                                                    ";
td +="			<th>Kredit</th>                                                                     ";
td +="            <th>Saldo</th>                                                                    ";
td +="		</tr>                                                                                   ";
td +="      </thead>                                                                                ";
td +="      <tbody id='renderrekap'>                                                                ";
td +="	                                                                                            ";
td +="      </tbody>                                                                                ";
td +="    </table>	                                                                                ";
	$("#tablerekap").append(td);
	
}


headerTableCash =  async function(myData){
	var td = '';
td +="	  	<table id='examplefix1010' class='cell-border compact stripe' style='width:100%;font-size:12px;'> ";
td +="      <thead>                                                                                    ";
td +="        <tr>                                                                                     ";
td +="          <th rowspan='2'  align='center'>No					</th>                             ";
td +="          <th rowspan='2' align='center'>Tanggal				</th>                             ";
td +="          <th rowspan='2' align='center'>Nomor Jurnal			</th>                             ";
td +="          <th rowspan='2' align='center'>Nomor Voucher			</th>                             ";
td +="          <th colspan='2' align='center'>Chart of Account/Address</th>                           ";
td +="          <th rowspan='2' 'align='center'>Descriptions			</th>                            ";
td +="                                                                                                 ";
td +="          <th  colspan='2'align='center'>Pihak Lawan Transaksi	</th>                             ";
td +="          <th rowspan='2' align='center'>Nomor Faktur Pajak		</th>                         ";
td +="          <th rowspan='2' align='center'>Nomor Invoice			</th>                             ";
td +="          <th rowspan='2' align='center' style='display:none'>Kurs					</th>                             ";
td +="          <th  rowspan='2'align='center'>Debet					</th>                             ";
td +="          <th  rowspan='2'align='center'>Kredit					</th>                         ";
td +="          <th  rowspan='2'align='center'>Saldo					</th>                             ";
td +="        </tr>                                                                                    ";
td +="        <tr>                                                                                     ";
td +="          <td align='center'>Nomor								</td>                             ";
td +="          <td align='center'>Nama								</td>                             ";
td +="          <td align='center'>Kode/ID							</td>                             ";
td +="          <td align='center'>Nama								</td>                             ";
td +="                                                                                                 ";
td +="                                                                                                 ";
td +="                                                                                                 ";
td +="        </tr>                                                                                    ";
td +="      </thead>                                                                                   ";
td +="      <tbody id='render'>                                                                        ";
td +="	  	                                                                                          ";
td +="      </tbody>                                                                                   ";
td +="    </table>																					  ";
	
	$("#tablelaporan").append(td);
	rekapTableCash();
	await $("#render").append(myData);
	
}


headerTableBank =  async function(myData){
	var td = '';
td +="	  	<table id='examplefix1010' class='cell-border compact stripe ' style='auto;font-size:12px;'> ";
td +="      <thead border='1'>                                                                                    ";
td +="        <tr>                                                                                     ";
td +="          <th rowspan='2'  align='center'>No					</th>                             ";
td +="          <th rowspan='2' align='center'>Tanggal				</th>                             ";
td +="          <th rowspan='2' align='center'>Nomor Jurnal			</th>                             ";
td +="          <th rowspan='2' align='center'>Nomor Voucher			</th>                             ";
td +="          <th colspan='2' align='center'>Chart of Account/Address</th>                           ";
td +="          <th rowspan='2' 'align='center'>Descriptions			</th>                            ";
td +="          <th rowspan='2' 'align='center'>No Bill Yet			</th>                            ";
td +="          <th  colspan='2'align='center'>Pihak Lawan Transaksi	</th>                             ";
td +="          <th rowspan='2' align='center'>Nomor Faktur Pajak		</th>                         ";
td +="          <th rowspan='2' align='center'>Nomor Invoice			</th>                             ";
td +="          <th rowspan='2' align='center' style='display:none'>Kurs					</th>                             ";
td +="          <th  colspan='2' align='center'>Debet					</th>                             ";
td +="          <th   colspan='2'  align='center'>Kredit					</th>                         ";
td +="          <th   colspan='2' align='center'>Saldo					</th>                             ";
td +="        </tr>                                                                                    ";
td +="        <tr>                                                                                     ";
td +="          <td align='center'>Nomor								</td>                             ";
td +="          <td align='center'>Nama								</td>                             ";
td +="          <td align='center'>Kode/ID							</td>                             ";
td +="          <td align='center'>Nama								</td>                             ";
td +="          <td align='center'>Rupiah								</td>                             ";
td +="          <td align='center'>USD								</td>                             ";
td +="          <td align='center'>Rupiah								</td>                             ";
td +="          <td align='center'>USD								</td>                             ";
td +="          <td align='center'>Rupiah								</td>                             ";
td +="          <td align='center'>USD								</td>                             ";
td +="        </tr>                                                                                    ";
td +="      </thead>                                                                                   ";
td +="      <tbody id='render'>                                                                        ";
td +="	  	                                                                                          ";
td +="      </tbody>                                                                                   ";
td +="    </table>																					  ";
	
	$("#tablelaporan").append(td);
	rekapTableBank();
	await $("#render").append(myData);
	
}



saldoawalfooter = 0;
async function rekapbank(myRekap){
	saldoawalfooter = decodeURIComponent(myRekap.saldoawal);
	curr = decodeURIComponent(myRekap.curr)
	console.log(myRekap);

		td  += "<tr>";
			td  += "<td>";
				td  += " ";
			td  += "</td>";
			td  += "<td>";
				td  += " ";
			td  += "</td>";		

			td  += "<td>";
				td  += "Saldo Awal :";
			td  += "</td>";	


			td  += "<td>";
				td  += " ";
			td  += "</td>";				
	
			td  += "<td>";
				td  += " ";
			td  += "</td>";	


			td  += "<td>";
				td  += " ";
			td  += "</td>";		
			
			td  += "<td>";
				td  += "";
			td  += "</td>";		
			
			if(curr == "IDR"){
				td  += "<td>";
				td  += formatRupiah(saldoawalfooter);
			td  += "</td>";		

			td  += "<td>";
				td  += "";
			td  += "</td>";					
				
				
			}else if(curr == "USD"){


			td  += "<td>";
				td  += "";
			td  += "</td>";		
			
				td  += "<td>";
				td  += formatRupiah(saldoawalfooter);
			td  += "</td>";						
				
				
			}
			



			td  += "<td>";
				td  += "";
			td  += "</td>";		
			td  += "<td>";
				td  += "";
			td  += "</td>";				
			
		td  += "</tr>";

		for(i=0;i<myRekap.records.length;i++){
			saldoawalfooter = decodeURIComponent(myRekap.records[0].saldoawal);
		td  += "<tr>";
		
			td  += "<td>";
				td  += " ";
			td  += "</td>";
			


			td  += "<td>";
				td  += decodeURIComponent(myRekap.records[i].id_coa);
			td  += "</td>";	


			td  += "<td>";
				td  += decodeURIComponent(myRekap.records[i].nm_coa);
			td  += "</td>";				
	

			
			if(curr == "IDR"){
				
				
			td  += "<td>";
				td  += formatRupiah(decodeURIComponent(myRekap.records[i].debit));
			td  += "</td>";	

			td  += "<td>";
				td  += "0.00";
			td  += "</td>";	

			td  += "<td>";
				td  += formatRupiah(decodeURIComponent(myRekap.records[i].credit));
			td  += "</td>";		

			td  += "<td>";
				td  += "0.00";
			td  += "</td>";					
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				td  += "<td>";
					td  += formatRupiah(decodeURIComponent(myRekap.records[i].saldoberjalan));
				td  += "</td>";		
			
				td  += "<td>";
					td  += "0.00";
				td  += "</td>";					
			}else if(curr == "USD"){
				
				td  += "<td>";
				td  += "0.00";
			td  += "</td>";	
			
				
			td  += "<td>";
				td  += formatRupiah(decodeURIComponent(myRekap.records[i].debit));
			td  += "</td>";	


			td  += "<td>";
				td  += "0.00";
			td  += "</td>";		


			td  += "<td>";
				td  += formatRupiah(decodeURIComponent(myRekap.records[i].credit));
			td  += "</td>";		

			
				
				
				
				
				
				
				
				
				
				
				
				
				
				
			
				td  += "<td>";
					td  += "0.00";
				td  += "</td>";						
				
				
					td  += "<td>";
					td  += formatRupiah(decodeURIComponent(myRekap.records[i].saldoberjalan));
				td  += "</td>";		
			
				
			}
			
			

	
		td  += "</tr>";
	
	
	}
	$("#renderrekap").append(td);	
	await $('#examplerekap').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });
	
}


async function rekapcashbank(myRekap){
	saldoawalfooter = decodeURIComponent(myRekap.saldoawal);
	console.log(myRekap);

		td  += "<tr>";
		
			td  += "<td>";
				td  += " ";
			td  += "</td>";
			td  += "<td>";
				td  += " ";
			td  += "</td>";		

			td  += "<td>";
				td  += "Saldo Awal :";
			td  += "</td>";	


			td  += "<td>";
				td  += " ";
			td  += "</td>";				
	
			td  += "<td>";
				td  += " ";
			td  += "</td>";	



			
			td  += "<td>";
				td  += formatRupiah(saldoawalfooter);
			td  += "</td>";		
			
		td  += "</tr>";

		for(i=0;i<myRekap.records.length;i++){
			saldoawalfooter = decodeURIComponent(myRekap.records[0].saldoawal);
			console.log(saldoawalfooter);
		td  += "<tr>";
		
			td  += "<td>";
				td  += " ";
			td  += "</td>";
			


			td  += "<td>";
				td  += decodeURIComponent(myRekap.records[i].id_coa);
			td  += "</td>";	


			td  += "<td>";
				td  += decodeURIComponent(myRekap.records[i].nm_coa);
			td  += "</td>";				
	
			td  += "<td>";
				td  += formatRupiah(decodeURIComponent(myRekap.records[i].debit));
			td  += "</td>";	


			td  += "<td>";
				td  += formatRupiah(decodeURIComponent(myRekap.records[i].credit));
			td  += "</td>";		
			
			td  += "<td>";
				td  += formatRupiah(decodeURIComponent(myRekap.records[i].saldoberjalan));
			td  += "</td>";		
			
		td  += "</tr>";
	
	
	}
	$("#renderrekap").append(td);	
	await $('#examplerekap').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });
	
}

function getidcashbank(idcoa){
	CashBankId = idcoa;
	//alert(CashBankId);
}
	function getAkunCashBank(myCoas){
	    $.ajax({		
        type:"POST",
        cache:false,
        url:"webservices/getAkunCashBank.php", 
        data : { code : '1', type:"Laporan", typeidcoa: myCoas },     // multiple data sent using ajax
        success: function (response) {
			//console.log(response);
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				//d = response;
				option  = '';
				renders = '';
			//	console.log(d.records.length);
				if(d.message == '1'){
					//	$("#render").append(data[1]);
					//	console.log(d.records);
						for(i=0;i<d.records.length;i++){
							option += "<option value="+decodeURIComponent(d.records[i][0].id)+">"+decodeURIComponent(d.records[i][0].id) +" | "+decodeURIComponent(d.records[i][0].nama)+"</option>";
						}//department
						$("#idcoa").append(option);

				setTimeout(function(){ 				

				}, 3000);						
						
						
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	
	
}	
	


function getLaporan() {
	$("#MasterKursPajak").dataTable().fnDestroy();$("#MasterKursPajak").dataTable().fnDestroy();
	url2 =window.location.search;
	console.log(url2);
	myCase2 = url2.split("=");
	console.log(myCase2);
	var dumptitle= $("#idcoa").val();
	var dumpdate= $("#fromdate").val();
	var dumptodate= $("#todate").val();
	dumptitle=$( "#idcoa option:selected" ).text();
	split = dumptitle.split("|");
	
	
	if(myCase2[1] == "LaporanCash" || myCase2[1] == "LaporanCashRekap" ){
		GenerateTable = headerTableCash;
		$("#bukukas").text(split[1]);
	}
	else if(myCase2[1]== "LaporanBank" || myCase2[1] == "LaporanBankRekap" ){
		GenerateTable = headerTableBank;
		myrek=split[1];
		split2 = myrek.split(" ");
		console.log("bgin Title bank");
		console.log(split2);
		$("#bukukas").text(split2[1]);
		$("#rekening").text(" A/C " + split2[2]);  
		console.log(split2[0]);
		console.log(split2[1]);
	}
	
	$("#periode").text(dumpdate+" s/d "+dumptodate);
	
	froms = $("#fromdate").val();
	tos   = $("#todate").val();
    $("#loading").css("display", "block");
    $("#search").css("display", "none");
	if(froms == '') {alert("From date harus diisi");return false}
	if(tos  == '')  {alert("To date harus diisi");return false}
	getListData();
	
	
	
	  setTimeout(function(){     
	  
 var table = $('#examplefix1010').DataTable
    ({  
	    destroy: true,
     searching: true,
     ordering:  true,
     lengthMenu: [5, 10, 25, 50, 75, 100 ]  , 
	
	scrollY: 200,
        scrollX: true,
        scrollCollapse: true,
        paging: true,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1   
        }

    });
	
		function edit(){  
		alert(data);
	}
	}, 7000);	
	
}

function getChild(Item) {
	//alert(Item);
	console.log(Item);
	classs = $("#" + Item).attr('class');
    console.log(classs);
    var Mysplit = classs.split(" ");
    if (Mysplit[1] == 'fa-minus') {
        console.log('MINUS');
        $("." + Item).remove();
        $("#" + Item).toggleClass('fa-plus fa-minus');
        return false;
    }
    $("#" + Item).toggleClass('fa-plus fa-minus');
    $.ajax({
        type: "POST",
        cache: false,
        url: "webservices/getChildCashBank.php",
        data: { code: '1', from: froms, to: tos,akun:Item,idcashbank : CashBankId  },     // multiple data sent using ajax
        success: function (response) {
            console.log(response);
            data = response.split("<-|->");
            d = JSON.parse(data[0]);
			console.log(d);
            //d = response;
            td = '';
            renders = '';
            console.log(d.records.length);
            if (d.message == '1') {
               // $("#Group" + Item).append(data[1]);
                $(data[1]).insertAfter($("#Group" + Item).closest('tr'));
				if(cc == '0'){
					if(myCase[1] == "LaporanCash" || myCase[1] == "LaporanCashRekap" ){
							rekapcashbank(d);
						
					}
					else if(myCase[1] == "LaporanBank" || myCase[1] == "LaporanBankRekap" ){
						rekapbank(d);
						
					}
					
					cc = cc+1;
				}
				
				
                setTimeout(function () {

                    //console.log(Ddata);	
                    $("#uijurnal").css("display", "block");
                    $("#backs").css("display", "block");
                    $("#abcd").css("display", "none");

                }, 3000);


            }
            if (d.message == '2') {
                alert("Input Tanggal Salah !")
            }
        }
    });	
}

	async function getListData(){
	    $.ajax({		
        type:"POST",
        cache:false,
		 async: false,
        url:"webservices/getListLaporanCashBank.php", 
        data : { code : '1',  from: froms, to :tos,idcashbank : CashBankId },     // multiple data sent using ajax
        success: function (response) {
			console.log(response);
			data = response.split("<-|->");
				d = JSON.parse(data[0]);
				//d = response;
				td  = '';
				renders = '';
				Induk = d.records[0];
				console.log(d.records[0]);
				if(d.message == '1'){
						GenerateTable(data[1]);
				setTimeout(function(){ 				
				
				//console.log(Ddata);	
				$("#uijurnal").css("display","block");
				$("#backs").css("display","block");
				$("#abcd").css("display","none");
				//console.log(CashBankId);
				if(CashBankId != ''){
					tempid = "Group"+Induk;
					console.log(tempid);
					getChild(Induk);
					
				}
				
				}, 3000);						
						
						
				}
				if(d.message == '2'){
					alert("Input Tanggal Salah !")
				}
        }
      });	
	
	
}

function back(){
	 location.reload();
	
}

function formatRupiah(angka){
			var number_string = angka;
			split2  		= number_string.split('.');
			sisa     		= split[0].length % 3;
			rupiah     		= split[0].substr(0, sisa);
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
			rupiah = split2[1] != undefined ? rupiah + ',' + split2[1] : rupiah;
			console.log(split2[1]);
			if(split2[1]){
				console.log("MASUK");
				rupiah = rupiah + ',' + split2[1];
			}
				//$("#nilai").val(rupiah);
			return rupiah;
		
			
		}