<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='s_pack.php?mod=<?php echo $mod; ?>' onsubmit='return validasi()'>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Tanggal Input *</label>
            <input type='text' onchange="handlekeyup(this)" class='form-control' id='tanggalinput' 
              name='txtdateout' placeholder='Masukkan Tanggal Output' value='' >
          </div>
          <div class='form-group'>
            <label>Notes</label>
            <input type='text' class='form-control' onkeyup ="handlekeyup(this)" name='txtnotes' id="notes"
              placeholder='Masukkan Notes' value='' >
          </div>
		  </div>
		   <div class='col-md-3'>
          <div class='form-group'>
            <label>Inhouse / Subkon *</label>
            <select class='form-control select2' onchange="handlekeyup(this);getListDeptSubcon()" id="inhousesubcon" style='width: 100%;' 
              name='txtLine'>
             <?php /*
              $sql = "select id_supplier isi,supplier tampil from 
                mastersupplier where area='LINE'";
              IsiCombo($sql,'','Pilih Line'); */
              ?>
            </select>
          </div>
          <div class='form-group'>
            <label>Dept / Subkon *</label>
            <select class='form-control select2' onchange="handlekeyup(this)" id="deptsubcon" style='width: 100%;' 
              name='txtLine'>
             <?php /*
              $sql = "select id_supplier isi,supplier tampil from 
                mastersupplier where area='LINE'";
              IsiCombo($sql,'','Pilih Line'); */
              ?>
            </select>
          </div>		  
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Process *</label>
            <select onchange="handlekeyup(this)" class='form-control select2' style='width: 100%;' id="proses"
              name='txtPro' onchange="getListJo();handlekeyup(this)" >
              <?php 
            /*  $sql = "select nama_pilihan isi,nama_pilihan tampil from 
                masterpilihan where kode_pilihan regexp 'Pack_Pro' order by kode_pilihan";
                IsiCombo($sql,'','Pilih Process');
             */ 
			  ?>
            </select>
          </div>
          <div class='form-group'>
            <label>WS # *</label>
            <select class='form-control select2' id="ws" style='width: 100%;' 
              name='txtJOItem' id='ws' onchange="getDetail(this.value);handlekeyup(this)">
            </select>
          </div>
        </div>
        <div class='box-body'>
          <div id='detail_item'></div>
        </div>
        <div class='col-md-3'>
          <a type='submit' name='submit' onclick="save()" class='btn btn-primary'>Simpan</a>
        </div>
      </form>
    </div>
  </div>
  
         <div class='box-body'>
          <div id='detail_item'></div>
        </div> 
  
</div>