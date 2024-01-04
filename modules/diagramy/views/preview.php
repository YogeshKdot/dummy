<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>modules/diagramy/assets/css/preview.css">
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php $value = (isset($diagramy) ? $diagramy->diagramy_content : ''); ?>
            <textarea id="diagramy_content" name="diagramy_content"><?php echo $value; ?></textarea>
            <div class="col-lg-12">
            	<div class="panel_s"  id="top-panel">
            		<div class="panel-body">
            			<h4 class="no-margin"><?php echo $title; ?>
                        <span class="close2" id="close">×</span>            
                    </h4>
                    <hr class="hr-panel-heading" />
                    <?php $value = (isset($diagramy) ? $diagramy->title : ''); ?>
                    <?php echo render_input('title', 'Title', $value, '', ['disabled'=>'disabled']); ?>
                    <?php
                    $mmgroup = ($diagramy_group) ? $diagramy_group->name : '';
                    echo render_input('diagramy_group_id', 'diagramy_group', $mmgroup, '', ['disabled'=>'disabled']);
                    ?>
                    <?php $value = (isset($diagramy) ? $diagramy->description : ''); ?>
                    <?php echo render_textarea('description', 'Description', $value, ['rows'=>4, 'disabled'=>'disabled'], []); ?>
                </div>
            </div>
            <div class="panel_s">
                <div class="panel-body">
                    <h4 class="no-margin"><?php echo _l('diagramy'); ?>
                    <span style="color:red;font-size:12px;">(Note:If you want to export image,url and print click on drawing Project.)</span>
                    <span>
                        <button id="expand-button" type="button" class="collapsible btn btn-success">Properties</button>
                        <a href="<?php echo base_url(); ?>admin/diagramy/publicpreview/<?php echo $diagramy->diagramy_slug; ?>" target="_blank"><button type="button" class="btn btn-warning" style="float:right;margin-right:2px;background:grey;"><i class="fa fa-share" style="padding-right:2px;"></i>Share</button></a>
                    </span>
                </h4>
                <hr class="hr-panel-heading" />
                <div class="row">
                    <div class="col-md-12">
                        <div id="map">
                           <div id="image" style="max-width:100%;cursor:pointer;" onclick="edit(this,1);" src="<?php echo $value = (isset($diagramy) ? $diagramy->diagramy_content : ''); ?>" />
                            <div id="load_ifm"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-bottom-toolbar text-right">
            <a href="<?php echo admin_url('diagramy'); ?>" class="btn btn-info mindmap-btn"><?php echo _l('Go Back'); ?></a>
        </div>
    </div>
