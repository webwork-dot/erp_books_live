<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Location Model
 *
 * Handles states and cities data
 *
 * @package		ERP
 * @subpackage	Models
 * @category	Models
 * @author		ERP Team
 */
class Location_model extends CI_Model
{
	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
		// Use default database connection (will be switched to vendor database by Vendor_base)
		// Do not load separate connection - use $this->db which is switched to vendor database
	}
	
	/**
	 * Get all states (for India - country_id = 101)
	 *
	 * @return	array	Array of states
	 */
	public function getAllStates()
	{
		$this->db->where('country_id', 101); // India
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get('states');
		
		return $query->result_array();
	}
	
	/**
	 * Get state by ID
	 *
	 * @param	int	$state_id	State ID
	 * @return	array|NULL	State data or NULL if not found
	 */
	public function getStateById($state_id)
	{
		$this->db->where('id', $state_id);
		$query = $this->db->get('states');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return NULL;
	}
	
	/**
	 * Get cities by state
	 *
	 * @param	int	$state_id	State ID
	 * @return	array	Array of cities
	 */
	public function getCitiesByState($state_id)
	{
		$this->db->where('state_id', $state_id);
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get('cities');
		
		return $query->result_array();
	}
	
	/**
	 * Get city by ID
	 *
	 * @param	int	$city_id	City ID
	 * @return	array|NULL	City data or NULL if not found
	 */
	public function getCityById($city_id)
	{
		$this->db->where('id', $city_id);
		$query = $this->db->get('cities');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return NULL;
	}
	
	/**
	 * Get state name by ID
	 *
	 * @param	int	$state_id	State ID
	 * @return	string|NULL	State name or NULL if not found
	 */
	public function getStateName($state_id)
	{
		$state = $this->getStateById($state_id);
		return $state ? $state['name'] : NULL;
	}
	
	/**
	 * Get city name by ID
	 *
	 * @param	int	$city_id	City ID
	 * @return	string|NULL	City name or NULL if not found
	 */
	public function getCityName($city_id)
	{
		$city = $this->getCityById($city_id);
		return $city ? $city['name'] : NULL;
	}
}

