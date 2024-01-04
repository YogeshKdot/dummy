<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
* Prepare query for grid
*/
function prepare_grid_query($aColumns, $sIndexColumn, $sTable, $join = [], $where = [], $additionalSelect = [], $sGroupBy = '', $searchAs = [])
{
    $CI = & get_instance();
    $__post = $CI
        ->input
        ->post();
    $havingCount = '';
    $sLimit = '';
    if ((is_numeric($CI
        ->input
        ->post('start', true))) && $CI
        ->input
        ->post('length', true) != '-1')
    {
		$offset = $CI
            ->input
            ->post('start', true)*$CI
            ->input
            ->post('length', true);
		//echo $offset; die;
        $sLimit = 'LIMIT ' . intval($offset). ', ' . intval($CI
            ->input
            ->post('length', true));
    }
	//echo $sLimit; die; 
    $_aColumns = [];
    foreach ($aColumns as $column)
    {
        if (substr_count($column, '.') == 1 && strpos($column, ' as ') === false)
        {
            $_column = explode('.', $column);
            if (isset($_column[1]))
            {
                if (startsWith($_column[0], db_prefix()))
                {
                    $_prefix = prefixed_table_fields_wildcard($_column[0], $_column[0], $_column[1]);
                    array_push($_aColumns, $_prefix);
                }
                else
                {
                    array_push($_aColumns, $column);
                }
            }
            else
            {
                array_push($_aColumns, $_column[0]);
            }
        }
        else
        {
            array_push($_aColumns, $column);
        }
    }
    $nullColumnsAsLast = get_null_columns_that_should_be_sorted_as_last();
    $sOrder = '';
    if ($CI
        ->input
        ->post('order', true))
    {
        $sOrder = 'ORDER BY ';
        foreach ($CI
            ->input
            ->post('order', true) as $key => $val)
        {
            $columnName = $aColumns[intval($__post['order'][$key]['column']) ];
            $dir = strtoupper($__post['order'][$key]['dir']);
            if (strpos($columnName, ' as ') !== false)
            {
                $columnName = strbefore($columnName, ' as');
            }
            if ((in_array($sTable . '.' . $columnName, $nullColumnsAsLast) || in_array($columnName, $nullColumnsAsLast)))
            {
                $sOrder .= $columnName . ' IS NULL ' . $dir . ', ' . $columnName;
            }
            else
            {
                $sOrder .= hooks()->apply_filters('datatables_query_order_column', $columnName, $sTable);
            }
            $sOrder .= ' ' . $dir . ', ';
        }
        if (trim($sOrder) == 'ORDER BY')
        {
            $sOrder = '';
        }
        $sOrder = rtrim($sOrder, ', ');
        if (get_option('save_last_order_for_tables') == '1' && $CI
            ->input
            ->post('last_order_identifier', true) && $CI
            ->input
            ->post('order', true))
        {
            $indexedOnly = [];
            foreach ($CI
                ->input
                ->post('order', true) as $row)
            {
                $indexedOnly[] = array_values($row);
            }
            $meta_name = $CI
                ->input
                ->post('last_order_identifier', true) . '-table-last-order';
            update_staff_meta(get_staff_user_id() , $meta_name, json_encode($indexedOnly, JSON_NUMERIC_CHECK));
        }
    }
    $sWhere = '';
    if ((isset($__post['search'])) && $__post['search'] != '')
    {
        $search_value = $CI
            ->input
            ->post('search', true);
        $search_value = trim($search_value);
        $sWhere = 'WHERE (';
        $sMatchCustomFields = [];
        $useMatchForCustomFieldsTableSearch = hooks()->apply_filters('use_match_for_custom_fields_table_search', 'false');
        for ($i = 0;$i < count($aColumns);$i++)
        {
            $columnName = $aColumns[$i];
            if (strpos($columnName, ' as ') !== false)
            {
                $columnName = strbefore($columnName, ' as');
            }
            if (stripos($columnName, 'AVG(') !== false || stripos($columnName, 'SUM(') !== false)
            {
            }
            else
            {
                if (isset($searchAs[$i]))
                {
                    $columnName = $searchAs[$i];
                }
                if ($useMatchForCustomFieldsTableSearch === 'true' && startsWith($columnName, 'ctable_'))
                {
                    $sMatchCustomFields[] = $columnName;
                }
                else
                {
                    $sWhere .= 'convert(' . $columnName . ' USING utf8)' . " LIKE '%" . $CI
                        ->db
                        ->escape_like_str($search_value) . "%' OR ";
                }
            }
        }
        if (count($sMatchCustomFields) > 0)
        {
            $s = $CI
                ->db
                ->escape_like_str($search_value);
            foreach ($sMatchCustomFields as $matchCustomField)
            {
                $sWhere .= "MATCH ({$matchCustomField}) AGAINST (CONVERT(BINARY('{$s}') USING utf8)) OR ";
            }
        }
        if (count($additionalSelect) > 0)
        {
            foreach ($additionalSelect as $searchAdditionalField)
            {
                if (strpos($searchAdditionalField, ' as ') !== false)
                {
                    $searchAdditionalField = strbefore($searchAdditionalField, ' as');
                }
                if (stripos($columnName, 'AVG(') !== false || stripos($columnName, 'SUM(') !== false)
                {
                }
                else
                {
                    $sWhere .= 'convert(' . $searchAdditionalField . ' USING utf8)' . " LIKE '%" . $CI
                        ->db
                        ->escape_like_str($search_value) . "%' OR ";
                }
            }
        }
        $sWhere = substr_replace($sWhere, '', -3);
        $sWhere .= ')';
    }
    $_additionalSelect = '';
    if (count($additionalSelect) > 0)
    {
        $_additionalSelect = ',' . implode(',', $additionalSelect);
    }
    $where = implode(' ', $where);
    if ($sWhere == '')
    {
        $where = trim($where);
        if (startsWith($where, 'AND') || startsWith($where, 'OR'))
        {
            if (startsWith($where, 'OR'))
            {
                $where = substr($where, 2);
            }
            else
            {
                $where = substr($where, 3);
            }
            $where = 'WHERE ' . $where;
        }
    }
    $join = implode(' ', $join);
    $sQuery = 'SELECT SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $_aColumns)) . ' ' . $_additionalSelect . " FROM $sTable " . $join . " $sWhere " . $where . " $sGroupBy $sOrder $sLimit";
    
    $rResult = $CI
        ->db
        ->query($sQuery)->result_array();
    $last_query = $CI
        ->db
        ->last_query();
	
    $rResult = hooks()->apply_filters('datatables_sql_query_results', $rResult, ['table' => $sTable, 'limit' => $sLimit, 'order' => $sOrder, ]);
    $sQuery = 'SELECT FOUND_ROWS()';
    $_query = $CI
        ->db
        ->query($sQuery)->result_array();
    $iFilteredTotal = $_query[0]['FOUND_ROWS()'];
    if (startsWith($where, 'AND'))
    {
        $where = 'WHERE ' . substr($where, 3);
    }
    $sQuery = 'SELECT COUNT(' . $sTable . '.' . $sIndexColumn . ") FROM $sTable " . $join . ' ' . $where;
    $_query = $CI->db
                ->query($sQuery)->result_array();
    $iTotal = $_query[0]['COUNT(' . $sTable . '.' . $sIndexColumn . ')'];
    $output = ['draw' => $__post['draw'] ? intval($__post['draw']) : 0, 'iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'aaData' => [], ];
    return ['rResult' => $rResult, 'output' => $output, "query" => $last_query];
}

/*
* Handle cover image for challenge
*/
function handle_challenge_cover_image_upload($path = '')
{
    if (isset($_FILES['cover_image']['name']) && $_FILES['cover_image']['name'] != '')
    {
        $tmpFilePath = $_FILES['cover_image']['tmp_name'];
        if (!empty($tmpFilePath) && $tmpFilePath != '')
        {
            $extension = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($extension, $allowed_extensions))
            {
                set_alert('warning', _l('file_php_extension_blocked'));
                return false;
            }
            $filename = time() . '.' . $extension;
            $newFilePath = $path . '/' . $filename;
            if (move_uploaded_file($tmpFilePath, $newFilePath))
            {
                return $filename;
            }
        }
    }
    return false;
}

