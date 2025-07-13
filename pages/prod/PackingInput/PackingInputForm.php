
<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='s_pack.php?mod=6' onsubmit='return validasi()'>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Tanggal Input *</label>
            <input type='text' class='form-control' 
              name='txtdateout' placeholder='Masukkan Tanggal Input' id="tanggalinput" >
          </div>
          <div class='form-group'>
            <label>Notes</label>
            <input type='text' class='form-control' id="notes" name='txtnotes' 
              placeholder='Masukkan Notes' value='' >
          </div>
          <div class='form-group'>
            <label>Line *</label>
            <select class='form-control select2' id="line" style='width: 100%;' 
              name='txtLine'>
      </select>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Process *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtPro' id="proses" onchange='getListJo()'>
           </select>
          </div>
          <div class='form-group'>
            <label>WS # *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtJOItem' id='ws' onchange='getDetail()'   >
            </select>
          </div>
        </div>
        <div class='box-body'>
          <div id='detail_item'></div>
        </div>
        <div class='col-md-3'>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>  
      </section>
      <!-- /.content -->
    </div>
  </div>
</div>
