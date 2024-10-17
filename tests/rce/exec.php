<?php
    exec('whoami');

    $output=null;
    exec('whoami', $output);

    $output=null;
    $retval=null;
    exec('whoami', $output, $retval);
    print_r($output);


    $command_part_two = "-al";
    $command_part_one = "ls";

    $cmd = $command_part_one." ".$command_part_two;

    exec($cmd);

    $output=null;
    exec($cmd, $output);

    $output=null;
    $retval=null;
    exec($cmd, $output, $retval);
    print_r($output);


    $command_part_two = $_GET['cmd'];
    $command_part_one = "ls";

    $cmd = $command_part_one." ".$command_part_two;

    exec($cmd);

    $output=null;
    exec($cmd, $output);

    $output=null;
    $retval=null;
    exec($cmd, $output, $retval);
    print_r($output);