/*
* Getting challenge cover image url
*/
function ci_challenge_cover_image_url($image_name = '')
{
    $url = base_url('modules/idea_hub/assets/images/challenge_cover.jpg');
    if ($image_name)
    {
        if (!empty($image_name))
        {
            $coverImagePath = FCPATH . 'modules/idea_hub/uploads/challenges/' . $image_name;
            if (file_exists($coverImagePath))
            {
                $url = base_url($coverImagePath);
            }
        }
    }
    return $url;
}

/*
* Converting days into H:i:s format
*/
function convert_to_days_hrs_min_sec($from_date)
{
    $deadline = [];
    if (DateTime::createFromFormat('Y-m-d H:i:s', $from_date) !== false && strtotime($from_date) > time())
    {
        $now = new DateTime(date('Y-m-d H:i:s', time()));
        $your_date = new DateTime($from_date);
        $datediff = $your_date->diff($now);
        $deadline['days'] = $datediff->days;
        $deadline['hrs'] = $datediff->format('%h') ? $datediff->format('%h') : 0;
        $deadline['mins'] = $datediff->format('%i') ? $datediff->format('%i') : 0;
        $deadline['secs'] = $datediff->format('%s') ? $datediff->format('%s') : 0;
    }
    return $deadline;
}

/*
* Checking from date is greater then current time or not
*/
function ci_check_deadline_is_greater($from_date)
{
    $datediff = false;
    if (DateTime::createFromFormat('Y-m-d H:i:s', $from_date) !== false && strtotime($from_date) > time())
    {
        $datediff = true;
    }
    return $datediff;
}

