import mysql.connector
import time
import sys
import webbrowser

depid = int(sys.argv[1])
collegeid = int(sys.argv[2])
calendarid = int(sys.argv[3])
'''depid = 0
collegeid = 3
calendarid = 57'''
minor=0

conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="schedai"
)

cursor = conn.cursor()


if (depid==0):
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
        ORDER BY subjectschedule.departmentid ASC, subject.type DESC, subject.unit DESC, fs.specialization_count ASC, subject.name ASC;

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
        ORDER BY faculty.departmentid ASC, faculty.type DESC, teachinghours ASC 
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
        AND department.id = %s 
        ORDER BY subjectschedule.departmentid ASC, subject.type DESC, subject.unit DESC, fs.specialization_count ASC, subject.name ASC;
    """, (calendarid, depid))
    subjectschedule = cursor.fetchall()

    cursor.execute("""
        SELECT COUNT(*) FROM `subjectschedule` 
        JOIN `subject` ON subjectschedule.subjectid = subject.id 
        JOIN department ON department.id=subjectschedule.departmentid
        WHERE subject.focus != 'Minor' AND subject.focus != 'Major2'
        AND subjectschedule.calendarid = %s 
        AND department.id = %s 
        ORDER BY subjectschedule.departmentid ASC
    """, (calendarid, depid))
    subjectschedulecount = cursor.fetchone()

    cursor.execute("""
        SELECT * 
        FROM facultysubject
        JOIN faculty ON faculty.id = facultysubject.facultyid 
        WHERE faculty.departmentid = %s 
        ORDER BY faculty.departmentid ASC, faculty.type DESC, teachinghours ASC 
    """, (depid,))
    facultysubject = cursor.fetchall()

    cursor.execute("""SELECT * FROM faculty WHERE faculty.departmentid = %s""", (depid,))
    faculty = cursor.fetchall()

    cursor.execute("""SELECT * FROM subject WHERE subject.departmentid = %s""", (depid,))
    subject = cursor.fetchall()

    cursor.execute("""SELECT * FROM room WHERE departmentid=%s""", (depid,))
    room = cursor.fetchall()

    cursor.execute("""SELECT faculty.*, facultypreferences.*, COUNT(facultysubject.facultyid) AS subject_count FROM facultypreferences JOIN faculty ON faculty.id = facultypreferences.facultyid LEFT JOIN facultysubject ON facultysubject.facultyid = facultypreferences.facultyid JOIN department ON department.id=faculty.departmentid WHERE department.id=%s GROUP BY faculty.id, facultypreferences.id ORDER BY faculty.teachinghours ASC, subject_count ASC""",(depid,))
    facultypreference = cursor.fetchall()
    try:
        cursor.execute("SET FOREIGN_KEY_CHECKS = 0;")

        cursor.execute("UPDATE `subjectschedule` SET `facultyid` = NULL, `facultyfname` = NULL, `facultylname` = NULL WHERE depid=%s AND calendarid=%s", (depid, calendarid))
        conn.commit()


    finally:
        cursor.execute("SET FOREIGN_KEY_CHECKS = 1;")



def facultysubjectmatch(subjectschedulesubjectname, facultysubjectfsubjectname, subjectschedulesubjectmasters, facultysubjectmasters, subjectscheduledepartmentid, facultysubjectdepartmentid):
    subject_name_match = (subjectschedulesubjectname.strip().lower() == facultysubjectfsubjectname.strip().lower())
    master_match = (subjectschedulesubjectmasters == facultysubjectmasters or (subjectschedulesubjectmasters == 'No' and facultysubjectmasters == 'Yes'))

    return subject_name_match and master_match 

def lec3daysgapfaculty(facultyid):
    for facultypref1 in facultypreference:
        
        if facultypref1[0] == facultyid: 
            
            day1 = facultypref1[23]  
            for facultypref2 in facultypreference:
                if facultypref2[0] == facultyid:
                    day2 = facultypref2[23]
               
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
maxdepth=1
assignedsubjectscount=0
newfaculty={}



def assign_subject(currentshubjectid):
    global collegeid
    global depid
    global assignedsubjectscount
    if (depid==0):
        cursor.execute("""
            SELECT * 
            FROM facultysubject
            JOIN faculty ON faculty.id = facultysubject.facultyid 
            WHERE faculty.collegeid = %s 
            ORDER BY faculty.departmentid ASC, faculty.type DESC, teachinghours ASC 
            """, (collegeid,))
        facultysubject1 = cursor.fetchall()  
        cursor.execute("""SELECT * FROM faculty WHERE faculty.collegeid = %s""", (collegeid,))
        faculty1 = cursor.fetchall()
    else:
        cursor.execute("""
            SELECT * 
            FROM facultysubject
            JOIN faculty ON faculty.id = facultysubject.facultyid 
            WHERE faculty.departmentid = %s 
            ORDER BY faculty.departmentid ASC, faculty.type DESC, teachinghours ASC 
            """, (depid,))
        facultysubject1 = cursor.fetchall()  
        cursor.execute("""SELECT * FROM faculty WHERE faculty.departmentid = %s""", (depid,))
        faculty1 = cursor.fetchall()
    

    for faculties in faculty1:
        faculty_id = faculties[0]  
        teaching_hours = faculties[12]  

        
        if faculty_id not in facultyworkinghours:
            facultyworkinghours[faculty_id] = teaching_hours

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

        sortedfaculty1 = sorted(facultysubject1, key=lambda x: (x[13] != subjectscheduledepartmentid))
        for facultysubjects in sortedfaculty1:
            facultysubjectfacultyid = facultysubjects[3]
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
            values = (facultysubjectfname, facultysubjectlname, lowesthoursfaculty, subjectscheduleid)
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
            newfaculty[currentshubjectid]=True
            query = """
                INSERT INTO `faculty` (`fname`, `lname`, `type`, `teachinghours`,`masters`,`collegeid`,`departmentid`)
                VALUES (%s, %s, %s, %s, %s, %s, %s)
            """
            values = ("NEW", 'FACULTY', 'Contractual', 30, 'Yes',collegeid,facultysubjectdepartmentid)
            cursor.execute(query, values)
            conn.commit()
            newfacultyid = cursor.lastrowid

            query = """
                INSERT INTO `facultysubject` (`facultyid`, `subjectname`)
                VALUES (%s, %s)
            """
            values = (newfacultyid, subjectschedulesubjectname)
            cursor.execute(query, values)
            conn.commit()

            for i in range(1,7):
                if i==7:
                    break
                query = """
                    INSERT INTO `facultypreferences` (`facultyid`, `day`,`starttime`,`endtime`)
                    VALUES (%s, %s,%s, %s)
                """
                values = (newfacultyid, i, '07:00', '19:00')
                cursor.execute(query, values)
                conn.commit()

            backtrackcounters[currentshubjectid]=0
            
            if assign_subject(currentshubjectid):
                return True

   

    sortedfaculty = sorted(facultysubject1, key=lambda x: (x[13] != subjectscheduledepartmentid))
    
    for facultysubjects in sortedfaculty:
        facultysubjectfacultyid = facultysubjects[3]
        facultysubjectfsubjectname = facultysubjects[2]
        facultysubjectmasters = facultysubjects[11]
        facultysubjectype = facultysubjects[11]
        facultysubjectdepartmentid = facultysubjects[13]
        facultysubjectfname = facultysubjects[4]
        facultysubjectlname = facultysubjects[6]

        if currentshubjectid not in newfaculty:
            newfaculty[currentshubjectid]={}
            
        if not newfaculty[currentshubjectid]:
            if (subjectscheduletype == 'Lec' and subjectscheduleunit == 3):
                if not lec3daysgapfaculty(facultysubjectfacultyid):
                    continue

        if not newfaculty[currentshubjectid] and facultysubjectfname=='NEW':
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
if (depid==0):
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
        subject.focus != 'Major1' AND subject.focus != 'Minor' 
        AND subjectschedule.calendarid = %s
        AND department.collegeid = %s
) AS ordered_schedule
ORDER BY 
    CASE 
        WHEN ordered_schedule.unit = 3 THEN 1   -- unit 3 comes first
        WHEN ordered_schedule.unit = 1 AND ordered_schedule.requirelabroom = 1 THEN 2   -- unit 1 with requirelabroom = 1 comes second
        WHEN ordered_schedule.unit = 2 THEN 3   -- unit 2 comes third
        WHEN ordered_schedule.unit = 1 AND ordered_schedule.requirelabroom = 0 THEN 4  -- unit 1 with requirelabroom = 0 comes last
        ELSE 5   -- Default case for other units, if any
    END
,ordered_schedule.startdate ASC, ordered_schedule.departmentid ASC;""", (calendarid, collegeid))
    subjectschedule = cursor.fetchall()

    cursor.execute("""SELECT COUNT(*) FROM `subjectschedule` JOIN subject ON subjectschedule.subjectid=subject.id JOIN faculty ON faculty.id=subjectschedule.facultyid JOIN department ON department.id=subjectschedule.departmentid WHERE subject.focus!='Major1' AND subject.focus!='Minor' AND subjectschedule.calendarid = %s AND department.collegeid = %s ORDER BY FIELD(unit, 3, 1, 2), faculty.startdate ASC """, (calendarid, collegeid))
    subjectschedulecount = cursor.fetchone()

    cursor.execute("""SELECT * FROM faculty JOIN department ON department.id=faculty.departmentid WHERE department.collegeid=%s""",(collegeid,))
    facultyall = cursor.fetchall()

    cursor.execute("SELECT * FROM facultypreferences JOIN faculty ON faculty.id=facultypreferences.facultyid JOIN department ON department.id=faculty.departmentid WHERE department.collegeid=%s AND faculty.id!=0 ORDER BY starttime ASC""",(collegeid,))
    facultypreference = cursor.fetchall()

    cursor.execute("""
        SELECT 
            subjectschedule.*, 
            subject.id AS subject_id, 
            subject.subjectcode, 
            subject.name AS subject_name, 
            department.id AS department_id, 
            department.abbreviation 
        FROM 
            subjectschedule 
        JOIN 
            subject ON subject.id = subjectschedule.subjectid 
        JOIN 
            department ON department.id = subjectschedule.departmentid 
        WHERE 
            subject.focus = 'Minor' 
            AND department.collegeid = %s 
            AND subjectschedule.calendarid = %s
    """, (collegeid, calendarid))
    subjectscheduleminor = cursor.fetchall()

    cursor.execute("""SELECT * FROM room WHERE collegeid=%s ORDER BY collegeid ASC""",(depid,))
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
            subject.focus != 'Major1' AND subject.focus != 'Minor' 
            AND subjectschedule.calendarid = %s
            AND department.id = %s
    ) AS ordered_schedule
    ORDER BY 
        CASE 
            WHEN ordered_schedule.unit = 3 THEN 1   -- unit 3 comes first
            WHEN ordered_schedule.unit = 1 AND ordered_schedule.requirelabroom = 1 THEN 2   -- unit 1 with requirelabroom = 1 comes second
            WHEN ordered_schedule.unit = 2 THEN 3   -- unit 2 comes third
            WHEN ordered_schedule.unit = 1 AND ordered_schedule.requirelabroom = 0 THEN 4  -- unit 1 with requirelabroom = 0 comes last
            ELSE 5   -- Default case for other units, if any
        END
    ,ordered_schedule.startdate ASC, ordered_schedule.departmentid ASC;""", (calendarid, depid))
    subjectschedule = cursor.fetchall()

    cursor.execute("""SELECT COUNT(*) FROM `subjectschedule` JOIN subject ON subjectschedule.subjectid=subject.id JOIN faculty ON faculty.id=subjectschedule.facultyid JOIN department ON department.id=subjectschedule.departmentid WHERE subject.focus!='Major1' AND subject.focus='Minor' AND subjectschedule.calendarid = %s AND department.id = %s ORDER BY FIELD(unit, 3, 1, 2), faculty.startdate ASC """, (calendarid, depid))
    subjectschedulecount = cursor.fetchone()

    cursor.execute("""SELECT * FROM faculty JOIN department ON department.id=faculty.departmentid WHERE department.id=%s""",(depid,))
    facultyall = cursor.fetchall()

    cursor.execute("SELECT * FROM facultypreferences JOIN faculty ON faculty.id=facultypreferences.facultyid JOIN department ON department.id=faculty.departmentid WHERE department.id=%s AND faculty.id!=0 ORDER BY starttime ASC""",(depid,))
    facultypreference = cursor.fetchall()

    cursor.execute("""
        SELECT 
        subjectschedule.*, 
        subject.id AS subject_id, 
        subject.subjectcode, 
        subject.name AS subject_name, 
        department.id AS department_id, 
        department.abbreviation 
        FROM 
            subjectschedule 
        JOIN 
            subject ON subject.id = subjectschedule.subjectid 
        JOIN 
            department ON department.id = subjectschedule.departmentid 
        WHERE 
            subject.focus = 'Minor' 
            AND department.id = %s 
            AND subjectschedule.calendarid = %s
    """,(depid,calendarid))
    subjectscheduleminor = cursor.fetchall()

    cursor.execute("""SELECT * FROM room WHERE departmentid=%s ORDER BY departmentid ASC""",(depid,))
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
        """, (depid, calendarid))
        
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
sectionoccupied={}
facultypreferencedays = {}


for pref in facultypreference:
    facultyid, day, starttime, endtime = pref[1], pref[2], pref[3], pref[4]
    
    startminutes = timetominutes(starttime)
    starthours = minutestotime(startminutes)
    endminutes = timetominutes(endtime)


    '''print(f" Faculty {facultyid} prefers Day {day} from {starttime} to {endtime}")'''

    '''print("for 3.0")'''
    for pref2 in facultypreference:
        facultyid2, day2, starttime2, endtime2 = pref2[1], pref2[2], pref2[3], pref2[4]
        if facultyid2!=facultyid:
            continue
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
        subid=int(minorsub[0])
        departmentid = int(minorsub[9])
        yearlvl = int(minorsub[3])  
        section = minorsub[4]     
        dayminor = str(minorsub[7])  
              
        timestart = timetominutes(minorsub[5])
        timeend = timetominutes(minorsub[6])

        dayslist = []
        daymap = {'M': 1, 'T': 2, 'W': 3, 'Th': 4, 'F': 5, 'S': 6}
        
        if len(dayminor) == 1:
            daynum = daymap[dayminor[0]]
            dayslist.append(daynum)

        elif len(dayminor) == 3:
        
            dayone = dayminor[0]
            if dayone in daymap:
                dayslist.append(daymap[dayone])

            daytwo = dayminor[1:]
            if daytwo in daymap:
                dayslist.append(daymap[daytwo])


        elif len(dayminor) == 2 and dayminor not in daymap:
           
            dayone = dayminor[0] 
            daytwo2 = dayminor[1] 

            daynum1 = daymap[dayone]  
            daynum2 = daymap[daytwo2]  

       
            dayslist.append(daynum1) 
            dayslist.append(daynum2) 

        elif len(dayminor) == 2 and dayminor in daymap:
            if dayminor in daymap: 
                daynum = daymap[dayminor]
                dayslist.append(daynum)

        if departmentid not in sectionoccupied:
            sectionoccupied[departmentid] = {}

        if yearlvl not in sectionoccupied[departmentid]:
            sectionoccupied[departmentid][yearlvl] = {}

        if section not in sectionoccupied[departmentid][yearlvl]:
            sectionoccupied[departmentid][yearlvl][section] = {}

        for days in dayslist:
            daysfinal=days
            if days not in sectionoccupied[departmentid][yearlvl][section]:
                sectionoccupied[departmentid][yearlvl][section][daysfinal] = {}

            for time in range(timestart, timeend, 30):
                if time not in sectionoccupied[departmentid][yearlvl][section][daysfinal]:
                    sectionoccupied[departmentid][yearlvl][section][daysfinal][time] = 'occupied'
                    





      
def sectionfree(departmentid, yearlvl, section, day, time):
    time
    department = sectionoccupied.get(departmentid, {})
    year = department.get(yearlvl, {})
    sec = year.get(section, {})
    day_data = sec.get(day, {})
    
  
    if day_data.get(time) == 'occupied':
        return False
    
    return True


def sectionassign(departmentid, yearlvl, section, day, time, gap):
    sectionoccupied.setdefault(departmentid, {}).setdefault(yearlvl, {}).setdefault(section, {}).setdefault(day, {})

    for timesection in range(time, time + gap, 30):
        sectionoccupied[departmentid][yearlvl][section][day][timesection] = 'occupied'

def countup(roomid, day, timestart):
    timeslotcount = 0
    time = timestart-30

    while time >= 420:
        if roomoccupied[roomid][day].get(time, 'occupied') == 'free': 
            timeslotcount += 1
        else:
            break
        time -= 30

    return timeslotcount

def countdown(roomid, day, timestart):
    timeslotcount = 0
    time = timestart
    
    while time <= 1140: 
        if roomoccupied[roomid][day].get(time, 'occupied') == 'free': 
            timeslotcount += 1
        else:
            break
        time += 30

    return timeslotcount


subjectiteration={}
backtrackcounters={}
facultyhoursday={}
newroomlablol={}
newroomlec3={}
newroomlec2={}
maxdepth=1

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




def checkroomfree(roomid, day, time):
    for room in roomoccupied:
        if roomid == room:
            return roomoccupied[roomid][day].get(time) != 'occupied'
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
    global depid
    global assignedsubjectscount
    
    if currentsubjectid not in backtrackcounters:
        backtrackcounters[currentsubjectid] = 0  

    if (depid==0):
        cursor.execute("""SELECT * FROM room
        WHERE collegeid = %s
        ORDER BY 
            CASE 
                WHEN name = 'NEW ROOM' THEN 1
                ELSE 0
            END,
            type DESC,
            departmentid ASC;
        """,(collegeid,))
        room = cursor.fetchall()
    else:
        cursor.execute("""SELECT * FROM room
        WHERE departmentid = %s
        ORDER BY 
            CASE 
                WHEN name = 'NEW ROOM' THEN 1
                ELSE 0
            END,
            type DESC,
            departmentid ASC;
        """,(depid,))
        room = cursor.fetchall()
        
    for dayput in range(1, 7):
        for rm in room:  
            roomid = rm[0]
            if roomid not in roomoccupied:
                roomoccupied[roomid] = {}

            if dayput not in roomoccupied[roomid]:
                roomoccupied[roomid][dayput] = {time: 'free' for time in range(420, 1140, 30)}

     
    if currentsubjectid not in newroomlec3:
        newroomlec3[currentsubjectid]=False

    if currentsubjectid not in newroomlablol:
        newroomlablol[currentsubjectid]=False

    if currentsubjectid not in newroomlec2:
        newroomlec2[currentsubjectid]=False
   
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
    
    sorted_rooms = sorted(room, key=lambda x: (x[5] != departmentid, x[2] != subjecttype, x[5], x[1]))
    for rm in sorted_rooms:
        roomid, roomname, roomtype, roomstart, roomend, roomdeptid = rm[0], rm[1], rm[2], rm[3], rm[4], rm[5]

        

        


        '''print(f"trying subject {currentsubjectid} in room {roomname} (id: {roomid}, type: {roomtype})")'''
    
        if units == 3.0:
            if(backtrackcounters[currentsubjectid] < maxdepth):
                if roomtype != subjecttype and subjecttype!='Lec':  
                    continue 
                if not newroomlec3[currentsubjectid] and roomname=='NEW ROOM':
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
                        if day1+3!=day2:
                            continue
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

                        if facultyassignmentcounter[facultyidpair][day1] % 2==0:
                        
                            lastfacultyass= findlastfacultyasslec3(facultyidpair, day1)
                            if lastfacultyass:
                                startminutes = lastfacultyass + 90 
                            else:
                                startminutes=startminutes
                        elif facultyassignmentcounter[facultyidpair][day1] % 2!=0:
                            
                            lastfacultyass= findlastfacultyasslec3(facultyidpair, day1)
                            if lastfacultyass:
                                startminutes = lastfacultyass 
                            else:
                                startminutes=startminutes
                            
                        for time_slot in range(startminutes, startminutes+90, 30):
                            if time_slot==1140 :
                                day1free=False
                                break

                            roomoccupied.setdefault(roomid, {}).setdefault(day1, {})
                            roomoccupied.setdefault(roomid, {}).setdefault(day2, {})

                            
                            
                            if not sectionfree(departmentid, yearlvl, section, day1, time_slot) or not sectionfree(departmentid, yearlvl, section, day2, time_slot):
                                
                                day1free = False
                                break

                            if roomoccupied[roomid][day1].get(time_slot) != 'free':
                                day1free = False
                                break

                        
                            if roomoccupied[roomid][day1].get(time_slot) != 'free':
                                day1free = False
                                break
                            else:
                                day1free=True

                            if roomoccupied[roomid][day2].get(time_slot) != 'free':
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
                            
                            sectionassign(departmentid, yearlvl, section, day1, startminutes, 90)
                            sectionassign(departmentid, yearlvl, section, day2, startminutes, 90)
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
                            for time_slot3 in range(startminutes, startminutes+90, 30):
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
                sorted_room32 = sorted(room, key=lambda x: (x[5] != departmentid, x[2] != subjecttype, x[5], x[1]))
                for rm in sorted_room32:
                    roomid, roomname, roomtype, roomstart, roomend, roomdeptid = rm[0], rm[1], rm[2], rm[3], rm[4], rm[5]
                    if not newroomlec3[currentsubjectid] and roomname=='NEW ROOM':
                        continue
                    
                    if subjectfacultyid in facultypreferencedays:
                        preferreddays = list(facultypreferencedays[subjectfacultyid]) 
                        
                    for dayin3 in preferreddays: 
                        
                        
                        day2in3 = (dayin3 + 3)

                        if day2in3 == 7 or day2in3 == 8 or day2in3 == 9:
                            continue

                        if day2in3-3!=dayin3:
                            continue
                       
                        if dayin3 not in facultyhoursday[subjectfacultyid]:
                            facultyhoursday[subjectfacultyid][dayin3] = Decimal(0)
                        if day2in3 not in facultyhoursday[subjectfacultyid]:
                            facultyhoursday[subjectfacultyid][day2in3] = Decimal(0)
                        if getfacultytype(subjectfacultyid)=='Regular' and getfacultyhoursday(subjectfacultyid, dayin3)>=6 and getfacultyhoursday(subjectfacultyid, day2in3)>=6:
                            continue
                        for time3 in range(420, 1140, 30):
                            day1 = False
                            day2 = False
                            facultyday1 = False
                            facultyday2 = False

                            if time3==1140:
                                day1free=False
                                break
                            
                            
                            
                            if (checkroomfree(roomid, dayin3, time3) and
                                checkroomfree(roomid, dayin3, time3+30) and
                                checkroomfree(roomid, dayin3, time3+60)):
                                day1 = True
                                
                            else:
                                day1 = False
                                continue
                            
                            if (checkroomfree(roomid, day2in3, time3) and
                                checkroomfree(roomid, day2in3, time3+30) and
                                checkroomfree(roomid, day2in3, time3+60)):
                                day2 = True
                                
                            else:
                                day2 = False
                                continue

                            if dayin3 not in facultyoccupied[subjectfacultyid]:
                                facultyoccupied[subjectfacultyid][dayin3] = {}
                            if day2in3 not in facultyoccupied[subjectfacultyid]:
                                facultyoccupied[subjectfacultyid][day2in3] = {}

                            if (facultyoccupied[subjectfacultyid][dayin3].get(time3) == 'free' and
                                facultyoccupied[subjectfacultyid][dayin3].get(time3 + 30) == 'free' and
                                facultyoccupied[subjectfacultyid][dayin3].get(time3 + 60) == 'free' and
                                sectionfree(departmentid, yearlvl, section, dayin3, time3) and
                                sectionfree(departmentid, yearlvl, section, dayin3, time3 + 30) and
                                sectionfree(departmentid, yearlvl, section, dayin3, time3 + 60)):
                                facultyday1 = True
                            else:
                                facultyday1 = False
                                continue
                            if (facultyoccupied[subjectfacultyid][day2in3].get(time3) == 'free' and
                                facultyoccupied[subjectfacultyid][day2in3].get(time3 + 30) == 'free' and
                                facultyoccupied[subjectfacultyid][day2in3].get(time3 + 60) == 'free' and
                                sectionfree(departmentid, yearlvl, section, day2in3, time3) and
                                sectionfree(departmentid, yearlvl, section, day2in3, time3 + 30) and
                                sectionfree(departmentid, yearlvl, section, day2in3, time3 + 60)):
                                facultyday2 = True
                            else:
                                facultyday2 = False
                                continue
                                    
                            
                            
                            if day1 and day2 and facultyday1 and facultyday2:  
                                
                                sectionassign(departmentid, yearlvl, section, dayin3, time3, 90)
                                sectionassign(departmentid, yearlvl, section, day2in3, time3, 90)
                                lec3found=True
                                '''print(f"assigned alter subject {currentsubjectid} in {roomname} to this day {day2} and {day1} w/ time3 slot starting at {minutestotime(startminutes)} upto {minutestotime(end_minutes)}")'''
                            
                                for time_slot in range(time3, time3+90, 30):
                                    roomoccupied[roomid][dayin3][time_slot] = 'occupied'
                                    roomoccupied[roomid][day2in3][time_slot] = 'occupied'
                                    facultyoccupied[subjectfacultyid][dayin3][time_slot] = 'occupied'
                                    facultyoccupied[subjectfacultyid][day2in3][time_slot] = 'occupied'

                                facultyassignmentcounter[subjectfacultyid][dayin3] += 1
                                facultyassignmentcounter[subjectfacultyid][day2in3] += 1
                                facultyhoursday[subjectfacultyid][dayin3] += Decimal(1.5)
                                facultyhoursday[subjectfacultyid][day2in3] += Decimal(1.5)

                                assignments[subjectid] = (time3, time3 + 90, (dayin3, day2in3), roomid)
                                assignedsubjects.add(subjectid)
                                assignedsubjectscount=assignedsubjectscount+1
                                progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
                                print(f"{progress:.2f}%: {subname} {subjectfacultyid} assigned on {dayin3} and {day2in3} starting {time3}-{time3+90}")
                                sys.stdout.flush()
                               
                                if assigntimeslot(currentsubjectid+1):
                                    return True 
                                '''print("backtracking lec 3.0")'''
                                assignedsubjectscount=assignedsubjectscount-1
                                for time_slot in range(time3, time3+90, 30):
                                    roomoccupied[roomid][dayin3][time_slot] = 'free'
                                    roomoccupied[roomid][day2in3][time_slot] = 'free'
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
                    sorted_room33 = sorted(room, key=lambda x: (x[5] != departmentid, x[2] != subjecttype, x[5], x[1]))
                    for rm in sorted_room33:
                        roomid, roomname, roomtype, roomstart, roomend, roomdeptid = rm[0], rm[1], rm[2], rm[3], rm[4], rm[5]
                        if not newroomlec3[currentsubjectid] and roomname=='NEW ROOM':
                            continue
                        
                        
                        
                        for dayin3 in range(1,7): 
                            day1 = False
                            day2 = False
                            facultyday1 = False
                            facultyday2 = False
                            day2in3 = (dayin3 + 3)

                            
                            if day2in3 == 7:
                                break
                            facultyhoursday1 = getfacultyhoursday(subjectfacultyid, dayin3)
                            facultyhoursday2 = getfacultyhoursday(subjectfacultyid, day2in3)

                            if getfacultytype(subjectfacultyid) == 'Regular' and facultyhoursday1 is not None and facultyhoursday1 >= 6 and facultyhoursday2 is not None and facultyhoursday2 >= 6:
                                continue

                            
                            for time in range(420, 1140, 30):
                                if time==1140:
                                    day1free=False
                                    break
                                
                                
                                
                                if (checkroomfree(roomid, dayin3, time) and
                                    checkroomfree(roomid, dayin3, time+30) and
                                    checkroomfree(roomid, dayin3, time+60)):
                                    day1 = True
                                    
                                else:
                                    continue
                                
                                if (checkroomfree(roomid, day2in3, time) and
                                    checkroomfree(roomid, day2in3, time+30) and
                                    checkroomfree(roomid, day2in3, time+60)):
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
                                    facultyoccupied[subjectfacultyid][dayin3].get(time + 60) == 'free' and sectionfree(departmentid, yearlvl, section, dayin3, time)):
                                    facultyday1 = True
                                else:
                                    continue
                                if (facultyoccupied[subjectfacultyid][day2in3].get(time) == 'free' and
                                    facultyoccupied[subjectfacultyid][day2in3].get(time + 30) == 'free' and
                                    facultyoccupied[subjectfacultyid][day2in3].get(time + 60) == 'free' and sectionfree(departmentid, yearlvl, section, day2in3, time)):
                                    facultyday2 = True
                                else:
                                    continue
                                        
                                
                        
                                if day1 and day2 and facultyday1 and facultyday2:  
                                    print(dayin3, day2in3)   
                                      
                                    sectionassign(departmentid, yearlvl, section, dayin3, time, 90)
                                    sectionassign(departmentid, yearlvl, section, dayin3, time, 90)
                                    lec3found=True
                                    '''print(f"assigned alter subject {currentsubjectid} in {roomname} to this day {day2} and {day1} w/ time slot starting at {minutestotime(startminutes)} upto {minutestotime(end_minutes)}")'''
                                
                                    for time_slot in range(time, time+90, 30):
                                        roomoccupied[roomid][dayin3][time_slot] = 'occupied'
                                        roomoccupied[roomid][day2in3][time_slot] = 'occupied'
                                        facultyoccupied[subjectfacultyid][dayin3][time_slot] = 'occupied'
                                        facultyoccupied[subjectfacultyid][day2in3][time_slot] = 'occupied'

                                    facultyassignmentcounter[subjectfacultyid][dayin3] += 1
                                    facultyassignmentcounter[subjectfacultyid][day2in3] += 1
                                    facultyhoursday[subjectfacultyid][dayin3] += Decimal(1.5)
                                    facultyhoursday[subjectfacultyid][day2in3] += Decimal(1.5)
                                    assignments[subjectid] = (time, time + 90, (day1, day2), roomid)
                                    
                                    assignedsubjects.add(subjectid)
                                    assignedsubjectscount=assignedsubjectscount+1
                                    progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
                                    print(f"{progress:.2f}%: {subname} {subjectfacultyid} assigned on {dayin3} and {day2in3} starting {time}-{time+90}")
                                    sys.stdout.flush()

                                    if assigntimeslot(currentsubjectid+1):
                                        return True 
                                    '''print("backtracking lec 3.0")'''
                                    assignedsubjectscount=assignedsubjectscount-1
                                    for time_slot in range(time, time+90, 30):
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
                        
                        newroomlec3[currentsubjectid]=True
                        '''assignments[subjectid] = (0, 0, (0), 0)
                        assignedsubjects.add(subjectid)
                        assignedsubjectscount=assignedsubjectscount+1
                        progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
                        print(f"{progress:.2f}%: {subname} {subjectfacultyid}")
                        sys.stdout.flush()
                        if assigntimeslot(currentsubjectid+1):
                            return True 
                        
                        print("Backtracking h.0")
                        assignedsubjectscount=assignedsubjectscount-1
                        
                        
                        if subjectid in assignments:
                            del assignments[subjectid]
                        assignedsubjects.remove(subjectid)'''

                        query = """
                            INSERT INTO `room` (`name`, `type`, `departmentid`, `collegeid`)
                            VALUES (%s, %s, %s, %s)
                        """
                        values = ("NEW ROOM", 'Lec', departmentid, collegeid)
                        cursor.execute(query, values)
                        conn.commit()
                        backtrackcounters[currentsubjectid] = 0

                        if assigntimeslot(currentsubjectid):
                            return True
                                

            

           

                    
        elif(units==2.0):
            if (backtrackcounters[currentsubjectid] < maxdepth):
                if roomtype != subjecttype and not (roomtype == 'Lab' and subjecttype == 'Lec'):
                    continue
                
                if not newroomlec2[currentsubjectid] and roomname=='NEW ROOM':
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

                        countup_value = countup(roomid, daylec2, startminutes)
                        countdown_value = countdown(roomid, daylec2, startminutes) 

                        if countup_value<4 and countup_value!=0 and (countdown_value-4)!=0 and countup_value<(countdown_value-4):
                            startminutes = startminutes + (30 * (4 - countup_value))
                        elif countup_value<4 and countup_value!=0 and (countdown_value-4)!=0 and countup_value>(countdown_value-4):
                            startminutes = startminutes + (30 * (4 - countup_value))    
                        elif (countdown_value-4)<4 and countdown_value-4!=0 and countdown_value<countup_value:
                            startminutes = startminutes + (30 * (4 - countup_value))   

                        for timeslotlec2 in range(startminutes, startminutes+120, 30):
                            if timeslotlec2>=1140:
                                daylec2free=False
                            
                                break
                            if timeslotlec2 not in roomoccupied[roomid][daylec2]:
                                roomoccupied[roomid][daylec2][timeslotlec2] = 'free'
                                daylec2free=True

                            if not sectionfree(departmentid, yearlvl, section, daylec2, timeslotlec2):
                                daylec2free = False
                                break

                            if roomoccupied[roomid][daylec2][timeslotlec2] == 'occupied':
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
                            sectionassign(departmentid, yearlvl, section, daylec2, startminutes, 120)
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
                sorted_room22 = sorted(room, key=lambda x: (x[5] != departmentid, x[2] != subjecttype, x[5], x[1]))
                for rm in sorted_room22:
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

                        facultyhours = getfacultyhoursday(subjectfacultyid, daylabbacktrack)

                        if getfacultytype(subjectfacultyid)=='Regular':
                            if facultyhours is not None and facultyhours >= 6:
                                continue

                        for timebacktrack in range(420, 1140, 30):
                            if timebacktrack>=1140:
                                day1free=False
                                
                                break
                            if (checkroomfree(roomid, daylabbacktrack, timebacktrack) and
                                checkroomfree(roomid, daylabbacktrack, timebacktrack+30) and
                                checkroomfree(roomid, daylabbacktrack, timebacktrack+60) and checkroomfree(roomid, daylabbacktrack, timebacktrack+90) and
                                sectionfree(departmentid, yearlvl, section, daylabbacktrack, timebacktrack) and
                                sectionfree(departmentid, yearlvl, section, daylabbacktrack, timebacktrack + 30) and
                                sectionfree(departmentid, yearlvl, section, daylabbacktrack, timebacktrack + 60) and sectionfree(departmentid, yearlvl, section, daylabbacktrack, timebacktrack + 90)):
                                day1free = True
                            else:
                                day1free = False
                                continue    
                            if daylabbacktrack not in facultyoccupied[subjectfacultyid]:
                                facultyoccupied[subjectfacultyid][daylabbacktrack] = {}

                            if (facultyoccupied[subjectfacultyid][daylabbacktrack].get(timebacktrack) == 'free' and
                                facultyoccupied[subjectfacultyid][daylabbacktrack].get(timebacktrack + 30) == 'free' and
                                facultyoccupied[subjectfacultyid][daylabbacktrack].get(timebacktrack + 60) == 'free' and facultyoccupied[subjectfacultyid][daylabbacktrack].get(timebacktrack + 90) == 'free'):
                                facultyday1free = True
                            else:
                                facultyday1free = False
                                continue

                            if day1free and facultyday1free:
                                starttime = timebacktrack
                                dayvalid=daylabbacktrack
                                

            
                            if day1free and facultyday1free:         
                                sectionassign(departmentid, yearlvl, section, dayvalid, starttime, 120)
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
                    
                    sorted_room23 = sorted(room, key=lambda x: (x[5] != departmentid, x[2] != subjecttype, x[5], x[1]))
                    for rm in sorted_room23:
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
                                if (checkroomfree(roomid, daybacktracklec2, time) and
                                    checkroomfree(roomid, daybacktracklec2, time+30) and
                                    checkroomfree(roomid, daybacktracklec2, time+60) and
                                    checkroomfree(roomid, daybacktracklec2, time+90) and
                                    sectionfree(departmentid, yearlvl, section, daybacktracklec2, time) and
                                    sectionfree(departmentid, yearlvl, section, daybacktracklec2, time + 30) and
                                    sectionfree(departmentid, yearlvl, section, daybacktracklec2, time + 60) and
                                    sectionfree(departmentid, yearlvl, section, daybacktracklec2, time + 90)):
                                    day1true = True
                                else:
                                    day1true = False
                                    continue
                                
                                
                                if daybacktracklec2 not in facultyoccupied[subjectfacultyid]:
                                    facultyoccupied[subjectfacultyid][daybacktracklec2] = {}

                                if (facultyoccupied[subjectfacultyid][daybacktracklec2].get(time) == 'free' and
                                    facultyoccupied[subjectfacultyid][daybacktracklec2].get(time + 30) == 'free' and
                                    facultyoccupied[subjectfacultyid][daybacktracklec2].get(time + 60) == 'free' and
                                    facultyoccupied[subjectfacultyid][daybacktracklec2].get(time + 90) == 'free'):
                                    facultyday1true = True   
                                else:
                                    facultyday1true = False  
                                    continue

                                if day1true and facultyday1true: 
                                    sectionassign(departmentid, yearlvl, section, daybacktracklec2, time, 120)
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
                        
                        newroomlec2[currentsubjectid]=True
                        '''assignments[subjectid] = (0, 0, (0), 0)
                        assignedsubjects.add(subjectid)
                        assignedsubjectscount=assignedsubjectscount+1
                        progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
                        print(f"{progress:.2f}%: {subname} {subjectfacultyid}")
                        sys.stdout.flush()
                        if assigntimeslot(currentsubjectid+1):
                            return True 
                        
                        print("Backtracking h.0")
                        assignedsubjectscount=assignedsubjectscount-1
                        
                        
                        if subjectid in assignments:
                            del assignments[subjectid]
                        assignedsubjects.remove(subjectid)'''

                        query = """
                            INSERT INTO `room` (`name`, `type`, `departmentid`, `collegeid`)
                            VALUES (%s, %s, %s, %s)
                        """
                        values = ("NEW ROOM", 'Lec', departmentid, collegeid)
                        cursor.execute(query, values)
                        conn.commit()
                        backtrackcounters[currentsubjectid] = 0

                        if assigntimeslot(currentsubjectid):
                            return True
                                
            

        elif (units == 1.0):
            
            if(backtrackcounters[currentsubjectid] < maxdepth):
                if roomtype != subjecttype and  requirelab==1: 
                    continue   
                if not newroomlablol[currentsubjectid] and roomname=='NEW ROOM':
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
                            if getfacultyhoursday(faculty_idlab, daylab)+3>6:
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
                        
                        '''if 0 < countup(roomid, daylab, start_minuteslab) < 6:
                
                            if 0 < countdown(roomid, daylab, start_minuteslab+150)>=1:
                                start_minuteslab = start_minuteslab + (30 * (6 - countup(roomid, daylab, start_minuteslab)))
                                print(start_minuteslab)'''

                       
                            
                        '''if countdown(roomid, daylab, start_minuteslab)<11:
                            start_minuteslab = start_minuteslab + (30 * countdown(roomid, daylab, start_minuteslab))'''
                            
                            
                            
                            

                        '''elif(countup_value<countdown_value and countdown_value>=1):
                        if countup_value<countdown_value:
                            start_minuteslab = start_minuteslab+180 + (30 * countdown_value)'''

                        countup_value = countup(roomid, daylab, start_minuteslab)
                        countdown_value = countdown(roomid, daylab, start_minuteslab) 

                        
                        
                        if countup_value<6 and countup_value!=0 and (countdown_value-6)!=0 and countup_value<(countdown_value-6):
                            start_minuteslab = start_minuteslab + (30 * (6 - countup_value))
                        elif countup_value<6 and countup_value!=0 and (countdown_value-6)!=0 and countup_value>(countdown_value-6):
                            start_minuteslab = start_minuteslab + (30 * (6 - countup_value))    
                        elif (countdown_value-6)<6 and countdown_value-6!=0 and countdown_value<countup_value:
                            start_minuteslab = start_minuteslab + (30 * (6 - countup_value))    
                        elif (countdown_value-6)==3 and countdown_value-6!=0 and countdown_value==3 and countup_value!=0:
                            start_minuteslab = start_minuteslab + (30 * (6 - countup_value))  

                        for time_slotlab in range(start_minuteslab, start_minuteslab+180, 30):
                            if (time_slotlab>=1140):
                                dayfreelab = False
                
                                break
                        
                            if time_slotlab not in roomoccupied[roomid][daylab]:
                                roomoccupied[roomid][daylab][time_slotlab] = 'free'

                            if not sectionfree(departmentid, yearlvl, section, daylab, time_slotlab):
                                dayfreelab = False
                                break 

                                '''print(f"room {roomid} {daylab} {time_slotlab} is free")'''
                            if roomoccupied[roomid][daylab][time_slotlab] == 'occupied':
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
                           
                            sectionassign(departmentid, yearlvl, section, daylab, start_minuteslab, 180)
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
                sorted_rooms1 = sorted(room, key=lambda x: (x[5] != departmentid, x[2] != subjecttype, x[5], x[1]))
                for rm in sorted_rooms1:
                    roomid, roomname, roomtype, roomstart, roomend, roomdeptid = rm[0], rm[1], rm[2], rm[3], rm[4], rm[5]
                    if subjectfacultyid in facultydaystimelab:
                        preferreddays = list(set(entry[0] for entry in facultydaystimelab[subjectfacultyid]))
                    
                    if roomtype=='Lec' and requirelab==1: 
                        continue
                    if not newroomlablol[currentsubjectid] and roomname=='NEW ROOM':
                        continue
                        
                    for day1 in preferreddays: 
                        
                        day1true = None
                        facultyday1true = None

                        if subjectfacultyid not in facultyhoursday:
                            facultyhoursday[subjectfacultyid]={}

                        if day1 not in facultyhoursday[subjectfacultyid]:
                            facultyhoursday[subjectfacultyid][day1] = Decimal(0)

                        if getfacultytype(subjectfacultyid)=='Regular' and getfacultyhoursday(subjectfacultyid, day1)+3>6:
                            continue
                        
                        for time in range(420, 1140, 30):
                            countup_value = countup(roomid, day1, time)
                            countdown_value = countdown(roomid, day1, time) 

                        
                        

                        
                            if (countdown_value-6)<6 and countdown_value!=0:
                                day1true = False
                                continue

                            if (countup_value)<6 and countup_value!=0:
                                day1true = False
                                continue

                            if (checkroomfree(roomid, day1, time) and
                                checkroomfree(roomid, day1, time + 30) and
                                checkroomfree(roomid, day1, time + 60) and
                                checkroomfree(roomid, day1, time + 90) and
                                checkroomfree(roomid, day1, time + 120) and
                                checkroomfree(roomid, day1, time + 150) and
                                sectionfree(departmentid, yearlvl, section, day1, time) and
                                sectionfree(departmentid, yearlvl, section, day1, time + 30) and
                                sectionfree(departmentid, yearlvl, section, day1, time + 60) and
                                sectionfree(departmentid, yearlvl, section, day1, time + 90) and
                                sectionfree(departmentid, yearlvl, section, day1, time + 120) and
                                sectionfree(departmentid, yearlvl, section, day1, time + 150)):
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
                                facultyoccupied[subjectfacultyid][day1].get(time + 150) == 'free'):
                                facultyday1true = True   
                            else:
                                facultyday1true = False  
                                continue

                            if day1true and facultyday1true: 
                                
                                sectionassign(departmentid, yearlvl, section, day1, time, 180)
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

                                if subjectfacultyid not in facultyhoursday:
                                    facultyoccupied[subjectfacultyid] = {}

                                if day1 not in facultyhoursday[subjectfacultyid]:
                                    facultyhoursday[subjectfacultyid][day1] = Decimal(0)

                                facultyhoursday[subjectfacultyid][day1]=facultyhoursday[subjectfacultyid][day1]+3

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
                    
                    sorted_rooms2 = sorted(room, key=lambda x: (x[5] != departmentid, x[2] != subjecttype, x[5], x[1]))
                    for rm in sorted_rooms2:
                        roomid, roomname, roomtype, roomstart, roomend, roomdeptid = rm[0], rm[1], rm[2], rm[3], rm[4], rm[5]
                        
                        if roomtype=='Lec' and requirelab==1: 
                            continue

                        if not newroomlablol[currentsubjectid] and roomname=='NEW ROOM':
                            
                            continue
                        
                        for day1 in range(1,7): 
                            
                            if subjectfacultyid not in facultyhoursday:
                                facultyhoursday[subjectfacultyid] = {}
                            if day1 not in facultyhoursday[subjectfacultyid]:
                                facultyhoursday[subjectfacultyid][day1] = Decimal(0)

                            if getfacultytype(subjectfacultyid)=='Regular' and getfacultyhoursday(subjectfacultyid, day1)+3>6:
                                continue
                            day1true = None
                            facultyday1true = None

                            for time in range(420, 1140, 30):
                                countup_value = countup(roomid, day1, time)
                                countdown_value = countdown(roomid, day1, time) 
                            
                                if countup_value<6 and countup_value!=0:
                                    day1true = False
                                    continue

                                if countdown_value-6<6 and countdown_value!=0:
                                    day1true = False
                                    continue

                                if time>=1140:
                                    day1true=False
                                    
                                    break
                                if (checkroomfree(roomid, day1, time) and
                                    checkroomfree(roomid, day1, time+30) and
                                    checkroomfree(roomid, day1, time+60) and
                                    checkroomfree(roomid, day1, time+90) and
                                    checkroomfree(roomid, day1, time+120) and
                                    checkroomfree(roomid, day1, time+150) and 
                                    sectionfree(departmentid, yearlvl, section, day1, time) and
                                    sectionfree(departmentid, yearlvl, section, day1, time + 30) and
                                    sectionfree(departmentid, yearlvl, section, day1, time + 60) and
                                    sectionfree(departmentid, yearlvl, section, day1, time + 90) and
                                    sectionfree(departmentid, yearlvl, section, day1, time + 120) and
                                    sectionfree(departmentid, yearlvl, section, day1, time + 150)):
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
                                    facultyoccupied[subjectfacultyid][day1].get(time + 150) == 'free'):
                                    facultyday1true = True   
                                else:
                                    facultyday1true = False  
                                    continue

                                if day1true and facultyday1true:
                                    newroomlab=False
                                    sectionassign(departmentid, yearlvl, section, day1, time, 180)
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
                        
                        newroomlablol[currentsubjectid] = True
                        '''assignments[subjectid] = (0, 0, (0), 0)
                        assignedsubjects.add(subjectid)
                        assignedsubjectscount=assignedsubjectscount+1
                        progress = (assignedsubjectscount) / subjectschedulecount[0] * 100
                        print(f"{progress:.2f}%: {subname} {subjectfacultyid}")
                        sys.stdout.flush()
                        if assigntimeslot(currentsubjectid+1):
                            return True 
                        
                        print("Backtracking h.0")
                        assignedsubjectscount=assignedsubjectscount-1
                        
                        
                        if subjectid in assignments:
                            del assignments[subjectid]
                        assignedsubjects.remove(subjectid)'''

                        query = """
                            INSERT INTO `room` (`name`, `type`, `departmentid`, `collegeid`)
                            VALUES (%s, %s, %s, %s)
                        """
                        values = ("NEW ROOM", 'Lab', departmentid, collegeid)
                        cursor.execute(query, values)
                        conn.commit()
                        backtrackcounters[currentsubjectid] = 0

                        if assigntimeslot(currentsubjectid):
                            return True 
                        
        
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


