import mysql.connector
from datetime import timedelta
import time as time2

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
    database="facultyscheduling"
)

cursor = conn.cursor()

cursor.execute("SELECT * FROM `subjectschedule` JOIN subject ON subjectschedule.subjectid=subject.id JOIN faculty ON faculty.id=subjectschedule.facultyid WHERE subject.focus='Major' ORDER BY subject.unit DESC, faculty.startdate ASC")
subjectschedule = cursor.fetchall()


cursor.execute("SELECT * FROM room WHERE departmentid=1")
room = cursor.fetchall()


cursor.execute("SELECT * FROM facultypreferences JOIN faculty ON faculty.id=facultypreferences.facultyid WHERE (faculty.departmentid=1 OR faculty.departmentid=3) ORDER BY starttime ASC")
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
                print(f"  faculty {facultyid} pair: Day {day} and day {day2} with time slot start {starttimeoverlap} end at {endtimeoverlap}")
                            

    '''print("for 2.0")'''
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


def assigntimeslot(subjects):
    if not subjects:
        return True 

    subject = subjects[0]


    
    if subject[18]!='Major':
        subjectid = subject[0]
        cursor.execute("UPDATE subjectschedule SET roomid = 6 WHERE id = %s", (subjectid,))
        conn.commit()  
    
        
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

        '''print(f"trying subject {subjectid} in room {roomname} (id: {roomid}, type: {roomtype})")'''
        if (units==3.0):
            print("3.0")
            for facultyidpair, slots in facultypairdaystime.items():
                if facultyidpair not in facultyassignmentcounter:
                    facultyassignmentcounter[facultyidpair] = {}

                if subjectfacultyid != facultyidpair:
                    continue

                for day1, day2, starttime, endtime in slots:
                    if day1 not in facultyassignmentcounter[facultyidpair]:
                        facultyassignmentcounter[facultyidpair][day1] = 0

                    if day2 not in facultyassignmentcounter[facultyidpair]:
                        facultyassignmentcounter[facultyidpair][day2] = 0

                    if facultyassignmentcounter[facultyidpair][day1] >= 2:
                        continue
                    if facultyassignmentcounter[facultyidpair][day2] >= 2:
                        continue

                    if subjectid in assignedsubjects:
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
                        for time_slot in range(startminutes, end_minutes, 30):
                            roomoccupied[roomid][day1][time_slot] = 'occupied'
                            roomoccupied[roomid][day2][time_slot] = 'occupied'
                            facultyoccupied[facultyidpair][day1][time_slot] = 'occupied'
                            facultyoccupied[facultyidpair][day2][time_slot] = 'occupied'

                        facultyassignmentcounter[facultyidpair][day1] += 1
                        facultyassignmentcounter[facultyidpair][day2] += 1

                        assignments[subjectid] = (startminutes, startminutes + 90, (day1, day2), roomid)
                        assignedsubjects.add(subjectid)

                        if assigntimeslot(subjects[1:]):
                            return True

                        print("backtracking lec 3.0")
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

                    pass

                    
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
                        '''print("faculty day full")'''
                        continue
                    else:
                        '''print(f"huuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu {facultyassignmentcounter[facultyidlec2][daylec2]}")'''

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

                    for timeslotlec2 in range(startminutes, end_minutes+30, 30):
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
                        '''print(f"assigned subject {subjectid} to this day {daylec2} w/ time slot starting at {minutestotime(startminutes)} upto {minutestotime(end_minutes)}")'''
                        for time in range(startminutes, end_minutes+30, 30):
                            roomoccupied[roomid][daylec2][time] = 'occupied'
                            facultyoccupied[facultyidlec2][daylec2][time] = 'occupied'
                        
                    
                            '''print("occupying", minutestotime(time))'''
                        facultyassignmentcounter[facultyidlec2][daylec2]=facultyassignmentcounter[facultyidlec2][daylec2]+1
                        assignments[subjectid] = (startminutes, end_minutes+30, daylec2, roomid)
                        assignedsubjects.add(subjectid)

                        if assigntimeslot(subjects[1:]):
                            return True

                        print("Backtracking 2.0")
                        for time in range(startminutes, end_minutes+30, 30):
                            roomoccupied[roomid][daylec2][time] = 'free'
                            facultyoccupied[facultyidlec2][daylec2][time] = 'free'
                        
                    
                            '''print("occupying", minutestotime(time))'''
                        facultyassignmentcounter[facultyidlec2][daylec2]=facultyassignmentcounter[facultyidlec2][daylec2]-1
                        if subjectid in assignments:
                            del assignments[subjectid]

                        assignedsubjects.remove(subjectid)      
                        pass

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
                        '''print("already assigned")'''
                        continue

                    if facultyassignmentcounter[faculty_idlab][daylab] >= 2:
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
                    
        
                    for time_slotlab in range(start_minuteslab, end_minuteslab, 30):
                    
                        if time_slotlab not in roomoccupied[roomid][daylab]:
                            roomoccupied[roomid][daylab][time_slotlab] = 'free'
                            daylabfree = True 

                            '''print(f"room {roomid} {daylab} {time_slotlab} is free")'''
                        if roomoccupied[roomid][daylab][time_slotlab] == 'occupied':
                            '''print(f"room {roomid} {daylab} {time_slotlab} is occupied")'''
                            daylabfree = False
                            break 

                    
                        if time_slotlab not in facultyoccupied[faculty_idlab][daylab]:
                            facultyoccupied[faculty_idlab][daylab][time_slotlab] = 'free'
                            '''print(f" facuty{faculty_idlab} {daylab} {time_slotlab} is free")'''
                            facultyfreelab=True
                            
                        if facultyoccupied[faculty_idlab][daylab][time_slotlab] == 'occupied':
                            '''print(f"facuty {faculty_idlab} {daylab} {time_slotlab} is occupied")'''
                            facultyfreelab = False
                            break 
                        
                        
                    if daylabfree and facultyfreelab:
                        '''print(f"assigned subject {subjectid} to day {daylab} with time slot starting at {minutestotime(start_minuteslab)} up to {minutestotime(end_minuteslab)}")'''
                        for time in range(start_minuteslab, end_minuteslab+60, 30):
                            roomoccupied[roomid][daylab][time] = 'occupied'
                            facultyoccupied[faculty_idlab][daylab][time] = 'occupied'
                        facultyassignmentcounter[faculty_idlab][daylab] =facultyassignmentcounter[faculty_idlab][daylab]+1
                        assignments[subjectid] = (start_minuteslab, end_minuteslab, daylab, roomid)
                        assignedsubjects.add(subjectid)

                        if assigntimeslot(subjects[1:]):
                            return True

                        print("Backtracking lab")
                        for time in range(start_minuteslab, end_minuteslab+60, 30):
                            roomoccupied[roomid][daylab][time] = 'free'
                            facultyoccupied[faculty_idlab][daylab][time] = 'free'
                        facultyassignmentcounter[faculty_idlab][daylab] =facultyassignmentcounter[faculty_idlab][daylab]-1
                        if subjectid in assignments:
                            del assignments[subjectid]
                        assignedsubjects.remove(subjectid)
                        pass         
    return False

counter=0
              
print("schedai starting...")
success = assigntimeslot(subjectschedule)

if success:
    print("subjects are all assigned")
else:
    print("no valid schedule")
                                        

def daytoletter(daytuple):
    daymapping = {
        1: 'M',  
        2: 'T', 
        3: 'W',  
        4: 'Th',
        5: 'F',  
        6: 'S',  
        7: 'Su'  
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
