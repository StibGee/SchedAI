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

    // Generate the schedule table
    $(document).ready(function() {
        const times = [
            '7:00 AM', '8:00 AM', '9:00 AM', '10:00 AM', '11:00 AM', '12:00 PM',
            '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM', '6:00 PM', '7:00 PM'
        ];

        const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        times.forEach(time => {
            let row = '<tr><td>' + time + '</td>';
            days.forEach(() => {
                row += '<td></td>';
            });
            row += '</tr>';
            $('#scheduleTableBody').append(row);
        });

        // Sample data for tabular view
        const tabularData = [
            { no: 1, code: 'CS101', description: 'Intro to Computer Science', type: 'Lecture', room: 'R101', time: '8:00 AM - 9:00 AM', day: 'Monday', yearSec: '1A' },
            { no: 2, code: 'MATH101', description: 'Calculus I', type: 'Lecture', room: 'R102', time: '9:00 AM - 10:00 AM', day: 'Tuesday', yearSec: '1B' }
            // Add more data as needed
        ];

        tabularData.forEach(data => {
            let row = `<tr>
                            <td>${data.no}</td>
                            <td>${data.code}</td>
                            <td>${data.description}</td>
                            <td>${data.type}</td>
                            <td>${data.room}</td>
                            <td>${data.time}</td>
                            <td>${data.day}</td>
                            <td>${data.yearSec}</td>
                       </tr>`;
            $('#tabularTableBody').append(row);
        });

        $('#viewToggleButton').click(function() {
            $('#calendarView, #tabularView').toggle();
        });
    });
});
