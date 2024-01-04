<?php 

/* Adjust some of the default settings for tenants: start */
if (!function_exists('get_tenant_task_status')) {
	function get_tenant_task_status()
	{
	    get_instance()->load->model('tasks_model');
	    $statuses = get_instance()->tasks_model->get_statuses();
	    get_instance()->db->where_in('ID', array_column($statuses, 'id'));
		$result = get_instance()->db->delete(db_prefix() . 'task_statuses');
		$task_status = [];

		if ($result) {
		    $task_status = [
		        [
		            'id'             => 1,
		            'color'          => '#64748b',
		            'name'           => _l('to_start'),
		            'order'          => 1,
		            'filter_default' => true,
		        ],
		        [
		            'id'             => 2,
		            'color'          => '#3b82f6',
		            'name'           => _l('inProgress'),
		            'order'          => 2,
		            'filter_default' => true,
		        ],
		        [
		            'id'             => 4,
		            'color'          => '#84cc16',
		            'name'           => _l('awaiting_response'),
		            'order'          => 4,
		            'filter_default' => true,
		        ],
		        [
		            'id'             => 100,
		            'color'          => '#22c55e',
		            'name'           => _l('filled'),
		            'order'          => 100,
		            'filter_default' => false,
		        ],
		    ];
		}

	    return $task_status;
	}
}
/* Adjust some of the default settings for tenants: end */