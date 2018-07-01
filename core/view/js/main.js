function randInt(min,max){
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function matrix(){
  $.each($('#leftslider .column'), function(){
    var r = Math.random();
    var s = ( parseInt($(this).css('width')) / 100 ) * randInt(30,80);
    var l = ( parseInt($(this).css('width')) - s ) / 2;
    var t = randInt(5000,10000);
    $(this).append('<div removeid="'+r+'" class="matrixCode" style="width:'+s+'px; height:'+s+'px; left:'+l+'px;font-size:'+s/2+'px">&#0'+randInt(410,490)+';</div>');
    $('#leftslider').css({'opacity':'0.6'}).find("[removeid='"+r+"']").animate({'top':'100%'}, t);
    function m(){$('#leftslider').find("[removeid='"+r+"']").remove()};
        setTimeout(m,t);
    });
}

$(document).ready(function(){
    $('#leftslider').append('<div class="column"></div><div class="column"></div><div class="column"></div><div class="column"></div><div class="column"></div><div class="column"></div><div class="column"></div><div class="column"></div><div class="column"></div><div class="column"></div>');
    setInterval(matrix,2000);
    /*if($('#ajaxLoader').length){
        $('#ajaxLoader').find('.fullnews > a').mouseenter(function(){
            $('body').append('<div id="ajaxTooltip"></div>');
            $.get($(this).attr('href'),{},function(o){
                $('#ajaxTooltip').html($(o).find('#dle-content > div > .stryplace').html());
            });
            $(this).mousemove(function (pos) {
                $('#ajaxTooltip').css({'left':(pos.pageX)+20+'px','top':(pos.pageY)-parseInt($(this).css('height'))-40+'px','box-shadow': '0 0 10px 5px #ebebeb'});
            });
        }).mouseleave(function(){
            $('#ajaxTooltip').remove();
        }).click(function(){
            return false;
        });
    };
    if($('.getYoutube').length){
        $('.getYoutube').html('<iframe width="420" height="315" src="'+$('.getYoutube').text()+'" frameborder="0" allowfullscreen></iframe>');
    };*/
});
$(window).on('load',function(){
    $('.bodydiv').height($(window).height());
});
