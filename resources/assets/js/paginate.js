var filter_url = '';
var filters = {
    totalItems: 0,
    itemPerPage: 5,
    currentPage: 1,
    totalPages: 1
};
var multipleFilter = [];
var div_id = '';

$(document).ready(function (){
    $('#recordPerPage').val(filters.itemPerPage);
    $('#status_search').trigger('focus');
    
    $(document).on('click','.page_no',function(){
        var cp = $(this).data('page');
        var table = $(this).data('table');
        
        filters.currentPage = cp;
        var furl = multipleFilter[table]['filter_url'];
//        console.log(table,furl);
//        console.log(multipleFilter);
//        filterData(filter_url);
        filterData(furl,table);
    });
    
    $(document).on('keydown','.ui-autocomplete-input',function(e){
        var k = e.which;
        
        if(k == 40 || k == 38){
            $('.ui-autocomplete .ui-menu-item').removeClass('custom-autocomplete-hover');
            $('.ui-autocomplete li a').each(function(){
                
                if($(this).hasClass('ui-state-focus')){
//                    console.log($(this));
                    $(this).parent('li').addClass('custom-autocomplete-hover');
                }
            });
//            console.log($('.ui-autocomplete li').find('a .ui-state-focus').html());
            
//            $('.ui-autocomplete li').find('a .ui-state-focus').parent('li').addClass('custom-autocomplete-hover');
        }
    });
});

function changeRecordPerPage(url,table){
    var id = '';
    if(typeof table !== 'undefined'){
        id= '-'+table;
    }
    var recPp = $('#recordPerPage'+id).val();
    if(recPp == '' || recPp <= 0){
        Toast(0,'Please select valid page limit');
        return false;
    }
    filters.currentPage = 1;
    filters.itemPerPage = recPp;
    
    if(typeof table === 'undefined'){
        table = 'table-data';
    }
    filterData(url,table);
}

