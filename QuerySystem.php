<?php

class QuerySystem{

    static private  $connectionObject = null;
    static private  $QueryCustomizerObject = null;
  
    ///////////////////////////////////
    //this function is used to open the connection with ((Any Database)) 
    // Dont't forget to close connection before connection to an other database
    ///////////////////////////////////
    static public function openConnection($Host , $DBName , $DBUserName , $DBUserPassword)
    {
        try{
            $dns = "mysql:host=" . $Host . ";dbname=" . $DBName .";charset=utf8";
            self::$connectionObject = new PDO($dns , $DBUserName , $DBUserPassword);
            self::$connectionObject->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            return self::$connectionObject;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    ///////////////////////////////////
    //this method is used to close the connection
    ///////////////////////////////////
    static public function closeConnection()
    {
        self::$connectionObject = null;
        return true;
    }

    ///////////////////////////////////
    //this function used to return a QueryCustomizer object .... it can help you to customize a query string
    // but it isn't recommonded to use it ..... use this library's methodes (QuerySystem Methodes) instead.
    ///////////////////////////////////
    static public function custumizeQuery()
    { 
        self::$QueryCustomizerObject = new QueryCustomizer(); 
        return self::$QueryCustomizerObject;
    } 

    ///////////////////////////////////
    //this function used to return a PDOStatement object .... it can execute , executeReader , excuteReaderAll Methdes to work
    //Don't use it ... private methode
    ///////////////////////////////////
    static private function getPDOStatementOb($queryString)
    {
        $queryStatment = self::$connectionObject->prepare($queryString);
        $queryStatment->execute();
        return $queryStatment;
    }

    ///////////////////////////////////
    //this function used to return true or false after (insert , update ,delete) queries is executed
    //Don't use it ... private methode
    ///////////////////////////////////
    static private function execute($queryString)
    {
        $executionResult = self::getPDOStatementOb($queryString)->rowCount();
        return  $executionResult != 0 ? true : false;   
    }

    ///////////////////////////////////
    //this function used to return a Multi Dimensional indexed array after (select More than one row) queries is executed
    //Don't use it ... private methode
    ///////////////////////////////////
    static private function executeReaderAll($queryString)
    {
        $PDOStatementOb = self::getPDOStatementOb($queryString);
        return  $PDOStatementOb->rowCount() != 0 ? $PDOStatementOb->fetchAll(PDO::FETCH_ASSOC) : null; 
    }

    ///////////////////////////////////
    //this function used to return a one Dimensional indexed array after (select one row) queries is executed
    //Don't use it ... private methode
    ///////////////////////////////////
    static private function executeReader($queryString)
    { 
        $PDOStatementOb = self::getPDOStatementOb($queryString);
        return  $PDOStatementOb->rowCount() != 0 ? $PDOStatementOb->fetch(PDO::FETCH_ASSOC) : null; 
    }

    //==============================================================================
    //insertion Methodes
    //==============================================================================

    /////////////////////////////////// 
    //with this method you can insert row and return boolean value where :
    //@table : is table name
    //@ColumnsAndValuesArray : is an associative array of values that you want to insert 
    //
     //@option array : with this array you can specify the execution mode ... it contains :
    //"NoExecution_retuurnSQLString"  : is a logical valued key  if it is true the query will be executed ...
    // if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed)
    //Ex $options = array("NoExecution_retuurnSQLString" => false)
    //
    //Note : don't forget to open connection before use this method 
    ///////////////////////////////////
    static public function insertByValuesArray($table ,   $ColumnsAndValuesArray , $options = array())
    { 
        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            } 
            $queryCustomizingResult = self::custumizeQuery()->insertQueryCustomizer( $table , $ColumnsAndValuesArray);
            $options["NoExecution_retuurnSQLString"] = !isset($options["NoExecution_retuurnSQLString"]) ? false : $options["NoExecution_retuurnSQLString"];
            if(!$options["NoExecution_retuurnSQLString"]){
                return self::execute($queryCustomizingResult); 
            }
            return $queryCustomizingResult;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    ///////////////////////////////////
    //with this method you can insert a new row and return boolean value , where :
    //@statement is a sql insert statement 
    //Note : don't forget to open connection before use this method
    ///////////////////////////////////
    static public function insertBySqlStatement($statement)
    {
        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            }
            return self::execute($statement); 
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    //==============================================================================
    //deletion  Methodes
    //==============================================================================

    ///////////////////////////////////
    //this methode will delete a row from table and return boolean value  , where :
    //@table : is table name
    //@id : is the id of row
    //
    //@option array : with this array you can specify the execution mode ... it contains :
    //"NoExecution_retuurnSQLString"  : is a logical value key  if it is true the query will be executed ...
    // if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed)
    //Ex $options = array("NoExecution_retuurnSQLString" => false)
    //
    //Note : don't forget to open connection before use this method 
    ///////////////////////////////////
    static public function removeWhereId($table , $id , $options = array())
    {
        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            } 
            $queryCustomizingResult = self::custumizeQuery()->deleteQueryCustomizer($table , array("id = $id"));
            $options["NoExecution_retuurnSQLString"] = !isset($options["NoExecution_retuurnSQLString"]) ? false : $options["NoExecution_retuurnSQLString"];
            if(!$options["NoExecution_retuurnSQLString"]){
                return self::execute($queryCustomizingResult); 
            }
            return $queryCustomizingResult;  
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    ///////////////////////////////////
    //this methode will delete a row from table and return boolean value  , where :
    //@table : is table name
    //@id : is the id of row
    //
    //@conditionsArray : is an indexed array that contains the conditions of deletion operation (dont't write "where , and , or" .. just write the conditions)
    //Ex : $conditionsArray = array("id = 7 " , "Username = 'user123'")
    //
    //@option array : with this array you can specify the execution mode ... it contains :
    //1- "NoExecution_retuurnSQLString"  : is a logical value key  if it is true the query will be executed ...
    // if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed)
    //2- "limit" : is a numeric value key ... it mean how many rows will be deleted .. default value null
    //Ex $options = array("NoExecution_retuurnSQLString" => false , "limit" => 2)
    //
    //Note : don't forget to open connection before use this method 
    ///////////////////////////////////
    static public function removeWhere(string $table , Array $conditionsArray , $options = array())
    {
        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            } 
            $queryCustomizingResult = self::custumizeQuery()->deleteQueryCustomizer($table , $conditionsArray , $options);
            $options["NoExecution_retuurnSQLString"] = !isset($options["NoExecution_retuurnSQLString"]) ? false : $options["NoExecution_retuurnSQLString"];
            if(!$options["NoExecution_retuurnSQLString"]){
                return self::execute($queryCustomizingResult); 
            }
            return $queryCustomizingResult; 
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    ///////////////////////////////////
    //this methode will delete a row from table and return boolean value  , where :
    //@statement is a sql delete statement 
    //Note : don't forget to open connection before use this method 
    ///////////////////////////////////
    static public function removeBySqlStatement($statement)
    {
        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            }
            return self::execute($statement);  
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    //==============================================================================
    //update Methodes
    //==============================================================================
    

    ///////////////////////////////////
    //this methode will update a row in table and return boolean value  , where :
    //@table : is table name
    //  
    //@ColumnsValuesArray : is an associative array that contains columns and its new values
    //Ex $userNewValues = array("Username" => "User123" , "password" => 224422)
    //
    //@conditionsArray : is an indexed array that contains the conditions of deletion operation (dont't write "where , and , or" .. just write the conditions)
    //Ex : $conditionsArray = array("id = 7 " , "Username = 'user123'")
    //
    //@option array : with this array you can specify the execution mode ... it contains :
    //1- "NoExecution_retuurnSQLString"  : is a logical value key  if it is true the query will be executed ...
    // if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed)
    //2- "limit" : is a numeric value key ... it mean how many rows will be deleted .. default value null
    //Ex $options = array("NoExecution_retuurnSQLString" => false , "limit" => 2)
    //
    //Note : don't forget to open connection before use this method 
    ///////////////////////////////////
    static public function updateByValueAssocArray($table , $ColumnsValuesArray , $conditionsArray = array() , $options = array())
    {
        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            }  
            $queryCustomizingResult = self::custumizeQuery()->updateQueryCustomizer(  $table ,  $ColumnsValuesArray ,  $conditionsArray , $options );
            $options["NoExecution_retuurnSQLString"] = !isset($options["NoExecution_retuurnSQLString"]) ? false : $options["NoExecution_retuurnSQLString"];
            if(!$options["NoExecution_retuurnSQLString"]){
                return self::execute($queryCustomizingResult); 
            }
            return $queryCustomizingResult;

        }catch(Exception $e){
            return $e->getMessage();
        }
         
    }
    
