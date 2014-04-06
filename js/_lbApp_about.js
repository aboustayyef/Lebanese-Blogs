$(document).on('click','#submitBlog',function(e)
{
  e.preventDefault();
  var re = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/ ;
  var submission = $('#submitblog').val();
  if (re.test(submission))
  { //value is URL
    var formData =
    {
    key: 'qsdljkhqepoi3420$98346adfs34',
    kind: '[SUBMISSION]',
    submittedText: submission,
    };
    $.ajax(
    {
      url: "mailer.php",
      type: "POST",
      data: formData,
      success: function(data)
      {
        $('#submitblog').val('');
        $('#message_submitblog').html('<p class ="clear">Your Submission has been sent. Kindly allow us a few days to process</p>').fadeOut(3500);
      }
    });
  }else{
    $('#message_submitblog').html('<p class ="error">You Should submit a proper website</p>').fadeOut(3500);
  }
});
 
$(document).on('click','#submitFeedback',function(e)
{
  e.preventDefault(e);
  var submission = $('textarea#feedback').val();
  if (submission !== '')
  { //value is not empty
    var formData =
    {
      key: 'qsdljkhqepoi3420$98346adfs34',
      kind: '[FEEDBACK]',
      submittedText: submission,
    };
    $.ajax({
      url: "mailer.php",
      type: "POST",
      data: formData,
      success: function(data)
      {
        $('textarea#feedback').val('');
        $('#message_submitfeedback').html('<p class ="clear">Your Feedback has been sent. If you included your email in it, there is a chance we\'ll respond</p>').fadeOut(3500);
      }
    });
  }else{
    $('#message_submitfeedback').html('<p class ="error">Sorry, Feedback message cannot be left empty</p>').fadeOut(3500);
  }
});