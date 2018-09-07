$(document).ready(function() {
  console.log("Ready!");
  $(".pound").hide();
  $(".cheesecake").hide();

})



$("#layer").click(function() {
  $(".layer").show();
  $(".pound").hide();
  $(".cheesecake").hide();

})

$("#pound").click(function() {
  $(".pound").show();
  $(".layer").hide();;
  $(".cheesecake").hide();
})

$("#cheesecake").click(function() {
  $(".cheesecake").show();
  $(".layer").hide();
  $(".pound").hide();
});