    ///////////////////////////////////
    //this methode will update a row from table and return boolean value  , where :
    //@statement is a sql update statement 
    //Note : don't forget to open connection before use this method 
    ///////////////////////////////////
    static public function updateBySqlStatement($statement){
        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            } 
            return self::execute($statement);    
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
 
    //==============================================================================
    //selection (geting data)  Methodes
    //==============================================================================
    


    ///////////////////////////////////
    //this methode will get a row's all columns from table and return its value in asociative array  , where :
    //@table : is table name
    //  
    //@id : is id of row
    // 
    //@option array : with this array you can specify the execution mode ... it contains :
    //1- "NoExecution_retuurnSQLString"  : is a logical value key  if it is true the query will be executed ...
    // if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed)  
    //Ex $options = array("NoExecution_retuurnSQLString" => false  )
    //
    //Note : don't forget to open connection before use this method 
    ///////////////////////////////////
    static public function getRowWhereId($table , $id , $options = array())
    {
        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            }

            $queryCustomizingResult =  self::custumizeQuery()->selectQueryCustomizer(  $table , array("*") ,  array("Id = $id") ); 
            $options["NoExecution_retuurnSQLString"] = !isset($options["NoExecution_retuurnSQLString"]) ? false : $options["NoExecution_retuurnSQLString"];
            if(!$options["NoExecution_retuurnSQLString"]){
                return self::executeReader($queryCustomizingResult);
            }
            return $queryCustomizingResult; 
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
     
