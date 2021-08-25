<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 23/4/17
 * Time: 4:39 PM
 */

//$CONFIG['db_name'] = "etradebanc";
//$CONFIG['db_host'] = "104.192.31.75";
//$CONFIG['db_user'] = "empireDB";
//$CONFIG['db_pass'] = "1emPire82";

define( 'DB_SERVER' , 'localhost' ) ;
define( 'DB_DATABASE' , 'etxint_etradebanc' ) ;
define( 'DB_USER' , 'etxint_admin' ) ;
define( 'DB_PASS' , 'Ohc6icho6eimaid3' ) ;

$mysqli = new mysqli( DB_SERVER , DB_USER , DB_PASS , DB_DATABASE ) ;

class country
{
	private $rows = array() ;

	function __construct()
	{
		global $mysqli ;
		$this->sql = "select * from country" ;

		$result = $mysqli->query( $this->sql ) ;
		if ( $result )
		{
			$this->found = $result->num_rows ;
			while ( $row = $result->fetch_object( /*$this->fetchObj*/ ) )
			{
				$this->rows[] = $row ;

			}
		}
		print_r( $this ); exit;
	}
}

class members
{
	private $rows = array() ;
	private $fields = array() ;
	private $table = 'members' ;

	function __construct()
	{
		global $mysqli ;
		$this->fields() ;
		$flds = implode( ', ' , $this->fields ) ;
//		$this->sql = "select $flds from members" ;
		$this->sql = "select count(*) as num from members" ;

		$result = $mysqli->query( $this->sql ) ;
		if ( $result )
		{
			$this->found = $result->num_rows ;
			while ( $row = $result->fetch_object( /*$this->fetchObj*/ ) )
			{
				$this->rows[] = $row ;

			}
		}
		print_r( $this ); exit;
	}

	private function fields(  )
	{
		$this->addField( 'memid' , 'member_id' ) ;
		$this->addField( 'accholder_first' , 'member_first_name' ) ;
		$this->addField( 'accholder_surname' , 'member_last_name' ) ;
		$this->addField( 'accholder_surname' , 'email' ) ;
		$this->addCalc( "ifnull( members.email_accounts , members.emailaddressold )" , 'email' ) ;
		$this->addCalc( "concat( members.streetno , ' ' , members.streetname )" , 'address_line_1' ) ;
		$this->addField( 'city' , 'city' ) ;
		$this->addField( 'state' , 'state' ) ;
	}

	private function addField( $field , $alias , $table = false )
	{
		$tbl = ( $table ) ? $table : $this->table ;
		$this->fields[] = $tbl . '.' . $field . ' as ' . $alias ;
	}

	private function addCalc( $calc , $alias )
	{
		$this->fields[] = $calc . ' as ' . $alias ;
	}

}


$mem = new members() ;