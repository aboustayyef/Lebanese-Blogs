var lbApp = {};


// This Function sends the reArrangePosts event to listeners in the interface
// The event tells interface listeners to reflow the posts
lbApp.reArrange = function(){
  $.event.trigger({
    type: "reArrangePosts",
  });
};

$(window).load(function()
{
  // initialize posts type. Default is "cards", so we won't include it here
  if (global_viewtype === 'timeline')
  {
    lbApp.Posts.init({
      viewtype: 'timeline', //default
      $wrapper: $('#posts'), // Wraps all the blog posts
      superContainer: '.timeline-post', // The class that wraps each post 
      container: '.timeline-post'
    });
  }
  if (global_viewtype === 'compact')
  {
    lbApp.Posts.init({
      viewtype: 'compact', //default
      $wrapper: $('#posts'), // Wraps all the blog posts
      superContainer: '.compact', // The class that wraps each post 
      container: '.compact'
    });
  }

  // Global Buton / Action Link Run
  // Create a Function from a string
  $(document).on('click', 'button[data-action], .action', function (e) {
      e.preventDefault();
      e.stopPropagation();
      var action = $(this).data('action'), funct; // Get Data Action Attribute
      funct = eval('lbApp.' + action); // Convert it to an executable function
      funct($(this)); // Run it with passed in object  });
  });

  // initialize interface
  lbApp.interfaceElements.init();

  // make sure all posts are laid out and in place before revealing them
  lbApp.reArrange();

  // Hide the curtain
  lbApp.interfaceElements.viewArea.revealContent();
});