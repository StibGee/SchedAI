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


cursor.execute("SELECT * FROM `subjectschedule` JOIN subject ON subjectschedule.subjectid=subject.id")
subjectschedule = cursor.fetchall()


cursor.execute("SELECT * FROM room WHERE departmentid=1")
room = cursor.fetchall()


cursor.execute("SELECT * FROM facultypreferences JOIN faculty ON faculty.id=facultypreferences.facultyid WHERE (faculty.departmentid=1 OR faculty.departmentid=3)")
facultypreference = cursor.fetchall()

# Group data by faculty and subjects
try:
    # Disable foreign key checks
    cursor.execute("SET FOREIGN_KEY_CHECKS = 0;")
    print("Foreign key checks disabled.")

    # Update the `subjectschedule` table
    cursor.execute("UPDATE `subjectschedule` SET `timestart` = NULL, `timeend` = NULL,`day` = NULL,  `roomid` = NULL;")
    conn.commit()  # Commit the changes
    print("Columns updated to NULL.")

finally:
    # Re-enable foreign key checks
    cursor.execute("SET FOREIGN_KEY_CHECKS = 1;")


pairdays = {}
facultypairdaystime = {}
assigned_schedule = []
faculty_assigned_days = {}
room_schedule = {}  # Dictionary to track room usage by day
facultydaystimelec = {}
facultydaystimelab = {}
roomoccupied = {}
assignments={}
assignedsubjects = set() 

for pref in facultypreference:
    facultyid, day, start_time, end_time = pref[1], pref[2], pref[3], pref[4]
    
    start_minutes = time_to_minutes(start_time)
    start_hours = minutes_to_time(start_minutes)
    end_minutes = time_to_minutes(end_time)


    '''print(f" Faculty {facultyid} prefers Day {day} from {start_time} to {end_time}")'''

    print("for 3.0")
    for pref2 in facultypreference:
        day2, start_time2, end_time2 = pref2[2], pref2[3], pref2[4]
        if facultyid not in facultypairdaystime:
            facultypairdaystime[facultyid] = []

        if day - 3 == day2:
            valid_time_slots = find_overlapping_slots(start_time, end_time, start_time2, end_time2, 90)

            for start_time_overlap, end_time_overlap in valid_time_slots:
                
                facultypairdaystime[facultyid].append((day2, day, start_time_overlap, end_time_overlap))
                '''print(f"  Valid pair: Day {day} and Day {day2} with time slot start {start_time_overlap} end at {end_time_overlap}")'''
                            

    print("for 2.0")
    numberfit=int((time_to_minutes(end_time)-time_to_minutes(start_time))/120)

    if (facultyid) not in facultydaystimelec:
        facultydaystimelec[facultyid] = []

    if (numberfit>= 1):
        
        for validtime in range(start_minutes, end_minutes, 30):
            duration = 90
            end_of_slot = validtime + duration
            if end_of_slot <= end_minutes:
                '''print(f"   Time start: {minutes_to_time(validtime)}, Time end: {minutes_to_time(end_of_slot)}")'''
                
                if can_fit_time2(minutes_to_time(validtime), minutes_to_time(end_of_slot), duration):
                    '''print(f"   Appending: {day}, {minutes_to_time(validtime)}, {minutes_to_time(end_of_slot)}")'''
                    facultydaystimelec[facultyid].append((day, minutes_to_time(validtime), minutes_to_time(end_of_slot)))

    print("for 1.0")
    numberfit=int((time_to_minutes(end_time)-time_to_minutes(start_time))/180)

    if facultyid not in facultydaystimelab:
        facultydaystimelab[facultyid] = []

    if (numberfit>=1):
        
        for validtime in range(start_minutes, end_minutes, 30):
            duration = 180
            end_of_slot = validtime + duration
            
            if end_of_slot <= end_minutes:
                print(f"   Time start: {minutes_to_time(validtime)}, Time end: {minutes_to_time(end_of_slot)}")
                
                if can_fit_time2(minutes_to_time(validtime), minutes_to_time(end_of_slot), duration):
                    print(f"   Appending: {day}, {minutes_to_time(validtime)}, {minutes_to_time(end_of_slot)}")
                    facultydaystimelab[facultyid].append((day, minutes_to_time(validtime), minutes_to_time(end_of_slot)))
                            
