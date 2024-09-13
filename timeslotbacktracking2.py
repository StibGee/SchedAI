import mysql.connector
from datetime import timedelta

# Utility functions to convert time formats
def time_to_minutes(time_str):
    hours, minutes = map(int, time_str.split(':'))
    return hours * 60 + minutes

def minutes_to_time(minutes):
    hours = minutes // 60
    minutes = minutes % 60
    return f"{hours:02}:{minutes:02}"

# Check if a given time slot is available within a faculty's preference and room availability
def is_valid_slot(faculty_id, room_id, day, start_time, end_time, room_occupied, faculty_assigned):
    start_minutes = time_to_minutes(start_time)
    end_minutes = time_to_minutes(end_time)

    # Check if room is available
    if room_id in room_occupied and day in room_occupied[room_id]:
        for time in range(start_minutes, end_minutes, 30):
            if time in room_occupied[room_id][day]:
                return False
    
    # Check if faculty is available
    if faculty_id in faculty_assigned and day in faculty_assigned[faculty_id]:
        for time in range(start_minutes, end_minutes, 30):
            if time in faculty_assigned[faculty_id][day]:
                return False

    return True

# Assign a time slot to a subject and mark it as occupied for both room and faculty
def assign_slot(subject_id, faculty_id, room_id, day, start_time, end_time, room_occupied, faculty_assigned, assignments):
    start_minutes = time_to_minutes(start_time)
    end_minutes = time_to_minutes(end_time)

    # Mark the room as occupied
    if room_id not in room_occupied:
        room_occupied[room_id] = {}
    if day not in room_occupied[room_id]:
        room_occupied[room_id][day] = set()
    for time in range(start_minutes, end_minutes, 30):
        room_occupied[room_id][day].add(time)

    # Mark the faculty as occupied
    if faculty_id not in faculty_assigned:
        faculty_assigned[faculty_id] = {}
    if day not in faculty_assigned[faculty_id]:
        faculty_assigned[faculty_id][day] = set()
    for time in range(start_minutes, end_minutes, 30):
        faculty_assigned[faculty_id][day].add(time)

    # Record the assignment
    assignments[subject_id] = (faculty_id, room_id, day, start_time, end_time)

    # Debugging print statement
    print(f"Assigned subject {subject_id} to faculty {faculty_id}, room {room_id}, on {day} from {start_time} to {end_time}")

# Unassign a time slot (used during backtracking)
def unassign_slot(subject_id, faculty_id, room_id, day, start_time, end_time, room_occupied, faculty_assigned, assignments):
    start_minutes = time_to_minutes(start_time)
    end_minutes = time_to_minutes(end_time)

    # Remove the room occupancy
    for time in range(start_minutes, end_minutes, 30):
        room_occupied[room_id][day].remove(time)

    # Remove the faculty occupancy
    for time in range(start_minutes, end_minutes, 30):
        faculty_assigned[faculty_id][day].remove(time)

    # Remove the assignment
    del assignments[subject_id]

# Backtracking function to find a valid schedule
def schedule_subjects(subjects, faculty_preferences, room_list, assignments, room_occupied, faculty_assigned):
    if not subjects:
        return True  # All subjects scheduled

    subject = subjects[0]
    subject_id, subject_name, units, subject_type, subjectfacultyid, subject_minor = subject[0], subject[12], subject[14], subject[16], subject[5], subject[18]

    # Exclude minor subjects
    if subject_minor != 'Major1':
        '''print(f"Skipping minor subject {subject_id}")''' 
        return schedule_subjects(subjects[1:], faculty_preferences, room_list, assignments, room_occupied, faculty_assigned)

    required_time = 90 if subject_type == 'Lec' and units == 3.0 else 120 if subject_type == 'Lec' else 180
    meetings_per_week = 2 if subject_type == 'Lec' and units == 3.0 else 1

    for pref in faculty_preferences:
        faculty_id, day, start_time, end_time = pref[1], pref[2], pref[3], pref[4]
        if faculty_id != subjectfacultyid:
            continue

        for room in room_list:
            room_id, room_name, room_type, room_start, room_end = room[0], room[1], room[2], room[3], room[4]
            if room_type != subject_type:
                continue

            # Try scheduling all required meetings per week
            for _ in range(meetings_per_week):
                if is_valid_slot(faculty_id, room_id, day, start_time, minutes_to_time(time_to_minutes(start_time) + required_time), room_occupied, faculty_assigned):
                    assign_slot(subject_id, faculty_id, room_id, day, start_time, minutes_to_time(time_to_minutes(start_time) + required_time), room_occupied, faculty_assigned, assignments)

                    # Recursively attempt to schedule the remaining subjects
                    if schedule_subjects(subjects[1:], faculty_preferences, room_list, assignments, room_occupied, faculty_assigned):
                        return True  # Found a valid schedule

                    # Backtrack: undo the assignment
                    unassign_slot(subject_id, faculty_id, room_id, day, start_time, minutes_to_time(time_to_minutes(start_time) + required_time), room_occupied, faculty_assigned, assignments)
                    print(f"Backtracking on subject {subject_id}, faculty {faculty_id}, room {room_id}, day {day}")

    return False  # No valid schedule found

# Database connection setup
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="facultyscheduling"
)
cursor = conn.cursor()

# Fetching data
cursor.execute("SELECT * FROM faculty")
faculty = cursor.fetchall()

cursor.execute("SELECT * FROM `subjectschedule` JOIN subject ON subjectschedule.subjectid=subject.id")
subjectschedule = cursor.fetchall()

cursor.execute("SELECT * FROM room WHERE departmentid=1")
room = cursor.fetchall()

cursor.execute("SELECT * FROM facultypreferences JOIN faculty ON faculty.id=facultypreferences.facultyid WHERE faculty.departmentid=1 OR faculty.departmentid=3")
facultypreference = cursor.fetchall()

# Preparing the data
assignments = {}
room_occupied = {}
faculty_assigned = {}

# Start the backtracking scheduling process
print("Starting the scheduling process...")
success = schedule_subjects(subjectschedule, facultypreference, room, assignments, room_occupied, faculty_assigned)

if success:
    print("Successfully scheduled all subjects!")
    for subject_id, assignment in assignments.items():
        print(f"Subject {subject_id}: Faculty {assignment[0]}, Room {assignment[1]}, Day {assignment[2]}, Time {assignment[3]}-{assignment[4]}")
else:
    print("Failed to find a valid schedule.")

# Commit changes and close the connection
conn.commit()
conn.close()
