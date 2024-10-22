import mysql.connector
from datetime import timedelta
import time as time2

collegeid = 3
departmentid = 0
calendarid = 8


def timetominutes(time_str):
    hours, minutes = map(int, time_str.split(':'))
    return hours * 60 + minutes

def minutestotime(minutes):
    hours = minutes // 60
    minutes = minutes % 60
    return f"{hours:02}:{minutes:02}"

def findoverlappingslots(start1, end1, start2, end2, duration):

    start1minutes = timetominutes(start1)
    end1minutes = timetominutes(end1)
    start2minutes = timetominutes(start2)
    end2minutes = timetominutes(end2)
    
    overlap_start = max(start1minutes, start2minutes)
    overlap_end = min(end1minutes, end2minutes)
    
    if overlap_start >= overlap_end:
        return []
    
    overlapping_slots = []

    for valid_start in range(overlap_start, overlap_end - duration + 1, 30):
        valid_end = valid_start + duration
        overlapping_slots.append((minutestotime(valid_start), minutestotime(valid_end)))
                
    return overlapping_slots


def canfit(starttime, endtime, duration):
    start_min = timetominutes(starttime)
    end_min = timetominutes(endtime)
    if (end_min-start_min>=duration):
        return True
    return False




checked_pairs = set()

conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="schedai"
)

cursor = conn.cursor()

cursor.execute("SELECT subjectschedule.id, subjectschedule.subjectid, subjectschedule.calendarid, subjectschedule.yearlvl, subjectschedule.section, subjectschedule.timestart,subjectschedule.timeend,subjectschedule.day, subjectschedule.roomname, subjectschedule. departmentid, subjectschedule. facultyid, subject.subjectcode, subject.name, subject.unit, subject.hours, subject.type, subject.masters, subject.focus, subject.requirelabroom FROM `subjectschedule` JOIN subject ON subjectschedule.subjectid=subject.id WHERE subject.focus='Major' and subject.unit=3.0 ORDER BY subject.unit DESC limit 5")
subjectschedule = cursor.fetchall()


cursor.execute("SELECT * FROM room")
room = cursor.fetchall()


cursor.execute("SELECT * FROM facultypreferences JOIN faculty ON faculty.id=facultypreferences.facultyid WHERE (faculty.departmentid=1 OR faculty.departmentid=3) ORDER BY starttime ASC")
facultypreference = cursor.fetchall()

cursor.execute("SELECT * FROM faculty WHERE departmentid=1")
facultyall = cursor.fetchall()

cursor.execute("SELECT * FROM facultysubject JOIN faculty ON faculty.id=facultysubject.facultyid")
facultysubject = cursor.fetchall()

cursor.execute("SELECT * FROM subjectschedule JOIN subject ON subject.id=subjectschedule.subjectid WHERE subject.focus='Minor'")
subjectscheduleminor = cursor.fetchall()

try:
    cursor.execute("SET FOREIGN_KEY_CHECKS = 0;")

    cursor.execute("UPDATE `subjectschedule` SET `timestart` = NULL, `timeend` = NULL,`day` = NULL,  `roomname` = NULL, `roomid` = NULL;")
    conn.commit() 

finally:
    cursor.execute("SET FOREIGN_KEY_CHECKS = 1;")

cursor.execute("SET FOREIGN_KEY_CHECKS = 0; UPDATE `subjectschedule` SET `facultyid` = NULL; SET FOREIGN_KEY_CHECKS = 1;", multi=True)
conn.commit()

pairdays = {}
facultypairdaystime = {}
facultyassignmentcounter={}
faculty_assigned_days = {}
facultydaystimelec = {}
facultydaystimelec2={}
facultydaystimelab = {}
roomoccupied = {}
assignments={}
assignedsubjects = set()
facultyoccupied={} 
workinghoursleft={}
sectionminor={}


facultyworkinghours = {faculties[0]: faculties[12] for faculties in facultyall}
assignedsubjectsmatching = {}

