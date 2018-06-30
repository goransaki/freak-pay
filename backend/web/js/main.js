/// <reference path="SearchToggle.ts"/>
$("document").ready(function () {
    new SearchToggle('.show-search', '.hide-search', '.search');
    $('.field-notificationform-user').hide();
    $('.s2-select-label').hide();
   
});
//# sourceMappingURL=main.js.map

function changeOption(){
        var option=$('#notificationform-sending_options').val();
        if(option==='1'){
            $('.field-notificationform-user').show();
        }else{
            $('.field-notificationform-user').hide();
        }

}