<?php

    function reset_id($conn, $table_name) {
        // query to fetch Username and Password from 
        // the table geek 
        $query = "SELECT * FROM ".$table_name; 
        
        // Execute the query and store the result set 
        $result = $conn->query($query);
        
        if ($result) 
        { 
            // it return number of rows in the table. 
            $row = $result->num_rows;
            
            if ($row == 0) 
            { 
                $conn->query("ALTER TABLE ".$table_name." AUTO_INCREMENT = 1");
            }
        }
    }

?>