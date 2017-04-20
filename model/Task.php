<?php

class Task
{
	private $db = null;

	function __construct($db)
	{
		$this->db = $db;
	}

	/**
	* Добавление задачи
	* @param $description string
	* @return mixed
	*/

	function add($description)
	{
		$sql = "INSERT INTO task (id, user_id, assigned_user_id, description, is_done, date_added) VALUES (null, :id, null, :description, 0, NOW())";
	    $stmt = $this->db->prepare($sql);
	    $stmt->execute(['id' => $_SESSION['user']['id'], 'description' => $description]);
		return $stmt->fetch();
	}

	/**
	 * Удаление задачи
	* @param $id int
	* @return mixed
	*/
	function delete($id)
	{
		$sql = "DELETE FROM task WHERE id = :id LIMIT 1;";
	    $stmt = $this->db->prepare($sql);
	    $stmt->execute(['id' => $id]);
		return $stmt->fetch();
	}

	/**
	* Изменение задачи
	* @param $description string
	* @return mixed
	*/
	function edit($description, $id)
	{
		$sql = "UPDATE task SET description = :description WHERE id = :id LIMIT 1;";
	    $stmt = $this->db->prepare($sql);
	    $stmt->execute(['description' => $description, 'id' => $id]);
		return $stmt->fetch();
	}

	/**
	* Изменение ответственного задачи
	* @param $data array
	* @return mixed
	*/
	function editUser($data)
	{
		$sql = "UPDATE task SET assigned_user_id = {$data[1]} WHERE id = {$data[3]} LIMIT 1;";
	    $stmt = $this->db->prepare($sql);
	    $stmt->execute();
		return $stmt->fetch();
	}

	/**
	* Изменение статуса задачи
	* @param $id int
	* @return mixed
	*/
	function done($id)
	{
		$sql = "UPDATE task SET is_done = 1 WHERE id = :id LIMIT 1;";
	    $stmt = $this->db->prepare($sql);
	    $stmt->execute(['id' => $id]);
		return $stmt->fetch();
	}

	/**
	* Получение всех задач
	* @return array
	*/
	public function findAll()
	{
		$sql = "SELECT t.id, u.login AS autor, au.login as assigned, t.description, t.is_done, t.date_added
            FROM task AS t
            JOIN user AS u ON u.id=t.user_id
            LEFT JOIN user AS au on au.id=t.assigned_user_id";
	    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["sort_by"])) {
	        if ($_POST["sort_by"] === 'date_created'){$sql = $sql . " ORDER BY t.date_added";}
	        if ($_POST["sort_by"] === 'is_done'){$sql = $sql . " ORDER BY t.is_done";}
	        if ($_POST["sort_by"] === 'description'){$sql = $sql . " ORDER BY t.description";}
	    } else {$sql = $sql . " ORDER BY t.id";}
		$stmt = $this->db->prepare($sql);
		if ($stmt->execute()) {
			return $stmt->fetchAll();
		}
		return false;
	}

	/**
	 * Получение одной задачи
	 * @param $id int
	 * @return array
	 */
	public function find($id)
	{
		$sql = "SELECT id, description FROM task WHERE id = :id LIMIT 1;";
	    $stmt = $this->db->prepare($sql);
	    $stmt->execute(['id' => $id]);
		return $stmt->fetch();
	}
}
