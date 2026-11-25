<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * Stock import Excel template + parse (name-based, like Update Stock filters).
 */
class Stock_import_model extends CI_Model
{
	const TEMPLATE_DATA_ROWS = 500;

	const HEADERS = array(
		'product_name*',
		'type*',
		'publisher',
		'board',
		'grade_age_type',
		'grade',
		'age',
		'category',
		'brand',
		'colour',
		'qty*'
	);

	public function __construct()
	{
		parent::__construct();
	}

	protected function bootstrapSpreadsheet()
	{
		static $bootstrapped = FALSE;
		if ($bootstrapped) {
			return;
		}
		$autoload = APPPATH . 'vendor/autoload.php';
		if (!file_exists($autoload)) {
			throw new RuntimeException('Composer autoload not found. Run composer install in application/.');
		}
		require_once $autoload;
		$bootstrapped = TRUE;
	}

	/**
	 * @param	int		$vendor_id
	 * @param	array	$features	has_textbook, has_notebook, has_stationery
	 * @return	Spreadsheet
	 */
	public function buildTemplate($vendor_id, array $features)
	{
		$this->bootstrapSpreadsheet();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Stock');

		$this->writeHeaderRow($sheet, self::HEADERS);
		$sheet->fromArray(array(
			'Sample Product Name',
			$this->defaultSampleType($features),
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'10'
		), NULL, 'A2');

		$type_options = $this->typeOptions($features);
		$refs = $this->addReferenceSheets($spreadsheet, (int)$vendor_id, $features, $type_options);
		$this->applyDropdowns($sheet, $refs, $type_options);

		foreach (range(1, count(self::HEADERS)) as $i) {
			$sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(TRUE);
		}

		$spreadsheet->setActiveSheetIndex(0);
		return $spreadsheet;
	}

