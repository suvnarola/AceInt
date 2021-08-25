<?
 include("/home/etxint/admin.etxint.com/includes/global.php");


 //$query = dbRead("select * FROM invoice, members, country WHERE (invoice.memid = members.memid) and (members.CID = country.countryID) and date = '2007-04-30' and CID = 15");
 $query = dbRead("select * FROM tbl_templates_templates WHERE fieldID in (21,22)","etxint_email_system");

 while($row = mysql_fetch_assoc($query)) {

print $row[templateData];

 }
?>