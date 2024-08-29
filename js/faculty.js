document.addEventListener("DOMContentLoaded", function(event) {
    const showNavbar = (toggleId, navId, bodyId, headerId) => {
        const toggle = document.getElementById(toggleId),
              nav = document.getElementById(navId),
              bodypd = document.getElementById(bodyId),
              headerpd = document.getElementById(headerId);

        if (toggle && nav && bodypd && headerpd) {
            toggle.addEventListener('click', () => {
                nav.classList.toggle('show');
                toggle.classList.toggle('bx-x');
                bodypd.classList.toggle('body-pd');
                headerpd.classList.toggle('body-pd');
            });
        }
    }

    showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header');

    const linkColor = document.querySelectorAll('.nav_link');

    function colorLink() {
        linkColor.forEach(l => l.classList.remove('active'));
        this.classList.add('active');
    }
    linkColor.forEach(l => l.addEventListener('click', colorLink));


});

document.getElementById('search-sub').addEventListener('input', function() {
    const searchValue = this.value.toLowerCase();
    const options = document.querySelectorAll('#position option');
    options.forEach(option => {
        if (option.text.toLowerCase().includes(searchValue)) {
            option.style.display = 'block';
        } else {
            option.style.display = 'none';
        }
    });
});

document.getElementById('search-sub').addEventListener('focus', function() {
    const options = document.querySelectorAll('#position option');
    options.forEach(option => {
        option.style.display = 'block';
    });
});
$(document).ready(function() {


    var readURL = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.profile-pic').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }


    $(".file-upload").on('change', function(){
        readURL(this);
    });

    $(".upload-button").on('click', function() {
       $(".file-upload").click();
    });
});
