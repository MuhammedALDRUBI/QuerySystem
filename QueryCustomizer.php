<?php


class QueryCustomizer{

    private string $queryString ;
    private ArrayAnalyzer $ArrayAnalyzerObject ;
    
    ///////////////////////////////////
    //this method will create a new object from ArrayAnalyzer class to use it in arrays analyzing operations
    ///////////////////////////////////
    public function __construct() 
    {  
             $this->ArrayAnalyzerObject = new ArrayAnalyzer();
            return $this;
    }
  
    ///////////////////////////////////
    //this method will handle an insert sql statment ... it just need :
    //@table : table name that you want to insert row into
    //
    //@ColumnsAndValuesArray : is an associative array that contains the row 's columns and its columns values
    //Ex : $ColumnsAndValuesArray = array("Username" => "user123" , "Password" => "224422");
    ///////////////////////////////////
    public function insertQueryCustomizer( string $table , Array $ColumnsAndValuesArray) : string
    { 
        $columnsArray = array_keys($ColumnsAndValuesArray);
        $ValuesArray = array_values($ColumnsAndValuesArray);
        $this->queryString = "insert into $table( "; 
        $this->queryString .=  $this->ArrayAnalyzerObject->indexedArrayPrinter($columnsArray ,   " " ,   " , " , false);  
        $this->queryString .= " )  Values( ";
        $this->queryString .=  $this->ArrayAnalyzerObject->indexedArrayPrinter($ValuesArray ,  " " ,   " , " );   
        $this->queryString .= " ) ;";
        return $this->queryString; 
    }
 
    ///////////////////////////////////
    //this method will handle a delete sql statment ... it just need :
    //@table : table name that you want to delete row from
    //
    //@conditionsArray : is an indexed array that contains deletion conditions
    // it is nullable array BUT if it there no deletion's conditions ALL rows will be deleted
    //Ex : $conditionsArray = array("Id = 4");
    ///////////////////////////////////
    public function deleteQueryCustomizer( string $table , Array $conditionsArray = array()  , $options = array()  ) : string
    { 
        $options["limit"] = !isset($options["limit"]) ? null : $options["limit"]; 
        $countOfConditions = count($conditionsArray); 
        $this->queryString = "delete from $table ";  
        //Note : if no conditions are there ... all records will be deleted
        if($countOfConditions == 0){ $this->queryString .= $options["limit"] != null ? " limit " . $options['limit']   : "";  return $this->queryString; }
        $this->queryString .= " where " .  $this->ArrayAnalyzerObject->indexedArrayPrinter($conditionsArray , " " ,  " and " , false);  
        $this->queryString .= $options["limit"] != null ? " limit " . $options['limit']   : ""; 
        return $this->queryString; 
    }
    
    ///////////////////////////////////
    //this method will handle a update sql statment ... it just need :
    //@table : table name that contains rows that you want to update it
    //
    //@ColumnsValuesArray : is an associative array that contains the row 's columns and its columns values
    //
    //@conditionsArray : is an indexed array that contains updating conditions
    // it is nullable array BUT if it there no updating's conditions ALL rows will be updated
    //Ex : $conditionsArray = array("Id = 4");
    ///////////////////////////////////
    public function updateQueryCustomizer( string $table , Array $ColumnsValuesArray , Array $conditionsArray = array() , $options = array() ) : string
    {  
        $options["limit"] = !isset($options["limit"]) ? null : $options["limit"]; 
        $this->queryString = "update $table set ";
        $this->queryString .= $this->ArrayAnalyzerObject->AssocArrayPrinter($ColumnsValuesArray , " " , " = " , " , "); 

        //Note : if no conditions are there ... all records will be deleted
        $countOfConditions = count($conditionsArray); 
        if($countOfConditions == 0){$this->queryString .= $options["limit"] != null ? " limit " . $options['limit']   : "";  return $this->queryString; }
        $this->queryString .= " where " . $this->ArrayAnalyzerObject->indexedArrayPrinter($conditionsArray , " " ,  " and " , false);  
        $this->queryString .= $options["limit"] != null ? " limit " . $options['limit']   : ""; 
        return $this->queryString; 
    }

