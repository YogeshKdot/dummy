<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>

<div id="wrapper">

   <div class="content">

      <div class="row">

         <div class="col-md-12 left-column">

            <div class="panel_s">

               <div class="panel-body">

               <?php echo form_open('zoom_meetings/zoom_meetings/submit_meeting',array('id'=>'meeting-submit-form')); ?>

                 
`
                

               <div class="form-group select-placeholder projects-wrapper<?php if((!isset($contract)) || (isset($contract) && !customer_has_projects($contract->client))){ echo ' hide';} ?>">

                

           

            </div>



           <div class="row">
		     <div class="col-md-6">
			 <label for="timezone"><?php echo _l('zoom_customer'); ?></label>
 <select  id="clientid" name="clientid" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
             <?php $selected = (isset($expense) ? $expense->clientid : '');
                        if($selected == ''){
                          $selected = (isset($customer_id) ? $customer_id: '');
                        }
                        if($selected != ''){
                         $rel_data = get_relation_data('customer',$selected);
                         $rel_val = get_relation_values($rel_data,'customer');
                         echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['name'].'</option>';
                        } ?>
                     </select>
               </div>
			   <div class="col-md-6">
                                                  <div class="form-group">
                                                       <?php echo render_select('staff[]', $staff_members, array('staffid', array('firstname', 'lastname')), 'staff', [], array('multiple' => true), array(), '', '', false); ?>
                                                  </div>
               </div>
			   
			   
		   </div>
		   <hr>
		   <br>
		    <div class="row">
				 <div class="col-md-6">
				<?php echo render_input('subject','zoom_meeting_subject','','text',array('required'=>'true')); ?>

				</div>
		   </div>

            

         <div class="row">



         <div class="col-md-6">



						  <div class="form-group open-ticket-subject-group">



							 <label for="timezone"><?php echo _l('zoom_timezone'); ?></label>



								   <select required  name="timezone" id="timezone" class="form-control selectpicker">



									  <option value="" ><?php echo _l('select'); ?></option>



									  <option value="Pacific/Midway" >Midway Island, Samoa</option>



									  <option value="Pacific/Pago_Pago" >Pago Pago</option>



									  <option value="Pacific/Honolulu" >Hawaii</option>



									  <option value="America/Anchorage" >Alaska</option>



									  <option value="America/Vancouver" >Vancouver</option>

                            

                             <option value="America/Los_Angeles" >Pacific Time (US and Canada)</option>

                             <option value="America/Tijuana" >Tijuana</option>

                             <option value="America/Edmonton" >Edmonton</option>

                             <option value="America/Denver" >Mountain Time (US and Canada)</option>

                             <option value="America/Phoenix" >Arizona</option>

                             <option value="America/Mazatlan" >Mazatlan</option>

                            

                             <option value="America/Winnipeg" >Winnipeg</option>

                             <option value="America/Regina" >Regina</option>

                             <option value="America/Chicago" >Chicago</option>

                             <option value="America/Mexico_City" >Mexico_City</option>

                             <option value="America/Guatemala" >Guatemala</option>

                             <option value="America/El_Salvador" >El_Salvador</option>

                             <option value="America/Managua" >Managua</option>

                             <option value="America/Costa_Rica" >Costa_Rica</option>

                             <option value="America/Montreal" >Montreal</option>

                             <option value="America/New_York" >New_York</option>

                             <option value="America/Indianapolis" >Indianapolis</option>

                             <option value=" America/Panama" >Panama</option>

                             <option value="America/Bogota" >Bogota</option>

                             <option value="America/Lima" >Lima</option>

                             <option value="America/Halifax" >Halifax</option>

                             <option value=" America/Puerto_Rico" >Puerto_Rico</option>

                             <option value="America/Caracas" >Caracas</option>

                             <option value="America/Santiago" >Santiago</option>

                             <option value="America/St_Johns" >St_Johns</option>

                             <option value="America/Montevideo" >Montevideo</option>

                             <option value="America/Araguaina" >Araguaina</option>

                             <option value="America/Argentina/Buenos_Aires" >Buenos Aires</option>

                             <option value="America/Godthab" >Godthab</option>

                             <option value="America/Sao_Paulo" >Sao Paulo</option>

                             <option value="Atlantic/Azores" >Azores</option>

                             <option value="Canada/Atlantic" >Atlantic</option>

                             <option value="Atlantic/Cape_Verde" >Cape Verde</option>

                             <option value="UTC" >Universal Time UTC</option>

                             <option value="Etc/Greenwich" >Greenwich</option>

                             <option value="Europe/Belgrade" >Belgrade</option>

                             <option value="CET" >Sarajevo, Skopje, Zagreb</option>

                             <option value="Atlantic/Reykjavik" >Reykjavik</option>

                             <option value="Europe/Dublin" >Dublin</option>

                             <option value="Europe/London" >London</option>

                             <option value="Europe/Lisbon" >Lisbon</option>

                             <option value="Africa/Casablanca" >Casablanca</option>

                             <option value="Africa/Nouakchott" >Nouakchott</option>

                             <option value="Europe/Oslo" >Oslo</option>

                             <option value="Europe/Copenhagen" >Copenhagen</option>

                             <option value="Europe/Brussels" >Brussels</option>

                             <option value="Europe/Berlin" >Berlin</option>

                             <option value="Europe/Helsinki" >Helsinki</option>

                             <option value="Europe/Amsterdam" >Amsterdam</option>

                             <option value="Europe/Rome" >Rome</option>

                             <option value="Europe/Stockholm" >Stockholm</option>

                             <option value="Europe/Vienna" >Vienna</option>

                             <option value="Europe/Luxembourg" >Luxembourg</option>

                             <option value="Europe/Paris" >Paris</option>

                             <option value="Europe/Zurich" >Zurich</option>

                             <option value="Europe/Madrid" >Madrid</option>

                             <option value="Africa/Bangui" >Bangui</option>

                             <option value="Africa/Algiers" >Algiers</option>

                             <option value="Africa/Tunis" >Tunis</option>

                             <option value="Africa/Harare" >Harare</option>

                             <option value="Africa/Nairobi" >Nairobi</option>

                             <option value="Europe/Warsaw" >Warsaw</option>

                             <option value="Europe/Prague" >Prague</option>

                             <option value="Europe/Budapest" >Budapest</option>

                             <option value="Europe/Sofia" >Sofia</option>

                             <option value="Europe/Istanbul" >Istanbul</option>

                             <option value="Europe/Athens" >Athens</option>

                             <option value="Europe/Bucharest" >Bucharest</option>

                             <option value="Asia/Nicosia" >Nicosia</option>

                             <option value="Asia/Beirut" >Beirut</option>

                             <option value="Asia/Damascus" >Damascus</option>

                             <option value="Asia/Jerusalem" >Jerusalem</option>

                             <option value="Asia/Amman" >Amman</option>

                             <option value="Africa/Tripoli" >Tripoli</option>

                             <option value="Africa/Cairo" >Cairo</option>

                             <option value="Africa/Johannesburg" >Johannesburg</option>

                             <option value="Europe/Moscow" >Moscow</option>

                             <option value="Asia/Baghdad" >Baghdad</option>

                             <option value="Asia/Kuwait" >Kuwait</option>

                             <option value="Asia/Riyadh" >Riyadh</option>

                             <option value="Asia/Bahrain" >Bahrain</option>

                             <option value="Asia/Qatar" >Qatar</option>

                             <option value="Asia/Aden" >Aden</option>

                             <option value="Asia/Tehran" >Tehran</option>

                             <option value="Africa/Khartoum" >Khartoum</option>

                             <option value="Africa/Djibouti" >Djibouti</option>

                             <option value="Africa/Mogadishu" >Mogadishu</option>

                             <option value="Asia/Dubai" >Dubai</option>

                             <option value="Asia/Muscat" >Muscat</option>

                             <option value="Asia/Baku" >Baku</option>

                             <option value="Asia/Kabul" >Kabul</option>

                             <option value="Asia/Yekaterinburg" >Yekaterinburg</option>

                             <option value="Asia/Tashkent" >Islamabad, Karachi, Tashkent</option>

                             <option value="Asia/Calcutta" >India</option>

                             <option value="Asia/Kathmandu" >Kathmandu</option>

                             <option value="Asia/Novosibirsk" >Novosibirsk</option>

                             <option value="Asia/Almaty" >Almaty</option>

                             <option value="Asia/Dacca" >Dacca</option>

                             <option value=" Asia/Krasnoyarsk" >Krasnoyarsk</option>

                             <option value="Asia/Dhaka" >Astana, Dhaka</option>

                             <option value="Asia/Bangkok" >Bangkok</option>

                             <option value="Asia/Saigon" >Saigon</option>

                             <option value="Asia/Jakarta" >Jakarta</option>

                             <option value="Asia/Irkutsk" >Irkutsk</option>

                             <option value="Asia/Shanghai" >Shanghai</option>

                             <option value="Asia/Hong_Kong" >HongKong</option>

                             <option value="Asia/Taipei" >Taipei</option>

                             <option value="Asia/Kuala_Lumpur" >KualaLumpur</option>

                             <option value="Asia/Singapore" >Singapore</option>

                             <option value="Australia/Perth" >Perth</option>

                             <option value="Asia/Yakutsk" >Yakutsk</option>

                             <option value="Asia/Seoul" >Seoul</option>

                             <option value="Asia/Tokyo" >Tokyo</option>

                             <option value="Australia/Darwin" >Darwin</option>

                             <option value="Australia/Adelaide" >Adelaide</option>

                             <option value="Asia/Vladivostok" >Vladivostok</option>

                             <option value="Pacific/Port_Moresby" >Port Moresby</option>

                             <option value="Australia/Brisbane" >Brisbane</option>

                             <option value="Australia/Sydney" >Sydney</option>

                             <option value="Australia/Hobart" >Hobart</option>

                             <option value="Asia/Magadan" >Magadan</option>

                             <option value="SST" >Solomon Islands</option>

                             <option value="Pacific/Noumea" >Noumea</option>

                             <option value="Asia/Kamchatka" >Kamchatka</option>

                             <option value="Pacific/Fiji" >Fiji</option>

                             <option value="Pacific/Auckland" >Auckland</option>

                             <option value="Asia/Kolkata" >Kolkata</option>

                             <option value="Europe/Kiev" >Kiev</option>

                             <option value="America/Tegucigalpa" >Tegucigalpa</option>

                             <option value="Pacific/Apia" >Apia</option>



								   </select>



							 <?php echo form_error('subject'); ?>



						  </div>



					 </div> 

            <div class="col-md-6">

              

               <?php echo render_datetime_input('start_time','zoom_start_date'); ?>

            </div>

            

         </div>



         <div class="row">



               <div class="col-md-6">

               <?php echo render_input('duration','zoom_meeting_duration'); ?>



               </div>
			   
			   

         </div>
		 <div class="row">
		 <div class="col-md-6">
                                             <h4 class="mfont-bold-medium-size mtop1"><?= _l('zoom_additional_settings'); ?></h4>
                                             <hr>
                                             <div class="form-group">
                                                  <div class="checkbox checkbox-primary">
                                                       <input type="checkbox" name="join_before_host" id="join_before_host">
                                                       <label for="join_before_host"><?= _l('zoom_join_before_host'); ?></label>
                                                  </div>
                                                  <div class="checkbox checkbox-primary">
                                                       <input type="checkbox" name="host_video" id="host_video">
                                                       <label for="host_video"><?= _l('zoom_host_video'); ?></label>
                                                  </div>
                                                  <div class="checkbox checkbox-primary">
                                                       <input type="checkbox" name="participant_video" id="participant_video">
                                                       <label for="participant_video"><?= _l('zoom_participant_video'); ?></label>
                                                  </div>
                                                  <div class="checkbox checkbox-primary">
                                                       <input type="checkbox" name="mute_upon_entry" id="mute_upon_entry">
                                                       <label for="mute_upon_entry"><?= _l('zoom_mute_upon_entry'); ?></label>
                                                  </div>
                                                  <div class="checkbox checkbox-primary">
                                                       <input type="checkbox" name="waiting_room" id="waiting_room">
                                                       <label for="waiting_room"><?= _l('zoom_waiting_room'); ?></label>
                                                  </div>
                                                  <div class="ptop10">

                                                  </div>
                                             </div>
                                        </div>
		 
		 </div>
         <hr>
         <div class="row">

            <div class="col-md-12">

            <?php echo render_textarea('agenda','zoom_meeting_agenda',array('rows'=>10)); ?>

            </div>

         </div>



         <div class="btn-bottom-toolbar text-right">

            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>

         </div>

         <?php echo form_close(); ?>

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

<?php init_tail(); ?>

<script>
   var customer_currency = '';
   Dropzone.options.expenseForm = false;
   var expenseDropzone;
   init_ajax_project_search_by_customer_id();
   var selectCurrency = $('select[name="currency"]');
   <?php if(isset($customer_currency)){ ?>
     var customer_currency = '<?php echo $customer_currency; ?>';
   <?php } ?>


   $('select[name="clientid"]').on('change',function(){
       customer_init();
       
     });

    function customer_init(){
        var customer_id = $('select[name="clientid"]').val();
        var projectAjax = $('select[name="project_id"]');
        var clonedProjectsAjaxSearchSelect = projectAjax.html('').clone();
        var projectsWrapper = $('.projects-wrapper');
        projectAjax.selectpicker('destroy').remove();
        projectAjax = clonedProjectsAjaxSearchSelect;
        $('#project_ajax_search_wrapper').append(clonedProjectsAjaxSearchSelect);
        init_ajax_project_search_by_customer_id();
        if(!customer_id){
           set_base_currency();
           projectsWrapper.addClass('hide');
         }
       $.get(admin_url + 'expenses/get_customer_change_data/'+customer_id,function(response){
         if(customer_id && response.customer_has_projects){
           projectsWrapper.removeClass('hide');
         } else {
           projectsWrapper.addClass('hide');
         }
         var client_currency = parseInt(response.client_currency);
       
       },'json');
     }


</script>

</body>

</html>

