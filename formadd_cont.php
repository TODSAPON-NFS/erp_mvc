  <? session_start();
  	require("class.phpmailer.php");
	$mail = new PHPMailer();
			include "../admin/config/connect/connect.php";
			$d1=date("d");
			$d2=date("m");
			$d3=date("Y");
			$d4=date("H");
			$d5=date("i");
			$d6=date("s");
			$date22="$d1-$d2-$d3 $d4:$d5:$d6";
			$MailFrom2 = 'Dinas-olivenoel Support';
			$MailTos1="info@dinas-olivenoel.ch";
			$MailTos2="dinas_olivenoel@yahoo.de";
			$setCaptcha_fromProgrammer=$_SESSION["detect_farang"];
			$inputCaptcha_fromUser=$_POST['inputEmail4'];
			if($setCaptcha_fromProgrammer==$inputCaptcha_fromUser){
						
						$MailMessage = $_POST['inputEmail3'];
						if($MailMessage==""){  echo "<script>alert(\"please insert  your Contact  detail\"); </script>"; }
						else
						{
												$MailTo2 = $_POST['inputEmail1'];
												
												$MailFrom2 = "info@dinas-olivenoel.ch";
												
												$Mailphone = $_POST['inputEmail2'];
												$MailSubject ="Dinas-olivenoel From : ".$MailTo2;
												$MailMessage = $_POST['inputEmail3'];
												$mailDate=$date22;
												$detail11="<div style='background-color:#ffffff; border:1px #ededed solid; padding:15px; width:100%; min-width:300px; max-width:650px; font-family:Verdana, Geneva, sans-serif; font-size:14px;'>
<div style='padding-bottom:7px; border-bottom:1px #ededed solid;'>
<table width='100%' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td width='50%'><a href='http://www.dinas-olivenoel.ch' target='_blank'><img src='http://www.dinas-olivenoel.ch/dinas_logo_mail.jpg' alt='dinas-olivenoel.ch' border='0' width='250'></a></td>
    </tr>
    <tr>
    <td width='50%'><div style='font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#333; font-weight:bold; padding-top:23px;' align='left'>Email From Contact</div>
    <div style='font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#333; font-weight:bold; padding-top:8px;' align='left'>Date Send : <font color='#FF0000'>$date22</font></div>
    </td>
  </tr>
</table>

</div>
<div style='font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#333; font-weight:normal; padding-top:20px;' align='left'>Contact Name : <b>$MailTo2</b></div>
<div style='font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#333; font-weight:normal; padding-top:8px;' align='left'>Email : <b>$Mailphone</b></div>

<div style='font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#333; font-weight:bold; padding-top:35px;' align='left'>Detail</div>
<div style='font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#333; font-weight:normal; padding-top:8px; padding-bottom:35px;' align='left'>$MailMessage </div>
<div style='font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#333; font-weight:bold; padding-top:35px;' align='left'>
	Dinas-olivenoel.ch
</div>
<div style='font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#333; font-weight:normal; padding-top:8px; padding-bottom:0px;' align='left'>
	DINAS Olivenöl Koroni-Zürich Ueberlandstrasse 240 8600 Dübendorf
</div>
<div style='font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#333; font-weight:normal; padding-top:8px; padding-bottom:0px;' align='left'>
	<b>Phone :</b> 0041 78 758 92 64
</div>
<div style='font-family:Verdana, Geneva, sans-serif; font-size:13px; color:#333; font-weight:normal; padding-top:8px; padding-bottom:0px;' align='left'>
	<b>Email :</b> dinas_olivenoel@yahoo.de
</div>
<div></div>
</div>";
			
		$MailMessage=iconv('utf8','iso-8859-1',$MailMessage);
												
		$PointSlide=mysql_fetch_array(mysql_query("select * from email_list order by mail_point desc limit 0,1"));
		$PointSlides=$PointSlide['mail_point'];
		if($PointSlides==0 || $PointSlides==""){$PointSlides=1;}
		else {$PointSlides=$PointSlides+1;}
		
		$sql="insert into email_list VALUES('','$PointSlides','Email From Dinas Form','$MailTo2','$Mailphone','$MailMessage','0','0','$date22')";
		echo $sql;
		$qry=mysql_query($sql);
				if($qry)
				{
						$textMailAll="$detail11<br><br><br>" ;;
						$body = $textMailAll;
						$mail->CharSet = "iso-8859-1";
						$mail->IsSMTP();
						$mail->SMTPDebug = 0;
						$mail->SMTPAuth = true;
						$mail->Host = "smtp.dinas-olivenoel.ch"; // SMTP server
						$mail->Port = 587; 
						$mail->Username = "info@dinas-olivenoel.ch"; // account SMTP
						$mail->Password = "Dinas123456@"; //  SMTP
						
						$mail->SetFrom("info@dinas-olivenoel.ch", "Dinas-olivenoel");
						$mail->AddReplyTo("info@dinas-olivenoel.ch", "Dinas-olivenoel");
						$mail->Subject = "KONTAKT FORMULAR";
						
						$mail->MsgHTML($body);
						
						$mail->AddAddress("info@dinas-olivenoel.ch", "dinas-olivenoel"); //
						$mail->AddAddress("dinas_olivenoel@yahoo.de", "dinas-olivenoel"); //
						
						if(!$mail->Send()) {
    							echo "Mailer Error: " . $mail->ErrorInfo;
						} else {
							echo "<script>alert(\" Nachricht versendet \"); </script>";	
							echo "<script language='JavaScript' type='text/javascript'>window.parent.cleardata();</script>";	
						}
						
						/*$MailMessage2 ="$detail11<br><br><br>" ;
						$Headers = "MIME-Version: 1.0\r\n" ;
						$Headers .= "Content-type: text/html; charset=utf-8\r\n" ;
						$Headers .= "From: ".$MailFrom2."\r\n" ;
						$Headers .= "Reply-to: ".$MailFrom2."\r\n" ;
						$Headers .= "X-Priority: 3\r\n" ;
						$Headers .= "X-Mailer: PHP mailer\r\n" ;
						mail($MailTo, $MailSubject , $MailMessage2, $Headers);		
						mail($MailTos1, $MailSubject , $MailMessage2, $Headers);		
						mail($MailTos2, $MailSubject , $MailMessage2, $Headers);				
						echo "<script>alert(\" Nachricht versendet \"); </script>";	
						echo "<script language='JavaScript' type='text/javascript'>window.parent.cleardata();</script>";	
						///END SEND MAIL//*/
				}										
			}	
		}
		else 
		{
			echo "<script>alert(\"Please Check Captcha Code \"); </script>";	
			echo "<script language='JavaScript' type='text/javascript'>window.parent.CheckCapt();</script>";	
		}
?>
  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">