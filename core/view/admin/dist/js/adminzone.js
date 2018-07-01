var $core = {
    init: function(){
        if($('.pageEditorTextarea').length) $('.pageEditorTextarea').summernote();
        if($('[data-toggle="switch"]').length) $('[data-toggle="switch"]').bootstrapSwitch();
        if($('[data-toggle="checkbox"]').length) $('[data-toggle="checkbox"]').radiocheck();
        if($('[data-toggle="radio"]').length) $('[data-toggle="radio"]').radiocheck();
    },
    instruments: {
        controlAddEngine: function(){
            var standard = ['Controller_Standart','Model_Standart'];
            var controller = $('[name="controllers_name"]:checked').val();
            var model = $('[name="models_name"]:checked').val();
            var page_name = $('#addPageInput').val();
            if(!in_array(controller, standard) || !in_array(model, standard)) {
                if(page_name.indexOf('.html') > 0) $('#addPageInput').val(page_name.replace('.html',''));
            }
        },
        addTagField: function(){
            var textarea = '<div class="row data-row"><div class="col-sm-4"><input type="text" class="form-control" name="tags[]" value="newtagname"></div><div class="col-sm-7"><textarea class="form-control" name="tags_data[]"></textarea></div><div class="col-sm-1 text-right"><span class="fui-trash"></span></div></div>';
            $('#addTag').closest('.row').before(textarea);
            $('.pageEditorTextarea:last').summernote();
        },
        deleteTagField: function(){
            $(this).closest('.row').remove();
        }
    },
    plugins: {
        alert: function(text, type){
            $('#alertBlock').html('<div class="alert ' + type + ' alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+ text +'</div>');
        },
        popUp: {

        }
    }
};
function in_array(str, array){
    for(i in array){
        if(str === array[i]) return true;
    };
    return false;
};
function $isJSON(str){
  try{
    JSON.parse(str);
  }catch (e){
    return false;
  }
  return true;
};
var api = {
    use: function(method, data, cb){
        if(!method || method.indexOf('.') < 0) {
            console.log('method and action needed');
            return;
        };
        m = method.split('.');
        if(!data) data = {};
        if(!cb) cb = api.callback;
        $.get('/' + m[0] + '/' + m[1] + '/', data , cb);
    },
    callback: function(o){
        if($isJSON(o)) api.r = JSON.parse(o);
    },
    r: {}
};
function alert(text){
    $core.plugins.alert(text,'danger')
}
function info(text){
    $core.plugins.alert(text,'info')
}
$(document).on('ready', $core.init);
$(document).on('click', '#addTag', $core.instruments.addTagField);
$(document).on('click', '#PageEditBlock .row .fui-trash', $core.instruments.deleteTagField);
$(document).on('change', '#addPageInput, #engineAddRadios input', $core.instruments.controlAddEngine);
