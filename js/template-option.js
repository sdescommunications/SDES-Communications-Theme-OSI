(function($){
$(document).ready(function() {

    var $page_template = $('#page_template'),
        $side = $('#custom_page_metabox'),
        $billboard = $('#billboard-meta-box'),
        $service = $('#service-meta-box'),
        $bt = $('#billTag'),
        $bu = $('#billUrl'),
        $btb = $('#billTagb'),
        $bub = $('#billUrlb'),
        $isfrontpage = data.isFrontPage,
        $pagid = data.postID;

    $page_template.change(function() {
        if ($(this).val() == 'content-right-sidecol.php' || $(this).val() == 'content-left-sidecol.php'){
            $side.show();
            $billboard.hide();
            $service.hide();
            

        }else if ($(this).val() == 'content-billboard-video.php' && $isfrontpage == $pagid){
            $billboard.show();
            $side.show();
            $bt.show();
            $btb.show();
            $bub.hide();
            $bu.hide();
            $service.hide();

        }else if($(this).val() == 'content-billboard.php' ){
        	$billboard.show();
			$side.show();
            $bt.show();
            $btb.show();
            $bub.hide();
            $bu.hide();
            $service.hide();

        }else if($(this).val() == 'content-billboard-full.php' ){
            $billboard.show();
            $side.hide();
            $bt.show();
            $btb.show();
            $bub.hide();
            $bu.hide();
            $service.hide();

        }else if($(this).val() == 'content-billboard-video.php' ){
            $billboard.show();
            $side.hide();
            $bt.hide();
            $btb.hide();
            $bub.show();
            $bu.show();
            $service.hide();
            
           
        }else if($(this).val() == 'content-services.php'){
            $service.show();
            $side.hide();
            $billboard.hide();
           
        }
        else {
            $side.hide();
            $billboard.hide();
            $service.hide();
            
        }
    }).change();

});
})(jQuery);