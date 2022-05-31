# QuerySystem
QuerySystem is the Last Version Of QueryHandler.php - By PHP

<hr>

## this Library will help you to connect MySQL and execute a lot of operations in specified database
## All Methodes are static .... so you don't need to create an object from QuerySystem class
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
    this methode will delete a row from table and return boolean value  , where :
    @table : is table name
    @id : is the id of row
    
    @option array : with this array you can specify the execution mode ... it contains :
    "NoExecution_retuurnSQLString"  : is a logical value key  if it is true the query will be executed ...
     if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed)
    Ex $options = array("NoExecution_retuurnSQLString" => false)
    
    Note : don't forget to open connection before use this method 
    
#### 7 - removeWhere(string $table , Array $conditionsArray , $options = array())
    this methode will delete a row from table and return boolean value  , where :
    @table : is table name
    @id : is the id of row
    
    @conditionsArray : is an indexed array that contains the conditions of deletion operation (dont't write "where , and , or" .. just write the conditions)
    Ex : $conditionsArray = array("id = 7 " , "Username = 'user123'")
    
    @option array : with this array you can specify the execution mode ... it contains :
    1- "NoExecution_retuurnSQLString"  : is a logical value key  if it is true the query will be executed ...
     if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed)
    2- "limit" : is a numeric value key ... it mean how many rows will be deleted .. default value null
    Ex $options = array("NoExecution_retuurnSQLString" => false , "limit" => 2)
    
    Note : don't forget to open connection before use this method 
    
#### 8 - removeBySqlStatement($statement)
    this methode will delete a row from table and return boolean value  , where :
    @statement is a sql delete statement 
    Note : don't forget to open connection before use this method 
<hr> 

### Updating Methodes 

#### 9 - updateByValueAssocArray($table , $ColumnsValuesArray , $conditionsArray = array() , $options = array())
    this methode will update a row in table and return boolean value  , where :
    @table : is table name
    
    @ColumnsValuesArray : is an associative array that contains columns and its new values
    Ex $userNewValues = array("Username" => "User123" , "password" => 224422)
    
    @conditionsArray : is an indexed array that contains the conditions of deletion operation (dont't write "where , and , or" .. just write the conditions)
    Ex : $conditionsArray = array("id = 7 " , "Username = 'user123'")
    
    @option array : with this array you can specify the execution mode ... it contains :
    1- "NoExecution_retuurnSQLString"  : is a logical value key  if it is true the query will be executed ...
        if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed)
    2- "limit" : is a numeric value key ... it mean how many rows will be deleted .. default value null
    Ex $options = array("NoExecution_retuurnSQLString" => false , "limit" => 2)
    
    Note : don't forget to open connection before use this method 
    
#### 10 - updateBySqlStatement($statement)
    this methode will update a row from table and return boolean value  , where :
    @statement is a sql update statement 
    Note : don't forget to open connection before use this method 

<hr>

### selection Methodes

#### 11 - getRowWhereId($table , $id , $options = array())
    this methode will get a row's all columns from table and return its value in asociative array  , where :
    @table : is table name
    
    @id : is id of row
    
    @option array : with this array you can specify the execution mode ... it contains :
    1- "NoExecution_retuurnSQLString"  : is a logical value key  if it is true the query will be executed ...
         if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed)  
    Ex $options = array("NoExecution_retuurnSQLString" => false  )
    
    Note : don't forget to open connection before use this method 
    
#### 12 - getRowColumnsWhereId($table , $columnsArray , $id , $options = array())
    this methode  will return the information of some fields for the row (( By passing Id of row)), where :
    @table : is table name
    
    @columnsArray : is an indexed array that contains columns that you want t get its values
    Ex $user = array("Username"  ,"password" )
    
    @id : is the id of row
    
    @option array : with this array you can specify the execution mode ... it contains :
    1- "NoExecution_retuurnSQLString"  : is a logical value key  if it is true the query will be executed ...
        if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed)  
    Ex $options = array("NoExecution_retuurnSQLString" => false)
    
    Note : don't forget to open connection before use this method
    
