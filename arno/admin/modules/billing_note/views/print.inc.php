
<div  style="font-size:10px;padding-left:32px;padding-top:48px;width:595px; heigth:320px;">
    <div style="font-size:22px;">ARNO (THAILAND) CO.,LTD.</div>
    <div style="font-size:18px;">บริษัท อาร์โน (ประเทศไทย) จำกัด</div>
    <div>สำนักงานใหญ่ : 2/27 อาคารบางนาคอมเพล็กซ์ ออฟฟิศทาวเวอร์ชั้น 7 ซอยบางนา-ตราด 25 ถนนบางนา-ตราด</div>
    <div>แขวงบางนาเหนือ เขตบางนา กรุงเทพฯ 10260</div>
    <div>038-989 615 Fax. 038-989 614</div>
    <div align="right" style="padding-right:80px;font-size:22px;">ใบวางบิล</div>

    <div style="display:block;padding:8;">
        ลูกค้า : <?PHP echo $billing_note['customer_code']; ?>
    </div>

    <div>
        <table width="595" heigth="320">
            <tr>
                <td style="padding:8px;">
                    <?PHP echo $billing_note['billing_note_name']; ?> <br>
                    <?PHP echo $billing_note['billing_note_address']; ?><br>
                    เลขประจำตัวผู้เสียภาษี / Tax : <?PHP echo $billing_note['billing_note_tax']; ?>
                </td>
                <td width="200">
                    <table>
                        <tr>
                            <td width="40" height="32" valign="middle" align="left">
                            No.
                            </td>
                            <td width="140" height="32" valign="middle" align="left">
                               : <?PHP echo $billing_note['billing_note_code']; ?>
                            </td>
                        </tr>

                       
                        <tr>
                            
                            <td width="40" height="32" valign="middle" align="left">
                            Date
                            </td>
                            <td width="140" height="32" valign="middle" align="left">
                            : <?PHP echo $billing_note['billing_note_date']; ?>
                            </td>

                        </tr>
                    </table>

                </td>
            </tr>
        </table>
    </div>

    <div style="height:64px;">

    </div>

    <div style="height:440px;">
        <table width="595" >
            <thead>
                <tr style="border-bottom:1px solid #000;border-top:1px solid #000;padding:16px 0px;">
                    <th style="text-align:center;">No.</th>
                    <th style="text-align:center;">Invoice Number</th>
                    <th style="text-align:center;">Date</th>
                    <th style="text-align:center;" width="100">Due Date</th>
                    <th style="text-align:center;" width="100">Amount</th>
                    <th style="text-align:center;" width="100">Paid</th>
                    <th style="text-align:center;" width="100">Balance</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                $total = 0;
                for($i=0; $i < count($billing_note_lists); $i++){
                ?>
                    <tr class="odd gradeX">
                        <td align="center">
                            <?PHP echo $i+1;?>
                        </td>
                        <td align="center">
                            <?PHP echo  $billing_note_lists[$i]['invoice_customer_code'];?>
                        </td>
                        <td align="center">
                            <?PHP echo  $billing_note_lists[$i]['billing_note_list_date'];?>
                        </td>
                        <td align="center">
                            <?PHP echo  $billing_note_lists[$i]['billing_note_list_due'];?>
                        </td>
                        <td align="right">
                            <?PHP echo  number_format($billing_note_lists[$i]['billing_note_list_net'],2);?>
                        </td>
                        <td align="right">
                            <?PHP echo  number_format($billing_note_lists[$i]['billing_note_list_paid'],2);?>
                        </td>
                        <td align="right">
                            <?PHP echo  number_format($billing_note_lists[$i]['billing_note_list_net'] - $billing_note_lists[$i]['billing_note_list_paid'],2);?>
                        </td>
                    </tr>
                <?
                    $total += $billing_note_lists[$i]['billing_note_list_net'] - $billing_note_lists[$i]['billing_note_list_paid'];
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
            

        </tr>
            
        <tr class="odd gradeX" style="border-bottom:1px solid #000;border-top:1px solid #000;padding:8px 0px;">
            <td colspan="3" >
                (<?PHP echo $billing_note['billing_note_net_thai'];?>)
            </td>
            <td colspan="2" align="left" style="vertical-align: middle;">
                Total
            </td>
            <td style="text-align: right;">
                <?PHP echo number_format($total,2) ;?>
            </td>
            
        </tr>

        <tr class="odd gradeX" style="padding-top:32px;">
            <td colspan="4" >
            <br>
            <br>
                <div><b>Remark</b></div>
                <div>วบ.mail 5-10 +recpt ที่พระราม2, TR15</div>
                <div>ชื่อผู้รับวางบิล   ______________________</div>
                <div>วันที่รับ __/__/____</div><br>
                <div><b>จ่ายโดย</b> </div>
                <div style="padding-left : 40px;">|_| การโอนเงิน วันที่ _______________</div>
                <div style="padding-left : 40px;">|_| เช็ค วันที่รับเช็ค _______________</div>
            </td>
            <td colspan="3" align="left" style="vertical-align: middle;">
            <br><br>
            <div>บริษัท อาร์โน (ประเทศไทย) จำกัด</div>
            <div>ชื่อผู้วางบิล   ______________________</div>
            </td>
                       
        </tr>
        
    </table>   

</div>
                