	public function streamSpreadsheet(Spreadsheet $spreadsheet, $download_name)
	{
		$this->bootstrapSpreadsheet();
		while (ob_get_level() > 0) {
			ob_end_clean();
		}
		if (function_exists('ini_set')) {
			@ini_set('zlib.output_compression', 'Off');
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . $download_name . '"');
		header('Cache-Control: max-age=0, no-cache, no-store, must-revalidate');
		header('Pragma: public');
		header('Expires: 0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}

	/**
	 * Parse uploaded xlsx into normalized rows.
	 *
	 * @return	array
	 */
	public function parseUpload($tmp_path)
	{
		$this->bootstrapSpreadsheet();
		$spreadsheet = IOFactory::load($tmp_path);
		$sheet = $spreadsheet->getSheet(0);
		$highestRow = (int)$sheet->getHighestDataRow();
		$highestCol = $sheet->getHighestDataColumn();
		if ($highestRow < 2) {
			throw new RuntimeException('The file has no data rows.');
		}

		$headerRow = $sheet->rangeToArray('A1:' . $highestCol . '1', NULL, TRUE, FALSE)[0];
		$headerMap = $this->buildHeaderMap($headerRow);
		if (!isset($headerMap['product_name']) || !isset($headerMap['type']) || !isset($headerMap['qty'])) {
			throw new RuntimeException('Template must include product_name, type and qty columns.');
		}

		$rows = array();
		for ($r = 2; $r <= $highestRow; $r++) {
			$lineValues = $sheet->rangeToArray('A' . $r . ':' . $highestCol . $r, NULL, TRUE, FALSE)[0];
			$data = array();
			$hasValue = FALSE;
			foreach ($headerMap as $key => $colIndex) {
				$value = isset($lineValues[$colIndex]) ? trim((string)$lineValues[$colIndex]) : '';
				$data[$key] = $value;
				if ($value !== '') {
					$hasValue = TRUE;
				}
			}
			if (!$hasValue) {
				continue;
			}
			$data['_line'] = $r;
			$rows[] = $data;
		}
		return $rows;
	}

	/**
	 * Match one import row against catalog rows from buildStockCatalogRows().
	 *
	 * @return	array|null	Single matched catalog row, or null if none / ambiguous (sets $error)
	 */
	public function matchCatalogRow(array $importRow, array $catalogRows, &$error)
	{
		$error = '';
		$product_name = strtolower(trim((string)(isset($importRow['product_name']) ? $importRow['product_name'] : '')));
		$type = $this->normalizeType(isset($importRow['type']) ? $importRow['type'] : '');
		if ($product_name === '' || $type === '') {
			$error = 'Product name and type are required.';
			return NULL;
		}

		$publisher = strtolower(trim((string)(isset($importRow['publisher']) ? $importRow['publisher'] : '')));
		$board = strtolower(trim((string)(isset($importRow['board']) ? $importRow['board'] : '')));
		$grade_age_type = $this->normalizeGradeAgeType(isset($importRow['grade_age_type']) ? $importRow['grade_age_type'] : '');
		$grade = strtolower(trim((string)(isset($importRow['grade']) ? $importRow['grade'] : '')));
		$age = strtolower(trim((string)(isset($importRow['age']) ? $importRow['age'] : '')));
		$category = strtolower(trim((string)(isset($importRow['category']) ? $importRow['category'] : '')));
		$brand = strtolower(trim((string)(isset($importRow['brand']) ? $importRow['brand'] : '')));
		$colour = strtolower(trim((string)(isset($importRow['colour']) ? $importRow['colour'] : '')));

		$matches = array();
		foreach ($catalogRows as $row) {
			$row_type = strtolower(trim((string)(isset($row['product_type']) ? $row['product_type'] : '')));
			$row_name = strtolower(trim((string)(isset($row['product_name']) ? $row['product_name'] : '')));
			if ($row_name !== $product_name) {
				continue;
			}
			if ($row_type !== $type) {
				// uniform_type_name fallback e.g. "Textbook"
				$ut = strtolower(trim((string)(isset($row['uniform_type_name']) ? $row['uniform_type_name'] : '')));
				if ($ut !== $type) {
					continue;
				}
			}
			if ($publisher !== '' && strtolower(trim((string)(isset($row['publisher_name']) ? $row['publisher_name'] : ''))) !== $publisher) {
				continue;
			}
			if ($board !== '' && strtolower(trim((string)(isset($row['board_name']) ? $row['board_name'] : ''))) !== $board) {
				continue;
			}
			if ($grade_age_type !== '' && strtolower(trim((string)(isset($row['grade_age_type']) ? $row['grade_age_type'] : ''))) !== $grade_age_type) {
				continue;
			}
			if ($grade !== '') {
				$grade_name = strtolower((string)(isset($row['grade_name']) ? $row['grade_name'] : ''));
				if ($grade_name === '-' || $grade_name === '') {
					continue;
				}
				$parts = array_map('trim', explode(',', $grade_name));
				if (!in_array($grade, $parts, TRUE)) {
					continue;
				}
			}
			if ($age !== '') {
				$grade_name = strtolower((string)(isset($row['grade_name']) ? $row['grade_name'] : ''));
				$parts = array_map('trim', explode(',', $grade_name));
				if (!in_array($age, $parts, TRUE)) {
					continue;
				}
			}
			if ($category !== '' && strtolower(trim((string)(isset($row['category_name']) ? $row['category_name'] : ''))) !== $category) {
				continue;
			}
			if ($brand !== '' && strtolower(trim((string)(isset($row['brand_name']) ? $row['brand_name'] : ''))) !== $brand) {
				continue;
			}
			if ($colour !== '' && strtolower(trim((string)(isset($row['colour_name']) ? $row['colour_name'] : ''))) !== $colour) {
				continue;
			}
			$matches[] = $row;
		}

		if (count($matches) === 0) {
			$error = 'No product matched "' . (isset($importRow['product_name']) ? $importRow['product_name'] : '') . '" (' . $type . '). Check name/filters.';
			return NULL;
		}
		if (count($matches) > 1) {
			$error = 'Multiple products matched "' . (isset($importRow['product_name']) ? $importRow['product_name'] : '') . '". Add publisher/board/grade/category filters.';
			return NULL;
		}
		return $matches[0];
	}

	public function typeOptions(array $features)
	{
		$opts = array();
		if (!empty($features['has_textbook'])) {
			$opts[] = 'Textbook';
		}
		if (!empty($features['has_notebook'])) {
			$opts[] = 'Notebook';
		}
		if (!empty($features['has_stationery'])) {
			$opts[] = 'Stationery';
		}
		return $opts;
	}

	protected function defaultSampleType(array $features)
	{
		$opts = $this->typeOptions($features);
		return !empty($opts) ? $opts[0] : 'Textbook';
	}

	protected function normalizeType($type)
	{
		$t = strtolower(trim((string)$type));
		if ($t === 'textbooks') {
			$t = 'textbook';
		}
		if ($t === 'notebooks') {
			$t = 'notebook';
		}
		return $t;
	}

	protected function normalizeGradeAgeType($value)
	{
		$v = strtolower(trim((string)$value));
		if ($v === 'grades') {
			$v = 'grade';
		}
		if ($v === 'ages') {
			$v = 'age';
		}
		return $v;
	}

	protected function writeHeaderRow($sheet, array $headers)
	{
		foreach ($headers as $i => $header) {
			$sheet->setCellValueByColumnAndRow($i + 1, 1, $header);
		}
		$sheet->getStyle('A1:' . Coordinate::stringFromColumnIndex(count($headers)) . '1')->getFont()->setBold(TRUE);
	}

	protected function addReferenceSheets(Spreadsheet $spreadsheet, $vendor_id, array $features, array $type_options)
	{
		$refs = array();
		$refs['Types'] = $this->addNameSheet($spreadsheet, 'Types', $type_options);

		$show_books = !empty($features['has_textbook']) || !empty($features['has_notebook']);
		if ($show_books) {
			$refs['Publishers'] = $this->addNameSheet($spreadsheet, 'Publishers', $this->fetchNames('erp_textbook_publishers', $vendor_id));
			$boards = array();
			if ($this->db->table_exists('erp_school_boards')) {
				$this->db->select('board_name AS name');
				$this->db->from('erp_school_boards');
				$this->db->where('(vendor_id IS NULL OR vendor_id = ' . (int)$vendor_id . ')', NULL, FALSE);
				$this->db->order_by('board_name', 'ASC');
				$boards = array_column($this->db->get()->result_array(), 'name');
			}
			$refs['Boards'] = $this->addNameSheet($spreadsheet, 'Boards', $boards);
			$refs['Grades'] = $this->addNameSheet($spreadsheet, 'Grades', $this->fetchNames('erp_textbook_grades', $vendor_id));
			$refs['Ages'] = $this->addNameSheet($spreadsheet, 'Ages', $this->fetchNames('erp_textbook_ages', $vendor_id));
			$refs['GradeAgeType'] = $this->addNameSheet($spreadsheet, 'GradeAgeType', array('Grade', 'Age'));
		}

		if (!empty($features['has_stationery'])) {
			$refs['Categories'] = $this->addNameSheet($spreadsheet, 'Categories', $this->fetchNames('erp_stationery_categories', $vendor_id));
			$refs['Brands'] = $this->addNameSheet($spreadsheet, 'Brands', $this->fetchNames('erp_stationery_brands', $vendor_id));
			$refs['Colours'] = $this->addNameSheet($spreadsheet, 'Colours', $this->fetchNames('erp_stationery_colours', $vendor_id));
		}

		return $refs;
	}

	protected function fetchNames($table, $vendor_id)
	{
		if (!$this->db->table_exists($table)) {
			return array();
		}
		$this->db->select('name');
		$this->db->from($table);
		$this->db->where('vendor_id', (int)$vendor_id);
		if ($this->db->field_exists('status', $table)) {
			$this->db->where('status', 'active');
		}
		$this->db->order_by('name', 'ASC');
		return array_column($this->db->get()->result_array(), 'name');
	}

	protected function addNameSheet(Spreadsheet $spreadsheet, $title, array $names)
	{
		$sheet = $spreadsheet->createSheet();
		$sheet->setTitle($title);
		$sheet->setCellValue('A1', 'name');
		$row = 2;
		if (empty($names)) {
			$sheet->setCellValue('A2', '(none configured)');
			return array('title' => $title, 'last_row' => 2);
		}
		foreach ($names as $name) {
			$sheet->setCellValue('A' . $row, $name);
			$row++;
		}
		return array('title' => $title, 'last_row' => $row - 1);
	}

	protected function sheetListFormula(array $ref)
	{
		$title = str_replace("'", "''", $ref['title']);
		return "'" . $title . "'!\$A\$2:\$A\$" . (int)$ref['last_row'];
	}

	protected function headerColumnMap(array $headers)
	{
		$map = array();
		foreach ($headers as $i => $header) {
			$key = str_replace('*', '', $header);
			$map[$key] = Coordinate::stringFromColumnIndex($i + 1);
		}
		return $map;
	}

	protected function applyDropdowns($sheet, array $refs, array $type_options)
	{
		$cols = $this->headerColumnMap(self::HEADERS);
		$endRow = self::TEMPLATE_DATA_ROWS + 1;

		if (isset($refs['Types'])) {
			$this->applyListValidation($sheet, $cols['type'], 2, $endRow, $this->sheetListFormula($refs['Types']), TRUE, 'Type', 'Select Textbook / Notebook / Stationery based on your access.');
		}
		if (isset($refs['Publishers'])) {
			$this->applyListValidation($sheet, $cols['publisher'], 2, $endRow, $this->sheetListFormula($refs['Publishers']), FALSE, 'Publisher', 'For Textbook / Notebook.');
		}
		if (isset($refs['Boards'])) {
			$this->applyListValidation($sheet, $cols['board'], 2, $endRow, $this->sheetListFormula($refs['Boards']), FALSE, 'Board', 'For Textbook.');
		}
		if (isset($refs['GradeAgeType'])) {
			$this->applyListValidation($sheet, $cols['grade_age_type'], 2, $endRow, $this->sheetListFormula($refs['GradeAgeType']), FALSE, 'Grade / Age', 'Select Grade or Age.');
		}
		if (isset($refs['Grades'])) {
			$this->applyListValidation($sheet, $cols['grade'], 2, $endRow, $this->sheetListFormula($refs['Grades']), FALSE, 'Grade', 'When Grade/Age type is Grade.');
		}
		if (isset($refs['Ages'])) {
			$this->applyListValidation($sheet, $cols['age'], 2, $endRow, $this->sheetListFormula($refs['Ages']), FALSE, 'Age', 'When Grade/Age type is Age.');
		}
		if (isset($refs['Categories'])) {
			$this->applyListValidation($sheet, $cols['category'], 2, $endRow, $this->sheetListFormula($refs['Categories']), FALSE, 'Category', 'For Stationery.');
		}
		if (isset($refs['Brands'])) {
			$this->applyListValidation($sheet, $cols['brand'], 2, $endRow, $this->sheetListFormula($refs['Brands']), FALSE, 'Brand', 'For Stationery.');
		}
		if (isset($refs['Colours'])) {
			$this->applyListValidation($sheet, $cols['colour'], 2, $endRow, $this->sheetListFormula($refs['Colours']), FALSE, 'Colour', 'For Stationery.');
		}
	}

	protected function applyListValidation($sheet, $column, $startRow, $endRow, $formula, $strict, $promptTitle = '', $prompt = '')
	{
		if ($column === '' || $formula === '') {
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
		if ($promptTitle !== '') {
			$template->setPromptTitle($promptTitle);
		}
		if ($prompt !== '') {
			$template->setPrompt($prompt);
		}
		if ($strict) {
			$template->setErrorTitle('Invalid value');
			$template->setError('Please choose a value from the dropdown list.');
		}
		$sheet->setDataValidation($column . $startRow . ':' . $column . $endRow, $template);
	}

	protected function buildHeaderMap(array $headerRow)
	{
		$map = array();
		foreach ($headerRow as $i => $raw) {
			$key = strtolower(trim((string)$raw));
			$key = str_replace('*', '', $key);
			$key = str_replace(array('-', ' '), '_', $key);
			$key = preg_replace('/_+/', '_', $key);
			if ($key === '') {
				continue;
			}
			// aliases
			if ($key === 'product' || $key === 'name') {
				$key = 'product_name';
			}
			if ($key === 'qty' || $key === 'quantity' || $key === 'stock' || $key === 'opening_qty') {
				$key = 'qty';
			}
			if ($key === 'grade_age' || $key === 'select_grade_age') {
				$key = 'grade_age_type';
			}
			if ($key === 'color') {
				$key = 'colour';
			}
			$map[$key] = $i;
		}
		return $map;
	}
}