for pref in facultypreference:
    facultyid, day, starttime, endtime = pref[1], pref[2], pref[3], pref[4]
    
    startminutes = timetominutes(starttime)
    starthours = minutestotime(startminutes)
    endminutes = timetominutes(endtime)


    '''print(f" Faculty {facultyid} prefers Day {day} from {starttime} to {endtime}")'''

    '''print("for 3.0")'''
    for pref2 in facultypreference:
        day2, start_time2, end_time2 = pref2[2], pref2[3], pref2[4]
        if facultyid not in facultypairdaystime:
            facultypairdaystime[facultyid] = []

        if day + 3 == day2:
            valid_time_slots = findoverlappingslots(starttime, endtime, start_time2, end_time2, 90)

            for starttimeoverlap, endtimeoverlap in valid_time_slots:
                facultypairdaystime[facultyid].append((day, day2, starttimeoverlap, endtimeoverlap))
                '''print(f"  faculty {facultyid} pair: Day {day} and day {day2} with time slot start {starttimeoverlap} end at {endtimeoverlap}")'''
                            

    '''print("for 2.0")'''
    numberfit=int((timetominutes(endtime)-timetominutes(starttime))/120)

    if (facultyid) not in facultydaystimelec2:
        facultydaystimelec2[facultyid] = []

    if (numberfit>= 1):
        
        for validtime in range(startminutes, endminutes, 30):
            duration = 120
            endslot = validtime + duration
            if endslot <= endminutes:
                '''print(f"start: {minutestotime(validtime)}, end: {minutestotime(endslot)}")'''
                
                if canfit(minutestotime(validtime), minutestotime(endslot), duration):
                    '''print(f"appedning: {day}, {minutestotime(validtime)}, {minutestotime(endslot)}")'''
                    facultydaystimelec2[facultyid].append((day, minutestotime(validtime), minutestotime(endslot)))

    '''print("for 1.0")'''
    numberfit=int((timetominutes(endtime)-timetominutes(starttime))/180)

    if facultyid not in facultydaystimelab:
        facultydaystimelab[facultyid] = []

    if (numberfit>=1):
        
        for validtime in range(startminutes, endminutes, 30):
            duration = 180
            endslot = validtime + duration
            
            if endslot <= endminutes:
                '''print(f"start: {minutestotime(validtime)}, end: {minutestotime(endslot)}")'''
                
                if canfit(minutestotime(validtime), minutestotime(endslot), duration):
                    '''print(f" appendng {day}, {minutestotime(validtime)}, {minutestotime(endslot)}")'''
                    facultydaystimelab[facultyid].append((day, minutestotime(validtime), minutestotime(endslot)))




for day in range(1, 6):
    for rm in room:  
        roomid = rm[0]
        if roomid not in roomoccupied:
            roomoccupied[roomid] = {}

        if roomid not in roomoccupied[roomid]:
                roomoccupied[roomid][day] = {}

        if day not in roomoccupied[roomid]:
            roomoccupied[roomid][day] = {time: 'free' for time in range(420, 1140, 30)}

        for faculty in facultyall:
            facultyid=faculty[0]
            if facultyid not in facultyoccupied:
                facultyoccupied[facultyid] = {}

            if day not in facultyoccupied[facultyid]:
                facultyoccupied[facultyid][day] = {}

            if day not in facultyoccupied[facultyid][day]:
                facultyoccupied[facultyid][day] = {time: 'free' for time in range(420, 1140, 30)}


subjectiteration={}
backtrackcounters={}
maxdepth=50

def facultysubjectmatch(subjectschedulesubjectname, facultysubjectfsubjectname, subjectschedulesubjectmasters, facultysubjectmasters, subjectscheduledepartmentid, facultysubjectdepartmentid):
    subject_name_match = (subjectschedulesubjectname.strip().lower() == facultysubjectfsubjectname.strip().lower())
    master_match = (subjectschedulesubjectmasters == facultysubjectmasters or (subjectschedulesubjectmasters == 'No' and facultysubjectmasters == 'Yes'))
    department_match = (subjectscheduledepartmentid == facultysubjectdepartmentid or facultysubjectdepartmentid == 3)
    
    '''if subject_name_match:
        print(f"Subject name matches", subjectschedulesubjectname, facultysubjectfsubjectname)
    else:
        print(f"Subject not matches", subjectschedulesubjectname, facultysubjectfsubjectname)
    
    if master_match:
        print("Master's status matches.")
   
    if department_match:
        print("Department ID matches.")'''

    return subject_name_match and master_match and department_match




def facultyworkinghourscheck(facultyworkinghours, subjectschedulesubjecthours, facultysubjectfacultyid):
    if facultyworkinghours < subjectschedulesubjecthours:
        '''print(f"{facultysubjectfacultyid} does not have enough working hours")'''
        return False
    '''print(f"{facultysubjectfacultyid} has enough working hours")'''
    return True

