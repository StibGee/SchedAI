import mysql.connector
import time
import sys
import webbrowser

'''departmentid = int(sys.argv[1])
collegeid = int(sys.argv[2])
calendarid = int(sys.argv[3])'''
departmentid = 0
collegeid = 3
calendarid = 26
minor=False

conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="schedai"
)

cursor = conn.cursor()


if (departmentid==0):
    cursor.execute("""
        SELECT * 
        FROM `subjectschedule` 
        JOIN `subject` ON subjectschedule.subjectid = subject.id 
        JOIN department ON department.id = subjectschedule.departmentid
        LEFT JOIN (
            SELECT subjectname, COUNT(subjectname) AS specialization_count
            FROM facultysubject
            GROUP BY subjectname
        ) AS fs ON fs.subjectname = subject.commonname
        WHERE subject.focus != 'Minor' 
        AND subject.focus != 'Major2'
        AND subjectschedule.calendarid = %s 
        AND department.collegeid = %s 
        ORDER BY subjectschedule.departmentid ASC, subject.type DESC, subject.unit DESC, fs.specialization_count ASC;

    """, (calendarid, collegeid))
    subjectschedule = cursor.fetchall()

    cursor.execute("""
        SELECT COUNT(*) FROM `subjectschedule` 
        JOIN `subject` ON subjectschedule.subjectid = subject.id 
        JOIN department ON department.id=subjectschedule.departmentid
        WHERE subject.focus != 'Minor' AND subject.focus != 'Major2'
        AND subjectschedule.calendarid = %s 
        AND department.collegeid = %s 
    
        ORDER BY subjectschedule.departmentid ASC
    """, (calendarid, collegeid))
    subjectschedulecount = cursor.fetchone()

    cursor.execute("""
    SELECT * 
    FROM facultysubject
    JOIN faculty ON faculty.id = facultysubject.facultyid 
    WHERE faculty.collegeid = %s 
    ORDER BY faculty.departmentid ASC, faculty.type DESC 
    """, (collegeid,))
    facultysubject = cursor.fetchall()

    cursor.execute("""SELECT * FROM faculty WHERE faculty.collegeid = %s""", (collegeid,))
    faculty = cursor.fetchall()


    cursor.execute("""SELECT * FROM subject JOIN department ON department.id=subject.departmentid WHERE department.collegeid = %s""", (collegeid,))
    subject = cursor.fetchall()

    cursor.execute("""SELECT * FROM room WHERE collegeid=%s""", (collegeid,))
    room = cursor.fetchall()

    cursor.execute("SELECT faculty.*, facultypreferences.*, COUNT(facultysubject.facultyid) AS subject_count FROM facultypreferences JOIN faculty ON faculty.id = facultypreferences.facultyid LEFT JOIN facultysubject ON facultysubject.facultyid = facultypreferences.facultyid JOIN department ON department.id=faculty.departmentid WHERE department.collegeid=%s GROUP BY faculty.id, facultypreferences.id ORDER BY faculty.teachinghours ASC, subject_count ASC""",(collegeid,))
    facultypreference = cursor.fetchall()

    try:
        cursor.execute("SET FOREIGN_KEY_CHECKS = 0;")

        cursor.execute("""
            UPDATE `subjectschedule` 
            JOIN department ON department.id = subjectschedule.departmentid 
            SET `facultyid` = NULL, `facultyfname` = NULL, `facultylname` = NULL 
            WHERE department.collegeid = %s AND subjectschedule.calendarid = %s;
        """, (collegeid, calendarid))
        
        conn.commit()

    finally:
        cursor.execute("SET FOREIGN_KEY_CHECKS = 1;")

else:
    cursor.execute("""
        SELECT * FROM `subjectschedule` 
        JOIN `subject` ON subjectschedule.subjectid = subject.id 
        JOIN department ON department.id=subjectschedule.departmentid
        WHERE subject.focus !='Minor' 
        AND subjectschedule.calendarid = %s 
        AND department.id = %s 
        ORDER BY 
        FIELD(subject.focus, 'Major1') DESC,  
        subjectschedule.departmentid ASC
    """, (calendarid, departmentid))
    subjectschedule = cursor.fetchall()

    cursor.execute("""
        SELECT COUNT(*) FROM `subjectschedule` 
        JOIN `subject` ON subjectschedule.subjectid = subject.id 
        JOIN department ON department.id=subjectschedule.departmentid
        WHERE subject.focus !='Minor' 
        AND subjectschedule.calendarid = %s 
        AND department.id = %s 
        ORDER BY subjectschedule.departmentid ASC
    """, (calendarid, departmentid))
    subjectschedulecount = cursor.fetchone()

    cursor.execute("""
    SELECT * 
    FROM facultysubject 
    JOIN faculty ON faculty.id = facultysubject.facultyid 
    WHERE faculty.departmentid = %s 
    ORDER BY faculty.departmentid ASC, faculty.masters ASC, faculty.teachinghours DESC
    """, (departmentid,))
    facultysubject = cursor.fetchall()

    cursor.execute("""SELECT * FROM faculty WHERE faculty.departmentid = %s""", (departmentid,))
    faculty = cursor.fetchall()

    cursor.execute("""SELECT * FROM subject WHERE subject.departmentid = %s""", (departmentid,))
    subject = cursor.fetchall()

    cursor.execute("""SELECT * FROM room WHERE departmentid=%s""", (departmentid,))
    room = cursor.fetchall()

    cursor.execute("""SELECT faculty.*, facultypreferences.*, COUNT(facultysubject.facultyid) AS subject_count FROM facultypreferences JOIN faculty ON faculty.id = facultypreferences.facultyid LEFT JOIN facultysubject ON facultysubject.facultyid = facultypreferences.facultyid WHERE faculty.departmentid=%s GROUP BY faculty.id, facultypreferences.id ORDER BY faculty.teachinghours ASC, subject_count ASC""",(departmentid,))
    
    facultypreference = cursor.fetchall()
    try:
        cursor.execute("SET FOREIGN_KEY_CHECKS = 0;")

        cursor.execute("UPDATE `subjectschedule` SET `facultyid` = NULL, `facultyfname` = NULL, `facultylname` = NULL WHERE departmentid=%s AND calendarid=%s", (departmentid, calendarid))
        conn.commit()


    finally:
        cursor.execute("SET FOREIGN_KEY_CHECKS = 1;")



def facultysubjectmatch(subjectschedulesubjectname, facultysubjectfsubjectname, subjectschedulesubjectmasters, facultysubjectmasters, subjectscheduledepartmentid, facultysubjectdepartmentid):
    subject_name_match = (subjectschedulesubjectname.strip().lower() == facultysubjectfsubjectname.strip().lower())
    master_match = (subjectschedulesubjectmasters == facultysubjectmasters or (subjectschedulesubjectmasters == 'No' and facultysubjectmasters == 'Yes'))
    '''department_match = (subjectscheduledepartmentid == facultysubjectdepartmentid or facultysubjectdepartmentid == 3)'''

    '''if subject_name_match:
        print(f"Subject name matches", subjectschedulesubjectname, facultysubjectfsubjectname)
    else:
        print(f"Subject not matches", subjectschedulesubjectname, facultysubjectfsubjectname)
    
    if master_match:
        print("Master's status matches.")
   
    if department_match:
        print("Department ID matches.")'''

    return subject_name_match and master_match 

def lec3daysgapfaculty(facultyid):
    for facultypref1 in facultypreference:
        
        if facultypref1[0] == facultyid: 
            
            day1 = facultypref1[22]  
            for facultypref2 in facultypreference:
                if facultypref2[0] == facultyid:
                    day2 = facultypref2[22]
               
                    if abs(day2 - day1) == 3:
                        return True
   
    return False





def facultyworkinghourscheck(facultyworkinghours, subjectschedulesubjecthours, facultysubjectfacultyid):
    if facultyworkinghours < subjectschedulesubjecthours:
        '''print(f"{facultysubjectfacultyid} does not have enough working hours")'''
        return False
    '''print(f"{facultysubjectfacultyid} has enough working hours")'''
    return True

workinghoursleft={}
unassigned_subjects=[]
unassigned_subjects = []  
facultyworkinghours = {faculties[0]: faculties[12] for faculties in faculty}
assignedsubjects = {}
noassignment=[]
tbh=[]
backtrackcounters={}
maxdepth=100
assignedsubjectscount=0

