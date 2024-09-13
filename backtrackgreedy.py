

import mysql.connector
import time
import sys

start_time = time.time()

conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="facultyscheduling"
)

cursor = conn.cursor()

cursor.execute("SELECT * FROM faculty")
faculty = cursor.fetchall()

cursor.execute("SELECT * FROM facultysubject JOIN faculty ON faculty.id=facultysubject.facultyid")
facultysubject = cursor.fetchall()

cursor.execute("SELECT * FROM subject")
subject = cursor.fetchall()

cursor.execute("SELECT * FROM `subjectschedule` JOIN subject ON subjectschedule.subjectid=subject.id WHERE focus!='Minor'")
subjectschedule = cursor.fetchall()
subjectschedulecount = len(subjectschedule)



cursor.execute("SELECT * FROM room WHERE departmentid=1")
room = cursor.fetchall()

cursor.execute("SELECT * FROM facultypreferences JOIN faculty ON faculty.id=facultypreferences.facultyid WHERE faculty.departmentid=1")
facultypreference = cursor.fetchall()

cursor.execute("SET FOREIGN_KEY_CHECKS = 0; UPDATE `subjectschedule` SET `facultyid` = NULL; SET FOREIGN_KEY_CHECKS = 1;", multi=True)
conn.commit()

def facultysubjectmatch(subjectschedulesubjectid, facultysubjectfsubjectid, subjectschedulesubjectmasters, facultysubjectmasters, subjectscheduledepartmentid, facultysubjectdepartmentid):
    return (subjectschedulesubjectid == facultysubjectfsubjectid and (
        subjectschedulesubjectmasters == facultysubjectmasters or subjectschedulesubjectmasters == 'No'
    ) and (subjectscheduledepartmentid == facultysubjectdepartmentid or facultysubjectdepartmentid == 3))

def facultyworkinghourscheck(facultyworkinghours, subjectschedulesubjecthours, facultysubjectfacultyid):
    if facultyworkinghours < subjectschedulesubjecthours:
        print(f"{facultysubjectfacultyid} does not have enough working hours")
        return False
    print(f"{facultysubjectfacultyid} has enough working hours")
    return True
workinghoursleft={}

assignedsubjectscount = 0

def assign_subject(currentshubjectid):
    global assignedsubjectscount
    if currentshubjectid >= len(subjectschedule):
        return True  
    
    subjectschedules = subjectschedule[currentshubjectid]
    subjectscheduleid = subjectschedules[0]
    subjectschedulesubjectid = subjectschedules[1]
    subjectschedulesubjecthours = subjectschedules[15]
    subjectschedulesubjectmasters = subjectschedules[17]
    subjectscheduledepartmentid = subjectschedules[10]
    
    for facultysubjects in facultysubject:
        facultysubjectfacultyid = facultysubjects[1]
        facultysubjectfsubjectid = facultysubjects[2]
        facultysubjectmasters = facultysubjects[11]
        facultysubjectdepartmentid = facultysubjects[13]
        
        if facultysubjectmatch(subjectschedulesubjectid, facultysubjectfsubjectid, subjectschedulesubjectmasters, facultysubjectmasters, subjectscheduledepartmentid, facultysubjectdepartmentid):
            if facultyworkinghourscheck(facultyworkinghours[facultysubjectfacultyid], subjectschedulesubjecthours, facultysubjectfacultyid):
                
                facultyworkinghours[facultysubjectfacultyid] -= subjectschedulesubjecthours
                assignedsubjects[subjectscheduleid] = facultysubjectfacultyid
                workinghoursleft[facultysubjectfacultyid] = facultyworkinghours[facultysubjectfacultyid]
                print(f"Assigned {facultysubjectfacultyid} to {subjectscheduleid}")
                progress = (currentshubjectid+1-0.1) / subjectschedulecount * 100
                print(f"{progress:.2f}%: {subjectscheduleid} assigned to {facultysubjectfacultyid}")
                sys.stdout.flush()
                assignedsubjectscount+= 1
                cursor.execute(f"UPDATE `subjectschedule` SET `facultyid` = {facultysubjectfacultyid} WHERE `id` = {subjectscheduleid}")
                cursor.execute(f"UPDATE `faculty` SET `remainingteachinghours` = {facultyworkinghours[facultysubjectfacultyid]} WHERE `id` = {facultysubjectfacultyid}")
                conn.commit()

                if assign_subject(currentshubjectid + 1):
                    return True

                print(f"Backtracking subject {currentshubjectid}/{len(subjectschedule)}")
                facultyworkinghours[facultysubjectfacultyid] += subjectschedulesubjecthours
                assignedsubjectscount-= 1
                del assignedsubjects[subjectscheduleid]
                cursor.execute(f"UPDATE `subjectschedule` SET `facultyid` = NULL WHERE `id` = {subjectscheduleid}")
                cursor.execute(f"UPDATE `faculty` SET `remainingteachinghours` = {facultyworkinghours[facultysubjectfacultyid]} WHERE `id` = {facultysubjectfacultyid}")
                conn.commit()
               
    print(f"Failed to assign subject {currentshubjectid}/{len(subjectschedule)}, trying previous assignment.")
    return False

