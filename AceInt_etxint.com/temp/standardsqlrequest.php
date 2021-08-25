<?php
/**
 * Created by PhpStorm.
 * User: davidalderson
 * Date: 24/03/15
 * Time: 3:49 PM
 */

//use html\table;
//use html\tbody;
//use html\thead;
//use html\tr;

date_default_timezone_set('Australia/Melbourne');

class standardSQLrequest {
    var $table ;
    var $action ;
    var $fieldList = array() ;
    /* @var $where wheres */
    var $where ;
    /* @var $having wheres */
    var $having ;
    var $fields ;
    /* @var $joins joins */
    var $joins ;
    var $setFields ;
    var $dupFields ;
    var $dupField = array() ;
    /* @var $groupBy group */
    var $groupBy ;
    var $orderBy ;
    var $sql ;
    var $error ;
    var $found ;
    var $affected ;
    var $rows = array() ;
    var $fetchObj = 'standardSQLrequestObj' ;
    var $fetchObjParams ;			// Needs to be an array. Mostly useful when utilising a class or refenced variable where the fetchobj can add to alter etc
    var $rowKeyField ;
    var $rowKeyPrefix ;
    var $last_insert_id ;
    /* @var $rowObjToThis bool */
    var $rowObjToThis ;
    var $recordLimit ;
    var $limitOffset ;
    var $distinct ;
    var $alias ;
    var $debug ;
    var $dbServer ;
    var $dbDatabase ;
    var $dbUser ;
    var $dbPass ;

	function __construct( $table = '' , $act = 'select' ){
        $this->table = $table ;
        $this->action = $act ;
        $this->fields = new fields() ;
        $this->dbDatabase = DB_DATABASE ;
        if ( $table ) $this->primaryKeyField = $this->retPrimaryKeyField() ;
    }

    function connect(){

    }

    public static function tilde( $val )
    {
        return "`$val`" ;
    }

    //	Functions for our search queiries

    function clearWhere(){
        $this->where = new wheres ;
    }

    function clearHaving() {
        $this->having = new wheres ;
    }

    private function table_alias() {
        return ( $this->alias ) ? $this->alias : $this->table ;
    }

    /**
     * Check where or having exists.
     *
     * Checks that the property $type exists and is a class wheres and if not creates a new wheres instance assigned to the $type property.
     *
     * @param string $type
     */
    private function checkWhereExists( $type = 'where' ) {
        $w = 'wheres' ;
        if ( ! ( $this->{$type} instanceof $w ) ) $this->{$type} = new wheres ;
        $this->{$type}->type = $type ;
    }

    /**
     * @param string $fld
     * @param string $val
     * @param string $op
     * @param string $tbl
     * @param string $id
     *
     * @return where
     */
    function where( $fld , $val = '' , $op = '=' , $tbl = '' , $id = '' ) {
        return $this->addWhere( $fld , $val , $op , $tbl , $id ) ;
    }

    /**
     * Adds a new where class.
     *
     * @param string $fld
     * @param string $val
     * @param string $op
     * @param string $tbl
     * @param string $id
     *
     * @return where
     */
    function addWhere( $fld , $val = '' , $op = '=' , $tbl = '' , $id = '' ) {
        $this->checkWhereExists() ;
        $tbl = ( $tbl ) ? $tbl : $this->table_alias() ;
        $wh = $this->where->addWhere( $fld , $val , $tbl , $id ) ;
        if ( $op != '=' ) $wh->op = $op ;
        if ( $val == '[boolean:true]' ) $wh->isBoolean = true ;
        return $wh  ;
    }

    function addWhereById( $id , $fld , $val = '' , $op = '=' , $tbl = '' ) {
        return $this->where( $fld , $val , $op , $tbl , $id ) ;
    }

    function in( $fld , array $valsArray , $table = '' )
    {
        $val = "('" . implode("','" , $valsArray ) . "')[calc]" ;
        $this->where( $fld , $val , ' in ' , $table ) ;
    }

    private function remove_search( $fld , $type = 'where' ) {
        $w = 'wheres' ;
        if ( $this->{$type} instanceof $w ) {
            unset( $this->{$type}->wheres[$fld] ) ;
        }
    }

    function remWhere( $fld ) {
        $this->remove_search( $fld ) ;
    }

    function remHaving( $fld ) {
        $this->remove_search( $fld , 'having' ) ;
    }

    function between( $fld , $from , $to , $table = false ) {
        $wh = $this->where( $fld ) ;
        if ( $table ) $wh->table = $table ;
        $wh->between( $from , $to ) ;
        return $wh ;
    }

    function havingById(  $id , $fld , $val = '' , $op = '=' , $tbl = '' ) {
        $this->checkWhereExists( 'having' ) ;
        $wh = $this->having->addWhere( $fld , $val , $tbl , $id , $op ) ;
        return $wh ;
    }

    function having( $fld , $val = '' , $op = '=' , $tbl = '' ) {
        $w = 'wheres' ;
        if ( ! ( $this->having instanceof $w ) ) $this->having = new wheres ;
        $this->having->type = 'having' ;
        $wh = $this->having->addWhere( $fld , $val , $tbl ) ;
        $wh->op = $op ;
        return $wh ;
    }

    //	Functions for sorting the data

    function addOrderBy( $fld , $sort = 'ASC' , $table = false ) {
        $f = 'orderBys' ;
        if ( ! ( $this->orderBy instanceof $f ) ) $this->orderBy = new orderBys ;
        return $this->orderBy->addOrderBy( $fld , $sort , $table ) ;
    }

    function addSort( $fld , $sort = 'ASC' , $table = false ) {
        return $this->addOrderBy( $fld , $sort , $table ) ;
    }

    function clearSort() {
        $this->orderBy = false ;
    }

    //	Function for working with SQLs GROUP BY statement

    function addGroupBy( $fld , $tbl = '<none>' ) {
        $f = 'group' ;
        if ( ! ( $this->groupBy instanceof $f ) ) $this->groupBy = new group ;
        $this->groupBy->addGroup ( $fld , ( $tbl == '<none>' ) ? $this->table : $tbl ) ;
    }

    function clearGroupBy() {
        $this->groupBy = new group ;
    }

    function rollup() {
        $f = 'group' ;
        if ( ! ( $this->groupBy instanceof $f ) ) $this->groupBy = new group ;
        $this->groupBy->rollup = ( @ $this->groupBy->rollup ) ? false : true ;
    }

    /**
     * @return groupParse
     */
    function groupData() { return $this->groupBy->parseData ; } //	Provides easy access to this data

    //	For working with fields in sql staetments.

    function clearSetFields() {
        $this->setfields = false ;
    }

