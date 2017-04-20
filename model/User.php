<?php

class User
{
	private $db = null;

	function __construct($db)
	{
		$this->db = $db;
	}

	/**
	* Получение всех задач
	* @return array
	*/
	public function findAll()
	{
		$sql = "SELECT * FROM user";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}
}
