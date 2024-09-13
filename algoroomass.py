import mysql.connector
from datetime import timedelta

def time_to_minutes(time_str):
    hours, minutes = map(int, time_str.split(':'))
    return hours * 60 + minutes

def minutes_to_time(minutes):
    hours = minutes // 60
    minutes = minutes % 60
    return f"{hours:02}:{minutes:02}"

def find_overlapping_slots(start1, end1, start2, end2, duration):
    """
    Find all overlapping slots of given duration between two time ranges.
    """
    start1_minutes = time_to_minutes(start1)
    end1_minutes = time_to_minutes(end1)
    start2_minutes = time_to_minutes(start2)
    end2_minutes = time_to_minutes(end2)

    overlapping_slots = []

    # Check every possible start time in 30-minute increments
    for valid_start in range(start1_minutes, end1_minutes - duration + 1, 30):
        valid_end = valid_start + duration
        
        # Ensure the slot is within both time ranges
        if valid_start >= start2_minutes and valid_end <= end2_minutes:
            if can_fit_time2(minutes_to_time(valid_start), minutes_to_time(valid_end), duration):
                overlapping_slots.append((minutes_to_time(valid_start), minutes_to_time(valid_end)))
                
    return overlapping_slots

def can_fit_time3(start1, end1, start2, end2, duration):
    start1_min = time_to_minutes(start1)
    end1_min = time_to_minutes(end1)
    start2_min = time_to_minutes(start2)
    end2_min = time_to_minutes(end2)

    # Find overlap between two time slots
    overlap_start = max(start1_min, start2_min)
    overlap_end = min(end1_min, end2_min)

    # Check if a duration can fit within the overlap
    fit_end = overlap_start + duration
    if overlap_end >= fit_end:
        return True, overlap_start, fit_end

    return False, None, None

def can_fit_time2(starttime, endtime, duration):
    start_min = time_to_minutes(starttime)
    end_min = time_to_minutes(endtime)
    if (end_min-start_min>=duration):
        return True
    return False






checked_pairs = set()

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


cursor.execute("SELECT * FROM `subjectschedule` JOIN subject ON subjectschedule.subjectid=subject.id WHERE subject.focus!='Minor'")
subjectschedule = cursor.fetchall()


cursor.execute("SELECT * FROM room WHERE departmentid=1")
room = cursor.fetchall()


cursor.execute("SELECT * FROM facultypreferences JOIN faculty ON faculty.id=facultypreferences.facultyid WHERE faculty.departmentid=1 OR faculty.departmentid=3")
facultypreference = cursor.fetchall()

# Group data by faculty and subjects

pairdays = {}
facultypairdaystime = {}
assigned_schedule = []
faculty_assigned_days = {}
room_schedule = {}  # Dictionary to track room usage by day
facultydaystimelec = {}
facultydaystimelab = {}
roomoccupied = {}
assignments={}

