document.addEventListener('DOMContentLoaded', function () {
    const prevButtons = document.querySelectorAll('.carousel-control-prev');
    const nextButtons = document.querySelectorAll('.carousel-control-next');
    
    prevButtons.forEach(button => {
        button.addEventListener('click', function () {
            const carousel = button.closest('.custom-carousel');
            const inner = carousel.querySelector('.carousel-inner');
            const activeItem = inner.querySelector('.carousel-item.active');
            const prevItem = activeItem.previousElementSibling || inner.lastElementChild;

            activeItem.classList.remove('active');
            prevItem.classList.add('active');

            inner.style.transform = `translateX(-${prevItem.offsetLeft}px)`;
        });
    });
    
    nextButtons.forEach(button => {
        button.addEventListener('click', function () {
            const carousel = button.closest('.custom-carousel');
            const inner = carousel.querySelector('.carousel-inner');
            const activeItem = inner.querySelector('.carousel-item.active');
            const nextItem = activeItem.nextElementSibling || inner.firstElementChild;

            activeItem.classList.remove('active');
            nextItem.classList.add('active');

            inner.style.transform = `translateX(-${nextItem.offsetLeft}px)`;
        });
    });
});
