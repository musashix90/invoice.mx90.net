<?php

class Application_Model_Invoices
{

	public function addInvoice($name, $addr1, $addr2, $city, $state,
					$zip, $item_list, $qty_list, $rate_list,
					$shipping, $adjustment, $status)
	{
		$adapter = Zend_Db_Table::getDefaultAdapter();
		$data = array(
			'name' => $name,
			'addr1' => $addr1,
			'addr2' => $addr2,
			'city' => $city,
			'state' => $state,
			'zip' => $zip,
			'item_list' => $item_list,
			'qty_list' => $qty_list,
			'rate_list' => $rate_list,
			'status' => $status,
			'adjustment' => $adjustment,
			'shipping' => $shipping,
			'created_ts' => new Zend_Db_Expr('NOW()')
		);
		$adapter->insert('invoices', $data);
		$lastInsertId = $adapter->lastInsertId();
		return $lastInsertId;
	}

	public function getInvoice($id)
	{
		$adapter = Zend_Db_Table::getDefaultAdapter();
		$adapter->setFetchMode(Zend_Db::FETCH_OBJ);
		$info = $adapter->query("SELECT id, name, addr1, addr2, city, state, zip, item_list, qty_list, rate_list, shipping, adjustment, DATE_FORMAT(created_ts, '%M %e, %Y') AS created_ts FROM invoices WHERE id = ?", $id);
		$infoSet = $info->fetch();
		return $infoSet;
	}

}
?>
