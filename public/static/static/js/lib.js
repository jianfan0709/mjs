$(document).ready(function($) {
    
    // 选项卡 鼠标点击
    $(".TAB_CLICK li").click(function(){
      var tab=$(this).parent(".TAB_CLICK");
      var con=tab.attr("id");
      var on=tab.find("li").index(this);
      $(this).addClass('on').siblings(tab.find("li")).removeClass('on');
      $(con).eq(on).show().siblings(con).hide();
    });
    $('.TAB_CLICK').each(function(index, el) {
        $(this).find('li').filter(':first').trigger('click');
    });
})