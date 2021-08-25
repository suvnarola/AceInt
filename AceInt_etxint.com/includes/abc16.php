<?
 include("/home/etxint/admin.etxint.com/includes/global.php");


 //$query = dbRead("select emailaddress as email from  registered_accounts_OLD, reg_acc_details_OLD where (registered_accounts_OLD.Acc_No = reg_acc_details_OLD.Acc_No) and Cash_Refund > 0 group by emailaddress","ebanc_services");
 //$query = dbRead("select * from members, tbl_members_email where (members.memid = tbl_members_email.Acc_No) and status != 1 and type = 3 and fiftyclub > 0 and paymenttype = 20 and CID = 1 and accountno < 1");


 $query = dbRead("SELECT tbl_members_email.email FROM (members INNER JOIN tbl_members_email ON members.memid = tbl_members_email.acc_no) INNER JOIN mem_categories ON members.memid = mem_categories.memid WHERE tbl_members_email.type = 3 AND Not tbl_members_email.email= ' ' AND mem_categories.description Like '%vcfl%' AND Not members.status=1 ORDER BY members.memid");
 while($row = mysql_fetch_assoc($query)) {
    $counter++;
    $dd .= $row['email'].",";
 }

 print $dd;
 print $counter;
?>