    ///////////////////////////////////
    //this method will handle a select sql statment ... it just need :
    //@table : table name that you want to select rows from
    //
    //@columnsArray : is an indexed array that contains the columns these you want to select it from table
    //Ex : $columnsArray = array("FirstName" , "LastName" , "Email");
    //
    //@conditionsArray : is an indexed array that contains selection conditions
    // it is nullable array BUT if it there no selection's conditions ALL rows will be selected
    //Ex : $conditionsArray = array("Id = 4");
    ///////////////////////////////////
    public function selectQueryCustomizer( string $table , Array $columnsArray = array(), Array $conditionsArray = array() ,  $options = array() )  : string
    { 
        $options["limit"] = !isset($options["limit"]) ? null : $options["limit"]; 
        $options["offset"] = !isset($options["offset"]) ? null : $options["offset"]; 
        if(count($columnsArray) == 0 ){$columnsArray[] =  "*"; }
        $this->queryString = "select ";
        $this->queryString .= $this->ArrayAnalyzerObject->indexedArrayPrinter($columnsArray , " " ,  " , " , false);  
        $this->queryString .= " from $table ";  

        //Note : if no conditions are there ... all records will be deleted
        $countOfConditions = count($conditionsArray); 
        if($countOfConditions == 0){
            $this->queryString .= $options["limit"] != null ? " limit " . $options['limit']   : ""; 
            $this->queryString .= $options["offset"] != null ? " offset " . $options['offset']  : ""; 
            return $this->queryString; 
        }
        $this->queryString .= " where " . $this->ArrayAnalyzerObject->indexedArrayPrinter($conditionsArray , " " ,  " and " , false); 
        $this->queryString .= $options["limit"] != null ? " limit " . $options['limit']   : ""; 
        $this->queryString .= $options["offset"] != null ? " offset " . $options['offset']  : ""; 
        return $this->queryString; 
    }


    ///////////////////////////////////
    //this method will handle an inner join sql statment ... it just need :
    //@tableAndColumnsOfEachTableAssocArray : is an associative array that contains All tables to be entered into the join process
    //AND each table key's value must be an indexed array that contains all columns that wanted from that table
    //EX : $tableAndColumnsOfEachTableAssocArray = array("users" => array("FirstName" , "LastName") , "posts" => array("title" , "content"))
    //
    //@JoinConditions : is an indexde array that contains all join conditions .... it must not be null
    //EX : $JoinConditions = array("users.id = posts.UserId" , "posts.id = comment.PostId"); (don't write and , or in connditions ... it is by "and" by default)
    //
    //@whereConditions : is an indexed array that contains where conditions that will be applied after join operation is done
    //Ex : $whereConditions = array("users.city = 'Istanbul'" , "posts.created_at > '2011-11-13'");
    ///////////////////////////////////
    public function innerJoinQueryCustomizer( Array $tableAndColumnsOfEachTableAssocArray , Array $JoinConditions , Array $whereConditions = array() , $options = array() ) : string
    {  
        $options["limit"] = !isset($options["limit"]) ? null : $options["limit"]; 
        $options["offset"] = !isset($options["offset"]) ? null : $options["offset"];
        $tableArray = array_keys($tableAndColumnsOfEachTableAssocArray);
        $this->queryString = " select ";
        $this->queryString .=   $this->ArrayAnalyzerObject->AssocArrayPrinter($tableAndColumnsOfEachTableAssocArray ,   $keyPrefix = " " , $textBetween = "." , $valSuffix = " , " , false);
        $this->queryString .= " from " . $this->ArrayAnalyzerObject->indexedArrayPrinter($tableArray , " " ,  " inner join " , false);  
        $this->queryString .= " on " . $this->ArrayAnalyzerObject->indexedArrayPrinter($JoinConditions , " " ,  " and " , false);  

        $countOfConditions = count($whereConditions); 
        if($countOfConditions == 0){
            $this->queryString .= $options["limit"] != null ? " limit " . $options['limit']   : ""; 
            $this->queryString .= $options["offset"] != null ? " offset " . $options['offset']  : "";
            return $this->queryString; 
        }
        $this->queryString .= " where " . $this->ArrayAnalyzerObject->indexedArrayPrinter($whereConditions , " " ,  " and " , false);  
        $this->queryString .= $options["limit"] != null ? " limit " . $options['limit']   : ""; 
        $this->queryString .= $options["offset"] != null ? " offset " . $options['offset']  : "";
        return  $this->queryString ;
    }
    
