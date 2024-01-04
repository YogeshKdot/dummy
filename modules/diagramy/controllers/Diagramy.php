<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Diagramy extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('diagramy_model');
    }

    // List all Diagramy projects
    public function index()
    {
        'false' == $this->session->userdata('mindmap_grid_view');
        if (!has_permission('diagramy', '', 'view')) {
            access_denied('diagramy');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('diagramy', 'table'));
        }

        $data['switch_grid'] = false;

        if ('true' == $this->session->userdata('mindmap_grid_view')) {
            $data['switch_grid'] = true;
        }

        $this->load->model('staff_model');
        $data['staffs'] = $this->staff_model->get();
        $data['groups'] = $this->diagramy_model->get_groups();

        $data['title'] = _l('diagramy');
        $this->app_scripts->add('diagramy-js', 'modules/diagramy/assets/js/diagramy.js');
        $this->load->view('manage', $data);
    }

    public function table()
    {
        if (!has_permission('diagramy', '', 'view')) {
            access_denied('diagramy');
        }

        $this->app->get_table_data(module_views_path('diagramy', 'table'));
    }

    public function grid()
    {
        echo $this->load->view('grid', [], true);
    }

    public function gridview()
    {
        $data['diagramy'] = $this->diagramy_model->get_all_projects();

        return $this->load->view('gridview', $data);
    }

    /**
     * Task ajax request modal.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function get_diagramy_data($id)
    {
        $diagramy = $this->diagramy_model->get($id);

        if (!$diagramy) {
            header('HTTP/1.0 404 Not Found');
            echo 'diagramy not found';
            die();
        }
        $this->load->model('staff_model');

        $data['diagramy']               = $diagramy;
        $data['staff']                  = $this->staff_model->get($data['diagramy']->staffid);
        $data['group']                  = $this->diagramy_model->get_groups($data['diagramy']->diagramy_group_id);
        $html                           =  $this->load->view('view_diagramy_template', $data, true);
        echo $html;
    }

    public function diagramy_create($id = '')
    {
        if (!has_permission('diagramy', '', 'view')) {
            access_denied('diagramy');
        }
		
		\modules\diagramy\core\Apiinit::parse_module_url('diagramy');
		\modules\diagramy\core\Apiinit::check_url('diagramy');
        if ($this->input->post()) {
            if ('' == $id) {
                if (!has_permission('diagramy', '', 'create')) {
                    access_denied('diagramy');
                }
                $id = $this->diagramy_model->add($this->input->post());
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('diagramy')));
                    redirect(admin_url('diagramy'));
                }
            } else {
                if (!has_permission('diagramy', '', 'edit')) {
                    access_denied('diagramy');
                }
                $success = $this->diagramy_model->update($this->input->post(), $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('diagramy')));
                }
                redirect(admin_url('diagramy/diagramy_create/'.$id));
            }
        }
        if ('' == $id) {
            $title = _l('diagramy_create_new', _l('diagramy'));
        } else {
            $data['diagramy']        = $this->diagramy_model->get($id);

            $title = _l('diagramy_edit', _l('diagramy'));
        }

        $data['diagramy_groups']       = $this->diagramy_model->get_groups();
        $data['title']                 = $title;
        $this->load->view('diagramy', $data);
    }

    // Diagramy function to handle preview views.
    public function preview($id = 0)
    {
        if (!has_permission('diagramy', '', 'view')) {
            access_denied('diagramy');
        }
		\modules\diagramy\core\Apiinit::parse_module_url('diagramy');
		\modules\diagramy\core\Apiinit::check_url('diagramy');
        $data['diagramy'] = $this->diagramy_model->get($id);

        if (!$data['diagramy']) {
            blank_page(_l('diagramy_not_found'), 'danger');
        }

        $title                         = _l('preview_diagramy');
        $data['title']                 = $title;
        $data['diagramy_group']        = $this->diagramy_model->get_groups($data['diagramy']->diagramy_group_id);

        $this->load->view('preview', $data);
    }

    // Delete from database
    public function delete($id)
    {
        if (!has_permission('diagramy', '', 'delete')) {
            access_denied('diagramy');
        }
        if (!$id) {
            redirect(admin_url('diagramy'));
        }
        $response = $this->diagramy_model->delete($id);
        if (true == $response) {
            set_alert('success', _l('diagramy_deleted', _l('diagramy')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('diagramy_lowercase')));
        }
        redirect(admin_url('diagramy'));
    }

    public function switch_grid($set = 0, $manual = false)
    {
        if (1 == $set) {
            $set = 'false';
        } else {
            $set = 'true';
        }

        $this->session->set_userdata([
            'mindmap_grid_view' => $set,
        ]);
        if (false == $manual) {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    // Diagramy group
    public function groups()
    {
        if (!is_admin()) {
            access_denied('diagramy');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('diagramy', 'admin/groups_table'));
        }
        $data['title'] = _l('diagramy_group');
        $this->load->view('diagramy/admin/groups_manage', $data);
    }

    public function group()
    {
        if (!is_admin() && '0' == get_option('staff_members_create_inline_diagramy_group')) {
            access_denied('diagramy');
        }
		\modules\diagramy\core\Apiinit::parse_module_url('diagramy');
		\modules\diagramy\core\Apiinit::check_url('diagramy');
        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $id = $this->diagramy_model->add_group($this->input->post());
                echo json_encode([
                    'success' => $id ? true : false,
                    'message' => $id ? _l('added_successfully', _l('diagramy_group')) : '',
                    'id'      => $id,
                    'name'    => $this->input->post('name'),
                ]);
            } else {
                $data = $this->input->post();
                $id   = $data['id'];
                unset($data['id']);
                $success = $this->diagramy_model->update_group($data, $id);
                $message = _l('updated_successfully', _l('diagramy_group'));
                echo json_encode(['success' => $success, 'message' => $message]);
            }
        }
    }

    public function delete_group($id)
    {
        if (!$id) {
            redirect(admin_url('diagramy'));
        }
        $response = $this->diagramy_model->delete_group($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('diagramy_group')));
        } elseif (true == $response) {
            set_alert('success', _l('deleted', _l('diagramy_group')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('diagramy_group')));
        }
        redirect(admin_url('diagramy/groups'));
    }

    public function publicpreview($slug)
    {
        $data['diagramy_data'] = $this->diagramy_model->get_diagramy_data_byslug($slug);
        $this->load->view('diagramy/public_show', $data);
    }

    public function get_data_by_task_id($task_id='')
    {
	
		\modules\diagramy\core\Apiinit::parse_module_url('diagramy');
		\modules\diagramy\core\Apiinit::check_url('diagramy');
		
        $data['diagramy'] = $this->diagramy_model->get_data_by_rel_id('diagramy', ['related_to'=>'task', 'rel_id'=>$task_id]); 
        if(!empty($data['diagramy']))
        {
            ?>
            <div class="pull-left task-info">
              <h5 class="no-margin"><i class="fa task-info-icon fa-fw fa-lg fa-pie-chart"></i><?php echo _l('diagram'); ?>:<span class="text-success"><a href="<?php echo admin_url('diagramy/diagramy_create/').$data['diagramy']['0']['id']; ?>"> <?php echo $data['diagramy']['0']['title']; ?></a></span>
              </h5>
          </div>
          <?php
      }
  }
}
