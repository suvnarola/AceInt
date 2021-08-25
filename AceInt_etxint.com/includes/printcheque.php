<? 

 include("global.php");
 include("modules/class.chequeprint.php");

if($_REQUEST['new'])  {

  $Cheque = new ChequePrint($_SESSION['Country']['cheque_pages']);
  
  $Cheque->SetType("2", $_SESSION['Country']['countryID']);
  $Cheque->GenerateCheques();
  
  $Cheque->Complete("1");

} elseif($_REQUEST['inter'])  {

  $Cheque = new ChequePrint(2);
  
  $Cheque->SetType("1", $_SESSION['Country']['countryID'], $_REQUEST['memid']);
  $Cheque->GenerateCheques();
  
  $Cheque->Complete("1");

} else {

  $Cheque = new ChequePrint($_SESSION['Country']['cheque_pages']);
  
  $Cheque->SetType("1", $_SESSION['Country']['countryID'], $_REQUEST['memid']);
  $Cheque->GenerateCheques();
  
  $Cheque->Complete("1");

}

?>