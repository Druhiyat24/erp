<style>
    th{
        text-align: center;
    }
    .forms{
        text-align: right;
    }
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

<div class="box">
    <div class="box-body">
                
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>DC Join Date *</label>
                    <input type="text" class="form-control" id="date" name="date" value="<?php echo date('d M Y'); ?>"" disabled>
                </div>
                <div class="form-group">
                    <label>WS *</label>
                    <select class='form-control select2' id="ws" style='width: 100%;' onchange="getDetail(this.value)">
                        <!-- <option selected disabled>--Choose WS--</option> -->
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>DC Join Number</label>
                    <input type="text" class="form-control" name="djnum" id="djnum" value="" readonly>
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <input type="text" class="form-control" name='notes' id="notes" placeholder="Input Notes" value="" >
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary simpan" name='submit' onclick="Save()">Save</button>
                <button type="submit" class="btn btn-warning" name='cancel' onclick="Cancel()">Back</button>
            </div>
        </div>
        
    </div>
</div>

<div class="box boxxer">
    <div class="box-body">
        <div class="row">
        
            <div class="col-md-12">
                <table id="detail_item" class="table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th style="text-align: center;background-color:#FFFFFF">No</th>
                            <th style="text-align: center;background-color:#FFFFFF; min-width: 150px !important;">Product Description</th>
                            <th style="text-align: center;background-color:#FFFFFF">Color</th>
                            <th style="text-align: center;background-color:#FFFFFF">Size</th>
                            <th style="text-align: center;background-color:#FFFFFF; min-width: 50px !important;">Shell</th>
                            <th style="text-align: center;background-color:#FFFFFF">Panel</th>
                            <th style="text-align: center;background-color:#FFFFFF">LOT</th>
                            <th style="text-align: center;background-color:#FFFFFF">Qty SO</th>
                            <th style="text-align: center;background-color:#FFFFFF">Qty Cumulative</th>
                            <th style="text-align: center;background-color:#FFFFFF">Balance</th>
                            <th style="text-align: center;background-color:#FFFFFF">Cutting Number</th>
                            <th style="text-align: center;background-color:#FFFFFF">Bundle Number</th>
                            <th style="text-align: center;background-color:#FFFFFF">Sack Number</th>
                            <th style="text-align: center;background-color:#FFFFFF">Qty Input DC Join</th>
                            <th style="text-align: center;background-color:#FFFFFF">Qty Reject DC Join</th>
                            <th style="text-align: center;background-color:#FFFFFF">Qty DC Join</th>
                            <!-- <th style="text-align: center;background-color:#FFFFFF">Status</th> -->
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</div>