document.addEventListener("DOMContentLoaded", function(event) {
    const showNavbar = (toggleId, navId, bodyId, headerId) => {
        const toggle = document.getElementById(toggleId),
              nav = document.getElementById(navId),
              bodypd = document.getElementById(bodyId),
              headerpd = document.getElementById(headerId);

        // Validate that all variables exist
        if (toggle && nav && bodypd && headerpd) {
            toggle.addEventListener('click', () => {
                // show navbar
                nav.classList.toggle('show');
                // change icon
                toggle.classList.toggle('bx-x');
                // add padding to body
                bodypd.classList.toggle('body-pd');
                // add padding to header
                headerpd.classList.toggle('body-pd');
            });
        }
    }

    showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header');

    /*===== LINK ACTIVE =====*/
    const linkColor = document.querySelectorAll('.nav_link');

    function colorLink() {
        if (linkColor) {
            linkColor.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        }
    }
    linkColor.forEach(l => l.addEventListener('click', colorLink));
});

//----- year picker----//

    // Function to generate academic year options
    function generateAcademicYearOptions() {
        const currentYear = new Date().getFullYear();
        const selectElement = document.getElementById('select-academic-year');

        for (let i = 0; i < 5; i++) { // Generate options for the next 5 years
            const startYear = currentYear + i;
            const endYear = startYear + 1;
            const option = document.createElement('option');
            option.value = `${startYear}-${endYear}`;
            option.textContent = `${startYear}-${endYear}`;
            selectElement.appendChild(option);
        }
    }

    // Call the function to generate options when the page loads
    window.onload = generateAcademicYearOptions;

    // modal