#### 13 - getRowColumnsWhereConditions($table , $columnsArray = array("*"), $conditionsArray , $options = array())
    this methode  will return the information of some fields for the row (( By passing selection conditions)), where :
    @table : is table name
    
    @columnsArray : is an indexed array that contains columns that you want t get its values
    Ex $user = array("Username"  ,"password" )
    
    @conditionsArray : is an indexed array that contains the conditions of selection operation (dont't write "where , and , or" .. just write the conditions)
    Ex : $conditionsArray = array("id = 7 " , "Username = 'user123'")
    
    @option array : with this array you can specify the execution mode ... it contains :
    1- "NoExecution_retuurnSQLString"  : is a logical value key  if it is true the query will be executed ...
         if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed)  
    Ex $options = array("NoExecution_retuurnSQLString" => false  )
    
    Note : don't forget to open connection before use this method 
    
#### 14 - getRowBySqlStatement($statement)
    this methode will select a single row from table and return it in associative array , where :
    @statement is a sql select statement  (( for one row))
    Note : don't forget to open connection before use this method 
    
#### 15 - getRowsColumnsWhereConditions($table , $columnsArray = array("*") , $conditionsArray = array() , $options = array() )
    this methode  will return the information of some fields for the rows (( by selection conditions)), where :
    @table : is table name
    
    @columnsArray : is an indexed array that contains columns that you want t get its values
    Ex $user = array("Username"  ,"password" )
    
    @conditionsArray : is an indexed array that contains the conditions of selection operation (dont't write "where , and , or" .. just write the conditions)
    Ex : $conditionsArray = array("id = 7 " , "Username = 'user123'")
    
    @options array : with this array you can specify the execution mode ... it contains :
    1- "NoExecution_retuurnSQLString"  : is a logical value key  if it is true the query will be executed ...
         if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed) 
    2- "limit" : is a numeric value key ... it mean how many rows will be selected .. default value is null
    3- "offset" : is a numeric value key ... it mean From what record do you start counting?  .. default value is null
    Ex $options = array("NoExecution_retuurnSQLString" => false , "limit" => 2 , "offset" => 0)
    
    Note : don't forget to open connection before use this method 
    
#### 16 - getRowsBySqlStatement($statement)
    this methode will select a list of rows from table and return it in Multi Dimensional associative array , where :
    @statement is a sql select statement 
    Note : don't forget to open connection before use this method 
    
#### 17 - JoinByStatement($statement)
    this methode will select a list of rows from multi tables and return it in Multi Dimensional associative array , where :
    @statement is a sql select statement (join statement)
    Note : don't forget to open connection before use this method 
    
#### 18 - innerJoinByValues(  Array $tableAndColumnsOfEachTableAssocArray , Array $JoinConditions , Array $whereConditions = array() , $options = array( ))
    this methode  will return the rows that you selected it in join statement , where :
    
    @tableAndColumnsOfEachTableAssocArray : is an associative array that contains All tables to be entered into the join process
    AND each table key's value must be an indexed array that contains all columns that wanted from that table
    EX : $tableAndColumnsOfEachTableAssocArray = array("users" => array("FirstName" , "LastName") , "posts" => array("title" , "content"))
    
    @JoinConditions : is an indexde array that contains all join conditions .... it must not be null
    EX : $JoinConditions = array("users.id = posts.UserId" , "posts.id = comment.PostId"); (don't write and , or in connditions ... it is by "and" by default)
    
    @whereConditions : is an indexed array that contains where conditions that will be applied after join operation is done
    Ex : $whereConditions = array("users.city = 'Istanbul'" , "posts.created_at > '2011-11-13'");
    
    @options array : with this array you can specify the execution mode ... it contains :
    1- "NoExecution_retuurnSQLString"  : is a logical value key  if it is true the query will be executed ...
         if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed) 
    2- "limit" : is a numeric value key ... it mean how many rows will be selected .. default value is null
    3- "offset" : is a numeric value key ... it mean From what record do you start counting?  .. default value is null
    Ex $options = array("NoExecution_retuurnSQLString" => false , "limit" => 2 , "offset" => 0)
    
    Note : don't forget to open connection before use this method 
    
