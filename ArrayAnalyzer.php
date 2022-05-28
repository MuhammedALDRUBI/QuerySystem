<?php

class ArrayAnalyzer{

    ///////////////////////////////////
    //this method will return the dimension of any array will be passing into it
    // it is used by indexedArrayPrinter and AssocArrayPrinter methodes to check if they could handling the array that they received or need to call multiDimensional Methodes
    ///////////////////////////////////
    public function getArrayDimension(Array $array){
        if (is_array(reset($array)))
        {
             $dimension = $this->getArrayDimension(reset($array)) + 1; 
        }else{ $dimension = 1;} 
        return $dimension;
    }

    ///////////////////////////////////
    // it is a simple method to check if an array is indexed array or not
    ///////////////////////////////////
    public function isIndexed(Array $array){
        foreach($array as $key => $val){
            return is_numeric($key);
        }
    } 
    
    ///////////////////////////////////
    //this method handling indexed arraies
    //
    //@indexedArray is an indexed array that you want to adding an @ElementPrefix before its value , and want to adding an @ElementSuffix after that value
    //if @stringCapsulation is false the string values will not encapsulate in a double quotation (you will need it when you handling an sql query)
    ///////////////////////////////////
    public function indexedArrayPrinter(Array $indexedArray , $ElementPrefix = " " , $ElementSuffix = " " ,  $stringCapsulation = true){
        $dimension = $this->getArrayDimension($indexedArray); 
        if($dimension > 1){ return $this->MultiDimensionalIndexedArrayPrinter( $indexedArray , $ElementPrefix ,  $ElementSuffix , $stringCapsulation);}
       
        $elementIndex = 0; 
        $elementCount = count($indexedArray);
        $string = "";
        foreach($indexedArray as $val){
            if(is_string($val) && $stringCapsulation ){
                $val = "'" . $val . "'";
            }
            if($elementIndex == $elementCount - 1){ 
                $ElementSuffix = "";
            } 
            $string .=  $ElementPrefix . $val . $ElementSuffix; 
            $elementIndex++;
        }
        return $string;
    }

    ///////////////////////////////////
    // it is a private method .... it used automatically by indexedArrayPrinter method when it received a mult dimensional array 
    // you will not need to use it directly
    ///////////////////////////////////
    private function MultiDimensionalIndexedArrayPrinter(Array $indexedArray ,  $ElementPrefix = " " ,  $ElementSuffix = " " , $stringCapsulation = true ){ 
        $string = "";  
        $ArraySuffix = $ElementSuffix;
        
        $elementCount = count($indexedArray); 
        $isArrayIndexed = $this->isIndexed($indexedArray[0]);
        for($i=0;$i < $elementCount;$i++){
            $currentArray = $indexedArray[$i];
            if($i == $elementCount - 1){ 
                $ArraySuffix = "";
            } 
            if($isArrayIndexed){  
                $string .= $this->indexedArrayPrinter( $currentArray  , $ElementPrefix , $ElementSuffix   , $stringCapsulation ) . $ArraySuffix;
                continue;
            }
            
            $string .= $this->AssocArrayPrinter($currentArray ,   $ElementPrefix , " = " , $ElementSuffix  , $stringCapsulation) . $ArraySuffix; 
        } 
        return $string;
    }

    ///////////////////////////////////
    //this method handling  Associative arraies
    //
    //@AssocArray is an Associative array that you want to adding an @keyPrefix before each its key , and want to adding an @valSuffix after that key's value
    // and want to adding @textBetween between that key and its value
    //if @stringCapsulation is false the string values will not encapsulate in a double quotation (you will need it when you handling an sql query)
    ///////////////////////////////////
    public function AssocArrayPrinter(Array $AssocArray ,   $keyPrefix = " " , $textBetween = " " , $valSuffix = " " , $stringCapsulation = true){
        $dimension = $this->getArrayDimension($AssocArray);
        if($dimension > 1){ return $this->MultiDimensionalAssocArrayPrinter( $AssocArray ,  $keyPrefix  , $textBetween  ,  $valSuffix , $stringCapsulation  );}

        $elementIndex = 0; 
        $elementCount = count($AssocArray);
        $string = "";
 
        foreach($AssocArray as $key => $val){
            if($elementIndex == $elementCount - 1){ 
                $valSuffix = "";
            }  
            if(is_string($val)){ 
                if($stringCapsulation ){
                    $val = "'" . $val . "'";  
                }
                $string .=  $keyPrefix . $key  . $textBetween . $val . $valSuffix;
            }
            if(is_array($val)){
                $string .= $this->AssocArrayPrinter($val ,  $keyPrefix . $key , $textBetween , $valSuffix , $stringCapsulation) ;  
            }
             
            $elementIndex++;
        }
        return $string;
    }

    ///////////////////////////////////
    // it is a private method .... it used automatically by AssocArrayPrinter method when it received a mult dimensional array 
    // you will not need to use it directly
    ///////////////////////////////////
    private function MultiDimensionalAssocArrayPrinter(Array $AssocArray ,  $keyPrefix = " " ,  $textBetween = " " , $valSuffix = " " , $stringCapsulation = true ){
        
        $string = "";
        $elementIndex = 0; 
        $elementCount = count($AssocArray);
        $ArraySuffix = $valSuffix ; 
 
        foreach($AssocArray as $key => $val){
            if($elementIndex == $elementCount - 1){ 
                $ArraySuffix = "";
            }  
            $elementIndex++;


            if($this->isIndexed($val)){
                $string .=   $this->indexedArrayPrinter($val  ,   $keyPrefix . $key . $textBetween  ,  $valSuffix , $stringCapsulation  ) . $ArraySuffix;
                continue;
            }
            $string .=  $this->AssocArrayPrinter($val , $keyPrefix .  $key ,  $textBetween , $valSuffix , $stringCapsulation) . $ArraySuffix;
             
        }
        return $string;
    }
      
}