facultyworkinghours = {faculties[0]: faculties[12] for faculties in faculty}
assignedsubjects = {}

if assign_subject(0):
    print("All subjects assigned successfully.")
else:
    print("No valid assignment found for all subjects.")
'''print(workinghoursleft)'''


end_time = time.time()

# Calculating the total time taken
total_time = end_time - start_time
print(f"Backtracking Algorithm")
print(f"Utilizing 2 processor")
print(f"8GB of RAM")
print(f"ran in {total_time:.2f} seconds")

cursor.close()
conn.close()

assignedsubjectscount=0
'''FOR TIMESLOT ADN ROOM'''
import mysql.connector
from datetime import timedelta
import time as time2
import sys

def timetominutes(time_str):
    hours, minutes = map(int, time_str.split(':'))
    return hours * 60 + minutes

def minutestotime(minutes):
    hours = minutes // 60
    minutes = minutes % 60
    return f"{hours:02}:{minutes:02}"

def findoverlappingslots(start1, end1, start2, end2, duration):
    """
    Find all overlapping slots of given duration between two time ranges.
    """
    start1minutes = timetominutes(start1)
    end1minutes = timetominutes(end1)
    start2minutes = timetominutes(start2)
    end2minutes = timetominutes(end2)

    overlapping_slots = []

    
    for valid_start in range(start1minutes, end1minutes - duration + 1, 30):
        valid_end = valid_start + duration
        
        if valid_start >= start2minutes and valid_end <= end2minutes:
            if canfit(minutestotime(valid_start), minutestotime(valid_end), duration):
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
    database="facultyscheduling"
)

cursor = conn.cursor()

cursor.execute("SELECT * FROM `subjectschedule` JOIN subject ON subjectschedule.subjectid=subject.id ORDER BY subject.unit DESC")
subjectschedule = cursor.fetchall()

cursor.execute("SELECT * FROM `subjectschedule` JOIN subject ON subjectschedule.subjectid=subject.id WHERE focus!='Minor'")
subjectschedulecout = cursor.fetchall()
subjectschedulecount = len(subjectschedulecout)-1

cursor.execute("SELECT * FROM room WHERE departmentid=1")
room = cursor.fetchall()


cursor.execute("SELECT * FROM facultypreferences JOIN faculty ON faculty.id=facultypreferences.facultyid WHERE (faculty.departmentid=1 OR faculty.departmentid=3)")
facultypreference = cursor.fetchall()

try:
    cursor.execute("SET FOREIGN_KEY_CHECKS = 0;")

    cursor.execute("UPDATE `subjectschedule` SET `timestart` = NULL, `timeend` = NULL,`day` = NULL,  `roomid` = NULL;")
    conn.commit() 

finally:

    cursor.execute("SET FOREIGN_KEY_CHECKS = 1;")


