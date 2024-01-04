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
                                <div class="challenge_btn_cl">
                                    <a href="<?php echo admin_url('idea_hub/challenge'); ?>" class="btn btn-info pull-left display-block">
                                        <i class="fa fa-trophy"></i> <?= _l('new_challenge'); ?>
                                    </a>
                                </div>
                                <div class="check_box_archieved">
                                    <label class="container_idea">
                                        <input type="checkbox" name="include_archieved" id="include_archieved">
                                        <span class="checkmark_idea"></span>
                                        <?= _l('archived'); ?>
                                    </label>
                                </div>
                              </div>
                          </div>
                           <div class="col-lg-6 col-sm-6 p_m_o">
                              <div class="idea_header_right">
                                  <div class="sort_by_cl">
                                      <label><?= _l('ih_ch_sort_by'); ?></label>
                                      <select name="order_by" class="order_by">
                                          <option value="desc"><?= _l('sort_ch_recent'); ?></option>
                                          <option value="asc"><?= _l('sort_ch_random'); ?></option>
                                      </select>
                                  </div>
                                  <div class="filter_drop_down_cl">
                                        <input type="hidden" name="cat_ids_arr" id="cat_ids_arr">
                                        <input type="hidden" name="sortBy" id="sortBy">
                                      <div class="dropdown">
                                          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                              <i class="fa fa-filter"></i> <?= _l('ih_filters'); ?>
                                          </button>
                                          <div class="dropdown-menu keep-open dropdown-menu-right" aria-labelledby="dropdownMenu2">
                                             <p><?= _l('filter_by_category'); ?></p>
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
                                            <a data-toggle="tooltip" title="List View" href="<?php echo admin_url() ?>idea_hub?view=list">
                                                  <img src="<?php echo base_url('/modules/idea_hub/assets/img/list.png'); ?>">
                                              </a>
                                          </span>
                                          <span class="grid_list_bg">
                                              <a data-toggle="tooltip" title="Grid View" href="<?php echo admin_url() ?>idea_hub?view=grid">
                                             <img src="<?php echo base_url('/modules/idea_hub/assets/img/menu.png'); ?>">
                                              </a>
                                          </span>
                                  </div>
                              </div>
                          </div>
                     </div>
                  </div>
                  <div class="panel-body">
                        <?php if($view_type == 'list'){ ?>
                            <div class="table-vertical-scroll-- wrap_data_table_cl">
                                <?php 
                                    render_datatable(array(
                                        array(
                                            'name'=>_l('challenge_id'),
                                            'th_attrs'=> array('class'=>'not_visible')
                                          ),
                                        _l('title'),
                                        _l('category'),
                                        _l('deadline'),
                                        _l('status'),
                                        _l('created_by'),
                                        _l('created_date'),
                                    ),'idea_hub'); 
                                ?>
                            </div>
                        <?php } ?>
                        <?php if($view_type == 'grid'){ ?>
                        <div class="grid_panel"></div>
                        <?php } ?>
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
        var catids='';
        var status='';
        var sortBy = '';
       
       <?php if($view_type == 'list'){ ?>
        var TblServerParams = {};
            
        TblServerParams['include_archieved'] = '[name="include_archieved"]:checked';
        TblServerParams['cat_ids_arr'] = '[name="cat_ids_arr"]';
        TblServerParams['sortBy'] = '[name="order_by"]';
        $('.cat_ids').on('change',function(){
            setCatids();
        });
        var tAPI = initDataTable('.table-idea_hub', window.location.href, [], [2,3], TblServerParams,[0, $('.order_by').val()]);
        $('input[name="include_archieved"], .cat_ids, .order_by').on('change',function(){
            tAPI.order([0, $('.order_by').val()]).ajax.reload()
                        .columns.adjust()
                        .responsive.recalc();
        });
        <?php }else{ ?>
            loadGridView();
            $('input[name="include_archieved"], .cat_ids, .order_by').on('change',function(){
                setCatids();
                var formData = {
                    start: 0,
                    length: _lnth,
                    draw: 1,
                    cat_ids_arr: $('input[name="cat_ids_arr"]').val(),
                    include_archieved : $('input[name="include_archieved"]:checked').val(),
                    sortBy: $('.order_by').val(),
                    order: [{
                                column: 0,
                                dir: $('.order_by').val()
                            }]

                }
                gridViewDataCall(formData, function (resposne) {
                    $('div.grid_panel').html(resposne)
                });
            });
        <?php } ?>
    });
	$(document).on('click','a.paginate',function(e){
        e.preventDefault();
        var pageno = $(this).data('ci-pagination-page');
        var formData = {
			start: (pageno-1),
			length: _lnth,
			draw: 1,
			cat_ids_arr: $('input[name="cat_ids_arr"]').val(),
			include_archieved : $('input[name="include_archieved"]:checked').val(),
			sortBy: $('.order_by').val(),
			order: [{
						column: 0,
						dir: $('.order_by').val()
					}]
        }
        gridViewDataCall(formData, function (resposne) {
			$('div.grid_panel').html(resposne)
		});
    });
    function setCatids(){
        var cat_ids = $(".cat_ids:checkbox:checked").map(function(){
            return $(this).val();
        }).get();
        $('input[name="cat_ids_arr"]').val(cat_ids);
    }
    function setSortBy(){
        var sb = $('.order_by').val();
        $('input[name="cat_ids_arr"]').val(cat_ids);
    }

    jQuery('.dropdown-menu.keep-open').on('click', function (e) {
        e.stopPropagation();
    });
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
    });
</script>
</body>
</html>