var lbApp ={

  init: function(){ },

  posts:{
    //choose the basic building block for posts, depending on viewtype
    window : $('#posts'),
    busy: 'no',
    init: function(){
      // find the basic module of each post depending on viewtype
      if (global_viewtype === "cards") {
        this.module = $('.card-container');
      }else if (global_viewtype === "timeline"){
        this.module = $('.timeline');
      } else {
        this.module = $('.compact');
      }
    },

    //show posts
    show: function(){
      this.init(); // need to initialize every time to accomodate DOM elements added through javascript
      this.module.fadeTo('slow',1);
    },

    // hide posts
    hide: function(){
      this.init(); // need to initialize every time to accomodate DOM elements added through javascript
      this.module.fadeTo('slow',0);
    },

    // js-style polymorphism
    // flow is the method that does the heavylifting for laying out the posts
    // depending on the view type.
    flow: function(){
      if (global_viewtype === "cards") {
        lbApp.interface.cards.flow();
      }else if (global_viewtype === "timeline"){
        // series of timeline logic to reflow cards
        // nothing special yet
      }else{
        // compact logic to reflow cards
        // nothing special
      }
    },

    /* Method to get more posts for infinite scrolling */
    addMore: function(){
      lbApp.posts.busy = "yes"; //prevent concurrent loading of this function
      var url;
      if (global_page === "favorites") {
        url = "contr_get_favorites_extra_posts.php";
      } else if (global_page === "saved"){
        url = "contr_get_saved_extra_posts.php";
      } else {
        url = "contr_get_extra_posts.php";
      }
      $.post(url, function(data) {
        lbApp.posts.window.append(data);
        lbApp.posts.flow();
        lbApp.posts.show();
        //lbApp.posts.images.show();
        lbApp.interface.showLazyImages();
        lbApp.posts.busy = "no";
      });
    },

    /* Method to create the time selection list for top posts*/
    top_switcher_init: function(){
      // create switching list and lay it out
      $('#view-area').prepend('<ul id ="timeList" style ="position:fixed; z-index: 100; display:none"><li data-hours="12">12 Hours</li><li data-hours="24">24 Hours</li><li data-hours="72">3 Days</li><li data-hours="168">7 days</li></ul>');
      $('#timeList').css('left', $('#timeSelector').offset().left);
      $('#timeList').css('top', $('#timeSelector').offset().top);
      
      $(document).on('click','#timeSelector', function(){
        if ($('#timeList').css('display')=='none') {
          $('#timeList').css('display','block');
        }
      });
      $('#timeList').on('click','li', function(){
        var hours = $(this).data('hours');
        if ($('#timeList').css('display')=='block') {
          $('#timeList').css('display','none');
        }
        $('#timeSelector').html('<i class ="fa fa-spinner fa-spin">&nbsp;</i>'+$('#timeSelector').html());
        lbApp.posts.switch_top(hours, 5,'');
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
        lbApp.posts.flow();
      });
    },

    top_switcher_reposition: function(){
      if ($("#timeSelector").length > 0) {
        /*Only reposition it if it exists*/
          $('#timeList').css('left', $('#timeSelector').offset().left);
          $('#timeList').css('top', $('#timeSelector').offset().top);
      }
    },

    saved:{
      init:function(){
        var addToSavedHTML = '<a class="addToSaved" href="#"><i class="fa fa-clock-o"></i> Read Later</a>';
        var removeFromSavedHTML = '<a class="removeFromSaved" href="#"><i class="fa fa-list-alt selected"></i> Listed</a>';
        $("body").on('click','li.save_toggle',function(){
          var _user = $(this).data('user');
          var _url = $(this).data('url');
          
          var PostToSave = $('.save_toggle[data-url="'+_url+'"]');
            var currentContent = PostToSave.html();
            if (currentContent === addToSavedHTML) {
              PostToSave.html('Saving..');
            }else {
              PostToSave.html('Removing..');
            }

          $.post("contr_toggle_saved.php",{user:_user,url:_url},
            function(data){
              var PostToSave = $('.save_toggle[data-url="'+_url+'"]');
                var currentContent = PostToSave.html();
                if (currentContent === 'Saving..') {
                  PostToSave.html(removeFromSavedHTML);
                }else {
                  PostToSave.html(addToSavedHTML);
                }
              //$(this).html("<b>this</b> is a test");
          });
        });
      }
    },
    favorites :{
      init: function(){
        var addToFavoritesHTML = '<a class="addToFavorites" href="#"><i class="fa fa-star"></i> Add Blog to Favorites</a>';
        var removeFromFavoritesHTML = '<i class="fa fa-star" style="color:#FC0"></i> Favorite (<a class ="removeFromFavorites" href="#">remove</a>)';
        $("body").on('click','.favorite_toggle',function(){
          var _blog = $(this).data('blog');
          var _user = $(this).data('user');
          $('.favorite_toggle[data-blog="'+_blog+'"]').each(function(){
              var currentContent = $(this).html();
              if (currentContent === addToFavoritesHTML) {
                $(this).html(' adding.. ');
              }else {
                $(this).html(' removing.. ');
              }
              console.log(currentContent);
            //$(this).html("<b>this</b> is a test");
          });

          $.post("contr_toggle_favorites.php",{user:_user,blog:_blog},
            function(data){
              $('.favorite_toggle[data-blog="'+_blog+'"]').each(function(){
                var currentContent = $(this).html();
                if (currentContent === ' adding.. ') {
                  $(this).html(removeFromFavoritesHTML);
                }else {
                  $(this).html(addToFavoritesHTML);
                }
                console.log(currentContent);
              //$(this).html("<b>this</b> is a test");
            });
          });
          if (global_page === "favorites") {
            // if we are in the favorites page, refresh page to reflect changes
            location.reload();
          }
        });
      }
    },

    images : {
      show: function(){
        $("img.lazy").lazyload({
          threshold: 500,
          effect: "fadeIn",
          container: $("#view-area")
        });
        $("img.lazy").removeClass("lazy");
      },
      nudge: function(){
        var t = $('#view-area').scrollTop();
        $('#view-area').scrollTop(t+1); //nudge 1 pixel to counter lazy load bug.
      }
    },

  },

  interface:{
    columns : Math.floor(($('#view-area').outerWidth()-20)/320), // leave a minimum of 15px whitespace on either side
    init: function(){
      // depending on what page does, appropriate parts of the interface js will be activated.
      
      if ((global_page === "browse") || (global_page === "top") || (global_page === "favorites") || (global_page === "saved")) { this.leftNav.init(); } // initialize left navigation ;

      this.setDimensions(); // corrects page dimensions
      this.menus.init(); // initialize drop down menus
      this.modal.init();

      // hack to address fast scrolling with ios
      $('#view-area').css('-webkit-overflow-scrolling', 'touch');

    },

    cards: {
      flow: function(){
        //calculate number of columns and width of posts area
        var cardWidth = 320; //278px + 2 (border 1 px) + 20 (margin 10px);
        var columns = Math.floor(($('#view-area').outerWidth()-20)/cardWidth);
        if (columns < 1) {
          columns = 1;
        }
        var windowWidth = columns * cardWidth;
        console.log(windowWidth);
        //set width of posts window and center it
        $('#posts').css("width", windowWidth);
        $('#posts').css("margin","0 auto");

        // use blocksit plugin to lay the cards out
        $('#posts').BlocksIt({
          numOfCol: columns,
          offsetX: 10,
          offsetY: 10,
          blockElement: '.card-container'
        });
      }
    },
    revealContent: function(){
      $('.loader').fadeTo('fast',0, function(){
        $('#view-area').fadeTo('fast',1);
      });
    },
    header: $('.mainbar-wrapper'),
    sidebar: $('#left-col-wrapper'),
    viewArea: {
      window: $('#view-area'),
      handleScroll: function(){
        this.window.on('scroll',function(){
          if (lbApp.posts.busy === "yes") {
            // Don't do anything if we're in the middle of an update
            console.log('busy updating');
            return;
          }
          lbApp.posts.top_switcher_reposition();
          var scrollAmount = Math.abs(lbApp.posts.window.position().top);
          var distanceToBottom = lbApp.posts.window.height() - scrollAmount;
          console.log("distanceToBottom: "+distanceToBottom);
          if (distanceToBottom < 3000) {
            lbApp.posts.addMore();
          }
        });
      }
    },
    setDimensions: function(){
      // calculate the heights of the sidebar and the view area 
      // to fit exactly in the window
      lbApp.interface.sidebar.css("height", jQuery(window).outerHeight()); // 2px for the top border
      lbApp.interface.viewArea.window.css("height", jQuery(window).outerHeight()-lbApp.interface.header.outerHeight());

      // a little hack to remove the unnecessary scroll bar from the sidebar
      if ($('div.left-col-inner').height() > ($('#left-col-wrapper').height())) {
        $('#left-col-wrapper').css("overflow-y","scroll");
      } else {
        $('#left-col-wrapper').css("overflow-y","hidden");
      }

    },
    showLazyImages: function(){
      // this will use jquery instead of the lazy load plugin to show images.
      //<img class="lazy" data-original="http://4.bp.blogspot.com/-V8dJGssHVmc/UivdnUqR1GI/AAAAAAAABbw/_KY_jAxNSpU/s320/portugal.JPG" src="img/interface/grey.gif" width="278" height="208">
      $('img.lazy').each(function(){
        $(this).attr('src', $(this).data('original')) ;
        $(this).removeClass('lazy');
      });
    },
    leftNav : {
      init: function(){
        //by default, show left sidebar for larger windows
        // hide it for small screens
        if ($(window).width()<768 || global_leftColumnInitialState == "closed" ) {
          this.hide();
        }else{
          this.show();
        }
        //leftbar event handlers
        $('#hamburger').on('click', function(e){
          e.preventDefault();
          e.stopPropagation();          
          lbApp.interface.leftNav.show();
          lbApp.posts.flow();
          lbApp.posts.top_switcher_reposition();
        });

        $('.leftNav-dismiss').on('click', function(e){
          e.preventDefault();
          e.stopPropagation();
          lbApp.interface.leftNav.hide();
          lbApp.posts.flow();
          lbApp.posts.top_switcher_reposition();
        });

      },
      toggle: function(){
        lbApp.sidebar.toggle();
        $('#hamburger').toggle();
      },
      hide: function(){
        lbApp.interface.sidebar.css("display","none");
        $('#hamburger').css("display","block");
      },
      show: function(){
        lbApp.interface.sidebar.css("display", "block");
        $('#hamburger').css("display","none");
      },
      visible: function(){
        if (lbApp.interface.sidebar.css("display") === "block") {
          return true;
        }else {
          return false;
        }
      },

    },
    modal : {
      init: function(){
        // enable dismiss with click
        $('.modal-dismiss').on('click',function(){
          lbApp.interface.modal.hide();
        });
        // enable escape key dismiss
        $(document).on('keydown', function(event){ //monitor keystrokes
          if (event.which === 27) {
            lbApp.interface.modal.hide();
          }
        });
      },
      hide: function(){
        $('#modal').css('display','none');
      },
      show: function(){
        $('#modal').css('display','block');
      },
      message: function(message){
        $('#modal-body').html("<p>"+message+"<p>");
        lbApp.interface.modal.show();
      }
    },
    menus : { // for handling the Navigation menu
      init: function(){
        $('#menu-icons > li').on('click',function(){
          if ($(this).hasClass('selected')) { //just close current window
            $('.menu').hide(); // hide all open windows
            $(this).removeClass('selected');
          }else{
            $('.menu').hide(); // hide all open windows
            $('#menu-icons > li').removeClass("selected"); // deselect all
            $(this).addClass("selected"); // select the one we clicked on
            var whichWindow = $(this).attr('data-menu');
            console.log(whichWindow+"test");
            $('#'+whichWindow).show();
            if (whichWindow == 'menu-search') {
              console.log('yes');
              $('#search-box').focus();
            }
          }
        });
      }

    },

    // below are interface items specific to certain viewtypes

    compactView : {
      init: function(){
        lbApp.posts.module.on('click', function(){
          lbApp.interface.compactView.handleClicks($(this));
        });
        this.selection.init();

      },
      selection:{
        init: function(){
          theSelection = lbApp.interface.compactView.selection;
          theSelection.show();
          // handle keyboard shortcuts
          $(document).on('keydown', function(event){
            var keyPressed = event.which;
            if ((keyPressed === 74) || (keyPressed === 40)) { //j or Down arrow

              theSelection.moveDown();
              theSelection.show();

            } else if ((keyPressed === 75) || (keyPressed === 38)) { //k or up arrow

              theSelection.moveUp();
              theSelection.show();

            } else if ((keyPressed === 13) || (keyPressed === 32) || (keyPressed === 79)) { //Spacebar or Enter Key or 'o' button
            lbApp.interface.compactView.handleClicks($('.compact.selected'));
          }
        });
        },
        position : 0,
        moveUp : function(){
          if (this.position >0) {
            this.position = this.position - 1;
          }
        },
        moveDown : function(){
          //if (this.position < $('.compact').length){
            this.position = parseInt(this.position,10) + 1;
          //        }
        },
        show : function(){
          $('.compact.selected').removeClass('selected');
          $('.compact[data-post-number='+this.position+']').addClass('selected');
        },
        focus : function(post){
          $('.compact.selected').removeClass('selected');
          this.position = post.attr("data-post-number");
          $('.compact[data-post-number='+this.position+']').addClass('selected');
        },
      },
      handleClicks: function(post){
        var whichPost = post.attr('data-post-number');
        this.selection.position=whichPost;
        this.selection.show();
        if (post.hasClass('open')) {
          //clicking on an open post will only close it.
          post.removeClass('open');
        }else{
          // close currently open class
          $('.compact.open').removeClass('open');
        // open selected class class
        post.addClass('open');

        // positions open post at top of image
        $('#view-area').scrollTop(41*(whichPost));
      }
    }
  },
},

bloggerPage:{
  window: $('.bloggerPosts'),
  flow: function(){
      this.cardWidth = 320; //278px + 2 (border 1 px) + 20 (margin 10px);
      this.columns = Math.floor(lbApp.interface.viewArea.window.outerWidth()/this.cardWidth);
      this.windowWidth = this.columns * this.cardWidth;
      
      this.window.css("width",this.windowWidth);
      this.window.css("margin","0 auto");

      $('.blogMeta').css({
        "width"     :   this.window.width() - 60,
        "margin"    :   "0 auto"
      });

      this.window.BlocksIt({
        numOfCol: this.columns,
        offsetX: 10,
        offsetY: 10,
        blockElement: '.card-container'
      });
    },
  show:function(){
    $('.card').css('opacity','1');
  },
  loadImages: function(){
    $("img.lazy").lazyload({
      threshold: 500,
      effect: "fadeIn",
    });
    $("img.lazy").removeClass("lazy");
    var t = $('.blogger').scrollTop();
    $(window).scrollTop(t+1); //nudge 1 pixel to counter lazy load bug.
  }
},

  aboutPage: {
    init: function(){
      
      // center the menu of the about section

      var menuWidth = 0;
      $('#section-choose li').each(function(){
        menuWidth += $(this).outerWidth();
      });
      $('ul#section-choose').css({
        'width': menuWidth+3, //allow for borders
        'margins': "0 auto"
      });

      // Accept form input

      $('#submitBlog').on('click',function(){
        var re = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/ ;
        var submission = $('#submitblog').val();
        if (re.test(submission)) { //value is URL
          var formData = {
            key: 'qsdljkhqepoi3420$98346adfs34',
            kind: '[SUBMISSION]',
            submittedText: submission,
          };
          $.ajax({
            url: "mailer.php",
            type: "POST",
            data: formData,
            success: function(data){
              $('#submitblog').val('');
              lbApp.interface.modal.message(data);
            }
          });
        }else{
          lbApp.interface.modal.message("Sorry, you should submit a URL");
        }
        return false;
      });
      $('#submitFeedback').on('click',function(){
        var submission = $('textarea#feedback').val();
        if (submission !== '') { //value is not empty
          var formData = {
            key: 'qsdljkhqepoi3420$98346adfs34',
            kind: '[FEEDBACK]',
            submittedText: submission,
          };
          $.ajax({
            url: "mailer.php",
            type: "POST",
            data: formData,
            success: function(data){
              $('textarea#feedback').val('');
              lbApp.interface.modal.message(data);
            }
          });
        }else{
          lbApp.interface.modal.message("Sorry, Feedback message cannot be left empty");
        }
        return false;
      });
    }
  },

  searchPage: {
    init: function(){
      searchTerm = $('#searchresults').data('term');
      this.findBlogNames();
      this.findPostTitles();
      this.findPostContents();
    },
    findBlogNames: function(){
      $.post("contr_search.php",{term:searchTerm, scope:"blognames"},
        function(data){
          $('#blognames').html(data);
        }
      );
    },
    findPostTitles: function(){
    $('#blogtitles').html('<h3 class ="status">Searching for Blog Posts with the term \''+searchTerm+'\' in their titles</h3><img src="img/interface/ajax-loader.gif" alt="spinning wheel">');
      $.post("contr_search.php",{term:searchTerm, scope:"blogtitles"},
        function(data){
          $('#blogtitles').html(data);
          if ($('#blogtitles').height()>250) { // hide for expansion
            $('#blogtitles').height(250);
            $('#blogtitles').after('<div class ="expand"><a class = "expandTitles" href ="#"><i class ="icon icon-expand-alt"></i> See All Results</a></div>');
            $('.expandTitles').on('click', function(){
              $('#blogtitles').height('auto');
              $(this).hide();
            });
          }
        }
      );
    },
    findPostContents: function(){
    $('#blogcontents').html('<h3 class ="status">Searching for Blog Posts with the term \''+searchTerm+'\' in their text</h3><img src="img/interface/ajax-loader.gif" alt="spinning wheel">');
      $.post("contr_search.php",{term:searchTerm, scope:"blogcontents"},
        function(data){
          $('#blogcontents').html(data);
          if ($('#blogcontents').height()>250) { // hide for expansion
            $('#blogcontents').height(250);
            $('#blogcontents').after('<div class ="expand"><a class = "expandContents" href ="#"><i class ="icon icon-expand-alt"></i> See All Results</a></div>');
            $('.expandContents').on('click', function(){
              $('#blogcontents').height('auto');
              $(this).hide();
            });
          }
        }
      );
    },
  },
};


