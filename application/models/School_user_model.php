<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Client User Model
 *
 * Handles database operations for client sub-users
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 * @author		ERP Team
 */
class School_user_model extends CI_Model
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		// Uses tenant database (switched by Tenant library)
	}
	
	/**
	 * Get user by username
	 *
	 * @param	string	$username	Username
	 * @return	array|NULL	User data or NULL if not found
	 */
	public function getUserByUsername($username)
	{
		$this->db->where('admin_email', $username);
		$this->db->where('status', 'active');
		$query = $this->db->get('erp_schools');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return NULL;
	}
	
	/**
	 * Get user by ID
	 *
	 * @param	int	$user_id	User ID
	 * @return	array|NULL	User data or NULL if not found
	 */
	public function getUserById($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->where('status', 1);
		$query = $this->db->get('client_users');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
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
		// Note: Add last_login column to client_users table if needed
		return TRUE;
	}
	
	/**
	 * Create user
	 *
	 * @param	array	$data	User data
	 * @return	int|FALSE	User ID on success, FALSE on failure
	 */
	public function createUser($data)
	{
		// Hash password if provided
		if (isset($data['password']))
		{
			$data['password'] = $this->hashPassword($data['password']);
		}
		
		$this->db->insert('client_users', $data);
		
		if ($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}
		
		return FALSE;
	}
	
	/**
	 * Get all users
	 *
	 * @param	array	$filters	Optional filters
	 * @return	array	Array of users
	 */
	public function getAllUsers($filters = array())
	{
		$this->db->from('client_users');
		
		if (isset($filters['status']))
		{
			$this->db->where('status', $filters['status']);
		}
		
		$this->db->order_by('created_at', 'DESC');
		$query = $this->db->get();
		
		return $query->result_array();
	}
}

