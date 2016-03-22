$(document).ready(function () {
    $('#isstudent').bootstrapSwitch({
        'onText': yesTr,
        'offText': noTr
    });
    $('.terms-and-conditions-container').hide();
    termsCheckBox = $('.terms-and-conditions-container :checkbox');
    termsCheckBox.change(function() {
      termsCheckBox.attr('checked', !termsCheckBox.attr('checked'));
      if(isTermsChecked()) {
        $('.form-horizontal button').show();
      } else {
        $('.form-horizontal button').hide();
      }
    });
    $('.form-horizontal').submit(function(e) {
      if(isTermsChecked()) {
        $(this).unbind('submit').submit();
      } else {
        e.preventDefault();
        $('.form-horizontal button').hide();
        $('.form-horizontal div').hide();
        $('.terms-and-conditions-container').show();
      }
      return true;
    });
    isMemberCheckBox = $(':checkbox[name=isMember]');
    isMemberCheckBox.change(function() {
        isMemberCheckBox.attr('checked', !isMemberCheckBox.attr('checked'));
    });
});
function isTermsChecked() {
  var checked = $('.terms-and-conditions-container :checkbox').attr("checked");
  return checked == "checked";
}
