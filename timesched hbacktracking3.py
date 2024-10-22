import mysql.connector
from datetime import timedelta
import time as timer

collegeid = 3
departmentid = 0
calendarid = 7

def timetominutes(timestr):
    hours, minutes = map(int, timestr.split(':'))
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
    
    overlapstart = max(start1minutes, start2minutes)
    overlapend = min(end1minutes, end2minutes)
    
    if overlapstart >= overlapend:
        return []
    
    overlappingslots = []

    for validstart in range(overlapstart, overlapend - duration + 1, 30):
        validend = validstart + duration
        overlappingslots.append((minutestotime(validstart), minutestotime(validend)))
                
    return overlappingslots


def canfit(starttime, endtime, duration):
    startmin = timetominutes(starttime)
    endmin = timetominutes(endtime)
    if (endmin-startmin>=duration):
        return True
    return False






conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="schedai"
)

cursor = conn.cursor()
if (departmentid==0):
    cursor.execute("""SELECT subjectschedule.id, subjectschedule.subjectid, subjectschedule.calendarid, subjectschedule.yearlvl, subjectschedule.section, subjectschedule.timestart,subjectschedule.timeend,subjectschedule.day, subjectschedule.roomname, subjectschedule. departmentid, subjectschedule. facultyid, subject.subjectcode, subject.name, subject.unit, subject.hours, subject.type, subject.masters, subject.focus, faculty.masters, faculty.startdate, subject.requirelabroom FROM `subjectschedule` JOIN subject ON subjectschedule.subjectid=subject.id JOIN faculty ON faculty.id=subjectschedule.facultyid JOIN department ON department.id=subjectschedule.departmentid WHERE subject.focus='Major' AND subjectschedule.calendarid = %s AND department.collegeid = %s  ORDER BY subject.unit DESC, faculty.startdate ASC """, (calendarid, collegeid))
    subjectschedule = cursor.fetchall()

    cursor.execute("""SELECT * FROM faculty JOIN department ON department.id=faculty.departmentid WHERE department.collegeid=%s""",(collegeid,))
    facultyall = cursor.fetchall()

    cursor.execute("SELECT * FROM facultypreferences JOIN faculty ON faculty.id=facultypreferences.facultyid JOIN department ON department.id=faculty.departmentid WHERE department.collegeid=%s AND faculty.id!=0 ORDER BY starttime ASC""",(collegeid,))
    facultypreference = cursor.fetchall()

    cursor.execute("SELECT * FROM subjectschedule JOIN subject ON subject.id=subjectschedule.subjectid JOIN department ON department.id=subjectschedule.departmentid WHERE subject.focus='Minor' AND department.collegeid=%s AND subjectschedule.calendarid=%s""",(collegeid,calendarid))
    subjectscheduleminor = cursor.fetchall()

cursor.execute("SELECT * FROM room ORDER BY departmentid ASC")
room = cursor.fetchall()








try:
    cursor.execute("SET FOREIGN_KEY_CHECKS = 0;")

    cursor.execute("UPDATE `subjectschedule` JOIN subject ON subject.id=subjectschedule.subjectid SET `timestart` = NULL, `timeend` = NULL,`day` = NULL,  `roomname` = NULL, `roomid` = NULL WHERE subject.focus!='Minor';")
    conn.commit() 

finally:
    cursor.execute("SET FOREIGN_KEY_CHECKS = 1;")


pairdays = {}
facultypairdaystime = {}
facultyassignmentcounter={}

facultydaystimelec = {}
facultydaystimelec2={}
facultydaystimelab = {}
roomoccupied = {}
assignments={}
assignedsubjects = set()
facultyoccupied={} 
minoroccupied={}

