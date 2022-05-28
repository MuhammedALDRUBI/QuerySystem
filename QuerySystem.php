<?php

class QuerySystem{

    static private  $connectionObject = null;
    static private  $QueryCustomizerObject = null;
  
    //this function is used to open the connection with ((Any Database)) , Dont't forget to close connection before connection to an other database
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

    //this method is used to close the connection
    static public function closeConnection()
    {
        self::$connectionObject = null;
        return true;
    }
    // $table , $ColumnsAndValuesArray = array()  , $ConditinsIndexedArray = array() 
    static public function custumizeQuery()
    { 
        self::$QueryCustomizerObject = new QueryCustomizer(); 
        return self::$QueryCustomizerObject;
    } 

    static private function getPDOStatementOb($queryString)
    {
        $queryStatment = self::$connectionObject->prepare($queryString);
        $queryStatment->execute();
        return $queryStatment;
    }

    static private function execute($queryString)
    {
        $executionResult = self::getPDOStatementOb($queryString)->rowCount();
        return  $executionResult != 0 ? true : false;   
    }

    static private function executeReaderAll($queryString)
    {
        $PDOStatementOb = self::getPDOStatementOb($queryString);
        return  $PDOStatementOb->rowCount() != 0 ? $PDOStatementOb->fetchAll(PDO::FETCH_ASSOC) : null; 
    }
    static private function executeReader($queryString)
    { 
        $PDOStatementOb = self::getPDOStatementOb($queryString);
        return  $PDOStatementOb->rowCount() != 0 ? $PDOStatementOb->fetch(PDO::FETCH_ASSOC) : null; 
    }

    //==============================================================================
    //insertion Methodes
    //==============================================================================

    //with this method you can insert values by passing tableName and associative array of values that you want to send it to DB
    //Note : don't forget to open connection before use this method 
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

    //with this method you can insert values by only passing the insert statement that contains table name and columns and values
    //Note : don't forget to open connection before use this method
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
    
    //with this method you can update values by only passing the update statement that contains table name and columns and values
    //Note : don't forget to open connection before use this method
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
    
    //Note : by this method you can only get one row info ...... to get more than use getItems methods
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
     
    //Note : by this method you can only get one row info ...... to get more than use getItems methods
    static public function getRowColumnsWhereId($table , $columnsArray, $id , $options = array())
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

    //Note : by this method you can only get one row info ...... to get more than use getItems methods
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

    //Note : by this method you can only get one row info ...... to get more than use getItems methods
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

    //Note : by this method you can only get multiple rows info ..... to get one row info use getItemInfo methods
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

    //Note : by this method you can only get multiple rows info ..... to get one row info use getItemInfo methods
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