<?



if($_REQUEST[contactpdf]) {

include("global.php");
cont();

}


function cont($type = false)  {

global $pdf;



if($type)  {

 foreach($_SESSION['Directory']['state'] as $value) {

   $querystate = dbRead("SELECT * from tbl_area_states where FieldID = ".$value."");
   $rowstate = mysql_fetch_array($querystate);

   if($rowstate) {
    $ss = $ss."'".$rowstate['StateName']."',";
   }
 }

 $ss = substr($ss, 0, strlen($ss)-1);

 if($_SESSION['Directory']['state'])  {
   //$statesql = " AND area.state IN (".comma_seperate($_SESSION['Directory']['state']).")";
   $statesql = " AND area.state IN ($ss)";
 }  else  {
   $statesql = "";
 }

$query = dbRead("SELECT country.name, area.state, area.place, area.r_address, area.p_address, area.state, area.tradeq, area.phone, area.email, area.fax, area.mobile FROM country, area WHERE area.CID = country.countryID AND area.display = 'Y'$statesql ORDER BY country.name, area.state, area.place");
//$query = dbRead("SELECT country.name, area.state, area.place, area.r_address, area.p_address, area.state, area.postcode, area.tradeq, area.phone, area.email, area.fax, area.mobile FROM country, area WHERE area.CID = country.countryID AND area.display = 'Y' and country.countryID = '1'$statesql ORDER BY country.name, area.location, area.place");

} else  {

if($_REQUEST['countryID'])  {
  $searchCID = $_REQUEST['countryID'];
} else {
  $searchCID = "%";
}

$query = dbRead("SELECT country.name,area.state, area.place, area.r_address, area.p_address, area.state, area.tradeq, area.phone, area.email, area.fax, area.mobile FROM country, area WHERE area.CID = country.countryID AND area.display = 'Y' and country.Display = 'Yes' and country.countryID like '".$searchCID."' ORDER BY country.name, area.state, area.place");
//$query = dbRead("SELECT country.name, area.location, area.place, area.street, area.suburb, area.state, area.postcode, area.tradeq, area.phone, area.email, area.fax, area.mobile FROM country, area WHERE area.CID = country.countryID AND area.display = 'Y' and country.countryID like '".$searchCID."' ORDER BY country.name, area.location, area.place");

}

$dirrow = array();

while($row = mysql_fetch_array($query)) {

 $big_array[] = $row;

}

if(!$type) {

//Create & Open PDF-Object this is before the loop
$pdf = pdf_new();
//pdf_set_parameter($pdf, "license", "L500102-010000-105258-97D9C0");
pdf_set_parameter($pdf, "license", "X600605-009100-45432E-D1E2B4");

pdf_open_file($pdf, '');
pdf_set_info($pdf, "Author","RDI Host");
pdf_set_info($pdf, "Title","Invoice 1");
pdf_set_info($pdf, "Creator", "Antony Puckey");
pdf_set_info($pdf, "Subject", "Hosting Invoice");
pdf_set_value($pdf, compress, 9);
pdf_begin_page($pdf, 595, 842);
pdf_set_parameter($pdf, "resourcefile", "/home/etxint/pdf_fonts/pdflib.upr");
pdf_set_parameter($pdf, "textformat", "utf8");

}

$font = pdf_findfont($pdf, "Verdana", "winansi", 0);
$fontbold = pdf_findfont($pdf, "VerdanaBold", "winansi", 0);

$counter3 = 0;
$offset3 = 0;
$page = 1;
$index=array();

    $data_structure = Array();
    for($a = 0; $a < count($big_array); $a++)
    {
        $row = $big_array[$a];
        if(!is_array($data_structure[$row[0]][$row[1]][$row[2]]))
        {
            $data_structure[$row[0]][$row[1]][$row[2]] = Array();
            array_push($data_structure[$row[0]][$row[1]][$row[2]],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13],$row[14],$row[15],$row[16]);
        }
        else
        {
            array_push($data_structure[$row[0]][$row[1]][$row[2]],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13],$row[14],$row[15],$row[16]);
        }
    }

    foreach(array_keys($data_structure) as $key_level1)
    {

	#$index[] =
	array_push($index, $key_level1, $page);

	$offset3 = $counter3;

	//3 top lines in boxes
	pdf_setlinewidth($pdf, 2);
	pdf_moveto($pdf, 10, 810-$offset3);
	pdf_lineto($pdf, 250, 810-$offset3);
	pdf_stroke($pdf);

  	pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
 	pdf_setfont($pdf, $font, 12);
 	pdf_set_text_pos($pdf, 10, 830-$offset3);
 	pdf_continue_text($pdf, $key_level1);
 	pdf_set_text_pos($pdf, 12, 810-$offset3);
 	pdf_continue_text($pdf, "Head Office");

    $cquery = dbRead("SELECT * from country where name = '".$key_level1."'");
 	$crow = mysql_fetch_array($cquery);

  	pdf_setfont($pdf, $fontbold, 10);
 	pdf_set_text_pos($pdf, 20, 795-$offset3);
 	pdf_continue_text($pdf, $crow['company']);

	pdf_setfont($pdf, $font, 8);
 	pdf_set_text_pos($pdf, 200, 795-$offset3);
 	pdf_continue_text($pdf, $crow['address1']);

    if($crow['address1'] != $crow['address2']) {
 	 pdf_set_text_pos($pdf, 200, 785-$offset3);
 	 pdf_continue_text($pdf, $crow['address2']);
 	}

 	pdf_set_text_pos($pdf, 360, 785-$offset3);
 	pdf_continue_text($pdf, "Email: ".$crow['email']);

	pdf_setfont($pdf, $font, 8);
 	pdf_set_text_pos($pdf, 65, 775-$offset3);
 	pdf_continue_text($pdf, "Tel: ".$crow['phone']);

	pdf_setfont($pdf, $font, 8);
 	pdf_set_text_pos($pdf, 220, 775-$offset3);
 	pdf_continue_text($pdf, "Fax: ".$crow['fax']);

	pdf_setfont($pdf, $font, 8);
 	pdf_set_text_pos($pdf, 360, 775-$offset3);
 	pdf_continue_text($pdf, "Mobile: ".$crow['mobile']);

	//3 top lines in boxes
	pdf_setlinewidth($pdf, 1);
	pdf_moveto($pdf, 20, 795-$offset3);
	pdf_lineto($pdf, 585, 795-$offset3);
	pdf_stroke($pdf);

    $counter3 = $counter3 + 40;

        foreach(array_keys($data_structure[$key_level1]) as $key_level2)
        {

		$offset3 = $counter3;

  		pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
 		pdf_setfont($pdf, $font, 12);
 	    pdf_set_text_pos($pdf, 12, 810-$offset3);
 	    pdf_continue_text($pdf, $key_level2);

            foreach(array_keys($data_structure[$key_level1][$key_level2]) as $key_level3)
            {
			$offset3 = $counter3;


			//3 top lines in boxes
			pdf_setlinewidth($pdf, 1);
			pdf_moveto($pdf, 20, 795-$offset3);
			pdf_lineto($pdf, 585, 795-$offset3);
			pdf_stroke($pdf);

  			pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  			pdf_setfont($pdf, $fontbold, 10);
 			pdf_set_text_pos($pdf, 20, 795-$offset3);
 			pdf_continue_text($pdf, $key_level3);

                $counter=0;
                foreach($data_structure[$key_level1][$key_level2][$key_level3] as $data)
                {


					$offset3 = $counter3;

                    if($counter == 0) {
                      $str=$data;
 					  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
					  pdf_setfont($pdf, $font, 8);
 					  pdf_set_text_pos($pdf, 200, 795-$offset3);
 					  pdf_continue_text($pdf, "$str");

                    } elseif($counter == 1) {
                      $sta=$data;
					  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
					  pdf_setfont($pdf, $font, 8);
 					  pdf_set_text_pos($pdf, 200, 785-$offset3);
 					  //pdf_continue_text($pdf, "$str $sta $pos $data ");
 					  if($str != $sta) {
 					    pdf_continue_text($pdf, "$sta");
                      }
                    } elseif($counter == 2) {
                      $pos=$data;


					  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
					  pdf_setfont($pdf, $font, 8);
 					  pdf_set_text_pos($pdf, 200, 795-$offset3);
 					  //pdf_continue_text($pdf, "$str $sta $pos $data ");
 					  //pdf_continue_text($pdf, "$str $sta $pos ");


                    } elseif($counter == 3) {
                      $dataid=$data;

                 	  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
					  pdf_setfont($pdf, $font, 10);
 					  pdf_set_text_pos($pdf, 50, 785-$offset3);
 					  pdf_continue_text($pdf, $dataid);

                    } elseif($counter == 4) {
                      $dataid=$data;

                 	  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
					  pdf_setfont($pdf, $font, 8);
 					  //pdf_set_text_pos($pdf, 775, 730-$offset3);
 				 	  pdf_set_text_pos($pdf, 65, 775-$offset3);
 					  pdf_continue_text($pdf, "Tel: ".$dataid);


                    } elseif($counter == 5) {
                      $dataid=$data;

                 	  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
					  pdf_setfont($pdf, $font, 8);
 					  pdf_set_text_pos($pdf, 360, 785-$offset3);
 					  pdf_continue_text($pdf, "Email: ".$dataid);

                    } elseif($counter == 6) {
                      $dataid=$data;

                 	  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
					  pdf_setfont($pdf, $font, 8);
 					  pdf_set_text_pos($pdf, 220, 775-$offset3);
 					  pdf_continue_text($pdf, "Fax: ".$dataid);


                    } elseif($counter == 7) {
                      $dataid=$data;

                 	  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
					  pdf_setfont($pdf, $font, 8);
 					  pdf_set_text_pos($pdf, 360, 775-$offset3);
 					  pdf_continue_text($pdf, "Mobile: ".$dataid);

                    }
                    $counter++;

                }
			$counter3 = $counter3 + 30;

			if($counter3 > 710) {

  			 //3 top lines in boxes
			 pdf_setlinewidth($pdf, 0.5);
			 pdf_moveto($pdf, 10, 34);
			 pdf_lineto($pdf, 585, 34);
			 pdf_stroke($pdf);

			if(!$type)  {
             pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
			 pdf_setfont($pdf, $font, 8);
 			 pdf_set_text_pos($pdf, 550, 30);
 		     pdf_continue_text($pdf, "Page $page");
			}
 			 $page = $page + 1;

			 pdf_end_page($pdf);
			 pdf_begin_page($pdf, 595, 842);
		 	$counter3 = 0;
		 	$offset3 =0;
			}

            }
		$counter3 = $counter3 + 20;

		if($counter3 > 710) {

  		 //3 top lines in boxes
		 pdf_setlinewidth($pdf, 0.5);
		 pdf_moveto($pdf, 10, 34);
		 pdf_lineto($pdf, 585, 34);
		 pdf_stroke($pdf);

		if(!$type)  {
         pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
		 pdf_setfont($pdf, $font, 8);
 		 pdf_set_text_pos($pdf, 550, 30);
 		 pdf_continue_text($pdf, "Page $page");
		}
 		 $page = $page + 1;

		 pdf_end_page($pdf);
		 pdf_begin_page($pdf, 595, 842);
		 $counter3 = 0;
		 $offset3 =0;
		}

        }
	$counter3 = $counter3 + 35;

	if($counter3 > 710) {


  	 //3 top lines in boxes
	 pdf_setlinewidth($pdf, 0.5);
	 pdf_moveto($pdf, 10, 34);
	 pdf_lineto($pdf, 585, 34);
	 pdf_stroke($pdf);

	if(!$type)  {
     pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
	 pdf_setfont($pdf, $font, 8);
 	 pdf_set_text_pos($pdf, 550, 30);
 	 pdf_continue_text($pdf, "Page $page");
	}
 	 $page = $page + 1;

	 pdf_end_page($pdf);
	 pdf_begin_page($pdf, 595, 842);
	 $counter3 = 0;
	 $offset3 =0;
	}

    }