for subject in subjectschedule:
    print(subject[18])
    if subject[18]!='Major':
        subject_id = subject[0]
        print("subject[18]")
        cursor.execute("UPDATE subjectschedule SET roomid = 6 WHERE id = %s", (subject_id,))
        conn.commit()  
        continue
        

    subject_id, subject_name, units, subject_type, subjectfacultyid = subject[0], subject[12], subject[14], subject[16],  subject[5]
    print(f"Processing subject {subject_name} (ID: {subject_id}, Type: {subject_type}, Units: {units})")
    
    if subject_id is not assignments:
        assignments[subject_id] = {}

    if subject_type == 'Lec':
        if units == 3.0:
            required_time = 90
            meetings_per_week = 2
            required_gap_days = 3
        elif units == 2.0:
            required_time = 120
            meetings_per_week = 1
            required_gap_days = 0 
           
    elif subject_type == 'Lab':
        required_time = 180 
        meetings_per_week = 1
        required_gap_days = 0
    else:
        print("skipped")
        continue
    
    
    

                
                

    for rm in room:
        room_id, room_name, room_type, room_start, room_end = rm[0], rm[1], rm[2], rm[3], rm[4]
        if room_type != subject_type:
            continue

        room_start_minutes = time_to_minutes(room_start)
        room_end_minutes = time_to_minutes(room_end)

        print(f"    Checking room {room_name} (ID: {room_id}, Type: {room_type}) availability")
        if (units==3.0):
            

            for faculty_id, slots in facultypairdaystime.items():
                for day1, day2, start_time, end_time in slots:
                    if subject_id in assignedsubjects:
                        continue 
                    day1free = True
                    day2free = True
                    start_minutes = time_to_minutes(start_time)
                    end_minutes = time_to_minutes(end_time)
                    
                    for time_slot in range(start_minutes, end_minutes, 30):
                        for time in range(time_slot, time_slot + 90, 30):
                            if room_id not in roomoccupied:
                                roomoccupied[room_id] = {}

                            if day1 not in roomoccupied[room_id]:
                                roomoccupied[room_id][day1] = {}

                            if time not in roomoccupied[room_id][day1]:
                                roomoccupied[room_id][day1][time] = 'free'
                                

                            if roomoccupied[room_id][day1][time] == 'occupied':
                                day1free = False
                                break

                            if day2 not in roomoccupied[room_id]:
                                roomoccupied[room_id][day2] = {}
                                
                            if time not in roomoccupied[room_id][day2]:
                                roomoccupied[room_id][day2][time] = 'free'

                            if roomoccupied[room_id][day2][time] == 'occupied':
                                day2free = False
                                break

                        if not day1free:
                            break

                    if day1free and day2free:
                        print(f"     Assigning subject {subject_id} to days {day1} and {day2} with time slot starting at {minutes_to_time(start_minutes)} up to {minutes_to_time(start_minutes + 90)}")
                        
                        for time in range(start_minutes, start_minutes + 90, 30):
                            if room_id not in roomoccupied:
                                roomoccupied[room_id] = {}
                            if day1 not in roomoccupied[room_id]:
                                roomoccupied[room_id][day1] = {}
                            if day2 not in roomoccupied[room_id]:
                                roomoccupied[room_id][day2] = {}

                            roomoccupied[room_id][day1][time] = 'occupied'
                            roomoccupied[room_id][day2][time] = 'occupied'
                            print("Occupying", minutes_to_time(time))

                        assignments[subject_id] = (start_minutes, start_minutes + 90, (day1, day2), room_id)
                        assignedsubjects.add(subject_id)
                        break 
                    else:
                        '''print(f"No suitable time slot found for subject {subject_id} on days {day1} and {day2}")'''
                        
                
        elif(units==2.0):
             
            for faculty_id, slots in facultydaystimelec.items():
                for day, start_time, end_time in slots:
                    if subject_id in assignedsubjects:
                        continue
                    dayfree = True
                    start_minutes = time_to_minutes(start_time)
                    end_minutes = time_to_minutes(end_time)
                    
                    '''print(f"    Faculty ID {faculty_id} - Day {day}")
                    print(f"    Start Time in Minutes: {start_minutes}")
                    print(f"    End Time in Minutes: {end_minutes}")'''

                    for time_slot in range(start_minutes, end_minutes, 30):
                        if room_id not in roomoccupied:
                            roomoccupied[room_id] = {}

                        if day not in roomoccupied[room_id]:
                            roomoccupied[room_id][day] = {}

                        if time_slot not in roomoccupied[room_id][day]:
                            roomoccupied[room_id][day][time_slot] = 'free'
                            
                        if roomoccupied[room_id][day][time_slot] == 'occupied':
                           
                            dayfree = False
                            break

                    if dayfree:
                        print(f"      Assigning subject {subject_id} to this day {day} w/ time slot starting at {minutes_to_time(start_minutes)} upto {minutes_to_time(end_minutes)}")
                        for time in range(start_minutes, end_minutes, 30):
                            roomoccupied[room_id][day][time] = 'occupied'
                            print("occupying", minutes_to_time(time))
                        assignments[subject_id] = (start_minutes, start_minutes+120, day, room_id)
                        assignedsubjects.add(subject_id)
                        break
                    else:
                        '''print(f"      No  suitable time slot found for subject {subject_id}")'''
                        
               
        elif(units==1.0):
             
            for faculty_id, slotslab in facultydaystimelab.items():
                daylabfree = True
                
                for daylab, start_timelab, end_timelab in slotslab:
                    if subject_id in assignedsubjects:
                        continue
                    
                    start_minuteslab = time_to_minutes(start_timelab)
                    end_minuteslab = time_to_minutes(end_timelab)
                    
                    # Initialize roomoccupied structure if not present
                    if room_id not in roomoccupied:
                        roomoccupied[room_id] = {}
                    if daylab not in roomoccupied[room_id]:
                        roomoccupied[room_id][daylab] = {}
                    
                    # Check if time slots are free
                    for time_slotlab in range(start_minuteslab, end_minuteslab, 30):
                        if time_slotlab not in roomoccupied[room_id][daylab]:
                            roomoccupied[room_id][daylab][time_slotlab] = 'free'
                        
                        if roomoccupied[room_id][daylab][time_slotlab] == 'occupied':
                            daylabfree = False
                            break
                    
                    if daylabfree:
                        print(f"      Assigning subject {subject_id} to this daylab {daylab} w/ time slot starting at {minutes_to_time(start_minuteslab)} upto {minutes_to_time(end_minuteslab)}")
                        for time in range(start_minuteslab, end_minuteslab, 30):
                            roomoccupied[room_id][daylab][time_slotlab] = 'occupied'
                            print("occupying", minutes_to_time(time_slotlab))
                        assignments[subject_id] = (start_minuteslab, start_minuteslab + 180, daylab, room_id)
                        assignedsubjects.add(subject_id)
                        break
                    else:
                        '''print(f"      No suitable time slot found for subject {subject_id}")'''

            
              
                                        
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
    if len(assignment) != 4:
        print(f"subject {subject_id} incomplete assignment data: {assignment}")
        continue

        
        

    day_tuple = assignment[2] 
    day_combined = days_to_string(day_tuple) 

    start_time = assignment[0]  
    end_time = assignment[1]   
    room_id = assignment[3]  

    start_time_formatted = minutes_to_time(start_time)
    end_time_formatted = minutes_to_time(end_time)

    print(f"Subject ID: {subject_id}")
    print(f"Day Combined: {day_combined}")
    print(f"Start Time: {start_time_formatted}")
    print(f"End Time: {end_time_formatted}")
    print(f"Room ID: {room_id}")

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
print(facultydaystimelab)

