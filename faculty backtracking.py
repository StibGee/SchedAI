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

# Fetching data
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
def assign_subject(currentshubjectid):
    
    if currentshubjectid >= len(subjectschedule):
        return True  # Base case: All subjects assigned successfully

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
                
                # Assign the faculty member to the subject schedule
                facultyworkinghours[facultysubjectfacultyid] -= subjectschedulesubjecthours
                assignedsubjects[subjectscheduleid] = facultysubjectfacultyid
                workinghoursleft[facultysubjectfacultyid] = facultyworkinghours[facultysubjectfacultyid]
                print(f"Assigned {facultysubjectfacultyid} to {subjectscheduleid}")
                
                cursor.execute(f"UPDATE `subjectschedule` SET `facultyid` = {facultysubjectfacultyid} WHERE `id` = {subjectscheduleid}")
                cursor.execute(f"UPDATE `faculty` SET `remainingteachinghours` = {facultyworkinghours[facultysubjectfacultyid]} WHERE `id` = {facultysubjectfacultyid}")
                conn.commit()

                # Recur to assign the next subject
                if assign_subject(currentshubjectid + 1):
                    return True

                # Backtrack if the assignment fails for the next subject
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