def assign_subject(current_subject_id):
    """Assign subjects to faculty and their corresponding time slots in rooms."""

    if current_subject_id >= len(subjectschedule):
        return True  

    print("Current Subject ID:", current_subject_id)
    subject_info = subjectschedule[current_subject_id]
    subject_id = subject_info[0]
    subject_name = subject_info[12]
    subject_hours = subject_info[14]
    subject_masters = subject_info[16]
    subject_department_id = subject_info[9]

    

    for faculty in facultysubject:
        faculty_id = faculty[1]
        faculty_subject_name = faculty[2]
        faculty_masters = faculty[11]
        faculty_department_id = faculty[13]
        faculty_first_name = faculty[4]
        faculty_last_name = faculty[6]
        
        if facultysubjectmatch(subject_name, faculty_subject_name, subject_masters, faculty_masters, subject_department_id, faculty_department_id):
            if facultyworkinghourscheck(facultyworkinghours[faculty_id], subject_hours, faculty_id):
                facultyworkinghours[faculty_id] -= subject_hours
                assignedsubjectsmatching[subject_id] = faculty_id
                workinghoursleft[faculty_id] = facultyworkinghours[faculty_id]
                print(f"Assigned {faculty_id} to {subject_id}")
                
                query = """
                    UPDATE `subjectschedule`
                    SET `facultyfname` = %s, `facultylname` = %s, `facultyid` = %s
                    WHERE `id` = %s
                """
                values = (faculty_first_name, faculty_last_name, faculty_id,subject_id)
                cursor.execute(query, values)

                cursor.execute(f"UPDATE `faculty` SET `remainingteachinghours` = {facultyworkinghours[faculty_id]} WHERE `id` = {faculty_id}")
                conn.commit()
       
                if assigntimeslot(current_subject_id, faculty_id):
                    return True
                
                # Backtracking if no valid time slots are found
                print(f"Backtracking assignment of subject {subject_id} for faculty {faculty_id}")
                facultyworkinghours[faculty_id] += subject_hours
                del assignedsubjectsmatching[subject_id]

    print(f"Failed to assign subject {current_subject_id}/{len(subjectschedule)} {subject_name}, trying previous assignment.")
    time2.sleep(0.1)
    return False

