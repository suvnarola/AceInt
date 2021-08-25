<?

include("includes/modules/db.php");

if($_REQUEST['changearea'])  {

  $SQL = new dbCreateSQL();

  $SQL->add_table("tbl_area_physical");

  $SQL->add_item("AreaName", encode_text2($_REQUEST['name']));
  $SQL->add_item("RegionalID", encode_text2($_REQUEST['state']));
  $SQL->add_item("CID", "12");

  dbWrite($SQL->get_sql_insert());
}
?>

<html>

<head>
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<title>New Page 1</title>
</head>

<body>
<form method="post" action="body.php?page=update_aa" name="changearea">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>Residential Address:</b></td>
    <td bgcolor="#FFFFFF" width="70%"><input type="text" name="name" size="30" value="<?= $row['r_address'] ?>"></td>
  </tr>
  <tr>
    <td align="right" valign="middle" class="Heading2" width="30%"><b>State:</b></td>
     <td bgcolor="#FFFFFF" align="left">
           <?

            //$sql_query = dbRead("select * from tbl_area_states where CID = 12 order by StateName");
            //form_select('state',$sql_query,'StateName','FieldID',$row['state']);

            $sql_query = dbRead("select * from tbl_area_regional where CID = 12 order by RegionalName");
            form_select('state',$sql_query,'RegionalName','FieldID',$row['state']);
           ?>
     </td>
  </tr>
  <tr>
    <td align="right" valign="top" class="Heading2" width="30%">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF" width="70%"><input type="submit" value="Change Area" name="changearea" style="font-family: Verdana; font-size: 8pt">
      <br><br></td>
  </tr>
</table>
</form>
</body>

</html>
