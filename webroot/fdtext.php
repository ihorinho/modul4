<?php
$path = dirname(dirname(__FILE__));
// $command = 'grep -rnw ' . $path .' -e <br/>Done!';
$command = 'grep -rnw . -e alert';
echo exec($command);
// exec('tar -czvf ./arch.tar.gz ' . $path);
echo '<br/>Done!';