$(document).ready(function(){
  lbApp.interface.init();
  if (global_page === "browse" || global_page === "search") {
    lbApp.posts.init();
    lbApp.posts.flow(); // fix dimensions & locations in posts. each viewtype will have a "posts" object with flow() and show() methos
    lbApp.posts.show(); // show everything
    lbApp.interface.viewArea.handleScroll();
    if (global_viewtype === "compact") {
      lbApp.interface.compactView.init();
    }
    lbApp.interface.revealContent();
    lbApp.posts.favorites.init();
    lbApp.posts.saved.init();
    lbApp.posts.top_switcher_init();
    $('.endloader').css('opacity',1);

  }
  if ((global_page === "about")) {
    lbApp.aboutPage.init();
    lbApp.interface.revealContent();
  }
  if ((global_page === "blogger")) {
    lbApp.bloggerPage.flow();
    lbApp.bloggerPage.show();
    lbApp.interface.revealContent();
    /*lbApp.bloggerPage.loadImages();*/
    lbApp.posts.favorites.init();
    lbApp.posts.saved.init();

  }
  if ((global_page === "top")) {
    lbApp.interface.cards.flow();
    lbApp.interface.revealContent();

  }
  if ((global_page === "search")) {
    lbApp.interface.revealContent();
    lbApp.searchPage.init();

  }
  if ((global_page === "favorites")){
    lbApp.posts.init();
    lbApp.posts.flow(); // fix dimensions & locations in posts. each viewtype will have a "posts" object with flow() and show() methos
    lbApp.posts.show(); // show everything
    lbApp.interface.viewArea.handleScroll();
    if (global_viewtype === "compact") {
      lbApp.interface.compactView.init();
    }
    lbApp.interface.revealContent();
    lbApp.posts.favorites.init();
    lbApp.posts.saved.init();
  }
  if ((global_page === "saved")){
    lbApp.posts.init();
    lbApp.posts.flow(); // fix dimensions & locations in posts. each viewtype will have a "posts" object with flow() and show() methos
    lbApp.posts.show(); // show everything
    lbApp.interface.viewArea.handleScroll();
    if (global_viewtype === "compact") {
      lbApp.interface.compactView.init();
    }
    lbApp.interface.revealContent();
    lbApp.posts.favorites.init();
    lbApp.posts.saved.init();
  }
});

$(window).load(function(){
    lbApp.interface.showLazyImages();
});

var do_resize;
$(window).resize(function() {
  clearTimeout(do_resize);
  do_resize = setTimeout(function() {
    if (global_page === "browse") {
      lbApp.interface.setDimensions();
      lbApp.posts.flow();
      lbApp.posts.top_switcher_reposition();
    }
    if (global_page === "blogger") {
      lbApp.bloggerPage.flow();
    }

    if (global_page === "top") {
      lbApp.interface.cards.flow();
    }
    if (global_page === "favorites" || global_page === "saved") {
      lbApp.interface.setDimensions();
      lbApp.posts.flow();
    }
  }, 300);
});