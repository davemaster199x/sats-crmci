// shift multiple checkbox select
function select_multiple_checkbox_via_shift_key(trr_chbox){
    
    var lastChecked = null;
    trr_chbox.click(function(e) {

        if (!lastChecked) {
            lastChecked = this;
            return;
        }

        if (e.shiftKey) {

            var start = trr_chbox.index(this);
            var end = trr_chbox.index(lastChecked);

            trr_chbox.slice(Math.min(start,end), Math.max(start,end)+ 1).prop('checked', lastChecked.checked);
        }

        lastChecked = this;
		
    });

}