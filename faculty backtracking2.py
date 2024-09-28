import mysql.connector
import time

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

cursor.execute("SELECT * FROM room WHERE departmentid=1")
room = cursor.fetchall()

cursor.execute("SELECT * FROM facultypreferences JOIN faculty ON faculty.id=facultypreferences.facultyid WHERE faculty.departmentid=1")
facultypreference = cursor.fetchall()

cursor.execute("SET FOREIGN_KEY_CHECKS = 0; UPDATE `subjectschedule` SET `facultyid` = NULL; SET FOREIGN_KEY_CHECKS = 1;", multi=True)
conn.commit()

def facultysubjectmatch(subjectschedulesubjectname, facultysubjectfsubjectname, subjectschedulesubjectmasters, facultysubjectmasters, subjectscheduledepartmentid, facultysubjectdepartmentid):
    return (subjectschedulesubjectname == facultysubjectfsubjectname and (
        subjectschedulesubjectmasters == facultysubjectmasters or subjectschedulesubjectmasters == 'No'
    ) and (subjectscheduledepartmentid == facultysubjectdepartmentid or facultysubjectdepartmentid == 3))

def facultyworkinghourscheck(facultyworkinghours, subjectschedulesubjecthours, facultysubjectfacultyid):
    if facultyworkinghours < subjectschedulesubjecthours:
        print(f"{facultysubjectfacultyid} does not have enough working hours")
        return False
    print(f"{facultysubjectfacultyid} has enough working hours")
    return True

workinghoursleft={}
unassigned_subjects=[]
unassigned_subjects = []  # Track unassigned subjects
facultyworkinghours = {faculties[0]: faculties[12] for faculties in faculty}
assignedsubjects = {}
noassignment=[]
tbh=[]

def greedyassign():
    
    for subjectschedules in subjectschedule:
        assignedgreedy = False  
        subjectscheduleid = subjectschedules[0]
        subjectschedulesubjectname = subjectschedules[13]
        subjectschedulesubjecthours = subjectschedules[15]
        subjectschedulesubjectmasters = subjectschedules[17]
        subjectscheduledepartmentid = subjectschedules[10]

        if subjectscheduleid in assignedsubjects:
            continue

        for facultysubjects in facultysubject:
            facultysubjectfacultyid = facultysubjects[1]
            facultysubjectfsubjectname = facultysubjects[2]
            facultysubjectmasters = facultysubjects[11]
            facultysubjectdepartmentid = facultysubjects[13]

            if facultysubjectmatch(subjectschedulesubjectname, facultysubjectfsubjectname, 
                                   subjectschedulesubjectmasters, facultysubjectmasters, 
                                   subjectscheduledepartmentid, facultysubjectdepartmentid):
                if facultyworkinghourscheck(facultyworkinghours[facultysubjectfacultyid], 
                                            subjectschedulesubjecthours, facultysubjectfacultyid):
                
                    facultyworkinghours[facultysubjectfacultyid] -= subjectschedulesubjecthours
                    assignedsubjects[subjectscheduleid] = facultysubjectfacultyid
                    print(f"greedy assigned {facultysubjectfacultyid} to {subjectscheduleid}")
                    cursor.execute(f"UPDATE `subjectschedule` SET `facultyid` = {facultysubjectfacultyid} WHERE `id` = {subjectscheduleid}")
                    cursor.execute(f"UPDATE `faculty` SET `remainingteachinghours` = {facultyworkinghours[facultysubjectfacultyid]} WHERE `id` = {facultysubjectfacultyid}")
                    assignedgreedy = True  
                    break 
        if not assignedgreedy:
            noassignment.append(subjectscheduleid)


    # Commit the assignments
    conn.commit()

def assign_subject(currentshubjectid):
    
    if currentshubjectid >= len(subjectschedule):
        return True  

    subjectschedules = subjectschedule[currentshubjectid]
    subjectscheduleid = subjectschedules[0]
   
    subjectschedulesubjectname = subjectschedules[13]
    subjectschedulesubjecthours = subjectschedules[15]
    subjectschedulesubjectmasters = subjectschedules[17]
    subjectscheduledepartmentid = subjectschedules[10]

    for facultysubjects in facultysubject:
        facultysubjectfacultyid = facultysubjects[1]
        facultysubjectfsubjectname = facultysubjects[2]
        facultysubjectmasters = facultysubjects[11]
        facultysubjectdepartmentid = facultysubjects[13]
        print(subjectschedulesubjectname,facultysubjectfsubjectname)
        if facultysubjectmatch(subjectschedulesubjectname, facultysubjectfsubjectname, subjectschedulesubjectmasters, facultysubjectmasters, subjectscheduledepartmentid, facultysubjectdepartmentid):
            if facultyworkinghourscheck(facultyworkinghours[facultysubjectfacultyid], subjectschedulesubjecthours, facultysubjectfacultyid):
                
                facultyworkinghours[facultysubjectfacultyid] -= subjectschedulesubjecthours
                assignedsubjects[subjectscheduleid] = facultysubjectfacultyid
                workinghoursleft[facultysubjectfacultyid] = facultyworkinghours[facultysubjectfacultyid]
                print(f"Assigned {facultysubjectfacultyid} to {subjectscheduleid}")
                
                cursor.execute(f"UPDATE `subjectschedule` SET `facultyid` = {facultysubjectfacultyid} WHERE `id` = {subjectscheduleid}")
                cursor.execute(f"UPDATE `faculty` SET `remainingteachinghours` = {facultyworkinghours[facultysubjectfacultyid]} WHERE `id` = {facultysubjectfacultyid}")
                conn.commit()

                if assign_subject(currentshubjectid + 1):
                    
                    return True
                
                
                print(f"Backtracking subject {currentshubjectid}/{len(subjectschedule)}")
                facultyworkinghours[facultysubjectfacultyid] += subjectschedulesubjecthours
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
    print("No valid assignment found for all subjects. Applying Greedy Fallback.")
    
    # Apply Greedy Algorithm for unassigned subjects
    greedyassign()

    # After greedy fallback, check if some subjects are still unassigned
    if len(noassignment) > 0:
        # Apply final fallback (assign "TBH")
        print("Fallback failed, assigning 'TBH' to unassigned subjects.")
        for subjectscheduleid in set(noassignment):
            print(f"Assigning TBH to subject {subjectscheduleid}")
            cursor.execute(f"UPDATE `subjectschedule` SET `facultyid` = 21 WHERE `id` = {subjectscheduleid}")
        
        conn.commit()









conn.commit()









end_time = time.time()

total_time = end_time - start_time
print(f"Backtracking Algorithm")
print(f"Utilizing 2 processor")
print(f"8GB of RAM")
print(f"ran in {total_time:.2f} seconds")

cursor.close()
conn.close()