for pref in facultypreference:
    facultyid, day, starttime, endtime = pref[1], pref[2], pref[3], pref[4]
    
    startminutes = timetominutes(starttime)
    starthours = minutestotime(startminutes)
    endminutes = timetominutes(endtime)


    '''print(f" Faculty {facultyid} prefers Day {day} from {starttime} to {endtime}")'''

    '''print("for 3.0")'''
    for pref2 in facultypreference:
        day2, starttime2, endtime2 = pref2[2], pref2[3], pref2[4]
        if facultyid not in facultypairdaystime:
            facultypairdaystime[facultyid] = []

        if day + 3 == day2:
            validtimeslots = findoverlappingslots(starttime, endtime, starttime2, endtime2, 90)

            for starttimeoverlap, endtimeoverlap in validtimeslots:
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

for minorsub in subjectscheduleminor:
    departmentid=minorsub[9]
    yearlvl=minorsub[3]
    section=minorsub[4]
    day=minorsub[7]
    timestart=timetominutes(minorsub[5])
    timeend=timetominutes(minorsub[6])


    dayslist = []
    daymap = {'M': 1, 'T': 2, 'W': 3, 'Th': 4, 'F': 5, 'S': 6}
    daynum = daymap[day[0]]
    dayslist.append(daynum) 

    if len(day) == 3:
   
        daytwo = day[1:]
        daynum = daymap[daytwo]
        dayslist.append(daynum)  
    elif len(day) == 2 and day not in daymap: 
        daytwo2 = day[1]  
        daynum2 = daymap[daytwo2]
        dayslist.append(daynum2)  

    if departmentid not in minoroccupied:
        minoroccupied[departmentid] = {}

    if yearlvl not in minoroccupied[departmentid]:
        minoroccupied[departmentid][yearlvl] = {}

    if section not in minoroccupied[departmentid][yearlvl]:
        minoroccupied[departmentid][yearlvl][section] = {}

    for days in dayslist:
        if days not in minoroccupied[departmentid][yearlvl]:
            minoroccupied[departmentid][yearlvl][section][days] = {}

        for time in range(timestart, timeend, 30):
        
            if time not in minoroccupied[departmentid][yearlvl][section][days]:
                minoroccupied[departmentid][yearlvl][section][days][time] = 'occupied'


      
def minorfree(departmentid, yearlvl, section, day, time):
   
    if departmentid in minoroccupied:
        # Check if yearlvl exists in the department
        if yearlvl in minoroccupied[departmentid]:
            # Check if section exists in the year level
            if section in minoroccupied[departmentid][yearlvl]:
                # Check if day exists in the section
                if day in minoroccupied[departmentid][yearlvl][section]:
                    # Check if the specific time is occupied
                    if time in minoroccupied[departmentid][yearlvl][section][day]:
                        return minoroccupied[departmentid][yearlvl][section][day][time] != 'occupied'
    
    # If any of the keys are missing, assume the time is free
    return True  # Or handle as per your logic


subjectiteration={}
backtrackcounters={}
maxdepth=1000

def findlastfacultyasslec3(facultyid, day):
    print(f"Finding last assignment for faculty {facultyid} on day {day}")
    
  
    day1 = day
    day2 = day1 + 3 

    lastassignedtime = None

   
    for subjects in subjectschedule:
        subjectid = int(subjects[0])  
        subjectfacultyid = int(subjects[10]) 
        
        
        if facultyid == subjectfacultyid:
        
            if subjectid in assignments:
                assignment = assignments[subjectid]
                
                if (day1, day2) in assignment:
                    print('ffffffffffffffffffffffff')
                    startminutes, endminutes, (day1assigned, day2assigned), roomid = assignment
                    
                    if day1 == day1assigned or day2 == day2assigned:
                        if lastassignedtime is None or endminutes > lastassignedtime:
                            lastassignedtime = endminutes  
                            print(f"Updated last assigned time to {lastassignedtime}")

    return lastassignedtime

