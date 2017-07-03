<?php

function getWhereValuesString($input_array){
    $out_put_string = "";
    $i=count($input_array);
    if($input_array[0]=="*"){
        return "*";
    }
    foreach($input_array as $key=>$value){
        $out_put_string.=$value;
        if($i>1){
            $out_put_string.=",";
            $i--;
        }
    }
    return $out_put_string;
}

function create_read_query($table_name,$columns_name,$where_clause_array){
    $sql = "SELECT ";
    if (!is_array($columns_name)) {
        $sql.=$columns_name." FROM ";
    }else{
        $sql.=implode(",", $columns_name)." FROM ";
    }
    $sql.=$table_name." ";
    $sql.=where_clause_array_to_string($where_clause_array).";";
    return $sql;
}

function where_clause_array_to_string($where_clause_array){
    $out_put_string = " ";
    $count = count($where_clause_array);
    if ($count) {
        $out_put_string .= " WHERE ";
        foreach ($where_clause_array as $key => $value) {
            $out_put_string.=$key."=\'".$value."\' ";
            if ($count>1) {
                $out_put_string.="OR ";
                $count--;
            }
        }
    }
    return $out_put_string;
}

function getWhereClauseArrayAsString($where_clauses_array,$additional_info){
    $out_put_string = "";
    $count=count($where_clauses_array);
    foreach($where_clauses_array as $column_name=>$column_value){
        $out_put_string.=" ".$column_name."='";
        if (substr($column_value,0,1)==":") {
            $out_put_string.=$additional_info[substr($column_value,1)];
        }else{
            $out_put_string.=$column_value;
        }
        $out_put_string.="' ";
        if ($count>1) {
            $out_put_string.="AND ";
            $count--;
        }
    }
    return $out_put_string;
}



?>