for subject in subjectschedule:
    
    subject_id, subject_name, units, subject_type, subjectfacultyid = subject[0], subject[12], subject[14], subject[16],  subject[5]
    print(f"Processing subject {subject_name} (ID: {subject_id}, Type: {subject_type}, Units: {units})")

    if subject_id is not assignments:
        assignments[subject_id] = {}

    if subject_type == 'Lec':
        if units == 3.0:
            required_time = 90  # 1.5 hours
            meetings_per_week = 2
            required_gap_days = 3  # 2-day gap between classes
        elif units == 2.0:
            required_time = 120  # 2 hours
            meetings_per_week = 1
            required_gap_days = 0  # No gap needed
            print("2.0")
    elif subject_type == 'Lab':
        required_time = 180  # 3 hours
        meetings_per_week = 1
        required_gap_days = 0  # No gap needed
    else:
        print("skipped")
        continue  # Skip unknown subject types
    
    
    for pref in facultypreference:
        facultyid, day, start_time, end_time = pref[1], pref[2], pref[3], pref[4]
        if subjectfacultyid!=facultyid:
            print(subjectfacultyid, facultyid)
            print("no faculty for " , subject_id)
            continue
        else:
            print("found faculty", subjectfacultyid, "for " ,subject_id)
        start_minutes = time_to_minutes(start_time)
        start_hours = minutes_to_time(start_minutes)
        end_minutes = time_to_minutes(end_time)


        '''print(f" Faculty {facultyid} prefers Day {day} from {start_time} to {end_time}")'''

        if units == 3:
            for pref2 in facultypreference:
                day2, start_time2, end_time2 = pref2[2], pref2[3], pref2[4]
                if facultyid not in facultypairdaystime:
                    facultypairdaystime[facultyid] = []

                if day - 3 == day2:
                    # Find all valid overlapping time slots of duration 90 minutes
                    valid_time_slots = find_overlapping_slots(start_time, end_time, start_time2, end_time2, 90)

                    for start_time_overlap, end_time_overlap in valid_time_slots:
                        
                        facultypairdaystime[facultyid].append((day2, day, start_time_overlap, end_time_overlap))
                        print(f"  Valid pair: Day {day} and Day {day2} with time slot start {start_time_overlap} end at {end_time_overlap}")
                                    
        elif units==2:
            
            print("dhhdhdhhdhdhhdhdhhdhdhhdhdhhdhdhhdhdhhdhdhhdhdhhdhdhhdhdhhdhdhhdhdhhdh")
            numberfit=int((time_to_minutes(end_time)-time_to_minutes(start_time))/120)

            if (facultyid) not in facultydaystimelec:
                facultydaystimelec[facultyid] = []

            print(day, numberfit)
            if (numberfit>= 1):
                
                for validtime in range(start_minutes, end_minutes, 30):
                    duration = 90  # Duration for the slot
                    end_of_slot = validtime + duration
                    if end_of_slot <= end_minutes:
                        '''print(f"   Time start: {minutes_to_time(validtime)}, Time end: {minutes_to_time(end_of_slot)}")'''
                        
                        if can_fit_time2(minutes_to_time(validtime), minutes_to_time(end_of_slot), duration):
                            '''print(f"   Appending: {day}, {minutes_to_time(validtime)}, {minutes_to_time(end_of_slot)}")'''
                            facultydaystimelec[facultyid].append((day, minutes_to_time(validtime), minutes_to_time(end_of_slot)))

        elif(units==1):   
            numberfit=int((time_to_minutes(end_time)-time_to_minutes(start_time))/180)

            if facultyid not in facultydaystimelab:
                facultydaystimelab[facultyid] = []

            print(day, numberfit)
            if (numberfit>= 1):
                
                for validtime in range(start_minutes, end_minutes, 30):
                    duration = 180  # Duration for the slot
                    end_of_slot = validtime + duration
                    
                    if end_of_slot <= end_minutes:
                        '''print(f"   Time start: {minutes_to_time(validtime)}, Time end: {minutes_to_time(end_of_slot)}")'''
                        
                        if can_fit_time2(minutes_to_time(validtime), minutes_to_time(end_of_slot), duration):
                            '''print(f"   Appending: {day}, {minutes_to_time(validtime)}, {minutes_to_time(end_of_slot)}")'''
                            facultydaystimelab[facultyid].append((day, minutes_to_time(validtime), minutes_to_time(end_of_slot)))

                
                

    # Check room availability
    for rm in room:
        room_id, room_name, room_type, room_start, room_end = rm[0], rm[1], rm[2], rm[3], rm[4]
        if room_type != subject_type:
            continue  # Skip rooms not matching subject type

        room_start_minutes = time_to_minutes(room_start)
        room_end_minutes = time_to_minutes(room_end)

        print(f"    Checking room {room_name} (ID: {room_id}, Type: {room_type}) availability")
        if (units==3.0):
            

            for faculty_id, slots in facultypairdaystime.items():
                for day1, day2, start_time, end_time in slots:
                    day1free = True  # Assume all slots are occupied unless proven otherwise
                    day2free = True

                    start_minutes = time_to_minutes(start_time)
                    end_minutes = time_to_minutes(end_time)
                    
                    print(f"    Faculty ID {faculty_id} - Day1 {day1} - day2 {day2}:")
                    print(f"    Start Time in Minutes: {start_minutes}")
                    print(f"    End Time in Minutes: {end_minutes}")

                    for time_slot in range(start_minutes, end_minutes, 30):

                        for time in range(time_slot, time_slot + 90, 30):
                            if room_id not in roomoccupied:
                                roomoccupied[room_id] = {}

                            if day1 not in roomoccupied[room_id]:
                                roomoccupied[room_id][day1] = {}

                            if time not in roomoccupied[room_id][day1]:
                                roomoccupied[room_id][day1][time] = 'free'
                                print("true1", time)
                            if roomoccupied[room_id][day1][time] == 'occupied':
                                print("false1", time)
                                day1free = False
                                break  # Exit the loop early if any slot is occupied

                            # Check if time slot is occupied for the second day
                            if day2 not in roomoccupied[room_id]:
                                roomoccupied[room_id][day2] = {}

                            if time not in roomoccupied[room_id][day2]:
                                roomoccupied[room_id][day2][time] = 'free'
                                print("true2", time)
                            if roomoccupied[room_id][day2][time] == 'occupied':
                                print("false2", time)
                                day2free = False
                                break  # Exit the loop early if any slot is occupied

                        if not day1free:
                            break  # Exit the outer loop if an unoccupied slot was found

                    # Print the result based on availability
                    if day1free and day2free:
                        print(f"     Assigning subject {subject_id} to this day {day} w/ time slot starting at {minutes_to_time(start_minutes)} upto {minutes_to_time(start_minutes+90)}")
                        
                        for time in range(start_minutes, start_minutes + 90, 30):
                            roomoccupied[room_id][day1][time] = 'occupied'
                            roomoccupied[room_id][day2][time] = 'occupied'
                        assignments[subject_id] = (start_minutes, start_minutes+90)
                        assignments[subject_id] = (start_minutes, start_minutes+90,(day1,day2), room_id)
                        break
                    else:
                        print(f"      No suitable time slot found for subject {subject_id}")
                
        elif(units==2.0):
             
            for faculty_id, slots in facultydaystimelec.items():
                for day, start_time, end_time in slots:
                    dayfree = True
                    start_minutes = time_to_minutes(start_time)
                    end_minutes = time_to_minutes(end_time)
                    
                    print(f"    Faculty ID {faculty_id} - Day1 {day}")
                    print(f"    Start Time in Minutes: {start_minutes}")
                    print(f"    End Time in Minutes: {end_minutes}")

                    for time_slot in range(start_minutes, end_minutes, 30):
                        if room_id not in roomoccupied:
                            roomoccupied[room_id] = {}

                        if day not in roomoccupied[room_id]:
                            roomoccupied[room_id][day] = {}

                        if time_slot not in roomoccupied[room_id][day]:
                            roomoccupied[room_id][day][time_slot] = 'free'
                            print("true", time_slot)
                        if roomoccupied[room_id][day][time_slot] == 'occupied':
                            print("false", time_slot)
                            dayfree = False
                            break

                    # Print the result based on availability
                    if dayfree:
                        print(f"      Assigning subject {subject_id} to this day {day} w/ time slot starting at {minutes_to_time(start_minutes)} upto {minutes_to_time(end_minutes)}")
                        for time in range(start_minutes, end_minutes, 30):
                            roomoccupied[room_id][day][time] = 'occupied'
                        assignments[subject_id] = (start_minutes, start_minutes+120,day, room_id)
                        break
                    else:
                        print(f"      No suitable time slot found for subject {subject_id}")
                        
               
        elif(units==1.0):
            
            for faculty_id, slots in facultydaystimelab.items():
                for day, start_timelab, end_timelab in slots:
                    daylabfree = True 
                    start_minuteslab = time_to_minutes(start_timelab)
                    end_minuteslab = time_to_minutes(end_timelab)
                    
                    print(f"    Faculty ID {faculty_id} - Day1 {day}")
                    print(f"    Start Time in Minutes: {start_minuteslab}")
                    print(f"    End Time in Minutes: {end_minuteslab}")

                    for time_slot in range(start_minuteslab, end_minuteslab, 30):
                        if room_id not in roomoccupied:
                            roomoccupied[room_id] = {}

                        if day not in roomoccupied[room_id]:
                            roomoccupied[room_id][day] = {}

                        if time_slot not in roomoccupied[room_id][day]:
                            roomoccupied[room_id][day][time_slot] = 'free'
                            print("true", time_slot)
                            print()

                        if roomoccupied[room_id][day][time_slot] == 'occupied':
                            daylabfree = False
                            print("false", time_slot)
                            break

                    # Print the result based on availability
                    if daylabfree:
                        print(f"      Assigning subject {subject_id} to this day {day} w/ time slot starting at {minutes_to_time(start_minuteslab)} upto {minutes_to_time(end_minuteslab+90)}")
                        for time in range(start_minuteslab, end_minuteslab, 30):
                            roomoccupied[room_id][day][time] = 'occupied'
                        assignments[subject_id] = (start_minuteslab, start_minuteslab+180,day, room_id)
                        break
                    else:
                        print(f"      No suitable time slot found for subject {subject_id}")
            
                daylabfree = True  # Reset for the next faculty ID

                                        

                        
                    
            '''available_slots={}
            # Find a time slot within faculty preference and room availability
            for time in range(max(start_minutes, room_start_minutes), min(end_minutes, room_end_minutes) - required_time + 1, 15):
                end_time_candidate = time + required_time

                # Check if the room is already booked during this time
                conflict = False
                for scheduled_time in room_schedule[day][room_id]:
                    if not (end_time_candidate <= scheduled_time[0] or time >= scheduled_time[1]):
                        conflict = True
                        break

                if not conflict:
                    available_slots.append((day, time, end_time_candidate, room_id, room_name))
                    print(f"      Considering time slot {minutes_to_time(time)} to {minutes_to_time(end_time_candidate)} on Day {day}")

    if len(available_slots) >= meetings_per_week:
        assigned_slots = []
        used_days = set()
        for i in range(meetings_per_week):
            slot = available_slots[i]
            start_time = minutes_to_time(slot[1])
            end_time = minutes_to_time(slot[2])
            assigned_slots.append((subject_id, faculty_id, slot[3], slot[0], start_time, end_time))
            print(f"    Assigned subject {subject_name} to faculty {faculty_name} in room {slot[4]} on Day {slot[0]} from {start_time} to {end_time}")

            # Mark the day as used for this faculty member
            if faculty_id not in faculty_assigned_days:
                faculty_assigned_days[faculty_id] = []
            faculty_assigned_days[faculty_id].append(slot[0])

            # Record the room usage to avoid conflicts
            room_schedule[slot[0]][slot[3]].append((slot[1], slot[2]))

        # Adjust for the required gap days
        if required_gap_days > 0:
            day1 = assigned_slots[0][3]
            for j in range(1, meetings_per_week):
                next_day = (day1 + required_gap_days) % 7
                if next_day == 0:
                    next_day = 7
                for slot in available_slots:
                    if slot[0] == next_day:
                        assigned_slots[j] = (subject_id, faculty_id, slot[3], slot[0], minutes_to_time(slot[1]), minutes_to_time(slot[2]))
                        break

        assigned_schedule.append(assigned_slots)
        break  # Stop once a subject is assigned'''
