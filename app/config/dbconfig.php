<?php

return ['database' =>
	[
	'host'=> '',
	'dbname'=> '',
	'username'=>'',
	'password'=>'',
	'charset'=>'',
	'options'=>[
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_PERSISTENT => true
		]
	]
];
