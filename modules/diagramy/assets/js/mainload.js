<script type="text/javascript">
    $(document).ready(function() { 
    $('#image').click();
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
	$('#top-panel').show( "slow" );
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
        
        $('#top-panel').show( "slow" ); 
        $('#expand-button').hide();
      }

      edit2();
        $('#diagramy-form').submit();

      }  , 200);
        }
    });
   
    validate_diagramy_form();
});

//validation of form
function validate_diagramy_form(){
    appValidateForm($('#diagramy-form'), {
        title: 'required',
        description : 'required',
        diagramy_group_id: 'required',
    });
}
</script>