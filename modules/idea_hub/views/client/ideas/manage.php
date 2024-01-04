<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$isListView = 'grid';
if ($this->session->userdata('ih_idea_list_view') == 'list') {
    $isListView = 'list';
}else if ($this->session->userdata('ih_idea_list_view') == 'kanban') {
    $isListView = 'kanban';
}else if ($this->session->userdata('ih_idea_list_view') == 'gantt') {
    $isListView = 'gantt';
}
?>
<link rel="stylesheet" type="text/css" href="<?= base_url('modules/idea_hub/assets/css/customer.css');?>">
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                	<div class="panel-body ad_bottom_cl">
                     
                     <div class="row">
                          <div class="col-lg-6 col-sm-6 p_m_o">
                              <div class="idea_header_left">
                                <div class="challenge_btn_cl">
                                    <input type="hidden" name="_filters_cats" id="_filters_cats">
                                    <button type="button" class="btn btn-info pull-left display-block" onclick="openChallengeModal();">
                                        <?=_l('challenge_overview');?>
                                    </button>
                                </div>
                              </div>
                          </div>
                           <div class="col-lg-6 col-sm-6 p_m_o">
                              <div class="idea_header_right">
                                  <div class="sort_by_cl">
                                      <label>Sort By:</label>
                                      <select name="order_by" class="orderBy">
                                          <option value="desc">Most Recent</option>
                                          <option value="asc">Old</option>
                                      </select>
                                  </div>
                                  
                                  <div class="filter_drop_down_cl">
                                    <input type="hidden" name="cat_ids_arr" id="cat_ids_arr">
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
                                  <div class="list_and_grid"></div>
                              </div>
                          </div>
                     </div>
                  </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="_buttons">
                                    <input type="hidden" name="_filters_cats" id="_filters_cats">
                                    <input type="hidden" name="_filters_status" id="_filters_status">
                                    <input type="hidden" name="_filters_stages" id="_filters_stages">
                                    <input type="hidden" name="challenge_id" id="challenge_id" value="<?php echo $challenge_id; ?>">
                                </div>  
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" style="border-top: 1px solid #fff;" />
                        <div class="row" id="concepttion-table">
                            <div class="col-lg-3 left_side_bar_full" style="display:none">
                                <div class="lft_side_bar">
                                    
                                </div>
                            </div>
                            <div class="col-lg-12 right_side_bar_full" >
                                <?php if($isListView ==1){ ?>
                                    <div class="clearfix"></div>
                                    <hr class="hr-panel-heading" />
                                <?php } ?>
                                <div class="col-md-12" id="small-table">
                                    <div class="grid-tab" id="grid-tab">
                                    </div>
                                </div>
                                <div class="col-md-7 small-table-right-col">
                                  <div id="idea_view" class="hide">
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade in" id="challenge-modal" tabindex="-1" role="dialog" style="display: none; z-index: 9999;">
    <div class="modal-dialog modal-md">
        <div class="modal-content animated fast zoomInUp">
            <div class="modal-header">
                <h3 class="modal-title"><?= _l('challenge_overview'); ?></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -40px;">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">                                         
                    <div class="col-md-12">
                        <div class="">
                            <div class="panel-body">
                                <div class="table_challenge_overview">
                                    <table class="table table-striped">
                                        <tr>
                                           <th>Title</th>
                                           <td><?php echo $challenge->title; ?></td>
                                        </tr>
                                        <tr>
                                           <th>Category</th>
                                           <?php $cat = get_category_by_challenge_id($challenge->id); ?>
                                           <td><span style="background-color: <?=$cat['color'];?>"><?=$cat['name'];?></span></td>
                                        </tr>
                                        <tr>
                                           <th>Description</th>
                                           <td><?php echo $challenge->description; ?></td>
                                        </tr>
                                        <tr>
                                           <th>Instruction</th>
                                           <td><?php echo $challenge->instruction; ?></td>
                                        </tr>
                                        <tr>
                                           <th>Deadline</th>
                                           <td><?php echo $challenge->deadline; ?></td>
                                        </tr>
                                        <tr>
                                           <th>Cover</th>
                                           <td><img width="60px" height="60px" src="<?=base_url('modules/idea_hub/uploads/challenges/'.$challenge->cover_image)?>"></td>
                                        </tr>
                                        <tr>
                                           <th>Status</th>
                                           <td><?php echo ucwords($challenge->status); ?></td>
                                        </tr>
                                   </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?=base_url('modules/idea_hub/assets/js/idea_hub_customer.js')?>"></script>
<script>
    var _lnth = 6;
    $(function(){
        loadIdeasGridView();
        $('.cat_ids,.orderBy').on('change',function(){
            setCatids();
            var formData = {
                challenge_id: '<?php echo $challenge_id; ?>',
                search_idea: $("input#search_idea").val(),
                start: 0,
                length: _lnth,
                draw: 1,
                cat_ids_arr: $('input[name="cat_ids_arr"]').val(),
                order: [{
                            column: 0,
                            dir: $('option:selected', '.orderBy').val()
                        }]
            }
            ideasGridViewDataCall(formData, function (resposne) {
                $('div#grid-tab').html(resposne)
            });
        });
        $(document).on('click','a.paginate',function(e){
            e.preventDefault();
            var pageno = $(this).data('ci-pagination-page');
            var formData = {
                search: $("input#search").val(),
                start: (pageno-1),
                length: _lnth,
                draw: 1,
                challenge_id: '<?php echo $challenge_id; ?>',
                search_idea: $("input#search_idea").val()
            }
            ideasGridViewDataCall(formData, function (resposne) {
                $('div#grid-tab').html(resposne)
            });
        });
    });

    function setCatids(){
        var cat_ids = $(".cat_ids:checkbox:checked").map(function(){
            return $(this).val();
        }).get();
        $('input[name="cat_ids_arr"]').val(cat_ids);
    }
</script>