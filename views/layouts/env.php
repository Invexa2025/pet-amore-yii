<?php

$env = YII_ENV;

if ($env == 'dev')
{
    echo '
		<div style="position:fixed;top:0;right:0;background-color:black;color:white;z-index:9998;font-size:12px;">
			Dev Version
		</div>
	';
}