function filterData(url,table){
    
    var token = $("#token").val();
    filters._token=token;
    if(typeof table === 'undefined'){
        table = 'table-data';
    }
//    console.log(filters);
//    console.log(multipleFilter[table]);
    var flush = 1;
    if(typeof multipleFilter[table] !== 'undefined' && typeof multipleFilter[table]['filters'] !== 'undefined'){
        flush = 0;
//        console.log(multipleFilter[table]['filters'],filters);
        $.each(multipleFilter[table]['filters'],function(k,v){
//            console.log(k,v);
            if(typeof filters[k] === 'undefined'){
                filters[k] = v;
            }
        });
//        console.log(multipleFilter[table]['filters'],filters);
    }else{
        multipleFilter[table] = {};
    }
    
    var jdata = filters;
    
    
    filter_url = url;
    
    $.ajax({
        type:'POST',
        url:url,
        // dataType: 'json',
        data:JSON.stringify(jdata),
        contentType: "application/json",
        async:false,
        success: function(res){
            if(res.flag === 0){
                console.log(res);
            }else{
                $("#"+table).html(res.blade);
                filters.totalItems = res['total_record'];
                filters.totalPages = filters.totalItems > 0 ? Math.ceil(filters.totalItems / filters.itemPerPage) : 0;
                setPagination(table);
            }
            
            multipleFilter[table]['filters'] = filters;
            multipleFilter[table]['filter_url'] = filter_url;
            flushFilters(flush);
            
        }, 
    });
    
    
}
function flushFilters(keep){
    
    if(keep){
        filters = {
            totalItems: 0,
            itemPerPage: filters.itemPerPage,
            currentPage: 1,
            totalPages: 1
        };
    }else{
        filters = {};
    }
}
function setPagination(table){
    var tp = filters.totalPages;
    var cp = filters.currentPage;
    
    var p = prevPage(cp,tp,0);
    var li = '';
    var fl = '<a class="btn-sm px-3 btn-secondary ml-1 page_no" data-page="'+1+'"  data-table="'+table+'" data-type="f" href="#"><i class="fa fa-angle-double-left"></i></a>';
    var ll = '<a href="#" data-page="'+tp+'" data-type="l" data-table="'+table+'" class="btn-sm px-3 btn-secondary ml-1 page_no"><i class="fa fa-angle-double-right"></i></a>';
    var pp = '<a href="#" data-page="'+p+'" data-type="p"  data-table="'+table+'" class="btn-sm px-3 btn-secondary ml-1 page_no"><i class="fa fa-angle-left"></i></a>';
    var p = prevPage(cp,tp,1);
    var np = '<a href="#" data-page="'+p+'" data-type="n"  data-table="'+table+'" class="btn-sm px-3 btn-secondary ml-1 page_no"><i class="fa fa-angle-right"></i></a>';
    var ns = '';
    var ps = '';
    var prev = cp - 7;
    var next = cp + 7;
    var pflag = 1;
    var nflag = 1;
    if(prev < 0){
        pflag = 0;
    }
    if(next > tp){
        pflag = 0;
    }
    if(tp < 7){
        for(i = 1;i<=tp;i++){
            li += '<a href="#" data-page="'+i+'" data-type="'+i+'"  data-table="'+table+'" class="btn-sm px-3 btn-secondary ml-1 page_no">'+i+'</a>';
        }
    }else{
        var nd = nextDigit(cp,tp,1);
        var dp = nextDigit(cp,tp,0);
        if(nd<tp){
          ns = '<a href="#" data-page="'+(nd+1)+'" data-type="'+(nd+1)+'"  data-table="'+table+'" class="btn-sm px-3 btn-secondary ml-1 page_no other-page"><i class="fa fa-ellipsis-h"></i></a>';
        }
        
        if(dp == nd){
            dp = (nd-7) > 0 ? (nd-7) : 1;
        }
//        console.log(dp + ' - ' + nd);
        if(dp>1){
            ps = '<a href="#" data-page="'+(dp-1)+'" data-type="'+(dp-1)+'"  data-table="'+table+'" class="btn-sm px-3 btn-secondary ml-1 page_no other-page"><i class="fa fa-ellipsis-h"></i></a>';
        }
        
        
        
        for(i=dp;i<=nd;i++){
            li += '<a href="#" data-page="'+i+'" data-type="'+i+'"  data-table="'+table+'" class="btn-sm px-3 btn-secondary ml-1 page_no">'+i+'</a>';
        }
    }
    
    li = fl+pp+ps+li+ns+np+ll;
    var cls1 = '';
    var cls2 = '';
    if($('.pagination').hasClass(table)){
        cls1 = '.'+table;
        cls2 = '.'+table;
    }
    
    $(cls1+'.pagination').html(li);

    $(cls1+' .page_no').each(function(){
        var tp = $(this).data('type');
        if(tp == cp){
            $(this).addClass('active');
        }
    });
    
    if(cp == 1){
        $(cls2+'.pagination a:first-child').removeClass('page_no').addClass('pagination-disable');
        $(cls2+'.pagination a:nth-child(2)').removeClass('page_no').addClass('pagination-disable');
    }
    if(cp == tp){
        $(cls2+'.pagination a:last-child').removeClass('page_no').addClass('pagination-disable');
        $(cls2+'.pagination a:nth-last-child(2)').removeClass('page_no').addClass('pagination-disable');
    }
    if(tp <= 1){
        $(cls1+'.pagination').html('');
    }
    
}
function prevPage(cp,tp,t){
    var p = 1;
    if(t){
        p = cp + 1 < tp ? cp + 1 : tp > 0 ? tp : 1;
    }else{
        p = cp - 1 > 0 ? cp - 1 : 1;
    }
    
    return p;
}

function nextDigit(cp,tp,t){
    if(t){
        for(i=cp;i<=tp;i++){
            if(i%7 == 0){
                return i;
            }
        }
        return tp;
    }else{
        for(i=cp;i>0;i--){
            if(i%7 == 0){
                return i;
            }
        }
        return 1;
    }
    
}

// Toast
var Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
});

function notify(flag,msg){
  if(flag==1){
  Toast.fire({
    icon: 'success',
    title: msg
  })
}else{
   Toast.fire({
    icon: 'error',
    title: msg
  })
}
}