    /**
     * @param string $fld
     * @param string $val
     * @param bool $dup
     * @param bool $tbl
     *
     * @return mixed
     */
    function setField( $fld , $val , $dup = true , $tbl = false ) {
        $sf = 'setfields' ;
        if ( ! $tbl ) $tbl = $this->table ;
        if ( ! ( $this->setFields instanceof $sf ) ) $this->setFields = new setfields ;
        $f = $this->setFields->add( $fld , $val, $tbl, $dup ) ;
        //	$this->setField[$fld] = sqlFieldSet( $fld , $val ) ;
        //	if ( $dup ) $this->dupField[$fld] = sqlFieldSet( $fld , $val ) ;
        return $f ;
    }

    function getSetField( $fld )
    {
        return @ $this->setFields->setFields[ $fld ]->value ;
    }

	function getField( $fieldName )
	{
		if ( count( $this->rows) == 1 )
		{
			return $this->rows[0]->{ $fieldName } ;
		}
		elseif( count( $this->rows) > 1 )
		{
			/*
			 * @ToDo Add facility to return either array of values or separated values
			 */
			return false ;
		}
		else
		{
			return false ;
		}
	}

	/**
     * @param string $fld
     * @param string $tbl
     * @param string $alias
     *
     * @return field
     */
    function addField( $fld = '' , $tbl = '' , $alias = '' ) {
        $f = 'fields' ;
        if ( ! ( $this->fields instanceof $f ) ) $this->fields = new fields ;
        return $this->fields->addField( ( $tbl ) ? $tbl : $this->table_alias() , $fld , $alias ) ;
    }

    function addCount( $alias = 'num' ) {
        $fld = $this->addFieldAlias( $alias , 'count(*)' ) ;
        return $fld ;
    }

    function addFieldArray( $arr ) {
        foreach ( $arr as $key => $fld ) {
            $this->addField( $fld ) ;
        }
    }

    function removeField( $fld ) {
        $f = 'fields' ;
        if ( ! ( $this->fields instanceof $f ) ) return ;
        $this->fields->removeField( $fld ) ;
    }

    /**
     * @param string $alias
     * @param string $fld
     * @param string $tbl
     *
     * @return field
     */
    function addFieldAlias( $alias = '' , $fld = '' , $tbl = '' ) {
        return $this->addField ( $fld, $tbl , $alias ) ;
    }

    function addFieldCalc( $alias = '' , $calc = '' ) {
        $fld = $this->addField ( $calc , '' , $alias ) ;
        $fld->isCalc = true ;
        return $fld ;
    }

    function concat( $alias , $array ) {
        $fld = $this->addFieldAlias( $alias ) ;
        $fld->concat( $array ) ;
        return $fld ;
    }

    /**
     * @param $alias
     * @param $cond
     * @param $result_if_null
     *
     * @return field
     */
    function ifnull( $alias , $cond , $result_if_null ) {
        $fld = $this->addFieldAlias( $alias ) ;
        $fld->ifnull( $cond , $result_if_null ) ;
        return $fld ;
    }

    function addCase( $alias ) {
        $fld = $this->addFieldCalc( $alias ) ;
        $fld->funcWrapper = "CASE %s \n\tEND" ;
        return $fld ;
    }

    /**
     * @param string  $alias
     * @param array   $array
     *
     * @return field
     */
    function json( $alias , $array )
    {
        $fld = $this->addFieldAlias( $alias ) ;
        $fld->json( $array ) ;
        return $fld ;
    }

    function groupConcat( $alias , $field )
    {
        $fld = $this->addFieldAlias( $alias ) ;
        $fld->groupConcat( $field ) ;
        return $fld ;
    }

    /**
     * @param string    $alias
     * @param string    $table
     * @param bool|true $retField
     *
     * @return field|standardSQLrequest
     */
    function subQuery( $alias , $table , $retField = true ) {
        $fld = $this->addFieldAlias( $alias ) ;
        $subq = $fld->addSubQuery( $table ) ;
        return ( $retField ) ? $fld : $subq ;
    }

    /**
     * @param $alias
     * @param $table
     *
     * @return standardSQLrequest
     */
    function retSubQuery( $alias , $table )
    {
        return $this->subQuery( $alias , $table , false ) ;
    }

    //	Working with Joins

    function addJoin( $toTable , $primaryKey , $secondaryKey = false , $fromTable = false ) {
        $j = 'joins' ;
        if ( ! ( $this->joins instanceof $j ) ) $this->joins = new joins ;
        if ( ! $secondaryKey ) $secondaryKey = $primaryKey ;
        return $this->joins->addJoin( $primaryKey , $secondaryKey , $toTable , ( $fromTable ) ? $fromTable : $this->table ) ;
    }

    function addLeftJoin( $toTable , $primaryKey , $secondaryKey = false , $fromTable = false ) {
        $jn = $this->addJoin( $toTable , $primaryKey , $secondaryKey , $fromTable ) ;
        $jn->type = 'left' ;
        return $jn ;
    }

    /**
     * @param string $toTable
     * @param string $primaryKey
     * @param string $secondaryKey
     * @param string $fromTable
     *
     * @return join
     */
    function leftJoin( $toTable , $primaryKey , $secondaryKey = false , $fromTable = false ) {
        return $this->addLeftJoin( $toTable , $primaryKey , $secondaryKey , $fromTable ) ;
    }

    function addJoinHard ( $toTable , $keyField , $value , $op = '=' ) {
        $j = 'joins' ;
        if ( ! ( $this->joins instanceof $j ) ) $this->joins = new joins ;
        $jn = $this->joins->addJoin( '' , $keyField , $toTable , '' ) ;
        $jn->addHard( $keyField , $value , $op ) ;
        return $jn ;
    }

    //	Function join
    //		acepts labelled parameters eg "label:value" or array( "label" => value , [....] ) ;
    //		Parameters are as below. [param] indicates an optional parameter
    //			to			:	The table we are joining to
    //			[from]		:	The table we are joining from. If not specified will use the primary table
    //			pkey		:	The primary key to use for the join
    //			[skey]		:	The secondary key if named differently from the primary key
    //			[alias]		:	Alias for the to table
    //			[type]		:	The type of join to be used. Defaults to inner join
    function Join()
    {
        $a = func_get_args(); $args = args::parse($a);
        if ( property_exists( $args , 'to' ) && property_exists( $args , 'pkey' ) )
        {
            if( @ $args->val && @ $args->skey )
            {
                $jn = $this->addJoinHard( $args->to , $args->skey , $args->val , ( @ $args->op ) ? $args->op : '=' ) ;
            }
            else
            {
                $jn = $this->addJoin( $args->to , $args->pkey , @ $args->skey , @ $args->from ) ;
            }
            if( @ $args->alias ) $jn->alias = $args->alias ;
            if( @ $args->type ) $jn->type = $args->type ;
            return $jn ;
        }
        else
        {
            error_log( 'class standardSQLrequest->Join() error: missing arguments must supply to: and pkey:' ) ;
            return json_decode( '{"error":"Missing parameters"}' ) ;
        }
    }

