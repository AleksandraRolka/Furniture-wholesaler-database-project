


function checkInputs() 
{
	var isValid = true;
  
	$('input').filter('[required]').each(function() {
		if ($(this).val() === '') 
		{
			$('#submitAddEmployee').prop('disabled', true)
			isValid = false;
			
			return false;
		}
	});
  
	if(isValid)
	{
		$('#submitAddEmployee').prop('disabled', false)
		}
  
  return isValid;
}


$('input').filter('[required]').on('keyup',function() 
	{
		checkInputs()
	}
);

checkInputs();

var positionSelect  = document.getElementById("empPosition");
var warehouseSelect = document.getElementById("warehouseNo");
positionSelect.addEventListener("change", function() {
    if(positionSelect.value == "kierownik")
    {
        warehouseSelect.setAttribute("disabled", "disabled");
		warehouseSelect.value = null;
    }
	else{
		warehouseSelect.removeAttribute("disabled");
	}
});