// $(".show-more").click(function () {
//     for (var i=0; i<$(".info").length; i++) {
//         if ($(".info").eq(i).hasClass("expand")) {
//             $(this).eq(i).text("Show less");
//             $(this).eq(i).parent().removeClass("expand");
//         } else {
//             $(this).eq(i).text("Show more");
//             $(this).eq(i).parent().addClass("expand");
//         }
//     }
// });

document.addEventListener('DOMContentLoaded', () => {
    const expandsMore = document.querySelectorAll('[expand-more]');

    function expand() {
        const showContent = document.getElementById(this.dataset.target);
        if(showContent.classList.contains("expand-active")) {
            this.innerHTML = this.dataset.showtext;
            // showContent.scrollIntoView({
            //     block: 'start',
            //     behavior: 'smooth',
            //     inline: 'center'
            // });
        } else {
            this.innerHTML = this.dataset.hidetext;
        }
        showContent.classList.toggle("expand-active");
    }

    expandsMore.forEach(expandMore => {
        expandMore.addEventListener("click", expand);
    });
})