def assign_subject(currentshubjectid):
    global assignedsubjectscount

    if currentshubjectid == len(subjectschedule):
       
        return True  
    
    if currentshubjectid not in backtrackcounters:
        backtrackcounters[currentshubjectid] = 0
            
    subjectschedules = subjectschedule[currentshubjectid]
    subjectscheduleid = subjectschedules[0]
    
    subjectschedulesubjectname = subjectschedules[25]
    subjectschedulesubjecthours = subjectschedules[18]
    subjectscheduleunit = subjectschedules[17]
    subjectscheduletype = subjectschedules[19]
    subjectschedulesubjectmasters = subjectschedules[20]
    subjectscheduledepartmentid = subjectschedules[9]
    
    if (backtrackcounters[currentshubjectid] >= maxdepth):
        
        lowesthoursfaculty = None
        lowesthours = float('inf')

        for facultysubjects in facultysubject:
            facultysubjectfacultyid = facultysubjects[1]
            facultysubjectfsubjectname = facultysubjects[2]
            facultysubjectmasters = facultysubjects[11]
            facultysubjectdepartmentid = facultysubjects[13]
            facultysubjectfname = facultysubjects[4]
            facultysubjectlname = facultysubjects[6]
            
            master_match = (subjectschedulesubjectmasters == facultysubjectmasters or 
                            (subjectschedulesubjectmasters == 'No' and facultysubjectmasters == 'Yes'))

            if master_match:
                
                if facultyworkinghours[facultysubjectfacultyid] >= subjectschedulesubjecthours:
                    if facultyworkinghours[facultysubjectfacultyid] < lowesthours:
                        lowesthours = facultyworkinghours[facultysubjectfacultyid]
                        lowesthoursfaculty = facultysubjectfacultyid

        if lowesthoursfaculty is not None:
            
            assignedsubjectscount+= 1
            facultyworkinghours[lowesthoursfaculty] -= subjectschedulesubjecthours
            assignedsubjects[subjectscheduleid] = facultysubjectfacultyid
            workinghoursleft[lowesthoursfaculty] = facultyworkinghours[lowesthoursfaculty]
            query = """
                UPDATE `subjectschedule`
                SET `facultyfname` = %s, `facultylname` = %s, `facultyid` = %s
                WHERE `id` = %s
            """
            faculty_info = next(f for f in facultysubject if f[1] == lowesthoursfaculty)
            values = (faculty_info[4], faculty_info[6], lowesthoursfaculty, subjectscheduleid)
            cursor.execute(query, values)

            conn.commit()
            cursor.execute(f"UPDATE `faculty` SET `remainingteachinghours` = {facultyworkinghours[lowesthoursfaculty]} WHERE `id` = {lowesthoursfaculty}")
            conn.commit()
            progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
            print(f"{progress-1:.2f}%: {facultysubjectfsubjectname} assigned to {facultysubjectfname} {facultysubjectlname}")
            
            sys.stdout.flush()
            
            if assign_subject(currentshubjectid + 1):
                return True
            
            '''print(f"Backtracking subject {currentshubjectid}/{len(subjectschedule)} {subjectschedulesubjectname}")'''
            assignedsubjectscount-= 1
            facultyworkinghours[lowesthoursfaculty] += subjectschedulesubjecthours
            del assignedsubjects[subjectscheduleid]
            cursor.execute(f"UPDATE `subjectschedule` SET `facultyid` = NULL WHERE `id` = {subjectscheduleid}")
            cursor.execute(f"UPDATE `faculty` SET `remainingteachinghours` = {facultyworkinghours[lowesthoursfaculty]} WHERE `id` = {facultysubjectfacultyid}")
            
        else:
            
            query = """
                UPDATE `subjectschedule`
                SET `facultyfname` = %s, `facultylname` = %s, `facultyid` = %s
                WHERE `id` = %s
            """
            values = ("New", "Faculty", 0, subjectscheduleid)

           
            cursor.execute(query, values)
            conn.commit()
        
            assignedsubjectscount += 1
            assignedsubjects[subjectscheduleid]=0
            progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
            print(f"{progress-1:.2f}%: {facultysubjectfsubjectname} assigned to {facultysubjectfname} {facultysubjectlname}")
                
            sys.stdout.flush()
            if assign_subject(currentshubjectid + 1):
                return True
            assignedsubjectscount -= 1
            del assignedsubjects[subjectscheduleid]
        
    sortedfaculty = sorted(facultysubject, key=lambda x: -facultyworkinghours[x[1]])
    
    for facultysubjects in sortedfaculty:
        facultysubjectfacultyid = facultysubjects[1]
        facultysubjectfsubjectname = facultysubjects[2]
        facultysubjectmasters = facultysubjects[11]
        facultysubjectype = facultysubjects[11]
        facultysubjectdepartmentid = facultysubjects[13]
        facultysubjectfname = facultysubjects[4]
        facultysubjectlname = facultysubjects[6]

        if (subjectscheduletype == 'Lec' and subjectscheduleunit == 3):
            if not lec3daysgapfaculty(facultysubjectfacultyid):
                
                continue
 
        
              

        if facultysubjectmatch(subjectschedulesubjectname, facultysubjectfsubjectname, subjectschedulesubjectmasters, facultysubjectmasters, subjectscheduledepartmentid, facultysubjectdepartmentid):
            
            if facultyworkinghourscheck(facultyworkinghours[facultysubjectfacultyid], subjectschedulesubjecthours, facultysubjectfacultyid):
                assignedsubjectscount+= 1
                
                facultyworkinghours[facultysubjectfacultyid] -= subjectschedulesubjecthours
                assignedsubjects[subjectscheduleid] = facultysubjectfacultyid
                workinghoursleft[facultysubjectfacultyid] = facultyworkinghours[facultysubjectfacultyid]
                '''print(f"Assigned {facultysubjectfacultyid} to {subjectscheduleid}")'''
                
                query = """
                    UPDATE `subjectschedule`
                    SET `facultyfname` = %s, `facultylname` = %s, `facultyid` = %s
                    WHERE `id` = %s
                """
                values = (facultysubjectfname, facultysubjectlname, facultysubjectfacultyid,subjectscheduleid)
                cursor.execute(query, values)

                cursor.execute(f"UPDATE `faculty` SET `remainingteachinghours` = {facultyworkinghours[facultysubjectfacultyid]} WHERE `id` = {facultysubjectfacultyid}")
                conn.commit()
                progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
                print(f"{progress-1:.2f}%: {facultysubjectfsubjectname} assigned to {facultysubjectfname} {facultysubjectlname}")
                
                sys.stdout.flush()
                if assign_subject(currentshubjectid + 1):
                    return True

                '''print(f"Backtracking subject {currentshubjectid}/{len(subjectschedule)} {subjectschedulesubjectname}")'''
                assignedsubjectscount-= 1
                facultyworkinghours[facultysubjectfacultyid] += subjectschedulesubjecthours
                del assignedsubjects[subjectscheduleid]
                cursor.execute(f"UPDATE `subjectschedule` SET `facultyid` = NULL WHERE `id` = {subjectscheduleid}")
                cursor.execute(f"UPDATE `faculty` SET `remainingteachinghours` = {facultyworkinghours[facultysubjectfacultyid]} WHERE `id` = {facultysubjectfacultyid}")
                conn.commit()
   
    print(f"failed assigning {currentshubjectid}/{len(subjectschedule)} {subjectschedulesubjectname}, trying previous assignment.")
    backtrackcounters[currentshubjectid] += 1   
    return False


facultyworkinghours = {faculties[0]: faculties[12] for faculties in faculty}
assignedsubjects = {}



if assign_subject(0):
        print("all subjects are assigned")
else:
        webbrowser.open("http://schedai.online")
conn.commit()

cursor.close()
conn.close()



