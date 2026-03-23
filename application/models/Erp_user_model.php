<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ERP User Model
 *
 * Handles database operations for super admin users
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 * @author		ERP Team
 */
class Erp_user_model extends CI_Model
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		// Use master database
		$this->load->database('default', TRUE);
	}
	
	/**
	 * Get user by username
	 *
	 * @param	string	$username	Username
	 * @return	array|NULL	User data or NULL if not found
	 */
	public function getUserByUsername($username)
	{
		$this->db->select('erp_users.*, erp_user_roles.name as role_name, erp_user_roles.permissions');
		$this->db->from('erp_users');
		$this->db->join('erp_user_roles', 'erp_user_roles.id = erp_users.role_id', 'left');
		$this->db->where('erp_users.username', $username);
		$this->db->where('erp_users.status', 1);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			$user = $query->row_array();
			if (!empty($user['permissions']))
			{
				$user['permissions'] = json_decode($user['permissions'], TRUE);
			}
			return $user;
		}
		
		return NULL;
	}
	
	/**
	 * Get user by email
	 *
	 * @param	string	$email	Email address
	 * @return	array|NULL	User data or NULL if not found
	 */
	public function getUserByEmail($email)
	{
		$this->db->select('erp_users.*, erp_user_roles.name as role_name, erp_user_roles.permissions');
		$this->db->from('erp_users');
		$this->db->join('erp_user_roles', 'erp_user_roles.id = erp_users.role_id', 'left');
		$this->db->where('erp_users.email', $email);
		$this->db->where('erp_users.status', 1);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			$user = $query->row_array();
			if (!empty($user['permissions']))
			{
				$user['permissions'] = json_decode($user['permissions'], TRUE);
			}
			return $user;
		}
		
		return NULL;
	}
	
	/**
	 * Verify password
	 *
	 * @param	string	$password	Plain text password
	 * @param	string	$hash		Hashed password
	 * @return	bool	TRUE if password matches, FALSE otherwise
	 */
	public function verifyPassword($password, $hash)
	{
		return sha1($password) === $hash;
	}
	
	/**
	 * Hash password
	 *
	 * @param	string	$password	Plain text password
	 * @return	string	Hashed password
	 */
	public function hashPassword($password)
	{
		return sha1($password);
	}
	
	/**
	 * Update last login
	 *
	 * @param	int	$user_id	User ID
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function updateLastLogin($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->update('erp_users', array('last_login' => date('Y-m-d H:i:s')));
		
		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * Create user
	 *
	 * @param	array	$data	User data
	 * @param	bool	$password_already_hashed	If TRUE, password is already hashed and won't be hashed again
	 * @return	int|FALSE	User ID on success, FALSE on failure
	 */
	public function createUser($data, $password_already_hashed = FALSE)
	{
		// Hash password if provided and not already hashed
		if (isset($data['password']) && !$password_already_hashed)
		{
			$data['password'] = $this->hashPassword($data['password']);
		}
		
		$this->db->insert('erp_users', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Update user
	 *
	 * @param	int	$user_id	User ID
	 * @param	array	$data		User data
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function updateUser($user_id, $data)
	{
		// Hash password if provided
		if (isset($data['password']) && !empty($data['password']))
		{
			$data['password'] = $this->hashPassword($data['password']);
		}
		else
		{
			unset($data['password']);
		}
		
		$this->db->where('id', $user_id);
		$this->db->update('erp_users', $data);
		
		return $this->db->affected_rows() > 0;
	}
	
	/**
	 * Get user by ID
	 *
	 * @param	int	$user_id	User ID
	 * @return	array|NULL	User data or NULL if not found
	 */
	public function getUserById($user_id)
	{
		$this->db->select('erp_users.*, erp_user_roles.name as role_name, erp_user_roles.permissions');
		$this->db->from('erp_users');
		$this->db->join('erp_user_roles', 'erp_user_roles.id = erp_users.role_id', 'left');
		$this->db->where('erp_users.id', $user_id);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			$user = $query->row_array();
			if (!empty($user['permissions']))
			{
				$user['permissions'] = json_decode($user['permissions'], TRUE);
			}
			return $user;
		}
		
		return NULL;
	}
	
	/**
	 * Get all users
	 *
	 * @param	array	$filters	Optional filters
	 * @return	array	Array of users
	 */
	public function getAllUsers($filters = array(), $limit = NULL, $offset = 0)
	{
		$this->db->select('erp_users.*, erp_user_roles.name as role_name');
		$this->db->from('erp_users');
		$this->db->join('erp_user_roles', 'erp_user_roles.id = erp_users.role_id', 'left');
		
		if (isset($filters['status']))
		{
			$this->db->where('erp_users.status', $filters['status']);
		}
		
		if (isset($filters['role_id']))
		{
			$this->db->where('erp_users.role_id', $filters['role_id']);
		}
		
		$this->db->order_by('erp_users.created_at', 'ASC');
		
		if ($limit !== NULL)
		{
			$this->db->limit($limit, $offset);
		}
		
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/**
	 * Get total users count
	 *
	 * @param	array	$filters	Optional filters
	 * @return	int	Total number of users
	 */
	public function getTotalUsers($filters = array())
	{
		$this->db->from('erp_users');
		$this->db->join('erp_user_roles', 'erp_user_roles.id = erp_users.role_id', 'left');
		
		if (isset($filters['status']))
		{
			$this->db->where('erp_users.status', $filters['status']);
		}
		
		if (isset($filters['role_id']))
		{
			$this->db->where('erp_users.role_id', $filters['role_id']);
		}
		
		return $this->db->count_all_results();
	}
	
	/**
	 * Get role by name
	 *
	 * @param	string	$role_name	Role name
	 * @return	array|NULL	Role data or NULL if not found
	 */
	public function getRoleByName($role_name)
	{
		$this->db->where('name', $role_name);
		$query = $this->db->get('erp_user_roles');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return NULL;
	}
	
	/**
	 * Get or create vendor role
	 *
	 * @return	int	Role ID
	 */
	public function getOrCreateVendorRole()
	{
		$role = $this->getRoleByName('Vendor');
		
		if ($role)
		{
			return $role['id'];
		}
		
		// Create vendor role if it doesn't exist
		$role_data = array(
			'name' => 'Vendor',
			'description' => 'Vendor role for vendor login access',
			'permissions' => json_encode(array(
				'dashboard' => array('read'),
				'profile' => array('read', 'update')
			))
		);
		
		$this->db->insert('erp_user_roles', $role_data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return NULL;
	}
}

