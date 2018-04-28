<?php
/*if($pid = pcntl_fork()) {
    $my_pid = getmypid();
    print "My pid is $my_pid. pcntl_fork() return $pid, this is the parent\n";
} else {
    $my_pid = getmypid();
    print "My pid is $my_pid. pcntl_fork() returned 0, this is the child\n";
    for($i=0;$i<10;$i++){
        $content = "some text here";
        $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/myText"+$i+".txt","wb");
        fwrite($fp,$content);
        fclose($fp);
    }
}*/
phpinfo();
?>