#### 19 - JoinByValues( Array $tableAndColumnsOfEachTableAssocArray , string $LeftTableName , Array $RightTableName_joinType_Array , Array $table_joinConsitions , Array $whereConditions = array() ,  $options = array())
    this method will handle a join sql statment (inner or left or right or all of them) ... it just need :
    @tableAndColumnsOfEachTableAssocArray : is an associative array that contains All tables to be entered into the join process
        AND each table key's value must be an indexed array that contains all columns that wanted from that table
    EX : $tableAndColumnsOfEachTableAssocArray = array("users" => array("FirstName" , "LastName") , "posts" => array("title" , "content"))
    
    @LeftTableName : is the left table name .... that will be joined with other tables (right tables)
    Ex  : $LeftTableName = "users"; ===result will be===> "users inner join otherTable on joinCondition inner join anOtherTable on anOtherJoinCondition"
    
    @RightTableName_joinType_Array : is an Associative array that contains all tables these will be joined with leftTable .... it must not be null
        each table is key , and each key 's value must be a join type like "inner" or "left" or "right"
    EX : $RightTableName_joinType_Array = array("profile" => "inner" , "posts" => "left" , "comments" => "left"); (don't write 'join' in  join type)
    
    @table_joinConsitions : is an Associative array that contains all tables these will be joined with leftTable .... it must not be null
        each table is key , and each key 's value must be join condition
    Ex : $table_joinConsitions = array("posts" => "users.id = posts.UserId" , "comments" => "posts.id = comments.PostId" ); 
        (don't write and , or in connditions ... it is by "and" by default)
    
    @whereConditions : is an indexed array that contains where conditions that will be applied after join operation is done
    Ex : $whereConditions = array("users.city = 'Istanbul'" , "posts.created_at > '2011-11-13'");
    
    @options array : with this array you can specify the execution mode ... it contains :
    1- "NoExecution_retuurnSQLString"  : is a logical value key  if it is true the query will be executed ...
        if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed) 
    2- "limit" : is a numeric value key ... it mean how many rows will be selected .. default value is null
    3- "offset" : is a numeric value key ... it mean From what record do you start counting?  .. default value is null
    Ex $options = array("NoExecution_retuurnSQLString" => false , "limit" => 2 , "offset" => 0)
    
#### 20 - unionByValues( Array $ArrayOfSelectionQueries , $unionAll = false)
    this method will handle a union sql statment ... it just need :
    @ArrayOfSelectionQueries : is an indexed array that contains all selection statements these will be integrated in a one table
    EX : $ArrayOfSelectionQueries = array("select email from users" , "select email from customers");
    
    you can use previous methodes to return a sql statements
    Ex : $arrayOfSelections = array(
               QuerySystem::JoinByValues(
               array("users" => array("Username") , "posts" => array("title" , "content") , "comments" => array("comment"))  ,
               "users" , array("posts" => "left" , "comments" => "left") ,
               array("posts" => "users.id = posts.UserId" , "comments" => "posts.id = comments.PostId" ) ,
               array("users.id > 12")  ,  array("NoExecution_retuurnSQLString" => true) ) , 
               
               QuerySystem::JoinByValues( array("users" => array("Username") , "posts" => array("title" , "content") , "comments" => array("comment"))  , 
               "users" , array("posts" => "right" , "comments" => "right") , 
               array("posts" => "users.id = posts.UserId" , "comments" => "posts.id = comments.PostId" ) ,
               array("users.id > 12")  ,  array("NoExecution_retuurnSQLString" => true) ) , 
        );
          QuerySystem::unionByValues( $arrayOfSelections)
    
    if @unionAll is false the values will be unique (No redundancy there in value)
    
### Methodes to check if item or item found or Not ... Return bollean value 
#### 21 - isFoundWhereConditions($table , $conditionsArray)
    this method will return true if it found a row in table where : 
    @table : table name
    @conditionsArray : is an indexed array that contains the conditions of selection operation (dont't write "where , and , or" .. just write the conditions)
    Ex : $conditionsArray = array("id = 7 " , "Username = 'user123'")

### Database or table information getting Methodes

#### 22 - IsTableHasColumn($dbName , $table , $column)
    this method will handle a  query to check if table has a specified field or not and return true or false ... it just need :
    @dbName : database name
    @table : table name
    @column : column or field name
