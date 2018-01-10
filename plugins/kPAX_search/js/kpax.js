/**
 * Kpax JS functions to improve UI
 *
 * TODO: Create separate functions for easy maintenance
 */
$(document).ready(function() {
	//Context links
	$('.contextual-links').find('.elgg-icon-settings-alt ').siblings().addClass('hidden');
    $('.contextual-links').find('.elgg-icon-settings-alt ').click(function(){
        $(this).siblings().toggleClass('hidden');
    });

    //Search top bar inputs
	var str = '';
	str = 'menu-item-' + $('#search_input_filters option:selected').attr('value');
    $('li.' + str).removeClass('hidden').siblings('li').addClass('hidden');
	$('#search_input_filters').change(function(){
		str = 'menu-item-' + $('#search_input_filters option:selected').attr('value');
        $('li.' + str).removeClass('hidden').siblings('li').addClass('hidden');
	});
});