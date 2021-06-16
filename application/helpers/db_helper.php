<?php

if( ! function_exists('idClean')){

	function idClean($id,$size=11){
		return intval(substr($id,0,$size));
	}

}

if( ! function_exists('dbClean')){

	function dbClean($string,$size=1000000){
		return xss_clean(substr($string,0,$size));
	}

}

/**
 * 
 * @return \Illuminate\Database\Capsule\Manager
 */
function XDB(): \Illuminate\Database\Capsule\Manager{
	$db = new \Illuminate\Database\Capsule\Manager();
	return $db;
}

/**
 * Get a fluent query builder instance.
 *
 * @param  \Closure|\Illuminate\Database\Query\Builder|string  $table
 * @return \Illuminate\Database\Query\Builder
 */
function _db($table) {
	return XDB()::table($table);
}

function response_json($array, $code=200) {
	http_response_code($code);
	header('content-type: application/json');
	return json_encode($array);
}

function _insert_or_update($table, $set, $where, $id=0) {
	
	$check = _db($table)->where($where)->count();

	if($check > 0) {
		/* Update */
		_db($table)->where($where)->update($set);
		return $id;
	} else {
		/* Insert */
		return _db($table)->insertGetId($set);
	}
}

function _insert($table, $set) {
	return _db($table)->insertGetId($set);
}

function _update($table, $where, $set) {
	_db($table)->where($where)->update($set);
}

function _raw($query) {
	return XDB()::raw($query);
}

/**
 * Database Transaction 
 * Begin Transaction
 */
function _beginTransaction() {
	XDB()::beginTransaction();
}


/**
 * Database Transaction
 * Commit
 */
function _commit() {
	XDB()::commit();
}

/**
 * Database Transaction
 * Rollback
 */
function _rollback() {
	XDB()::rollback();
}

function _run_sp($query) {
	XDB()::unprepared($query);
}