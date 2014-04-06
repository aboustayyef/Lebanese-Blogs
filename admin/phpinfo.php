<?php
echo "__DIR__ : ", __DIR__ ,'<br/>';
echo "__FILE__ : ", __FILE__ , '<br/>';
echo 'dirname(__FILE__) : ', dirname(__FILE__), ' <br/>';
echo 'realpath(dirname(__FILE__)) : ', realpath(dirname(__FILE__)), ' <br/>';
echo 'DIRECTORY_SEPARATOR : [ ', DIRECTORY_SEPARATOR, ' ]';
echo '<br/>';
echo phpinfo();