    // The crux of it getting all this stuff and turning it into a sql statement

    function buildSQL() {
        if ( ! @ $this->primaryKeyField ) $this->primaryKeyField = $this->retPrimaryKeyField() ;
        $f = 'fields' ; $j = 'joins' ; $w = 'wheres' ; $o = 'orderBys' ; $s = 'setfields' ; $g = 'group' ;
        if ( ! ( $this->fields instanceof $f ) ) $this->fields = new fields ;
        if ( ! ( $this->joins instanceof $j ) ) $this->joins = new joins ;
        if ( ! ( $this->groupBy instanceof $g ) ) $this->groupBy = new group ;
        if ( ! ( $this->where instanceof $w ) ) $this->where = new wheres ;
        if ( ! ( $this->having instanceof $w ) ) $this->having = new wheres ;
        if ( ! ( $this->orderBy instanceof $o ) ) $this->orderBy = new orderBys ;
        if ( ! ( $this->setFields instanceof $s ) ) $this->setFields = new setfields ;
        $fl = $this->fields->build() ;
        $jn = $this->joins->build() ;
        $gp = $this->groupBy->build() ;
        $wh = $this->where->build() ;
        $hv = $this->having->build() ;
        $ob = $this->orderBy->build() ;
        $sf = $this->setFields->build() ;
        $df = $this->setFields->buildOnDuplicate() ;
        $loff = ( $this->limitOffset ) ? ( ( $this->limitOffset == 'start' ) ? "0," : "{$this->limitOffset}," ) : '' ;
        $lm = ( $this->recordLimit ) ? " limit {$loff}{$this->recordLimit}" : '' ;
        //	$cfr = ( $this->recordLimit ) ? 'SQL_CALC_FOUND_ROWS' : '' ;  // not working due to being applied in subqueries
        $dist = ( $this->distinct ) ? 'DISTINCT' : '' ;
        $alias = ( $this->alias ) ? "as {$this->alias}" : '' ;
        switch ( $this->action ) {
            case 'select' :
                $this->sql = "select $dist $fl from {$this->table} $alias $jn $wh $gp $hv $ob $lm" ; break ;
            case 'update' :
                $this->sql = "update {$this->table} $jn $sf $wh" ; break ;
            case 'delete' :
                $this->sql = "delete from {$this->table} $wh" ; break ;
            case 'insert' :
                $this->sql = "insert into {$this->table} $sf" ; break ;
            case 'insert_dup' :
                $this->sql = "insert into {$this->table} $sf $df" ; break ;
            case 'new' :
                $this->sql = "insert into {$this->table} $sf" ; break ;
            case 'describe' :
                $this->sql = "show columns from {$this->table}" ; break ;
            case 'truncate' :
                $this->sql = "truncate table {$this->table}" ; break ;
            case 'show' :
                $this->sql = "show FULL columns from {$this->table} $wh" ; break ;
            default : $from = '' ;
        }
    }

	function getRow( $num = 0, $valueField = '' , $valueData = '' )
	{
		$this->select( $valueField , $valueData ) ;
		return ( $num === 'all' ) ? $this->rows : $this->rows[ $num ] ;
	}

	function retSQL() {
        $this->buildSQL() ;
        return $this->sql ;
    }

    function select( $valueField = '' , $valueData = '' ) {
        $this->action = 'select' ;
        return $this->process( $valueField , $valueData) ;
    }

    function selectAll( $valueField = '' , $valueData = '' ) {
        $this->clearWhere() ;
        $this->clearHaving() ;
        return $this->select( $valueField , $valueData ) ;
    }

    function update( $valueField = '' , $valueData = '' ) {
        $this->action = 'update' ;
        return $this->process( $valueField , $valueData) ;
    }

    function delete() {
        $this->action = 'delete' ;
        return $this->process( '' , '' ) ;
    }

    function insert() {
        $this->action = 'insert' ;
        return $this->process( '' , '' ) ;
    }

    function describe() {
        $this->action = 'describe' ;
        $this->process() ;
        return $this->Columns ;
    }

    function show_columns() {
        $this->describe() ;
        return $this->process() ;
    }

	public function truncate()
	{
		$this->action = 'truncate' ;
		return $this->process() ;
	}

    function showFull()
    {
        $this->action = 'show' ;
        $this->process() ;
    }

    private function getData( $result ) {
        while ( $row = $result->fetch_object( $this->fetchObj , $this->fetchObjParams ) ) {
            if ( @ $this->rowKeyField ) {
                $this->rows["{$this->rowKeyPrefix}{$row->{$this->rowKeyField}}"] = $row ;
            } else {
                if ( ( $this->found == 1 || $this->recordLimit == 1 ) && $this->rowObjToThis ) {
                    foreach ( $row as $key => $val ) {
                        $key = str_replace( ' ' , '_' , $key ) ;
                        $this->{$key} = $val ;
                    }
                } else {
                    if ( $this->groupBy && $this->groupBy->rollup ) {
                        $this->groupBy->parse( $row ) ;
                    } else {
                        $this->rows[] = $row ;
                    }
                }
            }
            if ( @ $valueField ) $this->{$row->{$valueField}} = $row->{$valueData} ;
        }
    }

