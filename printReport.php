<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
extract($_POST);
require_once('../crud/Attendance.class.php');
           
        $projects = new Projects();
        $result = $projects->exec("SELECT p.name as 'Project Name',p.title as 'Project Title',p.description,p.location,u.name as username,r.name as role, p.created_date, p.updated_date FROM projects p, users u,roles r where u.id=p.created_user_id AND r.id = u.role_id AND date(p.created_date) BETWEEN date('$fromdate') AND date('$todate') ORDER BY p.created_date ");

             $fileName = "Report-".$fromdate." to ".$todate.".xls";
 
            if ($result) {
                function filterData(&$str) {
                    $str = preg_replace("/\t/", "\\t", $str);
                    $str = preg_replace("/\r?\n/", "\\n", $str);
                    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
                }
            
                // headers for download
                header("Content-Disposition: attachment; filename=\"$fileName\"");
                header("Content-Type: application/vnd.ms-excel");
            
                $flag = false;
                foreach($result as $row) {
                    if(!$flag) {
                        // display column names as first row
                        echo implode("\t", array_keys($row)) . "\n";
                        $flag = true;
                    }
                    // filter data
                    array_walk($row, 'filterData');
                    echo implode("\t", array_values($row)) . "\n";
                    // echo $row;
                }
                // exit;           
            }
// exit;
// header("location:../sittin_table_view.php");
