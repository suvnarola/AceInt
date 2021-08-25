<?

 /**
  * Key Performance Indicators Class
  *
  * class.kpi.php
  * version 0.01
  *
  * First Version of File.
  *
  * Contact: Antony Puckey
  * Email: antony@rdihost.com
  *
  * : Requires Database functions, dbRead(), dbWrite() and their associated functions.
  */

 class KPI {
 
  /*
   
   Class Description.
   
   
   Function Reference.
   
   
  */

  function KPI() {
  
    $this->CurrentUsers = Array();
    $this->SystemSummary = Array();
    $this->MonthlyUserSummary = Array();
    $this->UserTracking = Array();
    
  }

  function GetCurrentUsers() {
  
   $UserSQL = dbRead("select tbl_kpi.FieldID, UserID, LoginID, max(date) as Date, max(Type) as Type, max(Memid) as Memid, sec_to_time(unix_timestamp(now())-unix_timestamp(max(date))) as Diff  from tbl_kpi where date > '".date("Y-m-d H:i:s", mktime()-1440)."' Group By UserID Order by date desc","etxint_log");
   while($UserRow = mysql_fetch_assoc($UserSQL)) {
   
    $this->CurrentUsers[] = array(
    							'UserID'	=> $UserRow['UserID'],
    							'LoginID'	=> $UserRow['LoginID'],
    							'Date'		=> $UserRow['Date'],
    							'Diff'		=> $UserRow['Diff']
    						);
   
   }
   
  }

  function GetSystemSummary($YearMonth,$OrderBy = false) {
   
   $SQLOrder = ($OrderBy) ? $OrderBy : "tbl_kpi_type.Type";
   
   $KpiSQL = dbRead("select tbl_kpi_type.Type, count(tbl_kpi.FieldID) as Count from tbl_kpi_type left outer join tbl_kpi on (tbl_kpi_type.FieldID = tbl_kpi.Type and tbl_kpi.Date like '".$YearMonth."-%') group by tbl_kpi_type.Type order by " . $SQLOrder,"etxint_log");
   while($KpiRow = @mysql_fetch_assoc($KpiSQL)) {
   
    $this->SystemSummary[$KpiRow[Type]] = $KpiRow[Count];
   
   }
      
  }

  function GetMonthlyUserSummary($YearMonth) {
  
   $UserSQL = dbRead("select count(UserID) as Count, extract(Year_Month from Date) as YearMonth from tbl_kpi_login_history where Date like '".$YearMonth."-%' Group by YearMonth","etxint_log");
   $UserRow = mysql_fetch_assoc($UserSQL);
   
   $UniqueCountSQL = dbRead("select count(UserID), extract(Year_Month from Date) as YearMonth from tbl_kpi_login_history where Date like '".$YearMonth."-%' Group by UserID,YearMonth;","etxint_log");
   $UniqueCount = mysql_num_rows($UniqueCountSQL);
   
   $this->MonthlyUserSummary['Unique'] = $UniqueCount;
   $this->MonthlyUserSummary['TotalLogins'] = $UserRow['Count'];
   
  }

 }
 
?>