    ///////////////////////////////////
    //this method will handle a join sql statment (inner or left or right or all of them) ... it just need :
    //@tableAndColumnsOfEachTableAssocArray : is an associative array that contains All tables to be entered into the join process
    //AND each table key's value must be an indexed array that contains all columns that wanted from that table
    //EX : $tableAndColumnsOfEachTableAssocArray = array("users" => array("FirstName" , "LastName") , "posts" => array("title" , "content"))
    //
    //@LeftTableName : is the left table name .... that will be joined with other tables (right tables)
    //Ex  : $LeftTableName = "users"; ===result will be===> "users inner join otherTable on joinCondition inner join anOtherTable on anOtherJoinCondition"
    //
    //@RightTableName_joinType_Array : is an Associative array that contains all tables these will be joined with leftTable .... it must not be null
    // each table is key , and each key 's value must be a join type like "inner" or "left" or "right"
    //EX : $RightTableName_joinType_Array = array("profile" => "inner" , "posts" => "left" , "comments" => "left"); (don't write 'join' in  join type)
    //
    //@table_joinConsitions : is an Associative array that contains all tables these will be joined with leftTable .... it must not be null
    // each table is key , and each key 's value must be join condition
    //Ex : $table_joinConsitions = array("posts" => "users.id = posts.UserId" , "comments" => "posts.id = comments.PostId" ); (don't write and , or in connditions ... it is by "and" by default)
    //
    //@whereConditions : is an indexed array that contains where conditions that will be applied after join operation is done
    //Ex : $whereConditions = array("users.city = 'Istanbul'" , "posts.created_at > '2011-11-13'");
    ///////////////////////////////////
    public function complexJoinQueryCustomizer( Array $tableAndColumnsOfEachTableAssocArray , string $LeftTableName ,  Array $RightTableName_joinType_Array , Array $table_joinConsitions , Array $whereConditions = array() ,  $options = array() ) : string
    {   
        $options["limit"] = !isset($options["limit"]) ? null : $options["limit"]; 
        $options["offset"] = !isset($options["offset"]) ? null : $options["offset"];
        $tableArray = array_keys($tableAndColumnsOfEachTableAssocArray);
        $countOfTables = count($tableArray);
        $this->queryString = " select ";
        $this->queryString .=   $this->ArrayAnalyzerObject->AssocArrayPrinter($tableAndColumnsOfEachTableAssocArray ,   $keyPrefix = " " , $textBetween = "." , $valSuffix = " , " , false);
        $this->queryString .= " from  $LeftTableName " ;

        foreach($RightTableName_joinType_Array as $table => $joinType){
            $this->queryString .= " $joinType join $table on  $table_joinConsitions[$table] ";
        }           
        $countOfConditions = count($whereConditions); 
        if($countOfConditions == 0){
            $this->queryString .= $options["limit"] != null ? " limit " . $options['limit']   : ""; 
            $this->queryString .= $options["offset"] != null ? " offset " . $options['offset']  : "";
            return $this->queryString; 
        }
        $this->queryString .= " where " . $this->ArrayAnalyzerObject->indexedArrayPrinter($whereConditions , " " ,  " and " , false);  
        $this->queryString .= $options["limit"] != null ? " limit " . $options['limit']   : ""; 
        $this->queryString .= $options["offset"] != null ? " offset " . $options['offset']  : "";
        return  $this->queryString ;
    }

    ///////////////////////////////////
    //this method will handle a union sql statment ... it just need :
    //@ArrayOfSelectionQueries : is an indexed array that contains all selection statements these will be integrated in a one table
    //EX : $ArrayOfSelectionQueries = array("select email from users" , select email from customers");
    //
    //if @unionAll is false the values will be unique (No redundancy there in value)
    ///////////////////////////////////
    public function unionQueryCustomizer( Array $ArrayOfSelectionQueries , $unionAll = false) : string
    {  
        $textAfterValue = $unionAll ? " union all " : " union ";
        $this->queryString = $this->ArrayAnalyzerObject->indexedArrayPrinter($ArrayOfSelectionQueries , " " ,  " $textAfterValue " , false) ;
        return  $this->queryString ;
    }

    ///////////////////////////////////
    //this method will handle a  query to check if table has a specified field or not ... it just need :
    //@dbName : database name
    //@table : table name
    //@column : column or field name
    ///////////////////////////////////
    public function isColumnFoundQueryCustomizer( string $dbName , string $table , string $column) : string
    {
        $dbName = '"' . $dbName . '"';
        $table = '"' . $table . '"';
        $column = '"' . $column . '"';
        return $this->queryString = "select count(COLUMN_NAME) as count from information_schema.COLUMNS where TABLE_SCHEMA = $dbName and  TABLE_NAME =  $table  and information_schema.COLUMNS.COLUMN_NAME =  $column ";
    }
}