<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * Bulk import for textbooks, notebooks, and stationery (legacy + erp_products dual-write).
 */
class Book_product_import_model extends CI_Model
{
	const TEXTBOOK_HEADERS = array(
		'product_name*', 'isbn*', 'publisher*', 'board*', 'grade_age_type*',
		'grades', 'ages', 'subjects*', 'types',
		'min_quantity*', 'product_description*', 'gst_percentage*', 'mrp*', 'selling_price*',
		'sku', 'product_code', 'hsn', 'days_to_exchange', 'pointers',
		'packaging_length', 'packaging_width', 'packaging_height', 'packaging_weight',
		'meta_title', 'meta_keywords', 'meta_description',
		'is_individual', 'is_set', 'status',
	);

	const NOTEBOOK_HEADERS = array(
		'product_name*', 'brand*', 'min_quantity*', 'product_description*',
		'gst_percentage*', 'mrp*', 'selling_price*',
		'types', 'isbn', 'sku', 'size', 'binding_type', 'no_of_pages',
		'days_to_exchange', 'pointers',
		'packaging_length', 'packaging_width', 'packaging_height', 'packaging_weight',
		'product_code', 'hsn',
		'meta_title', 'meta_keywords', 'meta_description',
		'is_individual', 'is_set', 'status',
	);

	const NOTEBOOK_BINDING_TYPES = array('center_binding', 'perfect_binding', 'spiral_binding');

	const STATIONERY_HEADERS = array(
		'category*', 'product_name*', 'brand*', 'colour*',
		'min_quantity*', 'product_description*', 'gst_percentage*', 'mrp*', 'selling_price*',
		'isbn', 'sku', 'product_code', 'days_to_exchange', 'pointers',
		'packaging_length', 'packaging_width', 'packaging_height', 'packaging_weight',
		'gst_type', 'hsn',
		'meta_title', 'meta_keywords', 'meta_description',
		'is_individual', 'is_set', 'status',
	);

	const STATIONERY_GST_TYPES = array('igst', 'cgst_sgst');