//3 top lines in boxes
pdf_setlinewidth($pdf, 0.5);
pdf_moveto($pdf, 10, 34);
pdf_lineto($pdf, 585, 34);
pdf_stroke($pdf);

if(!$type)  {
pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
pdf_setfont($pdf, $font, 8);
pdf_set_text_pos($pdf, 550, 30);
pdf_continue_text($pdf, "Page $page");
}



$counter6=0;
$counter7=0;
$offset4=0;
$offset7=0;

if(!$type)  {
pdf_end_page($pdf);
pdf_begin_page($pdf, 595, 842);

foreach($index as $content => $value)  {

 if($counter6 == 0) {

  $indexname = $value;

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $font, 8);
  pdf_set_text_pos($pdf, 60+$offset7, 778-$offset4);
  pdf_continue_text($pdf, $indexname);

  $counter6++;

 } elseif($counter6 == 1) {

  $indexname=$value;

  pdf_setcolor($pdf, "fill", "rgb", 0, 0, 0, 0);
  pdf_setfont($pdf, $font, 8);
  pdf_set_text_pos($pdf, 225+$offset7, 778-$offset4);
  pdf_continue_text($pdf, $indexname);

  $counter6=0;
  $offset4=$offset4+10;

 }

$counter7=$counter7+1;

if($counter7 == 140) {
 $offset4=0;
 $offset7=$offset7+280;

} elseif($counter7 == 280) {
 pdf_end_page($pdf);
 pdf_begin_page($pdf, 595, 842);

 $offset4=0;
 $offset7=0;
 $counter7=0;

}

}


//close it up
pdf_end_page($pdf);
pdf_close($pdf);
$buffer = PDF_get_buffer($pdf);
pdf_delete($pdf);

send_to_browser($buffer,"application/pdf","Contacts.pdf","attachment");

}

}
?>