    function process( $valueField = '' , $valueData = '' ) {
        global $mysqli ;
        $this->buildSQL() ;
        if( $mysqli->connect_errno )
        {
            $this->error = 'Failed to connect to MySQL' ;
            return false ;
        }
        if ( $this->dbDatabase && $this->dbDatabase != DB_DATABASE )   $mysqli->select_db( $this->dbDatabase ) ;
        $sqlstart = microtime( true ) ;
        $result = $mysqli->query( $this->sql ) ;
        $sqlend = microtime( true ) ;
        $this->sql_query_time = round ( $sqlend - $sqlstart, 4 ) ;
        if ( $this->action == 'insert' ) $this->last_insert_id = $mysqli->insert_id ;
        $this->error = $mysqli->error ;
        $this->affected = $mysqli->affected_rows ;
        if ( $result ) {
            if ( $this->action == 'select' ) {
                if ( $this->limitOffset ) {
                    $offs = new mysqli( DB_SERVER , DB_USER , DB_PASS , $this->dbDatabase ) ;
                    $jn = $this->joins->build() ;
                    $wh = $this->where->build() ;
                    $hv = $this->having->build() ;
                    $res = $offs->query( "select count(*) as num from {$this->table} $jn $wh $hv" ) ;
                    $row = $res->fetch_row() ;
                    $this->found = $row[0] ;
                } else {
                    $this->found = $result->num_rows ;
                }
                $this->rows = array() ;
                if ( $this->fetchObj != 'standardSQLrequestObj' && $this->fetchObjParams ) {
                    $this->getData( $result ) ;
                } else {
                    while ( $row = $result->fetch_object( $this->fetchObj ) ) {
                        if ( @ $this->rowKeyField ) {
                            $this->rows["{$this->rowKeyPrefix}{$row->{$this->rowKeyField}}"] = $row ;
                        } else {
                            if ( ( $this->found == 1 || $this->recordLimit == 1 ) && $this->rowObjToThis ) {
                                foreach ( $row as $key => $val ) {
                                    $key = str_replace( ' ' , '_' , $key ) ;
                                    $this->{$key} = $val ;
                                }
                            } else {
                                if ( $this->groupBy && $this->groupBy->rollup ) {
                                    $this->groupBy->parse( $row ) ;
                                } else {
                                    $this->rows[] = $row ;
                                }
                            }
                        }
                        if ( $valueField && $row->{$valueField} ) $this->{$row->{$valueField}} = ( $valueData ) ? $row->{$valueData} : '' ;
                    }
                }
            }
            elseif( $this->action == 'describe' || $this->action == 'show')
			{
                $this->Columns = new stdClass ;
                while ( $row = $result->fetch_object( $this->fetchObj ) ) {
                    if( $this->action == 'show' && @ $row->Comment ) {
                        $comm = json_decode( $row->Comment ) ;
                        if( json_last_error() ) {
                            $c = new stdClass;
                            $c->comment = $row->Comment ;
                            $row->Comment = $c ;
                        }
                        else
                        {
                            $row->Comment = $comm ;
                        }
                    }
                    $colKeyPrefix = ( $this->rowKeyPrefix ) ? $this->rowKeyPrefix : '' ;
                    $this->Columns->{ $colKeyPrefix . $row->Field } = $row ;
                }
            }
//            $result->close() ;
        } else {
            //	echo 'Error: ' . $this->sql . '  <br>' ;
        }
        return ( $this->error ) ? false : true ;
    }

    function retPrimaryKeyField() {
        $pkeys = new MySQLi( DB_SERVER , DB_USER , DB_PASS , ( $this->dbDatabase ) ? $this->dbDatabase : 'allgrads_dbms' ) ;
        if( $pkeys->connect_errno ) {
            error_log( 'Failed to connect to MYSQL in ' . __FILE__ . ' at line: ' . __LINE__ ) ;
        } else {
            if( $result = $pkeys->query( "select `primary_key_field` from pkeys where `table` = '{$this->table}'" ) ) {
                if ( $result->num_rows == 1 ) {
                    $row = $result->fetch_object();
                    $result->free();
                    return $row->primary_key_field;
                } else {
                    $result = $pkeys->query( "show columns from {$this->table} where `KEY`='PRI'" );
                    if ( $result->num_rows == 1 ) {
                        $row = $result->fetch_object();
                        $result->free();
                        $pkeys->query( "insert into pkeys set `table` = '{$this->table}', `primary_key_field` = '{$row->Field}'" );
                        return $row->Field;
                    }
                }
            }
            else
            {
                error_log( "Using {$this->dbDatabase}") ;
                error_log( "Query: select `primary_key_field` from pkeys where `table` = '{$this->table}' in " . __FILE__ . ' at line: ' . __LINE__ ) ;
            }
        }
        return '' ;
    }

    function csvArray()
    {
        $arr[] = array_keys( (array) $this->rows[0] ) ;
        foreach ( $this->rows as $row ) {
            $arr[] = array_values( (array) $row ) ;
        }
//		print_r( $arr ) ; exit ;
        return $arr ;
    }

	private function htmlTableHead( $fields )
	{
		$head = new thead() ;
		$tr = new tr() ;
		foreach ( $fields as $key => $field )
		{
			$tr->th( ( is_string( $key ) ) ? $key : $field ) ;
		}
		$head->data .= $tr ;
		return $head ;
    }

	private function htmlTableRow( $fields , $obj )
	{
		$tr = new tr() ;
		foreach ( $fields as $field )
		{
			$tr->td( $obj->{ $field } ) ;
		}
		return $tr ;
    }

	public function getHtmlTable( array $fields = null ) : table
	{
		$table = new table() ;
		$head = $this->htmlTableHead( $fields = ( $fields ) ? $fields : array_keys ( get_object_vars( $this->rows[0] ) ) ) ;
		$table->data .= $head ;
		$tbody = new tbody() ;
		foreach ( $this->rows as $row )
		{
			$tbody->data .= $this->htmlTableRow( $fields , $row ) ;
		}
		$table->data .= $tbody ;
		return $table ;
    }

}

class setfields {
    var $setFields ;
    var $dupFields ;

    function add ( $fld , $val , $tbl , $dup = true ) {
        $this->setFields[ $fld ] = new setField ( $fld , $val , $tbl ) ;
        if ( $dup ) $this->dupFields[ $fld ] = $this->setFields[ $fld ] ;
        return $this->setFields[ $fld ] ;
    }

    function build() {
        if ( $this->setFields ) {
            foreach ( $this->setFields as $key => $sf ) {
                @ $temp[] = $sf->build() ;
            }
            return " set " . join( ' , ' , $temp ) ;
        } else {
            return '' ;
        }
    }

    function buildOnDuplicate() {
        if ( $this->dupFields ) {
            foreach ( $this->dupFields as $key => $sf ) {
                @ $temp[] = $sf->build() ;
            }
            return " on duplicate key update " . join( ' , ' , $temp ) ;
        } else {
            return '' ;
        }
    }

}

class setField extends where {
    function __construct( $fldn , $val , $tbl ){
        $this->table = $tbl ;
        $this->field = $fldn ;
        $this->value = $val ;
        $this->parseVal() ;
        $this->op = '=' ;
    }
}

class wheres {
    var $wheres ;
    var $type = 'where' ;
    var $op = 'and' ;

    function addWhere ( $fld , $val , $tbl, $id = '' , $op = '=' ) {
        $f = 'field' ;
        if ( $fld instanceof $f && ! $id ) {
            $key = ( $fld->alias ) ? $fld->alias : $fld->name ;
        } else {
            $key = ($id) ? $id : $fld;
        }
        $this->wheres[ $key ] = new where ( $fld , $val , $tbl , $op ) ;
        return $this->wheres[ $key ] ;
    }