'''START TIMESLOT'''
import mysql.connector
from datetime import timedelta
import time as timer
import sys
from decimal import Decimal

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
    cursor.execute("""SELECT 
    ordered_schedule.id, 
    ordered_schedule.subjectid, 
    ordered_schedule.calendarid, 
    ordered_schedule.yearlvl, 
    ordered_schedule.section, 
    ordered_schedule.timestart,
    ordered_schedule.timeend,
    ordered_schedule.day, 
    ordered_schedule.roomname, 
    ordered_schedule.departmentid, 
    ordered_schedule.facultyid, 
    ordered_schedule.subjectcode, 
    ordered_schedule.name, 
    ordered_schedule.unit, 
    ordered_schedule.hours, 
    ordered_schedule.type, 
    ordered_schedule.subject_masters, 
    ordered_schedule.focus, 
    ordered_schedule.faculty_masters, 
    ordered_schedule.startdate, 
    ordered_schedule.requirelabroom
FROM (
    SELECT 
        subjectschedule.id, 
        subjectschedule.subjectid, 
        subjectschedule.calendarid, 
        subjectschedule.yearlvl, 
        subjectschedule.section, 
        subjectschedule.timestart,
        subjectschedule.timeend,
        subjectschedule.day, 
        subjectschedule.roomname, 
        subjectschedule.departmentid, 
        subjectschedule.facultyid, 
        subject.subjectcode, 
        subject.name, 
        subject.unit, 
        subject.hours, 
        subject.type, 
        subject.masters AS subject_masters, 
        subject.focus, 
        faculty.masters AS faculty_masters, 
        faculty.startdate, 
        subject.requirelabroom
    FROM 
        subjectschedule
    JOIN 
        subject ON subjectschedule.subjectid = subject.id 
    JOIN 
        faculty ON faculty.id = subjectschedule.facultyid 
    JOIN 
        department ON department.id = subjectschedule.departmentid 
    WHERE 
        subject.focus = 'Major' 
        AND subjectschedule.calendarid = %s
        AND department.collegeid = %s
) AS ordered_schedule
ORDER BY 
    FIELD(ordered_schedule.unit,3,1,2),ordered_schedule.requirelabroom DESC,ordered_schedule.startdate ASC, ordered_schedule.departmentid ASC;""", (calendarid, collegeid))
    subjectschedule = cursor.fetchall()

    cursor.execute("""SELECT COUNT(*) FROM `subjectschedule` JOIN subject ON subjectschedule.subjectid=subject.id JOIN faculty ON faculty.id=subjectschedule.facultyid JOIN department ON department.id=subjectschedule.departmentid WHERE subject.focus='Major' AND subjectschedule.calendarid = %s AND department.collegeid = %s ORDER BY FIELD(unit, 3, 1, 2), faculty.startdate ASC """, (calendarid, collegeid))
    subjectschedulecount = cursor.fetchone()

    cursor.execute("""SELECT * FROM faculty JOIN department ON department.id=faculty.departmentid WHERE department.collegeid=%s""",(collegeid,))
    facultyall = cursor.fetchall()

    cursor.execute("SELECT * FROM facultypreferences JOIN faculty ON faculty.id=facultypreferences.facultyid JOIN department ON department.id=faculty.departmentid WHERE department.collegeid=%s AND faculty.id!=0 ORDER BY starttime ASC""",(collegeid,))
    facultypreference = cursor.fetchall()

    cursor.execute("SELECT * FROM subjectschedule JOIN subject ON subject.id=subjectschedule.subjectid JOIN department ON department.id=subjectschedule.departmentid WHERE subject.focus='Minor' AND department.collegeid=%s AND subjectschedule.calendarid=%s""",(collegeid,calendarid))
    subjectscheduleminor = cursor.fetchall()

    cursor.execute("""SELECT * FROM room WHERE collegeid=%s ORDER BY type DESC, departmentid ASC""",(collegeid,))
    room = cursor.fetchall()
    try:
        cursor.execute("SET FOREIGN_KEY_CHECKS = 0;")
        
        cursor.execute("""
            UPDATE `subjectschedule` 
            JOIN subject ON subject.id = subjectschedule.subjectid 
            JOIN department ON department.id = subjectschedule.departmentid 
            SET `timestart` = NULL, `timeend` = NULL, `day` = NULL, `roomname` = NULL, `roomid` = NULL 
            WHERE subject.focus != 'Minor' 
            AND department.collegeid = %s 
            AND subjectschedule.calendarid = %s;
        """, (collegeid, calendarid))
        
        conn.commit()

    except Exception as e:
        print(f"An error occurred: {e}")
        conn.rollback()  

    finally:
        cursor.execute("SET FOREIGN_KEY_CHECKS = 1;")

else:
    cursor.execute("""SELECT 
    ordered_schedule.id, 
    ordered_schedule.subjectid, 
    ordered_schedule.calendarid, 
    ordered_schedule.yearlvl, 
    ordered_schedule.section, 
    ordered_schedule.timestart,
    ordered_schedule.timeend,
    ordered_schedule.day, 
    ordered_schedule.roomname, 
    ordered_schedule.departmentid, 
    ordered_schedule.facultyid, 
    ordered_schedule.subjectcode, 
    ordered_schedule.name, 
    ordered_schedule.unit, 
    ordered_schedule.hours, 
    ordered_schedule.type, 
    ordered_schedule.subject_masters, 
    ordered_schedule.focus, 
    ordered_schedule.faculty_masters, 
    ordered_schedule.startdate, 
    ordered_schedule.requirelabroom
FROM (
    SELECT 
        subjectschedule.id, 
        subjectschedule.subjectid, 
        subjectschedule.calendarid, 
        subjectschedule.yearlvl, 
        subjectschedule.section, 
        subjectschedule.timestart,
        subjectschedule.timeend,
        subjectschedule.day, 
        subjectschedule.roomname, 
        subjectschedule.departmentid, 
        subjectschedule.facultyid, 
        subject.subjectcode, 
        subject.name, 
        subject.unit, 
        subject.hours, 
        subject.type, 
        subject.masters AS subject_masters, 
        subject.focus, 
        faculty.masters AS faculty_masters, 
        faculty.startdate, 
        subject.requirelabroom
    FROM 
        subjectschedule
    JOIN 
        subject ON subjectschedule.subjectid = subject.id 
    JOIN 
        faculty ON faculty.id = subjectschedule.facultyid 
    JOIN 
        department ON department.id = subjectschedule.departmentid 
    WHERE 
        subject.focus = 'Major' 
        AND subjectschedule.calendarid = %s
        AND department.id = %s
) AS ordered_schedule
ORDER BY 
    FIELD(ordered_schedule.unit,3,1,2),ordered_schedule.startdate ASC,ordered_schedule.requirelabroom DESC;""", (calendarid, departmentid))
    subjectschedule = cursor.fetchall()

    cursor.execute("""SELECT COUNT(*) FROM `subjectschedule` JOIN subject ON subjectschedule.subjectid=subject.id JOIN faculty ON faculty.id=subjectschedule.facultyid JOIN department ON department.id=subjectschedule.departmentid WHERE subject.focus='Major' AND subjectschedule.calendarid = %s AND department.id = %s ORDER BY FIELD(unit, 3, 1, 2), faculty.startdate ASC """, (calendarid, departmentid))
    subjectschedulecount = cursor.fetchone()

    cursor.execute("""SELECT * FROM faculty JOIN department ON department.id=faculty.departmentid WHERE department.id=%s""",(departmentid,))
    facultyall = cursor.fetchall()

    cursor.execute("SELECT * FROM facultypreferences JOIN faculty ON faculty.id=facultypreferences.facultyid JOIN department ON department.id=faculty.departmentid WHERE department.id=%s AND faculty.id!=0 ORDER BY starttime ASC""",(departmentid,))
    facultypreference = cursor.fetchall()

    cursor.execute("SELECT * FROM subjectschedule JOIN subject ON subject.id=subjectschedule.subjectid JOIN department ON department.id=subjectschedule.departmentid WHERE subject.focus='Minor' AND department.id=%s AND subjectschedule.calendarid=%s""",(departmentid,calendarid))
    subjectscheduleminor = cursor.fetchall()

    cursor.execute("""SELECT * FROM room WHERE departmentid=%s ORDER BY departmentid ASC""",(departmentid,))
    room = cursor.fetchall()
    try:
        cursor.execute("SET FOREIGN_KEY_CHECKS = 0;")
        
        cursor.execute("""
            UPDATE `subjectschedule` 
            JOIN subject ON subject.id = subjectschedule.subjectid 
            SET `timestart` = NULL, `timeend` = NULL, `day` = NULL, `roomname` = NULL, `roomid` = NULL 
            WHERE subject.focus != 'Minor' 
            AND subjectschedule.departmentid = %s 
            AND subjectschedule.calendarid = %s;
        """, (departmentid, calendarid))
        
        conn.commit()

    except Exception as e:
        print(f"An error occurred: {e}")
        conn.rollback()  

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
facultypreferencedays = {}


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


    facultyid = pref[1]
    day = pref[2]  
    
   
    if facultyid not in facultypreferencedays:
        facultypreferencedays[facultyid] = set()

    facultypreferencedays[facultyid].add(day)
    

for dayput in range(1, 7):
    for rm in room:  
        roomid = rm[0]
        if roomid not in roomoccupied:
            roomoccupied[roomid] = {}

        if dayput not in roomoccupied[roomid]:
            roomoccupied[roomid][dayput] = {time: 'free' for time in range(420, 1140, 30)}

    for faculty in facultyall:
        facultyid=faculty[0]
        if facultyid not in facultyoccupied:
            facultyoccupied[facultyid] = {}

        if dayput not in facultyoccupied[facultyid]:
            facultyoccupied[facultyid][dayput] = {}

        if dayput not in facultyoccupied[facultyid][dayput]:
            facultyoccupied[facultyid][dayput] = {time: 'free' for time in range(420, 1140, 30)}

        if facultyid not in facultyassignmentcounter:
            facultyassignmentcounter[facultyid] = {}

        if dayput not in facultyassignmentcounter[facultyid]:
            facultyassignmentcounter[facultyid][dayput] = 0

if minor==1:
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
        if yearlvl in minoroccupied[departmentid]:
            if section in minoroccupied[departmentid][yearlvl]:
                if day in minoroccupied[departmentid][yearlvl][section]:
                    if time in minoroccupied[departmentid][yearlvl][section][day]:
                        return minoroccupied[departmentid][yearlvl][section][day][time] != 'occupied'
    
    return True  


