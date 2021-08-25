<?

/**************************************************************************************
* Class: Pager
* Author: Tsigo <tsigo@tsiris.com>
* Methods:
*         findStart
*         findPages
*         pageList
*         nextPrev
* Redistribute as you see fit.
**************************************************************************************/
 class Pager
  {
  /***********************************************************************************
   * int findStart (int limit)
   * Returns the start offset based on $_REQUEST['page'] and $limit
   ***********************************************************************************/
   function findStart($limit)
    {
     if ((!isset($_REQUEST['pageno'])) || ($_REQUEST['pageno'] == "1"))
      {
       $start = 0;
       $_REQUEST['pageno'] = 1;
      }
     else
      {
       $start = ($_REQUEST['pageno']-1) * $limit;
	   if($start < 0) {
	    $start = 0;
	   }
      }

     return $start;
    }
  /***********************************************************************************
   * int findPages (int count, int limit)
   * Returns the number of pages needed based on a count and a limit
   ***********************************************************************************/
   function findPages($count, $limit)
    {
     $pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;

     return $pages;
    }
  /***********************************************************************************
   * string pageList (int curpage, int pages)
   * Returns a list of pages in the format of "� < [pages] > �"
   ***********************************************************************************/
   function pageList($curpage, $pages)
    {

    foreach ($_REQUEST as $key => $value) {
    	if($key != "pageno") {
			$append .= urlencode($key) . "=" .urlencode($value)."&";
		}
	}

    foreach ($_POST as $key => $value) {
    	if($key != "pageno") {
			$append .= urlencode($key) . "=" .urlencode($value)."&";
		}
	}


     $page_list  = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"620\"><tr>";

     /* ************************************************************************
     Print the first and previous page links if necessary
     ************************************************************************ */

       $pos_current = $curpage-1;
       $pos_max_left = 8;
       $pos_max_right = 8;
       $pos_max_total = $pos_max_left+$pos_max_right+1;

       if($pos_current < $pos_max_left) {
       		if($pages > $pos_max_total) {
	       	   $pos_start = 1;
    	   	   $pos_end = $pos_max_total;
    	   	} else {
    	   		$pos_start = 1;
    	   		$pos_end = $pages;
    	   	}
		} elseif($pages-$pos_max_right > $pos_current) {
       	   $pos_start = $pos_current-$pos_max_left+1;
       	   $pos_end = $pos_current+($pos_max_right+1);
		} else {
			if($pages < $pos_max_total) {
			$pos_start = 1;
			$pos_end = $pages;
			} else {
           	$pos_start = $pages-($pos_max_total-1);
	       	$pos_end = $pages;
	       }

		}

       /*
       if(($curpage-1) <= 5) {
       	$pos_start = 1;
       	$pos_end = $pages;
       } elseif(($curpage-1) > ($pages-5)) {
       	$pos_start = $pages-5;
       	$pos_end = $pages;
       } else {
        $pos_start = ($curpage-1)-2;
       	$pos_end = ($curpage-1)+2;
       }

       */

	if (($curpage-1) > 0) {
       $page_list .= "<td valign=\"bottom\" width=\"80\"><font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\"><a href=\"".$_SERVER['PHP_SELF']."?".$append."pageno=1\" title=\"First Page\">&lt;&lt;</a>&nbsp;<a href=\"".$_SERVER['PHP_SELF']."?".$append."pageno=".($curpage-1)."\" title=\"Previous Page\">PREV</a></td><td nowrap class=\"paging\" align=\"center\">&nbsp;&nbsp;";
	} else {
       $page_list .= "<td valign=\"bottom\" width=\"80\"><font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">&nbsp;</td><td nowrap class=\"paging\" align=\"center\"><font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">&nbsp;&nbsp;";
	}

     /* Print the numeric page list; make the current page unlinked and bold for ($i=1; $i<=$pages; $i++)  */
     for ($i=$pos_start; $i<=$pos_end; $i++)
      {

      if($i < 10) { $page_list .= "&nbsp;"; }
       if ($i == $curpage)
        {
         $page_list .= "<font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\"><b>".$i."</b>";
        }
       else
        {
         $page_list .= "<font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\"><a href=\"".$_SERVER['PHP_SELF']."?".$append."pageno=".$i."\" title=\"Page ".$i."\">".$i."</a>";
        }
       if($i < 10) { $page_list .= "&nbsp;"; }
       $page_list .= "&nbsp;&nbsp;";
      }

     /* Print the Next and Last page links if necessary */
     if (($curpage+1) <= $pages) {
      	$page_list .= "</td><td valign=\"bottom\" align=\"right\" width=\"80\"><font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\"><a href=\"".$_SERVER['PHP_SELF']."?".$append."pageno=".($curpage+1)."\" title=\"Next Page\">NEXT</a>&nbsp;<a href=\"".$_SERVER['PHP_SELF']."?".$append."pageno=".$pages."\" title=\"Last Page\">&gt;&gt;</a> </td>";
	} else {
      	$page_list .= "</td><td valign=\"bottom\" align=\"right\" width=\"80\">&nbsp;</td>";
	}

     #if (($curpage != $pages) && ($pages != 0))
     # {
     #  $page_list .= "<a href=\"".$_SERVER['PHP_SELF']."?".$append."pageno=".$pages."\" title=\"Last Page\">�</a> ";
     # }

     $page_list .= "</tr></table>\n";

     return $page_list;
    }
  /***********************************************************************************
   * string nextPrev (int curpage, int pages)
   * Returns "Previous | Next" string for individual pagination (it's a word!)
   ***********************************************************************************/
   function nextPrev($curpage, $pages)
    {
     $next_prev  = "";

     if (($curpage-1) <= 0)
      {
       $next_prev .= "Previous";
      }
     else
      {
       $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?".$append."pageno=".($curpage-1)."\">Previous</a>";
      }

     $next_prev .= " | ";

     if (($curpage+1) > $pages)
      {
       $next_prev .= "Next";
      }
     else
      {
       $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?".$append."pageno=".($curpage+1)."\">Next</a>";
      }

     return $next_prev;
    }
  }
?>