<?php
if (! function_exists('pcntl_fork')) die('PCNTL functions not available on this PHP installation');

for ($x = 1; $x < 5; $x++) {
    switch ($pid = pcntl_fork()) {
        case -1:
            // @fail
            die('Fork failed');
            break;

        case 0:
            // @child: Include() misbehaving code here
            print "FORK: Child #{$x} preparing to nuke...\n";
            //generate_fatal_error(); // Undefined function
            start_generate();
            break;

        default:
            // @parent
            print "FORK: Parent, letting the child run amok...\n";
            pcntl_waitpid($pid, $status);
            break;
    }
}

function start_generate(){
    while (true){
        print "in child! :^)\n\n";
    }
}

print "Done! :^)\n\n";
?>