print(facultydaystimelec)
print(facultydaystimelab)
print("occupiennnnnnnnnnnnnnnnnnnnnnnnnnd",roomoccupied)

def days_to_string(day_tuple):
    day_map = {
        1: 'M',  # Monday
        2: 'T',  # Tuesday
        3: 'W',  # Wednesday
        4: 'Th', # Thursday
        5: 'F',  # Friday
        6: 'S',  # Saturday
        7: 'Su'  # Sunday
    }
    if isinstance(day_tuple, tuple):
        return ''.join(day_map.get(day, '') for day in day_tuple)
    return day_map.get(day_tuple, '')

def minutes_to_time(minutes):
    hours = minutes // 60
    mins = minutes % 60
    return f"{hours:02}:{mins:02}"


for subject_id, assignment in assignments.items():
    if len(assignment) < 4:
        print(f"Skipping subject {subject_id} due to incomplete assignment data: {assignment}")
        continue

    day_tuple = assignment[2]  # Extract the day or days
    day_combined = days_to_string(day_tuple)  # Convert to string

    start_time = assignment[0]  # Start time in minutes
    end_time = assignment[1]    # End time in minutes
    room_id = assignment[3]     # Room ID

    # Convert start_time and end_time from minutes to HH:MM format
    start_time_formatted = minutes_to_time(start_time)
    end_time_formatted = minutes_to_time(end_time)

    # Debugging print statements
    print(f"Subject ID: {subject_id}")
    print(f"Day Combined: {day_combined}")
    print(f"Start Time: {start_time_formatted}")
    print(f"End Time: {end_time_formatted}")
    print(f"Room ID: {room_id}")

    # Update the database with the new values
    cursor.execute("""
        UPDATE subjectschedule
        SET 
            day = %s,
            timestart = %s,
            timeend = %s,
            roomid = %s
        WHERE 
            id = %s
    """, (day_combined, start_time_formatted, end_time_formatted, room_id, subject_id))
    conn.commit()

cursor.close()
conn.close()
