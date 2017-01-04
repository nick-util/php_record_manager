<?php
/**
 * Created by PhpStorm.
 * User: Nick Kiermaier
 * Date: 12/21/16
 * Time: 7:26 PM
 */

class RecordManager{

    /*
     * The following group of functions are general utility functions.
     */
    protected function convertIps($unconverted_ips){
        $convert_ips = function($ip){ return long2ip($ip); };
        return array_map($convert_ips, $unconverted_ips);
    }

    public function removeEmptyValues($raw_array){
        $remove_emptys = function($value){ return trim($value) !== ''; };
        $out = array_filter($raw_array, $remove_emptys);
        return $out;
    }

    public function remove2DEmptyValues($array2D){
        $out = [];
        foreach ($array2D as $row){
            $out[] = $this->removeEmptyValues($row);
        }
        return $out;
    }

    public function getAssociation($foreign_table_name, $foreign_key, $associated_ids_array){

        if(sizeof($associated_ids_array) < 1) {
            return [];
        }
        $params = implode(",", $associated_ids_array);
        $sql = "SELECT * FROM $foreign_table_name
                WHERE $foreign_key IN ($params)";
        return sql_cache($sql);
    }

    /*
     * The following group of functions are tested by test/RecordManagerTest.php
     * Some models are built on top of them so I wanted a minimum of robustness.
     * See the tests for explanation of their functionality.
     */
    public function getArrayColumnValues($field, $twoDimArray){
        return array_column($twoDimArray, $field);
    }

    public function getArrayColumnValuesUnique($field, $twoDimArray){
        $fields =  array_column($twoDimArray, $field);
        $filter = function($value) {return ($value !== NULL);};
        $fields = array_filter($fields, $filter);
        $out = array_keys(array_flip($fields));
        return $out;
    }

    public function makeArray2DUnique($twoDimArray){
        $out = [];
        $keys = array_keys($twoDimArray[0]);
        foreach($keys as $key){
            $out[$key] = $this->getArrayColumnValuesUnique($key, $twoDimArray);
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
        $values = $this->getArrayColumnValuesUnique($key, $array);
        foreach($values as $val){
            $out[$val] = $this->selectArrayRows($key, $val, $array);
        }
        return $out;
    }

    public function splitOnKeyUnique($key, $array){
        $out = [];
        $values = $this->getArrayColumnValuesUnique($key, $array);
        $esps = $this->splitOnKey($key, $array);
        foreach ($values as $val){
            $out[$val] = $this->makeArray2DUnique($esps[$val]);
        }
        return $out;
    }
}
