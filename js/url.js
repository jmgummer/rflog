$(function(){
    var url = window.location.pathname, 
    urlRegExp = new RegExp(url.replace(/\/$/,'') + "$");
    $('.nav a').each(function(){
        if(urlRegExp.test(this.href.replace(/\/$/,''))){
            $(this).parent('li').addClass('active');
        }
    });
    $('.nav-second-level a').each(function(){
        if(urlRegExp.test(this.href.replace(/\/$/,''))){
            $(this).parent('li').parent('ul').addClass('in');
        }
    });
});