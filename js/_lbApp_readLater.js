lbApp.removeFromList = function(param){

  // show 'working' status
  param.find('.message').html('working..');

  // remove url
  $.ajax({
    type: "POST",
    url: "contr_toggle_saved.php",
    data: {url:param.data('url'), user:param.data('user')},
    success: function(){
      param.data('action','addToList');
      param.find('.message').html('<i class="fa fa-clock-o"></i> Read Later');
      // Update Sidebar Counter
      $figure = $('#readingListCounter .theFigure');
      var howmany = $figure.text();
      howmany = Number(howmany) - 1;
      $figure.text(howmany);
    }
  });

};

lbApp.addToList = function(param){
  
  // show 'working' status
  param.find('.message').html('working..');

  // add url
  $.ajax({
    type: "POST",
    url: "contr_toggle_saved.php",
    data: {url:param.data('url'), user:param.data('user')},
    success: function(){
      param.data('action','removeFromList');
      param.find('.message').html('<i class="fa fa-list-alt selected"></i> Listed');
      // Update Sidebar Counter
      $figure = $('#readingListCounter .theFigure');
      var howmany = $figure.text();
      howmany = Number(howmany) + 1;
      $figure.text(howmany);
    }
  });
};