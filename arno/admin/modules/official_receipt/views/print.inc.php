
<div  style="font-size:10px;padding-left:32px;padding-top:48px;width:595px; heigth:320px;">
    <div style="font-size:22px;">ARNO (THAILAND) CO.,LTD.</div>
    <div style="font-size:18px;">บริษัท อาร์โน (ประเทศไทย) จำกัด</div>
    <div>สำนักงานใหญ่ : 2/27 อาคารบางนาคอมเพล็กซ์ ออฟฟิศทาวเวอร์ชั้น 7 ซอยบางนา-ตราด 25 ถนนบางนา-ตราด</div>
    <div>แขวงบางนาเหนือ เขตบางนา กรุงเทพฯ 10260</div>
    <div>038-989 615 Fax. 038-989 614</div>
    <div align="center" style="font-size:22px;">ใบเสร็จรับเงิน</div>
    <div align="center" style="font-size:22px;">OFFICIAL RECEIPT</div>
    <div style="display:block;padding:8;">
        Receipt by thanks from : -
        
    </div>

    <div>
        <table width="480" heigth="320">
            <tr>
                <td style="padding:8px;">
                    <?PHP echo $official_receipt['official_receipt_name']; 
                    if( (int)$official_receipt['customer_branch'] * 1 == 0){
                        echo " สำนักงานใหญ่";
                    } else {
                        echo "สาขา " . ((int)$official_receipt['customer_branch'] * 1) ;
                    }
                    ?> <br>
                    <?PHP echo $official_receipt['official_receipt_address']; ?><br>
                    เลขประจำตัวผู้เสียภาษี / Tax : <?PHP echo $official_receipt['official_receipt_tax']; ?>
                </td>
                <td width="240">
                    <table>
                        <tr>
                            <td width="100" height="32" valign="middle" align="left">
                            No.
                            </td>
                            <td width="140" height="32" valign="middle" align="left">
                               : <?PHP echo $official_receipt['official_receipt_code']; ?>
                            </td>
                        </tr>

                       
                        <tr>
                            
                            <td width="100" height="32" valign="middle" align="left">
                            Date
                            </td>
                            <td width="140" height="32" valign="middle" align="left">
                            : <?PHP echo $official_receipt['official_receipt_date']; ?>
                            </td>

                        </tr>
                        <tr>
                            
                            <td width="100" height="32" valign="middle" align="left">
                            Customer code
                            </td>
                            <td width="140" height="32" valign="middle" align="left">
                            : <?PHP echo $official_receipt['customer_code']; ?>
                            </td>

                        </tr>
                    </table>

                </td>
            </tr>
        </table>
    </div>

    <div style="height:64px;">
        Begin payment for the followings invoice : - 
    </div>

    <div style="height:325px;">
        <table width="595" >
            <thead>
                <tr style="border-bottom:1px dashed #000;border-top:1px dashed #000;">
                    <th style="text-align:center; padding:4px 0px;" >SQ.</th>
                    <th style="text-align:center; padding:4px 0px;" >INV.No</th>
                    <th style="text-align:center; padding:4px 0px;" >INV.DD.</th>
                    <th style="text-align:center; padding:4px 0px;" >DUE DD.</th>
                    <th style="text-align:center; padding:4px 0px;" >BILLING NO. </th>
                    <th style="text-align:center; padding:4px 0px;" >INV. AMOUNT</th>
                    <th style="text-align:center; padding:4px 0px;" >BAL. AMOUNT</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                $total = 0;
                for($i=0; $i < count($official_receipt_lists); $i++){
                ?>
                    <tr class="odd gradeX">
                        <td align="center">
                            <?PHP echo $i+1;?>
                        </td>
                        <td align="center">
                            <?PHP echo  $official_receipt_lists[$i]['invoice_customer_code'];?>
                        </td>
                        <td align="center">
                            <?PHP echo  $official_receipt_lists[$i]['official_receipt_list_date'];?>
                        </td>
                        <td align="center">
                            <?PHP echo  $official_receipt_lists[$i]['official_receipt_list_due'];?>
                        </td>
                        <td align="center">
                            <?PHP echo  $official_receipt_lists[$i]['billing_note_code'];?>
                        </td>
                        <td align="right">
                            <?PHP echo  number_format($official_receipt_lists[$i]['official_receipt_inv_amount'],2);?>
                        </td>
                        <td align="right">
                            <?PHP echo  number_format($official_receipt_lists[$i]['official_receipt_bal_amount'],2);?>
                        </td>
                    </tr>
                <?
                    $total += $official_receipt_lists[$i]['official_receipt_bal_amount'];
                }
                ?>
            </tbody>
        </table>
    </div>
                        
                        
    <table width="595">
        <tr>
            <td width="20">
            </td>

            <td width="280">
                
            </td>

            <td width="80" align="right"></td>
            <td width="80" align="right"></td>
            <td width="80" align="right"></td>
            <td width="80" align="right"></td>
            

        </tr>
        <tr class="odd gradeX" style="border-bottom:1px dashed #000;">
            <td colspan="6" >
                (<?PHP echo $number_2_text->convert(number_format($total,2));?>)
            </td>
        </tr>    
        <tr class="odd gradeX" >
            <td colspan="3" >
               
            </td>
            <td colspan="2" align="right" style="vertical-align: middle;">
                Total Baht
            </td>
            <td style="text-align: right;border-bottom:1px dashed #000;" >
                <?PHP echo number_format($total,2) ;?>
            </td>
            
        </tr>

        <tr class="odd gradeX" style="padding-top:32px;">
            <td colspan="4" >
            <br>
            <br>
                <table>
                    <tr>
                        <td colspan="4" style="padding:8px 0px;">Payment in form of </b></td>
                    <tr>
                    <tr>
                        <td>[ ]</td>
                        <td>CASH  no._____________________</td>
                        <td>date ___________________</td>
                        <td>Baht _________________</td>
                    </tr>
                    <tr>
                        <td>[ ]</td>
                        <td>TRANSFER no.________________</td>
                        <td>date ___________________</td>
                        <td>Baht _________________</td>
                    </tr>
                    <tr>
                        <td>[ ]</td>
                        <td>CHEQUE no.__________________</td>
                        <td>chq.dd._________________</td>
                        <td>Baht _________________</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Bank_________________________</td>
                        <td>Branch_________________</td>
                        <td>Baht _________________</td>
                    </tr>
                    <tr>
                        <td colspan="4" style="padding:8px 0px;">Deduction </b></td>
                    <tr>
                        <td>[ ]</td>
                        <td>Withholding tax (if any)</td>
                        <td></td>
                        <td>Baht _________________</td>
                    </tr>
                    <tr>
                        <td>[ ]</td>
                        <td>[ ] Bank charges (if any)</td>
                        <td></td>
                        <td>Baht _________________</td>
                    </tr>
                </table>
            </td>
            <td colspan="3" align="left" style="vertical-align: middle;">
            
            </td>
                       
        </tr>
        
    </table>  
    <div>
    For payment for cheque, please make a crossed cheque payable to "ARNO (THAILAND) CO.,LTD."
    This Official Receipt is valid only after the cheque is honoured by the bank or transferred to ARNO (THAILAND) CO.,LTD account. 
    </div>
    <br>
    <table width="100%">
        <tr>
            <td align="center"> .............................. </td>
            <td align="center"> .............................. </td>
            <td align="center"> .............................. </td>
        </tr>
        <tr>
            <td align="center"> Bill collector </td>
            <td align="center"> Date </td>
            <td align="center"> Authorized Signature </td>
        </tr>
    </table>
</div>
                