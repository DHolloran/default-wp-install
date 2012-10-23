var myWidth = 0;
var myHeight = 0;

function changeSize() {

  if( typeof( window.innerWidth ) == 'number' ) {
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    myWidth = document.documentElement.clientWidth;
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;
  }
  myWidth = myWidth / 1.6;
  myHeight = myHeight / 1.6;
}

// Run the script on DOM ready:
jQuery(function(){
 changeSize();
 jQuery('table').visualize({type: 'pie', height: '300px', width: myWidth +'px'});
	jQuery('table').visualize({type: 'bar', width: myWidth + 'px'});
	jQuery('table').visualize({type: 'area', width: myWidth +'px'});
	jQuery('table').visualize({type: 'line', width: myWidth +'px'});
});