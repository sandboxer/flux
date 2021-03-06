<?php
/*
 * This is the user's home page, showing a list of his fluxes
 */
$head='';
$body='';

/*
 * 1) List of fluxes =====================================================================
 * All the subsequent code is used to generate and show the list of the fluxes owned by the user.
 * We create a container in html and then populate the list through javascript, making a request to the API
 */

ob_start(); //i call ob_start, so that instead of outputting everything directly, we can buffer the output and pass it to the create_page function
?>
<div class="well">
    <h2>My Fluxes:</h2>
    <table id="myFluxes" style="background-color: white">
        <tbody>
            <tr><td>Loading...</td></tr>
        </tbody>
    </table>
    <div class="btn success" onclick="$FW.popupUrl('include/popups/create_flux.php')">+ Create a new flux</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        /*we retrieve the list of the fluxes owned by the user to populate his page.*/
        flux_api_call("get_fluxes_owned_by.php?user_id="+_session["uid"],
            function (json) {
                $('#myFluxes tbody').html('');
                for (var i=0; i<json.length; i++) {
                    $Flux.addFluxToList(json[i]);
                }
            }
        );
    });
    
    //we put all our functions in an object $Flux, to avoid polluting the global namespace
    $Flux = {
        addFluxToList: function (flux) {
            var icon;
            if (flux['userflux']==='2') {icon = 'userIcon';}
            else if (flux['userflux']==='0') {icon = 'fluxIcon';}
            var newRow = $('<tr><td><div class="'+icon+'" /><a rel="twipsy" href="flux.php?id='+flux['flux_id']+'">'
                +flux['name']
                +'</a></td></tr>').appendTo('#myFluxes tbody');
            //Tooltip for your default personal user flux:
//            if (flux['userflux']==='2') {
//                newRow.find('a').twipsy({
//                    title: function() {return "<big><b>Your personal flux</b></big><br/>By default 100% of donations go to your account. If you want you can redirect them somewhere else";},
//                    content: function() {return 'content';},
//                    placement: 'right',
//                    html: true,
//                    offset:20
//                });
//            }
        }
    }
    
</script>
<?php
$body .= ob_get_clean();
/*
 * END 1) list of fluxes ===============================================================
 */

//START TOOLTIPS/POPOVERS
ob_start(); ?>
<script type="text/javascript" src="css/bootstrap/js/bootstrap-twipsy.js"></script>
<script type="text/javascript" src="css/bootstrap/js/bootstrap-popover.js"></script>
<?php
$head .= ob_get_clean();
//END TOOLTIPS/POPOVERS

require_once(dirname(__FILE__).'/scripts/page_creator.php');
$html = create_page($body,$head,'account_home',true);
echo $html;
?>