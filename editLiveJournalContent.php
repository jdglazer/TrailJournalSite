<?php
use Action\SaveJournalAction;
use DataAccess\DataAccessObjects\TrailQuery;
        
$_POST_LAST = null;
$id = null;
$trailId = null;
$direction = null;
$startPoint = null;
$endPoint = null;
$journal = null;
$date = null;
$lastPostError = null;

if (isset($_SESSION[POST_VARIABLES_SESSION_KEY_NAME])) {
    $_POST_LAST = $_SESSION[POST_VARIABLES_SESSION_KEY_NAME];
    
    if(isset($_POST_LAST[SaveJournalAction::POST_ID])) {
        $id = $_POST_LAST[SaveJournalAction::POST_ID];
    }
    
    if(isset($_POST_LAST[SaveJournalAction::POST_TRAIL_ID])) {
        $trailId = $_POST_LAST[SaveJournalAction::POST_TRAIL_ID];
    }
    
    if(isset($_POST_LAST[SaveJournalAction::POST_DATE])) {
        $date = $_POST_LAST[SaveJournalAction::POST_DATE];
    }
    
    if(isset($_POST_LAST[SaveJournalAction::POST_START_POINT])) {
        $startPoint = $_POST_LAST[SaveJournalAction::POST_START_POINT];
    }
    
    if(isset($_POST_LAST[SaveJournalAction::POST_END_POINT])) {
        $endPoint = $_POST_LAST[SaveJournalAction::POST_END_POINT];
    }
    
    if(isset($_POST_LAST[SaveJournalAction::POST_JOURNAL])) {
        $journal = $_POST_LAST[SaveJournalAction::POST_JOURNAL];
    }
    
    if(isset($_POST_LAST[SaveJournalAction::POST_DIRECTION])) {
        $direction = $_POST_LAST[SaveJournalAction::POST_DIRECTION];
    }
    
    if(isset($_SESSION[POST_ERROR_MESSAGE_SESSION_KEY_NAME])) {
        $lastPostError = $_SESSION[POST_ERROR_MESSAGE_SESSION_KEY_NAME];
        unset($_SESSION[POST_ERROR_MESSAGE_SESSION_KEY_NAME]);
    }
}

?>
<form id="save-journal-form" method="POST">
    <select id="trail-id-selector" name="<?php echo SaveJournalAction::POST_TRAIL_ID ?>">
        <?php
        $trails = TrailQuery::create()->find();
        foreach ($trails as $trail) {
            $selectedStr = ($trailId == $trail->getId()) ? "' selected>" : "'>";
            
            echo "<option value='".$trail->getId().$selectedStr.$trail->getName()."</option>";
        }
        ?>
    </select><br/>
    <select id="direction-selector" name="<?php echo SaveJournalAction::POST_DIRECTION ?>">
<!-- TO DO: add option for the selected value if it was set in last post variables -->
    </select><br/>
    <input name="<?php echo SaveJournalAction::POST_DATE ?>" type="date" value="<?php if(!is_null($date)) echo $date; ?>"/><br/>
    <input id="start-point" class="start-end-points" name="<?php echo SaveJournalAction::POST_START_POINT ?>" type="number" step="0.01" value="<?php if(!is_null($startPoint)) echo $startPoint; ?>""/><br/>
    <input id="end-point" class="start-end-points" name="<?php echo SaveJournalAction::POST_END_POINT ?>" type="number" step="0.01" value="<?php if(!is_null($endPoint)) echo $endPoint; ?>""/><br/>
    <textarea name="<?php echo SaveJournalAction::POST_JOURNAL ?>"><?php if(!is_null($journal)) echo $journal; ?></textarea><br/>
    <input type="submit" onclick="saveJournal();" value="Save"/>
    <input type="submit" onclick="saveDraftJournal();" value="Save As Draft"/>
    <?php if (!is_null($id)): ?>
    <input type="hidden" name="<?php echo SaveJournalAction::POST_ID ?>" value="<?php echo $id ?>"/>
    <?php endif; ?>
    <input type="hidden" name="<?php echo REDIRECT_URL_POST_KEY_NAME ?>" value="editLiveJournal.php"/>
</form>
<?php if (!is_null($lastPostError)): ?>
<span style="color:red"><?php echo $lastPostError ?></span>;
<?php elseif(!is_null($_POST_LAST)): ?>
<span style="color:green">Successfully saved journal changes!</span>
<?php endif;?>
<script type="text/javascript" src="scripts/jquery-3.5.1.min.js"></script>
<script>
function saveJournal() {
    $("#save-journal-form").attr('action', 'saveJournal.php');
    // Can do input validation here
    return true;
}

function saveDraftJournal() {
    $("#save-journal-form").attr('action', 'saveDraftJournal.php');
    // can do input validation here
    return true;
}

function loadTrailDirectionOptions() {
    $_post = {<?php echo SaveJournalAction::POST_TRAIL_ID ?>: $("#trail-id-selector").val()};
    $.post("getTrailMetadata.php",  $_post, function(data) {
        var trailData = JSON.parse(data);
        var directions;
        if (trailData.trails.length > 0 ) {
            if (trailData.trails[0].direction === "LON") {
                directions = ["North", "South"];
            } else {
                directions = ["East", "West"];
            }

            var direction_options_html = "";
            for(var i in directions) {
                var direction = directions[i];
                direction_options_html += "<option value='"+direction.charAt(0)+"'>"+direction+"</option>";
            }

            $("#direction-selector").html(direction_options_html);
        }
    });
}

$(loadTrailDirectionOptions);
$("#trail-id-selector").change(loadTrailDirectionOptions);

</script>
<?php unset($_SESSION[POST_VARIABLES_SESSION_KEY_NAME]); ?>