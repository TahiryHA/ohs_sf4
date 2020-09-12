$(document).ready(function(){


})



$(document).on('change','#user_academic_year',function(){
    let $field = $(this)
    let $form = $field.closest('form')
    let data = {}
    data[$field.attr('name')] = $field.val()

    var getUrl = Routing.generate("ay.change");

    $.post(getUrl,data).then(function(data){

        $('#user_level').replaceWith(data)
        
    })
});
