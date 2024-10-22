<?php
    $dir = $_GET['dir'];
    eval("system('ls -al".$dir."')");
    
    