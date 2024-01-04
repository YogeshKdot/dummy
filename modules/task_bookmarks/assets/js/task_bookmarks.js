// Change task milestone from single modal
function task_change_task_bookmarks(task_bookmarks_id, task_id) {
    url = 'task_bookmarks/change_task_bookmarks/' + task_bookmarks_id + '/' + task_id;
    var taskModalVisible = $('#task-modal').is(':visible');
    url += '?single_task=' + taskModalVisible;
    requestGetJSON(url).done(function(response) {
        if (response.success === true || response.success == 'true') {
            reload_tasks_tables();
            if (taskModalVisible) { _task_append_html(response.taskHtml); }
        }
    });
}

// Change task milestone from single modal
function task_add_task_bookmarks(task_bookmarks_id, task_id) {
    url = 'task_bookmarks/add_task_bookmarks/' + task_bookmarks_id + '/' + task_id;
    var taskModalVisible = $('#task-modal').is(':visible');
    url += '?single_task=' + taskModalVisible;
    requestGetJSON(url).done(function(response) {
        if (response.success === true || response.success == 'true') {
            reload_tasks_tables();
            if (taskModalVisible) { _task_append_html(response.taskHtml); }
        }
    });
}

// Change task milestone from single modal
function remove_task_bookmarks(task_bookmarks_id, task_id, append = true) {
    url = 'task_bookmarks/remove_task_bookmarks/' + task_bookmarks_id + '/' + task_id;
    var taskModalVisible = $('#task-modal').is(':visible');
    url += '?single_task=' + taskModalVisible;
    requestGetJSON(url).done(function(response) {
        if (response.success === true || response.success == 'true') {
            reload_tasks_tables();
            if(append == true){
                if (taskModalVisible) { _task_append_html(response.taskHtml); }
            }
        }
    });
}

// Change task milestone from single modal
function add_task_bookmarks(task_bookmarks_id, task_id, append = true) {
    url = 'task_bookmarks/add_task_bookmarks/' + task_bookmarks_id + '/' + task_id;
    var taskModalVisible = $('#task-modal').is(':visible');
    url += '?single_task=' + taskModalVisible;
    requestGetJSON(url).done(function(response) {
        if (response.success === true || response.success == 'true') {
            reload_tasks_tables();
            if(append == true){
                if (taskModalVisible) { _task_append_html(response.taskHtml); }
            }
        }
    });
}