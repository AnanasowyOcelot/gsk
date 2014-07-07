<?php
class Core_DB
{
	/**
	 * @var Core_DB
	 */
	private static $instance = null;

	/**
	 * @var ADODB_mysql
	 */
	private static $o_db = null;

	private function __construct()
	{
		self::$o_db = NewADOConnection('mysql');
		//self::$o_db->debug = true;
        self::$o_db->port = Core_Config::get('db.port');
		self::$o_db->Connect(
			Core_Config::get('db.host'),
			Core_Config::get('db.user'),
			Core_Config::get('db.password'),
			Core_Config::get('db.database')
		);
		self::$o_db->Execute("SET NAMES 'utf8'");
		self::$o_db->SetFetchMode(ADODB_FETCH_ASSOC);
	}

	/**
	 * @return Core_DB|null
	 */
	public static function instancja()
	{
		if (!self::$instance instanceof self) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function pobierz()
	{
		return self::$o_db->Execute('select * from uzytkownik')->GetRows();
	}

	//=====================================================
	public function last_insert_id($tablename)
	{
		$v_sql = "select LAST_INSERT_ID() FROM " . $tablename;
		$last_id = self::$o_db->GetOne($v_sql);
		return $last_id;
	}

	//=====================================================
	function insert($tablename, $record, $strip = 1)
	{
		if ($strip == 1) {
			foreach ($record as $klucz => $wartosc) {
				$record[$klucz] = str_replace("\"", "'", $wartosc);
			}
		}
		$rs         = self::$o_db->Execute('SELECT * FROM ' . $tablename . ' LIMIT 1');
		$insert_sql = self::$o_db->GetInsertSQL($rs, $record);

		$result = self::$o_db->Execute($insert_sql);
		if ($result === false) {
			return false;
		}
		return true;
	}

	//=====================================================
	function update($tablename, $record, $where = null, $data = null)
	{
		foreach ($record as $klucz => $wartosc) {
			$record[$klucz] = str_replace("\"", "'", $wartosc);
		}

		if ($where !== null) {
			$rs = self::$o_db->Execute('SELECT * FROM ' . $tablename . ' WHERE ' . $where . ' LIMIT 1', $data);
		} else {
			$rs = self::$o_db->Execute('SELECT * FROM ' . $tablename . ' LIMIT 1', $data);
		}
		$update_sql = self::$o_db->GetUpdateSQL($rs, $record);
		if ($update_sql != '') {
			return self::$o_db->Execute($update_sql);
		}
		return true;
	}

	/**
	 * $DB -> delete('useri','id=75');
	 */
	//=====================================================
	function delete($tablename, $where, $params)
	{
		$result = null;
		try {
			$result = self::$o_db->Execute('DELETE FROM ' . $tablename . ' WHERE ' . $where, $params);
		} catch (exception $e) {
			if (constant('DEBUG') === true) {
				print_r($e);
			}
		}
		return $result;
	}

	//=====================================================
	function query($query)
	{
		$result = null;
		try {
			$result = self::$o_db->Execute($query);
		} catch (exception $e) {
			print_r($e);
		}
		return $result;
	}

	//=====================================================
	function get_one($query)
	{
		$wartosc = null;
		try {
			$wartosc = self::$o_db->GetOne($query);
		} catch (exception $e) {
			if (constant('DEBUG') === true) {
				print_r($e);
			}
		}
		return $wartosc;
	}

	//=====================================================
	function get_row($query)
	{
		$wiersz = null;
		try {
			$wiersz = self::$o_db->GetRow($query);
		} catch (exception $e) {
			if (defined('DEBUG') && constant('DEBUG') === true) {
				print_r($e);
			}
		}
		return $wiersz;
	}

	//=====================================================
	function __call($method, $args)
	{
		return call_user_func_array(array(self::$o_db, $method), $args);
	}
}