subjectiteration={}
backtrackcounters={}
facultyhoursday={}
maxdepth=2000 
def findlastfacultyasslec3(facultyid, day):
    '''print(f"Finding last assignment for faculty {facultyid} on day {day}")'''
    
  
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
                   
                    startminutes, endminutes, (day1assigned, day2assigned), roomid = assignment
                    
                    if day1 == day1assigned or day2 == day2assigned:
                        if lastassignedtime is None or endminutes > lastassignedtime:
                            lastassignedtime = endminutes  
                            '''print(f"Updated last assigned time to {lastassignedtime}")'''

    return lastassignedtime

def roomchecker(roomid):
    for rooms in room: 
        if rooms[0] == roomid:  # Check if the room ID matches
            roomtypelol = rooms[2]  # Get the room type from index 5
            break
    return roomtypelol


def checkroomoccupancy(roomid, day, time):
    for room in roomoccupied:
        if roomid == room:
            return roomoccupied[room][day].get(time) == 'free'
    return False  

def getfacultyhoursday(facultyid, day):
    if facultyid in facultyhoursday:
        if day in facultyhoursday[facultyid]:
            return facultyhoursday[facultyid][day]
    return None
    
def findlastfacultyasslec2(facultyid, day):
    '''print(f"Finding last assignment for faculty {facultyid} on day {day}")'''
    
   
   

    lastassignedtime = None

    for subjects in subjectschedule:
        subjectid = int(subjects[0]) 
        subjectfacultyid = int(subjects[10]) 
        
        if facultyid == subjectfacultyid:
            if subjectid in assignments:
                assignment = assignments[subjectid]
                
 
                if (day) in assignment:
                   
                    startminutes, endminutes, (dayassigned), roomid = assignment
                    
                    if day == dayassigned:
                        if lastassignedtime is None or endminutes > lastassignedtime:
                            lastassignedtime = endminutes 
                            '''print(f"Updated last assigned time to {lastassignedtime}")'''
    
    return lastassignedtime



def getfacultytype(facultyid):
    for faculty in facultyall:
        facultyids=faculty[0]
        facultytype=faculty[7]
        if facultyid==facultyids:
            return facultytype
    return None
assignedsubjectscount=0