cursor.close()
conn.close()


'''Timeslot room'''
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
    """
    Find all overlapping slots of given duration between two time ranges.
    """
    start1_minutes = time_to_minutes(start1)
    end1_minutes = time_to_minutes(end1)
    start2_minutes = time_to_minutes(start2)
    end2_minutes = time_to_minutes(end2)

    overlapping_slots = []

    
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

cursor.execute("SELECT * FROM `subjectschedule` JOIN subject ON subjectschedule.subjectid=subject.id ORDER BY subject.unit DESC")
subjectschedule = cursor.fetchall()


cursor.execute("SELECT * FROM room WHERE departmentid=1")
room = cursor.fetchall()


cursor.execute("SELECT * FROM facultypreferences JOIN faculty ON faculty.id=facultypreferences.facultyid WHERE (faculty.departmentid=1 OR faculty.departmentid=3)")
facultypreference = cursor.fetchall()

# Group data by faculty and subjects
try:
    # Disable foreign key checks
    cursor.execute("SET FOREIGN_KEY_CHECKS = 0;")
    print("Foreign key checks disabled.")

    # Update the `subjectschedule` table
    cursor.execute("UPDATE `subjectschedule` SET `timestart` = NULL, `timeend` = NULL,`day` = NULL,  `roomid` = NULL;")
    conn.commit()  # Commit the changes
    print("Columns updated to NULL.")

