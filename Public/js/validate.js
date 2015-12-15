/**
 * Created by geeth on 2015/11/15 0015.
 */


var validaterule = {}; //规则对象
var validatetype = {}; //分类对象
var emsg = new Array();

validaterule.email        = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
validaterule.phone        = /^0?1[3|4|5|8|7][0-9]\d{8}$/;
validaterule.password     = /^[A-Za-z0-9_\!\#\$\%\^\&\*\.\~]{6,32}$/;
validaterule.username     = /^[\u4e00-\u9fa5]{2,16}$|^[\dA-Za-z_]{3,32}$/;
validaterule.commonString = /^[0-9a-zA-Z\u4e00-\u9fa5\;\,\.\，\。]{1,32}$/;
validaterule.zhcn         = /^[\u0391-\uFFE5]+$/;
validaterule.qq           = /[1-9][0-9]{4,}/;
validaterule.idcard       = /\d{15}|\d{18}/;
validaterule.date         = /^(\d{4})-(\d{2})-(\d{2})$/;
validaterule.number       = /^[0-9]*$/;
validaterule.check        = /^check$/;
validaterule.captcha      = /^[0-9]{4,6}$/;
validaterule.intro        = /^.{50,300}$/;

validatetype.email        = new Array('email');
validatetype.phone        = new Array('phone');
validatetype.password     = new Array('password', 'repassword');
validatetype.username     = new Array('username', 'nickname', 'realname');
validatetype.commonString = new Array('school', 'advantage', 'profession', 'skill', 'wantproject', 'title', 'tag');
validatetype.zhcn         = new Array('');
validatetype.qq           = new Array('qq');
validatetype.idcard       = new Array('idcard');
validatetype.date         = new Array('date');
validatetype.number       = new Array('number');
validatetype.check        = new Array('check');
validatetype.captcha      = new Array('captcha');
validatetype.intro        = new Array('intro');


emsg['email']       = '请填写正确邮箱';
emsg['username']    = '请填写正确的用户名';
emsg['nickname']    = '请填写正确的昵称';
emsg['realname']    = '请填写正确的姓名';
emsg['password']    = '请填写正确的密码';
emsg['repassword']  = '请填写正确的密码';
emsg['phone']       = '请填写正确的手机号';
emsg['qq']          = '请填写正确的QQ号';
emsg['idcard']      = '请填写正确的身份证号';
emsg['school']      = '请填写毕业院校';
emsg['captcha']     = '无效的验证码';
emsg['advantage']   = '请正确填写定位优势';
emsg['skill']       = '请正确填写特长';
emsg['profession']  = '请正确填写专业能力';
emsg['wantproject'] = '请正确填写项目类型';
emsg['title']       = '请正确填写标题';
emsg['tag']         = '请正确标签';
emsg['intro']      = '介绍在50-300长度间';

function validate(formobj) {
    var data = formobj.serializeArray();
    console.log(data);
    var re   = true;
    for (var i in data) {
        var obj = data[i];
        for (var j in validatetype) {
            if (validatetype[j].indexOf(obj.name) > -1) {
                console.log("test " + obj.name + " value:" + obj.value);
                var select = "input[name='" + obj.name + "']";
                if ($(select).length == 0) {//如果是非input元素
                    select = "textarea[name='" + obj.name + "']";
                }
                if (validaterule[j].test(obj.value)) {
                    $(select).tooltip('destroy');
                    //console.log(j + " " + obj.value);
                } else {
                    $(select).attr("data-toggle", "tooltip");
                    $(select).tooltip({
                                          title:     emsg[obj.name],
                                          placement: 'right',
                                          trigger:   'focus',
                                          container: 'body',
                                          viewport:  'form'
                                      });
                    $(select).tooltip('show');
                    re = false;
                }
            } else {
                //console.log(j+" no: " + obj.name);
            }
        }
    }
    return re;
}