	const TEMPLATE_DATA_ROWS = 500;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Product_model');
	}

	protected function bootstrapSpreadsheet()
	{
		static $bootstrapped = FALSE;
		if ($bootstrapped)
		{
			return;
		}
		$autoload = APPPATH . 'vendor/autoload.php';
		if (!file_exists($autoload))
		{
			throw new RuntimeException('Composer autoload not found. Run composer install in application/.');
		}
		require_once $autoload;
		$bootstrapped = TRUE;
	}

	/**
	 * @return	Spreadsheet
	 */
	public function buildTextbookTemplate($vendor_id)
	{
		$this->bootstrapSpreadsheet();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Textbooks');

		$this->writeHeaderRow($sheet, self::TEXTBOOK_HEADERS);
		$sheet->fromArray(array(
			'Sample Mathematics Book', '9780000000001', 'Sample Publisher', 'CBSE', 'grade',
			'Class 5, Class 6', '', 'Mathematics', 'Textbook',
			'1', 'Sample product description', '5', '500', '450',
			'SKU-001', 'PC-001', '4901', '7', 'Key highlights here',
			'25', '20', '2', '400',
			'Meta title', 'math, textbook', 'Meta description',
			'1', '1', 'active',
		), NULL, 'A2');

		$refSheets = $this->addReferenceSheets($spreadsheet, $vendor_id, TRUE);
		$this->applyTextbookDropdowns($sheet, $refSheets);

		return $spreadsheet;
	}

	/**
	 * @return	Spreadsheet
	 */
	public function buildNotebookTemplate($vendor_id)
	{
		$this->bootstrapSpreadsheet();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Notebooks');

		$this->writeHeaderRow($sheet, self::NOTEBOOK_HEADERS);
		$sheet->fromArray(array(
			'Sample Ruled Notebook', 'Sample Brand', '1', 'Sample notebook description',
			'5', '120', '100',
			'Notebook', '9780000000002', 'NB-001', 'A4', 'spiral_binding', '200',
			'7', 'Durable cover',
			'30', '21', '1', '250',
			'PC-NB-001', '4820',
			'Meta title', 'notebook', 'Meta description',
			'1', '1', 'active',
		), NULL, 'A2');

		$refSheets = $this->addReferenceSheets($spreadsheet, $vendor_id, FALSE);
		$this->applyNotebookDropdowns($sheet, $refSheets);

		return $spreadsheet;
	}

	/**
	 * @return	Spreadsheet
	 */
	public function buildStationeryTemplate($vendor_id)
	{
		$this->bootstrapSpreadsheet();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Stationery');

		$this->writeHeaderRow($sheet, self::STATIONERY_HEADERS);
		$sheet->fromArray(array(
			'Pens & Pencils', 'Sample Ballpoint Pen', 'Sample Brand', 'Blue',
			'1', 'Smooth writing ballpoint pen', '12', '50', '45',
			'9780000000003', 'ST-001', 'PC-ST-001', '7', 'Key features here',
			'15', '2', '2', '20',
			'igst', '9608',
			'Meta title', 'pen, stationery', 'Meta description',
			'1', '1', 'active',
		), NULL, 'A2');

		$refSheets = $this->addStationeryReferenceSheets($spreadsheet, $vendor_id);
		$this->applyStationeryDropdowns($sheet, $refSheets);

		return $spreadsheet;
	}

	/**
	 * @param	string	$file_path
	 * @param	int		$vendor_id
	 * @return	array
	 */
	public function importTextbooks($file_path, $vendor_id)
	{
		$this->bootstrapSpreadsheet();
		$rows = $this->parseImportFile($file_path, self::TEXTBOOK_HEADERS);
		$lookups = $this->loadTextbookLookups($vendor_id);
		$results = array('total' => 0, 'created' => 0, 'updated' => 0, 'failed' => 0, 'rows' => array());

		foreach ($rows as $item)
		{
			$line = $item['line'];
			$row = $item['data'];
			$results['total']++;

			try
			{
				$parsed = $this->validateAndParseTextbookRow($row, $lookups);
				$status = $this->upsertTextbook($vendor_id, $parsed);
				$results[$status]++;
				$results['rows'][] = array('line' => $line, 'status' => $status, 'message' => ucfirst($status) . ': ' . $parsed['product_name']);
			}
			catch (Exception $e)
			{
				$results['failed']++;
				$results['rows'][] = array('line' => $line, 'status' => 'failed', 'message' => $e->getMessage());
			}
		}

		return $results;
	}

	/**
	 * @param	string	$file_path
	 * @param	int		$vendor_id
	 * @return	array
	 */
	public function importNotebooks($file_path, $vendor_id)
	{
		$this->bootstrapSpreadsheet();
		$rows = $this->parseImportFile($file_path, self::NOTEBOOK_HEADERS);
		$lookups = $this->loadNotebookLookups($vendor_id);
		$results = array('total' => 0, 'created' => 0, 'updated' => 0, 'failed' => 0, 'rows' => array());

		foreach ($rows as $item)
		{
			$line = $item['line'];
			$row = $item['data'];
			$results['total']++;

			try
			{
				$parsed = $this->validateAndParseNotebookRow($row, $lookups);
				$status = $this->upsertNotebook($vendor_id, $parsed);
				$results[$status]++;
				$results['rows'][] = array('line' => $line, 'status' => $status, 'message' => ucfirst($status) . ': ' . $parsed['product_name']);
			}
			catch (Exception $e)
			{
				$results['failed']++;
				$results['rows'][] = array('line' => $line, 'status' => 'failed', 'message' => $e->getMessage());
			}
		}

		return $results;
	}

	/**
	 * @param	string	$file_path
	 * @param	int		$vendor_id
	 * @return	array
	 */
	public function importStationery($file_path, $vendor_id)
	{
		$this->bootstrapSpreadsheet();
		$rows = $this->parseImportFile($file_path, self::STATIONERY_HEADERS);
		$lookups = $this->loadStationeryLookups($vendor_id);
		$results = array('total' => 0, 'created' => 0, 'updated' => 0, 'failed' => 0, 'rows' => array());

		foreach ($rows as $item)
		{
			$line = $item['line'];
			$row = $item['data'];
			$results['total']++;

			try
			{
				$parsed = $this->validateAndParseStationeryRow($row, $lookups);
				$status = $this->upsertStationery($vendor_id, $parsed);
				$results[$status]++;
				$results['rows'][] = array('line' => $line, 'status' => $status, 'message' => ucfirst($status) . ': ' . $parsed['product_name']);
			}
			catch (Exception $e)
			{
				$results['failed']++;
				$results['rows'][] = array('line' => $line, 'status' => 'failed', 'message' => $e->getMessage());
			}
		}

		return $results;
	}

	protected function writeHeaderRow($sheet, array $headers)
	{
		foreach ($headers as $i => $header)
		{
			$sheet->setCellValueByColumnAndRow($i + 1, 1, $header);
		}
	}

	protected function addReferenceSheets(Spreadsheet $spreadsheet, $vendor_id, $include_boards)
	{
		$refs = array();
		$refs['Publishers'] = $this->addNameSheet($spreadsheet, 'Publishers', $this->fetchNames('erp_textbook_publishers', $vendor_id));

		if ($include_boards)
		{
			$this->db->select('board_name AS name');
			$this->db->from('erp_school_boards');
			$this->db->where('(vendor_id IS NULL OR vendor_id = ' . (int) $vendor_id . ')', NULL, FALSE);
			$this->db->where('status', 'active');
			$this->db->order_by('board_name', 'ASC');
			$boards = array_column($this->db->get()->result_array(), 'name');
			$refs['Boards'] = $this->addNameSheet($spreadsheet, 'Boards', $boards);
			$refs['Grades'] = $this->addNameSheet($spreadsheet, 'Grades', $this->fetchNames('erp_textbook_grades', $vendor_id));
			$refs['Ages'] = $this->addNameSheet($spreadsheet, 'Ages', $this->fetchNames('erp_textbook_ages', $vendor_id));
			$refs['Subjects'] = $this->addNameSheet($spreadsheet, 'Subjects', $this->fetchNames('erp_textbook_subjects', $vendor_id));
		}

		$refs['Types'] = $this->addNameSheet($spreadsheet, 'Types', $this->fetchNames('erp_textbook_types', $vendor_id));

		return $refs;
	}

	protected function fetchNames($table, $vendor_id)
	{
		$this->db->select('name');
		$this->db->from($table);
		$this->db->where('vendor_id', (int) $vendor_id);
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'ASC');
		return array_column($this->db->get()->result_array(), 'name');
	}

	protected function addNameSheet(Spreadsheet $spreadsheet, $title, array $names)
	{
		$sheet = $spreadsheet->createSheet();
		$sheet->setTitle($title);
		$sheet->setCellValue('A1', 'name');
		$row = 2;
		if (empty($names))
		{
			$sheet->setCellValue('A2', '(none configured - add in system first)');
			return array('title' => $title, 'last_row' => 2);
		}
		foreach ($names as $name)
		{
			$sheet->setCellValue('A' . $row, $name);
			$row++;
		}

		return array('title' => $title, 'last_row' => $row - 1);
	}

	protected function sheetListFormula(array $ref)
	{
		$title = str_replace("'", "''", $ref['title']);
		return "'" . $title . "'!\$A\$2:\$A\$" . (int) $ref['last_row'];
	}

	protected function headerColumnMap(array $headers)
	{
		$map = array();
		foreach ($headers as $i => $header)
		{
			$key = str_replace('*', '', $header);
			$map[$key] = Coordinate::stringFromColumnIndex($i + 1);
		}
		return $map;
	}

	protected function applyTextbookDropdowns($sheet, array $refSheets)
	{
		$cols = $this->headerColumnMap(self::TEXTBOOK_HEADERS);
		$endRow = self::TEMPLATE_DATA_ROWS + 1;

		if (isset($refSheets['Publishers']))
		{
			$this->applyListValidation($sheet, $cols['publisher'], 2, $endRow, $this->sheetListFormula($refSheets['Publishers']), TRUE, 'Publisher', 'Select a publisher from your catalog.');
		}
		if (isset($refSheets['Boards']))
		{
			$this->applyListValidation($sheet, $cols['board'], 2, $endRow, $this->sheetListFormula($refSheets['Boards']), TRUE, 'Board', 'Select a school board.');
		}
		$this->applyListValidation($sheet, $cols['grade_age_type'], 2, $endRow, '"grade,age"', TRUE, 'Grade or Age', 'Choose grade or age.');
		if (isset($refSheets['Grades']))
		{
			$this->applyListValidation($sheet, $cols['grades'], 2, $endRow, $this->sheetListFormula($refSheets['Grades']), FALSE, 'Grades', 'Pick from list or type multiple comma-separated grade names.');
		}
		if (isset($refSheets['Ages']))
		{
			$this->applyListValidation($sheet, $cols['ages'], 2, $endRow, $this->sheetListFormula($refSheets['Ages']), FALSE, 'Ages', 'Pick from list or type multiple comma-separated age names.');
		}
		if (isset($refSheets['Subjects']))
		{
			$this->applyListValidation($sheet, $cols['subjects'], 2, $endRow, $this->sheetListFormula($refSheets['Subjects']), FALSE, 'Subjects', 'Pick from list or type multiple comma-separated subject names.');
		}
		if (isset($refSheets['Types']))
		{
			$this->applyListValidation($sheet, $cols['types'], 2, $endRow, $this->sheetListFormula($refSheets['Types']), FALSE, 'Types', 'Pick from list or type multiple comma-separated type names.');
		}
		$this->applyListValidation($sheet, $cols['is_individual'], 2, $endRow, '"0,1"', TRUE, 'Is Individual', '1 = yes, 0 = no.');
		$this->applyListValidation($sheet, $cols['is_set'], 2, $endRow, '"0,1"', TRUE, 'Is Set', '1 = yes, 0 = no.');
		$this->applyListValidation($sheet, $cols['status'], 2, $endRow, '"active,inactive"', TRUE, 'Status', 'Product visibility status.');
	}

	protected function applyNotebookDropdowns($sheet, array $refSheets)
	{
		$cols = $this->headerColumnMap(self::NOTEBOOK_HEADERS);
		$endRow = self::TEMPLATE_DATA_ROWS + 1;

		if (isset($refSheets['Publishers']))
		{
			$this->applyListValidation($sheet, $cols['brand'], 2, $endRow, $this->sheetListFormula($refSheets['Publishers']), TRUE, 'Brand', 'Select a brand from your catalog.');
		}
		if (isset($refSheets['Types']))
		{
			$this->applyListValidation($sheet, $cols['types'], 2, $endRow, $this->sheetListFormula($refSheets['Types']), FALSE, 'Types', 'Pick from list or type multiple comma-separated type names.');
		}
		$this->applyListValidation($sheet, $cols['binding_type'], 2, $endRow, '"center_binding,perfect_binding,spiral_binding"', FALSE, 'Binding Type', 'Select binding type or type the value manually.');
		$this->applyListValidation($sheet, $cols['is_individual'], 2, $endRow, '"0,1"', TRUE, 'Is Individual', '1 = yes, 0 = no.');
		$this->applyListValidation($sheet, $cols['is_set'], 2, $endRow, '"0,1"', TRUE, 'Is Set', '1 = yes, 0 = no.');
		$this->applyListValidation($sheet, $cols['status'], 2, $endRow, '"active,inactive"', TRUE, 'Status', 'Product visibility status.');
	}

	protected function addStationeryReferenceSheets(Spreadsheet $spreadsheet, $vendor_id)
	{
		$refs = array();
		$refs['Categories'] = $this->addNameSheet($spreadsheet, 'Categories', $this->fetchNames('erp_stationery_categories', $vendor_id));
		$refs['Brands'] = $this->addNameSheet($spreadsheet, 'Brands', $this->fetchNames('erp_stationery_brands', $vendor_id));
		$refs['Colours'] = $this->addNameSheet($spreadsheet, 'Colours', $this->fetchNames('erp_stationery_colours', $vendor_id));

		return $refs;
	}

	protected function applyStationeryDropdowns($sheet, array $refSheets)
	{
		$cols = $this->headerColumnMap(self::STATIONERY_HEADERS);
		$endRow = self::TEMPLATE_DATA_ROWS + 1;

		if (isset($refSheets['Categories']))
		{
			$this->applyListValidation($sheet, $cols['category'], 2, $endRow, $this->sheetListFormula($refSheets['Categories']), FALSE, 'Category', 'Pick from list or type a new category name.');
		}
		if (isset($refSheets['Brands']))
		{
			$this->applyListValidation($sheet, $cols['brand'], 2, $endRow, $this->sheetListFormula($refSheets['Brands']), FALSE, 'Brand', 'Pick from list or type a new brand name.');
		}
		if (isset($refSheets['Colours']))
		{
			$this->applyListValidation($sheet, $cols['colour'], 2, $endRow, $this->sheetListFormula($refSheets['Colours']), FALSE, 'Colour', 'Pick from list or type a new colour name.');
		}
		$this->applyListValidation($sheet, $cols['gst_type'], 2, $endRow, '"igst,cgst_sgst"', FALSE, 'GST Type', 'Select igst or cgst_sgst, or leave blank.');
		$this->applyListValidation($sheet, $cols['is_individual'], 2, $endRow, '"0,1"', TRUE, 'Is Individual', '1 = yes, 0 = no.');
		$this->applyListValidation($sheet, $cols['is_set'], 2, $endRow, '"0,1"', TRUE, 'Is Set', '1 = yes, 0 = no.');
		$this->applyListValidation($sheet, $cols['status'], 2, $endRow, '"active,inactive"', TRUE, 'Status', 'Product visibility status.');
	}

	/**
	 * @param	string	$column		Column letter
	 * @param	bool	$strict		When FALSE, user can still type values not in the list (for comma-separated fields).
	 */
	protected function applyListValidation($sheet, $column, $startRow, $endRow, $formula, $strict, $promptTitle = '', $prompt = '')
	{
		if ($column === '' || $formula === '')
		{
			return;
		}

		$template = new DataValidation();
		$template->setType(DataValidation::TYPE_LIST);
		$template->setErrorStyle($strict ? DataValidation::STYLE_STOP : DataValidation::STYLE_INFORMATION);
		$template->setAllowBlank(TRUE);
		$template->setShowDropDown(TRUE);
		$template->setShowInputMessage(TRUE);
		$template->setShowErrorMessage($strict);
		$template->setFormula1($formula);
		if ($promptTitle !== '')
		{
			$template->setPromptTitle($promptTitle);
		}
		if ($prompt !== '')
		{
			$template->setPrompt($prompt);
		}
		if ($strict)
		{
			$template->setErrorTitle('Invalid value');
			$template->setError('Please choose a value from the dropdown list.');
		}

		$sheet->setDataValidation($column . $startRow . ':' . $column . $endRow, $template);
	}

	protected function saveSpreadsheetToTemp(Spreadsheet $spreadsheet, $prefix)
	{
		$path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $prefix . '_' . uniqid() . '.xlsx';
		$writer = new Xlsx($spreadsheet);
		$writer->save($path);
		return $path;
	}

	protected function parseImportFile($file_path, array $expected_headers)
	{
		$spreadsheet = IOFactory::load($file_path);
		$sheet = $spreadsheet->getSheet(0);
		$highestRow = (int) $sheet->getHighestDataRow();
		$highestCol = $sheet->getHighestDataColumn();

		if ($highestRow < 2)
		{
			throw new RuntimeException('The file has no data rows.');
		}

		$headerRow = $sheet->rangeToArray('A1:' . $highestCol . '1', NULL, TRUE, FALSE)[0];
		$headerMap = $this->buildHeaderMap($headerRow, $expected_headers);

		$rows = array();
		for ($r = 2; $r <= $highestRow; $r++)
		{
			$lineValues = $sheet->rangeToArray('A' . $r . ':' . $highestCol . $r, NULL, TRUE, FALSE)[0];
			$data = array();
			$hasValue = FALSE;
			foreach ($headerMap as $key => $colIndex)
			{
				$value = isset($lineValues[$colIndex]) ? trim((string) $lineValues[$colIndex]) : '';
				$data[$key] = $value;
				if ($value !== '')
				{
					$hasValue = TRUE;
				}
			}
			if (!$hasValue)
			{
				continue;
			}
			$rows[] = array('line' => $r, 'data' => $data);
		}

		if (empty($rows))
		{
			throw new RuntimeException('No non-empty data rows found.');
		}

		return $rows;
	}

	protected function buildHeaderMap(array $headerRow, array $expected_headers)
	{
		$normalizedExpected = array();
		foreach ($expected_headers as $header)
		{
			$normalizedExpected[$this->normalizeHeaderKey($header)] = $this->stripRequiredMarker($header);
		}

		$map = array();
		foreach ($headerRow as $index => $cell)
		{
			$key = $this->normalizeHeaderKey($cell);
			if (isset($normalizedExpected[$key]))
			{
				$map[$normalizedExpected[$key]] = $index;
			}
		}

		$missing = array();
		foreach ($expected_headers as $header)
		{
			if (strpos($header, '*') !== FALSE)
			{
				$key = $this->stripRequiredMarker($header);
				if (!isset($map[$key]))
				{
					$missing[] = $header;
				}
			}
		}

		if (!empty($missing))
		{
			throw new RuntimeException('Missing required columns: ' . implode(', ', $missing));
		}

		return $map;
	}

	protected function normalizeHeaderKey($value)
	{
		return strtolower(trim(str_replace('*', '', (string) $value)));
	}

	protected function stripRequiredMarker($header)
	{
		return str_replace('*', '', $header);
	}

	protected function loadTextbookLookups($vendor_id)
	{
		return array(
			'publishers' => $this->nameIdMap('erp_textbook_publishers', $vendor_id),
			'boards' => $this->boardNameIdMap($vendor_id),
			'grades' => $this->nameIdMap('erp_textbook_grades', $vendor_id),
			'ages' => $this->nameIdMap('erp_textbook_ages', $vendor_id),
			'subjects' => $this->nameIdMap('erp_textbook_subjects', $vendor_id),
			'types' => $this->nameIdMap('erp_textbook_types', $vendor_id),
		);
	}

	protected function loadNotebookLookups($vendor_id)
	{
		return array(
			'brands' => $this->nameIdMap('erp_textbook_publishers', $vendor_id),
			'types' => $this->nameIdMap('erp_textbook_types', $vendor_id),
		);
	}

	protected function loadStationeryLookups($vendor_id)
	{
		return array(
			'vendor_id' => (int) $vendor_id,
			'categories' => $this->nameIdMap('erp_stationery_categories', $vendor_id),
			'brands' => $this->nameIdMap('erp_stationery_brands', $vendor_id),
			'colours' => $this->nameIdMap('erp_stationery_colours', $vendor_id),
		);
	}

	protected function resolveOrCreateStationeryLookup($table, $vendor_id, $name, array &$lookupMap)
	{
		$name = trim((string) $name);
		if ($name === '')
		{
			throw new RuntimeException('Lookup name cannot be empty.');
		}

		$key = $this->normalizeLookupName($name);
		if (isset($lookupMap[$key]))
		{
			return (int) $lookupMap[$key];
		}

		$now = date('Y-m-d H:i:s');
		$this->db->insert($table, array(
			'vendor_id' => (int) $vendor_id,
			'name' => $name,
			'status' => 'active',
			'created_at' => $now,
			'updated_at' => $now,
		));
		$id = (int) $this->db->insert_id();
		if ($id <= 0)
		{
			throw new RuntimeException('Failed to create lookup "' . $name . '".');
		}

		$lookupMap[$key] = $id;
		return $id;
	}

	protected function nameIdMap($table, $vendor_id)
	{
		$this->db->select('id, name');
		$this->db->from($table);
		$this->db->where('vendor_id', (int) $vendor_id);
		$this->db->where('status', 'active');
		$map = array();
		foreach ($this->db->get()->result_array() as $row)
		{
			$map[$this->normalizeLookupName($row['name'])] = (int) $row['id'];
		}
		return $map;
	}

	protected function boardNameIdMap($vendor_id)
	{
		$this->db->select('id, board_name');
		$this->db->from('erp_school_boards');
		$this->db->where('(vendor_id IS NULL OR vendor_id = ' . (int) $vendor_id . ')', NULL, FALSE);
		$this->db->where('status', 'active');
		$map = array();
		foreach ($this->db->get()->result_array() as $row)
		{
			$map[$this->normalizeLookupName($row['board_name'])] = (int) $row['id'];
		}
		return $map;
	}

	protected function normalizeLookupName($name)
	{
		return strtolower(trim((string) $name));
	}

	protected function resolveNames(array $names, array $lookupMap, $label)
	{
		$ids = array();
		foreach ($names as $name)
		{
			$key = $this->normalizeLookupName($name);
			if ($key === '')
			{
				continue;
			}
			if (!isset($lookupMap[$key]))
			{
				throw new RuntimeException($label . ' "' . $name . '" not found.');
			}
			$ids[] = $lookupMap[$key];
		}
		return array_values(array_unique($ids));
	}

	protected function splitList($value)
	{
		if ($value === '')
		{
			return array();
		}
		$parts = preg_split('/\s*,\s*/', $value);
		$out = array();
		foreach ($parts as $part)
		{
			$part = trim($part);
			if ($part !== '')
			{
				$out[] = $part;
			}
		}
		return $out;
	}

	protected function requireField(array $row, $key, $label = NULL)
	{
		if (!isset($row[$key]) || trim((string) $row[$key]) === '')
		{
			throw new RuntimeException(($label ?: $key) . ' is required.');
		}
		return trim((string) $row[$key]);
	}

	protected function optionalField(array $row, $key)
	{
		return isset($row[$key]) ? trim((string) $row[$key]) : '';
	}

	protected function parseBool01($value, $default = 0)
	{
		if ($value === '' || $value === NULL)
		{
			return (int) $default;
		}
		$v = strtolower(trim((string) $value));
		if (in_array($v, array('1', 'yes', 'true', 'y'), TRUE))
		{
			return 1;
		}
		if (in_array($v, array('0', 'no', 'false', 'n'), TRUE))
		{
			return 0;
		}
		return (int) $default;
	}

	protected function parseStatus($value)
	{
		$v = strtolower(trim((string) $value));
		if ($v === '' || $v === 'active')
		{
			return 'active';
		}
		if ($v === 'inactive')
		{
			return 'inactive';
		}
		throw new RuntimeException('status must be active or inactive.');
	}

	protected function validateAndParseTextbookRow(array $row, array $lookups)
	{
		$product_name = $this->requireField($row, 'product_name', 'product_name');
		$isbn = $this->requireField($row, 'isbn', 'isbn');
		$publisher_name = $this->requireField($row, 'publisher', 'publisher');
		$board_name = $this->requireField($row, 'board', 'board');
		$grade_age_type = strtolower($this->requireField($row, 'grade_age_type', 'grade_age_type'));

		if (!in_array($grade_age_type, array('grade', 'age'), TRUE))
		{
			throw new RuntimeException('grade_age_type must be grade or age.');
		}

		$publisher_key = $this->normalizeLookupName($publisher_name);
		if (!isset($lookups['publishers'][$publisher_key]))
		{
			throw new RuntimeException('Publisher "' . $publisher_name . '" not found.');
		}

		$board_key = $this->normalizeLookupName($board_name);
		if (!isset($lookups['boards'][$board_key]))
		{
			throw new RuntimeException('Board "' . $board_name . '" not found.');
		}

		$grade_ids = array();
		$age_ids = array();
		if ($grade_age_type === 'grade')
		{
			$grade_ids = $this->resolveNames($this->splitList($this->optionalField($row, 'grades')), $lookups['grades'], 'Grade');
			if (empty($grade_ids))
			{
				throw new RuntimeException('grades is required when grade_age_type is grade.');
			}
		}
		else
		{
			$age_ids = $this->resolveNames($this->splitList($this->optionalField($row, 'ages')), $lookups['ages'], 'Age');
			if (empty($age_ids))
			{
				throw new RuntimeException('ages is required when grade_age_type is age.');
			}
		}

		$subject_ids = $this->resolveNames($this->splitList($this->requireField($row, 'subjects', 'subjects')), $lookups['subjects'], 'Subject');
		$type_ids = $this->resolveNames($this->splitList($this->optionalField($row, 'types')), $lookups['types'], 'Type');

		$min_quantity = (int) $this->requireField($row, 'min_quantity', 'min_quantity');
		if ($min_quantity < 1)
		{
			throw new RuntimeException('min_quantity must be at least 1.');
		}

		$product_description = $this->requireField($row, 'product_description', 'product_description');
		$gst_percentage = (float) $this->requireField($row, 'gst_percentage', 'gst_percentage');
		$mrp = (float) $this->requireField($row, 'mrp', 'mrp');
		$selling_price = (float) $this->requireField($row, 'selling_price', 'selling_price');

		if ($mrp < $selling_price)
		{
			throw new RuntimeException('MRP must be greater than or equal to selling_price.');
		}

		$status = $this->parseStatus($this->optionalField($row, 'status'));

		return array(
			'product_name' => $product_name,
			'isbn' => $isbn,
			'sku' => $this->nullable($this->optionalField($row, 'sku')),
			'publisher_id' => $lookups['publishers'][$publisher_key],
			'board_id' => $lookups['boards'][$board_key],
			'grade_age_type' => $grade_age_type,
			'grade_ids' => $grade_ids,
			'age_ids' => $age_ids,
			'subject_ids' => $subject_ids,
			'type_ids' => $type_ids,
			'min_quantity' => $min_quantity,
			'product_description' => $product_description,
			'gst_percentage' => $gst_percentage,
			'mrp' => $mrp,
			'selling_price' => $selling_price,
			'product_code' => $this->nullable($this->optionalField($row, 'product_code')),
			'hsn' => $this->nullable($this->optionalField($row, 'hsn')),
			'days_to_exchange' => $this->nullableInt($this->optionalField($row, 'days_to_exchange')),
			'pointers' => $this->nullable($this->optionalField($row, 'pointers')),
			'packaging_length' => $this->nullableFloat($this->optionalField($row, 'packaging_length')),
			'packaging_width' => $this->nullableFloat($this->optionalField($row, 'packaging_width')),
			'packaging_height' => $this->nullableFloat($this->optionalField($row, 'packaging_height')),
			'packaging_weight' => $this->nullableFloat($this->optionalField($row, 'packaging_weight')),
			'meta_title' => $this->nullable($this->optionalField($row, 'meta_title')),
			'meta_keywords' => $this->nullable($this->optionalField($row, 'meta_keywords')),
			'meta_description' => $this->nullable($this->optionalField($row, 'meta_description')),
			'is_individual' => $this->parseBool01($this->optionalField($row, 'is_individual'), 0),
			'is_set' => $this->parseBool01($this->optionalField($row, 'is_set'), 0),
			'status' => $status,
		);
	}

	protected function validateAndParseNotebookRow(array $row, array $lookups)
	{
		$product_name = $this->requireField($row, 'product_name', 'product_name');
		$brand_name = $this->requireField($row, 'brand', 'brand');
		$brand_key = $this->normalizeLookupName($brand_name);
		if (!isset($lookups['brands'][$brand_key]))
		{
			throw new RuntimeException('Brand "' . $brand_name . '" not found.');
		}

		$min_quantity = (int) $this->requireField($row, 'min_quantity', 'min_quantity');
		if ($min_quantity < 1)
		{
			throw new RuntimeException('min_quantity must be at least 1.');
		}

		$product_description = $this->requireField($row, 'product_description', 'product_description');
		$gst_percentage = (float) $this->requireField($row, 'gst_percentage', 'gst_percentage');
		$mrp = (float) $this->requireField($row, 'mrp', 'mrp');
		$selling_price = (float) $this->requireField($row, 'selling_price', 'selling_price');

		if ($mrp < $selling_price)
		{
			throw new RuntimeException('MRP must be greater than or equal to selling_price.');
		}

		$binding_type = $this->nullable($this->optionalField($row, 'binding_type'));
		if ($binding_type !== NULL && !in_array($binding_type, self::NOTEBOOK_BINDING_TYPES, TRUE))
		{
			throw new RuntimeException('binding_type must be one of: ' . implode(', ', self::NOTEBOOK_BINDING_TYPES));
		}

		$type_ids = $this->resolveNames($this->splitList($this->optionalField($row, 'types')), $lookups['types'], 'Type');
		$isbn = $this->nullable($this->optionalField($row, 'isbn'));
		$sku = $this->nullable($this->optionalField($row, 'sku'));

		if ($isbn === NULL && $sku === NULL)
		{
			throw new RuntimeException('At least one of isbn or sku is required for duplicate detection.');
		}

		return array(
			'product_name' => $product_name,
			'brand_id' => $lookups['brands'][$brand_key],
			'min_quantity' => $min_quantity,
			'product_description' => $product_description,
			'gst_percentage' => $gst_percentage,
			'mrp' => $mrp,
			'selling_price' => $selling_price,
			'type_ids' => $type_ids,
			'isbn' => $isbn,
			'sku' => $sku,
			'size' => $this->nullable($this->optionalField($row, 'size')),
			'binding_type' => $binding_type,
			'no_of_pages' => $this->nullableInt($this->optionalField($row, 'no_of_pages')),
			'days_to_exchange' => $this->nullableInt($this->optionalField($row, 'days_to_exchange')),
			'pointers' => $this->nullable($this->optionalField($row, 'pointers')),
			'packaging_length' => $this->nullableFloat($this->optionalField($row, 'packaging_length')),
			'packaging_width' => $this->nullableFloat($this->optionalField($row, 'packaging_width')),
			'packaging_height' => $this->nullableFloat($this->optionalField($row, 'packaging_height')),
			'packaging_weight' => $this->nullableFloat($this->optionalField($row, 'packaging_weight')),
			'product_code' => $this->nullable($this->optionalField($row, 'product_code')),
			'hsn' => $this->nullable($this->optionalField($row, 'hsn')),
			'meta_title' => $this->nullable($this->optionalField($row, 'meta_title')),
			'meta_keywords' => $this->nullable($this->optionalField($row, 'meta_keywords')),
			'meta_description' => $this->nullable($this->optionalField($row, 'meta_description')),
			'is_individual' => $this->parseBool01($this->optionalField($row, 'is_individual'), 0),
			'is_set' => $this->parseBool01($this->optionalField($row, 'is_set'), 0),
			'status' => $this->parseStatus($this->optionalField($row, 'status')),
		);
	}

	protected function validateAndParseStationeryRow(array $row, array &$lookups)
	{
		$vendor_id = $lookups['vendor_id'];

		$category_name = $this->requireField($row, 'category', 'category');
		$category_id = $this->resolveOrCreateStationeryLookup(
			'erp_stationery_categories',
			$vendor_id,
			$category_name,
			$lookups['categories']
		);

		$product_name = $this->requireField($row, 'product_name', 'product_name');

		$brand_name = $this->requireField($row, 'brand', 'brand');
		$brand_id = $this->resolveOrCreateStationeryLookup(
			'erp_stationery_brands',
			$vendor_id,
			$brand_name,
			$lookups['brands']
		);

		$colour_name = $this->requireField($row, 'colour', 'colour');
		$colour_id = $this->resolveOrCreateStationeryLookup(
			'erp_stationery_colours',
			$vendor_id,
			$colour_name,
			$lookups['colours']
		);

		$min_quantity = (int) $this->requireField($row, 'min_quantity', 'min_quantity');
		if ($min_quantity < 1)
		{
			throw new RuntimeException('min_quantity must be at least 1.');
		}

		$product_description = $this->requireField($row, 'product_description', 'product_description');
		$gst_percentage = (float) $this->requireField($row, 'gst_percentage', 'gst_percentage');
		$mrp = (float) $this->requireField($row, 'mrp', 'mrp');
		$selling_price = (float) $this->requireField($row, 'selling_price', 'selling_price');

		if ($mrp < $selling_price)
		{
			throw new RuntimeException('MRP must be greater than or equal to selling_price.');
		}

		$gst_type = $this->nullable($this->optionalField($row, 'gst_type'));
		if ($gst_type !== NULL && !in_array($gst_type, self::STATIONERY_GST_TYPES, TRUE))
		{
			throw new RuntimeException('gst_type must be igst or cgst_sgst.');
		}

		return array(
			'category_id' => $category_id,
			'brand_id' => $brand_id,
			'colour_id' => $colour_id,
			'product_name' => $product_name,
			'min_quantity' => $min_quantity,
			'product_description' => $product_description,
			'gst_percentage' => $gst_percentage,
			'gst_type' => $gst_type,
			'mrp' => $mrp,
			'selling_price' => $selling_price,
			'isbn' => $this->nullable($this->optionalField($row, 'isbn')),
			'sku' => $this->nullable($this->optionalField($row, 'sku')),
			'product_code' => $this->nullable($this->optionalField($row, 'product_code')),
			'days_to_exchange' => $this->nullableInt($this->optionalField($row, 'days_to_exchange')),
			'pointers' => $this->nullable($this->optionalField($row, 'pointers')),
			'packaging_length' => $this->nullableFloat($this->optionalField($row, 'packaging_length')),
			'packaging_width' => $this->nullableFloat($this->optionalField($row, 'packaging_width')),
			'packaging_height' => $this->nullableFloat($this->optionalField($row, 'packaging_height')),
			'packaging_weight' => $this->nullableFloat($this->optionalField($row, 'packaging_weight')),
			'hsn' => $this->nullable($this->optionalField($row, 'hsn')),
			'meta_title' => $this->nullable($this->optionalField($row, 'meta_title')),
			'meta_keywords' => $this->nullable($this->optionalField($row, 'meta_keywords')),
			'meta_description' => $this->nullable($this->optionalField($row, 'meta_description')),
			'is_individual' => $this->parseBool01($this->optionalField($row, 'is_individual'), 0),
			'is_set' => $this->parseBool01($this->optionalField($row, 'is_set'), 0),
			'status' => $this->parseStatus($this->optionalField($row, 'status')),
		);
	}

	protected function nullable($value)
	{
		$value = trim((string) $value);
		return $value === '' ? NULL : $value;
	}

	protected function nullableInt($value)
	{
		$value = trim((string) $value);
		if ($value === '')
		{
			return NULL;
		}
		return (int) $value;
	}

	protected function nullableFloat($value)
	{
		$value = trim((string) $value);
		if ($value === '')
		{
			return NULL;
		}
		return (float) $value;
	}

	protected function findExistingLegacyRow($table, $vendor_id, $isbn, $sku)
	{
		if ($isbn !== NULL && $isbn !== '')
		{
			$this->db->from($table);
			$this->db->where('vendor_id', (int) $vendor_id);
			$this->db->where('isbn', $isbn);
			$matches = $this->db->get()->result_array();
			if (count($matches) > 1)
			{
				throw new RuntimeException('Multiple products found with ISBN "' . $isbn . '".');
			}
			if (count($matches) === 1)
			{
				return $matches[0];
			}
		}

		if ($sku !== NULL && $sku !== '')
		{
			$this->db->from($table);
			$this->db->where('vendor_id', (int) $vendor_id);
			$this->db->where('sku', $sku);
			$matches = $this->db->get()->result_array();
			if (count($matches) > 1)
			{
				throw new RuntimeException('Multiple products found with SKU "' . $sku . '".');
			}
			if (count($matches) === 1)
			{
				return $matches[0];
			}
		}

		return NULL;
	}

	protected function upsertTextbook($vendor_id, array $parsed)
	{
		$this->db->trans_start();

		$existing = $this->findExistingLegacyRow('erp_textbooks', $vendor_id, $parsed['isbn'], $parsed['sku']);
		$is_update = !empty($existing);
		$now = date('Y-m-d H:i:s');

		$legacy_data = array(
			'publisher_id' => $parsed['publisher_id'],
			'board_id' => $parsed['board_id'],
			'grade_age_type' => $parsed['grade_age_type'],
			'product_name' => $parsed['product_name'],
			'isbn' => $parsed['isbn'],
			'min_quantity' => $parsed['min_quantity'],
			'days_to_exchange' => $parsed['days_to_exchange'],
			'pointers' => $parsed['pointers'],
			'product_description' => $parsed['product_description'],
			'packaging_length' => $parsed['packaging_length'],
			'packaging_width' => $parsed['packaging_width'],
			'packaging_height' => $parsed['packaging_height'],
			'packaging_weight' => $parsed['packaging_weight'],
			'gst_percentage' => $parsed['gst_percentage'],
			'hsn' => $parsed['hsn'],
			'product_code' => $parsed['product_code'],
			'sku' => $parsed['sku'],
			'mrp' => $parsed['mrp'],
			'selling_price' => $parsed['selling_price'],
			'meta_title' => $parsed['meta_title'],
			'meta_keywords' => $parsed['meta_keywords'],
			'meta_description' => $parsed['meta_description'],
			'is_individual' => $parsed['is_individual'],
			'is_set' => $parsed['is_set'],
			'status' => $parsed['status'],
			'updated_at' => $now,
		);

		if ($is_update)
		{
			$textbook_id = (int) $existing['id'];
			$this->db->where('id', $textbook_id);
			$this->db->where('vendor_id', (int) $vendor_id);
			$this->db->update('erp_textbooks', $legacy_data);
		}
		else
		{
			$legacy_data['vendor_id'] = (int) $vendor_id;
			$legacy_data['created_at'] = $now;
			$this->db->insert('erp_textbooks', $legacy_data);
			$textbook_id = (int) $this->db->insert_id();
		}

		if ($textbook_id <= 0)
		{
			$this->db->trans_complete();
			throw new RuntimeException('Failed to save textbook.');
		}

		$product_payload = array(
			'vendor_id' => (int) $vendor_id,
			'category_id' => NULL,
			'type' => 'textbook',
			'product_name' => $legacy_data['product_name'],
			'description' => $legacy_data['product_description'],
			'status' => $this->Product_model->normalize_product_status($legacy_data['status']),
			'brand_id' => $legacy_data['publisher_id'],
			'board_id' => $legacy_data['board_id'],
			'grade_id' => NULL,
			'subject_id' => NULL,
			'discount' => 0,
			'discount_amount' => 0,
			'selling_price' => $legacy_data['selling_price'],
			'product_mrp' => $legacy_data['mrp'],
			'gst' => $legacy_data['gst_percentage'],
			'isbn' => $legacy_data['isbn'],
			'hsn' => $legacy_data['hsn'],
			'sku' => $legacy_data['sku'],
			'product_code' => $legacy_data['product_code'],
			'pointers' => $legacy_data['pointers'],
			'quantity' => 0,
			'length' => $legacy_data['packaging_length'],
			'width' => $legacy_data['packaging_width'],
			'height' => $legacy_data['packaging_height'],
			'weight' => $legacy_data['packaging_weight'],
			'meta_title' => $legacy_data['meta_title'],
			'meta_keyword' => $legacy_data['meta_keywords'],
			'meta_description' => $legacy_data['meta_description'],
			'min_quantity' => $legacy_data['min_quantity'],
			'no_of_pages' => NULL,
			'binding_type' => NULL,
			'material_id' => NULL,
			'legacy_table' => 'erp_textbooks',
			'legacy_id' => $textbook_id,
		);

		$product = $this->Product_model->get_product_by_legacy('erp_textbooks', $textbook_id, $vendor_id);
		if ($product)
		{
			$this->Product_model->update_product($product['id'], $product_payload);
		}
		else
		{
			$this->Product_model->create_product($product_payload);
		}

		$this->syncTextbookMappings($textbook_id, $parsed);

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			throw new RuntimeException('Database error while saving textbook.');
		}

		return $is_update ? 'updated' : 'created';
	}

	protected function syncTextbookMappings($textbook_id, array $parsed)
	{
		$this->db->where('textbook_id', (int) $textbook_id)->delete('erp_textbook_type_mapping');
		$this->db->where('textbook_id', (int) $textbook_id)->delete('erp_textbook_grade_mapping');
		$this->db->where('textbook_id', (int) $textbook_id)->delete('erp_textbook_age_mapping');
		$this->db->where('textbook_id', (int) $textbook_id)->delete('erp_textbook_subject_mapping');

		$now = date('Y-m-d H:i:s');
		foreach ($parsed['type_ids'] as $type_id)
		{
			$this->db->insert('erp_textbook_type_mapping', array(
				'textbook_id' => (int) $textbook_id,
				'type_id' => (int) $type_id,
				'created_at' => $now,
			));
		}
		foreach ($parsed['grade_ids'] as $grade_id)
		{
			$this->db->insert('erp_textbook_grade_mapping', array(
				'textbook_id' => (int) $textbook_id,
				'grade_id' => (int) $grade_id,
				'created_at' => $now,
			));
		}
		foreach ($parsed['age_ids'] as $age_id)
		{
			$this->db->insert('erp_textbook_age_mapping', array(
				'textbook_id' => (int) $textbook_id,
				'age_id' => (int) $age_id,
				'created_at' => $now,
			));
		}
		foreach ($parsed['subject_ids'] as $subject_id)
		{
			$this->db->insert('erp_textbook_subject_mapping', array(
				'textbook_id' => (int) $textbook_id,
				'subject_id' => (int) $subject_id,
				'created_at' => $now,
			));
		}
	}

	protected function upsertNotebook($vendor_id, array $parsed)
	{
		$this->db->trans_start();

		$existing = $this->findExistingLegacyRow('erp_notebooks', $vendor_id, $parsed['isbn'], $parsed['sku']);
		$is_update = !empty($existing);
		$now = date('Y-m-d H:i:s');

		$legacy_data = array(
			'brand_id' => $parsed['brand_id'],
			'product_name' => $parsed['product_name'],
			'isbn' => $parsed['isbn'],
			'size' => $parsed['size'],
			'binding_type' => $parsed['binding_type'],
			'no_of_pages' => $parsed['no_of_pages'],
			'min_quantity' => $parsed['min_quantity'],
			'days_to_exchange' => $parsed['days_to_exchange'],
			'pointers' => $parsed['pointers'],
			'product_description' => $parsed['product_description'],
			'packaging_length' => $parsed['packaging_length'],
			'packaging_width' => $parsed['packaging_width'],
			'packaging_height' => $parsed['packaging_height'],
			'packaging_weight' => $parsed['packaging_weight'],
			'gst_percentage' => $parsed['gst_percentage'],
			'hsn' => $parsed['hsn'],
			'product_code' => $parsed['product_code'],
			'sku' => $parsed['sku'],
			'mrp' => $parsed['mrp'],
			'selling_price' => $parsed['selling_price'],
			'meta_title' => $parsed['meta_title'],
			'meta_keywords' => $parsed['meta_keywords'],
			'meta_description' => $parsed['meta_description'],
			'is_individual' => $parsed['is_individual'],
			'is_set' => $parsed['is_set'],
			'status' => $parsed['status'],
			'updated_at' => $now,
		);

		if ($is_update)
		{
			$notebook_id = (int) $existing['id'];
			$this->db->where('id', $notebook_id);
			$this->db->where('vendor_id', (int) $vendor_id);
			$this->db->update('erp_notebooks', $legacy_data);
		}
		else
		{
			$legacy_data['vendor_id'] = (int) $vendor_id;
			$legacy_data['created_at'] = $now;
			$this->db->insert('erp_notebooks', $legacy_data);
			$notebook_id = (int) $this->db->insert_id();
		}

		if ($notebook_id <= 0)
		{
			$this->db->trans_complete();
			throw new RuntimeException('Failed to save notebook.');
		}

		$product_payload = array(
			'vendor_id' => (int) $vendor_id,
			'category_id' => NULL,
			'type' => 'notebook',
			'product_name' => $legacy_data['product_name'],
			'description' => $legacy_data['product_description'],
			'status' => $this->Product_model->normalize_product_status($legacy_data['status']),
			'brand_id' => $legacy_data['brand_id'],
			'board_id' => NULL,
			'grade_id' => NULL,
			'subject_id' => NULL,
			'discount' => 0,
			'discount_amount' => 0,
			'selling_price' => $legacy_data['selling_price'],
			'product_mrp' => $legacy_data['mrp'],
			'gst' => $legacy_data['gst_percentage'],
			'isbn' => $legacy_data['isbn'],
			'hsn' => $legacy_data['hsn'],
			'sku' => $legacy_data['sku'],
			'product_code' => $legacy_data['product_code'],
			'pointers' => $legacy_data['pointers'],
			'quantity' => 0,
			'length' => $legacy_data['packaging_length'],
			'width' => $legacy_data['packaging_width'],
			'height' => $legacy_data['packaging_height'],
			'weight' => $legacy_data['packaging_weight'],
			'meta_title' => $legacy_data['meta_title'],
			'meta_keyword' => $legacy_data['meta_keywords'],
			'meta_description' => $legacy_data['meta_description'],
			'product_origin' => NULL,
			'gender' => NULL,
			'size_chart_id' => NULL,
			'no_of_pages' => $legacy_data['no_of_pages'],
			'binding_type' => $legacy_data['binding_type'],
			'material_id' => NULL,
			'min_quantity' => $legacy_data['min_quantity'],
			'legacy_table' => 'erp_notebooks',
			'legacy_id' => $notebook_id,
		);

		$product = $this->Product_model->get_product_by_legacy('erp_notebooks', $notebook_id, $vendor_id);
		if ($product)
		{
			$this->Product_model->update_product($product['id'], $product_payload);
		}
		else
		{
			$this->Product_model->create_product($product_payload);
		}

		$this->syncNotebookMappings($notebook_id, $parsed);

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			throw new RuntimeException('Database error while saving notebook.');
		}

		return $is_update ? 'updated' : 'created';
	}

	protected function syncNotebookMappings($notebook_id, array $parsed)
	{
		$this->db->where('notebook_id', (int) $notebook_id)->delete('erp_notebook_type_mapping');
		foreach ($parsed['type_ids'] as $type_id)
		{
			$this->db->insert('erp_notebook_type_mapping', array(
				'notebook_id' => (int) $notebook_id,
				'type_id' => (int) $type_id,
			));
		}
	}

	protected function upsertStationery($vendor_id, array $parsed)
	{
		$this->db->trans_start();

		$existing = $this->findExistingLegacyRow('erp_stationery', $vendor_id, $parsed['isbn'], $parsed['sku']);
		$is_update = !empty($existing);
		$now = date('Y-m-d H:i:s');

		$legacy_data = array(
			'category_id' => $parsed['category_id'],
			'brand_id' => $parsed['brand_id'],
			'colour_id' => $parsed['colour_id'],
			'product_name' => $parsed['product_name'],
			'isbn' => $parsed['isbn'],
			'sku' => $parsed['sku'],
			'product_code' => $parsed['product_code'],
			'min_quantity' => $parsed['min_quantity'],
			'days_to_exchange' => $parsed['days_to_exchange'],
			'pointers' => $parsed['pointers'],
			'product_description' => $parsed['product_description'],
			'packaging_length' => $parsed['packaging_length'],
			'packaging_width' => $parsed['packaging_width'],
			'packaging_height' => $parsed['packaging_height'],
			'packaging_weight' => $parsed['packaging_weight'],
			'gst_percentage' => $parsed['gst_percentage'],
			'gst_type' => $parsed['gst_type'],
			'hsn' => $parsed['hsn'],
			'mrp' => $parsed['mrp'],
			'selling_price' => $parsed['selling_price'],
			'meta_title' => $parsed['meta_title'],
			'meta_keywords' => $parsed['meta_keywords'],
			'meta_description' => $parsed['meta_description'],
			'is_individual' => $parsed['is_individual'],
			'is_set' => $parsed['is_set'],
			'status' => $parsed['status'],
			'updated_at' => $now,
		);

		if ($is_update)
		{
			$stationery_id = (int) $existing['id'];
			$this->db->where('id', $stationery_id);
			$this->db->where('vendor_id', (int) $vendor_id);
			$this->db->update('erp_stationery', $legacy_data);
		}
		else
		{
			$legacy_data['vendor_id'] = (int) $vendor_id;
			$legacy_data['created_at'] = $now;
			$this->db->insert('erp_stationery', $legacy_data);
			$stationery_id = (int) $this->db->insert_id();
		}

		if ($stationery_id <= 0)
		{
			$this->db->trans_complete();
			throw new RuntimeException('Failed to save stationery product.');
		}

		$product_payload = array(
			'vendor_id' => (int) $vendor_id,
			'category_id' => $parsed['category_id'],
			'type' => 'stationery',
			'product_name' => $legacy_data['product_name'],
			'description' => $legacy_data['product_description'],
			'status' => $this->Product_model->normalize_product_status($legacy_data['status']),
			'discount' => 0,
			'discount_amount' => 0,
			'selling_price' => $legacy_data['selling_price'],
			'product_mrp' => $legacy_data['mrp'],
			'gst' => $legacy_data['gst_percentage'],
			'isbn' => $legacy_data['isbn'],
			'hsn' => $legacy_data['hsn'],
			'sku' => $legacy_data['sku'],
			'product_code' => $legacy_data['product_code'],
			'pointers' => $legacy_data['pointers'],
			'quantity' => 0,
			'length' => $legacy_data['packaging_length'],
			'width' => $legacy_data['packaging_width'],
			'height' => $legacy_data['packaging_height'],
			'weight' => $legacy_data['packaging_weight'],
			'meta_title' => $legacy_data['meta_title'],
			'meta_keyword' => $legacy_data['meta_keywords'],
			'meta_description' => $legacy_data['meta_description'],
			'min_quantity' => $legacy_data['min_quantity'],
			'legacy_table' => 'erp_stationery',
			'legacy_id' => $stationery_id,
		);

		$product = $this->Product_model->get_product_by_legacy('erp_stationery', $stationery_id, $vendor_id);
		if ($product)
		{
			$this->Product_model->update_product($product['id'], $product_payload);
		}
		else
		{
			$this->Product_model->create_product($product_payload);
		}

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
		{
			throw new RuntimeException('Database error while saving stationery.');
		}

		return $is_update ? 'updated' : 'created';
	}

	/**
	 * Stream template file to browser.
	 */
	public function streamFile($path, $download_name)
	{
		if (!file_exists($path))
		{
			throw new RuntimeException('Template file not found.');
		}

		$this->sendDownloadHeaders($download_name, filesize($path));
		readfile($path);
		@unlink($path);
		exit;
	}

	/**
	 * Write spreadsheet directly to output (avoids temp-file + buffer issues).
	 */
	public function streamSpreadsheet(Spreadsheet $spreadsheet, $download_name)
	{
		$this->bootstrapSpreadsheet();
		$this->sendDownloadHeaders($download_name);

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}

	protected function sendDownloadHeaders($download_name, $content_length = NULL)
	{
		while (ob_get_level() > 0)
		{
			ob_end_clean();
		}

		if (function_exists('ini_set'))
		{
			@ini_set('zlib.output_compression', 'Off');
		}

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . $download_name . '"');
		header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');
		header('Pragma: public');
		header('Expires: 0');
		if ($content_length !== NULL)
		{
			header('Content-Length: ' . (int) $content_length);
		}
	}
}
