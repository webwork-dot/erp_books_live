<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Activity Log Model
 *
 * Writes audit rows to sys_activity_log (no-op if table missing).
 */
class Activity_log_model extends CI_Model
{
	/** @var bool|null */
	protected $table_ready = NULL;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Whether sys_activity_log exists on the current connection.
	 *
	 * @return bool
	 */
	public function tableExists()
	{
		if ($this->table_ready !== NULL) {
			return $this->table_ready;
		}
		$this->table_ready = $this->db->table_exists('sys_activity_log');
		return $this->table_ready;
	}

	/**
	 * Insert an activity log row.
	 *
	 * Expected keys: module, action, action_label, actor_id, actor_name, actor_role,
	 * reference_type, reference_id, reference_label, meta_json|meta, ip_address
	 *
	 * @param array $data
	 * @return bool
	 */
	public function log($data)
	{
		if (!$this->tableExists()) {
			return FALSE;
		}

		$meta = NULL;
		if (isset($data['meta_json'])) {
			$meta = is_string($data['meta_json']) ? $data['meta_json'] : json_encode($data['meta_json']);
		} elseif (isset($data['meta'])) {
			$meta = is_string($data['meta']) ? $data['meta'] : json_encode($data['meta']);
		}

		$row = array(
			'module' => isset($data['module']) ? (string) $data['module'] : '',
			'action' => isset($data['action']) ? (string) $data['action'] : '',
			'action_label' => isset($data['action_label']) ? $data['action_label'] : NULL,
			'actor_id' => isset($data['actor_id']) ? (int) $data['actor_id'] : NULL,
			'actor_name' => isset($data['actor_name']) ? $data['actor_name'] : NULL,
			'actor_role' => isset($data['actor_role']) ? $data['actor_role'] : NULL,
			'reference_type' => isset($data['reference_type']) ? $data['reference_type'] : NULL,
			'reference_id' => isset($data['reference_id']) ? (int) $data['reference_id'] : NULL,
			'reference_label' => isset($data['reference_label']) ? $data['reference_label'] : NULL,
			'meta_json' => $meta,
			'ip_address' => isset($data['ip_address']) ? $data['ip_address'] : NULL,
			'created_at' => date('Y-m-d H:i:s'),
		);

		if ($row['module'] === '' || $row['action'] === '') {
			return FALSE;
		}

		return (bool) $this->db->insert('sys_activity_log', $row);
	}
}
