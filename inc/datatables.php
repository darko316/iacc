<?php
include('config.php');

class TableData {
 	public function get($table, $columns, $filters=false, $groupby=false) {
 		# column handler
		foreach($columns as $field) {
			if($field[1]) {
				$columns_alias[] = $field[0].' AS '.$field[1];
				$columns_id[] = $field[1];
			} else {
				$columns_alias[] = $field[0];
				$columns_id[] = $field[0];
			}
			$columns_original[] = $field[0];
		}

		# filter handler
		if(is_array($filters)) {
			foreach($filters as $filter) {
				$filters_where[] = $filter[0];
				$filters_holder[] = $filter[1];
				$filters_value[] = $filter[2];
				if($filter[3]) {
					$filters_type[] = $filter[3];
				} else {
					$filters_type[] = PDO::PARAM_INT;
				}
			}
		}

		# paging
		$sLimit = "";
		if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
			$sLimit = "LIMIT ".intval($_GET['iDisplayStart']).", ".intval($_GET['iDisplayLength']);
		}

		# order
		$sOrder = "";
		if (isset($_GET['iSortCol_0'])) {
			$sOrder = "ORDER BY  ";
			for ($i=0 ; $i<intval($_GET['iSortingCols']) ; $i++) {
				if ($_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true") {
					$sortDir = (strcasecmp($_GET['sSortDir_'.$i], 'ASC') == 0) ? 'ASC' : 'DESC';
					$sOrder .= "".$columns_id[ intval($_GET['iSortCol_'.$i]) ]." ". $sortDir .", ";
				}
			}
			
			$sOrder = substr_replace($sOrder, "", -2);
			if ($sOrder == "ORDER BY") {
				$sOrder = "";
			}
		}

		# grouping
		$sGroup = "";
		if($groupby) $sGroup = "GROUP BY ".$groupby;
		
		# filtering
		$sWhere = "";

		# filtering: global
		if(isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
			$sWhere = "WHERE (";
			for ($i=0 ; $i<count($columns) ; $i++) {
				if (isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true") {
					$sWhere .= "".$columns_original[$i]." LIKE :search OR ";
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		# filtering: column
		for ($i=0 ; $i<count($columns) ; $i++) {
			if (isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '') {
				if ($sWhere == "") {
					$sWhere = "WHERE ";
				}
				else {
					$sWhere .= " AND ";
				}
				$sWhere .= "".$columns_original[$i]." LIKE :search".$i." ";
			}
		}

		# filtering: server
		if($filters) {
			if ($sWhere == "") {
				$sWhere = "WHERE ";
			}
			else {
				$sWhere .= " AND ";
			}
			$sFilter .= "WHERE (".implode(" AND ", $filters_where).")";
			$sWhere .= "(".implode(" AND ", $filters_where).")";
		}

		# queries
		$sql = "SELECT SQL_CALC_FOUND_ROWS ".implode(", ", $columns_alias)." FROM ".$table." ".$sWhere." ".$sGroup." ".$sOrder." ".$sLimit;
		//exit($sql);
		$stmt = cnn()->prepare($sql);

		# parameters
		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
			$stmt->bindValue(':search', '%'.$_GET['sSearch'].'%', PDO::PARAM_STR);
		}
		for ($i=0 ; $i<count($columns) ; $i++) {
			if (isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '') {
				$stmt->bindValue(':search'.$i, '%'.$_GET['sSearch_'.$i].'%', PDO::PARAM_STR);
			}
		}
		if(is_array($filters)) {
			for ($i=0 ; $i<count($filters) ; $i++) {
				$stmt->bindValue($filters_holder[$i], $filters_value[$i], PDO::PARAM_INT);
			}
		}

		# get parameter filtered data
		$stmt->execute();
		$rResult = $stmt->fetchAll();
		
		$iFilteredTotal = current(cnn()->query('SELECT FOUND_ROWS()')->fetch());
		
		# record count
		if($sFilter) {
			$sql = "SELECT COUNT(*) FROM ".$table." ".$sFilter." ".$sGroup;
			$stmt = cnn()->prepare($sql);
			for ($i=0 ; $i<count($filters) ; $i++) {
				$stmt->bindValue($filters_holder[$i], $filters_value[$i], PDO::PARAM_INT);
			}
			$stmt->execute();
			if($sGroup) {
				$iTotal = count($stmt->fetchAll());
			} else {
				$iTotal = current($stmt->fetch());
			}
		} else {
			$sql = "SELECT COUNT(*) FROM ".$table." ".$sGroup;
			if($sGroup) {
				$iTotal = count(cnn()->query($sql)->fetchAll());
			} else {
				$iTotal = current(cnn()->query($sql)->fetch());
			}
		}
		
		# json output
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		# return array
		foreach($rResult as $aRow) {
			$row = array();			
			for ($i = 0; $i < count($columns); $i++) {
				$row[] = $aRow[ $columns_id[$i] ];
			}
			$output['aaData'][] = $row;
		}
		echo json_encode($output);
	}
}
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Content-type: application/json');
$table_data = new TableData();
$table_data->get(
	base64_decode($_GET['table']),
	unserialize(base64_decode($_GET['cols'])),
	unserialize(base64_decode($_GET['filters'])),
	base64_decode($_GET['groupby'])
);
