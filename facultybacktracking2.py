import mysql.connector
import time
import sys

departmentid = 0
collegeid = 3
calendarid = 7


conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="schedai"
)

cursor = conn.cursor()


if (departmentid==0):
    cursor.execute("""
        SELECT COUNT(*) 
        FROM `subjectschedule`
        JOIN `subject` ON subjectschedule.subjectid = subject.id 
        JOIN department ON department.id = subjectschedule.departmentid
        WHERE subject.focus != 'Minor' 
        AND subjectschedule.calendarid = %s 
        AND department.collegeid = %s 
        ORDER BY 
        FIELD(subject.focus, 'Major1') DESC,  
        subjectschedule.departmentid ASC
    """, (calendarid, collegeid))
    subjectschedule = cursor.fetchall()

    cursor.execute("""
        SELECT COUNT(*) FROM `subjectschedule` 
        JOIN `subject` ON subjectschedule.subjectid = subject.id 
        JOIN department ON department.id=subjectschedule.departmentid
        WHERE subject.focus !='Minor' 
        AND subject.focus != 'Major1' 
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
    ORDER BY faculty.teachinghours DESC, faculty.departmentid ASC
    """, (collegeid,))
    facultysubject = cursor.fetchall()

    cursor.execute("""SELECT * FROM faculty WHERE faculty.collegeid = %s""", (collegeid,))
    faculty = cursor.fetchall()


    cursor.execute("""SELECT * FROM subject JOIN department ON department.id=subject.departmentid WHERE department.collegeid = %s""", (collegeid,))
    subject = cursor.fetchall()

    cursor.execute("""SELECT * FROM room WHERE collegeid=%s""", (collegeid,))
    room = cursor.fetchall()

    cursor.execute("""SELECT faculty.*, facultypreferences.*, COUNT(facultysubject.facultyid) AS subject_count FROM facultypreferences JOIN faculty ON faculty.id = facultypreferences.facultyid LEFT JOIN facultysubject ON facultysubject.facultyid = facultypreferences.facultyid WHERE faculty.collegeid=%s GROUP BY faculty.id, facultypreferences.id ORDER BY faculty.teachinghours ASC, subject_count ASC""",(collegeid,))
    facultypreference = cursor.fetchall()
else:
    cursor.execute("""
        SELECT * FROM `subjectschedule` 
        JOIN `subject` ON subjectschedule.subjectid = subject.id 
        JOIN department ON department.id=subjectschedule.departmentid
        WHERE subject.focus !='Minor' 
        AND subject.focus != 'Major1' 
        AND subjectschedule.calendarid = %s 
        AND department.collegeid = %s 
        ORDER BY subjectschedule.departmentid ASC
    """, (calendarid, collegeid))
    subjectschedule = cursor.fetchall()

    cursor.execute("""
        SELECT COUNT(*) FROM `subjectschedule` 
        JOIN `subject` ON subjectschedule.subjectid = subject.id 
        JOIN department ON department.id=subjectschedule.departmentid
        WHERE subject.focus !='Minor' 
        AND subject.focus != 'Major1' 
        AND subjectschedule.calendarid = %s 
        AND department.collegeid = %s 
        ORDER BY subjectschedule.departmentid ASC
    """, (calendarid, collegeid))
    subjectschedulecount = cursor.fetchone()

    cursor.execute("""
    SELECT * 
    FROM facultysubject 
    JOIN faculty ON faculty.id = facultysubject.facultyid 
    WHERE faculty.departmentid = %s 
    ORDER BY faculty.teachinghours DESC, faculty.departmentid ASC
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

    cursor.execute("UPDATE `subjectschedule` SET `facultyid` = NULL, `facultyfname` = NULL,`facultylname` = NULL;")
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
            print(f"{progress:.2f}%: {facultysubjectfsubjectname} assigned to {facultysubjectfname} {facultysubjectlname}")
            
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
            print(f"{progress:.2f}%: {facultysubjectfsubjectname} assigned to {facultysubjectfname} {facultysubjectlname}")
                
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
        facultysubjectdepartmentid = facultysubjects[13]
        facultysubjectfname = facultysubjects[4]
        facultysubjectlname = facultysubjects[6]

        

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
                print(f"{progress:.2f}%: {facultysubjectfsubjectname} assigned to {facultysubjectfname} {facultysubjectlname}")
                
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
conn.commit()

cursor.close()
conn.close()