finally:
    # Re-enable foreign key checks
    cursor.execute("SET FOREIGN_KEY_CHECKS = 1;")


pairdays = {}
facultypairdaystime = {}
facultyassignmentcounter={}
assigned_schedule = []
faculty_assigned_days = {}
room_schedule = {}  # Dictionary to track room usage by day
facultydaystimelec = {}
facultydaystimelab = {}
roomoccupied = {}
assignments={}
assignedsubjects = set()
facultyoccupied={} 

for pref in facultypreference:
    facultyid, day, start_time, end_time = pref[1], pref[2], pref[3], pref[4]
    
    start_minutes = time_to_minutes(start_time)
    start_hours = minutes_to_time(start_minutes)
    end_minutes = time_to_minutes(end_time)


    print(f" Faculty {facultyid} prefers Day {day} from {start_time} to {end_time}")

    print("for 3.0")
    for pref2 in facultypreference:
        day2, start_time2, end_time2 = pref2[2], pref2[3], pref2[4]
        if facultyid not in facultypairdaystime:
            facultypairdaystime[facultyid] = []

        if day + 3 == day2:
            valid_time_slots = find_overlapping_slots(start_time, end_time, start_time2, end_time2, 90)

            for start_time_overlap, end_time_overlap in valid_time_slots:
                
                facultypairdaystime[facultyid].append((day, day2, start_time_overlap, end_time_overlap))
                print(f"  Valid pair: Day {day2} and Day {day} with time slot start {start_time_overlap} end at {end_time_overlap}")
                            

    print("for 2.0")
    numberfit=int((time_to_minutes(end_time)-time_to_minutes(start_time))/120)

    if (facultyid) not in facultydaystimelec:
        facultydaystimelec[facultyid] = []

    if (numberfit>= 1):
        
        for validtime in range(start_minutes, end_minutes, 30):
            duration = 90
            end_of_slot = validtime + duration
            if end_of_slot <= end_minutes:
                '''print(f"   Time start: {minutes_to_time(validtime)}, Time end: {minutes_to_time(end_of_slot)}")'''
                
                if can_fit_time2(minutes_to_time(validtime), minutes_to_time(end_of_slot), duration):
                    '''print(f"   Appending: {day}, {minutes_to_time(validtime)}, {minutes_to_time(end_of_slot)}")'''
                    facultydaystimelec[facultyid].append((day, minutes_to_time(validtime), minutes_to_time(end_of_slot)))

    print("for 1.0")
    numberfit=int((time_to_minutes(end_time)-time_to_minutes(start_time))/180)

    if facultyid not in facultydaystimelab:
        facultydaystimelab[facultyid] = []

    if (numberfit>=1):
        
        for validtime in range(start_minutes, end_minutes, 30):
            duration = 180
            end_of_slot = validtime + duration
            
            if end_of_slot <= end_minutes:
                '''print(f"   Time start: {minutes_to_time(validtime)}, Time end: {minutes_to_time(end_of_slot)}")'''
                
                if can_fit_time2(minutes_to_time(validtime), minutes_to_time(end_of_slot), duration):
                    '''print(f"   Appending: {day}, {minutes_to_time(validtime)}, {minutes_to_time(end_of_slot)}")'''
                    facultydaystimelab[facultyid].append((day, minutes_to_time(validtime), minutes_to_time(end_of_slot)))
                            
