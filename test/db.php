<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $servername='localhost';
        $username='root';
        $dbpassword='';
        $dbname='db_test';
        $conn=mysqli_connect($servername,$username,$dbpassword,$dbname);

        $sql="SELECT * FROM tbl_users";

        $query=mysqli_query($conn,$sql);

        $result=

        if (!$conn) {
            die("Kết nối không thành công!");
        };

        echo "Kết nối thành công!";
    ?>
</body>
</html>