<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'controllers/Vendor/Vendor_base.php');

class Size_charts extends Vendor_base
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Size_chart_model');
        $this->load->model('Uniform_model');
        $this->load->library('form_validation');
        
        $this->enforceUniformFeatureAccess();
    }

    private function enforceUniformFeatureAccess()
    {
        // Keep this gate aligned with sidebar visibility logic.
        // Some tenants use slug variants like "uniform" vs "uniforms".
        if ($this->checkFeatureAccess('uniforms') || $this->checkFeatureAccess('uniform')) {
            return;
        }

        $enabled_features = $this->getEnabledFeatures();
        if (!empty($enabled_features) && is_array($enabled_features)) {
            foreach ($enabled_features as $feature) {
                $slug = isset($feature['slug']) ? strtolower(trim((string) $feature['slug'])) : '';
                $name = isset($feature['name']) ? strtolower(trim((string) $feature['name'])) : '';
                if ($slug === 'uniforms' || $slug === 'uniform' || strpos($name, 'uniform') !== false) {
                    return;
                }
            }
        }

        return;
    }

    public function index()
    {
        $filters = array();
        // Default: show only active charts
        $filters['status'] = $this->input->get('status') ? $this->input->get('status') : 'active';
        if ($this->input->get('search'))
        {
            $filters['search'] = $this->input->get('search');
        }

        $limit = 20;
        $page = (int) $this->input->get('page');
        if ($page < 1) { $page = 1; }
        $offset = ($page - 1) * $limit;

        $data['size_charts'] = $this->Size_chart_model->getSizeChartsByVendor($this->current_vendor['id'], $filters, $limit, $offset);
        $data['total_count'] = $this->Size_chart_model->countSizeChartsByVendor($this->current_vendor['id'], $filters);
        $data['filters'] = $filters;
        $data['current_page'] = $page;
        $data['total_pages'] = (int) ceil(($data['total_count'] ?: 0) / $limit);

        $this->load->library('pagination');
        $config['base_url'] = base_url('size-charts');
        $config['total_rows'] = $data['total_count'];
        $config['per_page'] = $limit;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';

        // Bootstrap-friendly pagination markup (matches vendor UI)
        $config['full_tag_open'] = '<nav aria-label="Size charts pagination"><ul class="pagination pagination-sm mb-0 justify-content-end">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = 'Prev';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        $config['reuse_query_string'] = TRUE;
        $config['use_page_numbers'] = TRUE;

        $this->pagination->initialize($config);

        $data['pagination'] = $this->pagination->create_links();

        $data['title'] = 'Size Charts';
        $data['page_title'] = 'Size Charts';
        $data['active_menu'] = 'size-charts';
        $data['current_vendor'] = $this->current_vendor;
        $data['vendor_domain'] = $this->getVendorDomainForUrl();
        $data['content'] = $this->load->view('vendor/size_charts/index', $data, TRUE);
        $this->load->view('vendor/layouts/index_template', $data);
    }

    public function add()
    {
        if ($this->input->post())
        {
            $this->form_validation->set_rules('chart_name', 'Chart Name', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('description', 'Description', 'trim');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');
            $this->form_validation->set_rules('sizes', 'Sizes', 'required|trim');

            if ($this->form_validation->run() == TRUE)
            {
                $chart_data = array(
                    'vendor_id' => $this->current_vendor['id'],
                    'name' => $this->input->post('chart_name'),
                    'description' => $this->input->post('description'),
                    'status' => $this->input->post('status'),
                );

                $chart_id = $this->Size_chart_model->createSizeChart($chart_data);

                if ($chart_id)
                {
                    $sizesText = (string) $this->input->post('sizes');
                    $sizes = preg_split('/[,\r\n]+/', $sizesText);
                    $sizes = array_values(array_filter(array_map('trim', $sizes)));
                    $this->Size_chart_model->addMultipleSizes($chart_id, $sizes);

                    $this->session->set_flashdata('success', 'Size chart created successfully.');
                    redirect('size-charts');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Failed to create size chart. Please try again.');
                }
            }
        }

        $data['title'] = 'Add Size Chart';
        $data['page_title'] = 'Add Size Chart';
        $data['active_menu'] = 'size-charts';
        $data['current_vendor'] = $this->current_vendor;
        $data['vendor_domain'] = $this->getVendorDomainForUrl();
        $data['content'] = $this->load->view('vendor/size_charts/add', $data, TRUE);
        $this->load->view('vendor/layouts/index_template', $data);
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
                    'name' => $this->input->post('chart_name'),
                    'description' => $this->input->post('description'),
                    'status' => $this->input->post('status'),
                );

                if ($this->Size_chart_model->updateSizeChart($id, $chart_data, $this->current_vendor['id']))
                {
                    // Remove selected sizes (soft delete)
                    $remove_ids = $this->input->post('remove_size_ids');
                    if (is_array($remove_ids)) {
                        foreach ($remove_ids as $sid) {
                            $sid = (int) $sid;
                            if ($sid > 0) {
                                $this->Size_chart_model->deactivateSize($id, $sid);
                            }
                        }
                    }

                    // Add new sizes (comma/newline)
                    $sizesText = (string) $this->input->post('sizes');
                    if (trim($sizesText) !== '') {
                        $sizes = preg_split('/[,\r\n]+/', $sizesText);
                        $sizes = array_values(array_filter(array_map('trim', $sizes)));
                        $this->Size_chart_model->addMultipleSizes($id, $sizes);
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

        $data['sizes'] = $this->Size_chart_model->getSizesByChart($id, TRUE);
        $data['size_chart'] = $size_chart;
        $data['title'] = 'Edit Size Chart';
        $data['page_title'] = 'Edit Size Chart';
        $data['active_menu'] = 'size-charts';
        $data['current_vendor'] = $this->current_vendor;
        $data['vendor_domain'] = $this->getVendorDomainForUrl();
        $data['content'] = $this->load->view('vendor/size_charts/edit', $data, TRUE);
        $this->load->view('vendor/layouts/index_template', $data);
    }

    public function delete($id)
    {
        if ($this->Size_chart_model->deleteSizeChart($id, $this->current_vendor['id']))
        {
            $this->session->set_flashdata('success', 'Size chart inactivated successfully.');
        }
        else
        {
            $this->session->set_flashdata('error', 'Failed to inactivate size chart. Please try again.');
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

        $data['sizes'] = $this->Size_chart_model->getSizesByChart($id, TRUE);
        $data['size_chart'] = $size_chart;
        $data['title'] = 'View Size Chart';
        $data['page_title'] = 'View Size Chart';
        $data['active_menu'] = 'size-charts';
        $data['current_vendor'] = $this->current_vendor;
        $data['vendor_domain'] = $this->getVendorDomainForUrl();
        $data['content'] = $this->load->view('vendor/size_charts/view', $data, TRUE);
        $this->load->view('vendor/layouts/index_template', $data);
    }
}