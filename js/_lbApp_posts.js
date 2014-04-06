lbApp.Posts =
{
  isUpdating: 'no', // used to prevent multiple simultaneous loading of new posts
  config:
  {
    viewtype: 'cards', //default
    $wrapper: $('#posts'), // Wraps all the blog posts
    superContainer: '.card-container', // The class that wraps each post 
    container: '.card'
  },
  init: function(config)
  {
    if (config && typeof(config) === 'object')
    {
      console.log('ok');
      // Depending on kind, $wrapper and container will be initialized, otherwise use defaults
      $.extend(lbApp.Posts.config, config);
    }
  },
  distanceToBottom: function()
  {
    thePosts = this.config.$wrapper;
    return (thePosts.height() + thePosts.position().top);
  },
  flow: function()
  {
    thePosts = this.config.$wrapper;
    if (this.config.viewtype === 'cards') // <- Consider changing this to detect card elements
      {
        thePosts.css("width",lbApp.interfaceElements.viewArea.windowWidth());
        thePosts.css("margin","0 auto");
        var cols = lbApp.interfaceElements.viewArea.numberOfColumns();
        
        /* Maximize the data size of dividers so that nothing wraps around them*/
        $('.divider').each(function(){
          $(this).css({'width':(cols*320)+'px'});
          $(this).attr({'data-size':cols});
        });

        /* Now organize the cards */
        thePosts.BlocksIt({
          numOfCol: cols,
          offsetX: 10,
          offsetY: 10,
          blockElement: this.config.superContainer
        });
      }
  },
  show: function()
  {
    // This function reveals the hidden posts
    $(this.config.container).css('opacity',1);
    $(this.config.superContainer).css('opacity',1);
  },
  hide: function()
  {
    // This function hides the posts
    $(this.container).fadeTo('slow',0);
  },
  addNew: function()
  {
    var url;
    lbApp.Posts.isUpdating = 'yes';
    if (global_page === "favorites")
    {
      url = "contr_get_favorites_extra_posts.php";
    } else if (global_page === "saved")
    {
      url = "contr_get_saved_extra_posts.php";
    } else if (global_page === "browse")
    {
      url = "contr_get_extra_posts.php";
    } else if (global_page === "blogger"){
      // Do Nothing. This page doesn't inifini-scroll
      return;
    }

    $.post(url, function(data)
    {
      lbApp.Posts.config.$wrapper.append(data);
      lbApp.reArrange();
      lbApp.Posts.isUpdating = 'no';
      console.log('updating done');
    });
  }
};

// Scroll event. Using smartscroll (debounced) event from utilities to throttle

$('#view-area').smartscroll(function(){
  var d = lbApp.Posts.distanceToBottom(); // How close we are to the bottom post
  var c = $(this).height(); // height of the viewport
  var e = d - c ;// difference between the two.
  if ((e < c*1.4) && (lbApp.Posts.isUpdating == 'no') && global_page != 'search') {
    console.log('updating now');
    lbApp.Posts.addNew();
  }
});

// Here we listen to custom reArrangePosts event, triggered from interface resizing or sidebar toggling, to flow posts on demand
$(document).on('reArrangePosts', function(){
  lbApp.Posts.flow();
  lbApp.Posts.show();
  $("img.lazy").lazyload({
     container: $("#view-area")
  });
});