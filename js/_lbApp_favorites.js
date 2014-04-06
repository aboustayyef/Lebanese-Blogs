/* used actions created */

lbApp.addToFavorites = function(params)
{
  var user = params.data('user');
  var blog = params.data('blog');
  
  // mark all fav buttons for this blog as "updating"
  $(".card[data-blogid='"+blog+"']").each(function(){
    // change class to updating
    $(this).find('.addToFav').removeClass('addToFav').addClass('updating');
  });

  $.ajax({
    type: "POST",
    url: 'contr_toggle_favorites.php',
    data: {blog:blog, user:user},
    success: function(){
      $(".card[data-blogid='"+blog+"']").each(function(){
        // change class to removeFromFavorites
        $(this).find('.updating').data('action','removeFromFavorites');
        $(this).find('.updating').removeClass('updating').addClass('removeFromFav');
      });
    }
  });
};

lbApp.removeFromFavorites = function(params)
{
  var user = params.data('user');
  var blog = params.data('blog');
  
  // mark all fav buttons for this blog as "updating"
  $(".card[data-blogid='"+blog+"']").each(function(){
    // change class to updating
    $(this).find('.removeFromFav').removeClass('removeFromFav').addClass('updating');
  });

  $.ajax({
    type: "POST",
    url: 'contr_toggle_favorites.php',
    data: {blog:blog, user:user},
    success: function(){
      $(".card[data-blogid='"+blog+"']").each(function(){
        // change class to removeFromFavorites
        $(this).find('.updating').data('action','addToFavorites');
        $(this).find('.updating').removeClass('updating').addClass('addToFav');
      });
    }
  });
};