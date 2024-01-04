<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="idea_hub">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
              <div class="panel_s">
                  <div class="panel-body ad_bottom_cl">
                     <div class="row">
                          <div class="col-lg-6 col-sm-6 p_m_o">
                              <div class="idea_header_left">
                                <div class="impression_btn_cl">
                                    <input type="hidden" name="_filters_cats" id="_filters_cats">
                                    <a href="<?php echo admin_url('idea_hub/idea/'.$challenge_id); ?>" class="btn btn-info pull-left display-block">
                                        <i class="fa fa-lightbulb-o"></i> <?php echo _l('new_idea'); ?>
                                    </a>
                                </div>
                              </div>
                          </div>
                           <div class="col-lg-6 col-sm-6 p_m_o">
                              <div class="idea_header_right">
                                  <div class="sort_by_cl">
                                      <label>Sort By:</label>
                                      <select name="order_by" class="order_by">
                                          <option value="desc">Most Recent</option>
                                          <option value="asc">Old</option>
                                      </select>
                                  </div>
                                  <div class="filter_drop_down_cl">
                                      <div class="dropdown">
                                          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                              <i class="fa fa-filter"></i> Filter
                                          </button>
                                          <div class="dropdown-menu keep-open dropdown-menu-right" aria-labelledby="dropdownMenu2">
                                             <p>Filter By Category</p>
                                             <ul>
                                                 <?php foreach($categories as $key=>$category) { ?>
                                                 <li>
                                                     <label class="container_filter"><?php echo $category['name']; ?>
                                                         <input type="checkbox" class="cat_ids" name="cat_ids[]" value="<?php echo $category['id']; ?>">
                                                         <span class="checkmark_filter"></span>
                                                     </label>
                                                 </li>
                                                 <?php } ?>
                                             </ul>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="list_and_grid">
                                          <span>
                                              <a href="<?php echo admin_url('idea_hub/ideas/' . $challenge_id); ?>?view=tree" data-toggle="tooltip"  title="Tree View">
                                                  <img src="<?php echo base_url('modules/idea_hub/assets/img/hierarchical-structure.png'); ?>">
                                              </a>
                                          </span>
                                          <span>
                                              <a href="<?php echo admin_url('idea_hub/ideas/' . $challenge_id); ?>?view=kanban" data-toggle="tooltip" title="Kanban Board">
                                                  <img src="<?php echo base_url('modules/idea_hub/assets/img/kanban.png'); ?>">
                                              </a>
                                          </span>
                                          <span>
                                            <a data-toggle="tooltip" title="List View" href="<?php echo admin_url('idea_hub/ideas/' . $challenge_id); ?>?view=list">
                                                  <img src="<?php echo base_url('modules/idea_hub/assets/img/list.png'); ?>">
                                              </a>
                                          </span>
                                          <span class="grid_list_bg">
                                              <a data-toggle="tooltip" title="Grid View" href="<?php echo admin_url('idea_hub/ideas/' . $challenge_id); ?>?view=grid">
                                             <img src="<?php echo base_url('modules/idea_hub/assets/img/menu.png'); ?>">
                                              </a>
                                          </span>
                                  </div>
                              </div>
                          </div>
                     </div>
                  </div>
                  <div class="panel-body">
                       <div class="table-vertical-scroll-- wrap_data_table_cl">
                            <?php
                                render_datatable(array(
                                    array(
                                        'name'=>_l('idea_id'),
                                        'th_attrs'=> array('class'=>'not_visible')
                                      ),
                                    _l('title'),
                                    _l('cover'),
                                    _l('status'),
                                    _l('point'),
                                    _l('stage'),
                                    _l('category'),
                                    _l('comments'),
                                    _l('created_by'),
                                    _l('created_date')
                                ),'idea_hub_idea');
                            ?>
                        </div>
                  </div>
              </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    "use strict";
    var _lnth = 6;
    $(function(){
        var TblServerParams = {"catids": '[name="_filters_cats"]', "statusids": '[name="_filters_status"]', "stageids": '[name="_filters_stages"]', "search_idea": '[name="search_idea"]'};
        var tAPI = initDataTable('.table-idea_hub_idea', admin_url+'idea_hub/idea_table/'+'<?php echo $challenge_id; ?>', [], [2, 3], TblServerParams,[0, $('.order_by').val()]);
        $.each(TblServerParams, function(i, obj) {
            $('input' + obj).on('change', function() {
                $('table.table-idea_hub_idea').DataTable().ajax.reload()
                .columns.adjust()
                .responsive.recalc();
            });
        });
        $('.order_by').on('change',function(){
            tAPI.order([0, $('.order_by').val()]).ajax.reload()
                        .columns.adjust()
                        .responsive.recalc();
           //tAPI.ajax.reload();
        });
        var params = [];
        $('.cat_ids:checkbox').on('change', function() {
            params = [];
            $('.cat_ids:checkbox:checked').each(function(i, obj){
                params.push($(obj).val());
            });
            var catids =  JSON.stringify(params);
            $("#_filters_cats").val(catids).change();
        });
    });
</script>
</body>
</html>