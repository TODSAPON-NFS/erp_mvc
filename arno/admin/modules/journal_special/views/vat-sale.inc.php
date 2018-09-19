<!-- ***************************************************** Invoice Payment ************************************************************* -->
<div id="modalAdd" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">ป้อนรายละเอียดรายการภาษีซื้อ</h4>
        </div>

        <div  class="modal-body" align="left">
            <div class="row"> 
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>เลขที่ใบกำกับภาษี <font color="#F00"><b>*</b></font> </label>
                        <input id="invoice_code" name="invoice_code" class="form-control" value="<?php echo $journal_special_invoices['invoice_code']; ?>" >
                        <p class="help-block">Example : -.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>วันที่ใบกำกับภาษี <font color="#F00"><b>*</b></font> </label>
                        <input id="invoice_date" name="invoice_date" class="form-control calendar" value="<?php echo $journal_special_invoices['invoice_date']; ?>" readonly />
                        <p class="help-block">Example : -.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                        <input id="supplier_code" name="supplier_code" class="form-control" value="<? echo $supplier['supplier_code'];?>" readonly>
                        <p class="help-block">Example : A0001.</p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <label>ผู้ขาย / Supplier  <font color="#F00"><b>*</b></font> </label>
                        <select id="supplier_id" name="supplier_id" class="form-control select" onchange="get_supplier_detail()" data-live-search="true">
                            <option value="">Select</option>
                            <?php 
                            for($i =  0 ; $i < count($suppliers) ; $i++){
                            ?>
                            <option <?php if($suppliers[$i]['supplier_id'] == $supplier['supplier_id']){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> </option>
                            <?
                            }
                            ?>
                        </select>
                        <input id="supplier_name" name="supplier_name" class="form-control" />
                        <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>เลขประจำตัวผู้เสียภาษี / Tax ID. <font color="#F00"><b>*</b></font></label>
                        <input id="supplier_tax" name="supplier_tax" class="form-control" readonly>
                        <p class="help-block">Example : Somchai Wongnai.</p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font></label>
                        <textarea  id="supplier_address" name="supplier_address" class="form-control" rows="5" readonly><? echo $supplier['supplier_address_1'] ."\n". $supplier['supplier_address_2'] ."\n". $supplier['supplier_address_3'];?></textarea >
                        <p class="help-block">Example : -.</p>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>ยื่นภาษีรวมในงวด <font color="#F00"><b>*</b></font> </label>
                        <input id="vat_section" name="vat_section" class="form-control" value="<?php echo $journal_special_invoices['vat_section']; ?>" >
                        <p class="help-block">Example : 08/61.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>ยื่นเพิ่มเติม <font color="#F00"><b>*</b></font> </label>
                        <input id="vat_section_add" name="vat_section_add" class="form-control" value="<?php echo $journal_special_invoices['vat_section_add']; ?>" >
                        <p class="help-block">Example : -.</p>
                    </div>
                </div>
            </div>  
        </div>

        <table width="100%" class="table table-striped table-bordered table-hover" >
            <thead>
                <tr>
                    <th style="text-align:center;" colspan="2">ภาษีซื้อขอคืนได้</th>
                    <th style="text-align:center;" colspan="2">ภาษีซื้อขอคืนไม่ได้</th>
                    <th style="text-align:center;" rowspan="2">มูลค่าสินค้าหรือบริการอัตราศูนย์</th>  
                </tr>
                <tr>
                    <th style="text-align:center;" >มูลค่าสินค้า</th>
                    <th style="text-align:center;" >จำนวนภาษี</th>
                    <th style="text-align:center;" >มูลค่าสินค้า</th>
                    <th style="text-align:center;" >จำนวนภาษี</th>
                </tr>
            </thead>
            <tbody> 
                <tr class="odd gradeX">
                    <td align="right"><input type="text" class="form-control" style="text-align: right;" id="product_price" name="product_price" onchange="update_vat()" value="<?php echo $journal_special_invoices['product_price']; ?>" /></td>
                    <td align="right"><input type="text" class="form-control" style="text-align: right;" id="product_vat" name="product_vat" readonly value="<?php echo $journal_special_invoices['product_vat']; ?>" /></td>
                    <td align="right"><input type="text" class="form-control" style="text-align: right;" id="product_price_non" name="product_price_non" onchange="update_vat_non()" value="<?php echo $journal_special_invoices['product_price_non']; ?>" /></td>
                    <td align="right"><input type="text" class="form-control" style="text-align: right;" id="product_vat_non" name="product_vat_non" readonly  value="<?php echo $journal_special_invoices['product_vat_non']; ?>" /></td>
                    <td align="right"><input type="text" class="form-control" style="text-align: right;" id="product_non"  name="product_non"  value="<?php echo $journal_special_invoices['product_non']; ?>" /></td>
                </tr> 
            </tbody> 
        </table> 

        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" onclick="set_vat(this)" >Save</button>
        </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- ****************************************************************************************************************** -->