/*
* Read more link
*/
function read_more($config, $link)
{
    $str = $config['str'];
    $len = $config['len'];
    $string = strip_tags($str);
    if (strlen($string) > $len)
    {
        $stringCut = substr($string, 0, $len);
        $endPoint = strrpos($stringCut, ' ');
        $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
        $string .= '... <a href="' . $link . '">read more</a>';
    }
    return $string;
}

/*
* Handle idea cover image
*/
function handle_idea_cover_image_upload($path = '')
{
    $filename = '';
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '')
    {
        $tmpFilePath = $_FILES['image']['tmp_name'];
        if (!empty($tmpFilePath) && $tmpFilePath != '')
        {
            $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mpeg-4', 'avi', 'mov', 'wmv', 'flv'];
            if (!in_array($extension, $allowed_extensions))
            {
                set_alert('warning', _l('file_php_extension_blocked'));
                return false;
            }
            $filename = time() . '.' . $extension;
            $newFilePath = $path . '/' . $filename;
            if (move_uploaded_file($tmpFilePath, $newFilePath))
            {
                return $filename;
            }
        }
    }
    return $filename;
}

/*
* Handle idea cover thumbnail 
*/
function handle_idea_cover_thumbnail_upload($path = '')
{
    if (isset($_FILES['video_thumbnail']['name']) && $_FILES['video_thumbnail']['name'] != '')
    {
        $tmpFilePath = $_FILES['video_thumbnail']['tmp_name'];
        if (!empty($tmpFilePath) && $tmpFilePath != '')
        {
            $extension = strtolower(pathinfo($_FILES['video_thumbnail']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($extension, $allowed_extensions))
            {
                set_alert('warning', _l('file_php_extension_blocked'));
                return false;
            }
            $filename = time() . '.' . $extension;
            $newFilePath = $path . '/' . $filename;
            if (move_uploaded_file($tmpFilePath, $newFilePath))
            {
                return $filename;
            }
        }
    }
    return false;
}

/*
* Getting category for challenge by id
*/
function get_category_by_challenge_id($challenge_id)
{
    $cat = null;
    if ($challenge_id)
    {
        $CI = & get_instance();
        $row = $CI
            ->db
            ->select(db_prefix() . 'idea_hub_category.*')
            ->from(db_prefix() . 'idea_hub_category')
            ->join(db_prefix() . 'idea_hub_challenges', db_prefix() . 'idea_hub_challenges.category_id=' . db_prefix() . 'idea_hub_category.id')
            ->where(db_prefix() . 'idea_hub_challenges.id', $challenge_id)->get()
            ->row_array();
        return $row;
    }
    return $cat;
}


/*
* Getting status by id
*/
function get_status_by_id($status_id)
{
    $status = null;
    if ($status_id)
    {
        $CI = & get_instance();
        $row = $CI
            ->db
            ->select('*')
            ->from(db_prefix() . 'idea_hub_status')
            ->where('id', $status_id)->get()
            ->row_array();
        return $row;
    }
    return $status;
}

/*
* Getting attachments by idea id
*/
function get_attachments_by_idea_id($idea_id)
{
    $attachments = null;
    if ($idea_id)
    {
        $CI = & get_instance();
        $query = $CI
            ->db
            ->select('*')
            ->from(db_prefix() . 'idea_hub_attachments')
            ->where('idea_id', $idea_id)->get();
        $i = 0;
        foreach ($query->result() as $row)
        {
            $attachments[$i]['file_name'] = $row->file_name;
            $attachments[$i]['file_title'] = $row->file_title;
            $i++;
        }
    }
    return $attachments;
}

/*
* Handle idea attachments
*/
function handle_idea_attachments_upload($path = '', $idea_id)
{
    if (isset($_FILES['attachment']))
    {
        $attachments = array();
        $myFile = $_FILES['attachment'];
        $fileCount = count($myFile["name"]);
        for ($i = 0;$i < $fileCount;$i++)
        {
            $tmpFilePath = $_FILES['attachment']['tmp_name'][$i];
            if (!empty($tmpFilePath) && $tmpFilePath != '')
            {
                $extension = strtolower(pathinfo($_FILES['attachment']['name'][$i], PATHINFO_EXTENSION));
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'gif', 'xlsx'];
                if (!in_array($extension, $allowed_extensions))
                {
                    set_alert('warning', _l('file_php_extension_blocked'));
                    return false;
                }
                $filetitle = $_FILES['attachment']['name'][$i];
                $filename = $idea_id . '_' . time() . '_' . $i . '.' . $extension;
                $newFilePath = $path . '/' . $filename;
                if (move_uploaded_file($tmpFilePath, $newFilePath))
                {
                    $attachments[$i]['file_title'] = $filetitle;
                    $attachments[$i]['file_name'] = $filename;
                    $attachments[$i]['idea_id'] = $idea_id;
                }
            }
        }
        return $attachments;
    }
    return false;
}

