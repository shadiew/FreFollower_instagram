const menuToggle = document.querySelector('.menu-toggle input');
const nav = document.querySelector('nav ul');
const navTitle = document.querySelector('.slide-menu-title');

menuToggle.addEventListener('click',function(){
    nav.classList.toggle('slide');
    navTitle.style.display = "block";
});