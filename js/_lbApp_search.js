/* Search tool that uses ajax to chain results of different kinds of search */

lbApp.Search =
{
  init: function(searchTerm)
  {
    this.searchTerm = searchTerm;
    // first, search blog titles and then call back to further
    console.log('searching blog names');
    lbApp.Search.search(searchTerm, 'blognames', "Names");
    console.log('searching blog titles')
    lbApp.Search.search(searchTerm, 'blogtitles', "Titles");
    console.log('searching blog contents');
    lbApp.Search.search(searchTerm, 'blogcontents', "Content");
  },

  search: function(searchTerm, scope, description, callBack)
  {
    $.ajax({
      type: 'POST',
      url: "contr_search.php",
      data: {term:searchTerm, scope:scope, description:description},
      success: function(data)
        {
          howManyResults = $(data).find('.card').length; // finds how many results we got
          var header = lbApp.Search.makeHeader(searchTerm, description, howManyResults) ; //+ ' ' + howManyResults;
          var content;
          if (howManyResults>0)
            {
               content = header+data;
            } else {
               content = '';
            }
          $('#posts').find('#placeHolder_'+scope).replaceWith(content);
          lbApp.reArrange();
        },
      async:false
    });
    // $.post("contr_search.php",{term:searchTerm, scope:scope, description:description}, function(data)
    // {
    //   howManyResults = $(data).find('.card').length; // finds how many results we got
    //   var header = lbApp.Search.makeHeader(searchTerm, description, howManyResults) ; //+ ' ' + howManyResults;
    //   var content;
    //   if (howManyResults>0)
    //     {
    //        content = header+data;
    //     } else {
    //        content = '';
    //     }
    //   $('#posts').find('#placeHolder_'+scope).replaceWith(content);
    //   lbApp.reArrange();
    // });
    if (typeof(callBack) === 'function') {
      callBack();
    }
  },

  makeHeader: function(searchTerm, description, howManyResults){
    var prefix;
    if (description == 'Names')
      {
        prefix = 'Blogs';
      }else {
        prefix = 'Blog posts';
      }
    var phrase = prefix + ' with the phrase "'+searchTerm+'" in their '+description+ ': '+ howManyResults;
    var cols = lbApp.interfaceElements.viewArea.numberOfColumns();
    return '<div class ="card-container divider" style="opacity:0" data-size="'+cols+'"><div style ="background:white"><p>'+phrase+'</p></div></div>';
  }
};

if (global_page === 'search') {
  $(document).ready(function(){
    //lbApp.Search.search("tripoli","blogtitles", "Blog titles");
  });
}