 jQuery(document).ready(function($){
                $('#testimonials_carousel').owlCarousel({
                loop:true,
                margin:10,
                nav:true,
                dots:false,
                responsive:{
                0:{
                    items:1
                },
                600:{
                    items:2
                },
                1000:{
                    items:2
                }
                }
                });


                $('#services_carousel').owlCarousel({
                loop:true,
                margin:10,
                nav:true,
                dots:false,
                responsive:{
                0:{
                    items:1
                },
                600:{
                    items:2
                },
                1000:{
                    items:4
                }
                }
                });

                $(".custom-pagination .page-item").find("span").addClass("page-link").removeClass("page-numbers").parent("li").addClass("active");

    });