def assigntimeslot(currentsubjectid, facultyidnew):
    
    for roomid, schedule in roomoccupied.items():  # Loop through each room and its schedule
        print(f"Room {roomid}:")  # Print room identifier
        for day in range(1, 7):  # Assuming days are numbered from 1 to 5
            free_times = []  # List to collect free times for the day
            if day in schedule:  # Check if the day exists in the schedule
                for time, status in schedule[day].items():  # Loop through each time slot in the day's schedule
                    if status == 'occupied':  # Check if the time slot is free
                        free_times.append(minutestotime(time))  # Convert time to a readable format and add to the list
        
            if free_times: 
                print(f"  Day {day}: {', '.join(free_times)} - Status: occupied")

    for facultyidpair, days in facultyoccupied.items():
        if facultyidpair != 14:  # Filtering for specific facultyidpair
            continue
        for day, times in days.items():  # Iterating over days and their respective times
            for time, status in times.items():  # Iterating over time and its status
                if status == "occupied":  # Checking if the status is "occupied"
                    print(f"Faculty ID Pair: {facultyidpair}, Day: {day}, Time: {minutestotime(time)}, Status: {status}")
    time2.sleep(0.3)



       


   
   
    print("")
    '''print(f"Current subject ID: {currentsubjectid}")'''
    '''print(f"Assignments so far: {assignments}")'''
    '''print(f"Assigned subjects: {assignedsubjects}")'''
  
    '''print(currentsubjectid,"/",len(subjectschedule))'''
    if currentsubjectid >= len(subjectschedule):
        return True  
    
  
    subject = subjectschedule[currentsubjectid]
  
    subjectid = int(subject[0])
    subname = subject[12]
    units = subject[13]
    subject_type = subject[15]
    subjectfacultyid = facultyidnew  # Assuming subject[10] is intended to be an integer
    requirelab = int(subject[18])  # Assuming subject[20] is intended to be an integer
    '''print(f"subject {subject_name} (id: {subjectid}, type: {subject_type}, unit: {units}, faculty: {subjectfacultyid} )")'''

    

    if subjectid not in assignments:
        assignments[subjectid] = {}

    if currentsubjectid not in backtrackcounters:
        backtrackcounters[currentsubjectid] = 0
    '''print("currentshubjectid", currentsubjectid)'''

    '''if backtrackcounters[currentsubjectid] >= maxdepth:
        print(f"No valid solution found for subject {subjectid} after {maxdepth} backtracks.")
       return False '''
    

    for rm in room:
        roomid, roomname, roomtype, roomstart, roomend = rm[0], rm[1], rm[2], rm[3], rm[4]

        

        

        roomstartminutes = timetominutes(roomstart)
        roomendminutes = timetominutes(roomend)

        print(f"trying subject {currentsubjectid} in room {roomname} (id: {roomid}, type: {roomtype})")
    
        if units == 3.0:
            '''if backtrackcounters[currentsubjectid] >= maxdepth:
                print(f"No valid solution found for subject {currentsubjectid} after {maxdepth} backtracks.")
                
                for roomid in roomoccupied:
                    print("heeeeeeeeeeeeeee")
                    day1 = False
                    day2 = False
                    facultyday1 = False
                    facultyday2 = False
                    start_time_day1 = None
                    start_time_day2 = None
                    
                    for day1 in range(1,3): 
                        day2 = (day1 + 3) 

                        for time in range(420, 1140, 30):
                            if (roomoccupied[roomid][day1].get(time) == 'free' and
                                roomoccupied[roomid][day1].get(time + 30) == 'free' and
                                roomoccupied[roomid][day1].get(time + 60) == 'free'):
                                day1 = True
                                start_time_day1 = time
                                
                            
                            if (roomoccupied[roomid][day2].get(time) == 'free' and
                                roomoccupied[roomid][day2].get(time + 30) == 'free' and
                                roomoccupied[roomid][day2].get(time + 60) == 'free'):
                                day2 = True
                                start_time_day2 = time
                            if day1 not in facultyoccupied[subjectfacultyid]:
                                facultyoccupied[subjectfacultyid][day1] = {}
                            if day2 not in facultyoccupied[subjectfacultyid]:
                                facultyoccupied[subjectfacultyid][day2] = {}

                            if (facultyoccupied[subjectfacultyid][day1].get(time) == 'free' and
                                facultyoccupied[subjectfacultyid][day1].get(time + 30) == 'free' and
                                facultyoccupied[subjectfacultyid][day1].get(time + 60) == 'free'):
                                faculty1 = True

                            if (facultyoccupied[subjectfacultyid][day2].get(time) == 'free' and
                                facultyoccupied[subjectfacultyid][day2].get(time + 30) == 'free' and
                                facultyoccupied[subjectfacultyid][day2].get(time + 60) == 'free'):
                                faculty2 = True
                                 
                        if day1 and day2 and facultyday1 and facultyday2:
                            break

                    if day1 and day2 and facultyday1 and facultyday2:
                        break
                if day1 and day2 and facultyday1 and facultyday1:     
                      
                    print(f"assigned alter subject {currentsubjectid} in {roomname} to this day {day2} and {day1} w/ time slot starting at {minutestotime(startminutes)} upto {minutestotime(end_minutes)}")
                   
                    for time_slot in range(start_time_day1, start_time_day1+90, 30):
                        roomoccupied[roomid][day1][time_slot] = 'occupied'
                        roomoccupied[roomid][day2][time_slot] = 'occupied'
                        facultyoccupied[subjectfacultyid][day1][time_slot] = 'occupied'
                        facultyoccupied[subjectfacultyid][day2][time_slot] = 'occupied'

                    facultyassignmentcounter[facultyidpair][day1] += 1
                    facultyassignmentcounter[subjectfacultyid][day2] += 1

                    assignments[subjectid] = (start_time_day1, start_time_day1 + 90, (day1, day2), roomid)
                    assignedsubjects.add(subjectid)

                    if assigntimeslot(currentsubjectid+1):
                        return True 
                    print("backtracking lec 3.0")

                    for time_slot in range(start_time_day1, start_time_day1+90, 30):
                        roomoccupied[roomid][day1][time_slot] = 'free'
                        roomoccupied[roomid][day2][time_slot] = 'free'
                        facultyoccupied[subjectfacultyid][day1][time_slot] = 'free'
                        facultyoccupied[subjectfacultyid][day2][time_slot] = 'free'

                    facultyassignmentcounter[subjectfacultyid][day1] -= 1
                    facultyassignmentcounter[subjectfacultyid][day2] -= 1
                 
                    
                    if subjectid in assignments:
                        del assignments[subjectid]
                    assignedsubjects.remove(subjectid)
                    
            else:'''
            if roomtype != subject_type:
                continue    
            for facultyidpair, slots in facultypairdaystime.items():
                print
                if facultyidpair not in facultyassignmentcounter:
                    facultyassignmentcounter[facultyidpair] = {}
                
                if subjectfacultyid != facultyidpair:
                    continue  
                else:
                    print(facultyidpair, subjectfacultyid)
                    time2.sleep(5)

                for day1, day2, starttime, endtime in slots:
                    
                    if day1 not in facultyassignmentcounter[facultyidpair]:
                        facultyassignmentcounter[facultyidpair][day1] = 0

                    if day2 not in facultyassignmentcounter[facultyidpair]:
                        facultyassignmentcounter[facultyidpair][day2] = 0

                    if facultyassignmentcounter[facultyidpair][day1] >= 1000:
                        continue  
                    if facultyassignmentcounter[facultyidpair][day2] >= 100:
                        continue

                    if subjectid in assignedsubjects:
                        print("assigned alreadyyy")
                        continue 

                    startminutes = timetominutes(starttime)
                    end_minutes = timetominutes(endtime)
                    day1free = day2free = facultyday1free = facultyday2free = True
                    
                    for time_slot in range(startminutes, end_minutes, 30):
                        if roomid not in roomoccupied:
                            roomoccupied[roomid] = {}

                        if day1 not in roomoccupied[roomid]:
                            roomoccupied[roomid][day1] = {}

                        if day2 not in roomoccupied[roomid]:
                            roomoccupied[roomid][day2] = {}

                        if roomoccupied[roomid][day1].get(time_slot) == 'occupied':
                            day1free = False
                            break

                        if roomoccupied[roomid][day2].get(time_slot) == 'occupied':
                            day2free = False
                            break

                        if facultyidpair not in facultyoccupied:
                            facultyoccupied[facultyidpair] = {}

                        if day1 not in facultyoccupied[facultyidpair]:
                            facultyoccupied[facultyidpair][day1] = {}

                        if day2 not in facultyoccupied[facultyidpair]:
                            facultyoccupied[facultyidpair][day2] = {}

                        if facultyoccupied[facultyidpair][day1].get(time_slot) == 'occupied':
                            facultyday1free = False
                            break
                       
                        if facultyoccupied[facultyidpair][day2].get(time_slot) == 'occupied':
                            facultyday2free = False
                            break
                       
                    if day1free and day2free and facultyday1free and facultyday2free:
            
                        print(f"assigned  subject {currentsubjectid} in {roomname} to this day {day2} and {day1} w/ time slot starting at {minutestotime(startminutes)} upto {minutestotime(end_minutes)}")
                
                        for time_slot in range(startminutes, end_minutes, 30):
                            roomoccupied[roomid][day1][time_slot] = 'occupied'
                            roomoccupied[roomid][day2][time_slot] = 'occupied'
                            facultyoccupied[facultyidpair][day1][time_slot] = 'occupied'
                            facultyoccupied[facultyidpair][day2][time_slot] = 'occupied'

                        facultyassignmentcounter[facultyidpair][day1] += 1
                        facultyassignmentcounter[facultyidpair][day2] += 1

                        assignments[subjectid] = (startminutes, startminutes + 90, (day1, day2), roomid)
                        assignedsubjects.add(subjectid)

                        if assigntimeslot(currentsubjectid+1, facultyidnew):
                            return True 
                    
                        '''print("backtracking lec 3.0")'''
                    
                        for time_slot in range(startminutes, end_minutes, 30):
                            roomoccupied[roomid][day1][time_slot] = 'free'
                            roomoccupied[roomid][day2][time_slot] = 'free'
                            facultyoccupied[facultyidpair][day1][time_slot] = 'free'
                            facultyoccupied[facultyidpair][day2][time_slot] = 'free'

                        facultyassignmentcounter[facultyidpair][day1] -= 1
                        facultyassignmentcounter[facultyidpair][day2] -= 1
        
                        
                        if subjectid in assignments:
                            del assignments[subjectid]
                        assignedsubjects.remove(subjectid)

            

           

                    
        elif(units==2.0):
            '''if backtrackcounters[currentsubjectid] >= maxdepth:
                print(f"No valid solution found for subject {currentsubjectid} after {maxdepth} backtracks.")
                for roomid in roomoccupied:
                   
                    day1free = False
                    
                    facultyday1free = False
                   
                    start_time_day1 = None
                    start_time_day2 = None
                    
                    for day1 in range(1,3): 
                       

                        for time in range(420, 1140, 30):
                            if (roomoccupied[roomid][day1].get(time) == 'free' and
                                roomoccupied[roomid][day1].get(time + 30) == 'free' and
                                roomoccupied[roomid][day1].get(time + 60) == 'free'):
                        
                                day1free = True
                                
                            if day1 not in facultyoccupied[subjectfacultyid]:
                                facultyoccupied[subjectfacultyid][day1] = {}

                            if (facultyoccupied[subjectfacultyid][day1].get(time) == 'free' and
                                facultyoccupied[subjectfacultyid][day1].get(time + 30) == 'free' and
                                facultyoccupied[subjectfacultyid][day1].get(time + 60) == 'free'):
                                facultyday1free = True

                            if day1free and facultyday1free:
                                starttime = time
                                dayvalid=day1
                                break  

                        if day1free and facultyday1free:
                            break
                                                   

                    if day1 and facultyday1free:
                      
                        break
                  
                       
                if day1free and facultyday1free:       
                    print(f"assigned alter subject {currentsubjectid} in {roomname} to this day {day1} w/ time slot starting at {minutestotime(starttime)} upto {minutestotime(starttime+120)}")
             
                    for time_slot in range(starttime, starttime+120, 30):
                        roomoccupied[roomid][dayvalid][time_slot] = 'occupied'
              
                        facultyoccupied[subjectfacultyid][dayvalid][time_slot] = 'occupied'
       
                    if subjectfacultyid not in facultyassignmentcounter:
                        facultyassignmentcounter[subjectfacultyid] = {}
                            
                    if dayvalid not in facultyassignmentcounter[subjectfacultyid]:
                        facultyassignmentcounter[subjectfacultyid][dayvalid] = 0

           
                    facultyassignmentcounter[subjectfacultyid][dayvalid] += 1
                

                    assignments[subjectid] = (starttime, starttime + 120, (dayvalid), roomid)
                    assignedsubjects.add(subjectid)

                    if assigntimeslot(currentsubjectid+1):
                        return True 
                    
                else:
              
                    assignments[subjectid] = (0, 0 + 120, 7, 69)
                    if assigntimeslot(currentsubjectid+1):
                        return True
            else:'''
            if roomtype != subject_type:
                continue   
            for facultyidlec2, slots in facultydaystimelec2.items():
                
                    
                if facultyidlec2 not in facultyassignmentcounter:
                    facultyassignmentcounter[facultyidlec2] = {}

                if subjectfacultyid!=facultyidlec2:
                    continue

                for daylec2, starttime, endtime in slots:
                    if daylec2 not in facultyassignmentcounter[facultyidlec2]:
                        facultyassignmentcounter[facultyidlec2][daylec2] = 0

                    if facultyassignmentcounter[facultyidlec2][daylec2] >= 3:
                        '''print("faculty day full")'''
                        continue
                    else:
                        '''print(f"huuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu {facultyassignmentcounter[facultyidlec2][daylec2]}")'''

                    if subjectid in assignedsubjects:
                        continue
                    
                    startminutes = timetominutes(starttime)
                    end_minutes = timetominutes(endtime)
                    
                    '''print(f" facultyidv{faculty_id} - Day {day}")
                    print(f"start time: {startminutes}")
                    print(f"end time: {end_minutes}")'''

                    
                    if roomid not in roomoccupied:
                            roomoccupied[roomid] = {}

                    if daylec2 not in roomoccupied[roomid]:
                        roomoccupied[roomid][daylec2] = {}

                    if facultyidlec2 not in facultyoccupied:
                        facultyoccupied[facultyidlec2] = {}

                    if daylec2 not in facultyoccupied[facultyidlec2]:
                        facultyoccupied[facultyidlec2][daylec2] = {}

                    daylec2free = facultylec2free = True

                    for timeslotlec2 in range(startminutes, end_minutes, 30):
                        if timeslotlec2 not in roomoccupied[roomid][daylec2]:
                            roomoccupied[roomid][daylec2][timeslotlec2] = 'free'
                            
                        if roomoccupied[roomid][daylec2][timeslotlec2] == 'occupied':
                            daylec2free = False
                            break
                        else:
                            print("dayfreeeeeeeeeeeeeeeeeeeeeeeeeeeeee")        
                        if timeslotlec2 not in facultyoccupied[facultyidlec2][daylec2]:
                            facultyoccupied[facultyidlec2][daylec2][timeslotlec2] = 'free'
                        
                        if facultyoccupied[facultyidlec2][daylec2][timeslotlec2] == 'occupied':
                            facultylec2free = False
                            break
                        else:
                            print("facultyfreeeeeeeeeeeeeeeeeeeeeeeeeeeeee")    
                    
                    if daylec2free and facultylec2free:
                        print(f"assigned subject {currentsubjectid} to this day {daylec2} w/ time slot starting at {minutestotime(startminutes)} upto {minutestotime(end_minutes)}")
                        print('')
                        for time in range(startminutes, end_minutes, 30):
                            roomoccupied[roomid][daylec2][time] = 'occupied'
                            facultyoccupied[facultyidlec2][daylec2][time] = 'occupied'
                        
                    
                        '''print("occupying", minutestotime(time))'''
                        facultyassignmentcounter[facultyidlec2][daylec2]=facultyassignmentcounter[facultyidlec2][daylec2]+1
                        assignments[subjectid] = (startminutes, end_minutes+30, daylec2, roomid)
                        assignedsubjects.add(subjectid)

                        if assigntimeslot(currentsubjectid+1, facultyidnew):
                            return True

                        '''print("Backtracking 2.0")'''
                        for time in range(startminutes, end_minutes, 30):
                            roomoccupied[roomid][daylec2][time] = 'free'
                            facultyoccupied[facultyidlec2][daylec2][time] = 'free'
                        
                    
                        '''print("unoccupying", minutestotime(time))'''
                        facultyassignmentcounter[facultyidlec2][daylec2]=facultyassignmentcounter[facultyidlec2][daylec2]-1
                        
                        if subjectid in assignments:
                            del assignments[subjectid]

                        assignedsubjects.remove(subjectid)      
                    

        elif (units == 1.0):
            
            '''if backtrackcounters[currentsubjectid] >= maxdepth:
                
                print(f"No valid solution found for subject {currentsubjectid} after {maxdepth} backtracks.")
                for roomid in roomoccupied:
                 
                    day1true = False
                   
                    facultyday1true = False
                   
               
                    start_time_day2 = None
                    
                    for day1 in range(1,3): 
                        for time in range(420, 1140, 30):
                            if (roomoccupied[roomid][day1].get(time) == 'free' and
                                roomoccupied[roomid][day1].get(time + 30) == 'free' and
                                roomoccupied[roomid][day1].get(time + 60) == 'free'):
                                day1true = True
                                
                        
                               
                               
                            if day1 not in facultyoccupied[subjectfacultyid]:
                                facultyoccupied[subjectfacultyid][day1] = {}

                            if (facultyoccupied[subjectfacultyid][day1].get(time) == 'free' and
                                facultyoccupied[subjectfacultyid][day1].get(time + 30) == 'free' and
                                facultyoccupied[subjectfacultyid][day1].get(time + 60) == 'free'):
                                
                                facultyday1true = True

                            if day1true and facultyday1true:
                                starttime = time
                                dayvalid = day1
                                break
                        if day1true and facultyday1true:
                         
                         
                            break
                       
                           
                    if day1true and facultyday1true:
                     
                     
                        break
                   
                
                if day1true and facultyday1true: 
                  
                    print(f"assigned alter subject {currentsubjectid} in {roomname} to this day {dayvalid} w/ time slot starting at {minutestotime(starttime)} upto {minutestotime(starttime+180)}")
                    print('')
            
                    for time_slot in range(starttime, starttime+180, 30):
                        roomoccupied[roomid][day1][time_slot] = 'occupied'
                      
                        facultyoccupied[subjectfacultyid][dayvalid][time_slot] = 'occupied'
                       
                    if subjectfacultyid not in facultyassignmentcounter:
                        facultyassignmentcounter[subjectfacultyid] = {}
                            
                    if dayvalid not in facultyassignmentcounter[subjectfacultyid]:
                        facultyassignmentcounter[subjectfacultyid][dayvalid] = 0

                    facultyassignmentcounter[subjectfacultyid][dayvalid] += 1
                  

                    assignments[subjectid] = (starttime, starttime + 180, (dayvalid), roomid)
                    assignedsubjects.add(subjectid)

                    if assigntimeslot(currentsubjectid+1):
                        return True 
                    
                else:
                    assignments[subjectid] = (0, 0 + 180, 7, 69)
                    if assigntimeslot(currentsubjectid+1):
                        return True 
                    continue
            else:'''
          
            if roomtype != subject_type:
                continue   
            for faculty_idlab, slotslab in facultydaystimelab.items():
                
                
                if subjectfacultyid != faculty_idlab:
                    continue

                if faculty_idlab not in facultyassignmentcounter:
                    facultyassignmentcounter[faculty_idlab] = {}
                

                for daylab, start_timelab, end_timelab in slotslab:
                    
                        
                    if daylab not in facultyassignmentcounter[faculty_idlab]:
                        facultyassignmentcounter[faculty_idlab][daylab] = 0

                    if subjectid in assignedsubjects:
                        '''print("already assigned")'''
                        continue

                    if facultyassignmentcounter[faculty_idlab][daylab] >= 3:
                        '''print("faculty day full")'''
                        continue

                    start_minuteslab = timetominutes(start_timelab)
                    end_minuteslab = timetominutes(end_timelab)
                    
        
                    if roomid not in roomoccupied:
                        roomoccupied[roomid] = {}
                    if daylab not in roomoccupied[roomid]:
                        roomoccupied[roomid][daylab] = {}

                    if faculty_idlab not in facultyoccupied:
                        facultyoccupied[faculty_idlab] = {}

                    if daylab not in facultyoccupied[faculty_idlab]:
                        facultyoccupied[faculty_idlab][daylab] = {}
                    
                    dayfreelab = facultyfreelab = True
                    for time_slotlab in range(start_minuteslab, end_minuteslab-30, 30):
                    
                        if time_slotlab not in roomoccupied[roomid][daylab]:
                            roomoccupied[roomid][daylab][time_slotlab] = 'free'
                            

                            '''print(f"room {roomid} {daylab} {time_slotlab} is free")'''
                        if roomoccupied[roomid][daylab][time_slotlab] == 'occupied':
                            '''print(f"room {roomid} {daylab} {time_slotlab} is occupied")'''
                            facultyfreelab = False
                            break 

                    
                        if time_slotlab not in facultyoccupied[faculty_idlab][daylab]:
                            facultyoccupied[faculty_idlab][daylab][time_slotlab] = 'free'
                            '''print(f" facuty{faculty_idlab} {daylab} {time_slotlab} is free")'''
                            facultyfreelab=True
                            
                        if facultyoccupied[faculty_idlab][daylab][time_slotlab] == 'occupied':
                            '''print(f"facuty {faculty_idlab} {daylab} {time_slotlab} is occupied")'''
                            facultyfreelab = False
                            break 
                        
                        
                    if dayfreelab and facultyfreelab:
                        print(f"assigned subject {currentsubjectid} to day {daylab} with time slot starting at {minutestotime(start_minuteslab)} up to {minutestotime(end_minuteslab)}")
                        print('')
                        for time in range(start_minuteslab, end_minuteslab, 30):
                            roomoccupied[roomid][daylab][time] = 'occupied'
                            facultyoccupied[faculty_idlab][daylab][time] = 'occupied'
                        facultyassignmentcounter[faculty_idlab][daylab] =facultyassignmentcounter[faculty_idlab][daylab]+1
                        assignments[subjectid] = (start_minuteslab, end_minuteslab, daylab, roomid)
                        assignedsubjects.add(subjectid)

                        if assigntimeslot(currentsubjectid+1, facultyidnew):
                            return True

                        '''print("Backtracking lab")'''
                        for time in range(start_minuteslab, end_minuteslab, 30):
                            roomoccupied[roomid][daylab][time] = 'free'
                            facultyoccupied[faculty_idlab][daylab][time] = 'free'
                        facultyassignmentcounter[faculty_idlab][daylab] =facultyassignmentcounter[faculty_idlab][daylab]-1
                        
                        if subjectid in assignments:
                            del assignments[subjectid]
                        assignedsubjects.remove(subjectid)
                    
                     
    print(f"Failed to assign subject {currentsubjectid} {subname} {subjectfacultyid}, trying previous assignment.") 
    time2.sleep(0.2)
    backtrackcounters[currentsubjectid] += 1   
    print(f"")                   
    return False