    function build() {
        if ( $this->wheres ) {
            foreach ( $this->wheres as $key => $where ) {
                @ $temp[] = $where->build() ;
            }
            return " \n {$this->type} \n\t" . join( "\n\t{$this->op} " , $temp ) ;
        } else {
            return '' ;
        }
    }
}

class where {
    var $table ;
    var $field ;
    var $value ;
    var $op = '=' ;
    var $quoted = "'" ;
    var $isBoolean = false ;
    var $isCalc = false ;		//	Boolean if true the field part of this where statement is a calculated result
    var $not = false ;
    var $valueTo ;
    var $binary ;

    function __construct( $fldn , $val , $tbl , $op = '=' ) {
        $this->table = $tbl ;
        $this->value = $val ;
        $this->op = $op ;
        $this->parseFld( $fldn ) ;
        //	Switch statement allows us to set up where based on special criteria in the value part ie $obj->where( fld , '[criteria]' )
        if ( $this->is_a_field( $val ) ) {
            $this->quoted = '' ;
            $this->value = $this->check_for_field( $val ) ;
        } else {
            switch ( $val ) {
                case '[bool]' : $this->isBoolean = true; $this->value = ''; break ;
                case '[boolean]' : $this->isBoolean = true; $this->value = ''; break ;
                case '[isBoolean]' : $this->isBoolean = true; $this->value = ''; break ;
                case '[true]' : $this->isBoolean = true; $this->value = ''; break ;
                case '[false]' : $this->isBoolean = true; $this->value = '' ; $this->not = true ; break ;
                case '[isEmpty]' : $this->empty_def() ; break ;
                case '[empty]' : $this->empty_def() ; break ;
                case '[notEmpty]' : $this->empty_def( true ) ; break ;
                case '[null]' : $this->quoted = ''; $this->value = 'null' ; $this->op = ' is ' ; break ;
                case '[notNull]' : $this->quoted = '' ; $this->value = 'null'; $this->op = ' is not ' ; break ;
                case '[notnull]' : $this->quoted = '' ; $this->value = 'null'; $this->op = ' is not ' ; break ;
                case '[curdate]' : $this->value = 'CURDATE()'; $this->quoted = '' ; break ;
                default : $this->parseVal() ;	// See next function for what happens here
            }
        }
    }

    //	Processed after switch statement that checks to see if val exactly matches certain criteria
    //	parseVal looks for certain strings in the val, strips them out and performs tasks based on the search string found
    function parseVal( $val = false ) {
        if( $val )
        {
            $val = $this->parseVal_sub( 'calc' , array( '<','>' ) , $val ) ;
            $val = $this->parseVal_sub( 'calc' , array( '[',']') , $val ) ;
            $val = $this->parseVal_sub( 'noquote' , array( '[',']') , $val ) ;
            $val = $this->parseVal_sub( 'number' , array( '[',']') , $val ) ;
            $val = $this->parseVal_sub( 'binary' , array( '[',']') , $val ) ;
            return $val ;
        }
        $this->parseVal_sub( 'calc' , array( '<','>' ) ) ;
        $this->parseVal_sub( 'calc' ) ;
        $this->parseVal_sub( 'noquote' ) ;
        $this->parseVal_sub( 'number' ) ;
        $this->parseVal_sub( 'binary' ) ;
        // This switch is for setField which extends this class as the function where above is not called
        switch ( strtolower( $this->value ) ) {
            case 'null' : $this->quoted = '' ; $this->op = ' is ' ; break ;
            case '[null]' : $this->value = 'null' ; $this->quoted = '' ; $this->op = ' is ' ; break ;
            case '[emptystring]' : $this->value = '' ; break ;
        }
    }

    /**
     * @param string $pv
     * @param array $bket
     * @param String $val
     *
     * @return string
     */
    private function parseVal_sub( $pv , $bket = array( '[',']' ) , $val = false ) {
        $binary = ( $pv == 'binary' ) ;
        $pv = $bket[0] . $pv . $bket[1] ;
        if ( $val )
        {
            if( strpos( $val , $pv ) !== false )
            {
                $val = str_replace( $pv , '' , $val ) ;
                if( $binary ) $val = "binary '$val'" ;
            }
            return $val ;
        }
        elseif ( strpos( $this->value , $pv ) !== false )
        {
            $this->value = str_replace( $pv , '' , $this->value ) ;
            if( $binary ) $this->value = "binary '{$this->value}'" ;
            $this->quoted = '' ;
        }
    }

    //	parseFld
    //	By placing <calc> in the field name we can define this field as a calc field.
    //	Also if there are opening and closing parenthesis '(' and ')' in the field def then we can assume this a calc field as it must contain a function
    //	Also look for [bin] or [binary] and set this field to be cast to binary
    function parseFld( $fldn ) {
        if ( $this->is_a_field( $fldn ) ) {
            $this->isCalc = true ;
        } elseif ( strpos( $fldn , '<calc>' ) !== false ) {
            $fldn = str_replace( '<calc>' , '' , $fldn ) ; $this->isCalc = true ;
        } elseif ( strpos( $fldn , '[calc]' ) !== false ) {
            $fldn = str_replace( '[calc]' , '' , $fldn ) ; $this->isCalc = true ;
        } elseif ( strpos( $fldn , '[bin]' ) !== false ) {
            $fldn = str_replace( '[bin]' , '' , $fldn ) ; $this->binary = true ;
        } elseif ( strpos( $fldn , '[binary]' ) !== false ) {
            $fldn = str_replace( '[binary]' , '' , $fldn ) ; $this->binary = true ;
        } elseif( strpos( $fldn , '(' ) !== false && strpos( $fldn , ')' ) !== false ){
            $this->isCalc = true ;
        }
        $this->field = $this->check_for_field( $fldn ) ;
    }

    function empty_def( $not = false ){
        //	Relies on empty function being defined in mysql is as follows
        /*  accepts 1 parameter `data` varchar(255) and returns tinyint(1)
            BEGIN
                if( data is null || data = '' ) THEN
                    return 1 ;
                else
                    return 0 ;
                end if ;
            END
        */
        $rev = ( $not ) ? '! ' : '' ;
        $this->field = $rev . "empty( " . $this->field_def() . " )" ;
        $this->isCalc = true;
        $this->isBoolean = true;
        $this->value = '' ;
    }

    private function field_def() {
        return $this->table_def( true ) ;
    }

    private function table_def( $incField = false ) {
        $tbl = ( $this->table ) ? $this->table . '.'  : '' ;
        $bin = ( $this->binary ) ? 'binary ' : '' ;
        return ( $incField ) ? $bin . $tbl . "`" . $this->field . "`" : $tbl ;
    }

