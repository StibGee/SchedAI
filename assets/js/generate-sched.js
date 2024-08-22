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

            $('#viewToggleButton').click(function() {
                $(' #tabularView, #calendarView').toggle();
            });
             // Sample data for tabular view
             const tabularData = [
                { no: 3, department: 'Physics', code: 'PHYS101', description: 'Intro to Physics', type: 'Lab', unit: 2, room: 'L101', time: '1:00 PM - 3:00 PM', day: 'Wednesday', yearSec: '1C', lecturer: 'Dr. Brown' },
                // Add more data as needed
            ];

            tabularData.forEach(data => {
                let row = `<tr>
                                <td>${data.no}</td>
                                <td>${data.department}</td>
                                <td>${data.code}</td>
                                <td>${data.description}</td>
                                <td>${data.type}</td>
                                <td>${data.unit}</td>
                                <td>${data.room}</td>
                                <td>${data.time}</td>
                                <td>${data.day}</td>
                                <td>${data.yearSec}</td>
                                <td>${data.lecturer}</td>
                           </tr>`;
                $('#tabularTableBody').append(row);
            });
        });

});


