<?php
# Create connection
function cnn() {
	static $pdo;
	if(!isset($pdo)) {
		try {
			# DB Settings
			$config['db']['host'] = 'localhost';
			$config['db']['user'] = 'root';
			$config['db']['pass'] = '';
			$config['db']['name'] = 'novatest_sib2';
			/*$config['db']['user'] = 'root';
			$config['db']['pass'] = '';*/
			//$config['db']['pass'] = '';
			$pdo = new PDO('mysql:host='.$config['db']['host'].';dbname='.$config['db']['name'], $config['db']['user'], $config['db']['pass'], array(PDO::ATTR_TIMEOUT => 30, PDO::ATTR_PERSISTENT => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
			return $pdo;
		} catch(PDOException $e) {
			if(defined('FLG_API')) {
				die($e->getCode().': '.$e->getMessage());
			}
		}
	} else {
		return $pdo;
	}
}

# Format strings for LIKE search
function sqls($string, $left=true) {
	if($left) {
		return '%'.$string.'%';
	} else {
		return $string.'%';
	}
}
