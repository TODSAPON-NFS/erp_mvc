<div  style="font-size:10px;padding-left:32px;padding-top:48px;width:595px; heigth:320px;">
    <div style="font-size:22px;">ARNO (THAILAND) CO.,LTD.</div>
    <div style="font-size:18px;">บริษัท อาร์โน (ประเทศไทย) จำกัด</div>
    <div>สำนักงานใหญ่ : 2/27 อาคารบางนาคอมเพล็กซ์ ออฟฟิศทาวเวอร์ชั้น 7 ซอยบางนา-ตราด 25 ถนนบางนา-ตราด</div>
    <div>แขวงบางนาเหนือ เขตบางนา กรุงเทพฯ 10260</div>
    <div>038-989 615 Fax. 038-989 614</div>
    <div>เลขประจำตัวผู้เสียภาษี 0105550006519</div><br>
    <div align="right" style="padding-right:80px;font-size:22px;">ใบเพิ่มหนี้</div>
    <br>
    <div style="display:block;padding:8;">
    รหัสลูกค้า : <?PHP echo $debit_note['customer_code']; ?>
    </div>
    <div>
        <table width="595" heigth="320">
            <tr>
                <td style="padding:8px;">
                    <?PHP echo $debit_note['debit_note_name']; ?> <br>
                    <?PHP echo $debit_note['debit_note_address']; ?><br>
                    เลขประจำตัวผู้เสียภาษี / Tax : <?PHP echo $debit_note['debit_note_tax']; ?>
                </td>
                <td width="240">
                    <table>
                        <tr>
                            <td width="100" height="32" valign="middle" align="left">
                            เลขที่ใบเพิ่มหนี้
                            </td>
                            <td width="140" height="32" valign="middle" align="left">
                                <?PHP echo $debit_note['debit_note_code']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" height="32" valign="middle" align="left">
                            วันที่
                            </td>
                            <td width="140" height="32" valign="middle" align="left">
                                <?PHP echo $debit_note['debit_note_date']; ?>
                            </td>

                        </tr>

                        <tr>
                            <td width="100" height="32" valign="middle" align="left">
                            อ้างอิงใบกำกับภาษี
                            </td>

                            <td width="140" height="32" valign="middle" align="left">
                                <?PHP echo $debit_note['invoice_customer_code']; ?> <?PHP echo $debit_note['invoice_customer_date']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" height="32" valign="middle" align="left">
                            พนักงานขาย
                            </td>

                            <td width="140" height="32" valign="middle" align="left">
                            <?PHP echo $debit_note['user_name']; ?> <?PHP echo $debit_note['user_lastname']; ?>
                            </td>

                        </tr>
                    </table>

                </td>
            </tr>
        </table>
    </div>
    
    <div style="height:64px;">

    </div>
    <div>บริษัทได้เพิ่มหนี้และเดบิทบัญชีของท่านตามรายการสินค้าดังต่อไปนี้</div><bR>
    <div style="height:440px;">
        <table width="595" >
            <thead>
                <tr style="border-bottom:1px solid #000;border-top:1px solid #000;padding:16px 0px;">
                    <th width="24">No.</th>
                    <th style="text-align:center;">รหัสสินค้า </th>
                    <th style="text-align:center;">รายละเอียดสินค้า </th>
                    <th style="text-align:center;" width="90">จำนวน </th>
                    <th style="text-align:center;" width="90">หน่วยละ</th>
                    <th style="text-align:center;" width="90">จำนวนเงิน</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                for($i=0; $i < count($debit_note_lists); $i++){
                ?>
                <tr class="odd gradeX">
                    <td><?PHP echo $i+1;?></td>
                    <td>
                        <?PHP echo  $debit_note_lists[$i]['product_code'];?>
                    </td>
                    <td>
                        <span>Product name : </span><?php echo $debit_note_lists[$i]['debit_note_list_product_name']; ?><br>
                        <span>Product detail : </span><?php echo $debit_note_lists[$i]['debit_note_list_product_detail']; ?><br>
                        <span>Remark : </span><?php echo $debit_note_lists[$i]['debit_note_list_remark']; ?><br>
                    </td>
                    <td align="right"><?php echo $debit_note_lists[$i]['debit_note_list_qty']; ?></td>
                    <td align="right"><?php echo  number_format($debit_note_lists[$i]['debit_note_list_price'],2); ?></td>
                    <td align="right"><?php echo  number_format($debit_note_lists[$i]['debit_note_list_qty'] * $debit_note_lists[$i]['debit_note_list_price'],2); ?></td>
                    
                </tr>
                <?
                    $total += $debit_note_lists[$i]['debit_note_list_qty'] * $debit_note_lists[$i]['debit_note_list_price'];
                }
                ?>
            </tbody>
            
        </table>
    </div>
                        
                        
    <table width="595">
    <tfoot>

    <tr>
            <td width="20">
            </td>

            <td width="280">
                
            </td>


            <td width="80" align="right"></td>
            <td width="80" align="right"></td>
            <td width="80" align="right"></td>
            

        </tr>
                            
                            <tr class="odd gradeX">
                                <td colspan="1" rowspan="8" style="vertical-align: top;">
                                    <b>หมายเหตุ</b>
                                    <p><?PHP echo $debit_note['debit_note_remark'];?></p>
                                </td>
                                <td colspan="3" align="right" style="vertical-align: middle;">
                                    <span>รวม</span>
                                </td>
                                <td style="text-align: right;">
                                <?PHP echo number_format($total,2) ;?>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                
                                <td colspan="3" align="right" style="vertical-align: middle;">
                                    <br>
                                </td>
                                <td style="text-align: right;">
                                    
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="3" align="right" style="vertical-align: middle;">
                                    <span>มูลค่าของสินค้าหรือบริการใบกำกับภาษีเดิม</span>
                                </td>
                                <td style="text-align: right;">
                                    <?PHP echo number_format($debit_note['debit_note_total_old'],2) ;?>
                                </td>
                            </tr>
            
                            <tr class="odd gradeX">
                                <td colspan="3" align="right" style="vertical-align: middle;">
                                    <span>มูลค่าของสินค้าหรือบริการที่ถูกต้อง</span>
                                </td>
                                <td style="text-align: right;">
                                    <?PHP echo number_format($debit_note['debit_note_total_old'] + $total ,2) ;?>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                
                                <td colspan="3" align="right" style="vertical-align: middle;">
                                <br>
                                </td>
                                <td style="text-align: right;">
                                    
                                </td>
                            </tr>
            
                            <tr class="odd gradeX">
                                <td colspan="3" align="right" style="vertical-align: middle;">
                                    <span>ผลต่าง</span>
                                </td>
                                <td style="text-align: right;">
                                    <?PHP echo number_format($total,2) ;?>
                                </td>
                            </tr>
            
                            <tr class="odd gradeX">
                                <td colspan="3" align="right" style="vertical-align: middle;">
                                จำนวนภาษีมูลค่าเพิ่ม <?PHP echo number_format($vat,2);?> %
                                                                        
                                </td>
                                <td style="text-align: right;">
                                    <?PHP echo number_format(($vat/100) * $total,2) ;?>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="3" align="right" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น</span>
                                </td>
                                <td style="text-align: right;">
                                    <?PHP echo number_format(($vat/100) * $total + $total,2) ;?>
                                </td>
                            </tr>
                        </tfoot>
    </table>   



</div>
                