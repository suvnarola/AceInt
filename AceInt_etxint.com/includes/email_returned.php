<?


if($_REQUEST['email']) {

   $dd = explode("<", $_REQUEST['email']);
   $df = explode(">", $dd[1]);


   //print $dd[1];
   //print $df[0];
   if(validate_email($df[0])) {

     $emailSQL = dbRead("select tbl_members_email.* from tbl_members_email where email like '%".$df[0]."%' group by acc_no");

     if(@mysql_num_rows($emailSQL)) {

       while($emailRow = mysql_fetch_assoc($emailSQL)) {

   		dbWrite("insert into notes (memid,date,userid,type,note) values ('".$emailRow['acc_no']."','".date("Y-m-d")."','".$_SESSION['User']['FieldID']."','1','Email Returned ".htmlspecialchars($_REQUEST['email'])."')");
  		Print "Email found";
       }
	 }
   } else {

  	Print "Email not found";

   }

}
?>
        <form method="post">
			<textarea rows="5" cols="50" name="email"></textarea>
            <input type="submit" value="Search">
        </form>
<?

?>