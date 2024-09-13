import mysql.connector
from datetime import timedelta
import time as time2

def time_to_minutes(time_str):
    hours, minutes = map(int, time_str.split(':'))
    return hours * 60 + minutes

def minutes_to_time(minutes):
    hours = minutes // 60
    minutes = minutes % 60
    return f"{hours:02}:{minutes:02}"

def find_overlapping_slots(start1, end1, start2, end2, duration):
    start1_minutes = time_to_minutes(start1)
    end1_minutes = time_to_minutes(end1)
    start2_minutes = time_to_minutes(start2)
    end2_minutes = time_to_minutes(end2)

    overlapping_slots = []
    for valid_start in range(start1_minutes, end1_minutes - duration + 1, 30):
        valid_end = valid_start + duration
        if valid_start >= start2_minutes and valid_end <= end2_minutes:
            overlapping_slots.append((minutes_to_time(valid_start), minutes_to_time(valid_end)))
    return overlapping_slots

def can_fit_time2(starttime, endtime, duration):
    start_min = time_to_minutes(starttime)
    end_min = time_to_minutes(endtime)
    return end_min - start_min >= duration

def schedule_subject(subject, faculty_prefs, rooms, assignments, room_occupancy, assigned_subjects):
    subject_id, subject_name, units, subject_type, subject_faculty_id = subject
    required_time = 180 if subject_type == 'Lab' else 120 if units == 2 else 90
    meetings_per_week = 1 if subject_type == 'Lab' else 1 if units == 2 else 2
    required_gap_days = 0 if subject_type == 'Lab' or units == 2 else 3

    for faculty_id, prefs in faculty_prefs.items():
        if subject_faculty_id != faculty_id:
            continue
        for day, start_time, end_time in prefs:
            for room in rooms:
                room_id, room_type = room[0], room[2]
                if room_type != subject_type:
                    continue
                overlapping_slots = find_overlapping_slots(start_time, end_time, room[3], room[4], required_time)
                for start_slot, end_slot in overlapping_slots:
                    if can_fit_time2(start_slot, end_slot, required_time):
                        if assign_slot(subject_id, day, start_slot, end_slot, room_id, assignments, room_occupancy, assigned_subjects):
                            return True
    return False

def assign_slot(subject_id, day, start_slot, end_slot, room_id, assignments, room_occupancy, assigned_subjects):
    start_minutes = time_to_minutes(start_slot)
    end_minutes = time_to_minutes(end_slot)
    for time in range(start_minutes, end_minutes, 30):
        if room_occupancy.get(room_id, {}).get(day, {}).get(time, 'free') == 'occupied':
            return False
    for time in range(start_minutes, end_minutes, 30):
        if room_id not in room_occupancy:
            room_occupancy[room_id] = {}
        if day not in room_occupancy[room_id]:
            room_occupancy[room_id][day] = {}
        room_occupancy[room_id][day][time] = 'occupied'
    assignments[subject_id] = (start_minutes, end_minutes, day, room_id)
    assigned_subjects.add(subject_id)
    return True

def backtrack_schedule(subjects, faculty_prefs, rooms, assignments, room_occupancy, assigned_subjects):
    if not subjects:
        return True
    subject = subjects.pop(0)
    if schedule_subject(subject, faculty_prefs, rooms, assignments, room_occupancy, assigned_subjects):
        if backtrack_schedule(subjects, faculty_prefs, rooms, assignments, room_occupancy, assigned_subjects):
            return True
    subjects.insert(0, subject)
    return False

conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="facultyscheduling"
)
cursor = conn.cursor()

cursor.execute("SELECT * FROM faculty")
faculty = cursor.fetchall()

cursor.execute("SELECT * FROM `subjectschedule` JOIN subject ON subjectschedule.subjectid=subject.id")
subjectschedule = cursor.fetchall()

cursor.execute("SELECT * FROM room WHERE departmentid=1")
room = cursor.fetchall()

cursor.execute("SELECT * FROM facultypreferences JOIN faculty ON faculty.id=facultypreferences.facultyid WHERE faculty.departmentid=1 OR faculty.departmentid=3")
facultypreference = cursor.fetchall()

faculty_prefs = {}
for pref in facultypreference:
    faculty_id = pref[1]
    if faculty_id not in faculty_prefs:
        faculty_prefs[faculty_id] = []
    faculty_prefs[faculty_id].append((pref[2], pref[3], pref[4]))

assignments = {}
room_occupancy = {}
assigned_subjects = set()

subjects = subjectschedule
backtrack_schedule(subjects, faculty_prefs, room, assignments, room_occupancy, assigned_subjects)

for subject_id, assignment in assignments.items():
    day = assignment[2]
    start_time = minutes_to_time(assignment[0])
    end_time = minutes_to_time(assignment[1])
    room_id = assignment[3]

    cursor.execute("""
        UPDATE subjectschedule
        SET 
            day = %s,
            timestart = %s,
            timeend = %s,
            roomid = %s
        WHERE 
            id = %s
    """, (day, start_time, end_time, room_id, subject_id))
    conn.commit()

cursor.close()
conn.close()