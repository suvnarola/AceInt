<?php

/**
 * Created by Narola.
 * User: narola
 * Date: 12/10/2018
 */
$to_email = 'sva@narola.email';
$subject = 'Testing PHP Mail';
$message = 'This mail is sent using the PHP mail function';
$headers = 'From: noreply @ company . com';
if (mail($to_email, $subject, $message, $headers)) {
    echo "Message accepted";
} else {
    echo "Error: Message not accepted";
}


$pdf = pdf_new();
echo '<br>1';
pdf_begin_document($pdf);
echo '<br>2';
pdf_set_info($pdf, "Author", "Paul Adams");
echo '<br>3';
pdf_begin_page($pdf, (72 * 8.5), (72 * 11));
echo '<br>4';
$font = pdf_findfont($pdf, "Times-Roman", "host", 0);
echo '<br>5';
pdf_setfont($pdf, $font, 16);
echo '<br>6';
pdf_set_text_pos($pdf, 72, 720);
echo '<br>7';
pdf_show($pdf, "My First PDF Document");
echo '<br>8';
pdf_end_page($pdf);
echo '<br>9';
pdf_end_document($pdf);
echo '<br>10';
$document = pdf_get_buffer($pdf);
echo '<br>11';
$length = strlen($document);
echo '<br>12';
$filename = "myfirstpdf.pdf";
echo '<br>13';
header("Content-Type:application/pdf");
header("Content-Length:" . $length);
header("Content-Disposition:inline; filename=" . $filename);
 readfile($document);
echo '<br>14';
echo($document);
echo '<br>15';
unset($document);
pdf_delete($pdf);
echo '<br>1';



 //close it up
//  pdf_close($pdf);
