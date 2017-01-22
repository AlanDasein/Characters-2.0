<?php

include "../models/Helper.php";

echo json_encode($_POST["k"] === Helper::encrypt(strtoupper($_POST["v"])));