    ///////////////////////////////////
    //this methode  will return the information of some fields for the row (( By passing Id of row)), where :
    //@table : is table name
    //  
    //@columnsArray : is an indexed array that contains columns that you want t get its values
    //Ex $user = array("Username"  ,"password" )
    // 
    //@id : is the id of row
    //
    //@option array : with this array you can specify the execution mode ... it contains :
    //1- "NoExecution_retuurnSQLString"  : is a logical value key  if it is true the query will be executed ...
    // if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed)  
    //Ex $options = array("NoExecution_retuurnSQLString" => false)
    //
    //Note : don't forget to open connection before use this method 
    ///////////////////////////////////
    static public function getRowColumnsWhereId($table , $columnsArray , $id , $options = array())
    {
        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            }
            $queryCustomizingResult =  self::custumizeQuery()->selectQueryCustomizer($table , $columnsArray , array("Id = $id ") );
            $options["NoExecution_retuurnSQLString"] = !isset($options["NoExecution_retuurnSQLString"]) ? false : $options["NoExecution_retuurnSQLString"];
            if(!$options["NoExecution_retuurnSQLString"]){
                return self::executeReader($queryCustomizingResult);
            }
            return $queryCustomizingResult; 
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    ///////////////////////////////////
    //this methode  will return the information of some fields for the row (( By passing selection conditions)), where :
    //@table : is table name
    //  
    //@columnsArray : is an indexed array that contains columns that you want t get its values
    //Ex $user = array("Username"  ,"password" )
    // 
    //@conditionsArray : is an indexed array that contains the conditions of selection operation (dont't write "where , and , or" .. just write the conditions)
    //Ex : $conditionsArray = array("id = 7 " , "Username = 'user123'")
    //
    //@option array : with this array you can specify the execution mode ... it contains :
    //1- "NoExecution_retuurnSQLString"  : is a logical value key  if it is true the query will be executed ...
    // if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed)  
    //Ex $options = array("NoExecution_retuurnSQLString" => false  )
    //
    //Note : don't forget to open connection before use this method 
    ///////////////////////////////////
    static public function getRowColumnsWhereConditions($table , $columnsArray = array("*"), $conditionsArray , $options = array())
    {
        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            }
            $queryCustomizingResult =  self::custumizeQuery()->selectQueryCustomizer($table , $columnsArray ,  $conditionsArray );
            $options["NoExecution_retuurnSQLString"] = !isset($options["NoExecution_retuurnSQLString"]) ? false : $options["NoExecution_retuurnSQLString"];
            if(!$options["NoExecution_retuurnSQLString"]){
                return self::executeReader($queryCustomizingResult);
            }
            return $queryCustomizingResult; 
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    ///////////////////////////////////
    //this methode will select a single row from table and return it in associative array , where :
    //@statement is a sql select statement  (( for one row))
    //Note : don't forget to open connection before use this method 
    ///////////////////////////////////
    static public function getRowBySqlStatement($statement )
    {
        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            } 
            return self::executeReader($statement); 
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    ///////////////////////////////////
    //this methode  will return the information of some fields for the rows (( by selection conditions)), where :
    //@table : is table name
    //  
    //@columnsArray : is an indexed array that contains columns that you want t get its values
    //Ex $user = array("Username"  ,"password" )
    // 
    //@conditionsArray : is an indexed array that contains the conditions of selection operation (dont't write "where , and , or" .. just write the conditions)
    //Ex : $conditionsArray = array("id = 7 " , "Username = 'user123'")
    //
    //@options array : with this array you can specify the execution mode ... it contains :
    //1- "NoExecution_retuurnSQLString"  : is a logical value key  if it is true the query will be executed ...
    // if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed) 
    //2- "limit" : is a numeric value key ... it mean how many rows will be selected .. default value is null
    //3- "offset" : is a numeric value key ... it mean From what record do you start counting?  .. default value is null
    //Ex $options = array("NoExecution_retuurnSQLString" => false , "limit" => 2 , "offset" => 0)
    //
    //Note : don't forget to open connection before use this method 
    ///////////////////////////////////
    static public function getRowsColumnsWhereConditions($table , $columnsArray = array("*") , $conditionsArray = array() , $options = array() )
    {
        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            } 
            $queryCustomizingResult =  self::custumizeQuery()->selectQueryCustomizer($table , $columnsArray , $conditionsArray  , $options);
            $options["NoExecution_retuurnSQLString"] = !isset($options["NoExecution_retuurnSQLString"]) ? false : $options["NoExecution_retuurnSQLString"];
            if(!$options["NoExecution_retuurnSQLString"]){
                return self::executeReaderAll($queryCustomizingResult);
            }
            return $queryCustomizingResult;  
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    ///////////////////////////////////
    //this methode will select a list of rows from table and return it in Multi Dimensional associative array , where :
    //@statement is a sql select statement 
    //Note : don't forget to open connection before use this method 
    ///////////////////////////////////    
    static public function getRowsBySqlStatement($statement)
    {
        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            } 
            return self::executeReaderAll($statement); 
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
 
