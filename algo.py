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

cursor.execute("SELECT * FROM `subjectschedule` JOIN subject ON subjectschedule.subjectid=subject.id")
subjectschedule = cursor.fetchall()

cursor.execute("SET FOREIGN_KEY_CHECKS = 0; UPDATE `subjectschedule` SET `facultyid` = NULL; SET FOREIGN_KEY_CHECKS = 1;", multi=True)
conn.commit()

workinghoursleft={}
def facultysubjectmatch(subjectschedulesubjectid, facultysubjectfsubjectid, subjectschedulesubjectmasters, facultysubjectmasters, subjectscheduledepartmentid, facultysubjectdepartmentid, subjectschedulesubjectfocus):
    print("mathc")
    return (subjectschedulesubjectfocus=='Major') and subjectschedulesubjectid == facultysubjectfsubjectid and (
        subjectschedulesubjectmasters == facultysubjectmasters or subjectschedulesubjectmasters == 'No'
    ) and (subjectscheduledepartmentid==facultysubjectdepartmentid or facultysubjectdepartmentid==3)
    
def facultyworkinghourscheck(facultyworkinghours, subjectschedulesubjecthours, subjectscheduleid, facultysubjectfacultyid):
    if facultyworkinghours>subjectschedulesubjecthours:
        print("mathc")
        return True
    else:
        print("not mathc")
        return False

for subjects in subject:
    subjectid = subjects[0]
    subjectcode = subjects[1]
    subjectname = subjects[2]
    subjectunit = subjects[3]
    subjecthours = subjects[4]
    subjecttype = subjects[5]
    subjectmasters = subjects[6]
    subjectfocus = subjects[7]

facultyworkinghours={}

for faculties in faculty:
    facultyid = faculties[0]
    facultyfname = faculties[1]
    facultymname = faculties[2]
    facultylname = faculties[3]
    facultygender = faculties[4]
    facultybday = faculties[5]
    facultycontactno = faculties[6]
    facultytype = faculties[7]
    facultymasters = faculties[8]
    facultyphd = faculties[9]
    facultydepartmentid = faculties[10]
    facultystartdate = faculties[11]
    facultyteachinghours = faculties[12]
    facultyworkinghours[facultyid]=facultyteachinghours

assignedsubjects={}



for subjectschedules in subjectschedule:
    subjectscheduleid = subjectschedules[0]
    subjectschedulesubjectid = subjectschedules[1]
    subjectschedulecalendarid = subjectschedules[2]
    subjectscheduleyearlvl = subjectschedules[3]
    subjectschedulesection = subjectschedules[4]
    subjectschedulefacultyid = subjectschedules[5]
    subjectscheduletimestart = subjectschedules[6]
    subjectscheduletimeend = subjectschedules[7]
    subjectscheduleday = subjectschedules[8]
    subjectscheduleroomid = subjectschedules[9]
    subjectscheduledepartmentid = subjectschedules[10]
    subjectschedulesubjectcode = subjectschedules[12]  
    subjectschedulesubjectname = subjectschedules[13] 
    subjectschedulesubjectunit = subjectschedules[14]
    subjectschedulesubjecthours= subjectschedules[15]
    subjectschedulesubjecttype = subjectschedules[16]  
    subjectschedulesubjectmasters = subjectschedules[17]  
    subjectschedulesubjectfocus = subjectschedules[18]  
    
    
    
    print(f"{subjectscheduleid}")
    
    for facultysubjects in facultysubject:

        facultysubjectid = facultysubjects[0]
        facultysubjectfacultyid = facultysubjects[1]
        facultysubjectfsubjectid = facultysubjects[2]
        facultysubjectfname = facultysubjects[4]
        facultysubjectmname = facultysubjects[5]
        facultysubjectlname = facultysubjects[6]
        facultysubjectgender = facultysubjects[7]
        facultysubjectbday = facultysubjects[8]
        facultysubjectcontactno = facultysubjects[9]
        facultysubjecttype = facultysubjects[10]
        facultysubjectmasters = facultysubjects[11]
        facultysubjectphd = facultysubjects[12]
        facultysubjectdepartmentid = facultysubjects[13]
        facultysubjectstartdate = facultysubjects[14]

       
        print(f"{facultysubjectid}")

        if subjectschedulesubjectfocus!="Major":
            print("Minor subject")
            
        time.sleep(0.001)
        
        if subjectscheduleid is not assignedsubjects:
            
            print("facultysubjectfacultyid")
            if facultysubjectmatch(subjectschedulesubjectid, facultysubjectfsubjectid, subjectschedulesubjectmasters, facultysubjectmasters, subjectscheduledepartmentid, facultysubjectdepartmentid,subjectschedulesubjectfocus):
                print(f"{subjectscheduleid} to assign {facultysubjectlname} {facultysubjectfsubjectid}")
                if facultyworkinghourscheck(facultyworkinghours[facultysubjectfacultyid], subjectschedulesubjecthours, subjectscheduleid, facultysubjectfacultyid):
                    facultyworkinghours[facultysubjectfacultyid]=facultyworkinghours[facultysubjectfacultyid]-subjectschedulesubjecthours
                    assignedsubjects[subjectscheduleid]=facultysubjectfacultyid
                    print(f"Faculty ID {facultysubjectfacultyid} has {facultyworkinghours[facultysubjectfacultyid]} hours available")
                    workinghoursleft[facultysubjectfacultyid]=facultyworkinghours[facultysubjectfacultyid]
                    print(f"Assigned {subjectscheduleid} to {facultysubjectfacultyid}")
                    cursor.execute(f"UPDATE `subjectschedule` SET `facultyid` = {facultysubjectfacultyid} WHERE `id` = {subjectscheduleid}")
                    cursor.execute(f"UPDATE `faculty` SET `remainingteachinghours` = {facultyworkinghours[facultysubjectfacultyid]} WHERE `id` = {facultysubjectfacultyid}")
                    conn.commit()
                    print(f"{subjectscheduleid} assigned to {facultysubjectlname}")
                    break
                else:
                    print(f"{facultysubjectfacultyid} No working hours left")
            else:
                print(f"{subjectscheduleid} not matching subject requirements")
        else:
            print(f"Subject {subjectscheduleid} assigned already")
            
                
'''print(assignedsubjects)'''
'''print(workinghoursleft)'''


end_time = time.time()

total_time = end_time - start_time
print(f"Greedy Algorithm")
print(f"Utilizing 2 processor")
print(f"4GB of RAM")
print(f"ran in {total_time:.2f} seconds")

cursor.close()
conn.close()