pairdays = {}
facultypairdaystime = {}
facultyassignmentcounter={}
faculty_assigned_days = {}
facultydaystimelec = {}
facultydaystimelab = {}
roomoccupied = {}
assignments={}
assignedsubjects = set()
facultyoccupied={} 
subjuectassignedcount=0
for pref in facultypreference:
    facultyid, day, starttime, endtime = pref[1], pref[2], pref[3], pref[4]
    
    startminutes = timetominutes(starttime)
    starthours = minutestotime(startminutes)
    endminutes = timetominutes(endtime)


    print(f" Faculty {facultyid} prefers Day {day} from {starttime} to {endtime}")

    print("for 3.0")
    for pref2 in facultypreference:
        day2, start_time2, end_time2 = pref2[2], pref2[3], pref2[4]
        if facultyid not in facultypairdaystime:
            facultypairdaystime[facultyid] = []

        if day + 3 == day2:
            valid_time_slots = findoverlappingslots(starttime, endtime, start_time2, end_time2, 90)

            for starttimeoverlap, endtimeoverlap in valid_time_slots:
                facultypairdaystime[facultyid].append((day, day2, starttimeoverlap, endtimeoverlap))
                '''print(f"  faculty pair: Day {day2} and day {day} with time slot start {starttimeoverlap} end at {endtimeoverlap}")'''
                            

    print("for 2.0")
    numberfit=int((timetominutes(endtime)-timetominutes(starttime))/120)

    if (facultyid) not in facultydaystimelec:
        facultydaystimelec[facultyid] = []

    if (numberfit>= 1):
        
        for validtime in range(startminutes, endminutes, 30):
            duration = 90
            endslot = validtime + duration
            if endslot <= endminutes:
                '''print(f"start: {minutestotime(validtime)}, end: {minutestotime(endslot)}")'''
                
                if canfit(minutestotime(validtime), minutestotime(endslot), duration):
                    '''print(f"appedning: {day}, {minutestotime(validtime)}, {minutestotime(endslot)}")'''
                    facultydaystimelec[facultyid].append((day, minutestotime(validtime), minutestotime(endslot)))

    print("for 1.0")
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
                            
