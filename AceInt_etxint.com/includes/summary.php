<?
if(!checkmodule("Summary")) {

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

add_kpi("60", "0");

$query3 = dbRead("select count(memid) as count from members");
$query = mysql_db_query($db, "select status, count(status) as count from members where CID='".$_SESSION['User']['CID']."' group by status", $linkid);
$query2 = mysql_db_query($db, "select count(*) as nl from members left outer join mem_categories on (members.memid = mem_categories.memid) where members.CID='".$_SESSION['User']['CID']."' and (mem_categories.category = NULL or mem_categories.category = 0) ", $linkid);

$row2=mysql_fetch_assoc($query2);
$row3 = mysql_fetch_assoc($query3);

while($row=mysql_fetch_array($query)) {

if($row[status] == 0) {
 $members[active]=$row[count];
}
if($row[status] == 1) {
 $members[deactive]=$row[count];
}
if($row[status] == 2) {
 $members[contractor]=$row[count];
}
if($row[status] == 3) {
 $members[staff]=$row[count];
}
if($row[status] == 4) {
 $members[sponsorships]=$row[count];
}
if($row[status] == 5) {
 $members[suspended]=$row[count];
}
if($row[status] == 6) {
 $members[suspendedlocked]=$row[count];
}

}

$members[total]=array_sum($members);
$members[notlisted]=$row2[nl];

?>

<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>

<body>

<table border="0" cellpadding="1" cellspacing="0" width="610">
<tr>
<td class="Border">
<table border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="AutoNumber1">
  <tr>
    <td width="100%" colspan="4" align="center" class="Heading2"><?= get_page_data("1") ?></tr>
  <tr>
    <td width="25%" class="Heading2" align="right"><?= get_page_data("2") ?>:<td width="25%" bgcolor="#FFFFFF"><?= number_format($members[total]) ?>&nbsp;(<?= $_SESSION['Country']['name'] ?>)<td width="25%" class="Heading2" align="right">
    Active Members:<td width="25%" bgcolor="#FFFFFF"><?= number_format($members[active]) ?></tr>
  <tr>
    <td width="25%" class="Heading2" align="right"><?= get_page_data("2") ?>:<td width="25%" bgcolor="#FFFFFF"><?= number_format($row3[count]) ?>&nbsp;(World)<td width="25%" class="Heading2" align="right">
    Deactive Members:<td width="25%" bgcolor="#FFFFFF"><?= number_format($members[deactive]) ?></tr>
  <tr>
    <td width="25%" class="Heading2" align="right"><?= get_page_data("4") ?>:<td width="25%" bgcolor="#FFFFFF"><?= number_format($members[notlisted]) ?><td width="25%" class="Heading2" align="right">
    Contractors:<td width="25%" bgcolor="#FFFFFF"><?= number_format($members[contractor]) ?></tr>
  <tr>
    <td width="25%" class="Heading2" align="right">&nbsp;<td width="25%" class="Heading2">
    &nbsp;<td width="25%" align="right" class="Heading2">Sponsorships:<td width="25%" bgcolor="#FFFFFF"><?= number_format($members[sponsorships]) ?></tr>
  </tr>
  <tr>
    <td width="25%" class="Heading2">&nbsp;<td width="25%" class="Heading2">&nbsp;<td width="25%" align="right" class="Heading2">
    Staff Accounts:<td width="25%" bgcolor="#FFFFFF"><?= number_format($members[staff]) ?></tr>
  </tr>
  <tr>
    <td width="25%" class="Heading2">&nbsp;<td width="25%" class="Heading2">&nbsp;<td width="25%" align="right" class="Heading2">
    <span lang="en-us">Suspended Accounts</span>:<td width="25%" bgcolor="#FFFFFF"><?= number_format($members[suspended]) ?></tr>
  <tr>
    <td width="25%" class="Heading2">&nbsp;<td width="25%" class="Heading2">&nbsp;<td width="25%" align="right" class="Heading2">
    <span lang="en-us">Suspended Locked Accounts</span>:<td width="25%" bgcolor="#FFFFFF"><?= number_format($members[suspendedlocked]) ?></tr>
  </table>
</td>
</tr>
</table>

</body>

</html>
