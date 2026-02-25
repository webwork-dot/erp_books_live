<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

class Size_charts extends Vendor_base
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Size_chart_model');
        $this->load->library('form_validation');
        
        $this->enforceUniformFeatureAccess();
    }

    private function enforceUniformFeatureAccess()
    {
        if (!$this->hasFeature('uniforms'))
        {
            show_error('Access denied. Uniform feature is not enabled for your account.', 403);
        }
    }

    public function index()
    {
        $filters = array();
        if ($this->input->get('status'))
        {
            $filters['status'] = $this->input->get('status');
        }
        if ($this->input->get('search'))
        {
            $filters['search'] = $this->input->get('search');
        }

        $limit = 20;
        $offset = $this->input->get('page') ? ($this->input->get('page') - 1) * $limit : 0;

        $data['size_charts'] = $this->Size_chart_model->getSizeChartsByVendor($this->current_vendor['id'], $filters, $limit, $offset);
        $data['total_count'] = $this->Size_chart_model->countSizeChartsByVendor($this->current_vendor['id'], $filters);

        $this->load->library('pagination');
        $config['base_url'] = base_url('size-charts');
        $config['total_rows'] = $data['total_count'];
        $config['per_page'] = $limit;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $this->pagination->initialize($config);

        $data['pagination'] = $this->pagination->create_links();

        $data['page_title'] = 'Size Charts';
        $data['active_menu'] = 'size-charts';
        $this->load->view('vendor/size_charts/index', $data);
    }

    public function add()
    {
        if ($this->input->post())
        {
            $this->form_validation->set_rules('chart_name', 'Chart Name', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('description', 'Description', 'trim');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');

            if ($this->form_validation->run() == TRUE)
            {
                $chart_data = array(
                    'vendor_id' => $this->current_vendor['id'],
                    'chart_name' => $this->input->post('chart_name'),
                    'description' => $this->input->post('description'),
                    'status' => $this->input->post('status'),
                );

                $chart_id = $this->Size_chart_model->createSizeChart($chart_data);

                if ($chart_id)
                {
                    $measurements = $this->input->post('measurements');
                    if (!empty($measurements) && is_array($measurements))
                    {
                        foreach ($measurements as $index => $measurement)
                        {
                            if (!empty($measurement['name']))
                            {
                                $measurement_data = array(
                                    'chart_id' => $chart_id,
                                    'measurement_name' => $measurement['name'],
                                    'measurement_order' => $index,
                                );
                                $this->Size_chart_model->addMeasurement($measurement_data);
                            }
                        }
                    }

                    $this->session->set_flashdata('success', 'Size chart created successfully.');
                    redirect('size-charts');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Failed to create size chart. Please try again.');
                }
            }
        }

        $data['page_title'] = 'Add Size Chart';
        $data['active_menu'] = 'size-charts';
        $this->load->view('vendor/size_charts/add', $data);
    }

    public function edit($id)
    {
        $size_chart = $this->Size_chart_model->getSizeChartById($id, $this->current_vendor['id']);
        if (empty($size_chart))
        {
            show_404();
        }

        if ($this->input->post())
        {
            $this->form_validation->set_rules('chart_name', 'Chart Name', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('description', 'Description', 'trim');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');

            if ($this->form_validation->run() == TRUE)
            {
                $chart_data = array(
                    'chart_name' => $this->input->post('chart_name'),
                    'description' => $this->input->post('description'),
                    'status' => $this->input->post('status'),
                );

                if ($this->Size_chart_model->updateSizeChart($id, $chart_data, $this->current_vendor['id']))
                {
                    $measurements = $this->input->post('measurements');
                    if (is_array($measurements))
                    {
                        $this->Size_chart_model->deleteMeasurementsByChart($id);
                        
                        foreach ($measurements as $index => $measurement)
                        {
                            if (!empty($measurement['name']))
                            {
                                $measurement_data = array(
                                    'chart_id' => $id,
                                    'measurement_name' => $measurement['name'],
                                    'measurement_order' => $index,
                                );
                                $this->Size_chart_model->addMeasurement($measurement_data);
                            }
                        }
                    }

                    $this->session->set_flashdata('success', 'Size chart updated successfully.');
                    redirect('size-charts');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Failed to update size chart. Please try again.');
                }
            }
        }

        $data['measurements'] = $this->Size_chart_model->getMeasurements($id);
        $data['size_chart'] = $size_chart;
        $data['page_title'] = 'Edit Size Chart';
        $data['active_menu'] = 'size-charts';
        $this->load->view('vendor/size_charts/edit', $data);
    }

    public function delete($id)
    {
        if ($this->Size_chart_model->deleteSizeChart($id, $this->current_vendor['id']))
        {
            $this->session->set_flashdata('success', 'Size chart deleted successfully.');
        }
        else
        {
            $this->session->set_flashdata('error', 'Failed to delete size chart. Please try again.');
        }
        redirect('size-charts');
    }

    public function view($id)
    {
        $size_chart = $this->Size_chart_model->getSizeChartById($id, $this->current_vendor['id']);
        if (empty($size_chart))
        {
            show_404();
        }

        $data['measurements'] = $this->Size_chart_model->getMeasurements($id);
        $data['size_chart'] = $size_chart;
        $data['page_title'] = 'View Size Chart';
        $data['active_menu'] = 'size-charts';
        $this->load->view('vendor/size_charts/view', $data);
    }
}