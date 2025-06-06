//importa Swiper desde la librería 'swiper'
import Swiper from 'swiper';
//importa módulo Navigation, desde la librería 'swiper/modules'
import { Navigation } from 'swiper/modules';



//asigna escucha evento y función(), al la carga del DOM
document.addEventListener('DOMContentLoaded', function() {
    //valida si existe el elemento con class slider en el document
    if(document.querySelector('.slider')) {

        //define objeto para las opciones del slider de swiper
        const opciones = {
            slidesPerView: 1,
            spaceBetween: 15,
            freeMode: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
            //como las Media Queries en px
            breakpoints: {
                768: {
                    slidesPerView: 2
                },
                1024: {
                    slidesPerView: 3
                },
                1200: {
                    slidesPerView: 4
                }
            }
        }

        //define el uso del módulo Navigation con Swiper, para las flechas
        Swiper.use([Navigation]);
        //instanciar Swiper() enviando la class del elemento html
        //donde se aplicará y opciones de comportamiento
        new Swiper('.slider', opciones);
    }
})