/*
* Getting tags name in array by idea id
*/
function tags_name_array_by_id($idea_id)
{
    $tags = null;
    if($idea_id)
    {
        $CI = & get_instance();
        $query = $CI
                ->db
                ->select('tag_name')
                ->from(db_prefix() . 'idea_hub_ideas_tags')
                ->where('idea_id', $idea_id)
                ->get();
        $rows = $query->result_array();
        $tags = array_column($rows, 'tag_name');     
    }
    return $tags;
}

/*
* Getting total ideas for challenge by id
*/
function ideas_count_by_challenge_id($id)
{
    $result = null;
    if ($id)
    {
        $CI = & get_instance();
        $result = $CI
            ->db
            ->select('count(id) as count')
            ->from(db_prefix() . 'idea_hub_ideas')
            ->where('challenge_id', $id)->get()
            ->row();
    }
    return html_escape($result ? $result->count : 0);
}

/*
* challenge total votes
*/
function challenge_votes_count($challenge_id)
{
    if ($challenge_id)
    {
        $CI = & get_instance();
        $query = $CI
            ->db
            ->query("SELECT COUNT(id) as total, (SELECT COUNT(id) FROM " . db_prefix() . 'idea_hub_challenges_votes' . " WHERE vote = 'up' and challenge_id = " . $challenge_id . ") as up, (SELECT COUNT(id) FROM " . db_prefix() . 'idea_hub_challenges_votes' . " WHERE vote = 'down' and challenge_id = " . $challenge_id . ") as down  FROM " . db_prefix() . 'idea_hub_challenges_votes' . " WHERE challenge_id = " . $challenge_id);
        $result = $query->result_array();
        return $result[0];
    }
}

/* 
* Getting loggedin user challenge votes
*/
function current_user_challenge_votes($challenge_id)
{
    $user_type = '';
    $user_id = '';
    if (is_client_logged_in())
    {
        $user_type = 'customer';
        $user_id = get_client_user_id();
    }
    if (is_staff_logged_in())
    {
        $user_type = 'staff';
        $user_id = get_staff_user_id();
    }
    if($challenge_id)
    {
        $result = null;
        $CI = & get_instance();
        $result = $CI
                    ->db
                    ->select('*')
                    ->from(db_prefix() . 'idea_hub_challenges_votes')
                    ->where(array(
                        'challenge_id' => $challenge_id,
                        'user_id' => $user_id,
                        'user_type' => $user_type
                    ))
                    ->get()
                    ->row();
        return html_escape($result ? $result->vote : 0);
    }
}

