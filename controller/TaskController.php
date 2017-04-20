<?php

class TaskController
{
	private $model = null;
	private $user = null;

	function __construct($db, $twig)
	{
		include 'model/Task.php';
		include 'model/User.php';
		$this->model = new Task($db);
		$this->user = new User($db);
		$this->twig = $twig;
	}

	/**
	 * Отображаем шаблон
	 * @param $template
	 * @param $params
	 */
	private function render($template, $params = [])
	{
		$fileTemplate = 'template/'.$template;
		if (is_file($fileTemplate)) {
			ob_start();
			if (count($params) > 0) {
				extract($params);
			}
			include $fileTemplate;
			return ob_get_clean();
		}
	}

	/**
	 * Форма добавление задачи
	 * @param $params array
	 * @return mixed
	 */
	function getAdd()
	{
		$template = $this->twig->loadTemplate('task/add.phtml');
		$template->display([]);
	}

	/**
	 * Добавление задачи
	 * @param $description string
	 * @return mixed
	 */
	function postAdd($description, $post)
	{
		$updateParam = [];
		if (isset($post)) {
			$idAdd = $this->model->add($post['name']);
			header('Location: ./');
		}
	}

	/**
	 * Удаление задачи
	 * @param $id int
	 */
	public function getDelete($id)
	{
		$id = $id['id'];
		if (isset($id) && is_numeric($id)) {
			$isDelete = $this->model->delete($id);
			header('Location: ./');
		}
	}

	/**
	 * Форма редактирование задачи
	 * @param $id int
	 */

	public function getEdit($id)
	{
		$id = $id['id'];
		if (isset($id) && is_numeric($id)) {
			$template = $this->twig->loadTemplate('task/edit.phtml');
			$tasks = ['tasks' => $this->model->find($id)];
			$template->display($tasks);
		}
	}


	/**
	 * Изменение данных о задаче
	 * @param $id
	 */

	public function postEdit($description, $post)
	{
		if (isset($description)) {
			$isUpdate = $this->model->edit($post['name'], $description['id']);
			header('Location: ./');
		}
	}

/**
 * Форма выполнения задачи
 * @param $id int
 */

public function getDone($id)
{
	$id = $id['id'];
	if (isset($id) && is_numeric($id)) {
		$isDone = $this->model->done($id);
		header('Location: ./');
	}
}

	/**
	 * Получение всех задач
	 * @return array
	 */
	public function getList()
	{

		$template = $this->twig->loadTemplate('task/list.phtml');
		$tasks = ['tasks' => $this->model->findAll(), 'login' => $_SESSION['user']['login'], 'users' => $this->user->findAll()];
		$template->display($tasks);
	}
	/////////
		/**
		 * Изменение ответственного задачи
		 * @param
		 */

		public function postList($description, $post)
		{
			$data = explode('_', $post["assigned_user_id"]);
			if (isset($description)) {
				$isEditUser = $this->model->editUser($data);
				header('Location: ./');
			}
		}
}