    ///////////////////////////////////
    //this methode will select a list of rows from multi tables and return it in Multi Dimensional associative array , where :
    //@statement is a sql select statement (join statement)
    //Note : don't forget to open connection before use this method 
    /////////////////////////////////// 
    static public function JoinByStatement($statement)
    {
        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            } 
            return self::executeReaderAll($statement); 
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    ///////////////////////////////////
    //this methode  will return the rows that you selected it in join statement , where :
    //@tableAndColumnsOfEachTableAssocArray : is an associative array that contains All tables to be entered into the join process
    //AND each table key's value must be an indexed array that contains all columns that wanted from that table
    //EX : $tableAndColumnsOfEachTableAssocArray = array("users" => array("FirstName" , "LastName") , "posts" => array("title" , "content"))
    //
    //
      //@JoinConditions : is an indexde array that contains all join conditions .... it must not be null
    //EX : $JoinConditions = array("users.id = posts.UserId" , "posts.id = comment.PostId"); (don't write and , or in connditions ... it is by "and" by default)
    //
    //@whereConditions : is an indexed array that contains where conditions that will be applied after join operation is done
    //Ex : $whereConditions = array("users.city = 'Istanbul'" , "posts.created_at > '2011-11-13'");
    //
    //@options array : with this array you can specify the execution mode ... it contains :
    //1- "NoExecution_retuurnSQLString"  : is a logical value key  if it is true the query will be executed ...
    // if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed) 
    //2- "limit" : is a numeric value key ... it mean how many rows will be selected .. default value is null
    //3- "offset" : is a numeric value key ... it mean From what record do you start counting?  .. default value is null
    //Ex $options = array("NoExecution_retuurnSQLString" => false , "limit" => 2 , "offset" => 0)
    //
    //Note : don't forget to open connection before use this method 
    ///////////////////////////////////
    static public function innerJoinByValues(  Array $tableAndColumnsOfEachTableAssocArray , Array $JoinConditions , Array $whereConditions = array() , $options = array( ))
    {
        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            } 
            $queryCustomizingResult =  self::custumizeQuery()->innerJoinQueryCustomizer(  $tableAndColumnsOfEachTableAssocArray ,  $JoinConditions ,  $whereConditions , $options );
            $options["NoExecution_retuurnSQLString"] = !isset($options["NoExecution_retuurnSQLString"]) ? false : $options["NoExecution_retuurnSQLString"];
            if(!$options["NoExecution_retuurnSQLString"]){
                return self::executeReaderAll($queryCustomizingResult);
            }
            return $queryCustomizingResult; 
        }
        catch(Exception $e){
            return $e->getMessage();
        }
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
    //
    // //@options array : with this array you can specify the execution mode ... it contains :
    //1- "NoExecution_retuurnSQLString"  : is a logical value key  if it is true the query will be executed ...
    // if it is false the method will return a sql statment without any execution default value is false (if you don't change it , query will be executed) 
    //2- "limit" : is a numeric value key ... it mean how many rows will be selected .. default value is null
    //3- "offset" : is a numeric value key ... it mean From what record do you start counting?  .. default value is null
    //Ex $options = array("NoExecution_retuurnSQLString" => false , "limit" => 2 , "offset" => 0)
    //
    ///////////////////////////////////
    static public function JoinByValues( Array $tableAndColumnsOfEachTableAssocArray , string $LeftTableName , Array $RightTableName_joinType_Array , Array $table_joinConsitions , Array $whereConditions = array() ,  $options = array())
    {
        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            } 
            $queryCustomizingResult =  self::custumizeQuery()->complexJoinQueryCustomizer( $tableAndColumnsOfEachTableAssocArray , $LeftTableName ,   $RightTableName_joinType_Array ,  $table_joinConsitions ,  $whereConditions , $options);
            $options["NoExecution_retuurnSQLString"] = !isset($options["NoExecution_retuurnSQLString"]) ? false : $options["NoExecution_retuurnSQLString"];
            if(!$options["NoExecution_retuurnSQLString"]){
                return self::executeReaderAll($queryCustomizingResult);
            }
            return $queryCustomizingResult; 
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    ///////////////////////////////////
    //this method will handle a union sql statment ... it just need :
    //@ArrayOfSelectionQueries : is an indexed array that contains all selection statements these will be integrated in a one table
    //EX : $ArrayOfSelectionQueries = array("select email from users" , "select email from customers");
    //
    //you can use previous methodes to return a sql statements
    //Ex : $arrayOfSelections = array(
    //           QuerySystem::JoinByValues( array("users" => array("Username") , "posts" => array("title" , "content") , "comments" => array("comment"))  , "users" , array("posts" => "left" , "comments" => "left") , array("posts" => "users.id = posts.UserId" , "comments" => "posts.id = comments.PostId" ) , array("users.id > 12")  ,  array("NoExecution_retuurnSQLString" => true) ) , 
    //           QuerySystem::JoinByValues( array("users" => array("Username") , "posts" => array("title" , "content") , "comments" => array("comment"))  , "users" , array("posts" => "right" , "comments" => "right") , array("posts" => "users.id = posts.UserId" , "comments" => "posts.id = comments.PostId" ) , array("users.id > 12")  ,  array("NoExecution_retuurnSQLString" => true) ) , 
    //      );
    //      QuerySystem::unionByValues( $arrayOfSelections)
    //
    //
    //if @unionAll is false the values will be unique (No redundancy there in value)
    ///////////////////////////////////
    static public function unionByValues( Array $ArrayOfSelectionQueries , $unionAll = false)
    {
        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            } 
            $queryCustomizingResult =  self::custumizeQuery()->unionQueryCustomizer( $ArrayOfSelectionQueries , $unionAll );
            if($queryCustomizingResult){
                return self::executeReaderAll($queryCustomizingResult);  
            }else{
                throw new Exception("Ancorrect query string!");
            }
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }


    //===============================================================================
    //is item Unique or found Metohds
    //===============================================================================


    ///////////////////////////////////
    //this method will return true if it found a row in table where : 
    //@table : table name
    //
    //@conditionsArray : is an indexed array that contains the conditions of selection operation (dont't write "where , and , or" .. just write the conditions)
    //Ex : $conditionsArray = array("id = 7 " , "Username = 'user123'")
    ///////////////////////////////////
    static public function isFoundWhereConditions($table , $conditionsArray)
    { 
        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            }  
             $matched_items = self::getRowColumnsWhereConditions($table ,  array("count(*) as count") , $conditionsArray )["count"];
            return $matched_items > 0 ? true : false;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    //===============================================================================
    //Database or table information getting Methodes
    //===============================================================================
    
    ///////////////////////////////////
    //this method will handle a  query to check if table has a specified field or not and return true or false ... it just need :
    //@dbName : database name
    //@table : table name
    //@column : column or field name
    ///////////////////////////////////
    static public function IsTableHasColumn($dbName , $table , $column)
    {

        try{
            if(self::$connectionObject == null){
                throw new Exception("You must open the connection with database first !");
            }
             $queryCustomizingResult = self::custumizeQuery()->isColumnFoundQueryCustomizer( $dbName , $table , $column); 
             if($queryCustomizingResult){ 
                return self::executeReader($queryCustomizingResult)["count"] != 0 ? true : false;  
            }else{
                throw new Exception("Ancorrect query string!");
            } 
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    } 
} 
