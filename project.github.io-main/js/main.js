$('.slick-slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    adaptiveHeight: true,
    prevArrow: $('.prev'),
    nextArrow: $('.next'),
    responsive: [{
            breakpoint: 768,
            settings: {
                arrows: false,
                slidesToShow: 1
            }
        },
        {
            breakpoint: 480,
            settings: {
                arrows: false,
                slidesToShow: 1
            }
        }
    ]
});
$('.slick-slider-two').slick({
    slidesToShow: 4,
    autoplay: true,
    autoplaySpeed: 2000,
    accessibility: false,
    responsive: [{
            breakpoint: 768,
            settings: {
                arrows: false,
                slidesToShow: 2
            }
        },
        {
            breakpoint: 480,
            settings: {
                arrows: false,
                slidesToShow: 2
            }
        }
    ]
});
$('.slick-slider-three').slick({
    slidesToShow: 4,
    autoplay: true,
    autoplaySpeed: 2500,
    accessibility: false,
    responsive: [{
            breakpoint: 768,
            settings: {
                arrows: false,
                slidesToShow: 2
            }
        },
        {
            breakpoint: 480,
            settings: {
                arrows: false,
                slidesToShow: 2
            }
        }
    ]
});

$(function() {

  
    $('.dropdown-toggle').on('mouseover', function(e) {
        $(this) 
            .parents('.dropdown') 
            .addClass('show'); 
        $(this)
            .siblings('.dropdown-menu-wrapper') 
            .find('.dropdown-menu')
            .addClass('show');
    })

   
    $('.dropdown-toggle').on('mouseout', function(e) {
        $(this).parents('.dropdown').removeClass('show');
        $(this).siblings('.dropdown-menu-wrapper').find('.dropdown-menu').removeClass('show');
    })
})

$(function(){
            $("#form").submit(function(e){
                e.preventDefault();

                let name = document.getElementById('edit-field-vashe-imya-0-value').value;
                let number = document.getElementById('edit-field-telefon-0-value').value;
                let email = document.getElementById('edit-field-e-mail-0-value').value;
                let comment = document.getElementById('edit-field-vash-0-value').value;
                let check = document.getElementById('edit-fz152-agreement').checked;
        
                if (!name || !email || number=='+7' || !number || !comment || !check) {
                    alert('Заполните все поля!!!');
                    return;
                }
        
                localStorage.setItem('name', name);
                localStorage.setItem('email', email);
                localStorage.setItem('number', number);
                localStorage.setItem('comment', comment);
            
        
            
              var href = $(this).attr("action");
              
              $.ajax({
                  type: "POST",
                  url: href,
                  data: new FormData(this),
                  dataType: "json",
                  processData: false,
                  contentType: false,
                  success: function(response){
                    if(response.status == "success"){
                        alert("Мы получили вашу заявку, спасибо вам");
                        localStorage.clear();
                        form.reset();
                    }
                    else if(response.code === 422){
                      alert("Field validation failed");
                      $.each(response.errors, function(key) {
                        $('[name="' + key + '"]').addClass('formcarry-field-error');
                      });
                    }
                    else{
                      alert("An error occured: " + response.message);
                    }
                  },
                  error: function(jqXHR, textStatus){
                    const errorObject = jqXHR.responseJSON
          
                    alert("Request failed, " + errorObject.title + ": " + errorObject.message);
                  }
              });
            });
          });
    
});