    function set() {
        global $mysqli ;
        $fldDef = ( $this->isCalc ) ? $this->field : $this->table_def( true ) ;
        $val = ( empty ( $this->quoted ) ) ? $this->value : $mysqli->real_escape_string( $this->value ) ;
        $val = $this->quoted . $val . $this->quoted ;
        if ( @ $this->valueTo ) {
            $v2 = $this->parseVal( $this->valueTo ) ;
            if( $v2 != $this->valueTo )
            {
                //  There has been a [calc] or [binary] etc in the value to. Leave as is
            }
            else
            {
                $v2 = $this->quoted . ( ( empty ( $this->quoted ) ) ? $this->valueTo : $mysqli->real_escape_string( $this->valueTo ) ) . $this->quoted ;
            }
            return "$fldDef between $val and $v2" ;
        } else {
            return $fldDef . $this->op . $val ;
        }
    }

    function between( $from , $to ) {
        $this->value = $from ;
        $this->parseVal() ;
        $this->valueTo = $to ;
    }

    private function is_a_field( $fld ) {
        return $this->check_for_field( $fld , true ) ;
    }

    private function check_for_field( $fld  , $bool = false ) {
        $f = 'field' ;
        if ( $bool ) {
            return ( $fld instanceof $f ) ? true : false ;
        } else {
            return ( $fld instanceof $f ) ? $fld->def() : $fld ;
        }
    }

    function build() {
        $f = 'field' ;
        $revBool = ( $this->not ) ? 'not ' : '' ;
        if ( $this->field instanceof $f ) {
            if ( $this->isBoolean ) {
                return $revBool . $this->field->def() ;
            } else {
                return $this->field->def() . $this->op . $this->quoted . ( ( empty ( $this->quoted ) ) ? $this->value : sval( $this->value ) ) . $this->quoted ;
            }
        }
        if ( $this->isBoolean ) {
            $tbl = ( $this->table ) ? $this->table . '.'  : '' ;
            // Add tbl to field return of following statement and check to see nothing breaks
            return $revBool . ( ( $this->isCalc ) ? $this->field : $tbl . "`" . $this->field . "`" ) ;
        } else {
            return $this->set() ;
        }
    }

}

class fields {
    var $fields ;

    /**
     * @param string $tbl
     * @param string $name
     * @param string $alias
     *
     * @return field
     */
    function addField( $tbl = '' , $name = '' , $alias = '' ) {
        $fld = ( $alias ) ? $alias : ( ( $tbl ) ? $tbl .'_' . $name : $name ) ;
        $this->fields[ $fld ] = new field( $tbl , $name , $alias ) ;
        return $this->fields[ $fld ] ;
    }

    function removeField( $fld ) {
        unset( $this->fields[ $fld ] ) ;
    }

    function build(){
        if ( $this->fields ) {
            foreach ( $this->fields as $key => $field ) if ( $field->include ) @ $temp[] = $field->build() ;
            return "\n\t" . join( ",\n\t"  , $temp ) . "\n" ;
        } else {
            return '*' ;
        }
    }
}

class field {
    var $table ;
    var $name ;
    var $alias ;
    var $isCalc ;
    var $include = true ;
    var $applyRounding ;
    var $roundTo = 2 ;
    var $sum ;			// ( boolean ) Indicates that this field is to be a sum of the numeric field value
    var $avg ; 			// ( boolean ) Indicates that this field is to be an average of the numeric field value
    var $maxval ; 		// ( boolean ) Indicates that this field is to be the maximum value of the field value
    /* @var $subQuery standardSQLrequest */
    var $subQuery ;
    var $case ;
    var $funcWrapper ;	// New way of processing min max sum etc see checkDirectives and wrapFunc for how this is applied

    function __construct( $tbl = '' , $name = '' , $alias = '' ) {
        $this->table = $tbl ;
        $name = $this->check_for_field( $name ) ;
        $name = $this->checkDirectives( $name ) ;
        $alias = $this->checkDirectives( $alias ) ;
        if ( ( $pos = strpos( $name , '[round:' ) ) !== false ) {
            $this->applyRounding = true ;
            $p = substr( $name , $pos , strpos( $name , ']' , $pos ) - $pos + 1 ) ;
            $ps = explode( ':' , trim( $p , '][' ) ) ;
            $this->roundTo = $ps[1] ;
            $name = str_replace( $p , '' , $name ) ;
        }
        if ( strpos( $name , '(' ) !== false && strpos( $name , ')' ) !== false ) $this->isCalc = true ;
        $this->name = $name ;
        $this->alias = $alias ;
    }

    function checkDirectives( $name ) {
        $preg = '#\[.{2,}?\]#' ;
        preg_match( $preg , $name , $matches ) ;
        if ( $matches ) {
            $func = trim( $matches[0] , '][' ) ;
            $checkRounding = ( $func == 'round' ) ? ", %d" : '' ;
            $params = explode( ':' , $func ) ;
            if ( count( $params ) > 1 ) {
                $func = array_shift( $params ) ;
                $checkRounding = str_replace( '%' , '[perc]' , ", " . implode( ' , ' , $params ) ) ;
            }
            $this->funcWrapper = ( $this->funcWrapper ) ? "$func( {$this->funcWrapper} $checkRounding )" : "$func( %s $checkRounding )" ;
            return $this->checkDirectives( preg_replace( $preg , '' , $name , 1 ) ) ;
        } else {
            return $name ;
        }
    }

    function addCalc( $fld1 = '' , $op = '' , $fld2 = '' ) {
        $this->isCalc = true ;
        $this->name = $this->check_for_field( $fld1 ) ;
        $this->name .= ( $op ) ? " $op " : '' ;
        $this->name .= $this->check_for_field( $fld2 ) ;
    }

    private function caseConditionTypeCheck( $obj ) {
        if ( $this->check_for_field( $obj , true ) ) {
            return $this->check_for_field( $obj ) ;
        } elseif( is_numeric( $obj ) ) {
            return $obj ;
        } else {
            return "'$obj'" ;
        }
    }

    function addCaseCondition( $left , $op = '=' , $right , $then ) {
        $left = $this->caseConditionTypeCheck( $left ) ;
        $right = $this->caseConditionTypeCheck( $right ) ;
        $then = $this->caseConditionTypeCheck( $then ) ;
        $if = "$left $op $right" ;
        $this->name .= "\n\t\tWHEN $if THEN $then " ;
    }

    function addCaseElse( $else ) {
        $else = $this->caseConditionTypeCheck( $else ) ;
        $this->name .= "\n\t\tELSE $else" ;
    }

