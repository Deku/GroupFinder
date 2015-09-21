$(document).ready( function() {
   $('.category-title-nav').on('change', function() {
      window.location.href = "../category/" + this.value; 
   });
});