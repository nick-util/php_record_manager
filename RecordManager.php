<?php

class RecordManager{

    public function getColumnValues($field, $twoDimArray){
            return array_column($twoDimArray, $field);
        }

    public function getColumnValuesUnique($field, $twoDimArray){
        $fields =  array_column($twoDimArray, $field);
        $out = array_keys(array_flip($fields));
        return $out;
    }

    public function makeArray2DUnique($twoDimArray){
        $out = [];
        $keys = array_keys($twoDimArray[0]);
        foreach($keys as $key){
            $out[$key] = $this->getColumnValuesUnique($key, $twoDimArray);
        }
        return $out;
    }

    public function selectArrayRows($key, $value, $twoDimArray){
        $out = [];
        foreach($twoDimArray as $array){
            if($array[$key] == $value){
                $out[] = $array;
            }
        }
        return $out;
    }

    public function splitOnKey($key, $array){
        $out = [];
        $values = $this->getColumnValuesUnique($key, $array);
        foreach($values as $val){
            $out[$val] = $this->selectArrayRows($key, $val, $array);
        }
        return $out;
    }
}