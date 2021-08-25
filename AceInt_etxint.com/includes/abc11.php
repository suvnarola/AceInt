<?
 $NoSession = true;
 include("/home/etxint/admin.etxint.com/includes/global.php");
 ini_set('max_execution_time','600');
 
$queryc = dbRead("select * from  members where status != 1 order by CID, memid"); 
//$queryc = dbRead("select tbl_corp_data.fieldid, country.* from  tbl_corp_data, country where (tbl_corp_data.langcode = country.countrycode) order by fieldid"); 
//$queryc = dbRead("select tbl_corp_headers.fieldid, country.* from  tbl_corp_headers, country where (tbl_corp_headers.CID = country.countryID) and tbl_corp_headers.langcode = 'nl' order by fieldid"); 
//$queryc = dbRead("select tbl_corp_data.fieldid, country.* from  tbl_corp_data, country where (tbl_corp_data.CID = country.countryID) and tbl_corp_data.langcode = 'be' order by fieldid"); 
//$queryc = dbRead("select tbl_lang_keywords.fieldid from  tbl_lang_keywords where tbl_lang_keywords.langcode = '' order by fieldid"); 
//$queryc = dbRead("select tbl_admin_data.fieldid from  tbl_admin_data where tbl_admin_data.langcode = 'be' order by fieldid"); 
//$queryc = dbRead("select tbl_admin_users.FieldID from  tbl_admin_users where tbl_admin_users.lang_code = 'be' order by FieldID"); 
  $Query7 = dbRead("select sum(transactions.sell) as sell, sum(transactions.buy) as buy from transactions, members where (members.memid = transactions.memid) and transactions.dis_date like '2005-11-%' and transactions.to_memid = '13698' and transactions.memid NOT IN (".get_non_included_accounts(1).") ");
$rowc = mysql_fetch_assoc($Query7);
print $rowc['sell']."  ".$rowc['buy'];
//while($rowc = mysql_fetch_assoc($queryc)) {

 //$query = dbRead("select Sum(dollarfees) as FeeSum from transactions where memid = ".$rowc['memid']." and dis_date <= '2005-06-30' and to_memid not in (".get_non_included_accounts($rowc['CID'],1).") group by memid"); 
 //$row = mysql_fetch_assoc($query);

 //$amount = ($row['FeeSum']-$rowc['fee_deductions']);
   
 if($amount > 0)  {
   //dbWrite("insert into feesincurred (date,memid,licensee,to_memid,to_licensee,fee_amount) values ('2005-06-30','".$rowc['memid']."','".$rowc['licensee']."','".$rowc['memid']."','".$rowc['licensee']."','".$amount."')"); 
 } elseif($row['FeeSum'] < 0)  {
   //$amount = ($row['FeeSum']*-1);
   //dbWrite("update members set over_payment = '".$amount."' where memid = '".$rowc['memid']."'");
   //if($rowc['fee_deductions'] > 0) {
    //print $rowc['memid'].", ".$rowc['fee_deductions'].", ".$amount."<br>";
    //dbWrite("update members set fee_deductions = '0' where memid = '".$rowc['memid']."'"); 
   //}
 }
 //$amount = 0;

  //dbWrite("update tbl_corp_data set CID = '".$rowc['countryID']."' where fieldid = '".$rowc['fieldid']."'"); 
  //dbWrite("update tbl_corp_headers set langcode = '".$rowc['Langcode']."' where fieldid = '".$rowc['fieldid']."'"); 
  //dbWrite("update tbl_corp_data set langcode = '".$rowc['Langcode']."' where fieldid = '".$rowc['fieldid']."'"); 
  //dbWrite("update tbl_admin_data set langcode = 'du' where fieldid = '".$rowc['fieldid']."'"); 
  //dbWrite("update tbl_admin_users set lang_code = 'du' where FieldID = '".$rowc['FieldID']."'"); 

  //print $rowc[fieldid].", ".$rowc[langcode]."<br>";
//}
?>