function UpdateIndustries(){
    var link = 'http://197.248.156.29/mediaxapi/anvil/queries.php';
    var load = document.getElementById('industries');
    jQuery.ajax({
        url:link,
        data:{'getindustries':true},
        type:'GET',
        cache:false,
        success:function(data){
            var response = JSON.parse(data);
            // Package the html
            opt_options  = '<option>Select Industry</option>';
            for (i = 0; i < response.length; i++) {
                opt_options += "<option value='"+response[i]["id"]+"'>"+response[i]["name"]+"</option>";
            }
            load.innerHTML = opt_options;
        },
        error:function(){
            load.innerHTML = "There was an error processing the request";
        }
    });
}
// obtain list of sub industries
function GenerateSubIndustries(){
    var link = 'http://197.248.156.29/mediaxapi/anvil/queries.php';
    var industryid = $('#industries').val();
    var subindustry = document.getElementById('subindustry');
    var companyid = $('#companies').val();
    jQuery.ajax({
        url:link,
        data:{'getsubindustries':true,'subid':industryid},
        type:'GET',
        dataType: "json",
        success:function(data){
            $('select[name="subindustry"]').empty();
            $('select[name="subindustry"]').append('<option>Select Sub Industry</option>');
            $.each(data, function(key, value) {
                $('select[name="subindustry"]').append('<option value="'+ key +'">'+ value +'</option>');
            });
        }
    });
}