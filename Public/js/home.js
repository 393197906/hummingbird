/**
 * Created by NilTor on 2015/10/6.
 */
//载入内容
function loadContent(selector,url) {
    $.get(url,function(data){
        selector.html(data);
    },'html')
}

//提示内容，type为类型success,danger,info,warning等
// info为显示信息
function alertInfo(type,important,info) {

    var alerthtml;
    type='alert-'+type;
    $.get(MOD_PATH+'/blocks/alert.html',function(data){
        alerthtml=data;
        alerthtml=alerthtml.replace('ALERT_TYPE',type);
        alerthtml=alerthtml.replace('ALERT_IMPORTANT',important);
        alerthtml=alerthtml.replace('ALERT_INFO',info);
        console.log(alerthtml);
        $("body").append(alerthtml);
        setTimeout(function(){
            $(".alerthtml").slideUp(500);
        },2000);
    });

}