counter=0
              


if assign_subject(0):  # Start from the first subject (ID 0)
    print("All subjects assigned successfully!")
else:
    print("Failed to assign all subjects.")
                                        

def daytoletter(daytuple):
    daymapping = {
        1: 'M',  
        2: 'T', 
        3: 'W',  
        4: 'Th',
        5: 'F',  
        6: 'S',  
        7: 'tbh'  
    }
    if isinstance(daytuple, tuple):
        return ''.join(daymapping.get(day, '') for day in daytuple)
    return daymapping.get(daytuple, '')

def minutestotime(minutes):
    hours = minutes // 60
    mins = minutes % 60
    return f"{hours:02}:{mins:02}"


for subjectid, assignment in assignments.items():
    if len(assignment) != 4:
        print(f"subject {subjectid} incomplete assignment: {assignment}")
        continue
    daytuple = assignment[2] 
    daycombined = daytoletter(daytuple) 

    starttime = assignment[0]  
    endtime = assignment[1]   
    roomid = assignment[3]  

    starttime_formatted = minutestotime(starttime)
    endtime_formatted = minutestotime(endtime)

    print(f"sub id: {subjectid}")
    print(f"day cmbined: {daycombined}")
    print(f"time start: {starttime_formatted}")
    print(f"end time: {endtime_formatted}")
    print(f"id room: {roomid}")

    cursor.execute("""UPDATE subjectschedule SET day = %s, timestart = %s, timeend = %s, roomid = %s WHERE id = %s""", (daycombined, starttime_formatted, endtime_formatted, roomid, subjectid))
    conn.commit()




cursor.close()
conn.close()