    function addFunction( $f ) {
        $this->name = "$f( {$this->name} )" ;
    }

    function json( $array )
    {
        $arr = array() ;
        $arr[] = "'{'" ;
        foreach ( $array as $key => $val ) {
            if( $this->check_for_field( $val , true ) )
            {
                $pre = ( $key ) ? "'" . '",' : "'" ;
                $arr[] = $pre . '"' . ( ( $val->alias ) ? $val->alias : $val->name ) . '":"' . "'" ;
                $arr[] = $this->check_for_field( $val ) ;
            }
        }
        $arr[] = "'" . '"}' . "'" ;
        $this->concat( $arr ) ;
    }

    function concat( $array ) {
        foreach ( $array as $key => &$val ) $val = $this->check_for_field( $val ) ;
        $this->name = "concat( " . implode( ' , ' , $array ) . ' )' ;
        $this->isCalc = true ;
    }

    function groupConcat( $field )
    {
        $this->name = 'GROUP_CONCAT(' . $this->check_for_field( $field ) . ')' ;
        $this->isCalc = true ;
    }

    function genFunction( $func , $params ) {
        foreach ( $params as $key => $param ) @ $a[] = $this->check_for_field( $param ) ;
        $this->name = "$func( " . implode( ' , ' , $a ) . " )" ;
        $this->isCalc = true ;
    }

    function datediff( $from , $less ) { $this->genFunction( 'datediff' , array( $from , $less ) ) ; }
    function timestampdiff( $from , $to , $units = 'SECOND' ) { $this->genFunction( 'timestampdiff' , array( $units , $from , $to ) ) ; }

    function addIf( $cond , $iftrue , $iffalse ) {
        $this->isCalc = true ;
        $cond = $this->check_for_field( $cond ) ;
        $iftrue = $this->check_for_field( $iftrue ) ;
        $iffalse = $this->check_for_field( $iffalse ) ;
        $this->name = "if( $cond , $iftrue , $iffalse )" ;
    }

    function ifnull( $cond , $result_if_null ) {
        $this->isCalc = true ;
        $this->name = "ifnull( " . $this->check_for_field( $cond ) . ' , ' . $this->check_for_field( $result_if_null ) . ' )' ;
    }

    private function check_for_field( $fld  , $bool = false ) {
        $f = 'field' ;
        if ( $bool ) {
            return ( $fld instanceof $f ) ? true : false ;
        } else {
            return ( $fld instanceof $f ) ? $fld->def() : $fld ;
        }
    }

    function encapsulate( $c1 = '(' , $c2 = ')' ) {
        return $c1 . $this->name . $c2 ;
    }

    function addSubQuery( $tbl ) {
        $this->subQuery = new standardSQLrequest ( $tbl ) ;
        return $this->subQuery ;
    }

    private function applyCase( $def ) {
        switch ( $this->case ) {
            case 'lower' : return "LOWER( $def )" ; break ;
            case 'upper' : return "UPPER( $def )" ; break ;
            default : return $def ;
        }
    }

    function retTableName() {
        $table = ( $this->table ) ? "{$this->table}." : '' ;
        $tilde = ( $this->name == '*' ) ? '' : '`' ;
        return  ( $this->isCalc ) ? $this->name : $table . $tilde . $this->name . $tilde ;
    }

    function def() {
        if( $this->subQuery ) return $this->wrapFunc( '( ' . $this->subQuery->retSQL() . ' )' ) ;
        if ( $this->case && ! $this->alias ) $this->alias = $this->name ;
        return $this->wrapFunc(  $this->applyCase( $this->retTableName() ) ) ;
    }

    function wrapFunc( $val ) {
        $func = '' ; $close = '' ;
        if ( $this->sum ) {
            $func = 'sum( ' ; $close = ' )' ;
        } elseif( $this->avg ) {
            $func = 'avg( ' ; $close = ' )' ;
        } elseif( $this->maxval ) {
            $func = 'max( ' ; $close = ' )' ;
        }
        if( $this->funcWrapper ) return str_replace( '[perc]' , '%' , sprintf( $this->funcWrapper , $val , $this->roundTo ) ) ;
        return ( $this->applyRounding ) ? "round( $func{$val}$close , {$this->roundTo} )" : "$func{$val}$close" ;
    }

    function build() {
        if ( $this->subQuery ) return $this->def()  . ' as ' . ( ( @ $this->alias ) ? standardSQLrequest::tilde($this->alias) : $this->name ) ;
        $alias = ( $this->alias ) ? " as `{$this->alias}`" : '' ;
        return  $this->def() . $alias ;
    }
}

class sql_function {
    private $params = array() ;

    function __construct( $name ){
        $this->name = $name ;
    }

    function param( $param ) {
        $this->params[] = new sql_function_param( $param ) ;
    }

    function build() {
        $str = $this->name . '( ' ;
        foreach ( $this->params as $key => $p ) {
            @ $temp[] = $p->build() ;
        }
        $str .= join( ' , ' , @ $temp ) . ' )' ;
        return $str ;
    }
}

class sql_function_param {
    function __construct( $val ) {
        $this->value = $val ;
    }

    function build() {
        return $this->value ;
    }
}

class joins {
    var $joins ;
    function __construct() {

    }

    /**
     * @param $primaryKey
     * @param $secondaryKey
     * @param $skey_table
     * @param $pkey_table
     *
     * @return join
     */
    function addJoin( $primaryKey , $secondaryKey , $skey_table , $pkey_table ) {
        $this->joins[] = new join( $primaryKey , $secondaryKey , $skey_table , $pkey_table ) ;
        return $this->joins[ count ( $this->joins ) - 1 ] ;
    }

    function build() {
        if ( $this->joins ) {
            foreach ( $this->joins as $key => $join ) {
                $temp[] = $join->build() ;
            }
            return " \n" . join( " \n"  , $temp ) ;
        } else {
            return '' ;
        }
    }
}

class join {
    var $primaryKey_table ;
    var $secondaryKey_table ;
    var $primaryKey ;
    var $secondaryKey ;
    var $type = 'inner' ;
    var $alias ;
    var $multiple = array() ;

    function __construct( $primaryKey = '' , $secondaryKey = '' , $skey_table = '' , $pkey_table = '' ) {
        $this->primaryKey = $primaryKey ;
        $this->secondaryKey = $secondaryKey ;
        $this->secondaryKey_table = $skey_table ;
        $this->primaryKey_table = $pkey_table ;
    }

    private function retSecondaryKeyTable() {
        return ( empty ( $this->alias ) ) ? $this->secondaryKey_table : $this->alias ;
    }

