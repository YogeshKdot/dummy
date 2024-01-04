<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
                                  
                            
                                  
                              </div>
                          </div>
                     </div>
                  </div>
                    <div class="panel-body">
                        <div class="grid-tab" id="grid-tab"></div>
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
        var params = [];
        $('.cat_ids,.orderBy').on('change',function(){
            setCatids();
            var formData = {
                search: $("input#search").val(),
                start: 0,
                length: _lnth,
                draw: 1,
                cat_ids_arr: $('input[name="cat_ids_arr"]').val(),
                order: [{
                            column: 0,
                            dir: $('option:selected', '.orderBy').val()
                        }]

            }
            challengeGridViewDataCall(formData, function (resposne) {
                $('div#grid-tab').html(resposne);
            });
        });
        
    });
    function setCatids(){
        var cat_ids = $(".cat_ids:checkbox:checked").map(function(){
            return $(this).val();
        }).get();
        $('input[name="cat_ids_arr"]').val(cat_ids);
    }
    $(function(){
        var TblServerParams = {"catids": '[name="_filters_cats"]'};
        loadGridForChallenge();
        $(document).on('click','a.paginate',function(e){
            e.preventDefault();
            var pageno = $(this).data('ci-pagination-page');
            var formData = {
                search: $("input#search").val(),
                start: (pageno-1),
                length: _lnth,
                draw: 1
            }
            challengeGridViewDataCall(formData, function (resposne) {
                $('div#grid-tab').html(resposne)
            });
        });
    });
</script>
</body>
</html>