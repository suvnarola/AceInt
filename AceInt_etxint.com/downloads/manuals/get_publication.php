<%

 if(is_file($_REQUEST['file']."-".$_REQUEST['code'] . "." . $_REQUEST['ext'])) {
  header("Location: " . $_REQUEST['file'] . "-" . $_REQUEST['code'] . "." . $_REQUEST['ext']);
 } else {
  if(is_file($_REQUEST['file'] . "-au." . $_REQUEST['ext'])) {
   header("Location: " . $_REQUEST['file'] . "-au." . $_REQUEST['ext']);
  } else {
   print "No Such File.";
  }
 }

%>