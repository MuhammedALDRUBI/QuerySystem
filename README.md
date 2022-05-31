# QuerySystem
QuerySystem is the Last Version Of QueryHandler.php - By PHP

<hr>

## this Library will help you to connect MySQL and execute a lot of operations in specified database
<hr> 

## Usable Static Methodes:

#### 1 - openConnection($Host , $DBName , $DBUserName , $DBUserPassword) 
    this function is used to open the connection with ((Any Database)) 
    Dont't forget to close connection before connection to an other database
    $Host : Host name (string value)
    $DBName : Database name (string value)
    $DBUserName : username that you will use it to control Database in MySql System
    $DBUserPassword : must be compatible with username
    
#### 2 - closeConnection()
     this method is used to close the connection
     
#### 3 - custumizeQuery()
    this function used to return a QueryCustomizer object .... it can help you to customize a query string
    but it isn't recommonded to use it ..... use this library's methodes (QuerySystem Methodes) instead.
<hr>

### insertion Methodes
####  4 - insertByValuesArray($table ,   $ColumnsAndValuesArray , $options = array())
    with this method you can insert row and return boolean value where :
    @table : is table name
    @ColumnsAndValuesArray : is an associative array of values that you want to insert 
    
    @option array : with this array you can specify the execution mode ... it contains :
    "NoExecution_retuurnSQLString"  : is a logical valued key  if it is true the query will be executed ...
    if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed)
    Ex $options = array("NoExecution_retuurnSQLString" => false)
    
    Note : don't forget to open connection before use this method 
    
####  5 - insertBySqlStatement($statement)
    with this method you can insert a new row and return boolean value , where :
    @statement is a sql insert statement 
    Note : don't forget to open connection before use this method

<hr>

### Deletion Methodes
####  6 - removeWhereId($table , $id , $options = array())
#### 7 - removeWhere(string $table , Array $conditionsArray , $options = array())
#### 8 - removeBySqlStatement($statement)

<hr> 

### Updating Methodes 
#### 9 - updateByValueAssocArray($table , $ColumnsValuesArray , $conditionsArray = array() , $options = array())
#### 10 - updateBySqlStatement($statement)

<hr>

### selection Methodes
#### 11 - getRowWhereId($table , $id , $options = array())
#### 12 - getRowColumnsWhereId($table , $columnsArray , $id , $options = array())
#### 13 - getRowColumnsWhereConditions($table , $columnsArray = array("*"), $conditionsArray , $options = array())
#### 14 - getRowBySqlStatement($statement)
#### 15 - getRowsColumnsWhereConditions($table , $columnsArray = array("*") , $conditionsArray = array() , $options = array() )
#### 16 - getRowsBySqlStatement($statement)
#### 17 - JoinByStatement($statement)
#### 18 - innerJoinByValues(  Array $tableAndColumnsOfEachTableAssocArray , Array $JoinConditions , Array $whereConditions = array() , $options = array( ))
#### 19 - JoinByValues( Array $tableAndColumnsOfEachTableAssocArray , string $LeftTableName , Array $RightTableName_joinType_Array , Array $table_joinConsitions , Array $whereConditions = array() ,  $options = array())
#### 20 - unionByValues( Array $ArrayOfSelectionQueries , $unionAll = false)

### 
#### 21 - IsTableHasColumn($dbName , $table , $column)

### Database or table information getting Methodes
#### 22 - IsTableHasColumn($dbName , $table , $column)
