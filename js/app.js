$(document).ready(function() {
    "use strict";
    $('#selectallregions').click(function (event) {
        if (this.checked) {
            $('.regionlist').each(function () { //loop through each checkbox
                $(this).prop('checked', true); //check 
            });
        } else {
            $('.regionlist').each(function () { //loop through each checkbox
                $(this).prop('checked', false); //uncheck              
            });
        }
    });
    $('#selectalllsm').click(function (event) {
        if (this.checked) {
            $('.lsmlist').each(function () { //loop through each checkbox
                $(this).prop('checked', true); //check 
            });
        } else {
            $('.lsmlist').each(function () { //loop through each checkbox
                $(this).prop('checked', false); //uncheck              
            });
        }
    });
    $('#selectallplatforms').click(function (event) {
        if (this.checked) {
            $('.medialist').each(function () { //loop through each checkbox
                $(this).prop('checked', true); //check 
            });
        } else {
            $('.medialist').each(function () { //loop through each checkbox
                $(this).prop('checked', false); //uncheck              
            });
        }
    });
});

function PostCampaignDemo(){
    // api source
    var link        = 'http://197.248.31.170/grp_runner/postcampaign/getdata';
    // required variables
    var plantitle   = $('#plantitle').val();
    var agelow      = $('#agelow').val();
    var agehigh     = $('#agehigh').val();
    var gender      = $("#gender").val();
    // var plantype    = $("#plantype").val();
    var rural_urban = $("#rural_urban").val();
    var regions = [];
    $(".regionlist:checked").each(function() { regions.push($(this).val()); });
    var lsmrange = [];
    $(".lsmlist:checked").each(function() { lsmrange.push($(this).val()); });
    // var mediaforms = [];
    var mediaforms = $("#mediaforms").val();
    // $(".medialist:checked").each(function() { mediaforms.push($(this).val()); });
    var ransessionnumber = $('#ransessionnumber').val();
    var load        = document.getElementById('qresults');
    // check if all required fields have data
    if (plantitle === '' || regions === undefined || regions.length == 0 || lsmrange === undefined || lsmrange.length == 0 ) {
        alert('Please make sure all entries are selected');
    }else{
        load.innerHTML  = "Loading data ...";
        jQuery.ajax({
            url:link,
            data:{'plantitle':plantitle,'gender':gender,'agelow':agelow,'agehigh':agehigh,'regions':regions,'rural_urban':rural_urban,
            'lsmrange':lsmrange,'mediaforms':mediaforms,'ransessionnumber':ransessionnumber,'getresults':true},
            type:'POST',
            cache:false,
            success:function(cdata){
                campaignid = cdata;
                load.innerHTML = "Received";
                var p_url = 'http://197.248.31.170/grp_runner/postcampaign/run/'+campaignid;
                var form = $('<form action="' + p_url + '" method="GET"></form>');
                $('body').append(form);
                form.submit();
            },
            error:function(){
                load.innerHTML = "There was an error processing the request, please try again";
            }
        });
    }
}

(function () {
    var textInput = document.getElementById('companysearch');
    var el = document.getElementById('c_demo');
    var timeout = null;

    textInput.oninput = function (e) {
        clearTimeout(timeout);
        timeout = setTimeout(function () { 
            // el.innerHTML = textInput.value;
            SearchCompany(); 
        }, 800);
    };
})();

function SearchCompany() {
    // api source
    var link        = 'http://197.248.31.170/rflogs/home/searchcompanies';
    var search_text = document.getElementById("companysearch").value;
    jQuery.ajax({
        url:link,
        data:{'search_text':search_text,'getresults':true},
        type:'POST',
        cache:false,
        success:function(cdata){
            document.getElementById("company_id").innerHTML = cdata;
        },
        error:function(){
            document.getElementById("company_id").innerHTML = "There was an error processing the request, please try again";
        }
    });
}
function GetClientBrands(){
    var link        = 'http://197.248.31.170/rflogs/home/searchcompanies';
    var company_id = $("#company_id").val();
    var adyear = $("#adyear").val();
    var admonth = $("#admonth").val();
    jQuery.ajax({
        url:link,
        data:{'company_id':company_id,'getactivebrands':true},
        type:'POST',
        cache:false,
        success:function(cdata){
            document.getElementById("brand_id").innerHTML = cdata;
        },
        error:function(){
            document.getElementById("brand_id").innerHTML = "There was an error processing the request, please try again";
        }
    });
}
function GetBrandSegmentRuns(){
    var link        = 'http://197.248.31.170/rflogs/home/searchcompanies';
    var brand_id = $("#brand_id").val();
    // var regions = [];
    // $(".regionlist:checked").each(function() { regions.push($(this).val()); });
    var adyear = $("#adyear").val();
    var admonth = $("#admonth").val();
    var load        = document.getElementById('qresults');
    load.innerHTML  = "Loading data ...";
    jQuery.ajax({
        url:link,
        data:{'brand_id':brand_id,'adyear':adyear,'admonth':admonth,'getbrandsegments':true},
        type:'POST',
        cache:false,
        success:function(cdata){
            load.innerHTML = cdata;
        },
        error:function(){
            load.innerHTML = "There was an error processing the request, please try again";
        }
    });
}