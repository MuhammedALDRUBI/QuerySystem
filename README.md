# QuerySystem
QuerySystem is the Last Version Of QueryHandler.php - By PHP

<hr>

## this Library will help you to connect MySQL and execute a lot of operations in specified database
<hr> 

## Usable Static Methodes:

1 - openConnection($Host , $DBName , $DBUserName , $DBUserPassword)
2 - closeConnection()
3 - custumizeQuery()

<hr>

### insertion Methodes
4 - insertByValuesArray($table ,   $ColumnsAndValuesArray , $options = array())
5 - insertBySqlStatement($statement)

<hr>

### Deletion Methodes
6 - removeWhereId($table , $id , $options = array())
7 - removeWhere(string $table , Array $conditionsArray , $options = array())
8 - removeBySqlStatement($statement)

<hr> 

### Updating Methodes 
9 - updateByValueAssocArray($table , $ColumnsValuesArray , $conditionsArray = array() , $options = array())
10 - updateBySqlStatement($statement)

<hr>

### selection Methodes
11 - getRowWhereId($table , $id , $options = array())
12 - getRowColumnsWhereId($table , $columnsArray , $id , $options = array())
13 - getRowColumnsWhereConditions($table , $columnsArray = array("*"), $conditionsArray , $options = array())
14 - getRowBySqlStatement($statement)
15 - getRowsColumnsWhereConditions($table , $columnsArray = array("*") , $conditionsArray = array() , $options = array() )
16 - getRowsBySqlStatement($statement)
17 - JoinByStatement($statement)
18 - innerJoinByValues(  Array $tableAndColumnsOfEachTableAssocArray , Array $JoinConditions , Array $whereConditions = array() , $options = array( ))
19 - JoinByValues( Array $tableAndColumnsOfEachTableAssocArray , string $LeftTableName , Array $RightTableName_joinType_Array , Array $table_joinConsitions , Array $whereConditions = array() ,  $options = array())
20 - unionByValues( Array $ArrayOfSelectionQueries , $unionAll = false)

### 
21 - IsTableHasColumn($dbName , $table , $column)

### Database or table information getting Methodes
22 - IsTableHasColumn($dbName , $table , $column)
