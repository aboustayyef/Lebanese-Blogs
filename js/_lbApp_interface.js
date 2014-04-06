lbApp.interfaceElements =
{
  init: function()    // initialize all menu items below
  {
    this.viewArea.adjust(); // Adjust the height of the viewing window to fit browser
    this.sidebar.init(); // Adjust sidebar's height and whether it should be visible at first load
    this.header.menus.init(); // initialize menu
  },
  header :
  {
    $header: $('.mainbar-wrapper'),
    menus : // for handling the Navigation menu
    {
      init: function()
      {
        $('#menu-icons > li').on('click',function(){
          if ($(this).hasClass('selected'))
          { // If clicked on open menu, just close it
            $('.menu').hide(); // hide all open windows
            $(this).removeClass('selected');
          }else{ // clicked on a closed menu
            $('.menu').hide(); // hide all open windows
            $('#menu-icons > li').removeClass("selected"); // deselect all
            $(this).addClass("selected"); // select the one we clicked on
            var whichWindow = $(this).data('menu'); // choose menu to open
            $('#'+whichWindow).show(); // open it
            if (whichWindow == 'menu-search') // add focus() to the search
            {
              $('#search-box').focus();
            }
          }
        });
      }

    },
  },
  sidebar :
  {
    // sidebar changes height everytime window is resized.
    $sideBar : $('div#left-col-wrapper'),
    adjust: function()
    {
      this.$sideBar.css('height', $(window).outerHeight());
    },
    init: function()
    {
      
      this.adjust(); // Adjust height
      //by default, show left sidebar for larger windows & hide it for small screens
      if (($(window).width()<768) || (global_leftColumnInitialState === "closed") )
      {
        this.hide();
      }else{
        this.show();
      }
    },
    hide: function(){
      this.$sideBar.hide();
      $('#hamburger').show();
    },
    show: function(){
      this.$sideBar.show();
      $('#hamburger').hide();
    },
    visible: function(){
      if (this.$sideBar.css("display") === "block") {
        return true;
      }else {
        return false;
      }
    },
  },
  viewArea :
  {
    // viewArea changes height everytime window is resized.
    $viewArea : $('#view-area'),
    cardWidth : 320, //278px + 2 (border 1 px) + 20 (margin 10px);
    adjust: function()
    {
      this.$viewArea.css('height', $(window).outerHeight() - lbApp.interfaceElements.header.$header.outerHeight());
    },
    numberOfColumns: function()
    {
      return Math.floor(this.$viewArea.outerWidth()/this.cardWidth);
    },
    windowWidth : function(){
      return this.numberOfColumns()* this.cardWidth;
    },
    revealContent: function()
    {
      $('.loader').fadeTo('fast',0, function(){
        $('#view-area').fadeTo('fast',1);
      });
    },
  },
  top_switcher : {
    init: function(){
      $(document).on('click','#timeSelector', function(){
        if ($('#timeList').css('display')=='none') {
          $('#timeList').css('display','block');
        }
      });
      $(document).on('click','#timeList li', function(){
        var hours = $(this).data('hours');
        if ($('#timeList').css('display')=='block') {
          $('#timeList').css('display','none');
        }
        $('#timeSelector').html('<i class ="fa fa-refresh fa-spin">&nbsp;</i>');
        lbApp.interfaceElements.top_switcher.switch_top(hours, 5,'');
      });
    },
    switch_top: function(hours,howmany,channel){

      // this function renders the top5 box with new settings

      $.post( "api_get_posts.php?type=top&hours="+hours+"&howmany="+howmany+"&channel="+channel, function( data ) {
        var box = $('#posts').find('.card-container').eq(0);
        
        // get current style to assign it to swapped box
        var style = box.attr('style');

        // replace top5 box with new one with new data
        box.css('opacity',0);
        box.replaceWith(data);
        
        // assign old style to new box
        box = $('#posts').find('.card-container').eq(0);
        box.attr('style',style);
        box.css('opacity',1);
        
        // just in case, flow cards for little distances
        lbApp.reArrange();
      });
    },
  },
};

/* Interface Events */

//leftbar event handlers
$('#hamburger').on('click', function(e){
  lbApp.interfaceElements.sidebar.show();
  lbApp.reArrange();
});

$('.leftNav-dismiss').on('click', function(e){
  lbApp.interfaceElements.sidebar.hide();
  lbApp.reArrange();
});

$(window).smartresize(function(){
  lbApp.reArrange();
});

$(document).ready(function(){
  $('#view-area').css('-webkit-overflow-scrolling', 'touch');
  lbApp.interfaceElements.top_switcher.init();
});