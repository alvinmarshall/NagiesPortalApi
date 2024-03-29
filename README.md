# SIMPLE SCHOOL REST API

This simple REST API was built without the use of any PHP framework
## Installation
first install dependencies
```bash
composer install
```

## REST EndPoints
You must login to obtain a token
#### UserType
```bash
parent,teacher
```
### User Login
endpoint: http://examplehost.com/api/users/{userType}
##### method: POST
```json
{
    "username": "example@me.com",
    "password": "pwd"
}
```
### Login Response
```json    
{
    "status": 200,
    "message": "Login Successful",
    "token": "eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3NjaG"
}
```
### All Students Response
endpoint: http://examplehost.com/api/students
##### method: GET
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

### With Pagination Students Response
endpoint: http://examplehost.com/api/students/page/{2}
##### method: GET
```bash
{
    "type": "Students",
    "message": "Students Records",
    "count": 5,
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
             
    ],
    "paging": {
        "first": "/api/students/page=1",
        "pages": [
            {
                "page": 1,
                "url": "/api/students/page=1",
                "current_page": "no"
            },
            {
                "page": 2,
                "url": "/api/students/page=2",
                "current_page": "yes"
            },
            {
                "page": 3,
                "url": "/api/students/page=3",
                "current_page": "no"
            },
            {
                "page": 4,
                "url": "/api/students/page=4",
                "current_page": "no"
            }
        ],
        "last": "/api/students/page=199"
    }
}
```
### Student Response
endpoint: http://examplehost.com/api/students/{id}
##### method: GET
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

### Assignment PDF
endpoint: http://examplehost.com/api/teachers/assignment_pdf
##### method: GET
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
### Complaint Messages
endpoint: http://examplehost.com/api/teachers/complaints
##### method: GET
```json
{
    "type": "Complaints",
    "message": "Complaint Messages",
    "count": 1,
    "complaints": [
        {
            "studentNo": "#",
            "studentName": "example name",
            "level": "level",
            "guardianName": "example guardian",
            "guardianContact": "000",
            "teacherName": "example teacher",
            "message": "hi example ",
            "date": "0000-00-00"
        }
    ]
}
```
### Student Report
```bash
format {_pdf,_image}
```
endpoint: http://examplehost.com/api/students/report{format}
##### method: GET
```json
{
    "type": "Reports",
    "message": "Student Report",
    "count": 1,
    "report": [
        {
            "studentNo": "#",
            "studentName": "example name",
            "teacherEmail": "example teacher",
            "fileUrl": "../location/filename.pdf",
            "format": "pdf",
            "date": "0000-00-00"
        }
    ]
}
```
### Parent Complaints
endpoint: http://examplehost.com/api/students/complaints
##### method: POST
```json
{
	"sender":"example sender",
	"name":"example name",
	"content":"example content",
	"level":"level",
	"guardian":"example guardian",
	"contact":"0000",
	"date":"0000-00-00"
}
```
### Parent Complaint Response
```json    
{
    "type": "Complaints",
    "message": "your complaint is sent",
    "id": "#",
    "errors": []
}
```

### Send Assignment PDF
endpoint: http://examplehost.com/api/teachers/upload_assignment_pdf
##### method: POST
```bash
Use formdata to upload file, example in postman
set the key to pdf or set name attribute in html to pdf
```
### Send Assignment PDF Response
```json
{
    "type": "AssignmentPDF",
    "message": "File upload successful",
    "path": "/location/uploads/filename.pdf",
    "id": "#",
    "errors": null
}
```
### Send Assignment IMAGE
endpoint: http://examplehost.com/api/teachers/upload_assignment_image
##### method: POST
```bash
Use formdata to upload file, example in postman
set the key to image or set name attribute in html to image
```
### Send Assignment Image Response
```json
{
    "type": "AssignmentIMAGE",
    "message": "File upload successful",
    "format": "image",
    "path": "/location/uploads/filename.png",
    "id": "#",
    "errors": null
}
```