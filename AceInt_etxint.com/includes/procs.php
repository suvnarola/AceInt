<?

if(!checkmodule("Downloads")) {

?>

<table width="601" border="0" cellpadding="1" cellspacing="0">
 <tr>
  <td class="Border">
   <table width="100%" border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td width="100%" align="center" class="Heading2"><?= get_word("81") ?>.</td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
die;
}

?>
<html><head>
<title></title>
<script>
 function new_window3(URL) {
  var sendmsg ="toolbar=0,location=0,directories=0,menubar=0,status=1,resizable=0,scrollbars=1,target=_blank,width=560,height=450";
  selectedURL = URL;
  remotecontrol=window.open(selectedURL, "Procedure", sendmsg);
  remotecontrol.focus();
 }
</script>
<meta name="save" content="history" />

<style type="text/css"><!--
.save{
   behavior:url(#default#savehistory);}
a:link{
	color: #000000;
    font-weight:normal;
   	text-decoration: none}
a:active{
	color: #000000;
    font-weight:normal;
	text-decoration: none}
a:visited{
	color: #000000;
    font-weight:normal;
	text-decoration: none}
a:hover{
	color: #000000;
    font-weight:normal;
	text-decoration: underline}



a.dsphead{
   text-decoration:none;
   margin-left:1.5em;}
a.dsphead span.dspchar{
   font-family:monospace;
   font-weight:normal;
   margin-top:0;
   margin-bottom:0;}
.dspcont{
   display:none;
   padding-left: 4em;
   }
.dspcont2{
   display:none;
   padding-left:4em;
   padding-top:10;
   padding-bottom:20;}
h1
  {
  font-family: Verdana;
  font-size: 12px;
  text-decoration : none;
  margin-top:0;
  margin-bottom:0;
  }
h2
  {
  color: #0000ff;
  font-family: Verdana;
  font-size: 12px;
  text-decoration : none;
  margin-top:0;
  margin-bottom:0;
  }

//--></style>



<script type="text/javascript"><!--
function dsp(loc){
   if(document.getElementById){
      var foc=loc.firstChild;
      foc=loc.firstChild.innerHTML?
         loc.firstChild:
         loc.firstChild.nextSibling;
      foc.innerHTML=foc.innerHTML=='+'?'-':'+';
      foc=loc.parentNode.nextSibling.style?
         loc.parentNode.nextSibling:
         loc.parentNode.nextSibling.nextSibling;
      foc.style.display=foc.style.display=='block'?'none':'block';}}

if(!document.getElementById)
   document.write('<style type="text/css"><!--\n'+
      '.dspcont{display:block;}\n'+
      '//--></style>');
//--></script>

<noscript>
<style type="text/css"><!--
.dspcont{display:block;}
//--></style></noscript>

</head>
<body>
<!-- <form method="POST" action="body.php?page=administration&ID=<?= $_GET[ID] ?>&tab=<?= $_GET[tab] ?>"> -->

<?

// Some Setup.

 $tabarray = array(get_page_data("1"),get_page_data("2"),"Admin Updates","Help System");

// Do Tabs if we need to.

 displaytabs($tabarray);

if($_GET[tab] == "tab1") {

  procedures();

} elseif($_GET[tab] == "tab2") {

  manuals();

} elseif($_GET[tab] == "tab3") {

  include("/home/etxint/admin.etxint.com/adminup/admin.updates.php");

} elseif($_GET[tab] == "tab4") {
?>
  <script language="javascript" type="text/javascript">
   window.open('/adminup/helpsystem/','helpsystem');
   window.location = '<?= $_SERVER['HTTP_REFERER']; ?>';
  </script>
<?
}




?>

<!-- </form> -->
</body>
</html>
<?

function procedures() {

?>

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2">Business Management System</td>
	</tr>
	<tr>
	<td align="center" bgcolor="FFFFFF"><a href="includes/procedures.php?list=1" class="nav">Download PDF Version</a></td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
<div class="save">
<?
  if($_SESSION['User']['CID'] == 8) {
   $GET_CID = 8;
  } else {
   $GET_CID = 1;
  }

  $query = dbRead("SELECT * from tbl_procedure, tbl_proc_code where (tbl_procedure.proc_code = tbl_proc_code.Proc_Code) and tbl_procedure.CID = ".$GET_CID." and tbl_proc_code.CID = ".$GET_CID." Group By tbl_procedure.proc_code order by tbl_procedure.proc_code");
  while($row = mysql_fetch_assoc($query)) {
   if(checkmodule($row['proc_access']))  {
?>
<h1><a href="javascript:void(0)" class="dsphead" onclick="dsp(this)"><span class="dspchar">+</span> <?= get_all_added_characters($row['Proc_Title']) ?>  (<?= get_all_added_characters($row['proc_code']) ?>)</a></h1>
   <div class="dspcont">
   <?
    $query2 = dbRead("SELECT * from tbl_procedure where proc_code = '".$row['proc_code']."' and CID = '".$GET_CID."' order by proc_no");
    while($row2 = mysql_fetch_assoc($query2)) {
   ?>
      <h2><a href="javascript:void(0)" class="dsphead2" onclick="dsp(this)">
         <span class="dsphead">+</span> <?= get_all_added_characters($row['proc_code']) ?> - <?= get_all_added_characters($row2['proc_no']) ?>: <?= get_all_added_characters($row2['proc_name']) ?></a></h2>
         <div class="dspcont2"><a href="javascript:new_window3('body.php?page=proc_html&id=<?= $row2['fieldid'] ?>');" class="nav"><font size="1"><?= get_all_added_characters($row2['proc_purpose']) ?></font></a></div>
  <?}?>
   </div>
<? }
  }?>
</div>
	    </td>
	</tr>
</table>
</td>
</tr>
</table>

<?

}

function manuals() {

?>

<table border="0" cellpadding="1" cellspacing="1" width="620">
<tr>
<td class="Border">
<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
	<tr>
		<td align="center" class="Heading2"><?= get_page_data("3") ?></td>
	</tr>
	<tr>
	    <td bgcolor="#FFFFFF">
	    <?= get_word("140") ?>.
	    <ul>
          <li><a href="downloads/manuals/get_publication.php?file=intranet&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Guide to Using the Intranet</a></li>
          <li><a href="downloads/manuals/get_publication.php?file=emailsystem&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Guide to Creating Spotlight in Email System</a></li>
          <li><a href="downloads/manuals/get_publication.php?file=csupport&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Member Support Manual</a></li>
          <li><a href="downloads/manuals/get_publication.php?file=licensee&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Agents Manual</a></li>
          <li><a href="downloads/manuals/get_publication.php?file=sales&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=zip" class="nav">Sales Presentation</a></li>

		<?

			if($_SESSION['Country']['countryID'] == 6) {

				?>
		          <li><a href="downloads/manuals/get_publication.php?file=sales&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=doc" class="nav">Sales Presentation - MS Word</a></li>
				<?

			}

		?>
          <li><a href="downloads/manuals/get_publication.php?file=sales_training&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Sales Training Manual</a></li>
          <li><a href="downloads/manuals/get_publication.php?file=mem_manual&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Members Manual</a></li>
          <li><a href="downloads/manuals/get_publication.php?file=intro&code=<?= $_SESSION['Country']['countrycode'] ?>&ext=pdf" class="nav">Introduction to Empire</a></li>
	    </ul>
	    </td>
	</tr>
</table>
</td>
</tr>
</table>

<?

}

?>