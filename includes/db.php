<?php
function db(): mysqli
{
    static $conn = null;
    if ($conn === null) {
        $conn = mysqli_connect("localhost", "root", "", "sch_fedex");
    }
    return $conn;
}