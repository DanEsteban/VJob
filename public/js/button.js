document.getElementById("button-up").addEventListener("click", scrollUp);

function scrollUp() {
    var currentScroll = document.documentElement.scrollTop;
    if(currentScroll > 0){
        window.requestAnimationFrame(scrollUp);
        window.scrollTo(0, currentScroll - (currentScroll / 10));
    }
}

buttonUp = document.getElementById("button-up");
window.onscroll = function(){
    var scroll = document.documentElement.scrollTop;
    if(scroll > 500){
        buttonUp.style.transform = "scale(1)";
    }else if(scroll < 500){
        buttonUp.style.transform = "scale(0)";
    }

    if($("#scroll_header").length){
        var header = document.getElementById("scroll_header");

        if (scroll > 320) {
            header.classList.add("sticky");            
            $("#top_header").removeAttr('hidden');
        } 
        else {
            header.classList.remove("sticky");            
            $("#top_header").attr('hidden', true);

        }
    }
}
