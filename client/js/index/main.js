
function removeClass()
{
    $('#lo-que-podes').removeClass( 'eight' );
    $('#lo-que-podes').removeClass( 'six' );
    $('#lo-que-podes').removeClass( 'four' );
    $('#lo-que-podes').removeClass( 'two' );
}

function switchTileGroup()
{
    if( $(window).width() >= 1300 )
    {
        removeClass();
        $('#lo-que-podes').addClass( 'eight' );
    }
    else if( $(window).width() >= 1035 )
    {
        removeClass();
        $('#lo-que-podes').addClass( 'six' );
    }
    else if( $(window).width() >= 700 )
    {
        removeClass();
        $('#lo-que-podes').addClass( 'four' );
    }
    else
    {
        removeClass();
        $('#lo-que-podes').addClass( 'two' );
    }
        
} // end switchTileGroup

$(document).on( 'ready', function(){
    switchTileGroup();
    $('#search-form .form-control').focus();
    
});

(function () {
    width = $(window).width();
    height = $(window).height();
    setInterval( function () {
        if ( $(window).width() !== width || $(window).height() !== height ) {
            width = $(window).width();
            height = $(window).height();
            switchTileGroup();
        }
    }, 50);
}());