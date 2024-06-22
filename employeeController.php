<?php

include('./dbcon.php');

if(isset($_POST["action"])){

    //Load data
    if($_POST["action"] == "Load"){

        $qry = "SELECT * FROM employees ORDER BY name ASC";
        $statement = $connect->prepare($qry);
        $statement->execute();
        $result = $statement->fetchAll();
        $output = '';
        $output .= '
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Gender</th>
                    <th>Age</th>
                </tr>
            </thead>
            <tbody>
        ';

        if(sizeof($result) > 0){
            
            foreach($result as $row){

                $output .= '
                    <input type="hidden" name="" value="'.$row["id"].'">
                    <tr>    
                        <td><button class="btn btn-warning btn-xs update" id="'.$row["id"].'"><i class="fas fa-file-signature"></i></button></td>
                        <td>'.$row["name"].'</td>
                        <td>'.$row['address'].'</td>
                        <td>'.$row['gender'].'</td>
                        <td>'.$row['age'].'</td>
                    </tr>';
            }
        }else{
            $output .= '
                <tr>
                    <td align="center">No records found.</td>
                </tr>';
        }

        $output .= '</tbody></table>';

        echo $output;

    }
}

//Fetch single data for display on Modal
if($_POST["action"] == "Select"){

    $output = array();
    $statement = $connect->prepare("SELECT * FROM employees WHERE id = '".$_POST["id"]."'  LIMIT 1" );
    $statement->execute();
    $result = $statement->fetchAll();

    foreach($result as $row){
        $output["id"]      = $row["id"];
        $output["name"]    = $row["name"];
        $output["address"] = $row["address"];
        $output["gender"]  = $row["gender"];
        $output["age"]     = $row["age"];
    }

    echo json_encode($output);
}

//Update
if($_POST["action"] == "Update"){
    
    $statement = $connect->prepare("UPDATE employees SET name = :name, address = :address, gender = :gender, age = :age WHERE id = :id ");
    
    $statement->bindParam(':id', $_POST["id"]);
    $statement->bindParam(':name', $_POST["name"]);
    $statement->bindParam(':address', $_POST["address"]);
    $statement->bindParam(':gender', $_POST["gender"]);
    $statement->bindParam(':age', $_POST["age"]);

    $result = $statement->execute();

    if(!empty($result)){
        echo 'recordUpdated';
    }
}