from datetime import timedelta
import mysql.connector

def time_to_minutes(time_str):
    hours, minutes = map(int, time_str.split(':'))
    return hours * 60 + minutes

def minutes_to_time(minutes):
    hours = minutes // 60
    minutes = minutes % 60
    return f"{hours:02}:{minutes:02}"

def find_3_hour_slot(day_slots, room_slots):
    required_time = 180  # 3 hours in minutes

    for day in day_slots:
        day_start, day_end = map(time_to_minutes, day_slots[day])
        for room in room_slots:
            room_start, room_end = map(time_to_minutes, room)
            # Check if room slot can fit a 3-hour subject
            if room_end - room_start >= required_time:
                slot_start = max(day_start, room_start)
                slot_end = min(day_end, room_end)
                if slot_end - slot_start >= required_time:
                    return slot_start, slot_start + required_time
    
    return None

conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="facultyscheduling"
)

cursor = conn.cursor()

# Fetch room data with correct column names
cursor.execute("SELECT timestart, timeend FROM room WHERE departmentid=1")
room_data = cursor.fetchall()

# Fetch day slots for faculty preferences
cursor.execute("SELECT day, start_time, end_time FROM facultypreferences JOIN faculty ON faculty.id=facultypreferences.facultyid WHERE faculty.departmentid=1")
day_data = cursor.fetchall()

# Prepare day and room slots
day_slots = {}
room_slots = []

for day in day_data:
    day_number, start_time, end_time = day
    day_slots[day_number] = (start_time, end_time)

for room in room_data:
    room_start, room_end = room
    room_slots.append((room_start, room_end))

# Find a 3-hour slot
slot = find_3_hour_slot(day_slots, room_slots)

if slot:
    print(f"A 3-hour subject can fit from {minutes_to_time(slot[0])} to {minutes_to_time(slot[1])}.")
else:
    print("No 3-hour slot fits in the given days.")

cursor.close()
conn.close()
