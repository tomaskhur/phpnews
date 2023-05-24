$(".checkbox-dropdown").click(function () {
    $(this).toggleClass("is-active");
    document.querySelector(".checkbox-dropdown").classList.toggle("margin-bottom");
});

$(".checkbox-dropdown ul").click(function(e) {
    e.stopPropagation();
});