def assigntimeslot(currentsubjectid):
    global backtrackcounters
    global assignedsubjectscount
    
    if currentsubjectid not in backtrackcounters:
        backtrackcounters[currentsubjectid] = 0  

    '''for roomid, schedule in roomoccupied.items(): 
        print(f"Room {roomid}:")  
        for day in range(1, 7):
            freetimes = []  
            if day in schedule:  
                for time, status in schedule[day].items():
                    if status == 'occupied':  
                        freetimes.append(minutestotime(time)) 
        
            if freetimes: 
                print(f"  Day {day}: {', '.join(freetimes)} - Status: occupied")'''

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
    subdept=int(subject[9]) 
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
        roomid, roomname, roomtype, roomstart, roomend, roomdeptid = rm[0], rm[1], rm[2], rm[3], rm[4], rm[5]

        

        


        '''print(f"trying subject {currentsubjectid} in room {roomname} (id: {roomid}, type: {roomtype})")'''
    
        if units == 3.0:
            if(backtrackcounters[currentsubjectid] < maxdepth):
                if roomtype != subjecttype and subjecttype!='Lec':  
                    continue 
                
                for facultyidpair, slots in facultypairdaystime.items():
                    if facultyidpair not in facultyassignmentcounter:
                        facultyassignmentcounter[facultyidpair] = {}

                    if facultyidpair not in facultyhoursday:
                        facultyhoursday[facultyidpair] = {}

                    if subjectfacultyid != facultyidpair:
                        continue  
            
                    for day1, day2, starttime, endtime in slots:
                        '''print(day1, day2, starttime, endtime)'''
                      
                        day1free = day2free = facultyday1free = facultyday2free = None
                        if day1 not in facultyhoursday[facultyidpair]:
                            facultyhoursday[facultyidpair][day1] = Decimal(0)

                        if day2 not in facultyhoursday[facultyidpair]:
                            facultyhoursday[facultyidpair][day2] = Decimal(0)

                        if day1 not in facultyassignmentcounter[facultyidpair]:
                            facultyassignmentcounter[facultyidpair][day1] = 0

                        if day2 not in facultyassignmentcounter[facultyidpair]:
                            facultyassignmentcounter[facultyidpair][day2] = 0

                        
                        
            
                        if subjectid in assignedsubjects:
                            '''print("assigned alreadyyy")'''
                            continue 

                        startminutes = timetominutes(starttime)
                        end_minutes = timetominutes(endtime)
                        if facultyassignmentcounter[facultyidpair][day1] == 4:
                            continue
                        if getfacultytype(facultyidpair)=='Regular' and getfacultyhoursday(facultyidpair, day1)>=6 and getfacultyhoursday(facultyidpair, day2)>=6:
                            continue
                        if facultyassignmentcounter[facultyidpair][day1] == 2:
                        
                            lastfacultyass= findlastfacultyasslec3(facultyidpair, day1)
                            if lastfacultyass:
                                startminutes = lastfacultyass + 90 
                            else:
                                '''print(f"No prior assignments for day2 {day2}")'''

                    
                    
                            
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
                
                            '''print(f"assigned  subject {currentsubjectid} in {roomname} to this day {day2} and {day1} w/ time slot starting at {minutestotime(startminutes)} upto {minutestotime(end_minutes)}")'''
                    
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
                            assignedsubjectscount=assignedsubjectscount+1
                            facultyhoursday[facultyidpair][day1] += Decimal(1.5)
                            facultyhoursday[facultyidpair][day2] += Decimal(1.5)
                            progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
                            print(f"{progress:.2f}%: {subname} {subjectfacultyid} assigned on {day1} and {day2} starting {startminutes}-{startminutes+90}")
                            sys.stdout.flush()
                            if assigntimeslot(currentsubjectid+1):
                                backtrackcounters[currentsubjectid] = 0 
                                return True 
                        
                            '''print("backtracking lec 3.0")'''
                            assignedsubjectscount=assignedsubjectscount-1
                            for time_slot3 in range(time_slot, time_slot+90, 30):
                                roomoccupied[roomid][day1][time_slot3] = 'free'
                                roomoccupied[roomid][day2][time_slot3] = 'free'
                                facultyoccupied[facultyidpair][day1][time_slot3] = 'free'
                                facultyoccupied[facultyidpair][day2][time_slot3] = 'free'

                            facultyassignmentcounter[facultyidpair][day1] -= 1
                            facultyassignmentcounter[facultyidpair][day2] -= 1
                            facultyhoursday[facultyidpair][day1] -= Decimal(1.5)
                            facultyhoursday[facultyidpair][day2] -= Decimal(1.5)
                            
                            if subjectid in assignments:
                                del assignments[subjectid]
                            assignedsubjects.remove(subjectid)

            if (backtrackcounters[currentsubjectid] >= maxdepth):
                for rm in room:
                    roomid, roomname, roomtype, roomstart, roomend, roomdeptid = rm[0], rm[1], rm[2], rm[3], rm[4], rm[5]

                    day1 = False
                    day2 = False
                    if subjectfacultyid in facultypreferencedays:
                        preferreddays = list(facultypreferencedays[subjectfacultyid]) 
                    
                    for dayin3 in preferreddays: 
                        day2in3 = (dayin3 + 1)

                        
                        if day2in3 == 7:
                            day2in3 = 1
                        if dayin3 not in facultyhoursday[subjectfacultyid]:
                            facultyhoursday[subjectfacultyid][dayin3] = Decimal(0)
                        if day2in3 not in facultyhoursday[subjectfacultyid]:
                            facultyhoursday[subjectfacultyid][day2in3] = Decimal(0)
                        if getfacultytype(subjectfacultyid)=='Regular' and getfacultyhoursday(subjectfacultyid, dayin3)>=6 and getfacultyhoursday(subjectfacultyid, day2in3)>=6:
                            continue
                        for time in range(420, 1140, 30):
                            if time==1140:
                                day1free=False
                                break
                            facultyday1 = False
                            facultyday2 = False
                            
                            
                            if (checkroomoccupancy(roomid, dayin3, time) and
                                checkroomoccupancy(roomid, dayin3, time+30) and
                                checkroomoccupancy(roomid, dayin3, time+60)):
                                day1 = True
                                start_time_day1=time
                            else:
                                continue
                            
                            if (checkroomoccupancy(roomid, day2in3, time) and
                                checkroomoccupancy(roomid, day2in3, time+30) and
                                checkroomoccupancy(roomid, day2in3, time+60)):
                                day2 = True
                                
                            else:
                                continue

                            if dayin3 not in facultyoccupied[subjectfacultyid]:
                                facultyoccupied[subjectfacultyid][dayin3] = {}
                            if day2in3 not in facultyoccupied[subjectfacultyid]:
                                facultyoccupied[subjectfacultyid][day2in3] = {}

                            if (facultyoccupied[subjectfacultyid][dayin3].get(time) == 'free' and
                                facultyoccupied[subjectfacultyid][dayin3].get(time + 30) == 'free' and
                                facultyoccupied[subjectfacultyid][dayin3].get(time + 60) == 'free' and minorfree(departmentid, yearlvl, section, dayin3, time)):
                                facultyday1 = True
                            else:
                                continue
                            if (facultyoccupied[subjectfacultyid][day2in3].get(time) == 'free' and
                                facultyoccupied[subjectfacultyid][day2in3].get(time + 30) == 'free' and
                                facultyoccupied[subjectfacultyid][day2in3].get(time + 60) == 'free' and minorfree(departmentid, yearlvl, section, day2in3, time)):
                                facultyday2 = True
                            else:
                                continue
                                    
                            
                    
                            if day1 and day2 and facultyday1 and facultyday2:     
                                lec3found=True
                                '''print(f"assigned alter subject {currentsubjectid} in {roomname} to this day {day2} and {day1} w/ time slot starting at {minutestotime(startminutes)} upto {minutestotime(end_minutes)}")'''
                            
                                for time_slot in range(start_time_day1, start_time_day1+90, 30):
                                    roomoccupied[roomid][dayin3][time_slot] = 'occupied'
                                    roomoccupied[roomid][day2in3][time_slot] = 'occupied'
                                    facultyoccupied[subjectfacultyid][dayin3][time_slot] = 'occupied'
                                    facultyoccupied[subjectfacultyid][day2in3][time_slot] = 'occupied'

                                facultyassignmentcounter[subjectfacultyid][dayin3] += 1
                                facultyassignmentcounter[subjectfacultyid][day2in3] += 1
                                facultyhoursday[subjectfacultyid][dayin3] += Decimal(1.5)
                                facultyhoursday[subjectfacultyid][day2in3] += Decimal(1.5)

                                assignments[subjectid] = (start_time_day1, start_time_day1 + 90, (day1, day2), roomid)
                                assignedsubjects.add(subjectid)
                                assignedsubjectscount=assignedsubjectscount+1
                                progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
                                print(f"{progress:.2f}%: {subname} {subjectfacultyid} assigned on {dayin3} and {day2in3} starting {start_time_day1}-{start_time_day1+90}")
                                sys.stdout.flush()

                                if assigntimeslot(currentsubjectid+1):
                                    return True 
                                '''print("backtracking lec 3.0")'''
                                assignedsubjectscount=assignedsubjectscount-1
                                for time_slot in range(start_time_day1, start_time_day1+90, 30):
                                    roomoccupied[roomid][dayin3][time_slot] = 'free'
                                    roomoccupied[roomid][day2][time_slot] = 'free'
                                    facultyoccupied[subjectfacultyid][dayin3][time_slot] = 'free'
                                    facultyoccupied[subjectfacultyid][day2in3][time_slot] = 'free'

                                facultyassignmentcounter[subjectfacultyid][dayin3] -= 1
                                facultyassignmentcounter[subjectfacultyid][day2in3] -= 1
                                facultyhoursday[subjectfacultyid][dayin3] -= Decimal(1.5)
                                facultyhoursday[subjectfacultyid][day2in3] -= Decimal(1.5)
                                
                                if subjectid in assignments:
                                    del assignments[subjectid]
                                assignedsubjects.remove(subjectid)
                lec3found=False
                if not lec3found:
                    for rm in room:
                        roomid, roomname, roomtype, roomstart, roomend, roomdeptid = rm[0], rm[1], rm[2], rm[3], rm[4], rm[5]

                        
                        
                        
                        for dayin3 in range(1,7): 
                            day1 = False
                            day2 = False
                            day2in3 = (dayin3 + 1)

                            
                            if day2in3 == 7:
                                day2in3 = 1
                            if getfacultytype(subjectfacultyid)=='Regular' and getfacultyhoursday(subjectfacultyid, dayin3)>=6 and getfacultyhoursday(subjectfacultyid, day2in3)>=6:
                                continue
                            for time in range(420, 1140, 30):
                                if time==1140:
                                    day1free=False
                                    break
                                facultyday1 = False
                                facultyday2 = False
                                
                                
                                if (checkroomoccupancy(roomid, dayin3, time) and
                                    checkroomoccupancy(roomid, dayin3, time+30) and
                                    checkroomoccupancy(roomid, dayin3, time+60)):
                                    day1 = True
                                    start_time_day1=time
                                else:
                                    continue
                                
                                if (checkroomoccupancy(roomid, day2in3, time) and
                                    checkroomoccupancy(roomid, day2in3, time+30) and
                                    checkroomoccupancy(roomid, day2in3, time+60)):
                                    day2 = True
                                    
                                else:
                                    continue

                                if dayin3 not in facultyoccupied[subjectfacultyid]:
                                    facultyoccupied[subjectfacultyid][dayin3] = {}
                                if day2in3 not in facultyoccupied[subjectfacultyid]:
                                    facultyoccupied[subjectfacultyid][day2in3] = {}
                                if dayin3 not in facultyhoursday[subjectfacultyid]:
                                    facultyhoursday[subjectfacultyid][dayin3] = Decimal(0)
                                if day2in3 not in facultyhoursday[subjectfacultyid]:
                                    facultyhoursday[subjectfacultyid][day2in3] = Decimal(0)

                                if (facultyoccupied[subjectfacultyid][dayin3].get(time) == 'free' and
                                    facultyoccupied[subjectfacultyid][dayin3].get(time + 30) == 'free' and
                                    facultyoccupied[subjectfacultyid][dayin3].get(time + 60) == 'free' and minorfree(departmentid, yearlvl, section, dayin3, time)):
                                    facultyday1 = True
                                else:
                                    continue
                                if (facultyoccupied[subjectfacultyid][day2in3].get(time) == 'free' and
                                    facultyoccupied[subjectfacultyid][day2in3].get(time + 30) == 'free' and
                                    facultyoccupied[subjectfacultyid][day2in3].get(time + 60) == 'free' and minorfree(departmentid, yearlvl, section, day2in3, time)):
                                    facultyday2 = True
                                else:
                                    continue
                                        
                                
                        
                                if day1 and day2 and facultyday1 and facultyday2:     
                                    lec3found=True
                                    '''print(f"assigned alter subject {currentsubjectid} in {roomname} to this day {day2} and {day1} w/ time slot starting at {minutestotime(startminutes)} upto {minutestotime(end_minutes)}")'''
                                
                                    for time_slot in range(start_time_day1, start_time_day1+90, 30):
                                        roomoccupied[roomid][dayin3][time_slot] = 'occupied'
                                        roomoccupied[roomid][day2in3][time_slot] = 'occupied'
                                        facultyoccupied[subjectfacultyid][dayin3][time_slot] = 'occupied'
                                        facultyoccupied[subjectfacultyid][day2in3][time_slot] = 'occupied'

                                    facultyassignmentcounter[subjectfacultyid][dayin3] += 1
                                    facultyassignmentcounter[subjectfacultyid][day2in3] += 1
                                    facultyhoursday[subjectfacultyid][dayin3] += Decimal(1.5)
                                    facultyhoursday[subjectfacultyid][day2in3] += Decimal(1.5)
                                    assignments[subjectid] = (start_time_day1, start_time_day1 + 90, (day1, day2), roomid)
                                    
                                    assignedsubjects.add(subjectid)
                                    assignedsubjectscount=assignedsubjectscount+1
                                    progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
                                    print(f"{progress:.2f}%: {subname} {subjectfacultyid} assigned on {dayin3} and {day2in3} starting {start_time_day1}-{start_time_day1+90}")
                                    sys.stdout.flush()

                                    if assigntimeslot(currentsubjectid+1):
                                        return True 
                                    '''print("backtracking lec 3.0")'''
                                    assignedsubjectscount=assignedsubjectscount-1
                                    for time_slot in range(start_time_day1, start_time_day1+90, 30):
                                        roomoccupied[roomid][dayin3][time_slot] = 'free'
                                        roomoccupied[roomid][day2][time_slot] = 'free'
                                        facultyoccupied[subjectfacultyid][dayin3][time_slot] = 'free'
                                        facultyoccupied[subjectfacultyid][day2in3][time_slot] = 'free'

                                    facultyassignmentcounter[subjectfacultyid][dayin3] -= 1
                                    facultyassignmentcounter[subjectfacultyid][day2in3] -= 1
                                    facultyhoursday[subjectfacultyid][dayin3] -= Decimal(1.5)
                                    facultyhoursday[subjectfacultyid][day2in3] -= Decimal(1.5)
                                    
                                    if subjectid in assignments:
                                        del assignments[subjectid]
                                    assignedsubjects.remove(subjectid)
                lec3found2=False
                if not lec3found2:
                        assignments[subjectid] = (0, 0, (0), 0)
                        assignedsubjects.add(subjectid)
                        assignedsubjectscount=assignedsubjectscount+1
                        progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
                        print(f"{progress:.2f}%: {subname} {subjectfacultyid}")
                        sys.stdout.flush()
                        if assigntimeslot(currentsubjectid+1):
                            return True 
                        
                        '''print("Backtracking h.0")'''
                        assignedsubjectscount=assignedsubjectscount-1
                        
                        
                        if subjectid in assignments:
                            del assignments[subjectid]
                        assignedsubjects.remove(subjectid)

            

           

                    
        elif(units==2.0):
            if (backtrackcounters[currentsubjectid] < maxdepth):
                if roomtype != subjecttype and not (roomtype == 'Lab' and subjecttype == 'Lec'):
                    continue

                
                for facultyidlec2, slots in facultydaystimelec2.items():
                    
                        
                    if facultyidlec2 not in facultyassignmentcounter:
                        facultyassignmentcounter[facultyidlec2] = {}

                    if facultyidlec2 not in facultyhoursday:
                        facultyhoursday[facultyidlec2] = {}

                    if subjectfacultyid!=facultyidlec2:
                        continue
                    
                    for daylec2, starttime, endtime in slots:
                        
                        daylec2free = facultylec2free = None
                        if daylec2 not in facultyassignmentcounter[facultyidlec2]:
                            facultyassignmentcounter[facultyidlec2][daylec2] = 0
                        if daylec2 not in facultyhoursday[facultyidlec2]:
                            facultyhoursday[facultyidlec2][daylec2] = Decimal(0)

                        
                
                        if subjectid in assignedsubjects:
                            continue
                            
                        if getfacultytype(facultyidlec2)=='Regular' and (getfacultyhoursday(facultyidlec2, daylec2)+2>=6) and facultyassignmentcounter[facultyidlec2][daylec2]>=2:
                            continue

                        startminutes = timetominutes(starttime)
                        end_minutes = timetominutes(endtime)

                        if facultyassignmentcounter[facultyidlec2][daylec2] == 4:
                            continue
                        
                        if roomid not in roomoccupied:
                                roomoccupied[roomid] = {}

                        if daylec2 not in roomoccupied[roomid]:
                            roomoccupied[roomid][daylec2] = {}

                        if facultyidlec2 not in facultyoccupied:
                            facultyoccupied[facultyidlec2] = {}

                        if daylec2 not in facultyoccupied[facultyidlec2]:
                            facultyoccupied[facultyidlec2][daylec2] = {}


                        for timeslotlec2 in range(startminutes, startminutes+120, 30):
                            if timeslotlec2>=1140:
                                daylec2free=False
                            
                                break
                            if timeslotlec2 not in roomoccupied[roomid][daylec2]:
                                roomoccupied[roomid][daylec2][timeslotlec2] = 'free'
                                daylec2free=True
                            
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
                            '''print(f"assigned subject {currentsubjectid} to this day {daylec2} w/ time slot starting at {minutestotime(startminutes)} upto {minutestotime(startminutes)}")
                            print('')'''
                            for time2 in range(startminutes, startminutes+120, 30):
                                roomoccupied[roomid][daylec2][time2] = 'occupied'
                                facultyoccupied[facultyidlec2][daylec2][time2] = 'occupied'
                            
                        
                            '''print("occupying", minutestotime(time))'''
                            facultyassignmentcounter[facultyidlec2][daylec2]=facultyassignmentcounter[facultyidlec2][daylec2]+1
                            facultyhoursday[facultyidlec2][daylec2]=facultyhoursday[facultyidlec2][daylec2]+2
                            assignments[subjectid] = (startminutes, startminutes+120, daylec2, roomid)
                            assignedsubjects.add(subjectid)
                            assignedsubjectscount=assignedsubjectscount+1
                            progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
                            print(f"{progress:.2f}%: {subname} {subjectfacultyid} assigned on {daylec2} starting {startminutes}-{startminutes+120}")
                            sys.stdout.flush()
                            if assigntimeslot(currentsubjectid+1):
                                
                                return True

                            '''print("Backtracking 2.0")'''
                            assignedsubjectscount=assignedsubjectscount-1
                            for time2 in range(startminutes, startminutes+120, 30):
                                roomoccupied[roomid][daylec2][time2] = 'free'
                                facultyoccupied[facultyidlec2][daylec2][time2] = 'free'
                            
                        
                            '''print("unoccupying", minutestotime(time))'''
                            facultyassignmentcounter[facultyidlec2][daylec2]=facultyassignmentcounter[facultyidlec2][daylec2]-1
                            facultyhoursday[facultyidlec2][daylec2]-=2
                            if subjectid in assignments:
                                del assignments[subjectid]

                            assignedsubjects.remove(subjectid)      
                            
            elif (backtrackcounters[currentsubjectid] >= maxdepth):
                
                for rm in room:
                    roomid, roomname, roomtype, roomstart, roomend, roomdeptid = rm[0], rm[1], rm[2], rm[3], rm[4], rm[5]
                    if subjectfacultyid in facultypreferencedays:
                        preferreddays = list(facultypreferencedays[subjectfacultyid]) 

                    if roomtype != subjecttype and not (roomtype == 'Lab' and subjecttype == 'Lec'):
                        continue

                        
                    for daylabbacktrack in preferreddays: 
                        if facultyassignmentcounter[subjectfacultyid][daylabbacktrack] == 4:
                            continue
                        facultyday1free = False
                        day1free = False

                        if subjectfacultyid not in facultyhoursday:
                            facultyhoursday[subjectfacultyid]={}

                        if daylabbacktrack not in facultyhoursday:
                            facultyhoursday[subjectfacultyid][daylabbacktrack]=Decimal(0)

                        if getfacultytype(subjectfacultyid)=='Regular':
                            if getfacultyhoursday(subjectfacultyid, daylabbacktrack)>=6:
                                continue
                        for timebacktrack in range(420, 1140, 30):
                            if timebacktrack>=1140:
                                day1free=False
                                
                                break
                            if (checkroomoccupancy(roomid, daylabbacktrack, timebacktrack) and
                                checkroomoccupancy(roomid, daylabbacktrack, timebacktrack+30) and
                                checkroomoccupancy(roomid, daylabbacktrack, timebacktrack+60) and checkroomoccupancy(roomid, daylabbacktrack, timebacktrack+90) and minorfree(departmentid, yearlvl, section, daylabbacktrack, timebacktrack)):
                                day1free = True
                            else:
                                day1free = False
                                continue    
                            if daylabbacktrack not in facultyoccupied[subjectfacultyid]:
                                facultyoccupied[subjectfacultyid][daylabbacktrack] = {}

                            if (facultyoccupied[subjectfacultyid][daylabbacktrack].get(timebacktrack) == 'free' and
                                facultyoccupied[subjectfacultyid][daylabbacktrack].get(timebacktrack + 30) == 'free' and
                                facultyoccupied[subjectfacultyid][daylabbacktrack].get(timebacktrack + 60) == 'free' and facultyoccupied[subjectfacultyid][daylabbacktrack].get(timebacktrack + 90) == 'free' and minorfree(departmentid, yearlvl, section, daylabbacktrack, timebacktrack)):
                                facultyday1free = True
                            else:
                                facultyday1free = False
                                continue

                            if day1free and facultyday1free:
                                starttime = timebacktrack
                                dayvalid=daylabbacktrack
                                

            
                            if day1free and facultyday1free:         
                                '''print(f"assigned alter subject {currentsubjectid} in {roomname} to this day {daylabbacktrack} w/ time slot starting at {minutestotime(starttime)} upto {minutestotime(starttime+120)}")'''
                        
                                for time_slot in range(starttime, starttime + 120, 30):
                                    roomoccupied[roomid][dayvalid][time_slot] = 'occupied'
                        
                                    facultyoccupied[subjectfacultyid][dayvalid][time_slot] = 'occupied'
                
                                if subjectfacultyid not in facultyassignmentcounter:
                                    facultyassignmentcounter[subjectfacultyid] = {}
                                        
                                if dayvalid not in facultyassignmentcounter[subjectfacultyid]:
                                    facultyassignmentcounter[subjectfacultyid][dayvalid] = 0

                                if dayvalid not in facultyhoursday[subjectfacultyid]:
                                    facultyhoursday[subjectfacultyid][dayvalid] = Decimal(0)

                    
                                facultyassignmentcounter[subjectfacultyid][dayvalid] += 1
                                facultyhoursday[subjectfacultyid][dayvalid] += 2
                                lec2found=True
                                assignments[subjectid] = (starttime, starttime + 120, (dayvalid), roomid)
                                assignedsubjects.add(subjectid)
                                assignedsubjectscount=assignedsubjectscount+1
                                progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
                                print(f"{progress:.2f}%: {subname} {subjectfacultyid} assigned on {dayvalid} starting {starttime}-{starttime+120}")
                                sys.stdout.flush()

                                if assigntimeslot(currentsubjectid+1):
                                    return True 
                                        
                                '''print("Backtracking 2.0")'''
                                assignedsubjectscount=assignedsubjectscount-1
                                for time2backtrack in range(starttime, starttime+120, 30):
                                    roomoccupied[roomid][dayvalid][time2backtrack] = 'free'
                                    facultyoccupied[subjectfacultyid][dayvalid][time2backtrack] = 'free'
                                facultyhoursday[subjectfacultyid][dayvalid] -= 2
                            
                                '''print("unoccupying", minutestotime(time))'''
                                facultyassignmentcounter[subjectfacultyid][dayvalid]=facultyassignmentcounter[subjectfacultyid][dayvalid]-1
                                
                                if subjectid in assignments:
                                    del assignments[subjectid]

                                assignedsubjects.remove(subjectid) 

                lec2found=False
                if not lec2found: 
                    
            
                    for rm in room:
                        roomid, roomname, roomtype, roomstart, roomend, roomdeptid = rm[0], rm[1], rm[2], rm[3], rm[4], rm[5]
                        if roomtype != subjecttype and not (roomtype == 'Lab' and subjecttype == 'Lec'):
                            continue

                        for daybacktracklec2 in range(1,7): 
                            day1true = None
                            facultyday1true = None
                            if daybacktracklec2 not in facultyhoursday[subjectfacultyid]:
                                facultyhoursday[subjectfacultyid][daybacktracklec2] = Decimal(0)
                            if getfacultytype(subjectfacultyid)=='Regular' and getfacultyhoursday(subjectfacultyid, daybacktracklec2)>=6:
                                continue
                            for time in range(420, 1140, 30):
                                if time>=1140:
                                    day1true=False
                                    
                                    break
                                if (checkroomoccupancy(roomid, daybacktracklec2, time) and
                                    checkroomoccupancy(roomid, daybacktracklec2, time+30) and
                                    checkroomoccupancy(roomid, daybacktracklec2, time+60) and
                                    checkroomoccupancy(roomid, daybacktracklec2, time+90) and minorfree(departmentid, yearlvl, section, daybacktracklec2, time)):
                                    day1true = True
                                else:
                                    day1true = False
                                    continue
                                
                                
                                if daybacktracklec2 not in facultyoccupied[subjectfacultyid]:
                                    facultyoccupied[subjectfacultyid][daybacktracklec2] = {}

                                if (facultyoccupied[subjectfacultyid][daybacktracklec2].get(time) == 'free' and
                                    facultyoccupied[subjectfacultyid][daybacktracklec2].get(time + 30) == 'free' and
                                    facultyoccupied[subjectfacultyid][daybacktracklec2].get(time + 60) == 'free' and
                                    facultyoccupied[subjectfacultyid][daybacktracklec2].get(time + 90) == 'free' and minorfree(departmentid, yearlvl, section, daybacktracklec2, time)):
                                    facultyday1true = True   
                                else:
                                    facultyday1true = False  
                                    continue

                                if day1true and facultyday1true: 
                                    lec2found2=True
                                    '''print(f"assigned alter subject {currentsubjectid} in {roomname} to this day {daybacktracklec2} w/ time slot starting at {minutestotime(time)} upto {minutestotime(time+180)}")
                                    print('')'''
                                  
                                    for time_slot in range(time, time+120, 30):
                                        roomoccupied[roomid][daybacktracklec2][time_slot] = 'occupied'
                                    
                                        facultyoccupied[subjectfacultyid][daybacktracklec2][time_slot] = 'occupied'
                                    
                                    if subjectfacultyid not in facultyassignmentcounter:
                                        facultyassignmentcounter[subjectfacultyid] = {}
                                            
                                    if daybacktracklec2 not in facultyassignmentcounter[subjectfacultyid]:
                                        facultyassignmentcounter[subjectfacultyid][daybacktracklec2] = 0

                                    facultyassignmentcounter[subjectfacultyid][daybacktracklec2] += 1
                                

                                    assignments[subjectid] = (time, time + 120, (daybacktracklec2), roomid)
                                    assignedsubjects.add(subjectid)
                                    assignedsubjectscount=assignedsubjectscount+1
                                    facultyhoursday[subjectfacultyid][daybacktracklec2] += 2
                                    progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
                                    print(f"{progress:.2f}%: {subname} {subjectfacultyid} assigned on {daybacktracklec2} starting {time}-{time+120}")
                                    sys.stdout.flush()
                                    if assigntimeslot(currentsubjectid+1):
                                        return True 
                                    
                                    '''print("Backtracking h.0")'''
                                    assignedsubjectscount=assignedsubjectscount-1
                                    for timelab3 in range(time, time+120, 30):
                                        roomoccupied[roomid][daybacktracklec2][timelab3] = 'free'
                                        facultyoccupied[subjectfacultyid][daybacktracklec2][timelab3] = 'free'
                                    facultyassignmentcounter[subjectfacultyid][daybacktracklec2] =facultyassignmentcounter[subjectfacultyid][daybacktracklec2]-1
                                    facultyhoursday[subjectfacultyid][daybacktracklec2] -=2
                                    if subjectid in assignments:
                                        del assignments[subjectid]
                                    assignedsubjects.remove(subjectid)

                    lec2found2=False
                    if not lec2found2:
                        assignments[subjectid] = (0, 0, (0), 0)
                        assignedsubjects.add(subjectid)
                        assignedsubjectscount=assignedsubjectscount+1
                        progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
                        print(f"{progress:.2f}%: {subname} {subjectfacultyid}")
                        sys.stdout.flush()
                        if assigntimeslot(currentsubjectid+1):
                            return True 
                        
                        '''print("Backtracking h.0")'''
                        assignedsubjectscount=assignedsubjectscount-1
                        
                        
                        if subjectid in assignments:
                            del assignments[subjectid]
                        assignedsubjects.remove(subjectid)
                                
            

        elif (units == 1.0):
            
            if(backtrackcounters[currentsubjectid] < maxdepth):
                if roomtype != subjecttype and  requirelab==1: 
                    continue   
                
                for faculty_idlab, slotslab in facultydaystimelab.items():
                    
                    
                    if subjectfacultyid != faculty_idlab:
                        continue

                    if faculty_idlab not in facultyassignmentcounter:
                        facultyassignmentcounter[faculty_idlab] = {}

                   

                    if faculty_idlab not in facultyhoursday:
                        facultyhoursday[faculty_idlab] = {}
                    

                    for daylab, start_timelab, end_timelab in slotslab:
                        
                            
                        dayfreelab = facultyfreelab = None
                            
                        if daylab not in facultyassignmentcounter[faculty_idlab]:
                            facultyassignmentcounter[faculty_idlab][daylab] = 0
                        if daylab not in facultyhoursday[faculty_idlab]:
                            facultyhoursday[faculty_idlab][daylab] = Decimal(0)
                            
                        if getfacultytype(subjectfacultyid)=='Regular':
                            if getfacultyhoursday(faculty_idlab, daylab)+3>=6:
                                continue
                        if subjectid in assignedsubjects:
                            '''print("already assigned")'''
                            
                        start_minuteslab = timetominutes(start_timelab)
                        end_minuteslab = timetominutes(end_timelab)
                       
                        if facultyassignmentcounter[faculty_idlab][daylab]>=2:
                            start_minuteslab=start_minuteslab+120
                        if roomid not in roomoccupied:
                            roomoccupied[roomid] = {}
                        if daylab not in roomoccupied[roomid]:
                            roomoccupied[roomid][daylab] = {}

                        if faculty_idlab not in facultyoccupied:
                            facultyoccupied[faculty_idlab] = {}

                        if daylab not in facultyoccupied[faculty_idlab]:
                            facultyoccupied[faculty_idlab][daylab] = {}

                        if facultyassignmentcounter[faculty_idlab][daylab] == 4:
                            continue
                        
                        for time_slotlab in range(start_minuteslab, start_minuteslab+180, 30):
                            
                            if (time_slotlab>=1140):
                                dayfreelab = False
                
                                break
                        
                            if time_slotlab not in roomoccupied[roomid][daylab]:
                                roomoccupied[roomid][daylab][time_slotlab] = 'free'
                            

                                '''print(f"room {roomid} {daylab} {time_slotlab} is free")'''
                            if roomoccupied[roomid][daylab][time_slotlab] == 'occupied' and minorfree(departmentid, yearlvl, section, daylab, time_slotlab):
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

                            '''print(f"assigned subject {currentsubjectid} to day {daylab} with time slot starting at {minutestotime(start_minuteslab)} up to {minutestotime(end_minuteslab)}")
                            print('')'''
                            for time3 in range(start_minuteslab, start_minuteslab+180, 30):
                                roomoccupied[roomid][daylab][time3] = 'occupied'
                                facultyoccupied[faculty_idlab][daylab][time3] = 'occupied'
                            facultyassignmentcounter[faculty_idlab][daylab] =facultyassignmentcounter[faculty_idlab][daylab]+1
                            facultyhoursday[faculty_idlab][daylab]=facultyhoursday[faculty_idlab][daylab]+3
                            assignments[subjectid] = (start_minuteslab, start_minuteslab+180, daylab, roomid)
                            assignedsubjects.add(subjectid)
                            assignedsubjects.add(subjectid)
                            assignedsubjectscount=assignedsubjectscount+1
                            progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
                            print(f"{progress:.2f}%: {subname} {subjectfacultyid} assigned on {daylab} starting {start_minuteslab}-{start_minuteslab+180}")
                            sys.stdout.flush()
                            if assigntimeslot(currentsubjectid+1): 
                                return True
                            
                            '''print("Backtracking lab")'''
                            assignedsubjectscount=assignedsubjectscount-1
                            for time3 in range(start_minuteslab, start_minuteslab+180, 30):
                                roomoccupied[roomid][daylab][time3] = 'free'
                                facultyoccupied[faculty_idlab][daylab][time3] = 'free'
                            facultyassignmentcounter[faculty_idlab][daylab] =facultyassignmentcounter[faculty_idlab][daylab]-1
                            facultyhoursday[faculty_idlab][daylab] -=3
                            if subjectid in assignments:
                                del assignments[subjectid]
                            assignedsubjects.remove(subjectid)
                       
        
            elif(backtrackcounters[currentsubjectid] >= maxdepth):
            
                for rm in room:
                    roomid, roomname, roomtype, roomstart, roomend, roomdeptid = rm[0], rm[1], rm[2], rm[3], rm[4], rm[5]
                    if subjectfacultyid in facultydaystimelab:
                        preferreddays = list(set(entry[0] for entry in facultydaystimelab[subjectfacultyid]))
                    
                    if roomchecker(roomid)=='Lec' and requirelab==1: 
                        continue
                    
                        
                    for day1 in preferreddays: 
                        
                        day1true = None
                        facultyday1true = None
                        if getfacultytype(subjectfacultyid)=='Regular' and getfacultyhoursday(subjectfacultyid, day1)>=6:
                            continue
                        
                        for time in range(420, 1140, 30):
                            
                            if (checkroomoccupancy(roomid, day1, time) and
                                checkroomoccupancy(roomid, day1, time+30) and
                                checkroomoccupancy(roomid, day1, time+60) and
                                checkroomoccupancy(roomid, day1, time+90) and
                                checkroomoccupancy(roomid, day1, time+120) and
                                checkroomoccupancy(roomid, day1, time+150) and minorfree(departmentid, yearlvl, section, day1, time)):
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
                                facultyoccupied[subjectfacultyid][day1].get(time + 150) == 'free' and minorfree(departmentid, yearlvl, section, day1, time)):
                                facultyday1true = True   
                            else:
                                facultyday1true = False  
                                continue

                            if day1true and facultyday1true: 
                            
                                '''print(f"assigned alter subject {currentsubjectid} in {roomname} to this day {day1} w/ time slot starting at {minutestotime(time)} upto {minutestotime(time+180)}")'''
                                '''print('')'''
                                assignmentfoundlab = True
                                for time_slot in range(time, time+180, 30):
                                    roomoccupied[roomid][day1][time_slot] = 'occupied'
                                
                                    facultyoccupied[subjectfacultyid][day1][time_slot] = 'occupied'
                                
                                if subjectfacultyid not in facultyassignmentcounter:
                                    facultyassignmentcounter[subjectfacultyid] = {}
                                        
                                if day1 not in facultyassignmentcounter[subjectfacultyid]:
                                    facultyassignmentcounter[subjectfacultyid][day1] = 0

                                facultyassignmentcounter[subjectfacultyid][day1] += 1
                                facultyhoursday[subjectfacultyid][day1] =facultyhoursday[subjectfacultyid][day1]+3

                                assignments[subjectid] = (time, time + 180, (day1), roomid)
                                assignedsubjects.add(subjectid)
                                assignedsubjectscount=assignedsubjectscount+1
                                progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
                                print(f"{progress:.2f}%: {subname} {subjectfacultyid} assigned on {day1} starting {time}-{time+180}")
                                sys.stdout.flush()
                                if assigntimeslot(currentsubjectid+1):
                                    return True 
                                
                                '''print("Backtracking labvf3.0")'''
                                assignedsubjectscount=assignedsubjectscount-1
                                for timelab3 in range(time, time+180, 30):
                                    roomoccupied[roomid][day1][timelab3] = 'free'
                                    facultyoccupied[subjectfacultyid][day1][timelab3] = 'free'
                                facultyassignmentcounter[subjectfacultyid][day1] =facultyassignmentcounter[subjectfacultyid][day1]-1
                                facultyhoursday[subjectfacultyid][day1]-=3
                                if subjectid in assignments:
                                    del assignments[subjectid]
                                assignedsubjects.remove(subjectid)
                labfound=False

                if not labfound:
                    for rm in room:
                        roomid, roomname, roomtype, roomstart, roomend, roomdeptid = rm[0], rm[1], rm[2], rm[3], rm[4], rm[5]
                        for day1 in range(1,7): 
                            if subjectfacultyid not in facultyhoursday:
                                facultyhoursday[subjectfacultyid] = {}
                            if day1 not in facultyhoursday[subjectfacultyid]:
                                facultyhoursday[subjectfacultyid][day1] = Decimal(0)
                            if getfacultytype(subjectfacultyid)=='Regular' and getfacultyhoursday(subjectfacultyid, day1)>=6:
                                continue
                            day1true = None
                            facultyday1true = None
                            for time in range(420, 1140, 30):
                                if time>=1140:
                                    day1true=False
                                    
                                    break
                                if (checkroomoccupancy(roomid, day1, time) and
                                    checkroomoccupancy(roomid, day1, time+30) and
                                    checkroomoccupancy(roomid, day1, time+60) and
                                    checkroomoccupancy(roomid, day1, time+90) and
                                    checkroomoccupancy(roomid, day1, time+120) and
                                    checkroomoccupancy(roomid, day1, time+150) and minorfree(departmentid, yearlvl, section, day1, time)):
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
                                    facultyoccupied[subjectfacultyid][day1].get(time + 150) == 'free' and minorfree(departmentid, yearlvl, section, day1, time)):
                                    facultyday1true = True   
                                else:
                                    facultyday1true = False  
                                    continue

                                if day1true and facultyday1true:
                                    labfound2=True
                                  
                                    '''print(f"assigned alter subject {currentsubjectid} in {roomname} to this day {day1} w/ time slot starting at {minutestotime(time)} upto {minutestotime(time+180)}")
                                    print('')'''
                                
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
                                    facultyhoursday[subjectfacultyid][day1] += 3
                                    assignedsubjectscount=assignedsubjectscount+1
                                    progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
                                    print(f"{progress:.2f}%: {subname} {subjectfacultyid} assigned on {day1} starting {time}-{time+180}")
                                    sys.stdout.flush()
                                    if assigntimeslot(currentsubjectid+1):
                                        return True 
                                    
                                    '''print("Backtracking labvf3.0")'''
                                    facultyhoursday[subjectfacultyid][day1] -=3
                                    assignedsubjectscount=assignedsubjectscount-1
                                    for timelab3 in range(time, time+180, 30):
                                        roomoccupied[roomid][day1][timelab3] = 'free'
                                        facultyoccupied[subjectfacultyid][day1][timelab3] = 'free'
                                    facultyassignmentcounter[subjectfacultyid][day1] =facultyassignmentcounter[subjectfacultyid][day1]-1
                                    
                                    if subjectid in assignments:
                                        del assignments[subjectid]
                                    assignedsubjects.remove(subjectid)

                    labfound2=False
                    if not labfound2:
                        assignments[subjectid] = (0, 0, (0), 0)
                        assignedsubjects.add(subjectid)
                        assignedsubjectscount=assignedsubjectscount+1
                        progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
                        print(f"{progress:.2f}%: {subname} {subjectfacultyid}")
                        sys.stdout.flush()
                        if assigntimeslot(currentsubjectid+1):
                            return True 
                        
                        '''print("Backtracking h.0")'''
                        assignedsubjectscount=assignedsubjectscount-1
                        
                        
                        if subjectid in assignments:
                            del assignments[subjectid]
                        assignedsubjects.remove(subjectid)

            
                    
                        
    print(f"Failed to assign subject {currentsubjectid} {subname} {(subjecttype)} Units: {units} Faculty id: {subjectfacultyid}, trying previous assignment.") 
   
    backtrackcounters[currentsubjectid] += 1
  
    print(f"")                   
    return False

counter=0
              
print("schedai starting...")
success = assigntimeslot(0)
import time as timer 

starttime = timer.time()  
print(f"Start time: {starttime}")


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
