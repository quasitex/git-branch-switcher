<?php
define('STATUS_OK', 0);
define('STATUS_ERROR', 1);

define('CACHE_BRANCHES', dirname(__FILE__).'/cache/branches.json');
define('CACHE_STATUS', dirname(__FILE__).'/cache/status.json');

function switchToBranch($branch) {
	$status = STATUS_OK;
	$out = array();
	
	exec('git checkout "' . $branch . '"', $out, $status);
	exec('git pull "' . $branch . '"', $out, $status);

	$result = array(
		($status == STATUS_OK ? 'success' : 'error') => true,
		'status' => json_encode($out),
	);

	echo json_encode($result);
}

function getStatus() {
        if (!file_exists(CACHE_STATUS)) {
		$status = STATUS_OK;
		$out = array();
	
		exec('git status', $out, $status);

		$result = array(
			($status == STATUS_OK ? 'success' : 'error') => true,
			'status' => json_encode($out),
		);

		file_put_contents(CACHE_STATUS, json_encode($result));
	}
	echo file_get_contents(CACHE_STATUS);
}

function listBranches($showRemote = false) {
        if (!file_exists(CACHE_BRANCHES)) {
		$status = STATUS_OK;
		$out = array();
	
		exec('git branch -l'. ($showRemote === true ? ' -a' : ''), $out, $status);

		$branches = array();
		if ($status == STATUS_OK) {
			foreach($out as $line) {
				$matches = array();
				preg_match('/(\*)?\s+(.+)/', $line, $matches);
				$branches[] = array(
					'id' => $matches[2],
					'name' => $matches[2],
					'current' => $matches[1] == '*',
					'remote' => strpos($matches[2], '/' <> -1),
				);
			}
		}
	
		$result = array(
			($status == STATUS_OK ? 'success' : 'error') => true,
			'branches' => $branches,
		);
		file_put_contents(CACHE_BRANCHES, json_encode($result));
	}

	echo file_get_contents(CACHE_BRANCHES);
}

function clearCache() {
	@unlink(CACHE_BRANCHES);
	@unlink(CACHE_STATUS);
}

if (isset($_SERVER['REQUEST_METHOD'])) {
	switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		listBranches(isset($_GET['showRemote']) && $_GET['showRemote'] === true);
		break;
	case 'POST':
		if (isset($_POST['branch']) && !empty($_POST['branch'])) {
			switchToBranch($_POST['branch']);
		}
		break;
	case 'DELETE':
		clearCache();
		break;
	}
}

if (count($argv) > 1 && strtolower($argv[1]) == 'delete') {
	clearCache();
} 