    public function addJoin( $prim, $secon = false , $hardwired = '' , $op = '=' , $primaryOveride = false  ) {
        if ( ! $secon ) $secon = $prim ;
        $stbl = $this->retSecondaryKeyTable() ;
        if ( ! $this->multiple ) $this->multiple[] = "{$this->primaryKey_table}.`{$this->primaryKey}` = $stbl.`{$this->secondaryKey}`" ;
        if ( $hardwired ) {
            $this->multiple[] = "$stbl.`$secon` $op '$hardwired'" ;
        } else {
            $tbl = ( $primaryOveride ) ? $primaryOveride : $this->primaryKey_table ;
            $this->multiple[] = "$tbl.`$prim` $op $stbl.`$secon`";
        }
    }

    public function addJoinPrimaryOveride( $prim , $secon , $overide , $op = '=' ) {
        $this->addJoin( $prim , $secon , '' , $op , $overide ) ;
    }

    /*
     * @todo addHard - looks broken, when invoked overrides initial part of join. Check any usages to make sure nothing breaks when fixing
     * Have made fix check still needs to be done
     */
    function addHard( $secon , $hardwired , $op = '=' , $quoted = "'" ) {
        $stbl = $this->retSecondaryKeyTable() ;
        $this->multiple() ;
        $this->multiple[] = "$stbl.`$secon` $op {$quoted}$hardwired{$quoted}" ;
    }

    function multiple() {
        if ( ! $this->multiple ) {
            $stbl = $this->retSecondaryKeyTable() ;
            if ( ! $this->primaryKey_table && ! $this->primaryKey ) return ;
            $this->multiple[] = "{$this->primaryKey_table}.`{$this->primaryKey}` = $stbl.`{$this->secondaryKey}`" ;
        }
    }

    public function addJoinCalc ( $calc ) {
//        $stbl = ( empty ( $this->alias ) ) ? $this->secondaryKey_table : $this->alias ;
//        if ( ! $this->multiple ) $this->multiple[] = "{$this->primaryKey_table}.`{$this->primaryKey}` = $stbl.`{$this->secondaryKey}`" ;
        $this->multiple[] = $calc ;
    }

    function build() {
        if ( empty ( $this->alias ) ) {
            $stbl = $this->secondaryKey_table ;
            $alias = '' ;
        } else {
            $stbl = $this->alias ;
            $alias = 'as ' . $this->alias ;
        }
        $val = ( $this->multiple ) ? join( ' and ' , $this->multiple ) : "{$this->primaryKey_table}.`{$this->primaryKey}` = $stbl.`{$this->secondaryKey}`" ;
        return "{$this->type} join {$this->secondaryKey_table} $alias on $val" ;
    }
}

class orderBys {

    function addOrderBy( $fld , $sort = 'ASC' , $table = false ) {
        $this->{$fld} = new orderby( $fld , $sort ) ;
        if ( $table ) $this->{$fld}->table = $table ;
        return $this->{$fld} ;
    }

    function build(  ) {
        $pass = false ;
        foreach ( $this as $key => $fld ) {
            $pass = true ;
            @ $temp[] = $fld->build() ;
        }
        return ( $pass ) ? "\n order by " . join ( ',' , $temp ) : '' ;
    }
}

class orderBy {
    var $table ;
    var $isCalc ;

    function __construct ( $fld , $sort = 'ASC' ) {
        $this->fld = $fld ;
        $this->sort = $sort ;
    }

    function build() {
        $tbl = ( $this->table ) ? $this->table . '.' : '' ;
        $val = ( $this->isCalc ) ? $this->fld : "`{$this->fld}`" ;
        return ( strtolower( $this->sort ) == "desc" ) ? "$tbl{$val} DESC" : "$tbl{$val}" ;
    }
}

class groupParse {
    private $total ;

    function &add( $key , $row ) {
        if ( ! @ $this->{$key . '.' . $row->{$key}} ) $this->{$key . '.' . $row->{$key}} = new groupParse ;
        $this->{$key . '.' . $row->{$key}}->total = $row ;
        return $this->{$key . '.' . $row->{$key}} ;
    }

    function addTotal( $row ) {
        $this->total = $row ;
    }

    function getTotal ( $fld = '' , $curr = '' ) {
        if ( $fld ) {
            $t  = $this->total->{$fld} ;
        } else {
            $t = $this->total ;
        }
        return ( $curr ) ? $curr . number_format ( (float) $t , 2 ) : $t ;
    }

    function getData ( $fld = '' , $curr = '' ) {
        return $this->getTotal( $fld , $curr ) ;
    }

    function percentDiff( $orig , $compTo , $rev = false ) {
        $perc = round( ( ( $this->getData( $orig ) - $this->getData( $compTo ) ) / $this->getData( $orig ) ) * 100 , 2 ) . '%';
        return ( $rev ) ? round( 100 - $perc , 2 ) . '%' : $perc ;
    }

    function elementTotal ( $mkey , $fld , $curr = '' ) {
        $sum = array() ;
        foreach ( $this as $key => $obj ) {
            if ( $key != 'total' ) {
                if ( @ $obj->{$mkey} ) $sum[] = $obj->{$mkey}->getTotal( $fld ) ;
            }
        }
        return ( $curr ) ? $curr . number_format ( (float) array_sum ( $sum ) , 2 ) : array_sum ( $sum ) ;
    }
}

class group {
    var $groups = array();
    var $rollup ;
    var $parseData;

    function __construct() {
        $this->parseData = new groupParse ;
    }

    function addGroup( $fld , $table ) {
        $this->groups[$fld] = new groupby( $fld , $table ) ;
        return $this->groups[$fld] ;
    }

    function build() {
        if ( $this->groups ) {
            foreach ( $this->groups as $key => $group ) {
                $temp[] = $group->build() ;
            }
            return "\n group by " . join ( ',' , $temp ) . ( ( $this->rollup ) ? ' with rollup ' : '' ) ;
        } else {
            return '' ;
        }
    }

    function parse( $row ) {
        $notProcessed = true ;
        $gp = $this->parseData ;
        foreach ( $this->groups as $key => $group ) {
            if ( @ $row->{$key} ) {
                $notProcessed = false ;
                $gp = $gp->add( $key , $row ) ;
            }
        }
        if ( $notProcessed ) $this->parseData->addTotal( $row ) ;
    }
}

class groupby {
    var $table ;
    var $fld ;

    function __construct( $fld , $table ) {
        $this->table = $table ;
        $this->fld = $fld ;
    }

    function build() {
        $tbl = ( $this->table ) ? $this->table . '.' : '' ;
        return "{$tbl}`{$this->fld}`" ;
    }
}
