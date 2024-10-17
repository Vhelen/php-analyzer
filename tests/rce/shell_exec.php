<?php
    // Shell exec command without arg
    $output = shell_exec('ls');
    echo "<pre>$output</pre>";

    // Shell exec command with arg
    $output = shell_exec('ls -lart');
    echo "<pre>$output</pre>";

    shell_exec("my_script.sh 2>&1 | tee -a /tmp/mylog 2>/dev/null >/dev/null &");

    // Shell exec with a php var
    $path_to_backup_file = "backup.gz";
    echo shell_exec("gunzip -c -t $path_to_backup_file 2>&1");