def findlastfacultyasslec2(facultyid, day):
    print(f"Finding last assignment for faculty {facultyid} on day {day}")
    
   
   

    lastassignedtime = None

    for subjects in subjectschedule:
        subjectid = int(subjects[0]) 
        subjectfacultyid = int(subjects[10]) 
        
        if facultyid == subjectfacultyid:
            if subjectid in assignments:
                assignment = assignments[subjectid]
                
 
                if (day) in assignment:
                    print('ffffffffffffffffffffffff')
                    startminutes, endminutes, (dayassigned), roomid = assignment
                    
                    if day == dayassigned:
                        if lastassignedtime is None or endminutes > lastassignedtime:
                            lastassignedtime = endminutes 
                            print(f"Updated last assigned time to {lastassignedtime}")
    
    return lastassignedtime



def assigntimeslot(currentsubjectid):
    global backtrackcounters
 
    
    if currentsubjectid not in backtrackcounters:
        backtrackcounters[currentsubjectid] = 0  
    for roomid, schedule in roomoccupied.items(): 
        print(f"Room {roomid}:")  
        for day in range(1, 7):
            freetimes = []  
            if day in schedule:  
                for time, status in schedule[day].items():
                    if status == 'occupied':  
                        freetimes.append(minutestotime(time)) 
        
            if freetimes: 
                print(f"  Day {day}: {', '.join(freetimes)} - Status: occupied")

    '''for facultyidpair, days in facultyoccupied.items():
        if facultyidpair != 14:
            continue
        for day, times in days.items():  
            for time, status in times.items():
                if status == "occupied":  
                    print(f"Faculty ID Pair: {facultyidpair}, Day: {day}, Time: {minutestotime(time)}, Status: {status}")'''

    '''timer.sleep(0.2)'''


       


   
   
    print("")
    '''print(f"current subject id: {currentsubjectid}")'''
    '''print(f"assignments so far: {assignments}")'''
    '''print(f"assigned subjects: {assignedsubjects}")'''
  
    '''print(currentsubjectid,"/",len(subjectschedule))'''
    if currentsubjectid >= len(subjectschedule):
        return True  
    
  
    subject = subjectschedule[currentsubjectid]
  
    subjectid = int(subject[0])
    subname = subject[12]
    units = subject[13]
    subjecttype = subject[15]
    subjectfacultyid = int(subject[10]) 
    departmentid=int(subject[9]) 
    yearlvl=int(subject[3]) 
    section=subject[4]
    requirelab = int(subject[20])  
    '''print(f"subject {subname} (id: {subjectid}, type: {subjecttype}, unit: {units}, faculty: {subjectfacultyid} )")'''

    

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
                print(f"no valid solution found for subject {currentsubjectid} after {maxdepth} backtracks.")
                
                for roomid in roomoccupied:
                    print("heeeeeeeeeeeeeee")
                    day1 = False
                    day2 = False
                    
                    
                    for day1 in range(1,3): 
                        day2 = (day1 + 3) 

                        for time in range(420, 1140, 30):
                            facultyday1 = False
                            facultyday2 = False
                            
                          
                            if (roomoccupied[roomid][day1].get(time) == 'free' and
                                roomoccupied[roomid][day1].get(time + 30) == 'free' and
                                roomoccupied[roomid][day1].get(time + 60) == 'free'):
                                day1 = True
                                start_time_day1=time
                            else:
                                continue
                            
                            if (roomoccupied[roomid][day2].get(time) == 'free' and
                                roomoccupied[roomid][day2].get(time + 30) == 'free' and
                                roomoccupied[roomid][day2].get(time + 60) == 'free'):
                                day2 = True
                              
                            else:
                                continue

                            if day1 not in facultyoccupied[subjectfacultyid]:
                                facultyoccupied[subjectfacultyid][day1] = {}
                            if day2 not in facultyoccupied[subjectfacultyid]:
                                facultyoccupied[subjectfacultyid][day2] = {}

                            if (facultyoccupied[subjectfacultyid][day1].get(time) == 'free' and
                                facultyoccupied[subjectfacultyid][day1].get(time + 30) == 'free' and
                                facultyoccupied[subjectfacultyid][day1].get(time + 60) == 'free'):
                                facultyday1 = True
                            else:
                                continue
                            if (facultyoccupied[subjectfacultyid][day2].get(time) == 'free' and
                                facultyoccupied[subjectfacultyid][day2].get(time + 30) == 'free' and
                                facultyoccupied[subjectfacultyid][day2].get(time + 60) == 'free'):
                                facultyday2 = True
                            else:
                                continue
                                 
                            
                    
                            if day1 and day2 and facultyday1 and facultyday2:     
                                
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
            if roomtype != subjecttype:
                continue    
            
            for facultyidpair, slots in facultypairdaystime.items():
                if facultyidpair not in facultyassignmentcounter:
                    facultyassignmentcounter[facultyidpair] = {}
                
                if subjectfacultyid != facultyidpair:
                    continue  
                
                for day1, day2, starttime, endtime in slots:
                    print(day1, day2, starttime, endtime)
                    timer.sleep(0)
                    day1free = day2free = facultyday1free = facultyday2free = None
                    if day1 not in facultyassignmentcounter[facultyidpair]:
                        facultyassignmentcounter[facultyidpair][day1] = 0

                    if day2 not in facultyassignmentcounter[facultyidpair]:
                        facultyassignmentcounter[facultyidpair][day2] = 0

                    
                    
         
                    if subjectid in assignedsubjects:
                        print("assigned alreadyyy")
                        continue 

                    startminutes = timetominutes(starttime)
                    end_minutes = timetominutes(endtime)
               
                    if facultyassignmentcounter[facultyidpair][day1] == 2:
                       
                        lastfacultyass= findlastfacultyasslec3(facultyidpair, day1)
                        if lastfacultyass:
                            startminutes = lastfacultyass + 90 
                        else:
                            print(f"No prior assignments for day2 {day2}")

                   
                   
                        
                    for time_slot in range(startminutes, startminutes+90, 30):
                        if time_slot==1140 :
                            day1free=False
                            break

                        if roomid not in roomoccupied:
                            roomoccupied[roomid] = {}

                        if day1 not in roomoccupied[roomid]:
                            roomoccupied[roomid][day1] = {}

                        if day2 not in roomoccupied[roomid]:
                            roomoccupied[roomid][day2] = {}

                        if roomoccupied[roomid][day1].get(time_slot) == 'occupied' and minorfree(departmentid, yearlvl, section, day1, time_slot):
                            day1free = False
                            break
                        else:
                            day1free=True

                        if roomoccupied[roomid][day2].get(time_slot) == 'occupied' and minorfree(departmentid, yearlvl, section, day2, time_slot):
                            day2free = False
                            break
                        else:
                            day2free=True

                        if facultyidpair not in facultyoccupied:
                            facultyoccupied[facultyidpair] = {}

                        if day1 not in facultyoccupied[facultyidpair]:
                            facultyoccupied[facultyidpair][day1] = {}

                        if day2 not in facultyoccupied[facultyidpair]:
                            facultyoccupied[facultyidpair][day2] = {}

                        if facultyoccupied[facultyidpair][day1].get(time_slot) == 'occupied':
                            facultyday1free = False
                            break
                        else:
                            facultyday1free=True
                    
                        if facultyoccupied[facultyidpair][day2].get(time_slot) == 'occupied':
                            facultyday2free = False
                            break
                        else:
                            facultyday2free=True
                    
                    if day1free and day2free and facultyday1free and facultyday2free:
            
                        print(f"assigned  subject {currentsubjectid} in {roomname} to this day {day2} and {day1} w/ time slot starting at {minutestotime(startminutes)} upto {minutestotime(end_minutes)}")
                
                        for time_slot3 in range(startminutes, startminutes+90, 30):
                            roomoccupied[roomid][day1][time_slot3] = 'occupied'
                            roomoccupied[roomid][day2][time_slot3] = 'occupied'
                            facultyoccupied[facultyidpair][day1][time_slot3] = 'occupied'
                            facultyoccupied[facultyidpair][day2][time_slot3] = 'occupied'
                        lastassignedtime1=startminutes+90
                        lastassignedtime2=startminutes+90
                        facultyassignmentcounter[facultyidpair][day1] += 1
                        facultyassignmentcounter[facultyidpair][day2] += 1
                        
                        assignments[subjectid] = (startminutes, startminutes + 90, (day1, day2), roomid)
                        assignedsubjects.add(subjectid)

                        if assigntimeslot(currentsubjectid+1):
                            backtrackcounters[currentsubjectid] = 0 
                            return True 
                    
                        '''print("backtracking lec 3.0")'''
                
                        for time_slot3 in range(time_slot, time_slot+90, 30):
                            roomoccupied[roomid][day1][time_slot3] = 'free'
                            roomoccupied[roomid][day2][time_slot3] = 'free'
                            facultyoccupied[facultyidpair][day1][time_slot3] = 'free'
                            facultyoccupied[facultyidpair][day2][time_slot3] = 'free'

                        facultyassignmentcounter[facultyidpair][day1] -= 1
                        facultyassignmentcounter[facultyidpair][day2] -= 1
        
                        
                        if subjectid in assignments:
                            del assignments[subjectid]
                        assignedsubjects.remove(subjectid)

            

           

                    
        elif(units==2.0):
            '''if backtrackcounters[currentsubjectid] >= maxdepth:
                print(f"No valid solution found for subject {currentsubjectid} after {maxdepth} backtracks.")
                for roomid in roomoccupied:
                    for day1 in range(1,3): 
                       

                        for time in range(420, 1140, 30):
                            facultyday1free = None
                            day1free = None
                           
                            if (roomoccupied[roomid][day1].get(time) == 'free' and
                                roomoccupied[roomid][day1].get(time + 30) == 'free' and
                                roomoccupied[roomid][day1].get(time + 60) == 'free'):
                                day1free = True
                            else:
                                day1free = False
                                continue    
                            if day1 not in facultyoccupied[subjectfacultyid]:
                                facultyoccupied[subjectfacultyid][day1] = {}

                            if (facultyoccupied[subjectfacultyid][day1].get(time) == 'free' and
                                facultyoccupied[subjectfacultyid][day1].get(time + 30) == 'free' and
                                facultyoccupied[subjectfacultyid][day1].get(time + 60) == 'free'):
                                facultyday1free = True
                            else:
                                facultyday1free = False
                                continue

                            if day1free and facultyday1free:
                                starttime = time
                                dayvalid=day1
                               

         
                            if day1free and facultyday1free:       
                                print(f"assigned alter subject {currentsubjectid} in {roomname} to this day {day1} w/ time slot starting at {minutestotime(starttime)} upto {minutestotime(starttime+120)}")
                        
                                for time_slot in range(starttime, starttime + 120, 30):
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
            if roomtype != subjecttype:
                continue   
            for facultyidlec2, slots in facultydaystimelec2.items():
                
                    
                if facultyidlec2 not in facultyassignmentcounter:
                    facultyassignmentcounter[facultyidlec2] = {}

                if subjectfacultyid!=facultyidlec2:
                    continue

                for daylec2, starttime, endtime in slots:
                    daylec2free = facultylec2free = None
                    if daylec2 not in facultyassignmentcounter[facultyidlec2]:
                        facultyassignmentcounter[facultyidlec2][daylec2] = 0

                    
                   
                    
                        
                        
                    if subjectid in assignedsubjects:
                        continue
                    
                    startminutes = timetominutes(starttime)
                    end_minutes = timetominutes(endtime)
                    
                    '''print(f" facultyidv{faculty_id} - Day {day}")
                    print(f"start time: {startminutes}")
                    print(f"end time: {end_minutes}")'''

                    if facultyassignmentcounter[facultyidlec2][daylec2] > 2:
                        continue
                    
                    if roomid not in roomoccupied:
                            roomoccupied[roomid] = {}

                    if daylec2 not in roomoccupied[roomid]:
                        roomoccupied[roomid][daylec2] = {}

                    if facultyidlec2 not in facultyoccupied:
                        facultyoccupied[facultyidlec2] = {}

                    if daylec2 not in facultyoccupied[facultyidlec2]:
                        facultyoccupied[facultyidlec2][daylec2] = {}

                    if facultyassignmentcounter[facultyidlec2][daylec2] == 2:
                       
                        lastfacultyass= findlastfacultyasslec2(facultyidlec2, daylec2)
                        if lastfacultyass:
                            startminutes = lastfacultyass + 30
                        else:
                            print(f"No prior assignments for day {daylec2}")

                    for timeslotlec2 in range(startminutes, startminutes+120, 30):
                        if timeslotlec2==1140 :
                            daylec2free=False
                            break
                        if timeslotlec2 not in roomoccupied[roomid][daylec2]:
                            roomoccupied[roomid][daylec2][timeslotlec2] = 'free'
                            
                        
                        if roomoccupied[roomid][daylec2][timeslotlec2] == 'occupied' and minorfree(departmentid, yearlvl, section, daylec2, timeslotlec2):
                            daylec2free = False
                            break
                        else:
                            daylec2free = True

                        if timeslotlec2 not in facultyoccupied[facultyidlec2][daylec2]:
                            facultyoccupied[facultyidlec2][daylec2][timeslotlec2] = 'free'
                            facultylec2free=True

                        if facultyoccupied[facultyidlec2][daylec2][timeslotlec2] == 'occupied':
                            facultylec2free = False
                            break
                        else:
                            facultylec2free = True

                    if daylec2free and facultylec2free:
                        print(f"assigned subject {currentsubjectid} to this day {daylec2} w/ time slot starting at {minutestotime(startminutes)} upto {minutestotime(startminutes)}")
                        print('')
                        for time2 in range(startminutes, startminutes+120, 30):
                            roomoccupied[roomid][daylec2][time2] = 'occupied'
                            facultyoccupied[facultyidlec2][daylec2][time2] = 'occupied'
                        
                    
                        '''print("occupying", minutestotime(time))'''
                        facultyassignmentcounter[facultyidlec2][daylec2]=facultyassignmentcounter[facultyidlec2][daylec2]+1
                        assignments[subjectid] = (startminutes, startminutes+120, daylec2, roomid)
                        assignedsubjects.add(subjectid)

                        if assigntimeslot(currentsubjectid+1):
                            backtrackcounters[currentsubjectid] = 0 
                            return True

                        '''print("Backtracking 2.0")'''
                        for time2 in range(startminutes, startminutes+120, 30):
                            roomoccupied[roomid][daylec2][time2] = 'free'
                            facultyoccupied[facultyidlec2][daylec2][time2] = 'free'
                        
                    
                        '''print("unoccupying", minutestotime(time))'''
                        facultyassignmentcounter[facultyidlec2][daylec2]=facultyassignmentcounter[facultyidlec2][daylec2]-1
                        
                        if subjectid in assignments:
                            del assignments[subjectid]

                        assignedsubjects.remove(subjectid)      
                            

        elif (units == 1.0):
            
            if backtrackcounters[currentsubjectid] >= maxdepth:
                
                print(f"No valid solution found for subject {currentsubjectid} after {maxdepth} backtracks.")
                assignmentfound=False
                for roomid in roomoccupied:
                    for day1 in range(1,6): 
                        day1true = None
                        facultyday1true = None
                        for time in range(420, 1140, 30):
                            
                            if (roomoccupied[roomid][day1].get(time) == 'free' and
                                roomoccupied[roomid][day1].get(time + 30) == 'free' and
                                roomoccupied[roomid][day1].get(time + 60) == 'free' and
                                roomoccupied[roomid][day1].get(time + 90) == 'free' and
                                roomoccupied[roomid][day1].get(time + 120) == 'free' and
                                roomoccupied[roomid][day1].get(time + 150) == 'free' and minorfree(departmentid, yearlvl, section, daylec2, time)):
                                day1true = True
                            else:
                                day1true = False
                                continue
                               
                               
                            if day1 not in facultyoccupied[subjectfacultyid]:
                                facultyoccupied[subjectfacultyid][day1] = {}

                            if (facultyoccupied[subjectfacultyid][day1].get(time) == 'free' and
                                facultyoccupied[subjectfacultyid][day1].get(time + 30) == 'free' and
                                facultyoccupied[subjectfacultyid][day1].get(time + 60) == 'free' and
                                facultyoccupied[subjectfacultyid][day1].get(time + 90) == 'free' and
                                facultyoccupied[subjectfacultyid][day1].get(time + 120) == 'free' and
                                facultyoccupied[subjectfacultyid][day1].get(time + 150) == 'free' and minorfree(departmentid, yearlvl, section, daylec2, time)):
                                facultyday1true = True   
                            else:
                                facultyday1true = False  
                                continue

                            if day1true and facultyday1true: 
                                print(f"assigned alter subject {currentsubjectid} in {roomname} to this day {day1} w/ time slot starting at {minutestotime(time)} upto {minutestotime(time+180)}")
                                print('')
                                assignmentfound = True
                                for time_slot in range(time, time+180, 30):
                                    roomoccupied[roomid][day1][time_slot] = 'occupied'
                                
                                    facultyoccupied[subjectfacultyid][day1][time_slot] = 'occupied'
                                
                                if subjectfacultyid not in facultyassignmentcounter:
                                    facultyassignmentcounter[subjectfacultyid] = {}
                                        
                                if day1 not in facultyassignmentcounter[subjectfacultyid]:
                                    facultyassignmentcounter[subjectfacultyid][day1] = 0

                                facultyassignmentcounter[subjectfacultyid][day1] += 1
                            

                                assignments[subjectid] = (time, time + 180, (day1), roomid)
                                assignedsubjects.add(subjectid)
                     
                                if assigntimeslot(currentsubjectid+1):
                                    return True 
                                
                                print("Backtracking labvf3.0000000000000000000000000000000000000000000000000")
                                for timelab3 in range(time, time+180, 30):
                                    roomoccupied[roomid][day1][timelab3] = 'free'
                                    facultyoccupied[subjectfacultyid][day1][timelab3] = 'free'
                                facultyassignmentcounter[subjectfacultyid][day1] =facultyassignmentcounter[subjectfacultyid][day1]-1
                                
                                if subjectid in assignments:
                                    del assignments[subjectid]
                                assignedsubjects.remove(subjectid)
                                
                if not assignmentfound:    
                    print("Assign TBH")
                
            else:

                for faculty_idlab, slotslab in facultydaystimelab.items():
                    
                    
                    if subjectfacultyid != faculty_idlab:
                        continue

                    if faculty_idlab not in facultyassignmentcounter:
                        facultyassignmentcounter[faculty_idlab] = {}
                    

                    for daylab, start_timelab, end_timelab in slotslab:
                        dayfreelab = facultyfreelab = None
                            
                        if daylab not in facultyassignmentcounter[faculty_idlab]:
                            facultyassignmentcounter[faculty_idlab][daylab] = 0

                        if subjectid in assignedsubjects:
                            '''print("already assigned")'''
                            

                        

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
                        
                        
                        for time_slotlab in range(start_minuteslab, start_minuteslab+180, 30):
                            
                            if (time_slotlab>=1140):
                                dayfreelab = False
                                print(subjectid)
                                break
                            if facultyassignmentcounter[faculty_idlab][daylab] == 3:
                          
                                continue
                            if time_slotlab not in roomoccupied[roomid][daylab]:
                                roomoccupied[roomid][daylab][time_slotlab] = 'free'
                            

                                '''print(f"room {roomid} {daylab} {time_slotlab} is free")'''
                            if roomoccupied[roomid][daylab][time_slotlab] == 'occupied' and minorfree(departmentid, yearlvl, section, daylab, time):
                                '''print(f"room {roomid} {daylab} {time_slotlab} is occupied")'''
                                dayfreelab = False
                                break 
                            else:
                                dayfreelab=True

                        
                            if time_slotlab not in facultyoccupied[faculty_idlab][daylab]:
                                facultyoccupied[faculty_idlab][daylab][time_slotlab] = 'free'
                                '''print(f" facuty{faculty_idlab} {daylab} {time_slotlab} is free")'''
                                
                                
                            if facultyoccupied[faculty_idlab][daylab][time_slotlab] == 'occupied':
                                '''print(f"facuty {faculty_idlab} {daylab} {time_slotlab} is occupied")'''
                                facultyfreelab = False
                                break
                            else:
                                facultyfreelab=True
                            
                        if dayfreelab and facultyfreelab:
                            print(f"assigned subject {currentsubjectid} to day {daylab} with time slot starting at {minutestotime(start_minuteslab)} up to {minutestotime(end_minuteslab)}")
                            print('')
                            for time3 in range(start_minuteslab, start_minuteslab+180, 30):
                                roomoccupied[roomid][daylab][time3] = 'occupied'
                                facultyoccupied[faculty_idlab][daylab][time3] = 'occupied'
                            facultyassignmentcounter[faculty_idlab][daylab] =facultyassignmentcounter[faculty_idlab][daylab]+1
                            assignments[subjectid] = (start_minuteslab, start_minuteslab+180, daylab, roomid)
                            assignedsubjects.add(subjectid)

                            if assigntimeslot(currentsubjectid+1):
                                backtrackcounters[currentsubjectid] = 0 
                                return True
                            
                            '''print("Backtracking lab")'''
                            for time3 in range(start_minuteslab, start_minuteslab+180, 30):
                                roomoccupied[roomid][daylab][time3] = 'free'
                                facultyoccupied[faculty_idlab][daylab][time3] = 'free'
                            facultyassignmentcounter[faculty_idlab][daylab] =facultyassignmentcounter[faculty_idlab][daylab]-1
                            
                            if subjectid in assignments:
                                del assignments[subjectid]
                            assignedsubjects.remove(subjectid)
                        
                        
    print(f"Failed to assign subject {currentsubjectid} {subname} {subjectfacultyid}, trying previous assignment.") 
    backtrackcounters[currentsubjectid] += 1
  
    print(f"")                   
    return False

counter=0
              
print("schedai starting...")
success = assigntimeslot(0)
import time as timer 

starttime = timer.time()  
print(f"Start time: {starttime}")


timer.sleep(1)  

if success:
    endtime = timer.time() 
    print(f"End time: {endtime}")
    print("Subjects are all assigned")
    print(f"Duration: {endtime - starttime:.2f} seconds")
else:
    endtime = timer.time()  
    print(f"End time: {endtime}")
    print("No valid schedule")

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

    starttimeformatted = minutestotime(starttime)
    endtimeformatted = minutestotime(endtime)

    print(f"sub id: {subjectid}")
    print(f"day cmbined: {daycombined}")
    print(f"time start: {starttimeformatted}")
    print(f"end time: {endtimeformatted}")
    print(f"id room: {roomid}")

    cursor.execute("""UPDATE subjectschedule SET day = %s, timestart = %s, timeend = %s, roomid = %s WHERE id = %s""", (daycombined, starttimeformatted, endtimeformatted, roomid, subjectid))
    conn.commit()




cursor.close()
conn.close()