for subject in subjectschedule:
    print(subject[18])
    if subject[18]!='Major':
        subjectid = subject[0]
        print("subject[18]")
        cursor.execute("UPDATE subjectschedule SET roomid = 6 WHERE id = %s", (subjectid,))
        conn.commit()  
        continue
        
    subjectid, subject_name, units, subject_type, subjectfacultyid = subject[0], subject[12], subject[14], subject[16],  subject[5]
    print(f"subject {subject_name} (id: {subjectid}, type: {subject_type}, unit: {units})")
    
    if subjectid is not assignments:
        assignments[subjectid] = {}

    for rm in room:
        roomid, roomname, roomtype, roomstart, roomend = rm[0], rm[1], rm[2], rm[3], rm[4]
        if roomtype != subject_type:
            continue

        roomstartminutes = timetominutes(roomstart)
        roomendminutes = timetominutes(roomend)

        print(f"trying subject {subjectid} in room {roomname} (id: {roomid}, type: {roomtype})")
        if (units==3.0):
            for facultyidpair, slots in facultypairdaystime.items():
                facultyfree=True
                facultyday1free=True
                facultyday2free=True

                if facultyidpair not in facultyassignmentcounter:
                    facultyassignmentcounter[facultyidpair] = {}

                if subjectfacultyid!=facultyidpair:
                    continue

                for day1, day2, starttime, endtime in slots:
                    if day1 not in facultyassignmentcounter[facultyidpair]:
                        facultyassignmentcounter[facultyidpair][day1] = 0

                    if day2 not in facultyassignmentcounter[facultyidpair]:
                        facultyassignmentcounter[facultyidpair][day2] = 0

                    if facultyassignmentcounter[facultyidpair][day1]>=2:
                        continue
                    if facultyassignmentcounter[facultyidpair][day2]>=2:
                        continue
                    

                    if facultyidpair==1:
                        print (day1,day2)
                    if subjectid in assignedsubjects:
                        continue 
                    day1free = True
                    day2free = True
                    startminutes = timetominutes(starttime)
                    end_minutes = timetominutes(endtime)
                    
                    for time_slot in range(startminutes, end_minutes, 30):
                        for time in range(time_slot, time_slot + 90, 30):
                            if roomid not in roomoccupied:
                                roomoccupied[roomid] = {}

                            if day1 not in roomoccupied[roomid]:
                                roomoccupied[roomid][day1] = {}

                            if time not in roomoccupied[roomid][day1]:
                                roomoccupied[roomid][day1][time] = 'free'
                                day1free = True
                            if roomoccupied[roomid][day1][time] == 'occupied':
                                
                                day1free = False
                                break

                            if day2 not in roomoccupied[roomid]:
                                roomoccupied[roomid][day2] = {}

                            if time not in roomoccupied[roomid][day2]:
                                roomoccupied[roomid][day2][time] = 'free'
                                day2free = True
                            if roomoccupied[roomid][day2][time] == 'occupied':
                                day2free = False
                                break

              
                            if subjectfacultyid not in facultyoccupied:
                                facultyoccupied[facultyidpair] = {}

                            if day1 not in facultyoccupied[subjectfacultyid]:
                                facultyoccupied[facultyidpair][day1] = {}

                            if time not in facultyoccupied[subjectfacultyid][day1]:
                                facultyoccupied[facultyidpair][day1][time] = 'free'
                                facultyday1free = True
                            if facultyoccupied[facultyidpair][day1][time] == 'occupied':
                                facultyday1free = False
                                break

                            if day2 not in facultyoccupied[subjectfacultyid]:
                                facultyoccupied[facultyidpair][day2] = {}

                            if time not in facultyoccupied[subjectfacultyid][day2]:
                                facultyoccupied[facultyidpair][day2][time] = 'free'

                            if facultyoccupied[facultyidpair][day2][time] == 'occupied':
                                facultyday2free = False
                                break

                        if not day1free:
                            break

                    if day1free and day2free and facultyday1free and facultyday2free:
                        print(f"subject {subjectid} assigned to days {day1} and {day2} with time slot {minutestotime(startminutes)} up to {minutestotime(startminutes + 90)}")
                        
                        for time in range(startminutes, startminutes + 90, 30):
                            if roomid not in roomoccupied:
                                roomoccupied[roomid] = {}
                            if day1 not in roomoccupied[roomid]:
                                roomoccupied[roomid][day1] = {}
                            if day2 not in roomoccupied[roomid]:
                                roomoccupied[roomid][day2] = {}

                            roomoccupied[roomid][day1][time] = 'occupied'
                            roomoccupied[roomid][day2][time] = 'occupied'
                            facultyoccupied[facultyidpair][day2][time] = 'occupied'
                            facultyoccupied[facultyidpair][day1][time] = 'occupied'
                            '''print("Occupying", minutestotime(time))'''

                        facultyassignmentcounter[facultyidpair][day1] =facultyassignmentcounter[facultyidpair][day1]+1
                        facultyassignmentcounter[facultyidpair][day2] =facultyassignmentcounter[facultyidpair][day2]+1
                        assignments[subjectid] = (startminutes, startminutes + 90, (day1, day2), roomid)
                        assignedsubjects.add(subjectid)
                        progress = (assignedsubjectscount+1) / subjectschedulecount * 100
                        print(f"{progress:.2f}%: {assignedsubjectscount} assigned to {day1}{day2}")
                        time2.sleep(0.3)
                        sys.stdout.flush()
                        assignedsubjectscount+= 1
                        break 
                    else:
                        '''print(f"no slot found for subject {subjectid} on days {day1} and {day2}")'''

                
        elif(units==2.0):
             
            for facultyidlec2, slots in facultydaystimelec.items():
                facultyfree=True 
                    
                if facultyidlec2 not in facultyassignmentcounter:
                    facultyassignmentcounter[facultyidlec2] = {}

                if subjectfacultyid!=facultyidlec2:
                    continue

                for daylec2, starttime, endtime in slots:
                    if daylec2 not in facultyassignmentcounter[facultyidlec2]:
                        facultyassignmentcounter[facultyidlec2][daylec2] = 0

                    if facultyassignmentcounter[facultyidlec2][daylec2] >= 2:
                        print("faculty day full")
                        continue
                    else:
                        print(f"huuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu {facultyassignmentcounter[facultyidlec2][daylec2]}")

                    if subjectid in assignedsubjects:
                        continue
                    dayfree = True
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

                    for timeslotlec2 in range(startminutes, end_minutes, 30):
                        if timeslotlec2 not in roomoccupied[roomid][daylec2]:
                            roomoccupied[roomid][daylec2][timeslotlec2] = 'free'
                            
                        if roomoccupied[roomid][daylec2][timeslotlec2] == 'occupied':
                            dayfree = False
                            break

                        if timeslotlec2 not in facultyoccupied[facultyidlec2][daylec2]:
                            facultyoccupied[facultyidlec2][daylec2][timeslotlec2] = 'free'
                        
                        if facultyoccupied[facultyidlec2][daylec2][timeslotlec2] == 'occupied':
                            facultyfree = False
                            break

                    if dayfree and facultyfree:
                        print(f"assigned subject {subjectid} to this day {daylec2} w/ time slot starting at {minutestotime(startminutes)} upto {minutestotime(end_minutes)}")
                        for time in range(startminutes, end_minutes, 30):
                            roomoccupied[roomid][daylec2][time] = 'occupied'
                            facultyoccupied[facultyidlec2][daylec2][time] = 'occupied'
                          
                    
                            '''print("occupying", minutestotime(time))'''
                        facultyassignmentcounter[facultyidlec2][daylec2]=facultyassignmentcounter[facultyidlec2][daylec2]+1
                        assignments[subjectid] = (startminutes, startminutes+120, daylec2, roomid)
                        assignedsubjects.add(subjectid)

                        progress = (assignedsubjectscount+1) / subjectschedulecount * 100
                        print(f"{progress:.2f}%: {assignedsubjectscount} assigned to {daylec2}")
                        time2.sleep(0.3)
                        sys.stdout.flush()
                        assignedsubjectscount+= 1
                        break
                    else:
                        '''print(f" no time slot found for subject {subjectid}")'''
                        
               
        elif (units == 1.0):
            
            for faculty_idlab, slotslab in facultydaystimelab.items():
                facultyfreelab = True 
                
                if subjectfacultyid != faculty_idlab:
                    continue

                if faculty_idlab not in facultyassignmentcounter:
                    facultyassignmentcounter[faculty_idlab] = {}
                

                for daylab, start_timelab, end_timelab in slotslab:
                    daylabfree = True
                        
                    if daylab not in facultyassignmentcounter[faculty_idlab]:
                        facultyassignmentcounter[faculty_idlab][daylab] = 0

                    if subjectid in assignedsubjects:
                        print("already assigned")
                        continue

                    if facultyassignmentcounter[faculty_idlab][daylab] >= 2:
                        print("faculty day full")
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
                    
        
                    for time_slotlab in range(start_minuteslab, end_minuteslab, 30):
                       
                        if time_slotlab not in roomoccupied[roomid][daylab]:
                            roomoccupied[roomid][daylab][time_slotlab] = 'free'
                            daylabfree = True 

                            print(f"room {roomid} {daylab} {time_slotlab} is free")
                        if roomoccupied[roomid][daylab][time_slotlab] == 'occupied':
                            print(f"room {roomid} {daylab} {time_slotlab} is occupied")
                            daylabfree = False
                            break 

                       
                        if time_slotlab not in facultyoccupied[faculty_idlab][daylab]:
                            facultyoccupied[faculty_idlab][daylab][time_slotlab] = 'free'
                            print(f" facuty{faculty_idlab} {daylab} {time_slotlab} is free")
                            facultyfreelab=True
                            
                        if facultyoccupied[faculty_idlab][daylab][time_slotlab] == 'occupied':
                            print(f"facuty {faculty_idlab} {daylab} {time_slotlab} is occupied")
                            facultyfreelab = False
                            break 
                    
                  
                    if daylabfree and facultyfreelab:
                        print(f"assigned subject {subjectid} to day {daylab} with time slot starting at {minutestotime(start_minuteslab)} up to {minutestotime(end_minuteslab)}")
                        
                      
                        for time in range(start_minuteslab, end_minuteslab, 30):
                            roomoccupied[roomid][daylab][time] = 'occupied'
                            facultyoccupied[faculty_idlab][daylab][time] = 'occupied'
                        facultyassignmentcounter[faculty_idlab][daylab] =facultyassignmentcounter[faculty_idlab][daylab]+1
                        assignments[subjectid] = (start_minuteslab, end_minuteslab, daylab, roomid)
                        assignedsubjects.add(subjectid)
                        progress = (assignedsubjectscount+1) / subjectschedulecount * 100
                        print(f"{progress:.2f}%: {assignedsubjectscount} assigned to {daylab}")
                        time2.sleep(0.3)
                        sys.stdout.flush()
                        assignedsubjectscount+= 1
                        break 
                    
                    
print(facultydaystimelec)
print(facultydaystimelab)
print("occupiennnnnnnnnnnnnnnnnnnnnnnnnnd",roomoccupied)

def daytoletter(daytuple):
    day_map = {
        1: 'M',  
        2: 'T', 
        3: 'W',  
        4: 'Th',
        5: 'F',  
        6: 'S',  
        7: 'Su'  
    }
    if isinstance(daytuple, tuple):
        return ''.join(day_map.get(day, '') for day in daytuple)
    return day_map.get(daytuple, '')

def minutestotime(minutes):
    hours = minutes // 60
    mins = minutes % 60
    return f"{hours:02}:{mins:02}"


for subjectid, assignment in assignments.items():
    if len(assignment) != 4:
        print(f"subject {subjectid} incomplete assignment data: {assignment}")
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

print(facultypairdaystime)
cursor.close()
conn.close()
