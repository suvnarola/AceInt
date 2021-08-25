<?php

ini_set("display_errors", "1");
error_reporting(E_ALL);
$pdf = pdf_new();
pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");
PDF_begin_document($pdf, '', '');
PDF_begin_page_ext($pdf, 612, 792, '');
pdf_set_info($pdf, "Author", "E Banc Trade");
pdf_set_info($pdf, "Title", "Tax Invoice");
pdf_set_info($pdf, "Creator", "E Banc Accounts");
pdf_set_info($pdf, "Subject", "Tax Invoice");
pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
pdf_set_parameter($pdf, "textformat", "utf8");


$font = PDF_load_font($pdf, "Helvetica", "auto", false);
pdf_setfont($pdf, $font, 30);
$pdfimage = PDF_load_image($pdf, "jpeg", "/home/etxint/admin.etxint.com/images/etx-bw.jpg", '');
PDF_fit_image($pdf, $pdfimage, 445, 755, '');

//pdf_setcolor($pdf, "fill", "rgb", 0, 0, 1);
pdf_setColor($pdf, "fill", "rgb", 0, 0, 1, 0.0);
pdf_setfont($pdf, $font, 18);

//pdf_show_xy($pdf, "PHP Developer's Handbook", 10, PAGE_HEIGHT - 40);
//pdf_show_xy($pdf, "Hello, World! Using PDFLib and PHP", 10, PAGE_HEIGHT - 55);


//address box
pdf_rect($pdf, 65 + 265, 650, 180, 81);
pdf_closepath_stroke($pdf);
$offset = 1;
//top and bottom thin lines
pdf_moveto($pdf, 30, 630 - $offset);
pdf_lineto($pdf, 565, 630 - $offset);
pdf_stroke($pdf);

pdf_moveto($pdf, 30, 210 - $offset);
pdf_lineto($pdf, 565, 210 - $offset);
pdf_stroke($pdf);

//lines and boxes
pdf_rect($pdf, 65, 486, 463, 128);
pdf_closepath_stroke($pdf);

pdf_rect($pdf, 280, 486, 248, 27);
pdf_closepath_stroke($pdf);

pdf_rect($pdf, 280, 461, 248, 25);
pdf_closepath_stroke($pdf);

pdf_rect($pdf, 280, 436, 248, 25);
pdf_closepath_stroke($pdf);

pdf_moveto($pdf, 100, 300 - $offset);
pdf_lineto($pdf, 505, 300 - $offset);
pdf_stroke($pdf);


PDF_end_page_ext($pdf, '');
PDF_end_document($pdf, '');
$data = pdf_get_buffer($pdf);
header('Content-type: application/pdf');
header("Content-disposition: inline; filename=output.pdf");
header("Content-length: " . strlen($data));
echo $data;

exit;
?>