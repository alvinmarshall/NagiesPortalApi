# SIMPLE SCHOOL REST API

This simple REST API was built without the use of any PHP framework
## Installation
first install dependencies
```bash
composer install
```

## REST EndPoints
You must login to obtain a token
####UserType
```bash
parent,teacher
```
###User Login
endpoint: http://examplehost.com/api/users/{userType}
```json
{
    "username": "example@me.com",
    "password": "pwd"
}
```
###Login Response
```json    
{
    "status": 200,
    "message": "Login Successful",
    "token": "eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3NjaG"
}
```
###All Students Response
endpoint: http://examplehost.com/api/students
```bash
{
  "type": "Students",
      "message": "Students Record",
      "count": 10,
      "students": [
          {
              "id": "00",
              "refNo": "reference number",
              "name": "example name",
              "indexNo": "index number",
              "dob": "2019-05-09",
              "gender": "Female",
              "admissionDate": "2019-01-16",
              "age": "1",
              "sectionName": "year",
              "facultyName": "SCHOOL",
              "semester": "Semester",
              "level": "level",
              "guardianName": "example",
              "guardianContact": "000 000 000",
              "image": "../location/filename.jpg"
          },
          {...}
          
      ]
}
```
###Student Response
endpoint: http://examplehost.com/api/students/{id}
```json
{
  "type": "Students",
      "message": "Student Record",
      "count": 1,
      "students": [
          {
              "id": "{id}",
              "refNo": "reference number",
              "name": "example name",
              "indexNo": "index number",
              "dob": "2019-05-09",
              "gender": "Female",
              "admissionDate": "2019-01-16",
              "age": "1",
              "sectionName": "year",
              "facultyName": "SCHOOL",
              "semester": "Semester",
              "level": "level",
              "guardianName": "example",
              "guardianContact": "000 000 000",
              "image": "../location/filename.jpg"
          }
      ]
}
```

###Assignment PDF
endpoint: http://examplehost.com/api/teachers/assignment_pdf
```json
{
    "type": "AssignmentPDF",
    "status": 200,
    "message": "Available Assignment PDF",
    "count": 1,
    "AssignmentPDF": [
        {
            "id": "1",
            "studentNo": "#1",
            "studentName": "example name",
            "teacherEmail": "example email",
            "reportFile": "../location/filename.pdf",
            "reportDate": "2019-05-24"
        }
    ]
}
```