/*
* Getting ideas comments by challenge id
*/
function ideas_comments_by_challenge_id($challenge_id)
{
    if ($challenge_id)
    {
        $CI = & get_instance();
        $result = $CI
            ->db
            ->query("SELECT COUNT(id) as total FROM " . db_prefix() . 'idea_hub_ideas_comments' . " WHERE " . db_prefix() . 'idea_hub_ideas_comments.idea_id' . " IN (SELECT id FROM " . db_prefix() . 'idea_hub_ideas' . " WHERE challenge_id = " . $challenge_id . ")")->row();
        return html_escape($result ? $result->total : 0);
    }
}

/*
* Getting deadline of challenge by id
*/
function get_deadline_by_challenge_id($challenge_id)
{
    if ($challenge_id)
    {
        $CI = & get_instance();
        $imp = $CI
            ->db
            ->select('deadline')
            ->from(db_prefix() . 'idea_hub_challenges')
            ->where('id', $challenge_id)->get()
            ->row();
    }
    return html_escape($imp ? $imp->deadline : '');
}

/*
* Getting stage by id
*/
function get_stage_by_id($stage_id)
{
    if ($stage_id)
    {
        $CI = & get_instance();
        $row = $CI
            ->db
            ->select('*')
            ->from(db_prefix() . 'idea_hub_stages')
            ->where('id', $stage_id)->get()
            ->row_array();
        return $row;
    }
    return [];
}

/*
* Getting File Type
*/
function getFileMimeType($file)
{
    $mime_type = mime_content_type($file);
    return strtok($mime_type, '/');
}

/*
* Delete file from storage
*/
function unlink_file($meadiaPath = null)
{
    $uploadPath = FCPATH . 'modules/idea_hub/uploads/';
    if (isset($meadiaPath) && !empty($meadiaPath))
    {
        $path = $uploadPath . $meadiaPath;
        if (unlink($path))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

/*
* Read more link
*/
function generate_read_more_link($config, $link)
{
    $str = $config['str'];
    $len = $config['len'];
    $string = strip_tags($str);
    if (strlen($string) > $len)
    {
        $stringCut = substr($string, 0, $len);
        $endPoint = strrpos($stringCut, ' ');
        $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
        $string .= '... <a href="' . $link . '">read more</a>';
    }
    return $string;
}

/*
* Handle comment attachments
*/
function handle_idea_comment_attachments($discussion_id, $post_data, $insert_data)
{
    if (isset($_FILES['file']['name']) && _perfex_upload_error($_FILES['file']['error']))
    {
        header('HTTP/1.0 400 Bad error');
        echo json_encode(['message' => _perfex_upload_error($_FILES['file']['error']) ]);
        die;
    }
    if (isset($_FILES['file']['name']))
    {
        hooks()->do_action('before_upload_project_discussion_comment_attachment');
        $path = IDEA_HUB_DISCUSSION_ATTACHMENT_FOLDER . $discussion_id . '/';
        if (!_upload_extension_allowed($_FILES['file']['name']))
        {
            header('HTTP/1.0 400 Bad error');
            echo json_encode(['message' => _l('file_php_extension_blocked') ]);
            die;
        }
        $tmpFilePath = $_FILES['file']['tmp_name'];
        if (!empty($tmpFilePath) && $tmpFilePath != '')
        {
            _maybe_create_upload_path($path);
            $filename = unique_filename($path, $_FILES['file']['name']);
            $newFilePath = $path . $filename;
            if (move_uploaded_file($tmpFilePath, $newFilePath))
            {
                $insert_data['file_name'] = $filename;
                if (isset($_FILES['file']['type']))
                {
                    $insert_data['file_mime_type'] = $_FILES['file']['type'];
                }
                else
                {
                    $insert_data['file_mime_type'] = get_mime_by_extension($filename);
                }
            }
        }
    }
    return $insert_data;
}

function global_module_permission(){
	if(has_permission('idea_hub', '', 'view')||has_permission('idea_hub', '', 'edit')||has_permission('idea_hub', '', 'create')||has_permission('idea_hub', '', 'delete')){
		return true;
	}else{
		return false;
	}
}