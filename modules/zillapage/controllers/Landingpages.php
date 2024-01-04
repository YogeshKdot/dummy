<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Landingpages extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('landingpage_model');
        $this->load->model('roles_model');
        $this->load->model('staff_model');
    }

    /* List all Landingpages */
    public function index()
    {
        $alert_route = $this->define_my_routes_core();
        
        if (!has_permission('landingpages', '', 'view')) {
            access_denied('landingpages');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('zillapage', 'landingpages/table'));
        }
        $data['title']                 = _l('landing_pages');
        $data['alert_route'] = $alert_route;
        
        $this->load->view('landingpages/index', $data);
    }
    
    function define_my_routes_core(){

        $core_route_path = APPPATH.'config/routes.php';
        $core_my_routes_path = APPPATH.'config/my_routes.php';
        $zillapage_my_routes_path = FCPATH.'modules/zillapage/my_routes.php';
        $alert_route = false;
        if (!file_exists($core_my_routes_path))
        {
            copy($zillapage_my_routes_path, $core_my_routes_path);
        }
        else{
            
            include($core_route_path);
            include($core_my_routes_path);

            if (isset($route) && is_array($route))
            {
                // add new route
                if (!isset($route['publish/(:any)']) || !isset($route['publish/thankyou/(:any)'])) {
                    $alert_route = true;
                }
            }
        }
        return $alert_route;

    }
    public function setting($code = '')
    {

        if ($this->input->post()) {
            if ($code != '') {

                if (!has_permission('landingpages', '', 'edit')) {
                    access_denied('landingpages');
                }
                $page = $this->landingpage_model->get_landing_page_code($code);
                
                if ($page) {

                    $data = $this->input->post();
                
                    $success = $this->landingpage_model->update_landing_page($data, $page);
                    
                    if ($success) {
                        set_alert('success', _l('updated_successfully'));
                    }
                    set_alert('warning', _l('problem_updating'));
                    redirect(admin_url('zillapage/landingpages/setting')."/".$code);  
                } 
                
            }

            show_404();
        }

        $this->db->where('code', $code);
        $page = $this->db->get(db_prefix() . 'landing_pages')->row();

        if ($page) {
            # code...
            $data['item']        = $page;
            $title = strtoupper($page->name);
            $data['title']                 = $title;
            $data['roles']    = $this->roles_model->get();
            $data['members'] = $this->staff_model->get('', [
                'active'       => 1,
                'is_not_staff' => 0,
            ]);
            $this->load->view('landingpages/setting', $data);

        }else show_404();
        
    }

    public function templates()
    {

        if (!has_permission('landingpages', '', 'create')) {
            access_denied('landingpages');
        }

        $templates = $this->landingpage_model->get_all_templates();

        $data['title']                 = _l('templates');
        $data['templates']                 = $templates;

        $this->load->view('landingpages/templates', $data);
    }

    public function save()
    {
        if ($this->input->post()) {
            // validate
            $template_id = $this->input->post('template_id');
            $name = $this->input->post('name');

            if ($template_id && $name) {

                if (!has_permission('landingpages', '', 'create')) {
                    access_denied('landingpages');
                }
                // Get template ID content and style => load builder
                $template =  $this->landingpage_model->find_template($template_id);
                
                if (!$template) {
                    set_alert('danger', _l('template_id_and_name_required'));
                    redirect(admin_url('zillapage/landingpages/templates'));
                }

                $template = replaceVarContentStyle($template);

                $code = guidV4();

                $user = get_staff($this->session->userdata('tfa_staffid'));

                $data = [
                    'code' => $code,
                    'responsible' => $user->staffid,
                    'name' => $name,
                    'html' => $template->content,
                    'css' => $template->style,
                    'thank_you_page_html' => $template->thank_you_page,
                    'thank_you_page_css' => $template->style,
                ];

                $this->db->insert(db_prefix() . 'landing_pages', $data);
                
                $insert_id = $this->db->insert_id();

                if ($insert_id) {
                    
                    log_activity('New Landing Page added [ID:' . $insert_id . ']');
                    set_alert('success', _l('added_successfully'));
                    redirect(admin_url('zillapage/landingpages/builder/'. $code));
                }
            }

            set_alert('danger', _l('template_id_and_name_required'));
            redirect(admin_url('zillapage/landingpages/templates'));

        }
      
    }

    public function builder($code = '', $type = 'main-page'){
        
        $type_arr = array('main-page','thank-you-page');
        if (!in_array($type, $type_arr)) {
            show_404();
        }

        if (!empty($code)) {

            if (!has_permission('landingpages', '', 'edit')) {
                access_denied('landingpages');
            }

            
            $page = $this->landingpage_model->get_landing_page_code($code);

            if($page){

                $blocks = $this->landingpage_model->get_all_blocks();
                $arr_blocks = [];
                
                foreach ($blocks as $item) {
                     $arr_temp = [];
                     $arr_temp['id'] = $item['id'];
                     $arr_temp['thumb'] = base_url(ZILLAPAGE_ASSETS_PATH.'/images/thumb_blocks')."/".$item['thumb'];
                     $arr_temp['name'] = $item['name'];
                     $arr_temp['block_category'] = $item['block_category'];
                     $arr_temp['content'] = replaceVarContentStyle($item['content']);
                     array_push($arr_blocks, $arr_temp);
                }


                $app_icons = $this->landingpage_model->get_landing_page_setting('app-icons');
                
                $all_icons = json_decode($app_icons->value);
                $images_url = getAllImagesContentMedia();
                $data['title']                 = _l('builder'). " ".$page->name;
                $data['page']                 = $page;
                $data['blocks']                 = json_encode($arr_blocks, JSON_HEX_QUOT | JSON_HEX_TAG);
                $data['all_icons']                 = $all_icons;
                $data['images_url'] = json_encode($images_url, JSON_HEX_QUOT | JSON_HEX_TAG);

                $this->load->view('landingpages/builder', $data);
            
            }else show_404();
        }
        else show_404();
        
    }

    public function loadbuilder($code, $type = 'main-page'){
        
        $type_arr = array('main-page','thank-you-page');

        if (!in_array($type, $type_arr)) {
            header('Content-Type: application/json');
            echo json_encode(['error'=>_l('not_found_type')]); die;
        }

        if ($code) {

            $this->db->where('code', $code);
            $page = $this->db->get(db_prefix() . 'landing_pages')->row();

            if($page){
                
                if ($type == 'thank-you-page') {

                    header('Content-Type: application/json');
                    echo json_encode([
                        'gjs-components'=>$page->thank_you_page_components, 
                        'gjs-styles' => $page->thank_you_page_styles,
                        'gjs-html'=>$page->thank_you_page_html, 
                        'gjs-css' => $page->thank_you_page_css
                    ]);
                    die;
                }

                header('Content-Type: application/json');
                echo json_encode([
                    'gjs-components'=>$page->components, 
                    'gjs-styles' => $page->styles,
                    'gjs-html'=>$page->html, 
                    'gjs-css' => $page->css
                ]);
                die;
               
            }
            
        }
        echo json_encode(['error'=>_l('not_found_code')]); die;
    }

    public function updatebuilder($code, $type = 'main-page')
    {

        if ($this->input->post()) {

            $type_arr = array('main-page','thank-you-page');
            
            if (!in_array($type, $type_arr)) {
                echo json_encode(['error'=>_l('not_found_type')]); die;
            }

            if ($code) {
                
                $this->db->where('code', $code);
                $item = $this->db->get(db_prefix() . 'landing_pages')->row();

                $data = [];

                if ($item) {

                    if ($type == 'thank-you-page') {

                        $data['thank_you_page_components'] = $this->input->post('gjs-components');
                        $data['thank_you_page_styles'] = $this->input->post('gjs-styles');
                        $data['thank_you_page_html'] = $this->input->post('gjs-html');
                        $data['thank_you_page_css'] = $this->input->post('gjs-css');
                    }
                    else{

                        $data['components'] = $this->input->post('gjs-components');
                        $data['styles'] = $this->input->post('gjs-styles');
                        $data['html'] = $this->input->post('gjs-html');
                        $data['css'] = $this->input->post('gjs-css');
                        $data['main_page_script'] = $this->input->post('main_page_script');
                        
                    }
                    // update
                    $this->db->where('code', $code);
                    $this->db->update(db_prefix() . 'landing_pages', $data);

                    header('Content-Type: application/json');
                    echo json_encode(['success'=> _l('updated_successfully')]); die;
                }
                else{
                    header('Content-Type: application/json');
                    echo json_encode(['error'=> _l('not_found_code')]); die;
                }
                
            }
            header('Content-Type: application/json');
            echo json_encode(['error'=> _l('fail')]); die;

        }else {
            header('Content-Type: application/json');
            echo json_encode(['error'=> _l('fail')]); die;
        }

    }
    
    public function searchicon(){
        
        if ($this->input->post()) {

            $response = "";
            $keyword = $this->input->post('keyword');

            $app_icons = $this->landingpage_model->get_landing_page_setting('app-icons');
            $data = json_decode($app_icons->value);

            if ($keyword) {

                $input = preg_quote($keyword, '~'); 
                $result = preg_grep('~' . $input . '~', $data);
                
                foreach ($result as $key => $value) {
                    # code...
                    $response.= '<i class="'.$value.'"></i>';
                }
                header('Content-Type: application/json');
                echo json_encode(['result'=> $response]); die;


            }
            else{

                foreach ($data as $key => $value) {
                    # code...
                    $response.= '<i class="'.$value.'"></i>';
                }
                header('Content-Type: application/json');
                echo json_encode(['result'=> $response]); die;

            }
        }

    }
    public function uploadimage(){
        
        $config['upload_path'] = FCPATH.ZILLAPAGE_ASSETS_PATH."/images/content_media";
        $config['allowed_types'] = 'gif|jpg|png|svg|jpeg';
        $config['max_size'] = 20000;
        $newname = time();
        $config['file_name'] = 'upload'.$newname;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('files')) {
            $error = array('error' => $this->upload->display_errors());

            header('Content-Type: application/json');
            echo json_encode( $error); die;
            
        } else {

            $data = $this->upload->data();

            $imagesURL = base_url(ZILLAPAGE_IMAGE_PATH.'/content_media')."/".$data['file_name'];
            
            header('Content-Type: application/json');
            echo json_encode($imagesURL); die;

        }

    }
    
    public function deleteimage(){
        
        $file_path = FCPATH.ZILLAPAGE_ASSETS_PATH."/images/content_media";

        $input=$this->input->post();
        $link_array = explode('/',$input['image_src']);
        $image_name = end($link_array);

        $path = $file_path."/".$image_name;

        if (file_exists($path)) {
            unlink($path);
        }
        header('Content-Type: application/json');
        echo json_encode($image_name); die;

    }


    public function deletelandingpage($id)
    {
        if (!has_permission('landingpages', '', 'delete')) {
            access_denied('landingpages');
        }
        if (!$id) {
            redirect(admin_url('zillapage/landingpages/index'));
        }
        $response = $this->landingpage_model->delete_landing_page($id);
        if ($response == true) {
            set_alert('success', _l('deleted'));
        } else {
            set_alert('warning', _l('problem_deleting'));
        }
        redirect(admin_url('zillapage/landingpages/index'));
    }

    
}
