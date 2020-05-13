<?php

$mysqli = new mysqli('10.13.1.85', 'muzeumhome', 'pwmuz', 'muzeumhome');
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}


class funcction_actuator
{

    private $mysqli;

    function __construct(mysqli $a1)
    {
        $this->mysqli = $a1;
    }

    function run()
    {
        $serviced_function = $_REQUEST["function"];
        switch ($serviced_function) {
            case 'getBYid':
                $this->getImgById();
                break;
            case 'upload':
                $data = file_get_contents( $_FILES["fileToUpload"]["tmp_name"]);
                //$base64 =  base64_encode($data);

                if ($stmt = $this->mysqli->prepare("Insert into kepAdat (data,name) values (?,?)")) {
                    $stmt->bind_param("bs", $null,$kepNev);
                    $kepNev =$_FILES["fileToUpload"]["name"];

                    $stmt->send_long_data(0,$data);
                    $stmt->execute();
                    $stmt->close();
                }
                echo 'thx';
                break;
            default:
            print "<form method=\"post\" enctype=\"multipart/form-data\">  <input type='hidden' name='function' value='upload'><input name='fileToUpload' type='file' /><input type='submit'></form>";
                break;
        }

    }

    /**
     * @param mysqli $mysqli
     * @param $district
     * @return array
     */
    function getImgById()
    {
        if ($stmt = $this->mysqli->prepare("SELECT  data FROM kepAdat where id = ? ")) {

            /* bind parameters for markers */
            $stmt->bind_param("i", $kepId);
            $kepId = $_GET['id'];
            /* execute query */
            $stmt->execute();

            header("Content-Type: image/png");
            /* bind result variables */
            $stmt->bind_result($district);

            /* fetch value */
            $stmt->fetch();

            echo $district;
            /* close statement */
            $stmt->close();

        }
    }
}


$app = new funcction_actuator($mysqli);
$app->run();
$mysqli->close();
?>