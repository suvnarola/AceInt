<?
 $NoSession = true;
 include("/home/etxint/admin.etxint.com/includes/global.php");


 //echo get_non_included_accounts(1,true,false,false,true);

 echo get_non_included_accounts(1);
 //echo $rand2;

    $row4 = mysql_fetch_array(dbRead("select * from tbl_folder where userID = 4","etxint_email_system"));

print $row4[folder];

?>
