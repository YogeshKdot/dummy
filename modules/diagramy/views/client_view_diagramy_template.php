<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>modules/diagramy/assets/css/template.css">
<?php
if (isset($diagramy['0'])) 
{
  ?>
  <div id="wrapper">
    <div class="content">
      <div class="row">
        <div class="col-md-12">

          <div class="panel_s "  style="margin-top: 50px">
            <div class="panel-body">
              <form action="" method="post">
                <center>
                  <h1 class="modal-title"><?php echo $value = (isset($diagramy) ? $diagramy['0']['title'] : _l('diagram')); ?></h1>
                  <div class="tc-content">
                    <div id="diagramy_draw">
                      <div id="map">
                        <img id="image" style="max-width:100%;"  src="<?php echo $value = (isset($diagramy) ? $diagramy['0']['diagramy_content'] : ''); ?>" />
                      </div>
                    </div>
                  </div>
                </center>

              </form>
            </div>  
          </div>

        </div>
      </div>
    </div>
  </div>
  <?php
} 


?>