for subject in subjectschedule:
    print(subject[18])
    if subject[18]!='Major':
        subject_id = subject[0]
        print("subject[18]")
        cursor.execute("UPDATE subjectschedule SET roomid = 6 WHERE id = %s", (subject_id,))
        conn.commit()  
        continue
        

    subject_id, subject_name, units, subject_type, subjectfacultyid = subject[0], subject[12], subject[14], subject[16],  subject[5]
    print(f"Processing subject {subject_name} (ID: {subject_id}, Type: {subject_type}, Units: {units})")
    
    if subject_id is not assignments:
        assignments[subject_id] = {}

    if subject_type == 'Lec':
        if units == 3.0:
            required_time = 90
            meetings_per_week = 2
            required_gap_days = 3
        elif units == 2.0:
            required_time = 120
            meetings_per_week = 1
            required_gap_days = 0 
           
    elif subject_type == 'Lab':
        required_time = 180 
        meetings_per_week = 1
        required_gap_days = 0
    else:
        print("skipped")
        continue
    
    
    

                
                

    for rm in room:
        room_id, room_name, room_type, room_start, room_end = rm[0], rm[1], rm[2], rm[3], rm[4]
        if room_type != subject_type:
            continue

        room_start_minutes = time_to_minutes(room_start)
        room_end_minutes = time_to_minutes(room_end)

        print(f"Trying subject {subject_id} in room {room_name} (ID: {room_id}, Type: {room_type}) availability")
        if (units==3.0):
            for faculty_idpair, slots in facultypairdaystime.items():
                facultyfree=True
                facultyday1free=True
                facultyday2free=True

                if faculty_idpair not in facultyassignmentcounter:
                    facultyassignmentcounter[faculty_idpair] = {}

                if subjectfacultyid!=faculty_idpair:
                    continue

                for day1, day2, start_time, end_time in slots:
                    if day1 not in facultyassignmentcounter[faculty_idpair]:
                        facultyassignmentcounter[faculty_idpair][day1] = 0

                    if day2 not in facultyassignmentcounter[faculty_idpair]:
                        facultyassignmentcounter[faculty_idpair][day2] = 0

                    if facultyassignmentcounter[faculty_idpair][day1]>=2:
                        continue
                    if facultyassignmentcounter[faculty_idpair][day2]>=2:
                        continue
                    

                    if faculty_idpair==1:
                        print (day1,day2)
                    if subject_id in assignedsubjects:
                        continue 
                    day1free = True
                    day2free = True
                    start_minutes = time_to_minutes(start_time)
                    end_minutes = time_to_minutes(end_time)
                    
                    for time_slot in range(start_minutes, end_minutes, 30):
                        for time in range(time_slot, time_slot + 90, 30):
                            # Room availability checks
                            if room_id not in roomoccupied:
                                roomoccupied[room_id] = {}

                            if day1 not in roomoccupied[room_id]:
                                roomoccupied[room_id][day1] = {}

                            if time not in roomoccupied[room_id][day1]:
                                roomoccupied[room_id][day1][time] = 'free'
                                day1free = True
                            if roomoccupied[room_id][day1][time] == 'occupied':
                                
                                day1free = False
                                break

                            if day2 not in roomoccupied[room_id]:
                                roomoccupied[room_id][day2] = {}

                            if time not in roomoccupied[room_id][day2]:
                                roomoccupied[room_id][day2][time] = 'free'
                                day2free = True
                            if roomoccupied[room_id][day2][time] == 'occupied':
                                day2free = False
                                break

                            # Faculty availability checks
                            if subjectfacultyid not in facultyoccupied:
                                facultyoccupied[faculty_idpair] = {}

                            if day1 not in facultyoccupied[subjectfacultyid]:
                                facultyoccupied[faculty_idpair][day1] = {}

                            if time not in facultyoccupied[subjectfacultyid][day1]:
                                facultyoccupied[faculty_idpair][day1][time] = 'free'
                                facultyday1free = True
                            if facultyoccupied[faculty_idpair][day1][time] == 'occupied':
                                facultyday1free = False
                                break

                            if day2 not in facultyoccupied[subjectfacultyid]:
                                facultyoccupied[faculty_idpair][day2] = {}

                            if time not in facultyoccupied[subjectfacultyid][day2]:
                                facultyoccupied[faculty_idpair][day2][time] = 'free'

                            if facultyoccupied[faculty_idpair][day2][time] == 'occupied':
                                facultyday2free = False
                                break

                        if not day1free:
                            break

                    if day1free and day2free and facultyday1free and facultyday2free:
                        print(f"     Assigning subject {subject_id} to days {day1} and {day2} with time slot starting at {minutes_to_time(start_minutes)} up to {minutes_to_time(start_minutes + 90)}")
                        
                        for time in range(start_minutes, start_minutes + 90, 30):
                            if room_id not in roomoccupied:
                                roomoccupied[room_id] = {}
                            if day1 not in roomoccupied[room_id]:
                                roomoccupied[room_id][day1] = {}
                            if day2 not in roomoccupied[room_id]:
                                roomoccupied[room_id][day2] = {}

                            roomoccupied[room_id][day1][time] = 'occupied'
                            roomoccupied[room_id][day2][time] = 'occupied'
                            facultyoccupied[faculty_idpair][day2][time] = 'occupied'
                            facultyoccupied[faculty_idpair][day1][time] = 'occupied'
                            '''print("Occupying", minutes_to_time(time))'''

                        facultyassignmentcounter[faculty_idpair][day1] =facultyassignmentcounter[faculty_idpair][day1]+1
                        facultyassignmentcounter[faculty_idpair][day2] =facultyassignmentcounter[faculty_idpair][day2]+1
                        assignments[subject_id] = (start_minutes, start_minutes + 90, (day1, day2), room_id)
                        assignedsubjects.add(subject_id)
                        break 
                    else:
                        '''print(f"No suitable time slot found for subject {subject_id} on days {day1} and {day2}")'''
                        
                
        elif(units==2.0):
             
            for faculty_idlec2, slots in facultydaystimelec.items():
                facultyfree=True 
                    
                if faculty_idlec2 not in facultyassignmentcounter:
                    facultyassignmentcounter[faculty_idlec2] = {}

                if subjectfacultyid!=faculty_idlec2:
                    continue

                for daylec2, start_time, end_time in slots:
                    if daylec2 not in facultyassignmentcounter[faculty_idlec2]:
                        facultyassignmentcounter[faculty_idlec2][daylec2] = 0

                    if facultyassignmentcounter[faculty_idlec2][daylec2] >= 2:
                        print("faculty day full")
                        continue
                    else:
                        print(f"huuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu {facultyassignmentcounter[faculty_idlec2][daylec2]}")

                    if subject_id in assignedsubjects:
                        continue
                    dayfree = True
                    start_minutes = time_to_minutes(start_time)
                    end_minutes = time_to_minutes(end_time)
                    
                    '''print(f"    Faculty ID {faculty_id} - Day {day}")
                    print(f"    Start Time in Minutes: {start_minutes}")
                    print(f"    End Time in Minutes: {end_minutes}")'''

                    

                    if room_id not in roomoccupied:
                            roomoccupied[room_id] = {}

                    if daylec2 not in roomoccupied[room_id]:
                        roomoccupied[room_id][daylec2] = {}

                    if faculty_idlec2 not in facultyoccupied:
                        facultyoccupied[faculty_idlec2] = {}

                    if daylec2 not in facultyoccupied[faculty_idlec2]:
                        facultyoccupied[faculty_idlec2][daylec2] = {}

                    for time_slotlec2 in range(start_minutes, end_minutes, 30):
                        if time_slotlec2 not in roomoccupied[room_id][daylec2]:
                            roomoccupied[room_id][daylec2][time_slotlec2] = 'free'
                            
                        if roomoccupied[room_id][daylec2][time_slotlec2] == 'occupied':
                            dayfree = False
                            break

                        if time_slotlec2 not in facultyoccupied[faculty_idlec2][daylec2]:
                            facultyoccupied[faculty_idlec2][daylec2][time_slotlec2] = 'free'
                        
                        if facultyoccupied[faculty_idlec2][daylec2][time_slotlec2] == 'occupied':
                            facultyfree = False
                            break

                    if dayfree and facultyfree:
                        print(f"      Assigning subject {subject_id} to this day {daylec2} w/ time slot starting at {minutes_to_time(start_minutes)} upto {minutes_to_time(end_minutes)}")
                        for time in range(start_minutes, end_minutes, 30):
                            roomoccupied[room_id][daylec2][time] = 'occupied'
                            facultyoccupied[faculty_idlec2][daylec2][time] = 'occupied'
                          
                    
                            '''print("occupying", minutes_to_time(time))'''
                        facultyassignmentcounter[faculty_idlec2][daylec2]=facultyassignmentcounter[faculty_idlec2][daylec2]+1
                        assignments[subject_id] = (start_minutes, start_minutes+120, daylec2, room_id)
                        assignedsubjects.add(subject_id)

                        break
                    else:
                        '''print(f"      No  suitable time slot found for subject {subject_id}")'''
                        
               
        elif (units == 1.0):
            
            for faculty_idlab, slotslab in facultydaystimelab.items():
                # Assume faculty and room are free initially
                facultyfreelab = True 
                
                # Skip if this faculty does not match the subject's faculty ID
                if subjectfacultyid != faculty_idlab:
                    continue

                if faculty_idlab not in facultyassignmentcounter:
                    facultyassignmentcounter[faculty_idlab] = {}
                

                for daylab, start_timelab, end_timelab in slotslab:
                    daylabfree = True
                    print(f"facultys {daylab} ")
                    # Skip if the subject is already assigned
                        
                    if daylab not in facultyassignmentcounter[faculty_idlab]:
                        facultyassignmentcounter[faculty_idlab][daylab] = 0

                    if subject_id in assignedsubjects:
                        print("already assigned")
                        continue

                    if facultyassignmentcounter[faculty_idlab][daylab] >= 2:
                        print("faculty day full")
                        continue

                    start_minuteslab = time_to_minutes(start_timelab)
                    end_minuteslab = time_to_minutes(end_timelab)
                    
                    # Initialize roomoccupied and facultyoccupied structures if not present
                    if room_id not in roomoccupied:
                        roomoccupied[room_id] = {}
                    if daylab not in roomoccupied[room_id]:
                        roomoccupied[room_id][daylab] = {}

                    if faculty_idlab not in facultyoccupied:
                        facultyoccupied[faculty_idlab] = {}

                    if daylab not in facultyoccupied[faculty_idlab]:
                        facultyoccupied[faculty_idlab][daylab] = {}
                    
                    # Check if time slots are free
                    for time_slotlab in range(start_minuteslab, end_minuteslab, 30):
                        # Check room availability
                        if time_slotlab not in roomoccupied[room_id][daylab]:
                            roomoccupied[room_id][daylab][time_slotlab] = 'free'
                            daylabfree = True 

                            print(f"room {room_id} {daylab} {time_slotlab} is free")
                        if roomoccupied[room_id][daylab][time_slotlab] == 'occupied':
                            print(f"room {room_id} {daylab} {time_slotlab} is occupied")
                            daylabfree = False
                            break 

                        # Check faculty availability
                        if time_slotlab not in facultyoccupied[faculty_idlab][daylab]:
                            facultyoccupied[faculty_idlab][daylab][time_slotlab] = 'free'
                            print(f" facuty{faculty_idlab} {daylab} {time_slotlab} is free")
                            facultyfreelab=True
                            
                        if facultyoccupied[faculty_idlab][daylab][time_slotlab] == 'occupied':
                            print(f"facuty {faculty_idlab} {daylab} {time_slotlab} is occupied")
                            facultyfreelab = False
                            break 
                    
                    # If both room and faculty are free, assign the subject
                    if daylabfree and facultyfreelab:
                        print(f"Assigning subject {subject_id} to day {daylab} with time slot starting at {minutes_to_time(start_minuteslab)} up to {minutes_to_time(end_minuteslab)}")
                        
                        # Mark the time slots as occupied for both room and faculty
                        for time in range(start_minuteslab, end_minuteslab, 30):
                            roomoccupied[room_id][daylab][time] = 'occupied'
                            facultyoccupied[faculty_idlab][daylab][time] = 'occupied'
                        facultyassignmentcounter[faculty_idlab][daylab] =facultyassignmentcounter[faculty_idlab][daylab]+1
                        assignments[subject_id] = (start_minuteslab, end_minuteslab, daylab, room_id)
                        assignedsubjects.add(subject_id)
                        
                        break  # Stop checking other slots since assignment was successful


                

            
              
                                        
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
    if len(assignment) != 4:
        print(f"subject {subject_id} incomplete assignment data: {assignment}")
        continue

        
        

    day_tuple = assignment[2] 
    day_combined = days_to_string(day_tuple) 

    start_time = assignment[0]  
    end_time = assignment[1]   
    room_id = assignment[3]  

    start_time_formatted = minutes_to_time(start_time)
    end_time_formatted = minutes_to_time(end_time)

    print(f"Subject ID: {subject_id}")
    print(f"Day Combined: {day_combined}")
    print(f"Start Time: {start_time_formatted}")
    print(f"End Time: {end_time_formatted}")
    print(f"Room ID: {room_id}")

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

print(facultypairdaystime)
cursor.close()
conn.close()