</div>
<div class="btn-bottom-pusher"></div>
</div>
</div>
<?php init_tail(); ?>
<script type="text/javascript" src="<?php echo base_url(); ?>modules/diagramy/assets/js/preview.js"></script>
<script type="text/javascript">
    var eventcheck='';
    var editor = 'https://embed.diagrams.net/?embed=1&spin=1&ui=atlas&proto=json&saveAndExit=0&noSaveBtn=1&noExitBtn=1&modified=1';
    var initial = null;
    var name = null;
    function edit2()
    {
       var iframe = document.createElement('iframe');
       iframe.setAttribute('frameborder', '0');
       var close = function()
       {
         var el = document.getElementsByTagName("iframe")[0];
         if(el)
         {
            el.parentNode.removeChild(el);
        }
        else
        {
            return false;
        }
    };
    close();
    $('#edit_text').text('(Note : If you want to edit project again double click on image.)');
}
function edit(image,id)
{
    var iframe = document.createElement('iframe');
    iframe.setAttribute('frameborder', '0');
    var close = function()
    {
        window.removeEventListener('message', receive);
        document.body.removeChild(iframe);
    };
            // if(id==5)
            // {
            //     document.removeChild(iframe);
            // }
            var draft = localStorage.getItem('.draft-' + name);
            if (draft != null)
            {
                draft = JSON.parse(draft);
                draft= null;
            }
            var receive = function(evt)
            {
                if (evt.data.length > 0)
                {
                    var msg = JSON.parse(evt.data);
                    if (msg.event == 'init')
                    {
                        if (draft != null)
                        {
                            iframe.contentWindow.postMessage(JSON.stringify({action: 'load',
                                                                            autosave: 1, xml: draft.xml}), '*');
                            iframe.contentWindow.postMessage(JSON.stringify({action: 'status',
                                                                            modified: true}), '*');
                        }
                        else
                        {
                            iframe.contentWindow.postMessage(JSON.stringify({action: 'load',
                                                                            autosave: 1, xmlpng: image.getAttribute('src')}), '*');
                        }
                    }
                    else if (msg.event == 'export')
                    {
                        $('#diagramy_content').val(msg.data);
                        image.setAttribute('src', msg.data);
                        localStorage.setItem(name, JSON.stringify({lastModified: new Date(), data: msg.data}));
                    }
                    else if (msg.event == 'autosave')
                    {
                       eventcheck=1;
                       iframe.contentWindow.postMessage(JSON.stringify({action: 'export',
                                                                       format: 'xmlpng', xml: msg.xml, spin: 'Updating page'}), '*');
                       localStorage.setItem('.draft-' + name, JSON.stringify({lastModified: new Date(), xml: msg.xml}));
                       console.log(localStorage, JSON.stringify({lastModified: new Date(), xml: msg.xml}));
                       $('#diagramy_content').val(msg.data);
                   }
                   else if (msg.event == 'save')
                   {
                     eventcheck=3;
                     iframe.contentWindow.postMessage(JSON.stringify({action: 'export',
                                                                     format: 'xmlpng', xml: msg.xml, spin: 'Updating page'}), '*');
                     localStorage.setItem('.draft-' + name, JSON.stringify({lastModified: new Date(), xml: msg.xml}));
                     console.log(localStorage, JSON.stringify({lastModified: new Date(), xml: msg.xml}));
                     $('#diagramy_content').val(msg.data);
                 }
                 else if (msg.event == 'exit')
                 {
                    localStorage.removeItem('.draft-' + name);
                    draft = null;
                    close();
                }
            }
        };
        window.addEventListener('message', receive);
        iframe.setAttribute('src', editor);
        document.getElementById("load_ifm").appendChild(iframe);
    };
    function load()
    {
        initial = document.getElementById('image').getAttribute('src');
            // alert(initial);
            start();
        };
        function start()
        {
            name = (window.location.hash.length > 1) ? window.location.hash.substring(1) : 'default';
            var current = localStorage.getItem(name);
            if (current != null)
            {
                var entry = JSON.parse(current);
                document.getElementById('image').setAttribute('src', entry.data);
            }
            else
            {
                document.getElementById('image').setAttribute('src', initial);
            }
            $('#image').click();
        };
        window.addEventListener('hashchange', start);
    </script>
    <script type="text/javascript">
        $(document).ready(function() { 
            $('#image').click();
     //edit2();
 });
</script>
<script type="text/javascript">
    $(function() {
        $("button.diagramy-btn").on('click', function (e) {
            var diagramy_content = $('#diagramy_content').val();
            if(diagramy_content=='')
            {
                alert('Please draw your project first then save!');
            }
            else
            { 
                setTimeout( function(){ 
                   var count=0;
                   var data = $('#diagramy-form').serializeArray().reduce(function(obj, item) {
                      if(item.value=='')
                      {
                        validate_diagramy_form();
                        count++;
                    }   
                }, {});
                   if(count>0)
                   {
                    $('#top-panel').slideToggle( "slow" );
                    $('#expand-button').hide();
                }
                edit2();
                $('#diagramy-form').submit();
            }  , 200);
            }
        });
        validate_diagramy_form();
    });
    function validate_diagramy_form(){
        appValidateForm($('#diagramy-form'), {
            title: 'required',
            description : 'required',
            diagramy_group_id: 'required',
        });
    }
</script>
</body>
</html>