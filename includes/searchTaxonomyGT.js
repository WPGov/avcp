jQuery(document).ready(function($){

	if($('#taxonomy-ditte').length>0){	
	
	$('#annirif-tabs').prepend('File XML in cui la gara verrà stampata (autocompilato al salvataggio)<br><br>');
	$('#areesettori-adder').hide();
	//$("#ditte-adder").hide();
	$('#ditte-add').prepend('<small>Il codice fiscale bisogna inserirlo dal menù "Ditte"</small><br><br>Nome/Ragione sociale:<br>');
	$("#newditte_parent").hide();
		$('#taxonomy-ditte>.tabs-panel').eq(1).prepend('<span class="searchTaxonomyGTdiv"><label for="#searchTaxonomyGT">Cerca: </label><input type="text" value="" id="searchTaxonomyGT" style="border-radius: 10px;font-size: 11px;margin: 5px;"/><br/></span>');
		//<input type="checkbox" checked id="searchTaxonomyGT_keephierarchy"/><label for="#searchTaxonomyGT_keephierarchy">Keep Hierarchy</label></span>');
		
		
		$('#searchTaxonomyGT').keyup(function(e){
			$('.searchTaxonomyGTul li label').removeClass("visibleGT");
			if($(this).val().length>1){
			var filter = $(this).val(), count = 0;
			
			// Loop through the comment list
			$('.searchTaxonomyGTdiv').parent().children(".categorychecklist").addClass("searchTaxonomyGTul");
			
			$(".searchTaxonomyGTul li label").each(function(){
	 
				// If the list item does not contain the text phrase fade it out
				if ($(this).text().search(new RegExp(filter, "i")) > 0) {
					$(this).addClass("visibleGT");
					if($('#searchTaxonomyGT_keephierarchy').is(":checked")){
						//console.log($(this).parentsUntil('.categorychecklist','li').find("label").html());
						var lastparent = $(this).parentsUntil('.searchTaxonomyGTul','li');
						//parents
						lastparent.children("label").addClass("visibleGT");
						
						//children
						var childrenobjs = $(this).parent().find('label');
						$(childrenobjs).each(function (index) {
							$(this).addClass("visibleGT");
						});
					}
					count++;
					
				}else{
					$(this).hide();
				}
			});
			
			}
			if($('.searchTaxonomyGTul li .visibleGT').length>0){
				$('.searchTaxonomyGTul li .visibleGT').show();
				$('.searchTaxonomyGTul li label').not(".visibleGT").hide();
			}else if($('#searchTaxonomyGT').val()==""){
				$('.searchTaxonomyGTul li label').show();
			}
		});
	}
});